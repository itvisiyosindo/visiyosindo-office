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
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1" style="font-size: 11px; width: 100%;">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Nama Karyawan</th>
                            <th> No Pegawai</th>
                            <th> Status</th>
                            <th> Masa Kerja</th>
                            <th> Hari Hadir</th>
                            <th> Gaji Pokok</th>
                            <th> Potongan</th>
                            <th> Total Diterima</th>
                            <th> No Rekening</th>
                            <th> Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="fa fa-users"></i> <span id="modal-title">Form Data Gaji Pokok Resign</span></h5>
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
                            <input type="text" class="form-control" id="no_pegawai" name="no_pegawai" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Periode <span class="text-danger">*</span></label>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="periode" name="periode" required>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label">Status</label>
                            <input type="text" class="form-control" id="status_karyawan" name="status_karyawan" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label">Masa Kerja</label>
                            <input type="text" class="form-control" id="masa_kerja" name="masa_kerja" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label">No Rekening</label>
                            <input type="text" class="form-control" id="no_rekening" name="no_rekening" readonly>
                        </div>
                    </div>
                </div>

                <hr>
                <h6 class="text-primary"><strong>Rincian Gaji & Pendapatan</strong></h6>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Hari Hadir</label>
                            <input type="number" class="form-control" id="hari_kehadiran" name="hari_kehadiran" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label">Gaji Pokok (Rp) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-rupiah" id="gaji_pokok" name="gaji_pokok" placeholder="0" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-control-label">Pendapatan Lain (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="pendapatan_lain" name="pendapatan_lain" placeholder="0">
                        </div>
                    </div>
                </div>

                <hr>
                <h6 class="text-danger"><strong>Potongan & Pajak</strong></h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label">BPJS Kes (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="bpjs_kes" name="bpjs_kes" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label">BPJS TK (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="bpjs_tk" name="bpjs_tk" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label">Potongan Lain (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="potongan_lain" name="potongan_lain" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label">Pajak PPH21 (Rp)</label>
                            <input type="text" class="form-control input-rupiah" id="pph21" name="pph21" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-control-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="1"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="id" name="id">
                <input type="hidden" id="form_action" name="form_action" value="add">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan Data</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

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
    // 1. Inisialisasi Masking & Select2
    $('.input-rupiah').mask('000.000.000.000', { reverse: true });
    
    $('#pengguna_id').select2({
        placeholder: '-- Pilih Karyawan Resign --',
        allowClear: true,
        dropdownParent: $('#main-modal')
    });

    // 2. Autofill Data Karyawan
    $('#pengguna_id').on('change', function() {
        var pengguna_id = $(this).val();
        var selectedOption = $(this).find('option:selected');
        
        if (pengguna_id) {
            $('#nama_karyawan').val(selectedOption.data('nama'));
            
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

                        $('#bpjs_kes').val(formatRupiah(data.potonganBPJSKesehatan || 0));
                        $('#bpjs_tk').val(formatRupiah(data.potonganBPJSTK || 0)); 
                        $('#pendapatan_lain').val(formatRupiah(data.nominal_pendapatan_lain || 0));
                        $('#pph21').val(formatRupiah(data.nominal_pph21 || 0));
                        $('#potongan_lain').val(formatRupiah(data.nominal_potongan_lain || 0));
                    }
                },
                error: function() {
                    Swal.fire("Error", "Gagal mengambil data detail karyawan", "error");
                }
            });
        } else {
            $('#no_pegawai, #status_karyawan, #masa_kerja, #no_rekening, #bpjs_kes, #bpjs_tk, #pendapatan_lain, #pph21, #potongan_lain').val('');
        }
    });

    // 3. DataTable
    var table = $('#kt_table_1').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: 'salary_resign_pokok/pagination', 
            type: 'POST',
            data: function(e) {
                e.filter_month = $('#filter_month').val();
                e.csrf_token = token; 
            }
        },
        columnDefs: [
            { targets: [0, 2, 3, 5, 9, 10], className: 'text-center' },
            { targets: [6, 7, 8], className: 'text-right' } 
        ]
    });

    $('#filter_month').change(function() {
        table.ajax.reload();
    });

    // 4. Fungsi Reset Form
    function clearForm() {
        $('#modal-form')[0].reset();
        $('#id').val('');
        $('#form_action').val('add');
        $('#pengguna_id').val('').trigger('change.select2'); 
        $('#no_pegawai, #status_karyawan, #masa_kerja, #no_rekening').val('');
    }

    // 5. Tambah Data
    $('#btn-add').click(function() {
        clearForm();
        $('#modal-title').text('Tambah Gaji Pokok Resign');
        $('#main-modal').modal('show');
    });

    // 6. Simpan Data (FIX SWAL2)
    $(document).on('click', '.btn-save', function() {
        var form = $('#modal-form');
        var actionUrl = $('#form_action').val() == 'add' ? 'salary_resign_pokok/add' : 'salary_resign_pokok/update';
        
        if ($('#pengguna_id').val() == "" || $('#gaji_pokok').val() == "") {
            Swal.fire("Peringatan", "Nama Karyawan dan Gaji Pokok wajib diisi!", "warning");
            return;
        }

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: form.serialize() + '&csrf_token=' + token,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#main-modal').modal('hide');
                    Swal.fire("Berhasil", response.message, "success");
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire("Gagal", response.message, "error");
                }
            },
            error: function() {
                Swal.fire("Error", "Gagal menyimpan data.", "error");
            }
        });
    });

    // 7. Edit Data
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).attr('data-id');
        clearForm();
        $('#modal-title').text('Edit Gaji Pokok Resign');
        $('#form_action').val('update');
        $('#id').val(id);

        $.ajax({
            url: 'salary_resign_pokok/edit/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#pengguna_id').val(data.pengguna_id).trigger('change.select2');
                $('#nama_karyawan').val(data.nama_karyawan);
                $('#no_pegawai').val(data.no_pegawai);
                $('#status_karyawan').val(data.status_karyawan);
                $('#masa_kerja').val(data.masa_kerja);
                $('#hari_kehadiran').val(data.hari_kehadiran);
                $('#gaji_pokok').val(formatRupiah(data.gaji_pokok));
                $('#pendapatan_lain').val(formatRupiah(data.pendapatan_lain));
                $('#bpjs_kes').val(formatRupiah(data.bpjs_kes));
                $('#bpjs_tk').val(formatRupiah(data.bpjs_tk));
                $('#potongan_lain').val(formatRupiah(data.potongan_lain));
                $('#pph21').val(formatRupiah(data.pph21));
                $('#no_rekening').val(data.no_rekening);
                $('#periode').val(data.periode);
                $('#keterangan').val(data.keterangan);
                $('#main-modal').modal('show');
            }
        });
    });

    // 8. Hapus Data
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).attr('data-id');
        $('#delete_id').val(id);
        $('#delete-modal').modal('show');
    });

    $('.btn-confirm-delete').click(function() {
        $.ajax({
            url: 'salary_resign_pokok/delete/' + $('#delete_id').val(),
            type: 'POST',
            data: { csrf_token: token },
            dataType: 'json',
            success: function(response) {
                $('#delete-modal').modal('hide');
                if (response.status === 'success') {
                    Swal.fire("Terhapus", response.message, "success");
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire("Gagal", response.message, "error");
                }
            }
        });
    });

    // 9. Print Action
    $('#btn-print').click(function() {
        var month = $('#filter_month').val();
        if (!month) {
            Swal.fire("Info", "Pilih bulan filter terlebih dahulu!", "info");
            return;
        }
        window.open('<?= base_url() ?>salary_resign_pokok/print/' + month);
    });

    $(document).on('click', '.btn-print-single', function() {
        var id = $(this).attr('data-id');
        window.open('<?= base_url() ?>salary_resign_pokok/print_single/' + id);
    });

    // 10. Helper Rupiah
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