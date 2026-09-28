<!-- View: v_pm_form.php - Form Input/Edit Preventif Maintenance -->

<?php
$isEdit = isset($mode) && $mode == 'edit';
$hasData = isset($pm);
$pm = isset($pm) ? $pm : null;
$pelanggan = isset($pelanggan) ? $pelanggan : [];
$kategori = isset($kategori) ? $kategori : [];
$pihak_ketiga = isset($pihak_ketiga) ? $pihak_ketiga : [];
$pengguna = isset($pengguna) ? $pengguna : [];
?>

<style>
    .card {
        border: none;
        border-radius: 10px;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px 10px 0 0 !important;
    }

    .card-header h6 {
        color: #fff !important;
    }

    .form-control-label {
        font-weight: 600;
        font-size: 13px;
        color: #333;
    }

    .required::after {
        content: " *";
        color: red;
    }

    .info-box {
        background-color: #e7f3ff;
        border-left: 4px solid #2196F3;
        padding: 12px;
        border-radius: 4px;
        margin-bottom: 15px;
        font-size: 12px;
        color: #1976D2;
    }
</style>

<header class="page-header">
    <h2><i class="icons icon-user-follow"></i>&nbsp;Preventif Maintenance</h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Form Preventif Maintenance</span></li>
        </ol>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <form id="formPM" method="POST" enctype="multipart/form-data">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><?= $isEdit ? 'Edit' : 'Tambah' ?> Preventif Maintenance</h6>
                    </div>
                    <div class="card-body">

                        <!-- Input Mode & ID -->
                        <input type="hidden" name="mode" value="<?= isset($mode) ? $mode : 'add' ?>">
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="id_pm" value="<?= encrypt($pm->id_pm) ?>">
                        <?php endif; ?>

                        <!-- Penerima -->
                        <div class="form-group mb-3">
                            <label class="form-control-label required">Penerima PM (Teknisi)</label>
                            <select class="form-control select2" data-plugin-selectTwo name="id_penerima" id="id_penerima" required>
                                <option value="">- Pilih Penerima -</option>
                                <?php foreach ($pengguna as $u): ?>
                                    <option value="<?= $u->pengguna_id ?>" <?= $hasData && $pm->id_penerima == $u->pengguna_id ? 'selected' : '' ?>>
                                        <?= $u->nama ?><?= !empty($u->jabatan) ? ' - ' . $u->jabatan : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Internal user yang akan menerima dan mengerjakan PM</small>
                        </div>

                        <!-- Pelanggan -->
                        <div class="form-group mb-3">
                            <label class="form-control-label required">Pelanggan</label>
                            <select class="form-control select2" data-plugin-selectTwo name="id_pelanggan" id="id_pelanggan">
                                <option value="">- Pilih Pelanggan -</option>
                                <?php foreach ($pelanggan as $p): ?>
                                    <option value="<?= $p->id_pelanggan ?>"
                                        data-kontak="<?= $p->kontak ?>"
                                        data-cpname="<?= $p->cpname ?>"
                                        <?= $hasData && $pm->id_pelanggan == $p->id_pelanggan ? 'selected' : '' ?>>
                                        <?= $p->identitas_pelanggan ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Pilih customer untuk Preventif Maintenance</small>
                        </div>

                        <!-- Nama Contact Person (auto-filled) -->
                        <div class="form-group mb-3">
                            <label class="form-control-label">Nama Contact Person</label>
                            <input type="text" class="form-control" name="nama_cp" id="nama_cp"
                                value="<?= $hasData ? $pm->nama_cp : '' ?>"
                                placeholder="Masukkan Nama CP Customer untuk Notifikasi">
                            <small class="form-text text-muted">Nama Contact Person Customer</small>
                        </div>

                        <!-- Contact Person / WA (auto-filled) -->
                        <div class="form-group mb-3">
                            <label class="form-control-label">Contact Person / WA</label>
                            <input type="text" class="form-control" name="nomer_cp" id="nomer_cp"
                                value="<?= $hasData ? $pm->nomer_cp : '' ?>"
                                placeholder="Masukkan Nomer WA CP Customer untuk Notifikasi">
                            <small class="form-text text-muted">Nomor WhatsApp Contact Person</small>
                        </div>

                        <!-- Subject -->
                        <div class="form-group mb-3">
                            <label class="form-control-label required">Subject</label>
                            <input type="text" class="form-control" name="subject"
                                value="<?= $hasData ? $pm->subject : '' ?>"
                                placeholder="Masukkan Subject PM (Instalasi, Trouble, UKES, UPAR, etc)" required>
                            <small class="form-text text-muted">Judul atau topik PM</small>
                        </div>

                        <!-- Kategori Tiket -->
                        <div class="form-group mb-3">
                            <label class="form-control-label required">Kategori PM</label>
                            <select class="form-control select2" data-plugin-selectTwo name="id_topik" required>
                                <option value="">- Pilih Kategori -</option>
                                <?php foreach ($kategori as $k): ?>
                                    <option value="<?= $k->id_topik ?>"
                                        <?= $hasData && $pm->id_topik == $k->id_topik ? 'selected' : '' ?>>
                                        <?= $k->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Kategori Preventif Maintenance</small>
                        </div>

                        <!-- Prioritas -->
                        <div class="form-group mb-3">
                            <label class="form-control-label required">Prioritas</label>
                            <select class="form-control select2" data-plugin-selectTwo name="prioritas" required>
                                <option value="">- Pilih Prioritas -</option>
                                <option value="1" <?= $hasData && $pm->prioritas == 1 ? 'selected' : '' ?>>Low</option>
                                <option value="2" <?= $hasData && $pm->prioritas == 2 ? 'selected' : '' ?>>Medium</option>
                                <option value="3" <?= $hasData && $pm->prioritas == 3 ? 'selected' : '' ?>>High</option>
                                <option value="4" <?= $hasData && $pm->prioritas == 4 ? 'selected' : '' ?>>Urgent</option>
                            </select>
                            <small class="form-text text-muted">Tingkat prioritas PM</small>
                        </div>

                        <!-- Pihak Ketiga (PT/CV) -->
                        <div class="form-group mb-3">
                            <label class="form-control-label required">Pihak Ketiga (PT/CV)</label>
                            <select class="form-control select2" data-plugin-selectTwo name="id_pihak_ketiga" required>
                                <option value="">-- Pilih Pihak Ketiga --</option>

                                <option value="0" <?= $hasData && $pm->id_pihak_ketiga == '0' ? 'selected' : '' ?>>Penjualan Langsung dari VYM</option>

                                <?php foreach ($pihak_ketiga as $pk): ?>
                                    <option value="<?= $pk->id_pelanggan ?>"
                                        <?= $hasData && $pm->id_pihak_ketiga == $pk->id_pelanggan ? 'selected' : '' ?>>
                                        <?= $pk->nama ?> - <?= $pk->kota ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">PT/CV yang terkait dengan Preventif Maintenance, atau pilih Penjualan Langsung dari VYM</small>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group mb-3">
                            <label class="form-control-label required">Deskripsi</label>
                            <textarea class="form-control summernote" name="deskripsi"
                                placeholder="Masukkan deskripsi detail PM" required><?= $hasData ? $pm->deskripsi : '' ?></textarea>
                            <small class="form-text text-muted">Jelaskan detail maintenance yang akan dilakukan</small>
                        </div>

                        <!-- File Pendukung (Google Drive Link) -->
                        <div class="form-group mb-3">
                            <label class="form-control-label">File Pendukung</label>
                            <input type="text" class="form-control" name="file_pendukung"
                                value="<?= $hasData ? $pm->file_pendukung : '' ?>"
                                placeholder="Masukkan Link Google Drive untuk data pendukung">
                            <small class="form-text text-muted">Link Google Drive untuk dokumen pendukung</small>
                        </div>

                        <!-- File Invoice (Google Drive Link) -->
                        <div class="form-group mb-3">
                            <label class="form-control-label">File Invoice</label>
                            <input type="text" class="form-control" name="file_invoice"
                                value="<?= $hasData ? $pm->file_invoice : '' ?>"
                                placeholder="Masukkan Link Google Drive untuk file invoice">
                            <small class="form-text text-muted">Link Google Drive untuk file invoice</small>
                        </div>

                        <!-- Waktu Pengerjaan (Date Range) -->
                        <div class="form-group mb-3">
                            <label class="form-control-label required">Waktu Pengerjaan</label>
                            <div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="text" class="form-control" id="start" name="start"
                                    value="<?= $hasData && $pm->waktu_mulai ? date('d-m-Y', strtotime($pm->waktu_mulai)) : '' ?>"
                                    placeholder="Tanggal Mulai" required>
                                <span class="input-group-text border-start-0 border-end-0 rounded-0">
                                    to
                                </span>
                                <input type="text" class="form-control" id="end" name="end"
                                    value="<?= $hasData && $pm->waktu_selesai ? date('d-m-Y', strtotime($pm->waktu_selesai)) : '' ?>"
                                    placeholder="Tanggal Selesai" required>
                            </div>
                            <small class="form-text text-muted">Tentukan rentang waktu pelaksanaan PM</small>
                        </div>

                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?= $isEdit ? 'Update' : 'Simpan' ?>
                        </button>
                        <a href="<?= base_url('preventif-maintenance') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Informasi</h6>
                </div>
                <div class="card-body">
                    <?php if ($hasData): ?>
                        <div class="mb-3">
                            <strong>Kode PM:</strong>
                            <br><?= $pm->kode_pm ?>
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <br><?= $this->load->view('pages/preventif_maintenance/status_badge', ['status' => $pm->status_pm], true) ?>
                        </div>
                        <div class="mb-3">
                            <strong>Penerima PM:</strong>
                            <br><?= htmlspecialchars($pm->nama_penerima ?? '-') ?>
                        </div>
                        <div class="mb-3">
                            <strong>Dibuat oleh:</strong>
                            <br><?= $pm->nama_pembuat ?>
                        </div>
                        <div class="mb-3">
                            <strong>Tanggal Dibuat:</strong>
                            <br><?= date('d-m-Y H:i', strtotime($pm->created_at)) ?>
                        </div>
                        <div class="mb-3">
                            <strong>Update Terakhir:</strong>
                            <br><?= date('d-m-Y H:i', strtotime($pm->updated_at)) ?>
                        </div>
                    <?php else: ?>
                        <div class="info-box">
                            <strong>Catatan:</strong> Isi semua field yang bertanda * (wajib). Contact Person akan otomatis terisi dari data Pelanggan jika tersedia.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pastikan jQuery sudah di-load karena kita menggunakan Select2
        if (typeof $ !== 'undefined') {

            // Inisialisasi Summernote jika tersedia
            if (typeof $.fn.summernote !== 'undefined') {
                $('.summernote').summernote({
                    height: 250,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview']]
                    ]
                });
            }

            // Deteksi perubahan pada dropdown Pelanggan
            $('#id_pelanggan').on('change', function() {
                // Ambil option yang sedang dipilih
                var selectedOption = $(this).find('option:selected');

                // Ambil data dari attribute data-cpname dan data-kontak
                var namaCp = selectedOption.data('cpname');
                var nomerCp = selectedOption.data('kontak');

                // Masukkan ke dalam input field jika nilainya ada
                $('#nama_cp').val(namaCp ? namaCp : '');
                $('#nomer_cp').val(nomerCp ? nomerCp : '');
            });

            // Opsional: Jika mode 'add' (tambah baru), kosongkan field saat load
            // Jika mode 'edit', biarkan value PHP yang mengisi datanya
            var isEdit = <?= isset($isEdit) && $isEdit ? 'true' : 'false' ?>;
            if (!isEdit && $('#id_pelanggan').val() === "") {
                $('#nama_cp').val('');
                $('#nomer_cp').val('');
            }

            // Handle form submission via AJAX
            $('#formPM').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let btnSubmit = $(this).find('button[type="submit"]');
                let originalBtnText = btnSubmit.html();

                // Disable button and show loading state
                btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: '<?= base_url("preventif-maintenance/save") ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let res = typeof response === 'string' ? JSON.parse(response) : response;
                        if (res.status === 'success') {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: res.message || res.msg || 'Data berhasil disimpan',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            setTimeout(() => {
                                window.location.href = '<?= base_url("preventif-maintenance") ?>';
                            }, 1500);
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: res.message || res.msg || 'Gagal menyimpan data',
                                icon: 'error'
                            });
                            btnSubmit.prop('disabled', false).html(originalBtnText);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Terjadi kesalahan!',
                            text: xhr.responseText || 'Error system',
                            icon: 'error'
                        });
                        btnSubmit.prop('disabled', false).html(originalBtnText);
                    }
                });
            });

        } else {
            console.error('jQuery tidak ditemukan, script form handler tidak berjalan.');
        }
    });
</script>