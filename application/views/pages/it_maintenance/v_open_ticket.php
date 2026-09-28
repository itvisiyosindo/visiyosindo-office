<header class="page-header">
    <h2><i class="icons fas fa-ticket-alt"></i>&nbsp;<?= $page_title ?></h2>
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
                <h3>Form Pengajuan Tiket Kendala IT</h3>
                <p class="text-muted">Laporkan kerusakan atau masalah perangkat keras/lunak Anda kepada Tim IT</p>
            </div>

            <?= form_open_multipart('it_maintenance/save_ticket', array('id' => 'form-open-ticket')); ?>

            <div class="form-group mb-3">
                <label class="font-weight-bold">Aset Terkait <span class="text-danger">*</span></label>
                <select data-plugin-selectTwo class="form-control populate" name="id_aset" required>
                    <option value="">-- Pilih Aset Anda --</option>
                    <?php if (!empty($user_assets)) { ?>
                        <?php foreach ($user_assets as $asset) { ?>
                            <?php $selected = (!empty($my_asset_id) && $asset->id == $my_asset_id) ? 'selected' : ''; ?>
                            <option value="<?= $asset->id ?>" <?= $selected ?>><?= $asset->nama ?> (<?= $asset->kode ?>) - [<?= $asset->kategori ?>] - [<?= $asset->nama_pic ?>]</option>
                        <?php } ?>
                    <?php } ?>
                    <option value="0">Lainnya / Tidak Ada dalam Daftar</option>
                </select>
                <small class="text-muted d-block mt-1">
                    <i class="fas fa-info-circle"></i> Daftar di atas menampilkan aset perusahaan yang saat ini ditugaskan kepada Anda. Pilih "Lainnya" jika kendala umum atau aset belum terdaftar.
                </small>
            </div>

            <div class="form-group mb-3">
                <label class="font-weight-bold">Tingkat Indikator Prioritas <span class="text-danger">*</span></label>
                <select data-plugin-selectTwo class="form-control populate" name="prioritas" required>
                    <option value="">-- Pilih Tingkat Prioritas --</option>
                    <option value="Rendah">Rendah (Low)</option>
                    <option value="Sedang">Sedang (Medium)</option>
                    <option value="Tinggi">Tinggi (High)</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="font-weight-bold">Subjek Kendala <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="subject" placeholder="Contoh: Laptop mati total, Printer macet, OS minta update" required>
            </div>

            <div class="form-group mb-3">
                <label class="font-weight-bold">Deskripsi Masalah / Kerusakan <span class="text-danger">*</span></label>
                <textarea class="form-control" name="deskripsi" rows="5" placeholder="Detail kendala yang dialami. Sebutkan kronologi atau error message jika ada." required></textarea>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold">Link Google Drive / Lampiran <span class="text-danger">*</span></label>
                <input type="url" class="form-control" name="file_pendukung" placeholder="Contoh: https://drive.google.com/drive/folders/... atau https://drive.google.com/file/..." required>
                <small class="text-muted d-block mt-1">
                    <i class="fas fa-info-circle"></i> Masukkan URL/Link Google Drive berisi foto, screenshot, atau file pendukung kendala Anda.
                </small>
            </div>

            <!-- Container for Asset Ticket History -->
            <div id="riwayat-tiket-container" class="mb-4" style="display: none;"></div>

            <div class="text-right">
                <a href="<?= base_url('it_maintenance/my_ticket') ?>" class="btn btn-default"><i class="fas fa-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary btn-submit-ticket"><i class="fas fa-paper-plane"></i> Kirim Tiket</button>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for Aset Terkait
        if ($.isFunction($.fn.select2)) {
            $('[data-plugin-selectTwo]').select2({
                theme: 'bootstrap',
                width: '100%'
            });
        }

        // Dynamic Ticket/Repair History update
        $('select[name="id_aset"]').change(function() {
            var id_aset = $(this).val();
            if (id_aset && id_aset !== '0') {
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
                $('#riwayat-tiket-container').hide().html('');
            }
        });

        // Trigger on load if preselected
        if ($('select[name="id_aset"]').val()) {
            $('select[name="id_aset"]').trigger('change');
        }

        $('#form-open-ticket').submit(function(e) {
            e.preventDefault();

            var form = $(this);
            var formData = new FormData(form[0]);
            var btn = $('.btn-submit-ticket');

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function(resp) {
                    if (resp.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: resp.message || 'Tiket berhasil dikirim ke tim IT',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = '<?= base_url("it_maintenance/my_ticket") ?>';
                        });
                    } else {
                        Swal.fire('Error!', resp.message || 'Gagal mengirim tiket', 'error');
                        btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Tiket');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error, 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Tiket');
                }
            });
        });
    });
</script>