<header class="page-header">
    <h2><i class="icons fas fa-money-bill"></i>&nbsp;
        <?= $page_title ?>
    </h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>
                    <?= $page_desc ?>
                </span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col">
        <div class="card-body">
            <div class="table-responsive">
                <br>
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Nama Karyawan</th>
                            <th> Jabatan </th>
                            <th> Status </th>
                            <th> Gaji Pokok </th>
                            <th> Pph21 </th>
                            <th> No. Whatsapp </th>
                            <th> No.Rekening </th>
                            <th> Aksi </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">Gaji Pokok :</label>
                        <input type="text" class="form-control input_salary" id="gaji_pokok" name="gaji_pokok" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Tunjungan Jabatan :</label>
                        <input type="text" class="form-control input_salary" id="tunjangan_jabatan"
                            name="tunjangan_jabatan" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Dasar Potongan BPJS Kesehatan :</label>
                        <input type="text" class="form-control input_salary" id="dasar_potongan_bpjs"
                            name="dasar_potongan_bpjs" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Dasar Potongan BPJS Ketenagakerjaan :</label>
                        <input type="text" class="form-control input_salary" id="dasar_potongan_bpjs_tk"
                            name="dasar_potongan_bpjs_tk" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="main-modal-komisi" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Komisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form-komisi', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">Komisi :</label>
                        <input type="text" class="form-control input_salary" id="komisi" name="komisi" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Bulan :</label>
                        <input type="text" data-plugin-datepicker
                            data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}'
                            class="form-control" id="bulan" name="bulan" placeholder="Pilih Bulan" required
                            data-plugin-datepicker>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="main-modal-pendapatan_lain" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pendapatan Lain</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form-pendapatan_lain', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">Pendapatan Lainnya :</label>
                        <input type="text" class="form-control input_salary" id="pendapatan" name="pendapatan" required>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Pengurangan Lainnya :</label>
                        <input type="text" class="form-control input_salary" id="pengurangan" name="pengurangan"
                            required>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
       
        table = $('#kt_table_1').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: 'salary/paginationtutupbuku',
                type: 'POST',
                data: function (e) {
                    // e.filter_gudang = $('#filter_gudang').val()
                    // e.filter_jenis_penerimaan = $('#filter_jenis_penerimaan').val()
                    // e.filter_month = $('#filter_month').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0],
                className: 'text-center'
            }]
        })

       
       $(document).on('click', '#btn-kirim', function(){
				var par = $(this).data("id")
					$.ajax({
							type: "POST",
							url: "salary/kirimnotifikasi/"+par,
							async: false,
					});
				
			});
        
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>