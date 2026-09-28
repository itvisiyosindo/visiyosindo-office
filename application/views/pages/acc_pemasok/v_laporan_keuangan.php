<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .keuangan-container {
        background: #f8fafc;
        min-height: 100vh;
        padding: 20px 15px;
    }
</style>

<header class="page-header">
    <h2><i class="fas fa-balance-scale"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Accounting & Tax</span></li>
            <li><span>Laporan Keuangan</span></li>
        </ol>
    </div>
</header>

<div class="keuangan-container">
    <div class="container-fluid">
        <!-- FILTER PANEL -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body p-4" style="background-color: #f8fafc; border-radius: 8px;">
                <h5 class="mb-3 font-weight-bold text-dark"><i class="fas fa-filter text-primary"></i> Filter Laporan Keuangan</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="small text-muted font-weight-bold">Jenis Dokumen</label>
                        <select class="form-control" id="filter-jenis">
                            <option value="">-- Semua Jenis --</option>
                            <option value="Laba Rugi">Laba Rugi</option>
                            <option value="Posisi Keuangan">Posisi Keuangan</option>
                            <option value="Perubahan Modal">Perubahan Modal</option>
                            <option value="Arus Kas">Arus Kas</option>
                            <option value="CALK">CALK</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small text-muted font-weight-bold">Keperluan Dokumen</label>
                        <select class="form-control" id="filter-keperluan">
                            <option value="">-- Semua Keperluan --</option>
                            <option value="Pemegang Saham">Pemegang Saham</option>
                            <option value="Lapor Pajak">Lapor Pajak</option>
                            <option value="Bank">Bank</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small text-muted font-weight-bold">Tahun Pelaporan</label>
                        <input type="number" class="form-control" id="filter-tahun" placeholder="Contoh: 2025">
                    </div>
                    <div class="col-md-3 text-right align-self-end mb-3">
                        <button type="button" class="btn btn-default" id="btn-reset-filters"><i class="fas fa-undo"></i> Reset</button>
                        <button type="button" class="btn btn-primary ml-2" id="btn-apply-filters"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATATABLE CARD -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex align-items-center justify-content-between p-3" style="border-bottom: 1px solid #f1f5f9;">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list text-primary"></i> Daftar Laporan Keuangan</h5>
                <button type="button" class="btn btn-primary btn-add-new"><i class="fas fa-plus"></i> Tambah Dokumen Baru</button>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0" id="table-keuangan" style="width:100%;">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Dokumen</th>
                                <th width="15%">Jenis Laporan</th>
                                <th width="12%">Tahun Buku</th>
                                <th width="15%">Keperluan</th>
                                <th width="15%">Link Download</th>
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

<!-- MODAL: ADD/EDIT FORM -->
<div class="modal fade" id="modal-form-keuangan" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius:12px; border-top-right-radius:12px;">
                <h5 class="modal-title font-weight-bold text-white" id="modal-title"><i class="fas fa-file-invoice"></i> Form Dokumen Laporan Keuangan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="form-keuangan" action="<?= base_url('acc_laporan_keuangan/save') ?>">
                    <input type="hidden" name="id" id="form-id">
                    
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold text-dark">Nama / Judul Laporan Keuangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_dokumen" id="form-nama" required placeholder="Contoh: Laporan Keuangan Audit PT Yosindo FY2025">
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="font-weight-semibold text-dark">Jenis Dokumen <span class="text-danger">*</span></label>
                            <select class="form-control" name="jenis_dokumen" id="form-jenis" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Laba Rugi">Laba Rugi</option>
                                <option value="Posisi Keuangan">Posisi Keuangan</option>
                                <option value="Perubahan Modal">Perubahan Modal</option>
                                <option value="Arus Kas">Arus Kas</option>
                                <option value="CALK">CALK</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="font-weight-semibold text-dark">Tahun Pelaporan <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun_pelaporan" id="form-tahun" required value="<?= date('Y') ?>" placeholder="e.g. 2025">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="font-weight-semibold text-dark">Keperluan Dokumen <span class="text-danger">*</span></label>
                            <select class="form-control" name="keperluan_dokumen" id="form-keperluan" required>
                                <option value="">-- Pilih Keperluan --</option>
                                <option value="Pemegang Saham">Pemegang Saham</option>
                                <option value="Lapor Pajak">Lapor Pajak</option>
                                <option value="Bank">Bank</option>
                            </select>
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
                <button type="button" class="btn btn-primary" id="btn-save"><i class="fas fa-save"></i> Simpan Dokumen</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var table = $('#table-keuangan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url("acc_laporan_keuangan/pagination") ?>',
            type: 'POST',
            data: function(d) {
                d.filter_jenis_dokumen = $('#filter-jenis').val();
                d.filter_keperluan_dokumen = $('#filter-keperluan').val();
                d.filter_tahun_pelaporan = $('#filter-tahun').val();
            }
        },
        columnDefs: [
            { orderable: false, targets: [0, 5, 6] },
            { className: 'text-center', targets: [0, 2, 3, 4, 5, 6] }
        ]
    });

    // Filter action buttons
    $('#btn-apply-filters').click(function() {
        table.ajax.reload();
    });
    $('#btn-reset-filters').click(function() {
        $('#filter-jenis').val('');
        $('#filter-keperluan').val('');
        $('#filter-tahun').val('');
        table.ajax.reload();
    });

    // Add button click
    $('.btn-add-new').click(function() {
        $('#form-keuangan')[0].reset();
        $('#form-id').val('');
        $('#modal-title').html('<i class="fas fa-file-invoice"></i> Tambah Laporan Keuangan Baru');
        $('#modal-form-keuangan').modal('show');
    });

    // Edit button click
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= base_url("acc_laporan_keuangan/get_detail/") ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function(resp) {
                if (resp.id) {
                    $('#form-id').val(resp.id);
                    $('#form-nama').val(resp.nama_dokumen);
                    $('#form-jenis').val(resp.jenis_dokumen);
                    $('#form-tahun').val(resp.tahun_pelaporan);
                    $('#form-keperluan').val(resp.keperluan_dokumen);
                    $('#form-link').val(resp.link_dokumen);
                    $('#modal-title').html('<i class="fas fa-pencil-alt"></i> Edit Laporan Keuangan');
                    $('#modal-form-keuangan').modal('show');
                } else {
                    Swal.fire('Gagal!', 'Terjadi kesalahan mengambil detail.', 'error');
                }
            }
        });
    });

    // Save button inside modal
    $('#btn-save').click(function() {
        var form = $('#form-keuangan');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        var btn = $(this);
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
                        $('#modal-form-keuangan').modal('hide');
                        table.ajax.reload(null, false);
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
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Dokumen ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: '<?= base_url("acc_laporan_keuangan/delete") ?>',
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
                                table.ajax.reload(null, false);
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
