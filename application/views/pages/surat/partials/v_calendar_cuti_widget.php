<?php
$calendarWidgetId = isset($calendar_widget_id) ? $calendar_widget_id : 'calendar-cuti-widget';
$calendarModalId = isset($calendar_modal_id) ? $calendar_modal_id : 'modalDetailCutiWidget';
$calendarWidgetTitle = isset($calendar_widget_title) ? $calendar_widget_title : 'Kalender Cuti Tahunan Karyawan';
$calendarWidgetSubtitle = isset($calendar_widget_subtitle) ? $calendar_widget_subtitle : 'Klik nama pada kalender untuk melihat detail cuti.';
?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />

<style>
    .cuti-calendar-widget {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 18px;
        margin-bottom: 20px;
    }

    .cuti-calendar-widget .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #ececec;
    }

    .cuti-calendar-widget .calendar-title-wrap {
        flex: 1;
    }

    .cuti-calendar-widget .calendar-title {
        margin: 0;
        font-weight: 700;
        color: #2c3e50;
    }

    .cuti-calendar-widget .calendar-subtitle {
        margin: 4px 0 0 0;
        font-size: 12px;
        color: #6c757d;
    }

    .cuti-calendar-widget .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .cuti-calendar-widget .legend-item {
        display: flex;
        align-items: center;
        font-size: 11px;
    }

    .cuti-calendar-widget .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        margin-right: 5px;
    }

    .cuti-calendar-widget .calendar-root {
        min-height: 500px;
    }

    .cuti-calendar-widget .fc-toolbar-title {
        font-size: 1.1rem !important;
        font-weight: 700;
    }

    .cuti-calendar-widget .fc-event {
        cursor: pointer;
        font-size: 11px;
        border-radius: 3px;
    }

    .cuti-calendar-widget .fc-daygrid-event {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (max-width: 768px) {
        .cuti-calendar-widget .calendar-header {
            flex-direction: column;
        }

        .cuti-calendar-widget .calendar-root {
            min-height: 380px;
        }
    }
</style>

<div class="cuti-calendar-widget">
    <div class="calendar-header">
        <div class="calendar-title-wrap">
            <h5 class="calendar-title"><i class="fas fa-calendar-alt text-primary"></i> <?= $calendarWidgetTitle ?></h5>
            <p class="calendar-subtitle"><?= $calendarWidgetSubtitle ?></p>
        </div>
        <div class="calendar-legend">
            <div class="legend-item">
                <span class="legend-color" style="background-color: #ffc107;"></span>
                <span>Baru</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: #17a2b8;"></span>
                <span>GA</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: #007bff;"></span>
                <span>HR</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: #28a745;"></span>
                <span>Disetujui</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: #dc3545;"></span>
                <span>Ditolak</span>
            </div>
        </div>
    </div>
    <div id="<?= $calendarWidgetId ?>" class="calendar-root"></div>
</div>

<div class="modal fade" id="<?= $calendarModalId ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> Detail Cuti</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div><strong>Kode Cuti:</strong> <span id="<?= $calendarWidgetId ?>-kode">-</span></div>
                <div><strong>Nama:</strong> <span id="<?= $calendarWidgetId ?>-nama">-</span></div>
                <div><strong>Perusahaan:</strong> <span id="<?= $calendarWidgetId ?>-perusahaan">-</span></div>
                <div><strong>Jabatan:</strong> <span id="<?= $calendarWidgetId ?>-jabatan">-</span></div>
                <div><strong>Divisi:</strong> <span id="<?= $calendarWidgetId ?>-divisi">-</span></div>
                <div><strong>Tanggal:</strong> <span id="<?= $calendarWidgetId ?>-tanggal">-</span></div>
                <div><strong>Total Hari:</strong> <span id="<?= $calendarWidgetId ?>-total">-</span></div>
                <div><strong>Alasan:</strong> <span id="<?= $calendarWidgetId ?>-alasan">-</span></div>
                <div><strong>Status:</strong> <span id="<?= $calendarWidgetId ?>-status">-</span></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
                    '<span class="badge badge-success" style="margin-right: 4px; font-size: 9px; padding: 1px 3px; vertical-align: middle; color: #fff; background-color: #28a745;">SGM</span>' :
                    '<span class="badge badge-primary" style="margin-right: 4px; font-size: 9px; padding: 1px 3px; vertical-align: middle; color: #fff; background-color: #007bff;">VYM</span>';
                return {
                    html: '<div style="display: flex; align-items: center; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' +
                        badgeHtml +
                        '<span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 500;">' + arg.event.title + '</span>' +
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
                    $('#<?= $calendarWidgetId ?>-perusahaan').html('<span class="badge badge-success">SGM</span>');
                } else {
                    $('#<?= $calendarWidgetId ?>-perusahaan').html('<span class="badge badge-primary">VYM</span>');
                }

                $('#<?= $calendarWidgetId ?>-jabatan').text(props.jabatan || '-');
                $('#<?= $calendarWidgetId ?>-divisi').text(props.divisi || '-');
                $('#<?= $calendarWidgetId ?>-tanggal').text((props.tgl_awal || '-') + ' s/d ' + (props.tgl_akhir || '-'));
                $('#<?= $calendarWidgetId ?>-total').text((props.total_hari || '0') + ' Hari');
                $('#<?= $calendarWidgetId ?>-alasan').text(props.alasan || '-');

                var statusClass = 'badge-secondary';
                if (props.status_code == 0) statusClass = 'badge-warning';
                else if (props.status_code == 1) statusClass = 'badge-info';
                else if (props.status_code == 2) statusClass = props.is_sgm ? 'badge-success' : 'badge-primary';
                else if (props.status_code == 3) statusClass = 'badge-success';
                else if (props.status_code == 4 || props.status_code == 5 || props.status_code == 6 || props.status_code == 7) statusClass = 'badge-danger';

                $('#<?= $calendarWidgetId ?>-status').html('<span class="badge ' + statusClass + '">' + (props.status || '-') + '</span>');
                $(modalSelector).modal('show');
            },
            loading: function(isLoading) {
                calendarEl.style.opacity = isLoading ? '0.6' : '1';
            }
        });

        calendar.render();
    });
</script>