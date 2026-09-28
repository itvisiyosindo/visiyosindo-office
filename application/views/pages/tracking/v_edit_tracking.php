<header class="page-header">
    <h2><i class="icons fas fa-edit"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('tracking') ?>"><i class="fas fa-arrow-left"></i> Kembali ke List</a></li>
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
$tracking = $data_tracking[0];
$tracking_type = $tracking->tracking_type ?? 'pengeluaran_barang';
$is_pengiriman_stok = ($tracking_type == 'pengiriman_stok');
$is_serah_terima_barang = ($tracking_type == 'serah_terima_barang');

// Label logic
$type_label = 'Pengeluaran Barang (SJBK)';
$type_badge = 'primary';
if ($is_pengiriman_stok) {
    $type_label = 'Pengiriman Stok (TTBK)';
    $type_badge = 'warning';
} elseif ($is_serah_terima_barang) {
    $type_label = 'Serah Terima Barang (STTB)';
    $type_badge = 'info';
}
?>

<style>
    .form-control {
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 0.95rem;
        height: 38px;
    }

    textarea.form-control {
        height: auto;
        min-height: 80px;
    }

    .form-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }

    .form-section h5 {
        color: #495057;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .form-section.status-section {
        background: #e3f2fd;
        border-color: #bbdefb;
    }

    .readonly-info {
        background: #e9ecef;
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid #ced4da;
        min-height: 38px;
    }

    .status-history-table th {
        background: #007bff;
        color: white;
        border: none;
    }

    #panelDiterima {
        background: #f1f8f4;
        border: 2px solid #28a745;
        border-radius: 8px;
        padding: 15px;
    }

    #panelDiterima .alert {
        margin-bottom: 15px;
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    #panelDiterima label {
        font-weight: 600;
        color: #155724;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-edit mr-2"></i> Edit Data Tracking - <span class="badge badge-<?= $type_badge ?>"><?= $type_label ?></span></h5>
            </div>
            <div class="card-body">
                <?= form_open('tracking/update_full', ['id' => 'form-edit-tracking']) ?>
                <input type="hidden" name="id_tracking" value="<?= encrypt($tracking->id_tracking) ?>">
                <input type="hidden" name="tracking_type" value="<?= $tracking_type ?>">

                <div class="form-section">
                    <h5><i class="fas fa-file-alt mr-2"></i>Informasi Dokumen Sumber</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label>No. Surat Jalan / Kode</label>
                            <div class="readonly-info"><strong><?= $tracking->no_sj ?></strong></div>
                        </div>
                        <div class="col-md-4">
                            <label>Gudang/Pihak Pengirim</label>
                            <div class="readonly-info"><?= $tracking->nama_gudang ?? $tracking->nama_pihak1_stb ?? '-' ?></div>
                        </div>
                        <div class="col-md-4">
                            <label>Tujuan/Customer</label>
                            <div class="readonly-info"><?= $tracking->nama_customer ?? $tracking->gudang_tujuan ?? $tracking->nama_pihak2_stb ?? '-' ?></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5><i class="fas fa-truck mr-2"></i>Informasi Pengiriman</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>PIC Penerima <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pic_penerima" value="<?= htmlspecialchars($tracking->pic_penerima ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ekspedisi <span class="text-danger">*</span></label>
                                <select class="form-control" data-plugin-selectTwo name="id_ekspedisi" style="width: 100%;" required>
                                    <option value="">- Pilih Ekspedisi -</option>
                                    <?php foreach ($list_ekspedisi as $eks): ?>
                                        <option value="<?= $eks->id_ekspedisi ?>" <?= ($tracking->id_ekspedisi == $eks->id_ekspedisi) ? 'selected' : '' ?>><?= $eks->nama_ekspedisi ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Pengiriman <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tgl_pengiriman" value="<?= date('Y-m-d', strtotime($tracking->tgl_pengiriman)) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Barang Diterima</label>
                                <input type="date" class="form-control" name="tgl_sampai" value="<?= !empty($tracking->tgl_sampai) ? date('Y-m-d', strtotime($tracking->tgl_sampai)) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Biaya Pengiriman</label>
                                <input type="text" class="form-control" id="biaya" name="biaya" value="<?= number_format($tracking->biaya ?? 0, 0, ',', '.') ?>" onkeyup="formatRupiah(this)">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section status-section">
                    <h5><i class="fas fa-history mr-2"></i>History Status Tracking</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered bg-white" id="statusTable">
                            <thead>
                                <tr>
                                    <th width="20%">Tanggal</th>
                                    <th width="15%">Status</th>
                                    <th width="45%">Keterangan</th>
                                    <th width="20%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Gunakan list_status dari controller jika ada, fallback ke array lokal
                                $status_labels = isset($list_status) ? $list_status : [
                                    1 => 'Proses Kirim',
                                    2 => 'Manifest Berangkat',
                                    3 => 'Proses Sortir',
                                    4 => 'Pengantaran Kurir',
                                    5 => 'Diterima',
                                    6 => 'Menunggu Konfirmasi'
                                ];

                                $status_classes = [
                                    1 => 'secondary',
                                    2 => 'primary',
                                    3 => 'info',
                                    4 => 'warning',
                                    5 => 'success',
                                    6 => 'dark'
                                ];

                                foreach ($status_history as $sh):
                                    $label = $status_labels[$sh->id_status] ?? 'Unknown';
                                    $class = $status_classes[$sh->id_status] ?? 'secondary';
                                ?>
                                    <tr class="status-row" data-status-id="<?= $sh->id ?>">
                                        <td>
                                            <span class="display-text"><?= date('d-M-Y H:i', strtotime($sh->created_at)) ?></span>
                                            <input type="datetime-local" class="form-control form-control-sm edit-input tgl-input" style="display:none;" value="<?= date('Y-m-d\TH:i', strtotime($sh->created_at)) ?>">
                                        </td>
                                        <td><span class="badge badge-<?= $class ?>"><?= $label ?></span></td>
                                        <td>
                                            <div class="display-text">
                                                <?= htmlspecialchars($sh->keterangan_konfirmasi ?? '-') ?>
                                                <?php if ($sh->id_status == 5 && !empty($sh->nama_penerima)): ?>
                                                    <br><small class="text-success font-weight-bold">
                                                        <i class="fas fa-user-check"></i> Diterima oleh: <?= htmlspecialchars($sh->nama_penerima) ?>
                                                        <?php if (!empty($sh->tgl_penerima)): ?>
                                                            <br><i class="fas fa-calendar-check"></i> Tanggal: <?= date('d-M-Y', strtotime($sh->tgl_penerima)) ?>
                                                        <?php endif; ?>
                                                        <?php if (!empty($sh->bukti_penerima)): ?>
                                                            <br><i class="fas fa-file-image"></i> Bukti:
                                                            <?php if (filter_var($sh->bukti_penerima, FILTER_VALIDATE_URL)): ?>
                                                                <a href="<?= htmlspecialchars($sh->bukti_penerima) ?>" target="_blank" class="text-primary">
                                                                    <i class="fas fa-external-link-alt"></i> Lihat Bukti
                                                                </a>
                                                            <?php else: ?>
                                                                <?= htmlspecialchars($sh->bukti_penerima) ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            <textarea class="form-control form-control-sm edit-input ket-input" style="display:none;"><?= $sh->keterangan_konfirmasi ?></textarea>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-xs btn-warning btnEditRow"><i class="fas fa-edit"></i></button>
                                            <button type="button" class="btn btn-xs btn-success btnSaveRow" style="display:none;"><i class="fas fa-check"></i></button>
                                            <button type="button" class="btn btn-xs btn-danger btnDeleteRow" style="display:none;"><i class="fas fa-trash"></i></button>
                                            <button type="button" class="btn btn-xs btn-secondary btnCancelRow" style="display:none;"><i class="fas fa-times"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label>Tambah Status Baru</label>
                            <select class="form-control" name="new_status" id="new_status" onchange="togglePanelDiterima()">
                                <option value="">- Pilih Status -</option>
                                <option value="1">1 - Proses Kirim</option>
                                <option value="2">2 - Manifest Berangkat</option>
                                <option value="3">3 - Proses Sortir</option>
                                <option value="4">4 - Pengantaran Kurir</option>
                                <option value="5">5 - Diterima</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label>Keterangan Tambahan</label>
                            <input type="text" class="form-control" name="new_keterangan" placeholder="Opsional...">
                        </div>
                    </div>

                    <!-- Panel untuk Status Diterima -->
                    <div class="row mt-3" id="panelDiterima" style="display:none;">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <strong><i class="fas fa-info-circle"></i> Informasi Penerimaan Barang</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Nama Penerima *</label>
                            <input type="text" class="form-control" name="nama_penerima" id="nama_penerima" placeholder="Nama yang menerima barang">
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Diterima *</label>
                            <input type="date" class="form-control" name="tgl_penerima" id="tgl_penerima" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Bukti Penerimaan (Link/Keterangan)</label>
                            <input type="text" class="form-control" name="bukti_penerima" id="bukti_penerima" placeholder="Link foto/dokumen bukti">
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="<?= base_url('tracking') ?>" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save mr-2"></i>Simpan Semua Perubahan</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variabel CSRF dari CodeIgniter
        const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
        const csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

        // --- HELPER FETCH (Mencegah Error Unexpected Token <) ---
        async function customFetch(url, bodyData) {
            if (bodyData instanceof URLSearchParams || bodyData instanceof FormData) {
                bodyData.append(csrfName, csrfHash);
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: bodyData
                });

                const text = await response.text();

                try {
                    // Coba parse JSON tanpa peduli Header
                    return JSON.parse(text);
                } catch (jsonErr) {
                    // Jika benar-benar bukan JSON (misal error PHP HTML), baru tampilkan error
                    console.error("Server Response Non-JSON:", text);
                    throw new TypeError("Server mengirim respon tidak valid.");
                }
            } catch (err) {
                console.error("Fetch Error:", err);
                throw err;
            }
        }

        // --- FORMAT RUPIAH ---
        window.formatRupiah = function(input) {
            let value = input.value.replace(/\D/g, '');
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        };

        // --- ACTION: EDIT ROW (IN-LINE) ---
        document.querySelectorAll('.btnEditRow').forEach(btn => {
            btn.addEventListener('click', function() {
                let row = this.closest('tr');
                row.querySelectorAll('.display-text, .btnEditRow').forEach(el => el.style.display = 'none');
                row.querySelectorAll('.edit-input, .btnSaveRow, .btnDeleteRow, .btnCancelRow').forEach(el => el.style.display = 'inline-block');
            });
        });

        document.querySelectorAll('.btnCancelRow').forEach(btn => {
            btn.addEventListener('click', () => location.reload());
        });

        // --- ACTION: SAVE ROW (UPDATE STATUS HISTORY) ---
        document.querySelectorAll('.btnSaveRow').forEach(btn => {
            btn.addEventListener('click', async function() {
                let row = this.closest('tr');
                let id = row.getAttribute('data-status-id');
                let tgl = row.querySelector('.tgl-input').value;
                let ket = row.querySelector('.ket-input').value;

                if (!tgl) return Swal.fire('Error', 'Tanggal wajib diisi', 'error');

                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const params = new URLSearchParams({
                        status_id: id,
                        tgl_status: tgl,
                        keterangan: ket,
                        type: 'tracking_barang' // Tambahan untuk membedakan dari dokumen
                    });
                    const data = await customFetch('<?= base_url('tracking/update_status_history') ?>', params);

                    if (data.status === 'success') {
                        Swal.fire('Berhasil', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                } catch (e) {
                    Swal.fire('Error', 'Gagal memproses data. Cek koneksi atau login Anda.', 'error');
                }
            });
        });

        // --- ACTION: DELETE ROW ---
        document.querySelectorAll('.btnDeleteRow').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.closest('tr').getAttribute('data-status-id');

                Swal.fire({
                    title: 'Hapus Status?',
                    text: "Data ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const params = new URLSearchParams({
                                id_status: id,
                                doc_type: 'tracking_barang'
                            });
                            const data = await customFetch('<?= base_url('tracking/delete_status') ?>', params);
                            if (data.status === 'success') {
                                Swal.fire('Terhapus', '', 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        } catch (e) {
                            Swal.fire('Error', 'Gagal menghapus data', 'error');
                        }
                    }
                });
            });
        });

        // --- ACTION: SUBMIT FULL FORM ---
        const mainForm = document.getElementById('form-edit-tracking');
        mainForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validasi jika status Diterima dipilih
            const newStatus = document.getElementById('new_status').value;
            if (newStatus === '5') {
                const namaPenerima = document.getElementById('nama_penerima').value.trim();
                const tglPenerima = document.getElementById('tgl_penerima').value;

                if (!namaPenerima) {
                    Swal.fire('Peringatan', 'Nama Penerima wajib diisi untuk status Diterima!', 'warning');
                    document.getElementById('nama_penerima').focus();
                    return;
                }

                if (!tglPenerima) {
                    Swal.fire('Peringatan', 'Tanggal Diterima wajib diisi untuk status Diterima!', 'warning');
                    document.getElementById('tgl_penerima').focus();
                    return;
                }
            }

            const result = await Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Pastikan data sudah benar",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan'
            });

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            let formData = new FormData(mainForm);

            // Pastikan biaya dikirim tanpa titik (integer)
            let biayaVal = document.getElementById('biaya').value.replace(/\./g, '');
            formData.set('biaya', biayaVal);

            try {
                // Gunakan formMain.action secara otomatis
                const response = await fetch(mainForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Menandakan AJAX request
                    }
                });

                const textRes = await response.text(); // Ambil teks mentah dulu untuk didebug
                try {
                    const data = JSON.parse(textRes);
                    if (data.status === 'success') {
                        Swal.fire('Berhasil!', data.message, 'success').then(() => {
                            location.reload(); // Redirect back untuk refresh data
                        });
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                } catch (parseError) {
                    console.error("Respon Server Bukan JSON:", textRes);
                    Swal.fire('Error Sistem', 'Server mengirim respon tidak valid. Cek Console log (F12)', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Gagal menghubungi server', 'error');
            }
        });
    });

    // --- FUNGSI TOGGLE PANEL DITERIMA ---
    function togglePanelDiterima() {
        const statusSelect = document.getElementById('new_status');
        const panel = document.getElementById('panelDiterima');

        if (statusSelect.value === '5') {
            panel.style.display = 'flex';
            // Set default tanggal ke hari ini jika belum diisi
            const tglPenerima = document.getElementById('tgl_penerima');
            if (!tglPenerima.value) {
                tglPenerima.value = '<?= date('Y-m-d') ?>';
            }
        } else {
            panel.style.display = 'none';
            // Clear fields ketika bukan status Diterima
            document.getElementById('nama_penerima').value = '';
            document.getElementById('tgl_penerima').value = '';
            document.getElementById('bukti_penerima').value = '';
        }
    }
</script>