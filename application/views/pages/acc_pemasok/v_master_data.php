<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .master-container {
        background: #f8fafc;
        min-height: 100vh;
        padding: 20px 15px;
    }
    .profile-tabs {
        background: white;
        border-radius: 12px;
        padding: 5px;
        box-shadow: var(--card-shadow);
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    .profile-tabs .nav-link {
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .profile-tabs .nav-link:hover {
        background: #f1f5f9;
        color: #334155;
    }
    .profile-tabs .nav-link.active {
        background: var(--primary-gradient);
        color: white;
    }
</style>

<header class="page-header">
    <h2><i class="fas fa-database"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('acc_pemasok') ?>"><span>Data Pemasok</span></a></li>
            <li><span>Master Data</span></li>
        </ol>
    </div>
</header>

<div class="master-container">
    <div class="container-fluid">
        <!-- TAB NAVIGATION -->
        <ul class="nav profile-tabs" id="masterTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="status-tab" data-toggle="tab" href="#status" role="tab"><i class="fas fa-info-circle"></i> Status Pemasok</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tipe-tab" data-toggle="tab" href="#tipe" role="tab"><i class="fas fa-tag"></i> Tipe Pemasok</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="product-tab" data-toggle="tab" href="#product" role="tab"><i class="fas fa-box-open"></i> Produk Utama</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="expo-tab" data-toggle="tab" href="#expo" role="tab"><i class="fas fa-award"></i> Hospital Expo</a>
            </li>
        </ul>

        <!-- TAB CONTENTPANELS -->
        <div class="tab-content" id="masterTabsContent">
            
            <!-- 1. STATUS PEMASOK -->
            <div class="tab-pane fade show active" id="status" role="tabpanel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white font-weight-bold text-dark"><i class="fas fa-plus-circle text-primary"></i> Tambah Status Pemasok</div>
                            <div class="card-body">
                                <form action="<?= base_url('acc_master_pemasok/save_status') ?>" class="ajax-form-master">
                                    <div class="form-group">
                                        <label class="font-weight-semibold text-dark">Nama Status</label>
                                        <input type="text" class="form-control" name="nama" required placeholder="Contoh: Bekerjasama">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label class="font-weight-semibold text-dark">Urutan Tampil</label>
                                        <input type="number" class="form-control" name="urutan" value="0">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block mt-3"><i class="fas fa-save"></i> Simpan Status</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="font-weight-bold text-dark mb-3">Daftar Status Pemasok</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped datatable-master" id="table-status" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th width="10%">No</th>
                                                <th>Nama Status</th>
                                                <th width="20%">Urutan</th>
                                                <th width="15%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; foreach ($status_list as $st) { ?>
                                                <tr>
                                                    <td><?= $no++ ?>.</td>
                                                    <td><strong><?= htmlspecialchars($st->nama) ?></strong></td>
                                                    <td><?= $st->urutan ?></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-xs btn-danger btn-delete-master" data-url="<?= base_url('acc_master_pemasok/delete_status/' . $st->id) ?>"><i class="fas fa-trash-alt"></i> Hapus</button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. TIPE PEMASOK -->
            <div class="tab-pane fade" id="tipe" role="tabpanel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white font-weight-bold text-dark"><i class="fas fa-plus-circle text-primary"></i> Tambah Tipe Pemasok</div>
                            <div class="card-body">
                                <form action="<?= base_url('acc_master_pemasok/save_tipe') ?>" class="ajax-form-master">
                                    <div class="form-group">
                                        <label class="font-weight-semibold text-dark">Nama Tipe</label>
                                        <input type="text" class="form-control" name="nama" required placeholder="Contoh: Manufacture">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block mt-3"><i class="fas fa-save"></i> Simpan Tipe</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="font-weight-bold text-dark mb-3">Daftar Tipe Pemasok</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped datatable-master" id="table-tipe" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th width="10%">No</th>
                                                <th>Nama Tipe</th>
                                                <th width="15%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; foreach ($tipe_list as $tp) { ?>
                                                <tr>
                                                    <td><?= $no++ ?>.</td>
                                                    <td><strong><?= htmlspecialchars($tp->nama) ?></strong></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-xs btn-danger btn-delete-master" data-url="<?= base_url('acc_master_pemasok/delete_tipe/' . $tp->id) ?>"><i class="fas fa-trash-alt"></i> Hapus</button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. PRODUK UTAMA -->
            <div class="tab-pane fade" id="product" role="tabpanel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white font-weight-bold text-dark"><i class="fas fa-plus-circle text-primary"></i> Tambah Produk Utama</div>
                            <div class="card-body">
                                <form action="<?= base_url('acc_master_pemasok/save_product') ?>" class="ajax-form-master">
                                    <div class="form-group">
                                        <label class="font-weight-semibold text-dark">Nama Produk</label>
                                        <input type="text" class="form-control" name="nama" required placeholder="Contoh: USG Mindray">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block mt-3"><i class="fas fa-save"></i> Simpan Produk</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="font-weight-bold text-dark mb-3">Daftar Produk</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped datatable-master" id="table-product" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th width="10%">No</th>
                                                <th>Nama Produk</th>
                                                <th width="15%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; foreach ($product_list as $pr) { ?>
                                                <tr>
                                                    <td><?= $no++ ?>.</td>
                                                    <td><strong><?= htmlspecialchars($pr->nama) ?></strong></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-xs btn-danger btn-delete-master" data-url="<?= base_url('acc_master_pemasok/delete_product/' . $pr->id) ?>"><i class="fas fa-trash-alt"></i> Hapus</button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. HOSPITAL EXPO -->
            <div class="tab-pane fade" id="expo" role="tabpanel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white font-weight-bold text-dark"><i class="fas fa-plus-circle text-primary"></i> Tambah Event Hospital Expo</div>
                            <div class="card-body">
                                <form action="<?= base_url('acc_master_pemasok/save_hospital_expo') ?>" class="ajax-form-master">
                                    <div class="form-group mb-2">
                                        <label class="font-weight-semibold text-dark">Nama Event</label>
                                        <input type="text" class="form-control" name="nama" required placeholder="Contoh: Hospital Expo Jakarta">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="font-weight-semibold text-dark">Tahun Event</label>
                                        <input type="number" class="form-control" name="tahun" value="<?= date('Y') ?>" placeholder="e.g. 2026">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="font-weight-semibold text-dark">Lokasi / Keterangan</label>
                                        <input type="text" class="form-control" name="lokasi" placeholder="e.g. JCC Senayan">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block mt-3"><i class="fas fa-save"></i> Simpan Event</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="font-weight-bold text-dark mb-3">Daftar Event Hospital Expo</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped datatable-master" id="table-expo" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th width="10%">No</th>
                                                <th>Nama Event</th>
                                                <th width="15%">Tahun</th>
                                                <th>Lokasi</th>
                                                <th width="15%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; foreach ($hospital_expo_list as $he) { ?>
                                                <tr>
                                                    <td><?= $no++ ?>.</td>
                                                    <td><strong><?= htmlspecialchars($he->nama) ?></strong></td>
                                                    <td><span class="badge badge-info"><?= $he->tahun ?></span></td>
                                                    <td><?= htmlspecialchars($he->lokasi ?: '-') ?></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-xs btn-danger btn-delete-master" data-url="<?= base_url('acc_master_pemasok/delete_hospital_expo/' . $he->id) ?>"><i class="fas fa-trash-alt"></i> Hapus</button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajax handler for adding master data
    $('.ajax-form-master').submit(function(e) {
        e.preventDefault();
        
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        btn.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'JSON',
            success: function(resp) {
                if (resp.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: resp.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', resp.message, 'error');
                    btn.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error, 'error');
                btn.prop('disabled', false);
            }
        });
    });

    // Delete handler for master items
    $('.btn-delete-master').click(function() {
        var url = $(this).data('url');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Item master data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(resp) {
                        if (resp.status === 'success') {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: resp.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal!', resp.message, 'error');
                        }
                    }
                });
            }
        });
    });

    // Initialize DataTables for master tables
    if ($.isFunction($.fn.DataTable)) {
        $('.datatable-master').DataTable({
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            autoWidth: false,
            columnDefs: [
                { orderable: false, targets: [-1] }
            ]
        });

        // Adjust column sizing when bootstrap tabs change
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });
    }
});
</script>
