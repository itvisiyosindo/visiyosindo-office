<header class="page-header">
    <h2><i class="icons fas fa-people-carry"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col">
        <div class="">
        <?php if(isStafAdmin() || isAdmin()){ ?>
            <a href="<?= base_url('penerimaan_barang/show') ?>" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Data</a>
<?php } ?>
        </div>
        <br>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <small>Filter By Gudang:</small>
                    <select class="form-control " name="filter_gudang" id="filter_gudang">
                        <option value="">Semua</option>
                        <?php foreach ($gudang as $row) { ?>
                            <option value="<?= encrypt($row->id_gudang) ?>"><?= $row->nama_gudang ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <small>Filter By Jenis Penerimaan:</small>
                    <select class="form-control " name="filter_jenis_penerimaan" id="filter_jenis_penerimaan">
                        <option value="">Semua</option>
                        <option value="1">Penerimaan Pemindahan Gudang</option>
                        <option value="2">Penerimaan Langsung</option>
                    </select>
                </div>
                <div class="col-md-3">
                <small>Filter By Pemasok</small>
                <select class="form-control" name="filter_nama_pemasok" id="filter_nama_pemasok">
                <option value="">Semua</option>
                <?php
                $pemasok = $this->db->get('pemasok_utama')->result();
                foreach ($pemasok as $row) {
                ?>
                <option value="<?= encrypt($row->id_pemasok) ?>"><?= $row->nama_pemasok ?></option>
                <?php } ?>
                </select>
                </div>
                <div class="col-md-3">
                    <small>Filter By Month:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> No Terima</th>
                            <th> Tanggal Terima </th>
                            <th> Pemasok </th>
                            <th> Gudang </th>
                            <th> Keterangan </th>
                            <th> Nama Barang </th>
                            <th> Penerimaan Pemindahan </th>
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
                <h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('penerimaan_barang/update/file_pendukung', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <?php if(isAdmin() || isStafAdmin()){ ?>
                    <div class="form-group mb-2 pt-1">
                        <label for="InputExperience" class="col-form-label">File Pendukung :</label>
                        <input type="file" class="form-control" name="file_pendukung">
                        <small class="text-danger">upload file jpg, jpeg, png, pdf, doc dan xls, max 4mb</small>
                    </div>
                <?php } ?>
                <div>
                <div class="text-center mb-2">
                        <button type="button" id="btn-lihat" class="btn btn-primary">Lihat File</button>
                    </div>
                    <div class="text-center">
                        <a href="javascript:" id="btn-download"><i class="fas fa-download"></i> Download File</a>
                    </div>

                </div>
                <div class="modal-footer">
                    <input class="form-control" type="hidden" id="id_penerimaan_barang" name="id_penerimaan_barang">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="button" id="save-form" class="btn btn-success btn-save">Simpan</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#filter_jenis_penerimaan, #filter_gudang, #filter_month, #filter_nama_pemasok').change(function() {
            updateDatatable()
        })
        table = $('#kt_table_1').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: 'penerimaan_barang/pagination',
                type: 'POST',
                data: function(e) {
                    e.filter_gudang = $('#filter_gudang').val()
                    e.filter_jenis_penerimaan = $('#filter_jenis_penerimaan').val()
                    e.filter_nama_pemasok = $('#filter_nama_pemasok').val()
                    e.filter_month = $('#filter_month').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0, 2, 7, 8],
                className: 'text-center'
            }]
        })

        $(document).on('click', '.btn-edit', function() {
            var id = $(this).attr("data-id")
            var link = 'penerimaan_barang/edit/' + id
            window.location = '<?= base_url() ?>' + link
        })

        //file pendukung
        $(document).on('click', '.btn-file', function() {
            $('#main-modal .form-control').val(null)
            $('#main-modal').modal()

            var id = $(this).attr("data-id")
            var no_terima = $(this).attr("no-terima")
            
            $('#main-modal #modal-label').html('No Terima : '+no_terima)
            $('#main-modal #id_penerimaan_barang').val(id)
        })

        $(document).on('click', '#btn-download', function() {
            var id = $('#main-modal #id_penerimaan_barang').val()
            var link = 'penerimaan_barang/get/download_file/' + id
            window.open('<?= base_url() ?>' + link)
        })

        $(document).on('click', '#btn-lihat', function() {
            var id = $('#main-modal #id_penerimaan_barang').val()
            $.ajax({
                method: 'POST',
                url: 'penerimaan_barang/get/lihat_file',
                dataType: 'JSON',
                data: {
                    id: id,
                    csrf_token: token
                },
                success: function(name) {
                    if(name){
                        var link = 'uploads/penerimaan_barang/' + name
                        window.open('<?= base_url() ?>' + link)
                    } else {
                        alert('file tidak ada!')
                    }
                }
            })
        })
        //end file pendukung


        $(document).on('click', '.btn-gudang-asal', function() {
            var id = $(this).attr("data-id");
            var link = 'penerimaan_stok/edit/' + id
            window.location = '<?= base_url() ?>' + link
        });
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>