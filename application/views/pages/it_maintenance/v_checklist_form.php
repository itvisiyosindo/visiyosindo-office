<header class="page-header">
    <h2><i class="icons fas fa-clipboard-check"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="col-xl-8 mb-8 mb-xl-0" style="margin: auto;">
    <div class="card">
        <div class="card-body" style="background-color:#FFF; padding:30px; border-radius:8px;">
            <div class="text-center mb-4">
                <h3>Checklist Pemeliharaan Aset Elektronik</h3>
                <p class="text-muted">Formulir pemeriksaan berkala berkala (per 6 bulan) untuk perangkat IT Perusahaan</p>
            </div>

            <?= form_open('it_maintenance/save_maintenance', array('id' => 'form-checklist-maintenance')); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Pilih Aset Perangkat <span class="text-danger">*</span></label>
                        <select class="form-control" name="id_aset" id="select-aset" style="width: 100%;" required>
                            <option value="">-- Pilih Aset --</option>
                            <?php foreach ($list_aset as $asset) { ?>
                                <option value="<?= $asset->id ?>"
                                    data-pic="<?= $asset->nama_pengguna ? htmlspecialchars($asset->nama_pengguna) : 'Belum Ada PIC' ?>"
                                    data-kode="<?= $asset->kode ?>"
                                    data-kategori="<?= $asset->kategori ?>"
                                    <?= isset($aset) && $aset->id == $asset->id ? 'selected' : '' ?>>
                                    [<?= htmlspecialchars($asset->kategori) ?>] <?= htmlspecialchars($asset->nama_aset) ?> (<?= htmlspecialchars($asset->kode) ?>)
                                </option>
                            <?php } ?>
                        </select>
                        <div id="info-aset" class="mt-1"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Pemegang / PIC Perangkat Saat Ini</label>
                        <input type="text" class="form-control" id="pic-display" readonly placeholder="Pilih aset terlebih dahulu">
                        <small class="text-muted">PIC terisi otomatis sesuai dengan database aset_log terbaru.</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm-dd"}' class="form-control" name="tanggal_cek" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Test Keamanan Antivirus/Sistem <span class="text-danger">*</span></label>
                        <select class="form-control" name="test_keamanan" required>
                            <option value="Lolos">Lolos</option>
                            <option value="Tidak Lolos">Tidak Lolos</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Kondisi Hardware / Fisik <span class="text-danger">*</span></label>
                        <select class="form-control" name="status_hardware" required>
                            <option value="Baik">Baik (Semua Hardware Berfungsi)</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan / Upgrade</option>
                            <option value="Rusak">Rusak / Tidak Layak Pakai</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Status Software / OS <span class="text-danger">*</span></label>
                        <select class="form-control" name="status_software" required>
                            <option value="Baik">Baik (Standard & Legal)</option>
                            <option value="Perlu Update">Perlu Update OS / Driver</option>
                            <option value="Software Ilegal Terdeteksi">Ada Software Ilegal / Virus</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Backup Data ke Google Drive? <span class="text-danger">*</span></label>
                        <div class="form-check pt-2">
                            <input class="form-check-input" type="checkbox" name="backup_gdrive" value="1" id="checkBackup" checked>
                            <label class="form-check-label" for="checkBackup">
                                <strong>Sudah Diverifikasi Backup ke Google Drive</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Rekomendasi IT <span class="text-danger">*</span></label>
                        <select class="form-control" name="rekomendasi" required>
                            <option value="Tetap Digunakan">Tetap Digunakan oleh PIC</option>
                            <option value="Ganti Unit">Rekomendasi Ganti Unit / Scrap</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold">Keterangan Tambahan / Detail Temuan</label>
                <textarea class="form-control" name="keterangan" rows="3" placeholder="Sebutkan kendala spesifik, misalnya: baterai kembung, layar flicker, dll."></textarea>
            </div>

            <!-- Container for Asset Ticket History -->
            <div id="riwayat-tiket-container" class="mb-4" style="display: none;"></div>

            <div class="text-right">
                <a href="<?= base_url('it_maintenance/data_maintenance') ?>" class="btn btn-default"><i class="fas fa-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-success btn-submit-maintenance"><i class="fas fa-save"></i> Simpan Hasil Pemeriksaan</button>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Custom Select2 search matcher to match text, category, code, or PIC
        function matchCustomAset(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }
            if (typeof data.text === 'undefined') {
                return null;
            }

            var searchTerm = params.term.toLowerCase();

            // Search option text
            if (data.text.toLowerCase().indexOf(searchTerm) > -1) {
                return data;
            }

            // Search within custom attributes
            var element = $(data.element);
            if (element.length) {
                var kategori = (element.attr('data-kategori') || '').toLowerCase();
                var kode = (element.attr('data-kode') || '').toLowerCase();
                var pic = (element.attr('data-pic') || '').toLowerCase();

                if (kategori.indexOf(searchTerm) > -1 || kode.indexOf(searchTerm) > -1 || pic.indexOf(searchTerm) > -1) {
                    return data;
                }
            }

            return null;
        }

        // Initialize select2
        if ($.fn.select2) {
            $('#select-aset').select2({
                placeholder: '-- Pilih Aset --',
                allowClear: true,
                matcher: matchCustomAset
            });
        }

        // Dynamic PIC update & Ticket/Repair History update
        $('#select-aset').change(function() {
            var selected = $(this).find('option:selected');
            var pic = selected.data('pic');
            var kode = selected.data('kode');
            var kategori = selected.data('kategori');
            var id_aset = $(this).val();

            if (id_aset) {
                $('#pic-display').val(pic);
                $('#info-aset').html('<small class="text-info">Kode: ' + kode + ' | Kategori: ' + kategori + '</small>');

                // Fetch ticket history
                $('#riwayat-tiket-container').show().html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Memuat riwayat perbaikan...</div>');
                $.ajax({
                    url: '<?= base_url("it_maintenance/get_asset_ticket_history") ?>',
                    type: 'POST',
                    data: {
                        id_aset: id_aset
                    },
                    success: function(html) {
                        $('#riwayat-tiket-container').html(html);
                    },
                    error: function() {
                        $('#riwayat-tiket-container').html('<div class="alert alert-danger" style="font-size:13px;">Gagal memuat riwayat perbaikan.</div>');
                    }
                });
            } else {
                $('#pic-display').val('');
                $('#info-aset').html('');
                $('#riwayat-tiket-container').hide().html('');
            }
        });

        // Trigger change if editing or deep-linked
        if ($('#select-aset').val()) {
            $('#select-aset').trigger('change');
        }

        // Submit form
        $('#form-checklist-maintenance').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = $('.btn-submit-maintenance');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'JSON',
                success: function(resp) {
                    if (resp.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: resp.message || 'Checklist maintenance berhasil disimpan',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = '<?= base_url("it_maintenance/data_maintenance") ?>';
                        });
                    } else {
                        Swal.fire('Error!', resp.message || 'Gagal menyimpan data', 'error');
                        btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Hasil Pemeriksaan');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error, 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Hasil Pemeriksaan');
                }
            });
        });
    });
</script>