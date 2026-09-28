<header class="page-header">
    <h2><i class="icons fas fa-bullhorn"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <section class="card shadow-sm mb-4" style="border: none; border-radius: 8px;">
            <header class="card-header bg-white border-bottom-0 pt-4 px-4">
                <h4 class="card-title font-weight-bold text-dark mb-0">Buat Pengumuman Baru</h4>
                <p class="text-muted mb-0" style="font-size: 12.5px;">Tulis pesan informasi yang akan disiarkan ke seluruh karyawan.</p>
            </header>
            <div class="card-body px-4 pb-4">
                <?= form_open('announcement/add', array('id' => 'announcement-form', 'autocomplete' => 'off')); ?>

                <div class="form-group mb-3">
                    <label for="message" class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Isi Pengumuman <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="message" id="message" rows="6" placeholder="Tuliskan isi pengumuman di sini..." style="border-radius: 6px; font-family: 'Poppins', sans-serif;" required></textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="lampiran" class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Link Lampiran (Misal: Google Drive / URL)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                        </div>
                        <input type="text" class="form-control" id="lampiran" name="lampiran" placeholder="https://drive.google.com/..." style="border-radius: 0 6px 6px 0;">
                    </div>
                    <small class="text-muted" style="font-size: 11px;">Masukkan URL lampiran pendukung jika ada.</small>
                </div>

                <div class="form-group mb-4">
                    <label for="applieddate" class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Tanggal Berlaku</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" class="form-control" id="applieddate" name="applieddate" value="<?= date('Y-m-d'); ?>" style="border-radius: 0 6px 6px 0;">
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between bg-white border-top-0 px-0 pb-0 pt-3">
                    <a href="<?= base_url('Announcement/daftar') ?>" class="btn btn-default" style="border-radius: 4px;"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="button" class="btn btn-info btn-save btn-large" style="border-radius: 4px; padding: 6px 20px;"><i class="fas fa-paper-plane"></i> Publish Now</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </section>
    </div>
</div>