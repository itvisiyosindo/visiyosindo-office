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
    <h2><i class="icons fas fa-ticket-alt"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Tiket Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 stat-card active-filter" data-status="">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tiket IT</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tiket Baru Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 stat-card" data-status="1">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tiket Baru (Open)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['open'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope-open-text fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tiket Progress Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 stat-card" data-status="2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sedang Diproses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['progress'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tiket Selesai Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 stat-card" data-status="solved">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tiket Selesai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['solved'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Daftar Semua Tiket Kendala IT</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelDataTicket" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Kode Tiket</th>
                            <th style="width: 20%;">Pelapor</th>
                            <th style="width: 20%;">Subject</th>
                            <th style="width: 15%;">Aset</th>
                            <th style="width: 10%;">Prioritas</th>
                            <th style="width: 12%;">Tanggal</th>
                            <th style="width: 10%;">Teknisi</th>
                            <th style="width: 8%;">Status</th>
                            <th style="width: 10%;">Aksi</th>
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

        var table = $('#tabelDataTicket').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url("it_maintenance/data_ticket_pagination") ?>',
                type: 'POST',
                data: function(d) {
                    d.status_filter = currentStatusFilter;
                }
            },
            columns: [{
                    data: 1, // kode_tiket
                    render: function(data, type, row) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: 5
                }, // nama_pembuat (reporter)
                {
                    data: 2
                }, // subject
                {
                    data: 7, // nama_aset
                    render: function(data, type, row) {
                        var kode = row[8]; // kode_aset
                        if (kode && kode !== 'Tidak Ada') {
                            return data + ' (' + kode + ')';
                        }
                        return data || 'Lainnya / Umum';
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
                    data: 6, // nama_penerima (assigned technician)
                    render: function(data) {
                        return data || '<span class="text-danger font-weight-bold">Belum Ditugaskan</span>';
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
                    data: 0, // id_ticket
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                    render: function(data) {
                        var buttons = '<div class="d-flex justify-content-center align-items-center flex-nowrap">';
                        buttons += '<a href="<?= base_url("it_maintenance/detail_ticket/") ?>' + data + '" class="btn btn-sm btn-info mr-1" title="Detail"><i class="fa fa-search"></i></a>';

                        <?php if (isAdmin()) { ?>
                            buttons += '<button class="btn btn-sm btn-danger btn-delete-ticket" data-id="' + data + '" title="Hapus"><i class="fa fa-trash"></i></button>';
                        <?php } ?>

                        buttons += '</div>';
                        return buttons;
                    }
                }
            ],
            order: [
                [1, 'desc']
            ],
            pageLength: 10,
            language: {
                processing: "Memproses...",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data tiket",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(filtered dari _MAX_ total data)",
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        // Stats cards filter click handler
        $('.stat-card').on('click', function() {
            var status = $(this).data('status');
            currentStatusFilter = status;

            // Update active class styling
            $('.stat-card').removeClass('active-filter');
            $(this).addClass('active-filter');

            // Reload DataTable with new filter parameter
            table.ajax.reload();
        });

        // Delete ticket event handler
        $(document).on('click', '.btn-delete-ticket', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Tiket?',
                text: 'Data tiket akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url("it_maintenance/delete_ticket") ?>',
                        type: 'POST',
                        data: {
                            id: id
                        },
                        dataType: 'JSON',
                        success: function(resp) {
                            if (resp.status === 'success') {
                                Swal.fire('Berhasil!', resp.message || 'Tiket berhasil dihapus', 'success');
                                table.ajax.reload(null, false);
                            } else {
                                Swal.fire('Gagal!', resp.message || 'Gagal menghapus tiket', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error, 'error');
                        }
                    });
                }
            });
        });
    });
</script>