<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .pajak-container {
        background: #f8fafc;
        min-height: 100vh;
        padding: 20px 15px;
    }
    .pajak-tabs {
        background: white;
        border-radius: 12px;
        padding: 5px;
        box-shadow: var(--card-shadow);
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    .pajak-tabs .nav-link {
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
    .pajak-tabs .nav-link:hover {
        background: #f1f5f9;
        color: #334155;
    }
    .pajak-tabs .nav-link.active {
        background: var(--primary-gradient);
        color: white;
    }
</style>

<header class="page-header">
    <h2><i class="fas fa-file-invoice-dollar"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Accounting & Tax</span></li>
            <li><span>Bukti Lapor Pajak</span></li>
        </ol>
    </div>
</header>

<div class="pajak-container">
    <div class="container-fluid">
        <!-- FILTER PANEL -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body p-4" style="background-color: #f8fafc; border-radius: 8px;">
                <h5 class="mb-3 font-weight-bold text-dark"><i class="fas fa-filter text-primary"></i> Filter Bukti Lapor Pajak</h5>
                <div class="row">
                    <div class="col-md-9 mb-3">
                        <label class="small text-muted font-weight-bold">Tahun Pelaporan</label>
                        <input type="number" class="form-control" id="filter-tahun-pelaporan" placeholder="Masukkan tahun pelaporan, contoh: 2026">
                    </div>
                    <div class="col-md-3 text-right align-self-end mb-3">
                        <button type="button" class="btn btn-default" id="btn-reset-filters"><i class="fas fa-undo"></i> Reset</button>
                        <button type="button" class="btn btn-primary ml-2" id="btn-apply-filters"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB NAVIGATION -->
        <ul class="nav pajak-tabs" id="pajakTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="ppn-tab" data-toggle="tab" href="#ppn" role="tab" data-kategori="PPN"><i class="fas fa-percent"></i> PPN</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pph-tab" data-toggle="tab" href="#pph" role="tab" data-kategori="PPH"><i class="fas fa-hand-holding-usd"></i> PPH</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pph-tahunan-tab" data-toggle="tab" href="#pph-tahunan" role="tab" data-kategori="PPH Tahunan Badan"><i class="fas fa-calendar-alt"></i> PPH Tahunan Badan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pbb-tab" data-toggle="tab" href="#pbb" role="tab" data-kategori="PBB"><i class="fas fa-home"></i> PBB</a>
            </li>
        </ul>

        <!-- TAB PANELS -->
        <div class="tab-content" id="pajakTabsContent">
            <!-- 1. PPN -->
            <div class="tab-pane fade show active" id="ppn" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between p-3">
                        <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list text-primary"></i> Bukti Lapor PPN</h5>
                        <button type="button" class="btn btn-primary btn-add-pajak" data-kategori="PPN"><i class="fas fa-plus"></i> Tambah PPN Baru</button>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover mb-0 table-pajak-class" id="table-ppn" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th width="15%">Tahun Pelaporan</th>
                                        <th width="15%">Tanggal Lapor</th>
                                        <th width="15%">Batas Akhir</th>
                                        <th width="15%">Link Dokumen</th>
                                        <th width="12%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. PPH -->
            <div class="tab-pane fade" id="pph" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between p-3">
                        <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list text-primary"></i> Bukti Lapor PPH</h5>
                        <button type="button" class="btn btn-primary btn-add-pajak" data-kategori="PPH"><i class="fas fa-plus"></i> Tambah PPH Baru</button>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover mb-0 table-pajak-class" id="table-pph" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th width="15%">Tahun Pelaporan</th>
                                        <th width="15%">Tanggal Lapor</th>
                                        <th width="15%">Batas Akhir</th>
                                        <th width="15%">Link Dokumen</th>
                                        <th width="12%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. PPH TAHUNAN BADAN -->
            <div class="tab-pane fade" id="pph-tahunan" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between p-3">
                        <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list text-primary"></i> Bukti Lapor PPH Tahunan Badan</h5>
                        <button type="button" class="btn btn-primary btn-add-pajak" data-kategori="PPH Tahunan Badan"><i class="fas fa-plus"></i> Tambah Laporan Baru</button>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover mb-0 table-pajak-class" id="table-pph-tahunan" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th width="15%">Tahun Pelaporan</th>
                                        <th width="15%">Tanggal Lapor</th>
                                        <th width="15%">Batas Akhir</th>
                                        <th width="15%">Link Dokumen</th>
                                        <th width="12%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. PBB -->
            <div class="tab-pane fade" id="pbb" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between p-3">
                        <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list text-primary"></i> Bukti Lapor Pajak Bumi & Bangunan</h5>
                        <button type="button" class="btn btn-primary btn-add-pajak" data-kategori="PBB"><i class="fas fa-plus"></i> Tambah PBB Baru</button>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover mb-0 table-pajak-class" id="table-pbb" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th width="15%">Tahun Pelaporan</th>
                                        <th width="15%">Tanggal Lapor</th>
                                        <th width="15%">Batas Akhir</th>
                                        <th width="15%">Link Dokumen</th>
                                        <th width="12%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: ADD/EDIT FORM PAJAK -->
<div class="modal fade" id="modal-form-pajak" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius:12px; border-top-right-radius:12px;">
                <h5 class="modal-title font-weight-bold text-white" id="modal-title"><i class="fas fa-file-invoice-dollar"></i> Form Bukti Lapor Pajak</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="form-pajak" action="<?= base_url('acc_bukti_lapor_pajak/save') ?>">
                    <input type="hidden" name="id" id="form-id">
                    <input type="hidden" name="kategori" id="form-kategori">
                    
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold text-dark">Nama Dokumen / Keterangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_dokumen" id="form-nama" required placeholder="Contoh: Bukti Lapor Masa PPN Januari 2026">
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="font-weight-semibold text-dark">Tahun Pelaporan <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun_pelaporan" id="form-tahun" required value="<?= date('Y') ?>" placeholder="e.g. 2026">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="font-weight-semibold text-dark">Tanggal Lapor Dokumen</label>
                            <div class="input-group">
                                <span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tanggal_lapor" id="form-tanggal" placeholder="dd-mm-yyyy">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="font-weight-semibold text-dark">Batas Akhir Pelaporan</label>
                            <div class="input-group">
                                <span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="batas_akhir" id="form-batas" placeholder="dd-mm-yyyy">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-semibold text-dark">Link Dokumen (G-Drive / Cloud) <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" name="link_dokumen" id="form-link" required placeholder="https://drive.google.com/...">
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light p-3" style="border-bottom-left-radius:12px; border-bottom-right-radius:12px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-save"><i class="fas fa-save"></i> Simpan Dokumen Pajak</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // We will initialize 4 different DataTables with category parameter
    var tables = {};

    function initTable(id, kategori) {
        tables[kategori] = $('#' + id).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url("acc_bukti_lapor_pajak/pagination") ?>',
                type: 'POST',
                data: function(d) {
                    d.kategori = kategori;
                    d.filter_tahun_pelaporan = $('#filter-tahun-pelaporan').val();
                }
            },
            columnDefs: [
                { orderable: false, targets: [0, 5, 6] },
                { className: 'text-center', targets: [0, 2, 3, 4, 5, 6] }
            ]
        });
    }

    // Filter action buttons
    $('#btn-apply-filters').click(function() {
        Object.keys(tables).forEach(function(key) {
            tables[key].ajax.reload();
        });
    });
    $('#btn-reset-filters').click(function() {
        $('#filter-tahun-pelaporan').val('');
        Object.keys(tables).forEach(function(key) {
            tables[key].ajax.reload();
        });
    });

    initTable('table-ppn', 'PPN');
    initTable('table-pph', 'PPH');
    initTable('table-pph-tahunan', 'PPH Tahunan Badan');
    initTable('table-pbb', 'PBB');

    // Add button click handles setting the category
    $('.btn-add-pajak').click(function() {
        var kategori = $(this).data('kategori');
        $('#form-pajak')[0].reset();
        $('#form-id').val('');
        $('#form-kategori').val(kategori);
        $('#modal-title').html('<i class="fas fa-file-invoice-dollar"></i> Tambah Bukti Lapor ' + kategori + ' Baru');
        $('#modal-form-pajak').modal('show');
    });

    // Edit button click
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= base_url("acc_bukti_lapor_pajak/get_detail/") ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function(resp) {
                if (resp.id) {
                    $('#form-id').val(resp.id);
                    $('#form-kategori').val(resp.kategori);
                    $('#form-nama').val(resp.nama_dokumen);
                    $('#form-tahun').val(resp.tahun_pelaporan);
                    $('#form-tanggal').val(resp.tanggal_lapor);
                    $('#form-batas').val(resp.batas_akhir);
                    $('#form-link').val(resp.link_dokumen);
                    $('#modal-title').html('<i class="fas fa-pencil-alt"></i> Edit Bukti Lapor ' + resp.kategori);
                    $('#modal-form-pajak').modal('show');
                } else {
                    Swal.fire('Gagal!', 'Terjadi kesalahan mengambil detail.', 'error');
                }
            }
        });
    });

    // Save button inside modal
    $('#btn-save').click(function() {
        var form = $('#form-pajak');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        var btn = $(this);
        btn.prop('disabled', true);
        var kategori = $('#form-kategori').val();

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
                        $('#modal-form-pajak').modal('hide');
                        if (tables[kategori]) {
                            tables[kategori].ajax.reload(null, false);
                        }
                    });
                } else {
                    Swal.fire('Gagal!', resp.message, 'error');
                }
                btn.prop('disabled', false);
            },
            error: function() {
                Swal.fire('Error!', 'Gagal mengirim data ke server.', 'error');
                btn.prop('disabled', false);
            }
        });
    });

    // Delete button click
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        var activeTabKategori = $('.pajak-tabs .nav-link.active').data('kategori');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Dokumen Pajak ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: '<?= base_url("acc_bukti_lapor_pajak/delete") ?>',
                    type: 'POST',
                    data: { id: id },
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
                                if (tables[activeTabKategori]) {
                                    tables[activeTabKategori].ajax.reload(null, false);
                                }
                            });
                        } else {
                            Swal.fire('Gagal!', resp.message, 'error');
                        }
                    }
                });
            }
        });
    });
});
</script>
