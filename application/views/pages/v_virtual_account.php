<header class="page-header">
    <h2><i class="icons fas fa-database "></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col">
        <div class="">
            <a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Virtual Account</a>
        </div>
        <br>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> No Virtual account</th>
                            <th> Customer</th>
                            <th> Bank </th>
                            <th> Aksi </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Virtual account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label for="nama" class="form-control-label">No Virtual Account <span class="text-danger">*</span> :</label>
                        <input type="text" class="form-control" id="no_va" name="no_va" required>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Nama Bank <span class="text-danger">*</span></label>
                        <select class="form-control" name="id_bank" id="id_bank" required>
                            <option value="">...</option>
                            <?php foreach ($bank as $b) { ?>
                                <option value="<?= encrypt($b->id_bank) ?>"><?= $b->nama_bank ?></option>
                            <?php } ?>
                        </select>
                    </div>
					<div class="form-group">
                    <label class="col-form-label">Customer <span class="text-danger">*</span></label>
                    <select data-plugin-selectTwo class="form-control search_customer filter-grup" name="id_customer" id="id_customer"></select>
					</div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="is_aktif"></div>
                <input type="hidden" id="id_virtual_account" name="id_virtual_account">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        table = $('#kt_table_1').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: 'virtual_account/pagination',
                type: 'POST',
                data: function(e) {
                    // e.tahun = $('#tahun').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0, 4],
                className: 'text-center'
            }]
        })

        $(".search_customer").themePluginSelect2({
            placeholder: "--- Ketik Nama Customer ---",
            allowClear: true,
            minimumInputLength: 1,
            width: '100%',
            ajax: {
                method: 'POST',
                url: "customer/get/by_search",
                dataType: 'json',
                delay: 250,
                data:

                    function(params) {
                        return {
                            q: params.term, // search term
                            csrf_token: token
                        };
                    },
                processResults: function(data, params) {
                    return {
                        results: $.map(data.items, function(obj) {
                            return {
                                id: obj.id_customer,
                                text: `${obj.nama_customer}`
                            };
                        })
                    }
                },
                cache: true
            },
        });


        $('#btn-show-add-form').click(function() {
            $('.form-control').val(null)
            $('#main-modal #modal-form').attr('action', 'virtual_account/add')
            $('#main-modal').modal()
        })

        $(document).on('click', '.btn-edit', function() {
            var object = 'virtual_account'
            $('#main-modal #modal-form').attr('action', 'virtual_account/update')
            $('#main-modal').modal()

            var id = $(this).attr("data-id")
            fetch(object + '/edit/' + id)
                .then(function(resp) {
                    return resp.json()
                })
                .then(function(data) {
                    $('#main-modal #id_virtual_account').val(data[0].id_virtual_account)
                    $('#main-modal #id_bank').val(data[0].id_bank)
                    $('#main-modal #no_va').val(data[0].no_va)
                })
        })
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>