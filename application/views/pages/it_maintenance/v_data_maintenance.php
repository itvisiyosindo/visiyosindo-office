<header class="page-header">
    <h2><i class="icons fas fa-clipboard-list"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    .card-stat {
        border: 1px solid #e9ecef;
    }
    .card-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15) !important;
    }
    .card-stat.active-filter {
        background-color: #f0f8ff !important;
        border: 2px solid #0088cc !important;
    }
</style>

<div class="container-fluid">
    <!-- Card Stats Section -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card card-stat active-filter" id="card-total" style="cursor: pointer; border-left: 5px solid #0088cc; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase" style="font-size: 11px; font-weight: bold; letter-spacing: 0.5px;">Total Checklist</span>
                            <h3 class="mb-0 mt-1 font-weight-bold" style="color: #1a2332;"><?= (int) ($stats['total'] ?? 0) ?></h3>
                        </div>
                        <div class="stat-icon" style="background-color: rgba(0, 136, 208, 0.1); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-clipboard-list fa-lg text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card card-stat" id="card-baik" style="cursor: pointer; border-left: 5px solid #2baab1; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase" style="font-size: 11px; font-weight: bold; letter-spacing: 0.5px;">Hardware Baik</span>
                            <h3 class="mb-0 mt-1 font-weight-bold" style="color: #1a2332;"><?= (int) ($stats['baik'] ?? 0) ?></h3>
                        </div>
                        <div class="stat-icon" style="background-color: rgba(43, 170, 177, 0.1); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-circle fa-lg text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card card-stat" id="card-perbaikan" style="cursor: pointer; border-left: 5px solid #e36159; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase" style="font-size: 11px; font-weight: bold; letter-spacing: 0.5px;">Perlu Perbaikan</span>
                            <h3 class="mb-0 mt-1 font-weight-bold" style="color: #1a2332;"><?= (int) ($stats['perlu_perbaikan'] ?? 0) ?></h3>
                        </div>
                        <div class="stat-icon" style="background-color: rgba(227, 97, 89, 0.1); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-tools fa-lg text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card card-stat" id="card-ganti" style="cursor: pointer; border-left: 5px solid #734ba9; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase" style="font-size: 11px; font-weight: bold; letter-spacing: 0.5px;">Rekomendasi Ganti</span>
                            <h3 class="mb-0 mt-1 font-weight-bold" style="color: #1a2332;"><?= (int) ($stats['ganti_unit'] ?? 0) ?></h3>
                        </div>
                        <div class="stat-icon" style="background-color: rgba(115, 75, 169, 0.1); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-recycle fa-lg" style="color: #734ba9;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
            <div>
                <a href="<?= base_url('it_maintenance/checklist_form') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Cek Maintenance Aset
                </a>
            </div>
            
            <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                <div class="input-group input-daterange" data-plugin-datepicker data-plugin-options='{"format": "yyyy-mm-dd", "orientation": "bottom"}'>
                    <input type="text" class="form-control text-center" id="start-date" style="width: 130px; font-weight: bold; border-radius: 4px;" placeholder="Tgl Mulai" autocomplete="off">
                    <span class="input-group-addon" style="border: none; background: transparent; padding: 6px 12px;">s/d</span>
                    <input type="text" class="form-control text-center" id="end-date" style="width: 130px; font-weight: bold; border-radius: 4px;" placeholder="Tgl Akhir" autocomplete="off">
                </div>
                
                <button id="btn-filter-date" class="btn btn-default" title="Filter Tanggal">
                    <i class="fas fa-filter text-primary"></i> Filter
                </button>
                <button id="btn-reset-date" class="btn btn-default" title="Reset Filter">
                    <i class="fas fa-sync text-danger"></i> Reset
                </button>
                <button id="btn-export-excel" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Daftar Hasil Pemeriksaan Berkala Aset IT</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelDataMaintenance" class="table table-hover table-striped table-bordered" style="font-size: 13px;">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Kode Check</th>
                            <th style="width: 12%;">Tanggal Cek</th>
                            <th style="width: 18%;">Aset</th>
                            <th style="width: 15%;">Pemegang (PIC)</th>
                            <th style="width: 10%;">Status HW</th>
                            <th style="width: 10%;">Status SW</th>
                            <th style="width: 8%;">GDrive Backup</th>
                            <th style="width: 12%;">Rekomendasi</th>
                            <th style="width: 8%;">Aksi</th>
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
    var filterHardware = '';
    var filterRekomendasi = '';

    var table = $('#tabelDataMaintenance').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url("it_maintenance/data_maintenance_pagination") ?>',
            type: 'POST',
            data: function(d) {
                d.hardware_filter = filterHardware;
                d.rekomendasi_filter = filterRekomendasi;
                d.start_date = $('#start-date').val();
                d.end_date = $('#end-date').val();
            }
        },
        columns: [
            { 
                data: 1, // kode_maintenance
                render: function(data) {
                    return '<strong>' + data + '</strong>';
                }
            },
            { 
                data: 2, // tanggal_cek
                render: function(data) {
                    if (!data) return '-';
                    var d = new Date(data);
                    return d.getDate().toString().padStart(2, '0') + '-' + 
                           (d.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                           d.getFullYear();
                }
            },
            { 
                data: 7, // nama_aset
                render: function(data, type, row) {
                    var kode = row[8]; // kode_aset
                    if (kode) {
                        return data + ' (' + kode + ')';
                    }
                    return data || '-';
                }
            },
            { data: 9 }, // nama_pengguna (PIC)
            { 
                data: 3, // status_hardware
                render: function(data) {
                    if (data === 'Baik') return '<span class="badge badge-success">Baik</span>';
                    if (data === 'Perlu Perbaikan') return '<span class="badge badge-warning">Perlu Perbaikan</span>';
                    return '<span class="badge badge-danger">' + data + '</span>';
                }
            },
            { 
                data: 4, // status_software
                render: function(data) {
                    if (data === 'Baik') return '<span class="badge badge-success">Baik</span>';
                    if (data === 'Perlu Update') return '<span class="badge badge-warning">Perlu Update</span>';
                    return '<span class="badge badge-danger">' + data + '</span>';
                }
            },
            { 
                data: 5, // backup_gdrive
                render: function(data) {
                    return data == 1 
                        ? '<span class="text-success font-weight-bold"><i class="fa fa-check-circle"></i> Ya</span>' 
                        : '<span class="text-danger font-weight-bold"><i class="fa fa-times-circle"></i> Tidak</span>';
                }
            },
            { 
                data: 6, // rekomendasi
                render: function(data) {
                    if (data === 'Tetap Digunakan') return '<span class="badge badge-success">Tetap Digunakan</span>';
                    return '<span class="badge badge-danger">Ganti Unit</span>';
                }
            },
            { 
                data: 0, // id_maintenance
                orderable: false,
                searchable: false,
                className: "text-center",
                render: function(data) {
                    var buttons = '<div class="d-flex justify-content-center align-items-center flex-nowrap">';
                    
                    <?php if (isAdmin()) { ?>
                    buttons += '<button class="btn btn-sm btn-danger btn-delete-maintenance" data-id="' + data + '" title="Hapus"><i class="fa fa-trash"></i></button>';
                    <?php } else { ?>
                    buttons += '-';
                    <?php } ?>
                    
                    buttons += '</div>';
                    return buttons;
                }
            }
        ],
        order: [[1, 'desc']],
        pageLength: 10,
        language: {
            processing: "Memproses...",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Tidak ada data pemeriksaan",
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

    // Delete maintenance event handler
    $(document).on('click', '.btn-delete-maintenance', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Checklist?',
            text: 'Data checklist pemeriksaan ini akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("it_maintenance/delete_maintenance") ?>',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'JSON',
                    success: function(resp) {
                        if (resp.status === 'success') {
                            Swal.fire('Berhasil!', resp.message || 'Data berhasil dihapus', 'success');
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Gagal!', resp.message || 'Gagal menghapus data', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error, 'error');
                    }
                });
            }
        });
    });
    // Handle stats card click filters
    $('#card-total').click(function() {
        $('.card-stat').removeClass('active-filter');
        $(this).addClass('active-filter');
        filterHardware = '';
        filterRekomendasi = '';
        table.ajax.reload();
    });

    $('#card-baik').click(function() {
        $('.card-stat').removeClass('active-filter');
        $(this).addClass('active-filter');
        filterHardware = 'Baik';
        filterRekomendasi = '';
        table.ajax.reload();
    });

    $('#card-perbaikan').click(function() {
        $('.card-stat').removeClass('active-filter');
        $(this).addClass('active-filter');
        filterHardware = 'Perlu Perbaikan';
        filterRekomendasi = '';
        table.ajax.reload();
    });

    $('#card-ganti').click(function() {
        $('.card-stat').removeClass('active-filter');
        $(this).addClass('active-filter');
        filterHardware = '';
        filterRekomendasi = 'Ganti Unit';
        table.ajax.reload();
    });

    // Filter Date Click Handler
    $('#btn-filter-date').click(function() {
        table.ajax.reload();
    });

    // Reset Date Click Handler
    $('#btn-reset-date').click(function() {
        $('#start-date').val('');
        $('#end-date').val('');
        table.ajax.reload();
    });

    // Export Excel Click Handler
    $('#btn-export-excel').click(function() {
        var hardware = filterHardware;
        var rekomendasi = filterRekomendasi;
        var startDate = $('#start-date').val();
        var endDate = $('#end-date').val();
        var search = table.search() || '';

        var url = '<?= base_url("it_maintenance/export_maintenance_excel") ?>' + 
                  '?hardware_filter=' + encodeURIComponent(hardware) + 
                  '&rekomendasi_filter=' + encodeURIComponent(rekomendasi) + 
                  '&start_date=' + encodeURIComponent(startDate) + 
                  '&end_date=' + encodeURIComponent(endDate) + 
                  '&keyword=' + encodeURIComponent(search);
        
        window.location.href = url;
    });
});
</script>
