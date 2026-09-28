<header class="page-header">
    <h2><i class="icons fas fa-share-square"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col">
        <div class="">
            <?php if (isStafAdmin() || isAdmin()) { ?>
                <a href="<?= base_url('pengeluaran_barang/show') ?>" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Data</a>
                <a class="btn btn-sm btn-primary" id="tarik-invoice"><i class="icons icon-plus"></i>&nbsp;Tarik dari Invoice</a>
            <?php } ?>
            <a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Export Excel</a>
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
                <div class="col-md-2">
                    <small>Filter By Month:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <small>Filter By Customer:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="filter_customer" placeholder="Ketik nama Customer...">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <small>Filter By Ivoice:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <select class="form-control " name="filter_invoice" id="filter_invoice">
                                <option value="">Semua</option>
                                <option value="from_invoice">Tarik dari Invoice</option>
                                <option value="ready_invoice">Sudah ada Invoice</option>
                                <option value="unready_invoice">Belum ada penarikan invoice</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <small>Search by Sudah ada Invoice:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search_ready_invoice" placeholder="Ketik Nomor Invoice...">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <small>Search by Tarik Dari Invoice:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search_tarik_dari_invoice" placeholder="Ketik Nomor Invoice...">
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
                            <th> No Pengiriman </th>
                            <th> Tanggal Keluar </th>
                            <th> Nama Customer </th>
                            <th> Nama Gudang </th>
                            <th> Keterangan </th>
                            <th> Nama Barang </th>
                            <th> Sudah ada Invoice </th>
                            <th> Tarik dari invoice </th>
                            <th> Konfirmasi Email TIKI </th>
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
            <?= form_open('pengeluaran_barang/update/file_pendukung', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <?php if (isStafAdmin() || isAdmin()) { ?>
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
                    <input class="form-control" type="hidden" id="id_pengeluaran_barang" name="id_pengeluaran_barang">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="button" id="save-form" class="btn btn-success btn-save">Simpan</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- modal-by-invoice -->
<div id="modal-by-invoice" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Tarik Dari Invoice</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-2 pt-1">
                            <label class="col-form-label">Customer <span class="text-danger">*</span></label>
                            <select data-plugin-selectTwo class="form-control search_customer filter-grup" name="id_customer" id="id_customer">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="col-form-label">Gudang <span class="text-danger">*</span></label>
                        <select class="form-control" name="id_gudang" id="id_gudang" required <?= isset($detail_barang_keluar_temp) || $this->uri->segment(2) == 'edit' ? 'disabled' : NULL ?>>
                            <option value="">...</option>
                            <?php foreach ($gudang as $g) { ?>
                                <option value="<?= encrypt($g->id_gudang) ?>"><?= $g->nama_gudang ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12">
                        <section class="card">
                            <header class="card-header text-center">
                                <h2 class="card-title">Pilih Pengeluaran Barang</h2>
                            </header>
                            <div class="card-body">
                                <div class="scrollable visible-slider colored-slider" data-plugin-scrollable style="height: 350px;width:auto">
                                    <div class="scrollable-content">
                                        <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> No Invoice</th>
                                                    <th> Tanggal Invoice</th>
                                                    <th> Check </th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-invoice">
                                                <!-- isi table -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="button" id="save-form" class="btn btn-success btn-add-invoice">Simpan</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Cetak Data Pengeluaran Barang </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="dt-marketing-form">
                    <div class="form-group" style="display: flex;">
                        <div style="flex: 50%;padding: 10px;">
                            <input class="form-control" data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
                        </div>
                        <div style="flex: 50%;padding: 10px;">
                            <input class="form-control" data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" id="btn-export" class="btn btn-success btn-clear-form">Export Excel</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#filter_month, #filter_gudang, #filter_invoice').change(function() {
            updateDatatable()
        })
        $('#filter_customer, #search_ready_invoice, #search_tarik_dari_invoice').keyup(function() {
            updateDatatable()
        })

        table = $('#kt_table_1').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: 'pengeluaran_barang/pagination',
                type: 'POST',
                data: function(e) {
                    // e.tahun = $('#tahun').val()
                    e.filter_gudang = $('#filter_gudang').val()
                    e.filter_customer = $('#filter_customer').val()
                    e.filter_month = $('#filter_month').val()
                    e.filter_invoice = $('#filter_invoice').val()
                    e.search_ready_invoice = $('#search_ready_invoice').val()
                    e.search_tarik_dari_invoice = $('#search_tarik_dari_invoice').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0, 2, 7],
                className: 'text-center'
            }]
        })

        $(".search_customer").themePluginSelect2({
            placeholder: "--- Ketik Nama Customer ---",
            // data: 'fuadi',
            data: {
                id: '123',
                text: 'fuadi'
            },
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
                            q: params.term,
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

        $(document).on('click', '.btn-add-invoice', function() {
            if (!$('#id_customer').val() || !$('#id_gudang').val() || !$("input:radio[name=check]:checked").val()) {
                alert('form tidak boleh kosong!!!')
            } else {
                var id_customer = $('.search_customer').val()
                var id_invoice = $("input:radio[name=check]:checked").val()
                var id_gudang = $("#id_gudang").val()

                link = 'pengeluaran_barang/show/' + id_invoice + '/' + id_gudang

                window.location = '<?= base_url() ?>' + link
            }
        })

        $(document).on('change', '.search_customer', function() {
            $("#data-invoice").empty();
            id = $('.search_customer').val()
            $.ajax({
                method: 'POST',
                url: 'pengeluaran_barang/get/form_tarik_invoice',
                dataType: 'JSON',
                data: {
                    id: id,
                    csrf_token: token
                },
                success: function(data) {
                    console.log(data);

                    let i = 1
                    data.forEach(dt => {
                        let x = `
                    <tr>
                        <td><input type="hidden" value="">${i++} </td>
                        <td><input type="hidden" value="">${dt.no_invoice}</td>
                        <td><input type="hidden" value="">${dt.tgl_invoice}</td>
                        <td><input type="hidden" value=""><input value='${dt.id_invoice}' type="radio" name="check"></td>
                    </tr>
                    `
                        $('#data-invoice').append(x)
                    })
                }
            })
        });

        $(document).on('click', '.btn-edit', function() {
            var id = $(this).attr("data-id")
            var link = 'pengeluaran_barang/edit/' + id
            window.location = '<?= base_url() ?>' + link
        })

        $(document).on('click', '#tarik-invoice', function() {
            $('#modal-by-invoice .form-control').val(null)
            $("#data-invoice").empty();
            $(".search_customer").empty();
            $('#modal-by-invoice').modal()
        })

        //file pendukung
        $(document).on('click', '.btn-file', function() {
            $('#main-modal .form-control').val(null)
            $('#main-modal').modal()

            var id = $(this).attr("data-id")
            var no_pengiriman = $(this).attr("no-pengiriman")

            $('#main-modal #modal-label').html('No Pengiriman : ' + no_pengiriman)
            $('#main-modal #id_pengeluaran_barang').val(id)
        })

        $(document).on('click', '#btn-download', function() {
            var id = $('#main-modal #id_pengeluaran_barang').val()
            var link = 'pengeluaran_barang/get/download_file/' + id
            window.open('<?= base_url() ?>' + link)
        })

        $(document).on('click', '#btn-lihat', function() {
            var id = $('#main-modal #id_pengeluaran_barang').val()
            $.ajax({
                method: 'POST',
                url: 'pengeluaran_barang/get/lihat_file',
                dataType: 'JSON',
                data: {
                    id: id,
                    csrf_token: token
                },
                success: function(name) {
                    if (name) {
                        var link = 'uploads/pengeluaran_barang/' + name
                        window.open('<?= base_url() ?>' + link)
                    } else {
                        alert('file tidak ada!')
                    }
                }
            })
        })
        //end file pendukung

        $('#btn-cetaklaporan-form').click(function() {
            $('#main-modal-marketing').modal()


        })

        $("#btn-export").click(function() {

            tglawal = $("#tglawal").val();
            tglakhir = $("#tglakhir").val();
            window.open("<?php echo base_url(); ?>pengeluaran_barang/exportlaporan/search?tglawal=" + encodeURIComponent(tglawal) + "&tglakhir=" + encodeURIComponent(tglakhir), "_blank");
            $('#main-modal-marketing').modal('hide')

        });

        // Event saat checkbox TIKI diklik
        $(document).on('change', '.check-email-tiki', function() {
            var id = $(this).data('id');
            var isChecked = $(this).is(':checked');
            var $checkbox = $(this); // Simpan referensi checkbox untuk digunakan di dalam fungsi lain

            if (isChecked) {
                // Tampilkan SweetAlert Konfirmasi
                Swal.fire({
                    title: 'Konfirmasi Email TIKI',
                    text: "Yakin data ini SUDAH di-email ke TIKI? Notifikasi WA akan dikirim ke Gudang & P. Budi.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Proses!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {

                        // Mulai AJAX
                        $.ajax({
                            url: '<?= base_url("pengeluaran_barang/update_status_tiki") ?>',
                            type: 'POST',
                            dataType: 'JSON',
                            timeout: 0, // TAMBAHAN PENTING: Set 0 agar ajax menunggu unlimited time
                            data: {
                                id: id,
                                csrf_token: token
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Sedang Mengirim...',
                                    html: 'Mohon tunggu, sistem sedang mengirim notifikasi WhatsApp.<br><b>Jangan tutup halaman ini.</b>',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    // Reload tabel tanpa reset paging
                                    table.ajax.reload(null, false);

                                    // Tampilkan pesan Sukses
                                    Swal.fire(
                                        'Berhasil!',
                                        response.msg,
                                        'success'
                                    );
                                } else {
                                    // Jika status error dari server
                                    Swal.fire(
                                        'Gagal!',
                                        response.msg || 'Gagal update status',
                                        'error'
                                    );
                                    $checkbox.prop('checked', false); // Uncheck kembali
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle error spesifik
                                console.log(xhr.responseText);
                                Swal.fire(
                                    'Error!',
                                    'Terjadi kesalahan server atau koneksi (Status: ' + status + ').',
                                    'error'
                                );
                                $checkbox.prop('checked', false);
                            }
                        });

                    } else {
                        // Jika user klik Batal (Cancel)
                        $checkbox.prop('checked', false);
                    }
                });
            }
        });

    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>