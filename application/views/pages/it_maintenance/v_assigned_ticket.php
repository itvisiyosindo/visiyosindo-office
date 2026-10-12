<style>
    .stat-card {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .stat-card.active-filter {
        border-width: 2px !important;
        background-color: #f8f9fa;
    }
</style>

<header class="page-header">
    <h2><i class="icons fas fa-tasks"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="container-fluid">
    <!-- Statistics KPI Cards -->
    <div class="row mb-4">
        <!-- Total Tugas Card -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2 stat-card active-filter" data-status="">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tugas IT</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $assigned_total ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tiket Baru/Open Card -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2 stat-card" data-status="1">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tiket Baru (Open)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $assigned_open ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress Card -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2 stat-card" data-status="2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sedang Dikerjakan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $assigned_progress ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selesai Card -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2 stat-card" data-status="solved">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tiket Selesai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $assigned_solved ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-list text-primary mr-1"></i> Daftar Tiket IT Yang Ditugaskan Kepada Anda</h6>
            <a href="<?= base_url('it_maintenance/data_ticket') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-layer-group mr-1"></i> Lihat Semua Tiket Perusahaan
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelAssignedTicket" class="table table-hover table-striped table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 13%;">Kode Tiket</th>
                            <th style="width: 16%;">Pelapor</th>
                            <th style="width: 22%;">Subject Kendala</th>
                            <th style="width: 15%;">Perangkat / Aset</th>
                            <th style="width: 9%;">Prioritas</th>
                            <th style="width: 12%;">Tanggal Masuk</th>
                            <th style="width: 9%;">Status</th>
                            <th style="width: 10%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var currentStatusFilter = '';

        var table = $('#tabelAssignedTicket').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url("it_maintenance/assigned_ticket_pagination") ?>',
                type: 'POST',
                data: function(d) {
                    d.status_filter = currentStatusFilter;
                }
            },
            columns: [{
                    data: 1, // kode_tiket
                    render: function(data, type, row) {
                        return '<strong class="text-primary">' + data + '</strong>';
                    }
                },
                {
                    data: 5, // nama_pembuat (pelapor)
                    render: function(data) {
                        return '<i class="fas fa-user-circle text-muted mr-1"></i> ' + (data || '-');
                    }
                },
                {
                    data: 2 // subject
                },
                {
                    data: 7, // nama_aset
                    render: function(data, type, row) {
                        var kode = row[8];
                        if (kode && kode !== 'Tidak Ada') {
                            return (data || 'Aset') + ' <small class="text-muted d-block">(' + kode + ')</small>';
                        }
                        return data || '<span class="text-muted">Lainnya / Umum</span>';
                    }
                },
                {
                    data: 9, // prioritas
                    render: function(data) {
                        var text = data || 'Rendah';
                        var badge = 'badge-info';
                        if (text === 'Sedang') badge = 'badge-warning';
                        else if (text === 'Tinggi') badge = 'badge-danger';
                        return '<span class="badge ' + badge + '">' + text + '</span>';
                    }
                },
                {
                    data: 4, // created_at
                    render: function(data) {
                        if (!data) return '-';
                        var d = new Date(data);
                        return d.getDate().toString().padStart(2, '0') + '-' +
                            (d.getMonth() + 1).toString().padStart(2, '0') + '-' +
                            d.getFullYear() + ' ' +
                            d.getHours().toString().padStart(2, '0') + ':' +
                            d.getMinutes().toString().padStart(2, '0');
                    }
                },
                {
                    data: 3, // status_tiket
                    render: function(data) {
                        var badges = {
                            1: '<span class="badge badge-warning">Open</span>',
                            2: '<span class="badge badge-info">In Progress</span>',
                            3: '<span class="badge badge-success">Solved</span>',
                            4: '<span class="badge badge-secondary">Closed</span>',
                            5: '<span class="badge badge-danger">Rejected</span>'
                        };
                        return badges[data] || '<span class="badge badge-dark">Unknown</span>';
                    }
                },
                {
                    data: 0, // id_ticket (encrypted)
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                    render: function(data, type, row) {
                        return '<a href="<?= base_url("it_maintenance/detail_ticket/") ?>' + data + '" class="btn btn-sm btn-primary" title="Proses / Tindak Lanjut"><i class="fas fa-wrench mr-1"></i> Proses</a>';
                    }
                }
            ],
            order: [
                [5, 'desc'] // order by created_at DESC
            ],
            pageLength: 10,
            language: {
                processing: "Memproses...",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada tiket tugas yang ditugaskan kepada Anda",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        // Filter cards click event
        $('.stat-card').click(function() {
            $('.stat-card').removeClass('active-filter');
            $(this).addClass('active-filter');
            currentStatusFilter = $(this).data('status');
            table.ajax.reload();
        });
    });
</script>
