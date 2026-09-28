<?php
$role_nav_selected = isset($role_nav_selected) ? $role_nav_selected : 'pengaju';
$can_access_approval_menu = isset($can_access_approval_menu) ? $can_access_approval_menu : false;
$approver_name = !empty($approver) && !empty($approver->nama) ? $approver->nama : 'Dirangga Madali';
$approver_jabatan = !empty($approver) && !empty($approver->jabatan) ? $approver->jabatan : 'Head of Accounting and Tax';
$approver_backup_name = !empty($approver_backup) && !empty($approver_backup->nama) ? $approver_backup->nama : 'Head of Accounting and Tax';
$approver_backup_jabatan = !empty($approver_backup) && !empty($approver_backup->jabatan) ? $approver_backup->jabatan : 'Head of Accounting and Tax';
$approver_label = $approver_name . ' | ' . $approver_jabatan . ' / ' . $approver_backup_name . ' | ' . $approver_backup_jabatan;
?>

<style>
    .lta-detail-wrap {
        display: grid;
        gap: 16px;
    }

    .lta-detail-top {
        background: linear-gradient(120deg, #0f4c5c, #2c7da0);
        color: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .lta-detail-top h3 {
        margin: 0 0 5px 0;
        color: #fff;
        font-weight: 700;
    }

    .lta-detail-top p {
        margin: 0;
        opacity: .98;
        color: #e8f1f8;
    }

    .lta-detail-grid {
        display: grid;
        gap: 16px;
        grid-template-columns: 1fr 1fr;
        min-width: 0;
    }

    .lta-detail-col {
        display: grid;
        gap: 16px;
        min-width: 0;
    }

    .lta-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        min-width: 0;
    }

    .lta-card h4 {
        color: #1a2332;
        margin-bottom: 14px;
        font-weight: 700;
    }

    .lta-table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .lta-table-scroll table {
        min-width: 760px;
    }

    .lta-table-scroll th,
    .lta-table-scroll td {
        white-space: nowrap;
    }

    @media (max-width: 992px) {
        .lta-detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .lta-card {
            padding: 14px;
        }

        .lta-detail-top {
            padding: 14px;
        }
    }

    @media (max-width: 576px) {
        .lta-detail-wrap {
            gap: 14px;
        }

        .lta-form-actions {
            text-align: left;
        }

        .lta-form-actions .btn {
            width: 100%;
        }

        .lta-table-scroll,
        .lta-card,
        .lta-detail-col {
            max-width: 100%;
        }

        .lta-table-scroll table,
        .lta-table-scroll .table,
        .lta-table-scroll .dataTable {
            min-width: 0 !important;
            width: 100% !important;
        }

        .select2-container {
            width: 100% !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            float: none;
            text-align: left;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 100%;
            margin-left: 0;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin-top: 8px;
        }
    }
</style>

<header class="page-header">
    <h2><i class="icons icon-user-follow"></i>&nbsp;Lumpsum, Akomodasi & Transportasi</h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Form Pengajuan Lumpsum, Akomodasi & Transportasi</span></li>
        </ol>
    </div>
</header>

<div class="lta-detail-wrap">
    <div class="lta-detail-top">
        <h3>Pengajuan Lumpsum, Akomodasi & Transportasi</h3>
        <p>Form ini digunakan oleh pengaju untuk mengisi biaya transportasi udara/darat, hari dinas, dan lampiran. Total biaya akan dihitung otomatis sesuai rumus bisnis.</p>
    </div>


    <div class="lta-detail-grid">
        <div class="lta-detail-col">
            <div class="lta-card">
                <h4><i class="bx bx-edit"></i> Form Pengajuan</h4>
                <p style="margin-bottom:12px;color:#2d3748;">Silakan isi form pengajuan. Kolom bertanda * wajib diisi.</p>
                <div class="alert alert-info" style="margin-bottom:14px;">
                    <i class="bx bx-info-circle"></i> Jika salah satu biaya transportasi tidak ada, isi nominalnya dengan 0. Minimal salah satu (udara atau darat) wajib lebih dari 0.
                </div>
                <?= form_open('#', ['id' => 'form-lta', 'autocomplete' => 'off']); ?>
                <div class="form-group">
                    <label for="nominal_udara">Biaya Transportasi Udara (Nominal) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control lta-nominal-display" id="nominal_udara_display" placeholder="0" data-target="nominal_udara">
                        <input type="hidden" id="nominal_udara" name="nominal_udara">
                    </div>
                </div>

                <div class="form-group">
                    <label for="link_lampiran_udara">Link Lampiran Transportasi Udara <span class="text-danger">*</span></label>
                    <input type="url" class="form-control" id="link_lampiran_udara" name="link_lampiran_udara" placeholder="https://...">
                </div>

                <div class="form-group">
                    <label for="hari_dinas">Hari Dinas <span class="text-danger">*</span></label>
                    <input type="number" min="1" max="60" class="form-control" id="hari_dinas" name="hari_dinas" value="1">
                </div>

                <div class="form-group">
                    <label for="nominal_darat">Biaya Transportasi Darat (Nominal)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control lta-nominal-display" id="nominal_darat_display" placeholder="0" data-target="nominal_darat">
                        <input type="hidden" id="nominal_darat" name="nominal_darat">
                    </div>
                </div>

                <div class="form-group">
                    <label for="link_lampiran_darat">Link Lampiran Transportasi Darat</label>
                    <input type="url" class="form-control" id="link_lampiran_darat" name="link_lampiran_darat" placeholder="https://...">
                </div>

                <div class="form-group">
                    <label for="catatan_pengaju">Catatan Pengaju</label>
                    <textarea class="form-control" id="catatan_pengaju" name="catatan_pengaju" rows="2" placeholder="Opsional"></textarea>
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="customer_select" class="mb-0">Nama Customer</label>
                        <div class="form-check form-check-inline" style="margin-right: 0;">
                            <input class="form-check-input" type="checkbox" id="customer_manual_check" style="cursor:pointer;">
                            <label class="form-check-label text-muted" for="customer_manual_check" style="cursor:pointer; font-size: 0.85rem; font-weight: normal; margin-bottom: 0;">Input Manual</label>
                        </div>
                    </div>
                    <select id="customer_select" class="form-control" style="width:100%;"></select>
                    <input type="text" class="form-control d-none" id="customer_name_manual" placeholder="Masukkan Nama Customer secara manual">
                    <input type="hidden" id="customer_id" name="customer_id">
                    <input type="hidden" id="customer_name" name="customer_name">
                </div>

                <div class="form-group">
                    <label for="customer_address">Alamat Customer</label>
                    <textarea class="form-control" id="customer_address" name="customer_address" rows="2" placeholder="Alamat akan terisi otomatis jika customer dipilih"></textarea>
                    <div class="alert alert-warning mt-2 d-none" id="alert-manual-address" style="padding: 8px 12px; font-size: 0.85rem; margin-bottom: 0;">
                        <i class="bx bx-error-circle"></i> Alamat Customer wajib diisi untuk kerapian data ketika menginput secara manual!
                    </div>
                    <small id="customer-info-msg" class="form-text text-muted" style="display:none;margin-top:8px;padding:8px;background:#f0f7fc;border-left:3px solid #0066cc;border-radius:4px;">
                        <i class="bx bx-info-circle"></i> Belum ada customer yang cocok? <a href="<?= base_url('pelanggan') ?>" target="_blank" rel="noopener noreferrer">Tambah customer baru di sini</a>
                    </small>
                </div>

                <div class="form-group">
                    <label for="teknisi_select">Nama Teknisi</label>
                    <select id="teknisi_select" class="form-control" style="width:100%;"></select>
                    <input type="hidden" id="teknisi_id" name="teknisi_id">
                </div>

                <div class="text-right mt-3 lta-form-actions">
                    <button type="button" class="btn btn-primary" id="btn-submit-lta">
                        <i class="bx bx-send"></i> Submit Pengajuan
                    </button>
                </div>
            </div>
        </div>
        <div class="lta-detail-col">
            <div class="lta-card">
                <h4><i class="bx bx-world"></i> Referensi Harga Transportasi</h4>
                <p style="margin-bottom:14px;color:#2d3748;font-size:0.9rem;">Cari referensi harga transportasi untuk lampiran pengajuan Anda.</p>

                <div style="margin-bottom:14px;">
                    <div style="background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:12px;margin-bottom:10px;">
                        <p style="margin:0 0 8px 0;font-weight:600;color:#1a2332;font-size:0.95rem;">✈️ Transportasi Udara:</p>
                        <div style="display:grid;gap:8px;">
                            <a href="https://www.tiket.com/id-id" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary" style="justify-content:flex-start;padding:8px 12px;font-size:0.9rem;">
                                <i class="bx bx-link-external"></i> Tiket.com
                            </a>
                            <a href="https://www.traveloka.com/id-id/tiket-pesawat" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary" style="justify-content:flex-start;padding:8px 12px;font-size:0.9rem;">
                                <i class="bx bx-link-external"></i> Traveloka
                            </a>
                            <a href="https://id.trip.com/flights/?locale=id-ID&curr=IDR" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary" style="justify-content:flex-start;padding:8px 12px;font-size:0.9rem;">
                                <i class="bx bx-link-external"></i> Trip.com
                            </a>
                        </div>
                    </div>

                    <div style="background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:12px;">
                        <p style="margin:0 0 8px 0;font-weight:600;color:#1a2332;font-size:0.95rem;">🚗 Transportasi Darat:</p>
                        <div style="display:grid;gap:8px;">
                            <a href="https://www.gojek.com/" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success" style="justify-content:flex-start;padding:8px 12px;font-size:0.9rem;">
                                <i class="bx bx-link-external"></i> GoJek / Grab
                            </a>
                            <a href="https://www.blibli.com/" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success" style="justify-content:flex-start;padding:8px 12px;font-size:0.9rem;">
                                <i class="bx bx-link-external"></i> Blibli Travel
                            </a>
                            <a href="https://www.traveloka.com/" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success" style="justify-content:flex-start;padding:8px 12px;font-size:0.9rem;">
                                <i class="bx bx-link-external"></i> Traveloka Bus/Rental
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lta-card">
                <h4><i class="bx bx-history"></i> Pengajuan Saya</h4>
                <div style="font-size:0.9rem;color:#6c757d;margin-bottom:10px">Ringkasan pengajuan Anda & riwayat terbaru</div>
                <div class="table-responsive lta-table-scroll" style="max-height:600px;overflow-y:auto;">
                    <table id="table-my-lta" class="table table-bordered table-hover table-sm" style="min-width:800px;">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Kode</th>
                                <th width="11%">Hari Dinas</th>
                                <th width="14%">Transport Udara</th>
                                <th width="14%">Transport Darat</th>
                                <th width="14%">Approver</th>
                                <th width="12%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($my_submissions)): ?>
                                <?php $no = 1;
                                foreach ($my_submissions as $row): ?>
                                    <?php
                                    if ((int) $row->status === 5) {
                                        $status_badge = '<div class="lta-status-badge approved"><i class="bx bx-check-circle"></i> Disetujui</div>';
                                    } elseif ((int) $row->status === 99) {
                                        $status_badge = '<div class="lta-status-badge rejected"><i class="bx bx-x-circle"></i> Ditolak</div>';
                                    } else {
                                        $status_badge = '<div class="lta-status-badge pending"><i class="bx bx-hourglass-mid"></i> Menunggu</div>';
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row->kode_pengajuan) ?></td>
                                        <td class="text-center"><?= (int) $row->hari_dinas ?></td>
                                        <td class="text-right">Rp <?= number_format((int) $row->nominal_udara, 0, ',', '.') ?></td>
                                        <td class="text-right">Rp <?= number_format((int) $row->nominal_darat, 0, ',', '.') ?></td>
                                        <td><?= !empty($row->nama_approver) ? htmlspecialchars($row->nama_approver) : '-' ?></td>
                                        <td><?= $status_badge ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('lta_pengajuan/show/detail/' . encrypt($row->id)) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toNumber(value) {
            var normalized = String(value || '').replace(/[^0-9]/g, '');
            return normalized ? parseInt(normalized, 10) : 0;
        }

        function formatRupiahDisplay(value) {
            var num = toNumber(value);
            return num > 0 ? (num).toLocaleString('id-ID') : '';
        }

        function formatRupiahNumber(value) {
            return 'Rp ' + (Number(value || 0)).toLocaleString('id-ID');
        }

        // Format nominal inputs on input event
        function setupNominalFormatters() {
            $(document).on('input', '.lta-nominal-display', function() {
                var displayVal = $(this).val();
                var numVal = toNumber(displayVal);
                var formattedDisplay = formatRupiahDisplay(numVal);
                $(this).val(formattedDisplay);
                $('#' + $(this).data('target')).val(numVal);
            });
        }

        function hitungPreview() {
            var udara = toNumber($('#nominal_udara').val());
            var darat = toNumber($('#nominal_darat').val());
            var hari = parseInt($('#hari_dinas').val(), 10);
            if (Number.isNaN(hari) || hari < 1) {
                hari = 1;
            }

            var udara20 = Math.round(udara * 0.2);
            var penginapan = Math.max(hari - 1, 0) * 500000;
            var makan = hari * 115000;

            var total = udara + udara20 + darat + 500000 + 500000 + 20000 + penginapan + makan + 100000;

            $('#preview_udara').text(formatRupiahNumber(udara + udara20));
            $('#preview_darat').text(formatRupiahNumber(darat));
            $('#preview_penginapan').text(formatRupiahNumber(penginapan));
            $('#preview_makan').text(formatRupiahNumber(makan));
            $('#preview_total').text(formatRupiahNumber(total));
        }

        document.addEventListener('DOMContentLoaded', function() {
            setupNominalFormatters();

            // Toggle customer input manual
            $('#customer_manual_check').on('change', function() {
                var checked = $(this).is(':checked');
                if (checked) {
                    $('#customer_select').val(null).trigger('change');
                    $('#customer_select').next('.select2-container').hide();
                    $('#customer_name_manual').removeClass('d-none');

                    $('#customer_id').val('');
                    $('#customer_name').val('');

                    $('#alert-manual-address').removeClass('d-none');
                    $('#customer-info-msg').hide();
                } else {
                    $('#customer_select').next('.select2-container').show();
                    $('#customer_name_manual').addClass('d-none').val('');

                    $('#customer_id').val('');
                    $('#customer_name').val('');
                    $('#customer_address').val('');

                    $('#alert-manual-address').addClass('d-none');
                }
            });

            // Sinkronisasi manual name input
            $('#customer_name_manual').on('input', function() {
                $('#customer_name').val($(this).val());
            });

            if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#table-my-lta')) {
                $('#table-my-lta').DataTable({
                    pageLength: 10,
                    autoWidth: false,
                    order: [],
                    language: {
                        search: 'Cari:',
                        searchPlaceholder: 'Kode / Status / Approver',
                        emptyTable: 'Belum ada pengajuan.',
                        zeroRecords: 'Data tidak ditemukan.',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                        infoEmpty: 'Menampilkan 0 data',
                        paginate: {
                            first: '<<',
                            last: '>>',
                            next: '>',
                            previous: '<'
                        }
                    }
                });
            }

            hitungPreview();
            $(document).on('input', '#nominal_udara, #nominal_darat, #hari_dinas', hitungPreview);

            $(document).on('click', '#btn-submit-lta', function() {
                var nominalUdara = toNumber($('#nominal_udara_display').val());
                var nominalDarat = toNumber($('#nominal_darat_display').val());
                var hariDinas = parseInt($('#hari_dinas').val(), 10);
                var linkUdara = $('#link_lampiran_udara').val().trim();

                var isManual = $('#customer_manual_check').is(':checked');
                var customerName = isManual ? $('#customer_name_manual').val().trim() : $('#customer_name').val().trim();
                var customerAddress = $('#customer_address').val().trim();

                if (isManual) {
                    if (!customerName) {
                        Swal.fire('Validasi', 'Nama Customer wajib diisi jika memilih Input Manual.', 'warning');
                        return;
                    }
                    if (!customerAddress) {
                        Swal.fire('Validasi', 'Alamat Customer wajib diisi jika memilih Input Manual.', 'warning');
                        return;
                    }
                }

                if (nominalUdara <= 0 && nominalDarat <= 0) {
                    Swal.fire('Validasi', 'Minimal salah satu nominal transportasi (udara atau darat) wajib lebih dari 0.', 'warning');
                    return;
                }

                if (!hariDinas || hariDinas < 1) {
                    Swal.fire('Validasi', 'Hari dinas wajib minimal 1 hari.', 'warning');
                    return;
                }

                if (nominalUdara > 0 && !linkUdara) {
                    Swal.fire('Validasi', 'Link lampiran transportasi udara wajib diisi jika ada biaya transportasi udara.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Submit pengajuan?',
                    text: 'Pastikan data pengajuan sudah benar.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, submit',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (!result.value) {
                        return;
                    }

                    $.ajax({
                        method: 'POST',
                        url: 'lta_pengajuan/add',
                        dataType: 'JSON',
                        data: {
                            nominal_udara: $('#nominal_udara').val(),
                            link_lampiran_udara: $('#link_lampiran_udara').val(),
                            hari_dinas: $('#hari_dinas').val(),
                            nominal_darat: $('#nominal_darat').val(),
                            link_lampiran_darat: $('#link_lampiran_darat').val(),
                            catatan_pengaju: $('#catatan_pengaju').val(),
                            customer_id: $('#customer_id').val(),
                            customer_name: $('#customer_name').val(),
                            customer_address: $('#customer_address').val(),
                            teknisi_id: $('#teknisi_id').val(),
                            csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp);
                        }
                    });
                });
            });

            // initialize select2 for customers and teknisi
            $(document).ready(function() {
                if ($.fn.select2) {
                    $('#customer_select').select2({
                        placeholder: 'Cari customer...',
                        allowClear: true,
                        ajax: {
                            url: 'lta_pengajuan/ajax_customers',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term
                                };
                            },
                            processResults: function(data) {
                                // Show customer info message if no results
                                if (!data || !data.results || data.results.length === 0) {
                                    $('#customer-info-msg').show();
                                } else {
                                    $('#customer-info-msg').hide();
                                }
                                return data;
                            }
                        }
                    }).on('select2:select', function(e) {
                        var d = e.params.data;
                        $('#customer_id').val(d.id);
                        $('#customer_name').val(d.text || '');

                        // Fetch customer address from server via AJAX
                        $.ajax({
                            method: 'POST',
                            url: 'lta_pengajuan/ajax_customer_address',
                            dataType: 'JSON',
                            data: {
                                customer_id: d.id,
                                csrf_token: token
                            },
                            success: function(resp) {
                                if (resp.success && resp.address) {
                                    $('#customer_address').val(resp.address);
                                }
                            },
                            error: function() {
                                console.log('Failed to fetch customer address');
                            }
                        });
                    }).on('select2:unselect', function(e) {
                        $('#customer_id').val('');
                        $('#customer_name').val('');
                        $('#customer_address').val('');
                        $('#customer-info-msg').hide();
                    });

                    $('#teknisi_select').select2({
                        placeholder: 'Pilih teknisi...',
                        ajax: {
                            url: 'lta_pengajuan/ajax_teknisi',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term
                                };
                            },
                            processResults: function(data) {
                                return data;
                            }
                        }
                    }).on('select2:select', function(e) {
                        var d = e.params.data;
                        $('#teknisi_id').val(d.id);
                    });
                }
            });
        });
    </script>