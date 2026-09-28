<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .sk-container {
        background: #f8fafc;
        min-height: 100vh;
        padding: 20px 15px;
    }
</style>

<header class="page-header">
    <h2><i class="fas fa-folder-open"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Accounting & Tax</span></li>
            <li><span>Data SK & Ketentuan</span></li>
        </ol>
    </div>
</header>

<div class="sk-container">
    <div class="container-fluid">
        <!-- FILTER PANEL -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body p-4" style="background-color: #f8fafc; border-radius: 8px;">
                <h5 class="mb-3 font-weight-bold text-dark"><i class="fas fa-filter text-primary"></i> Filter SK & Ketentuan</h5>
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label class="small text-muted font-weight-bold">Tahun Dokumen</label>
                        <input type="number" class="form-control" id="filter-tahun" placeholder="Contoh: 2026">
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="small text-muted font-weight-bold">Status Masa Berlaku</label>
                        <select class="form-control" id="filter-status-berlaku">
                            <option value="">-- Semua Status --</option>
                            <option value="Aktif">Aktif / Masih Berlaku</option>
                            <option value="Kadaluwarsa">Sudah Kadaluwarsa</option>
                            <option value="Seumur_Hidup">Seumur Hidup / Tanpa Batas</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-right align-self-end mb-3">
                        <button type="button" class="btn btn-default" id="btn-reset-filters"><i class="fas fa-undo"></i> Reset</button>
                        <button type="button" class="btn btn-primary" id="btn-apply-filters"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATATABLE CARD -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex align-items-center justify-content-between p-3" style="border-bottom: 1px solid #f1f5f9;">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list text-primary"></i> Daftar SK & Ketentuan</h5>
                <button type="button" class="btn btn-primary btn-add-new"><i class="fas fa-plus"></i> Tambah Dokumen Baru</button>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0" id="table-sk" style="width:100%;">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Dokumen / SK</th>
                                <th width="15%">Tanggal Dokumen</th>
                                <th width="15%">Masa Berlaku</th>
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
<div class="modal fade" id="modal-form-sk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius:12px; border-top-right-radius:12px;">
                <h5 class="modal-title font-weight-bold text-white" id="modal-title"><i class="fas fa-file-alt"></i> Form Dokumen SK & Ketentuan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="form-sk" action="<?= base_url('acc_sk_ketentuan/save') ?>">
                    <input type="hidden" name="id" id="form-id">
                    
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold text-dark">Nama / Judul Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_dokumen" id="form-nama" required placeholder="Contoh: SK Direksi Terkait Standardisasi SOP">
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="font-weight-semibold text-dark">Tanggal Dokumen</label>
                            <div class="input-group">
                                <span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tanggal_dokumen" id="form-tanggal" placeholder="dd-mm-yyyy">
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="font-weight-semibold text-dark">Masa Berlaku</label>
                            <div class="input-group">
                                <span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="masa_berlaku" id="form-masa" placeholder="dd-mm-yyyy (kosongkan jika seumur hidup)">
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
                <button type="button" class="btn btn-primary" id="btn-save"><i class="fas fa-save"></i> Simpan Dokumen</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var table = $('#table-sk').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url("acc_sk_ketentuan/pagination") ?>',
            type: 'POST',
            data: function(d) {
                d.filter_tahun = $('#filter-tahun').val();
                d.filter_status_berlaku = $('#filter-status-berlaku').val();
            }
        },
        columnDefs: [
            { orderable: false, targets: [0, 4, 5] },
            { className: 'text-center', targets: [0, 2, 3, 4, 5] }
        ]
    });

    // Filter action buttons
    $('#btn-apply-filters').click(function() {
        table.ajax.reload();
    });
    $('#btn-reset-filters').click(function() {
        $('#filter-tahun').val('');
        $('#filter-status-berlaku').val('');
        table.ajax.reload();
    });

    // Add button click
    $('.btn-add-new').click(function() {
        $('#form-sk')[0].reset();
        $('#form-id').val('');
        $('#modal-title').html('<i class="fas fa-file-alt"></i> Tambah Dokumen SK & Ketentuan Baru');
        $('#modal-form-sk').modal('show');
    });

    // Edit button click
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= base_url("acc_sk_ketentuan/get_detail/") ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function(resp) {
                if (resp.id) {
                    $('#form-id').val(resp.id);
                    $('#form-nama').val(resp.nama_dokumen);
                    $('#form-tanggal').val(resp.tanggal_dokumen);
                    $('#form-masa').val(resp.masa_berlaku);
                    $('#form-link').val(resp.link_dokumen);
                    $('#modal-title').html('<i class="fas fa-pencil-alt"></i> Edit Dokumen SK & Ketentuan');
                    $('#modal-form-sk').modal('show');
                } else {
                    Swal.fire('Gagal!', 'Terjadi kesalahan mengambil detail.', 'error');
                }
            }
        });
    });

    // Save button inside modal
    $('#btn-save').click(function() {
        var form = $('#form-sk');
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
                        $('#modal-form-sk').modal('hide');
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
                    url: '<?= base_url("acc_sk_ketentuan/delete") ?>',
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
