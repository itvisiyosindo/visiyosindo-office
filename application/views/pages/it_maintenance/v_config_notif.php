<header class="page-header">
    <h2><i class="icons fas fa-cogs"></i>&nbsp;<?= $page_title ?></h2>
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
                <h3>Pengaturan Notifikasi WhatsApp</h3>
                <p class="text-muted">Konfigurasi tujuan pengiriman notifikasi WhatsApp untuk modul IT Asset Maintenance & Helpdesk</p>
            </div>
            
            <?= form_open('it_maintenance/save_config', array('id' => 'form-config-notif', 'autocomplete' => 'off')); ?>
            
            <div class="form-group mb-4">
                <label class="font-weight-bold">Nomor WhatsApp Tim IT (Menerima Notifikasi Tiket Baru) <span class="text-danger">*</span></label>
                <textarea class="form-control" name="it_wa_numbers" rows="3" placeholder="Contoh: 081261457547, 0895410953259" required><?= $it_wa_numbers ?></textarea>
                <small class="text-muted d-block mt-1">
                    <i class="fas fa-info-circle"></i> Gunakan format nomor lengkap (misal: 08xxxxxx atau 628xxxxxx), pisahkan dengan tanda koma ( , ) jika lebih dari satu nomor.
                </small>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold">Nama Grup WhatsApp (Menerima Notifikasi Progress/Timeline) <span class="text-danger">*</span></label>
                <textarea class="form-control" name="wa_groups" rows="3" placeholder="Contoh: IT Support Group, Gudang PT. VYM" required><?= $wa_groups ?></textarea>
                <small class="text-muted d-block mt-1">
                    <i class="fas fa-info-circle"></i> Masukkan nama grup persis seperti yang terdaftar di sistem WhaCenter. Pisahkan dengan tanda koma ( , ) jika lebih dari satu grup.
                </small>
            </div>

            <div class="text-right">
                <button type="button" class="btn btn-success btn-save-config"><i class="fas fa-save"></i> Simpan Konfigurasi</button>
            </div>
            
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.btn-save-config').click(function() {
        var form = $('#form-config-notif');
        var url = form.attr('action');
        var data = form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'JSON',
            success: function(resp) {
                if (resp.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: resp.message || 'Konfigurasi berhasil disimpan',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error!', resp.message || 'Gagal menyimpan konfigurasi', 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error, 'error');
            }
        });
    });
});
</script>
