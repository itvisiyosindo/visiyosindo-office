<?php
$calendarWidgetId = isset($calendar_widget_id) ? $calendar_widget_id : 'calendar-cuti-widget';
$calendarModalId = isset($calendar_modal_id) ? $calendar_modal_id : 'modalDetailCutiWidget';
$calendarWidgetTitle = isset($calendar_widget_title) ? $calendar_widget_title : 'Kalender Cuti Tahunan Karyawan';
$calendarWidgetSubtitle = isset($calendar_widget_subtitle) ? $calendar_widget_subtitle : 'Klik nama pada kalender untuk melihat detail cuti.';
?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />

<style>
    .cuti-calendar-widget {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 20px;
        box-shadow: 0 4px 24px -2px rgba(15, 23, 42, 0.06);
        padding: 24px;
        margin-bottom: 24px;
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .cuti-calendar-widget .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 18px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .cuti-calendar-widget .calendar-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cuti-calendar-widget .calendar-icon-badge {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .cuti-calendar-widget .calendar-title {
        margin: 0;
        font-weight: 700;
        font-size: 1.15rem;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .cuti-calendar-widget .calendar-subtitle {
        margin: 2px 0 0 0;
        font-size: 0.82rem;
        color: #64748b;
    }

    .cuti-calendar-widget .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }

    .cuti-calendar-widget .legend-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid transparent;
        transition: transform 0.2s ease;
    }

    .cuti-calendar-widget .legend-chip:hover {
        transform: translateY(-1px);
    }

    .cuti-calendar-widget .legend-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    .cuti-calendar-widget .chip-baru { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .cuti-calendar-widget .chip-baru .legend-dot { background: #f59e0b; }

    .cuti-calendar-widget .chip-ga { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
    .cuti-calendar-widget .chip-ga .legend-dot { background: #0284c7; }

    .cuti-calendar-widget .chip-hr { background: #ede9fe; color: #5b21b6; border-color: #ddd6fe; }
    .cuti-calendar-widget .chip-hr .legend-dot { background: #7c3aed; }

    .cuti-calendar-widget .chip-setuju { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }
    .cuti-calendar-widget .chip-setuju .legend-dot { background: #16a34a; }

    .cuti-calendar-widget .chip-tolak { background: #fee2e2; color: #991b1b; border-color: #fecaca; }
    .cuti-calendar-widget .chip-tolak .legend-dot { background: #dc2626; }

    /* Calendar Base */
    .cuti-calendar-widget .calendar-root {
        min-height: 540px;
    }

    /* FullCalendar Toolbar & Buttons */
    .cuti-calendar-widget .fc-toolbar {
        margin-bottom: 18px !important;
    }

    .cuti-calendar-widget .fc-toolbar-title {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        letter-spacing: -0.01em;
    }

    .cuti-calendar-widget .fc-button {
        background: #ffffff !important;
        color: #334155 !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 10px !important;
        font-size: 0.84rem !important;
        font-weight: 600 !important;
        padding: 6px 14px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
        transition: all 0.2s ease !important;
        text-transform: capitalize !important;
    }

    .cuti-calendar-widget .fc-button:hover {
        background: #f8fafc !important;
        border-color: #94a3b8 !important;
        color: #0f172a !important;
    }

    .cuti-calendar-widget .fc-button:focus,
    .cuti-calendar-widget .fc-button:active {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
    }

    .cuti-calendar-widget .fc-button-active {
        background: #0f172a !important;
        color: #ffffff !important;
        border-color: #0f172a !important;
    }

    .cuti-calendar-widget .fc-button-group {
        border-radius: 10px;
        overflow: hidden;
    }

    .cuti-calendar-widget .fc-today-button {
        background: #eff6ff !important;
        color: #2563eb !important;
        border-color: #bfdbfe !important;
    }

    .cuti-calendar-widget .fc-today-button:hover {
        background: #2563eb !important;
        color: #ffffff !important;
        border-color: #2563eb !important;
    }

    .cuti-calendar-widget .fc-today-button:disabled {
        opacity: 0.5 !important;
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        border-color: #e2e8f0 !important;
    }

    /* FullCalendar Table Grid Styling */
    .cuti-calendar-widget .fc-theme-standard th {
        background: #f8fafc !important;
        padding: 10px 0 !important;
        font-size: 0.76rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: #64748b !important;
        border: 1px solid #f1f5f9 !important;
    }

    .cuti-calendar-widget .fc-theme-standard td {
        border: 1px solid #f1f5f9 !important;
        transition: background-color 0.2s ease;
    }

    .cuti-calendar-widget .fc-daygrid-day-number {
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        color: #475569 !important;
        padding: 6px 8px !important;
    }

    /* Weekend Column Background Tint */
    .cuti-calendar-widget .fc-day-sat,
    .cuti-calendar-widget .fc-day-sun {
        background-color: #fafbfc !important;
    }

    /* Today Highlight */
    .cuti-calendar-widget .fc-day-today {
        background-color: rgba(59, 130, 246, 0.04) !important;
    }

    .cuti-calendar-widget .fc-day-today .fc-daygrid-day-number {
        background: #2563eb;
        color: #ffffff !important;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 4px;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35);
    }

    /* Event Badges */
    .cuti-calendar-widget .fc-event {
        cursor: pointer;
        border: none !important;
        border-radius: 6px !important;
        padding: 3px 6px !important;
        margin: 2px 3px !important;
        font-size: 11px !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .cuti-calendar-widget .fc-event:hover {
        transform: translateY(-1px) scale(1.01);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .cuti-calendar-widget .fc-daygrid-event {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cuti-event-chip {
        display: flex;
        align-items: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .cuti-company-badge {
        font-size: 9px;
        font-weight: 700;
        padding: 1px 4px;
        border-radius: 4px;
        margin-right: 5px;
        letter-spacing: 0.02em;
        background: rgba(255, 255, 255, 0.28);
        color: #ffffff;
        flex-shrink: 0;
    }

    .cuti-event-title {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 600;
        color: #ffffff;
    }

    /* Detail Modal Modern Styling */
    .modern-cuti-modal .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.2);
        overflow: hidden;
    }

    .modern-cuti-modal .modal-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        padding: 18px 24px;
        border: none;
    }

    .modern-cuti-modal .modal-title {
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modern-cuti-modal .modal-body {
        padding: 24px;
        background: #f8fafc;
    }

    .cuti-detail-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
        background: #ffffff;
        padding: 18px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
    }

    .cuti-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px dashed #f1f5f9;
        font-size: 0.88rem;
    }

    .cuti-detail-row:last-child {
        border-bottom: none;
    }

    .cuti-detail-label {
        color: #64748b;
        font-weight: 500;
    }

    .cuti-detail-value {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
    }

    .modern-cuti-modal .modal-footer {
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        padding: 14px 24px;
    }

    @media (max-width: 768px) {
        .cuti-calendar-widget {
            padding: 16px;
        }

        .cuti-calendar-widget .calendar-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .cuti-calendar-widget .calendar-root {
            min-height: 420px;
        }
    }
</style>

<div class="cuti-calendar-widget">
    <div class="calendar-header">
        <div class="calendar-title-wrap">
            <div class="calendar-icon-badge">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <h5 class="calendar-title"><?= $calendarWidgetTitle ?></h5>
                <p class="calendar-subtitle"><?= $calendarWidgetSubtitle ?></p>
            </div>
        </div>
        <div class="calendar-legend">
            <span class="legend-chip chip-baru">
                <span class="legend-dot"></span> Baru
            </span>
            <span class="legend-chip chip-ga">
                <span class="legend-dot"></span> GA
            </span>
            <span class="legend-chip chip-hr">
                <span class="legend-dot"></span> HR
            </span>
            <span class="legend-chip chip-setuju">
                <span class="legend-dot"></span> Disetujui
            </span>
            <span class="legend-chip chip-tolak">
                <span class="legend-dot"></span> Ditolak
            </span>
        </div>
    </div>
    <div id="<?= $calendarWidgetId ?>" class="calendar-root"></div>
</div>

<div class="modal fade modern-cuti-modal" id="<?= $calendarModalId ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title"><i class="fas fa-calendar-check text-primary"></i> Detail Permohonan Cuti</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="cuti-detail-grid">
                    <div class="cuti-detail-row">
                        <span class="cuti-detail-label">Kode Cuti</span>
                        <span class="cuti-detail-value" id="<?= $calendarWidgetId ?>-kode">-</span>
                    </div>
                    <div class="cuti-detail-row">
                        <span class="cuti-detail-label">Nama Karyawan</span>
                        <span class="cuti-detail-value" id="<?= $calendarWidgetId ?>-nama">-</span>
                    </div>
                    <div class="cuti-detail-row">
                        <span class="cuti-detail-label">Perusahaan</span>
                        <span class="cuti-detail-value" id="<?= $calendarWidgetId ?>-perusahaan">-</span>
                    </div>
                    <div class="cuti-detail-row">
                        <span class="cuti-detail-label">Jabatan & Divisi</span>
                        <span class="cuti-detail-value">
                            <span id="<?= $calendarWidgetId ?>-jabatan">-</span>
                            <span class="text-muted" style="font-weight: 400;"> • </span>
                            <span id="<?= $calendarWidgetId ?>-divisi" class="text-muted">-</span>
                        </span>
                    </div>
                    <div class="cuti-detail-row">
                        <span class="cuti-detail-label">Periode Tanggal</span>
                        <span class="cuti-detail-value text-primary" id="<?= $calendarWidgetId ?>-tanggal">-</span>
                    </div>
                    <div class="cuti-detail-row">
                        <span class="cuti-detail-label">Total Cuti</span>
                        <span class="cuti-detail-value" id="<?= $calendarWidgetId ?>-total">-</span>
                    </div>
                    <div class="cuti-detail-row">
                        <span class="cuti-detail-label">Alasan Cuti</span>
                        <span class="cuti-detail-value" id="<?= $calendarWidgetId ?>-alasan">-</span>
                    </div>
                    <div class="cuti-detail-row">
                        <span class="cuti-detail-label">Status Verifikasi</span>
                        <span class="cuti-detail-value" id="<?= $calendarWidgetId ?>-status">-</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('<?= $calendarWidgetId ?>');
        if (!calendarEl || typeof FullCalendar === 'undefined') {
            return;
        }

        var modalSelector = '#<?= $calendarModalId ?>';

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu'
            },
            eventContent: function(arg) {
                var is_sgm = arg.event.extendedProps.is_sgm;
                var badgeHtml = is_sgm ?
                    '<span class="cuti-company-badge">SGM</span>' :
                    '<span class="cuti-company-badge">VYM</span>';
                return {
                    html: '<div class="cuti-event-chip">' +
                        badgeHtml +
                        '<span class="cuti-event-title">' + arg.event.title + '</span>' +
                        '</div>'
                };
            },
            events: function(info, successCallback, failureCallback) {
                var viewYear = new Date((info.start.getTime() + info.end.getTime()) / 2).getFullYear();

                $.ajax({
                    url: '<?= base_url('surat_part_two/getCalendarCuti') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        year: viewYear
                    },
                    success: function(response) {
                        if (Array.isArray(response)) {
                            successCallback(response);
                        } else {
                            successCallback([]);
                        }
                    },
                    error: function() {
                        failureCallback();
                    }
                });
            },
            eventDidMount: function(info) {
                if (info.event && info.event.extendedProps) {
                    $(info.el).attr('title', info.event.extendedProps.nama_karyawan + ' (' + info.event.extendedProps.total_hari + ' hari)');
                }
            },
            eventClick: function(info) {
                var props = info.event.extendedProps || {};

                $('#<?= $calendarWidgetId ?>-kode').text(props.kode_cuti || '-');
                $('#<?= $calendarWidgetId ?>-nama').text(props.nama_karyawan || '-');

                // Set Perusahaan badge (VYM = Blue, SGM = Green)
                if (props.is_sgm) {
                    $('#<?= $calendarWidgetId ?>-perusahaan').html('<span class="badge badge-success px-2 py-1" style="border-radius: 6px;">SGM</span>');
                } else {
                    $('#<?= $calendarWidgetId ?>-perusahaan').html('<span class="badge badge-primary px-2 py-1" style="border-radius: 6px;">VYM</span>');
                }

                $('#<?= $calendarWidgetId ?>-jabatan').text(props.jabatan || '-');
                $('#<?= $calendarWidgetId ?>-divisi').text(props.divisi || '-');
                $('#<?= $calendarWidgetId ?>-tanggal').text((props.tgl_awal || '-') + ' s/d ' + (props.tgl_akhir || '-'));
                $('#<?= $calendarWidgetId ?>-total').text((props.total_hari || '0') + ' Hari');
                $('#<?= $calendarWidgetId ?>-alasan').text(props.alasan || '-');

                var statusBadge = '<span class="badge badge-secondary px-2 py-1" style="border-radius: 6px;">' + (props.status || '-') + '</span>';
                if (props.status_code == 0) statusBadge = '<span class="legend-chip chip-baru"><span class="legend-dot"></span> Baru</span>';
                else if (props.status_code == 1) statusBadge = '<span class="legend-chip chip-ga"><span class="legend-dot"></span> Verifikasi GA</span>';
                else if (props.status_code == 2) statusBadge = '<span class="legend-chip chip-hr"><span class="legend-dot"></span> Verifikasi HR</span>';
                else if (props.status_code == 3) statusBadge = '<span class="legend-chip chip-setuju"><span class="legend-dot"></span> Disetujui Penuh</span>';
                else if (props.status_code >= 4) statusBadge = '<span class="legend-chip chip-tolak"><span class="legend-dot"></span> Ditolak</span>';

                $('#<?= $calendarWidgetId ?>-status').html(statusBadge);
                $(modalSelector).modal('show');
            },
            loading: function(isLoading) {
                calendarEl.style.opacity = isLoading ? '0.6' : '1';
            }
        });

        calendar.render();
    });
</script>