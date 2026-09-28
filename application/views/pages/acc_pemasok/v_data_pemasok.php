<header class="page-header">
    <h2><i class="fas fa-truck-loading"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Accounting & Tax</span></li>
            <li><span><?= $page_title ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <!-- STATS CARDS -->
    <div class="col-lg-12">
        <div class="row mb-4">
            <!-- Total Pemasok -->
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card shadow-sm border-0 cursor-pointer stat-card" data-status="" style="background: linear-gradient(135deg, #4f46e5, #6366f1) !important; border-radius: 12px; transition: transform 0.2s; overflow: hidden;">
                    <div class="card-body p-3" style="background: transparent !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-0 small font-weight-semibold" style="color: rgba(255,255,255,0.85) !important; margin: 0; font-size: 11px;">Total Pemasok</p>
                                <h3 class="mb-0 font-weight-bold" id="stat-total" style="color: #ffffff !important; margin: 0; font-size: 20px;">0</h3>
                            </div>
                            <div class="stat-icon" style="background: rgba(255,255,255,0.2) !important; padding: 6px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-truck-loading text-white" style="font-size: 16px; color: #ffffff !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pemasok Baru -->
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card shadow-sm border-0 cursor-pointer stat-card" data-status="baru" style="background: linear-gradient(135deg, #0d9488, #14b8a6) !important; border-radius: 12px; transition: transform 0.2s; overflow: hidden;">
                    <div class="card-body p-3" style="background: transparent !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-0 small font-weight-semibold" style="color: rgba(255,255,255,0.85) !important; margin: 0; font-size: 11px;">Pemasok Baru</p>
                                <h3 class="mb-0 font-weight-bold" id="stat-baru" style="color: #ffffff !important; margin: 0; font-size: 20px;">0</h3>
                            </div>
                            <div class="stat-icon" style="background: rgba(255,255,255,0.2) !important; padding: 6px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-plus-circle text-white" style="font-size: 16px; color: #ffffff !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pemasok Aktif -->
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card shadow-sm border-0 cursor-pointer stat-card" data-status="1" style="background: linear-gradient(135deg, #059669, #10b981) !important; border-radius: 12px; transition: transform 0.2s; overflow: hidden;">
                    <div class="card-body p-3" style="background: transparent !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-0 small font-weight-semibold" style="color: rgba(255,255,255,0.85) !important; margin: 0; font-size: 11px;">Aktif (Kerjasama)</p>
                                <h3 class="mb-0 font-weight-bold" id="stat-aktif" style="color: #ffffff !important; margin: 0; font-size: 20px;">0</h3>
                            </div>
                            <div class="stat-icon" style="background: rgba(255,255,255,0.2) !important; padding: 6px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check-circle text-white" style="font-size: 16px; color: #ffffff !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pemasok Tidak Aktif -->
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <div class="card shadow-sm border-0 cursor-pointer stat-card" data-status="2" style="background: linear-gradient(135deg, #e11d48, #f43f5e) !important; border-radius: 12px; transition: transform 0.2s; overflow: hidden;">
                    <div class="card-body p-3" style="background: transparent !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-0 small font-weight-semibold" style="color: rgba(255,255,255,0.85) !important; margin: 0; font-size: 11px;">Tidak Aktif (Selesai)</p>
                                <h3 class="mb-0 font-weight-bold" id="stat-tidak-aktif" style="color: #ffffff !important; margin: 0; font-size: 20px;">0</h3>
                            </div>
                            <div class="stat-icon" style="background: rgba(255,255,255,0.2) !important; padding: 6px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-times-circle text-white" style="font-size: 16px; color: #ffffff !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referensi -->
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <div class="card shadow-sm border-0 cursor-pointer stat-card" data-status="3" style="background: linear-gradient(135deg, #d97706, #f59e0b) !important; border-radius: 12px; transition: transform 0.2s; overflow: hidden;">
                    <div class="card-body p-3" style="background: transparent !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-0 small font-weight-semibold" style="color: rgba(255,255,255,0.85) !important; margin: 0; font-size: 11px;">Referensi (Lainnya)</p>
                                <h3 class="mb-0 font-weight-bold" id="stat-referensi" style="color: #ffffff !important; margin: 0; font-size: 20px;">0</h3>
                            </div>
                            <div class="stat-icon" style="background: rgba(255,255,255,0.2) !important; padding: 6px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-info-circle text-white" style="font-size: 16px; color: #ffffff !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FILTER PANEL -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body p-4" style="background-color: #f8fafc; border-radius: 8px;">
        <h5 class="mb-3 font-weight-bold text-dark"><i class="fas fa-filter"></i> Filter & Cari Pemasok</h5>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <label class="small text-muted font-weight-bold">Nama Pemasok</label>
                <input type="text" class="form-control filter-input" id="filter-nama" placeholder="Masukkan nama pemasok...">
            </div>
            
            <div class="col-lg-2 col-md-6 col-sm-6 mb-3">
                <label class="small text-muted font-weight-bold">Status Pemasok</label>
                <select class="form-control select2 filter-select" id="filter-status">
                    <option value="">-- Semua Status --</option>
                    <?php foreach ($status_list as $st) { ?>
                        <option value="<?= $st->id ?>"><?= $st->nama ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-lg-2 col-md-6 col-sm-6 mb-3">
                <label class="small text-muted font-weight-bold">Tipe Pemasok</label>
                <select class="form-control select2 filter-select" id="filter-tipe">
                    <option value="">-- Semua Tipe --</option>
                    <?php foreach ($tipe_list as $tp) { ?>
                        <option value="<?= $tp->id ?>"><?= $tp->nama ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <label class="small text-muted font-weight-bold">Negara</label>
                <select class="form-control select2 filter-select" id="filter-negara">
                    <option value="">-- Semua Negara --</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-6 col-sm-6 mb-3">
                <label class="small text-muted font-weight-bold">Produk</label>
                <select class="form-control select2 filter-select" id="filter-product">
                    <option value="">-- Semua Produk --</option>
                    <?php foreach ($product_list as $pr) { ?>
                        <option value="<?= $pr->id ?>"><?= $pr->nama ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <label class="small text-muted font-weight-bold">Hospital Expo</label>
                <select class="form-control select2 filter-select" id="filter-hospital-expo">
                    <option value="">-- Semua Event --</option>
                    <?php foreach ($hospital_expo_list as $he) { ?>
                        <option value="<?= $he->id ?>"><?= $he->nama ?> (<?= $he->tahun ?>)</option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-lg-9 col-md-6 col-sm-12 text-right align-self-end mb-3">
                <button type="button" class="btn btn-default" id="btn-reset-filters"><i class="fas fa-undo"></i> Reset Filter</button>
                <button type="button" class="btn btn-primary" id="btn-apply-filters"><i class="fas fa-search"></i> Terapkan Filter</button>
            </div>
        </div>
    </div>
</div>

<!-- DATATABLE CARD -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex align-items-center justify-content-between p-3" style="border-bottom: 1px solid #f1f5f9;">
        <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list"></i> Daftar Pemasok</h5>
        <div>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-export-cols"><i class="fas fa-file-export"></i> Cetak / Export</button>
            <a href="<?= base_url('acc_pemasok/detail') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pemasok Baru</a>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0" id="table-pemasok" style="width:100%;">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Pemasok</th>
                        <th width="15%">Status</th>
                        <th width="15%">Tipe Pemasok</th>
                        <th width="15%">Negara</th>
                        <th>Produk Utama</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL: COLUMN SELECTOR FOR EXPORT -->
<div class="modal fade" id="modal-export-cols" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
            <div class="modal-header bg-success text-white" style="border-top-left-radius:12px; border-top-right-radius:12px;">
                <h5 class="modal-title font-weight-bold text-white"><i class="fas fa-tasks"></i> Pilih Kolom untuk Diexport</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted">Centang kolom-kolom yang ingin Anda sertakan pada file laporan (Excel/PDF):</p>
                <div class="row">
                    <?php 
                    $export_columns = [
                        'No', 'Nama Pemasok', 'Status', 'Tipe', 'Negara', 'Produk',
                        'Alamat', 'Kontak', 'Website', 'NPWP', 'Info Bank',
                        'Info Partner', 'Info Garansi', 'Info Pembayaran', 'Tgl LOA Awal', 'Tgl Berakhir'
                    ];
                    foreach ($export_columns as $col) {
                        $checked = in_array($col, ['No', 'Nama Pemasok', 'Status', 'Tipe', 'Negara', 'Produk', 'Kontak']) ? 'checked' : '';
                    ?>
                        <div class="col-6 mb-2">
                            <div class="checkbox-custom checkbox-success">
                                <input type="checkbox" class="export-col-checkbox" id="chk-col-<?= strtolower(str_replace(' ', '-', $col)) ?>" value="<?= $col ?>" <?= $checked ?>>
                                <label for="chk-col-<?= strtolower(str_replace(' ', '-', $col)) ?>" class="text-dark"><?= $col ?></label>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer bg-light p-3" style="border-bottom-left-radius:12px; border-bottom-right-radius:12px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn-export-pdf"><i class="fas fa-file-pdf"></i> Export PDF</button>
                <button type="button" class="btn btn-success" id="btn-export-excel"><i class="fas fa-file-excel"></i> Export Excel</button>
            </div>
        </div>
    </div>
</div>

<style>
.cursor-pointer {
    cursor: pointer !important;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
}
.table-actions .btn {
    padding: 3px 8px;
    font-size: 11px;
}
.checkbox-custom label {
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Current filter states
    var activeStatusFilter = '';
    var isBaruFilter = false;

    // Load country list dynamically from local server-side controller (solving CORS)
    $.ajax({
        url: '<?= base_url("acc_pemasok/get_countries") ?>',
        method: 'GET',
        dataType: 'JSON',
        success: function(resp) {
            if (resp.success && resp.data) {
                var select = $('#filter-negara');
                resp.data.forEach(function(country) {
                    select.append(new Option(country, country));
                });
                
                select.select2({
                    theme: 'bootstrap',
                    width: '100%',
                    placeholder: '-- Semua Negara --',
                    allowClear: true
                });
            }
        },
        error: function() {
            // Fallback list of major countries if API fails
            var fallback = ['Indonesia', 'Singapore', 'Malaysia', 'Japan', 'China', 'United States', 'Germany', 'India'];
            var select = $('#filter-negara');
            fallback.forEach(function(c) {
                select.append(new Option(c, c));
            });
            select.select2({
                theme: 'bootstrap',
                width: '100%',
                placeholder: '-- Semua Negara --',
                allowClear: true
            });
        }
    });

    // Initialize regular select2 elements
    if ($.isFunction($.fn.select2)) {
        $('.select2').select2({
            theme: 'bootstrap',
            width: '100%',
            allowClear: true
        });
    }

    // Datatable initialization
    var table = $('#table-pemasok').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url("acc_pemasok/pagination") ?>',
            type: 'POST',
            data: function(d) {
                d.filter_nama = $('#filter-nama').val();
                d.filter_status = isBaruFilter ? '' : (activeStatusFilter || $('#filter-status').val());
                d.filter_tipe = $('#filter-tipe').val();
                d.filter_negara = $('#filter-negara').val();
                d.filter_product = $('#filter-product').val();
                d.filter_hospital_expo = $('#filter-hospital-expo').val();
                // Pass is_baru flag if the "Pemasok Baru" card is clicked
                if (isBaruFilter) {
                    d.filter_status = '1'; // Must be active
                    d.is_baru = 1;
                }
            }
        },
        order: [[1, 'asc']], // Order by Nama Pemasok ascending
        columnDefs: [
            { orderable: false, targets: [0, 5, 6] },
            { className: 'text-center', targets: [0, 2, 3, 4, 6] }
        ]
    });

    // Apply filters
    $('#btn-apply-filters').click(function() {
        isBaruFilter = false;
        activeStatusFilter = '';
        table.ajax.reload();
        updateStats();
    });

    // Reset filters
    $('#btn-reset-filters').click(function() {
        $('#filter-nama').val('');
        $('#filter-status').val('').trigger('change');
        $('#filter-tipe').val('').trigger('change');
        $('#filter-negara').val('').trigger('change');
        $('#filter-product').val('').trigger('change');
        $('#filter-hospital-expo').val('').trigger('change');
        isBaruFilter = false;
        activeStatusFilter = '';
        table.ajax.reload();
        updateStats();
    });

    // Stats card click handlers to filter table
    $('.stat-card').click(function() {
        var status = $(this).data('status');
        
        if (status === 'baru') {
            isBaruFilter = true;
            activeStatusFilter = '';
        } else {
            isBaruFilter = false;
            activeStatusFilter = status;
        }

        // Reflect filter value on select dropdown if it's a specific status code (1, 2, 3)
        if (status === 1 || status === 2 || status === 3 || status === '1' || status === '2' || status === '3') {
            $('#filter-status').val(status).trigger('change');
        } else {
            $('#filter-status').val('').trigger('change');
        }

        table.ajax.reload();
    });

    // Function to reload dashboard stats
    function updateStats() {
        $.ajax({
            url: '<?= base_url("acc_pemasok/get_statistics") ?>',
            type: 'POST',
            dataType: 'JSON',
            success: function(resp) {
                if (resp.success) {
                    $('#stat-total').text(resp.data.total);
                    $('#stat-baru').text(resp.data.baru);
                    $('#stat-aktif').text(resp.data.aktif);
                    $('#stat-tidak-aktif').text(resp.data.tidak_aktif);
                    $('#stat-referensi').text(resp.data.referensi);
                }
            }
        });
    }
    updateStats(); // Initial load

    // Delete pemasok handler
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pemasok dan seluruh dokumen yang terkait akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: '<?= base_url("acc_pemasok/delete") ?>',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'JSON',
                    success: function(resp) {
                        if (resp.status === 'success') {
                            Swal.fire('Terhapus!', resp.message, 'success');
                            table.ajax.reload();
                            updateStats();
                        } else {
                            Swal.fire('Gagal!', resp.message, 'error');
                        }
                    }
                });
            }
        });
    });

    // Helper function to build dynamic export URL with selected columns
    function getExportParams() {
        var cols = [];
        $('.export-col-checkbox:checked').each(function() {
            cols.push($(this).val());
        });

        var params = $.param({
            filter_nama: $('#filter-nama').val(),
            filter_status: isBaruFilter ? '' : (activeStatusFilter || $('#filter-status').val()),
            filter_tipe: $('#filter-tipe').val(),
            filter_negara: $('#filter-negara').val(),
            filter_product: $('#filter-product').val(),
            filter_hospital_expo: $('#filter-hospital-expo').val(),
            cols: cols
        });

        if (isBaruFilter) {
            params += '&is_baru=1&filter_status=1';
        }

        return params;
    }

    // Export Excel action
    $('#btn-export-excel').click(function() {
        var params = getExportParams();
        window.location.href = '<?= base_url("acc_pemasok/export_excel?") ?>' + params;
        $('#modal-export-cols').modal('hide');
    });

    // Export PDF action
    $('#btn-export-pdf').click(function() {
        var params = getExportParams();
        window.open('<?= base_url("acc_pemasok/export_pdf?") ?>' + params, '_blank');
        $('#modal-export-cols').modal('hide');
    });
});
</script>
