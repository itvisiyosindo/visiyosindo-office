<header class="page-header">
    <h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<!-- STATISTIC CARDS -->
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card card-body bg-light border-left-primary h-100 py-3 shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Aktivitas Hari Ini</span>
                    <h3 class="font-weight-bold text-primary mb-0 mt-1" id="stat_today"><?= number_format($stats['today'] ?? 0) ?></h3>
                    <small class="text-muted"><i class="fa fa-calendar-check-o"></i> Tanggal <?= date('d M Y') ?></small>
                </div>
                <div class="p-3 bg-primary text-white rounded-circle">
                    <i class="fa fa-clock-o fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-body bg-light border-left-info h-100 py-3 shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Total Log Bulan Ini</span>
                    <h3 class="font-weight-bold text-info mb-0 mt-1" id="stat_month"><?= number_format($stats['month'] ?? 0) ?></h3>
                    <small class="text-muted"><i class="fa fa-calendar"></i> Periode Aktif</small>
                </div>
                <div class="p-3 bg-info text-white rounded-circle">
                    <i class="fa fa-history fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-body bg-light border-left-success h-100 py-3 shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Pengguna Aktif Bulan Ini</span>
                    <h3 class="font-weight-bold text-success mb-0 mt-1" id="stat_user"><?= number_format($stats['user'] ?? 0) ?></h3>
                    <small class="text-muted"><i class="fa fa-users"></i> Karyawan beraktivitas</small>
                </div>
                <div class="p-3 bg-success text-white rounded-circle">
                    <i class="fa fa-user-circle-o fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FILTER CARD -->
<div class="card mb-3 shadow-sm">
    <div class="card-header bg-default py-2 d-flex align-items-center justify-content-between">
        <h5 class="font-weight-bold mb-0 text-dark"><i class="fa fa-filter text-primary"></i> Filter & Pencarian Log</h5>
        <div>
            <button type="button" class="btn btn-sm btn-success" id="btn_export_excel">
                <i class="fa fa-file-excel-o"></i> Export Excel
            </button>
            <button type="button" class="btn btn-sm btn-secondary" id="btn_reset_filter">
                <i class="fa fa-undo"></i> Reset Filter
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Mode Filter Tanggal -->
            <div class="col-md-3">
                <label class="font-weight-bold" style="font-size: 12px;">Filter Periode:</label>
                <div class="mb-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filter_mode" id="mode_month" value="month" checked>
                        <label class="form-check-label" for="mode_month" style="font-size: 12px; cursor: pointer;">Per Bulan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filter_mode" id="mode_range" value="range">
                        <label class="form-check-label" for="mode_range" style="font-size: 12px; cursor: pointer;">Rentang Tanggal</label>
                    </div>
                </div>

                <!-- Input Bulan -->
                <div id="wrapper_month">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan">
                    </div>
                </div>

                <!-- Input Rentang Tanggal -->
                <div id="wrapper_range" style="display: none;">
                    <div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm-dd"}'>
                        <input type="text" class="form-control" id="tgl_mulai" placeholder="Mulai (YYYY-MM-DD)">
                        <span class="input-group-text border-left-0 border-right-0 rounded-0">s/d</span>
                        <input type="text" class="form-control" id="tgl_selesai" placeholder="Selesai (YYYY-MM-DD)">
                    </div>
                </div>
            </div>

            <!-- Filter Pengguna -->
            <div class="col-md-3">
                <label class="font-weight-bold" style="font-size: 12px;">Filter Pengguna:</label>
                <select class="form-control" id="pengguna_id" data-plugin-selectTwo data-plugin-options='{"allowClear": true, "placeholder": "-- Semua Pengguna --"}'>
                    <option value="">-- Semua Pengguna --</option>
                    <?php if (!empty($pengguna_list)): ?>
                        <?php foreach ($pengguna_list as $pg): ?>
                            <option value="<?= $pg->pengguna_id ?>"><?= htmlspecialchars($pg->nama) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Filter Jenis Aksi -->
            <div class="col-md-3">
                <label class="font-weight-bold" style="font-size: 12px;">Filter Jenis Aksi:</label>
                <select class="form-control" id="jenis_aksi" data-plugin-selectTwo data-plugin-options='{"allowClear": true, "placeholder": "-- Semua Jenis Aksi --"}'>
                    <option value="">-- Semua Jenis Aksi --</option>
                    <?php if (!empty($aksi_list)): ?>
                        <?php foreach ($aksi_list as $ak): ?>
                            <option value="<?= htmlspecialchars($ak->jenis_aksi) ?>"><?= htmlspecialchars($ak->jenis_aksi) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Tombol Filter -->
            <div class="col-md-3 d-flex align-items-end">
                <button type="button" class="btn btn-primary btn-block" id="btn_apply_filter" style="height: 38px;">
                    <i class="fa fa-search"></i> Terapkan Filter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- DATA TABLE CARD -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1" style="width: 100%;">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 50px;" class="text-center"> # </th>
                        <th style="width: 180px;"> Pengguna </th>
                        <th style="width: 160px;" class="text-center"> Aksi </th>
                        <th> Keterangan </th>
                        <th style="width: 130px;" class="text-center"> IP Address </th>
                        <th style="width: 160px;" class="text-center"> Tanggal & Waktu </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var table;
    document.addEventListener('DOMContentLoaded', function () {
        // Set default bulan sekarang
        const now = new Date();
        const month = now.getMonth() + 1;
        const formattedMonth = `${now.getFullYear()}-${month.toString().padStart(2, '0')}`;
        $('#filter_month').val(formattedMonth);

        // Toggle Filter Mode (Per Bulan vs Rentang Tanggal)
        $('input[name="filter_mode"]').change(function() {
            if ($(this).val() === 'range') {
                $('#wrapper_month').hide();
                $('#wrapper_range').show();
            } else {
                $('#wrapper_range').hide();
                $('#wrapper_month').show();
            }
        });

        // Update stats function
        function updateStats(month) {
            $.ajax({
                url: '<?= base_url("log/get_stats") ?>',
                type: 'POST',
                data: {
                    month: month,
                    csrf_token: token
                },
                dataType: 'json',
                success: function(res) {
                    if (res && res.status === 'success') {
                        $('#stat_today').text(Number(res.data.today).toLocaleString());
                        $('#stat_month').text(Number(res.data.month).toLocaleString());
                        $('#stat_user').text(Number(res.data.user).toLocaleString());
                    }
                }
            });
        }

        // Inisialisasi DataTable
        table = $('#kt_table_1').DataTable({
            responsive: false,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            scrollY: '50vh',
            scrollX: true,
            scrollCollapse: true,
            deferRender: true,
            order: [[0, 'desc']],
            ajax: {
                url: '<?= base_url("log/pagination") ?>',
                type: 'POST',
                data: function (e) {
                    const mode = $('input[name="filter_mode"]:checked').val();
                    if (mode === 'range') {
                        e.tgl_mulai = $('#tgl_mulai').val();
                        e.tgl_selesai = $('#tgl_selesai').val();
                        e.filter_month = '';
                    } else {
                        e.filter_month = $('#filter_month').val();
                        e.tgl_mulai = '';
                        e.tgl_selesai = '';
                    }
                    e.pengguna_id = $('#pengguna_id').val();
                    e.jenis_aksi = $('#jenis_aksi').val();
                    e.csrf_token = token;
                }
            },
            columnDefs: [
                { targets: [0, 2, 4, 5], className: 'text-center' },
                { targets: [3], className: 'text-left' }
            ]
        });

        // Trigger filter saat tombol Terapkan diklik
        $('#btn_apply_filter').click(function () {
            const mode = $('input[name="filter_mode"]:checked').val();
            if (mode === 'month') {
                updateStats($('#filter_month').val());
            }
            table.ajax.reload();
        });

        // Auto reload saat dropdown diubah
        $('#filter_month, #pengguna_id, #jenis_aksi').change(function () {
            const mode = $('input[name="filter_mode"]:checked').val();
            if (mode === 'month') {
                updateStats($('#filter_month').val());
            }
            table.ajax.reload();
        });

        // Reset Filter
        $('#btn_reset_filter').click(function () {
            $('#mode_month').prop('checked', true).trigger('change');
            $('#filter_month').val(formattedMonth);
            $('#tgl_mulai').val('');
            $('#tgl_selesai').val('');
            $('#pengguna_id').val('').trigger('change');
            $('#jenis_aksi').val('').trigger('change');
            updateStats(formattedMonth);
            table.ajax.reload();
        });

        // Export Excel
        $('#btn_export_excel').click(function () {
            const mode = $('input[name="filter_mode"]:checked').val();
            let params = [];

            if (mode === 'range') {
                const tgl_mulai = $('#tgl_mulai').val();
                const tgl_selesai = $('#tgl_selesai').val();
                if (tgl_mulai) params.push('tgl_mulai=' + encodeURIComponent(tgl_mulai));
                if (tgl_selesai) params.push('tgl_selesai=' + encodeURIComponent(tgl_selesai));
            } else {
                const filter_month = $('#filter_month').val();
                if (filter_month) params.push('filter_month=' + encodeURIComponent(filter_month));
            }

            const pengguna_id = $('#pengguna_id').val();
            if (pengguna_id) params.push('pengguna_id=' + encodeURIComponent(pengguna_id));

            const jenis_aksi = $('#jenis_aksi').val();
            if (jenis_aksi) params.push('jenis_aksi=' + encodeURIComponent(jenis_aksi));

            let url = '<?= base_url("log/export_excel") ?>';
            if (params.length > 0) {
                url += '?' + params.join('&');
            }

            window.open(url, '_blank');
        });
    });
</script>