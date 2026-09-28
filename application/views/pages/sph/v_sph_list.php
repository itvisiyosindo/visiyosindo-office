<!-- View: v_sph_list.php - Halaman List Surat Penawaran Harga -->

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total SPH</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $counts['total'] ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Draft</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $counts['draft'] ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-edit fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Final</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $counts['final'] ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Signed</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $counts['signed'] ?? 0 ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-signature fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-file-invoice mr-2"></i>Daftar Surat Penawaran Harga
        </h6>
        <div class="btn-group">
            <a href="<?= base_url('sph/tambah') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Buat SPH
            </a>
            <a href="<?= base_url('sph/keterangan') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-cog mr-1"></i> Master Keterangan
            </a>
            <a href="<?= base_url('sph/laporan') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i> Laporan
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Filter -->
        <div class="row mb-3">
            <div class="col-md-2">
                <label class="small font-weight-bold">Status</label>
                <select class="form-control form-control-sm" id="filter_status">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="final">Final</option>
                    <option value="signed">Signed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="small font-weight-bold">Divisi</label>
                <select class="form-control form-control-sm" id="filter_visilab">
                    <option value="">Semua</option>
                    <option value="0">Non-Visilab</option>
                    <option value="1">Visilab</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="small font-weight-bold">Dari Tanggal</label>
                <input type="date" class="form-control form-control-sm" id="filter_dari">
            </div>
            <div class="col-md-3">
                <label class="small font-weight-bold">Sampai Tanggal</label>
                <input type="date" class="form-control form-control-sm" id="filter_sampai">
            </div>
            <div class="col-md-2">
                <label class="small font-weight-bold">&nbsp;</label>
                <button type="button" class="btn btn-info btn-sm btn-block" id="btnFilter">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Nomor Surat</th>
                        <th width="15%">Tanggal</th>
                        <th width="25%">Kepada</th>
                        <th width="15%">Total Harga</th>
                        <th width="10%">Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toastr fallback using PNotify
        if (typeof toastr === 'undefined') {
            window.toastr = {
                success: function(msg, title) {
                    new PNotify({
                        title: title || 'Sukses',
                        text: msg,
                        type: 'success',
                        delay: 3000
                    });
                },
                error: function(msg, title) {
                    new PNotify({
                        title: title || 'Error',
                        text: msg,
                        type: 'error',
                        delay: 4000
                    });
                },
                warning: function(msg, title) {
                    new PNotify({
                        title: title || 'Peringatan',
                        text: msg,
                        type: 'warning',
                        delay: 3500
                    });
                },
                info: function(msg, title) {
                    new PNotify({
                        title: title || 'Info',
                        text: msg,
                        type: 'info',
                        delay: 3000
                    });
                }
            };
        }

        // Initialize DataTable
        var table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('sph/pagination') ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_status = $('#filter_status').val();
                    d.filter_visilab = $('#filter_visilab').val();
                    d.filter_dari = $('#filter_dari').val();
                    d.filter_sampai = $('#filter_sampai').val();
                }
            },
            columns: [{
                    data: 0
                },
                {
                    data: 1
                },
                {
                    data: 2
                },
                {
                    data: 3
                },
                {
                    data: 4
                },
                {
                    data: 5
                },
                {
                    data: 6
                }
            ],
            order: [
                [0, 'desc']
            ],
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(filter dari _MAX_ total data)',
                zeroRecords: 'Tidak ada data yang ditemukan',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Selanjutnya',
                    previous: 'Sebelumnya'
                }
            }
        });

        // Filter button
        $('#btnFilter').on('click', function() {
            table.ajax.reload();
        });

        // Delete button - menggunakan class khusus untuk menghindari global handler
        $(document).on('click', '.btn-delete-sph', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Hapus Data?',
                icon: 'error',
                text: 'Data yang sudah dihapus tidak dapat dikembalikan lagi!',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: '<?= base_url('sph/delete') ?>',
                        type: 'POST',
                        data: {
                            id: id,
                            csrf_token: token
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'success') {
                                toastr.success(response.message);
                                table.ajax.reload();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('Terjadi kesalahan sistem');
                        }
                    });
                }
            });
        });
    });
</script>