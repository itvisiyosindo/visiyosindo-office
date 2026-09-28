<header class="page-header">
    <h2><i class="icons fas fa-money-bill"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-2">
                    <small>Filter By Month:</small>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan">
                    </div>
                </div>
                <div class="col-md-6 mt-4">
                    <button type="button" id="btn-add" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data</button>
                    <button type="button" id="btn-print" class="btn btn-primary"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Nama Karyawan</th>
                            <th> No Pegawai</th>
                            <th> Status</th>
                            <th> Masa Kerja</th>
                            <th> Hari Hadir</th>
                            <th> T. Jabatan</th>
                            <th> T. Kinerja</th>
                            <th> T. Konsumsi</th>
                            <th> T. Komunikasi</th>
                            <th> T. Transportasi</th>
                            <th> T. BBM</th>
                            <th> T. Lainnya</th>
                            <th> Potongan</th>
                            <th> Total</th>
                            <th> No Rekening</th>
                            <th> Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Add/Edit -->
<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="fa fa-users"></i> <span id="modal-title">Form Data Salary Resign</span></h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">Nama Karyawan <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="pengguna_id" name="pengguna_id" required style="width: 100%;">
                                <option value="">-- Pilih Karyawan Resign --</option>
                                <?php foreach ($pengguna_resign as $p) : ?>
                                    <option value="<?= $p->pengguna_id ?>" data-nama="<?= $p->nama ?>"><?= $p->nama ?> (<?= $p->no_pegawai ?: 'No NPP' ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="nama_karyawan" name="nama_karyawan">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">No Pegawai</label>
                            <input type="text" class="form-control" id="no_pegawai" name="no_pegawai" placeholder="Auto" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Periode <span class="text-danger">*</span></label>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="periode" name="periode" required placeholder="Pilih Bulan">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Status Karyawan</label>
                            <input type="text" class="form-control" id="status_karyawan" name="status_karyawan" placeholder="Auto" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Masa Kerja</label>
                            <input type="text" class="form-control" id="masa_kerja" name="masa_kerja" placeholder="Auto" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Hari Kehadiran</label>
                            <input type="number" class="form-control" id="hari_kehadiran" name="hari_kehadiran" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">No Rekening</label>
                            <input type="text" class="form-control" id="no_rekening" name="no_rekening" placeholder="Auto" readonly>
                        </div>
                    </div>
                </div>
                
                <hr>
                <h6 class="text-primary"><strong>Tunjangan</strong></h6>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Tunjangan Jabatan (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="tunjangan_jabatan" name="tunjangan_jabatan" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Tunjangan Kinerja (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="tunjangan_kinerja" name="tunjangan_kinerja" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Tunjangan Konsumsi (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="tunjangan_konsumsi" name="tunjangan_konsumsi" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Tunjangan Komunikasi (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="tunjangan_komunikasi" name="tunjangan_komunikasi" placeholder="0">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Tunjangan Transportasi (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="tunjangan_transportasi" name="tunjangan_transportasi" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Tunjangan BBM (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="tunjangan_bbm" name="tunjangan_bbm" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Tunjangan Lainnya (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="tunjangan_lainnya" name="tunjangan_lainnya" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Potongan (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="potongan" name="potongan" placeholder="0">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-control-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="id" name="id" class="form-control">
                <input type="hidden" id="form_action" name="form_action" value="add">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Confirm Delete -->
<div id="delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-light">
                <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data ini?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="delete_id">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-confirm-delete">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Rupiah Mask
    $('.input-rupiah').mask('000.000.000.000', { reverse: true });

    // Initialize Select2
    $('#pengguna_id').select2({
        placeholder: '-- Pilih Karyawan Resign --',
        allowClear: true,
        dropdownParent: $('#main-modal')
    });

    // Autofill when karyawan is selected
    $('#pengguna_id').on('change', function() {
        var pengguna_id = $(this).val();
        var selectedOption = $(this).find('option:selected');
        
        if (pengguna_id) {
            // Set nama_karyawan hidden field
            $('#nama_karyawan').val(selectedOption.data('nama'));
            
            // Fetch pengguna detail for autofill
            $.ajax({
                url: '<?= base_url() ?>salary_resign/getPenggunaDetail/' + pengguna_id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var data = response.data;
                        $('#no_pegawai').val(data.no_pegawai || '');
                        $('#status_karyawan').val(data.status_karyawan || '');
                        $('#masa_kerja').val(data.masa_kerja || '');
                        $('#no_rekening').val(data.no_rek || '');
                    }
                }
            });
        } else {
            // Clear autofill fields
            $('#nama_karyawan').val('');
            $('#no_pegawai').val('');
            $('#status_karyawan').val('');
            $('#masa_kerja').val('');
            $('#no_rekening').val('');
        }
    });

    // DataTable
    var table = $('#kt_table_1').DataTable({
        responsive: false,
        processing: true,
        serverSide: true,
        scrollX: true,
        order: [[0, 'asc']],
        ajax: {
            url: 'salary_resign/pagination',
            type: 'POST',
            data: function(e) {
                e.filter_month = $('#filter_month').val();
                e.csrf_token = token;
            }
        },
        columnDefs: [
            { targets: [0, 2, 3, 5, 16], className: 'text-center' },
            { targets: [6,7,8,9,10,11,12,13,14], className: 'text-right' }
        ]
    });

    // Filter by month
    $('#filter_month').change(function() {
        table.ajax.reload(null, false);
    });

    // Clear form
    function clearForm() {
        $('#modal-form')[0].reset();
        $('#id').val('');
        $('#form_action').val('add');
        $('.input-rupiah').val('');
        $('#pengguna_id').val('').trigger('change');
        $('#nama_karyawan').val('');
        $('#no_pegawai').val('');
        $('#status_karyawan').val('');
        $('#masa_kerja').val('');
        $('#no_rekening').val('');
    }

    // Add button
    $('#btn-add').click(function() {
        clearForm();
        $('#modal-title').text('Tambah Data Salary Resign');
        $('#modal-form').attr('action', 'salary_resign/add');
        $('#main-modal').modal('show');
    });

    // Edit button
    $(document).on('click', '.btn-edit', function() {
        clearForm();
        var id = $(this).attr('data-id');
        $('#modal-title').text('Edit Data Salary Resign');
        $('#form_action').val('update');
        $('#id').val(id);
        $('#modal-form').attr('action', 'salary_resign/update');

        fetch('salary_resign/edit/' + id)
            .then(response => response.json())
            .then(data => {
                // Set dropdown and trigger change for autofill display
                $('#pengguna_id').val(data.pengguna_id).trigger('change');
                $('#nama_karyawan').val(data.nama_karyawan);
                $('#no_pegawai').val(data.no_pegawai);
                $('#status_karyawan').val(data.status_karyawan);
                $('#masa_kerja').val(data.masa_kerja);
                $('#hari_kehadiran').val(data.hari_kehadiran);
                $('#tunjangan_jabatan').val(formatRupiah(data.tunjangan_jabatan));
                $('#tunjangan_kinerja').val(formatRupiah(data.tunjangan_kinerja));
                $('#tunjangan_konsumsi').val(formatRupiah(data.tunjangan_konsumsi));
                $('#tunjangan_komunikasi').val(formatRupiah(data.tunjangan_komunikasi));
                $('#tunjangan_transportasi').val(formatRupiah(data.tunjangan_transportasi));
                $('#tunjangan_bbm').val(formatRupiah(data.tunjangan_bbm));
                $('#tunjangan_lainnya').val(formatRupiah(data.tunjangan_lainnya));
                $('#potongan').val(formatRupiah(data.potongan));
                $('#no_rekening').val(data.no_rekening);
                $('#periode').val(data.periode);
                $('#keterangan').val(data.keterangan);
                $('#main-modal').modal('show');
            });
    });

    // Delete button
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).attr('data-id');
        $('#delete_id').val(id);
        $('#delete-modal').modal('show');
    });

    // Confirm delete
    $('.btn-confirm-delete').click(function() {
        var id = $('#delete_id').val();
        $.ajax({
            url: 'salary_resign/delete/' + id,
            type: 'POST',
            data: { csrf_token: token },
            dataType: 'json',
            success: function(response) {
                $('#delete-modal').modal('hide');
                if (response.status === 'success') {
                    toastr.success(response.message);
                    table.ajax.reload(null, false);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    // Print single button
    $(document).on('click', '.btn-print-single', function() {
        var id = $(this).attr('data-id');
        window.open('<?= base_url() ?>salary_resign/print_single/' + id);
    });

    // Print button
    $('#btn-print').click(function() {
        var month = $('#filter_month').val();
        if (!month) {
            month = '<?= date("Y-m", strtotime("first day of last month")) ?>';
        }
        window.open('<?= base_url() ?>salary_resign/print/' + month);
    });

    // Format number to rupiah string
    function formatRupiah(angka) {
        if (!angka || angka == 0) return '';
        var number_string = parseFloat(angka).toFixed(0).toString();
        var sisa = number_string.length % 3;
        var rupiah = number_string.substr(0, sisa);
        var ribuan = number_string.substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }
});
</script>
