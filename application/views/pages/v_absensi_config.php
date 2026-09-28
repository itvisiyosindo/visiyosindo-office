<header class="page-header">
    <h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div class="col-xl-8 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:10%">
        <div class="text-center mt-0">
            <h2>Konfigurasi Absensi</h2>
        </div>
        <?= form_open('absensi_config/update', array('id' => 'abseni-config-form', 'autocomplete' => 'off')); ?>
        <div>
            <div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="nama" class="form-control-label">Absen di buka pada pukul <span class="text-danger">*</span> :</strong>
                            <input type="time" class="form-control" name="boleh_absen" value="<?= $config[0]->boleh_absen ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="nama" class="form-control-label">Waktu Masuk <span class="text-danger">*</span> :</strong>
                            <input type="time" class="form-control" name="jam_masuk" value="<?= $config[0]->jam_masuk ?>" required>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="username" class="form-control-label">Waktu Keluar<span class="text-danger">*</span> :</strong>
                            <input type="time" class="form-control" name="jam_keluar" value="<?= $config[0]->jam_keluar ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="username" class="form-control-label">Waktu Masuk Pak Anto (Office Boy)<span class="text-danger">*</span> :</strong>
                            <input type="time" class="form-control" name="jam_masuk_pak_anto" value="<?= $config[0]->jam_masuk_pak_anto ?>" required>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <label class=" col-form-label">Absen Istirahat Hari Senin - Kamis</label><br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="username" class="form-control-label">Absen Dibuka<span class="text-danger">*</span> :</strong>
                            <input type="time" class="form-control" name="mulai_rehat_b" value="<?= $config[0]->mulai_rehat_b ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="username" class="form-control-label">Absen Ditutup<span class="text-danger">*</span> :</strong>
                            <input type="time" class="form-control" name="akhir_rehat_b" value="<?= $config[0]->akhir_rehat_b ?>" required>
                        </div>
                    </div>
                </div>
                <br>
                <label class=" col-form-label">Absen Istirahat Hari Jumat</label><br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="username" class="form-control-label">Absen Dibuka<span class="text-danger">*</span> :</strong>
                            <input type="time" class="form-control" name="mulai_rehat_a" value="<?= $config[0]->mulai_rehat_a ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong for="username" class="form-control-label">Absen Ditutup<span class="text-danger">*</span> :</strong>
                            <input type="time" class="form-control" name="akhir_rehat_a" value="<?= $config[0]->akhir_rehat_a ?>" required>
                        </div>
                    </div>
                </div>
                <br>
                <strong for="username" class="">Tolak Absen</strong><br>
                <input type="checkbox" name="is_libur" <?= $config[0]->is_libur == 1 ? "checked" : '' ?>><br>
                <small class="text-danger">*jika libur pastikan kotak terceklis</small><br>
                <br>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success btn-save">Simpan</button>
            <?= form_close(); ?>
        </div>
        <br>
        <br>
        <br>

        <!-- WFA Configuration Section -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bx bx-work-outline"></i>
                    Konfigurasi WFA (Work From Anywhere)
                </h6>
            </div>
            <div class="card-body">
                <form id="formWFAConfig">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="toggleWFA"
                            name="is_wfa_active"
                            <?php echo (isset($config[0]->is_wfa_active) && $config[0]->is_wfa_active) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="toggleWFA">
                            <strong>Aktifkan Fitur WFA di Sistem</strong>
                        </label>
                    </div>
                    <small class="text-muted d-block mb-3">
                        <i class="bx bx-info-circle"></i>
                        Ketika WFA diaktifkan, karyawan dengan status WFA dapat memilih opsi
                        "Bekerja dari Mana Saja" saat melakukan absensi. Sistem akan set
                        <strong>tanpa_tunjangan = 1</strong> sehingga tunjangan harian tidak dihitung.
                    </small>
                    <button type="button" class="btn btn-primary btn-sm" onclick="updateWFAConfig()">
                        <i class="bx bx-save"></i> Simpan Konfigurasi WFA
                    </button>
                </form>
            </div>
        </div>

        <!-- Link ke Manajemen WFA Pengguna -->
        <div class="alert alert-info">
            <i class="bx bx-user"></i>
            <strong>Manajemen WFA Pengguna:</strong>
            <a href="<?php echo base_url('absensi_config/manage_pengguna_wfa'); ?>" class="btn btn-sm btn-outline-info">
                Atur Status WFA Karyawan →
            </a>
        </div>

        <br>
        <br>
        <br>

        <!-- Untuk Setting Tanggal Libur -->
        <div class="row">
            <div class="col">
                <div class="text-center mt-0">
                    <h4>Tanggal Hari Libur (Diluar Sabtu & Minggu)</h4>
                    <h5>Untuk Absen Security Malam</h5>
                </div>

                <div class="">
                    <a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th> Tanggal</th>
                                    <th> Keterangan</th>
                                    <th> Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-dark text-light">
                    <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Tanggal Libur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
                <div class="modal-body">
                    <div class="dt-kategori-form">

                        <div class="form-group">
                            <label for="tgl" class="form-control-label">Tanggal <span class="text-danger">*</span> :</label>
                            <div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="text" class="form-control" id="tgl" name="tgl" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ket" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
                            <textarea name="ket" class="form-control" id="ket" cols="15" rows="3"></textarea>
                        </div>




                    </div>
                </div>
                <div class="modal-footer">
                    <div class="is_aktif"></div>
                    <input type="hidden" id="id" name="id">
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
                    url: 'absensi_config/pagination',
                    type: 'POST',
                    data: function(e) {
                        e.tahun = $('#tahun').val()
                        e.csrf_token = token
                    }
                },
                columnDefs: [{
                    targets: [0, 1, 2, 3],
                    className: 'text-center'
                }]
            })


            function updateDatatable() {
                table.ajax.reload(null, false)
            }

            $('#btn-show-add-form').click(function() {
                $('.form-control').val(null)
                $('.btn-isactive').remove()
                var object = 'absensi_config'
                $('#main-modal #modal-form').attr('action', 'absensi_config/addLibur')
                $('#main-modal').modal()
            })

            $(document).on('click', '.btn-edit', function() {
                $('.btn-isactive').remove()
                var object = 'absensi_config'
                $('#main-modal #modal-form').attr('action', 'absensi_config/updateLibur')
                $('#main-modal').modal()

                var id = $(this).attr("data-id")
                fetch(object + '/editLibur/' + id)
                    .then(function(resp) {
                        return resp.json()
                    })
                    .then(function(data) {
                        $('#main-modal #tgl').val(data[0].tgl)
                        $('#main-modal #ket').val(data[0].ket)
                        $('#main-modal #id').val(id)
                    })
            })

        })
    </script>

    <script>
        // WFA Configuration Function
        function updateWFAConfig() {
            var isWfaActive = document.getElementById('toggleWFA').checked ? 'on' : 'off';

            console.log('Sending WFA Config:', {
                is_wfa_active: isWfaActive
            });

            $.ajax({
                url: '<?php echo base_url("absensi_config/update_wfa_config"); ?>',
                type: 'POST',
                data: {
                    is_wfa_active: isWfaActive,
                    csrf_token: token // Add CSRF token
                },
                dataType: 'json', // Explicitly set JSON response type
                success: function(response) {
                    console.log('Response received:', response);

                    if (response.status === 'success') {
                        Swal.fire('Sukses!', response.message || 'Konfigurasi WFA berhasil diupdate', 'success').then(function() {
                            if (response.reload === 'reload_page') {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire('Error!', response.message || 'Terjadi kesalahan', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Full Response:', xhr.responseText);
                    console.error('AJAX Error:', error);
                    console.error('Status:', status);
                    Swal.fire('Error!', 'Terjadi kesalahan: ' + error, 'error');
                }
            });
        }
    </script>