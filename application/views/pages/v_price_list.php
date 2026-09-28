<header class="page-header">
    <h2><i class="fas fa-dollar-sign"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<section class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 font-weight-bold text-dark">Tabel Price List</h4>
            <?php if (isAdmin() || isStafAdmin() || sessPenggunaId() == '107' || sessPenggunaId() == '33') { ?>
                <button type="button" id="btn-show-add-form" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i>&nbsp;Tambah Price List
                </button>
            <?php } ?>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1" style="width:100%">
                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Info Discount</th>
                        <th>Link/Preview</th>
                        <th>Uploader</th>
                        <th>Tanggal</th>
                        <?php if (isAdmin() || isStafAdmin() || sessPenggunaId() == '107' || sessPenggunaId() == '33') { ?>
                            <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<hr>

<section class="card mb-4 border-left-info">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 font-weight-bold text-info">Tabel Brosur Endoscopy</h4>
            <?php if (isAdmin() || isStafAdmin() || sessPenggunaId() == '107' || sessPenggunaId() == '33') { ?>
                <button type="button" id="btn-show-add-formEndoscopy" class="btn btn-sm btn-info">
                    <i class="fas fa-plus"></i>&nbsp;Tambah Brosur Endoscopy
                </button>
            <?php } ?>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_3" style="width:100%">
                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Info Discount</th>
                        <th>Link/Preview</th>
                        <th>Uploader</th>
                        <th>Tanggal</th>
                        <?php if (isAdmin() || isStafAdmin() || sessPenggunaId() == '107' || sessPenggunaId() == '33') { ?>
                            <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<hr>

<section class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 font-weight-bold text-dark">Tabel Katalog</h4>
            <?php if (isAdmin() || isStafAdmin() || sessPenggunaId() == '107' || sessPenggunaId() == '33') { ?>
                <button type="button" id="btn-show-add-formKatalog" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i>&nbsp;Tambah Katalog
                </button>
            <?php } ?>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_2" style="width:100%">
                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Info Discount</th>
                        <th>Link/Preview</th>
                        <th>Uploader</th>
                        <th>Tanggal</th>
                        <?php if (isAdmin() || isStafAdmin() || sessPenggunaId() == '107' || sessPenggunaId() == '33') { ?>
                            <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<div id="main-modal" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h4 id="modal-label" class="modal-title">Form Data</h4>
                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_price_list" name="nama_price_list" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="kategori" name="kategori" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Link Google Drive <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="link_download" name="link_download" placeholder="https://drive.google.com/..." required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Informasi Discount <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="diskon" name="diskon" required>
                </div>
                <div id="group-alasan-edit" style="display:none;">
                    <div class="form-group border-top pt-2">
                        <label class="font-weight-bold text-danger">Alasan Perubahan <span class="text-danger">*</span></label>
                        <textarea name="alasanedit" class="form-control" id="alasanedit" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="id_price_list" name="id_price_list">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-save">Simpan Data</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="preview-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h4 class="modal-title"><i class="fas fa-eye"></i> Preview Dokumen</h4>
                <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0 text-center bg-light">
                <div id="loading-preview" class="p-5"><i class="fas fa-spinner fa-spin fa-2x"></i> Memuat Dokumen...</div>
                <iframe id="preview-frame" src="" width="100%" height="700px" frameborder="0" style="display:none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    // Global scope table variables
    var table1, table2, table3;

    document.addEventListener('DOMContentLoaded', function() {

        // 1. DATA TABLES INITIALIZATION
        var dtConfig = (url) => ({
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                type: 'POST',
                data: {
                    csrf_token: token
                }
            },
            iDisplayLength: 25,
            columnDefs: [{
                targets: [0, 3, 4, 6],
                className: 'text-center'
            }]
        });

        table1 = $('#kt_table_1').DataTable(dtConfig('price_list/pagination'));
        table2 = $('#kt_table_2').DataTable(dtConfig('price_list/paginationKatalog'));
        table3 = $('#kt_table_3').DataTable(dtConfig('price_list/paginationEndoscopy'));

        // 2. MODAL ADD TRIGGERS
        const setupModal = (action, title) => {
            $('#modal-form')[0].reset();
            $('#id_price_list').val('');
            $('#group-alasan-edit').hide();
            $('#modal-label').text(title);
            $('#modal-form').attr('action', action);
            $('#main-modal').modal('show');
        };

        $('#btn-show-add-form').click(() => setupModal('price_list/add', 'Tambah Price List'));
        $('#btn-show-add-formKatalog').click(() => setupModal('price_list/addKatalog', 'Tambah Katalog'));
        $('#btn-show-add-formEndoscopy').click(() => setupModal('price_list/addEndoscopy', 'Tambah Brosur Endoscopy'));

        // 3. EDIT BUTTON HANDLER
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).attr("data-id");
            $('#modal-form')[0].reset();
            $('#group-alasan-edit').show();
            $('#modal-label').text('Edit Data Master');
            $('#modal-form').attr('action', 'price_list/update');

            fetch('price_list/edit/' + id)
                .then(resp => resp.json())
                .then(data => {
                    const d = data[0];
                    $('#id_price_list').val(d.id_price_list);
                    $('#nama_price_list').val(d.nama_price_list);
                    $('#kategori').val(d.kategori);
                    $('#link_download').val(d.link_download);
                    $('#diskon').val(d.diskon);
                    $('#main-modal').modal('show');
                });
        });

        // 4. SAVE BUTTON HANDLER
        $('.btn-save').click(function() {
            const form = $('#modal-form');
            if (!form[0].checkValidity()) {
                form[0].reportValidity();
                return;
            }

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize() + "&csrf_token=" + token,
                success: function(result) {
                    const r = JSON.parse(result);
                    if (r.status == 'success') {
                        Swal.fire('Berhasil!', r.msg, 'success');
                        $('#main-modal').modal('hide');
                        updateDatatable();
                    } else {
                        Swal.fire('Perhatian', r.msg, 'error');
                    }
                }
            });
        });

        // 5. PREVIEW LOGIC
        $(document).on('click', '.btn-preview', function() {
            let rawUrl = $(this).attr('data-url');
            let previewUrl = rawUrl;

            // Regex Drive yang lebih fleksibel
            const driveRegex = /(?:https?:\/\/)?(?:drive\.google\.com\/(?:file\/d\/|open\?id=))([a-zA-Z0-9_-]+)/;
            const match = rawUrl.match(driveRegex);

            if (match && match[1]) {
                previewUrl = `https://drive.google.com/file/d/${match[1]}/preview`;
            }

            $('#loading-preview').show();
            $('#preview-frame').hide().attr('src', previewUrl);
            $('#preview-modal').modal('show');

            $('#preview-frame').on('load', function() {
                $('#loading-preview').hide();
                $(this).fadeIn();
            });
        });

        $('#preview-modal').on('hidden.bs.modal', function() {
            $('#preview-frame').attr('src', '');
        });
    });

    // Helper Functions (Global)
    function dialoghapus(id) {
        $('#id_hapus_hidden').val(id);
        $('#alasan').val('');
        $('#main-modal-hapus').modal('show');
    }

    function updateDatatable() {
        if (table1) table1.ajax.reload(null, false);
        if (table2) table2.ajax.reload(null, false);
        if (table3) table3.ajax.reload(null, false);
    }
</script>