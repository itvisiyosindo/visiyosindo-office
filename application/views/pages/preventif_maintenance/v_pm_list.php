<!-- View: v_pm_list.php - List Preventif Maintenance -->

<?php
// Count statistics with default values
$count_open = isset($count_open) ? (int)$count_open : 0;
$count_progress = isset($count_progress) ? (int)$count_progress : 0;
$count_closed = isset($count_closed) ? (int)$count_closed : 0;
$count_all = isset($count_all) ? (int)$count_all : 0;

// Debug: Log values to console
?>
<script>
    console.log('Stats Debug:', {
        count_open: <?= $count_open ?>,
        count_progress: <?= $count_progress ?>,
        count_closed: <?= $count_closed ?>,
        count_all: <?= $count_all ?>
    });
</script>

<?php
?>

<style>
    .stat-card {
        border-left: 5px solid;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        background: white;
        position: relative;
        height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-card.active {
        border-left-width: 7px;
        opacity: 1;
    }

    .stat-card.open {
        border-left-color: #ffc107;
    }

    .stat-card.open .stat-number {
        color: #ff9800;
    }

    .stat-card.progress {
        border-left-color: #17a2b8;
    }

    .stat-card.progress .stat-number {
        color: #17a2b8;
    }

    .stat-card.closed {
        border-left-color: #28a745;
    }

    .stat-card.closed .stat-number {
        color: #28a745;
    }

    .stat-card.all {
        border-left-color: #667eea;
    }

    .stat-card.all .stat-number {
        color: #667eea;
    }

    .stat-number {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1;
        height: 40px;
    }

    .stat-label {
        font-size: 13px;
        color: #666;
        font-weight: 500;
        text-transform: capitalize;
        line-height: 1.2;
        height: 16px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (max-width: 768px) {
        .stat-card {
            height: 90px;
            padding: 18px;
        }

        .stat-number {
            font-size: 32px;
            height: 36px;
        }

        .stat-label {
            font-size: 12px;
            height: 15px;
        }
    }

    @media (max-width: 576px) {
        .stat-card {
            height: 85px;
            padding: 15px;
        }

        .stat-number {
            font-size: 28px;
            margin-bottom: 6px;
            height: 32px;
        }

        .stat-label {
            font-size: 11px;
            height: 14px;
        }
    }

    #tabelPM {
        font-size: 13px;
    }

    #tabelPM thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        font-weight: 600;
    }
</style>

<header class="page-header">
    <h2><i class="icons icon-user-follow"></i>&nbsp;Preventif Maintenance</h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Daftar Preventif Maintenance</span></li>
        </ol>
    </div>
</header>

<div class="container-fluid">
    <?php
    $canManagePM = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
    if ($canManagePM) {
    ?>
        <div class="row mb-3">
            <div class="col-12">
                <div class="btn-group">
                    <a href="<?= base_url('preventif-maintenance/add') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah PM
                    </a>
                    <button type="button" class="btn btn-success" onclick="exportExcel()">
                        <i class="fas fa-download"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-- Statistics -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="stat-card all" data-status="all">
                <div class="stat-number"><?= $count_all ?></div>
                <div class="stat-label">Total PM</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card open" data-status="1">
                <div class="stat-number"><?= $count_open ?></div>
                <div class="stat-label">Open</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card progress" data-status="2">
                <div class="stat-number"><?= $count_progress ?></div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card closed" data-status="3">
                <div class="stat-number"><?= $count_closed ?></div>
                <div class="stat-label">Closed</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Daftar Preventif Maintenance</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelPM" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Kode PM</th>
                            <th style="width: 20%;">Subject</th>
                            <th style="width: 15%;">Pihak Ketiga</th>
                            <th style="width: 12%;">Tanggal</th>
                            <th style="width: 15%;">Pembuat</th>
                            <th style="width: 12%;">Status</th>
                            <th style="width: 16%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let table;

    // Ensure jQuery is loaded before running this script
    function initializePMTable() {
        if (typeof $ === 'undefined') {
            // jQuery not loaded yet, try again
            setTimeout(initializePMTable, 100);
            return;
        }

        $(document).ready(function() {
            // Initialize datatable
            table = $('#tabelPM').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '<?= base_url('preventif-maintenance/pagination') ?>',
                    type: 'POST',
                    data: function(d) {
                        if (typeof FILTER_OWNER !== 'undefined' && FILTER_OWNER !== null) {
                            d.filter_owner = FILTER_OWNER;
                        }
                    }
                },
                columns: [{
                        data: 0,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 1,
                        orderable: false
                    },
                    {
                        data: 2,
                        orderable: false
                    },
                    {
                        data: 3,
                        orderable: false
                    },
                    {
                        data: 4,
                        orderable: false
                    },
                    {
                        data: 5,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 6,
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                language: {
                    processing: "Memproses...",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(filtered from _MAX_ total records)",
                    search: "Cari:",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Delete PM
            $(document).on('click', '.btn-delete-pm', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Hapus PM?',
                    text: "Data PM akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= base_url('preventif-maintenance/delete') ?>',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(response) {
                                let res = typeof response === 'string' ? JSON.parse(response) : response;
                                if (res.status === 'success') {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: res.message || res.msg || 'Data berhasil dihapus',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    table.ajax.reload();
                                }
                            }
                        });
                    }
                });
            });

            // Stat card filter
            $(document).on('click', '.stat-card', function() {
                let status = $(this).data('status');
                let statusValue = parseInt(status);

                // Reset all card active states
                $('.stat-card').removeClass('active');

                // Highlight clicked card
                $(this).addClass('active');

                console.log('Filter by status:', status);

                // Filter table by status
                if (status === 'all') {
                    table.column(5).search('').draw();
                } else {
                    // Search for exact status match in column 5
                    let searchTerm = statusValue.toString();
                    table.column(5).search(searchTerm, false, false).draw();
                }
            });
        });
    }

    // Pass optional filter_owner from server to JS
    var FILTER_OWNER = <?= isset($filter_owner) ? $filter_owner : 'null' ?>;

    // Initialize when DOM is ready
    initializePMTable();

    function exportExcel() {
        window.location.href = '<?= base_url('preventif-maintenance/export') ?>';
    }
</script>