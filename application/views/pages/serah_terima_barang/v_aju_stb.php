<header class="page-header">
    <h2><i class="icons fas fa-file-alt"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    .stb-container {
        max-width: 1200px;
        margin: 0 auto 35px;
    }

    .stb-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .stb-card-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
        color: #ffffff;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .stb-card-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stb-card-body {
        padding: 20px;
        color: #1e293b;
    }

    .stb-form-label {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }

    .stb-form-control {
        width: 100%;
        min-height: 40px;
        font-size: 14px !important;
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        padding: 7px 12px !important;
        background-color: #ffffff;
        color: #0f172a;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .stb-form-control:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
        outline: none;
    }

    .stb-pihak-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        height: 100%;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .stb-pihak-box:hover {
        border-color: #93c5fd;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.06);
    }

    .stb-pihak-title {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px dashed #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Card Item Barang */
    .stb-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #2563eb;
        border-radius: 10px;
        margin-bottom: 14px;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .stb-item-card:hover {
        box-shadow: 0 3px 8px rgba(15, 23, 42, 0.08);
    }

    .stb-item-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .stb-item-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        font-size: 14px;
        color: #0f172a;
    }

    .stb-item-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .stb-item-body {
        padding: 14px 16px;
    }

    /* Penandatangan Box */
    .stb-ttd-card {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 16px;
        text-align: center;
    }

    .stb-actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .select2-container {
        width: 100% !important;
    }

    @media (max-width: 767.98px) {
        .stb-card-body {
            padding: 14px;
        }
        .stb-item-body {
            padding: 12px;
        }
        .stb-actions-bar {
            flex-direction: column-reverse;
        }
        .stb-actions-bar .btn {
            width: 100%;
            margin: 0 !important;
            padding: 12px 16px;
            font-size: 15px;
        }
    }
</style>

<div class="stb-container">
    <?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>

    <!-- 1. KARTU INFORMASI PIHAK KESEPAKATAN -->
    <div class="stb-card">
        <div class="stb-card-header">
            <h4><i class="fas fa-handshake"></i> Form Pengajuan Surat Serah Terima Barang (SSTB)</h4>
            <span class="badge badge-light text-dark px-3 py-1 font-weight-bold">Form Pengajuan</span>
        </div>
        <div class="stb-card-body">
            <div class="alert alert-info py-2 px-3 mb-4" style="border-left: 4px solid #0284c7; background: #f0f9ff; color: #0369a1; font-size: 13.5px;">
                <i class="fas fa-info-circle mr-1"></i> Pada hari ini, pihak-pihak yang bertanda tangan di bawah ini menyepakati serah terima barang sebagai berikut:
            </div>

            <div class="row">
                <!-- PIHAK PERTAMA -->
                <div class="col-md-6 mb-3">
                    <div class="stb-pihak-box">
                        <div class="stb-pihak-title text-primary">
                            <span><i class="fas fa-building mr-1"></i> Pihak Pertama (Penyerah)</span>
                            <span class="badge badge-primary px-2 py-1">PIHAK I</span>
                        </div>
                        <div class="form-group mb-0">
                            <label class="stb-form-label" for="id_pihak1">Nama Instansi / Perusahaan <span class="text-danger">*</span></label>
                            <select id="id_pihak1" class="form-control stb-form-control select2-stb" required>
                                <option value="">-- Pilih Pihak Pertama --</option>
                                <option value="325" selected>PT VISI YOSINDO MEDIKAL</option>
                                <?php foreach ($list_cust as $row): ?>
                                    <option value="<?= $row->id_customer.'PengHubunG'.$row->nama_customer ?>"><?= htmlspecialchars($row->nama_customer) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted mt-1 d-block"><i class="fas fa-check-circle text-success mr-1"></i>Default: PT VISI YOSINDO MEDIKAL</small>
                        </div>
                    </div>
                </div>

                <!-- PIHAK KEDUA -->
                <div class="col-md-6 mb-3">
                    <div class="stb-pihak-box">
                        <div class="stb-pihak-title text-success">
                            <span><i class="fas fa-hospital mr-1"></i> Pihak Kedua (Penerima)</span>
                            <span class="badge badge-success px-2 py-1">PIHAK II</span>
                        </div>
                        <div class="form-group mb-0">
                            <label class="stb-form-label" for="id_customer">Nama Customer / Instansi Penerima <span class="text-danger">*</span></label>
                            <select id="id_customer" class="form-control stb-form-control select2-stb" required>
                                <option value="">-- Pilih Customer / Instansi --</option>
                                <?php foreach ($list_cust as $row): ?>
                                    <option value="<?= $row->id_customer.'PengHubunG'.$row->nama_customer ?>"><?= htmlspecialchars($row->nama_customer) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted mt-1 d-block"><i class="fas fa-search mr-1"></i>Pilih customer yang menerima barang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. KARTU DAFTAR BARANG YANG DISERAHKAN -->
    <div class="stb-card">
        <div class="stb-card-header">
            <h4><i class="fas fa-boxes"></i> Daftar Barang yang Diserahkan (<span id="stb-item-count">1</span> Barang)</h4>
            <button type="button" class="btn btn-sm btn-success font-weight-bold" onclick="addStbItem()">
                <i class="fas fa-plus mr-1"></i> Tambah Barang
            </button>
        </div>
        <div class="stb-card-body">
            <p class="text-muted small mb-3">
                <i class="fas fa-exclamation-circle text-info mr-1"></i>
                Isi rincian barang yang diserahterimakan. Anda dapat menambah atau mengurangi baris barang sesuai kebutuhan.
            </p>

            <div id="stb-items-container"></div>
            <input type="hidden" id="itung" value="0">

            <div class="d-flex flex-wrap justify-content-between align-items-center mt-3" style="gap: 10px;">
                <button type="button" class="btn btn-outline-success font-weight-bold" onclick="addStbItem()">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Baris Barang
                </button>
                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Baris yang kosong pada nama barang tidak akan diproses.</small>
            </div>
        </div>
    </div>

    <!-- 3. KARTU INFORMASI PENGESAHAN & PENGAJUAN -->
    <div class="stb-card">
        <div class="stb-card-header">
            <h4><i class="fas fa-calendar-check"></i> Waktu & Lokasi Pengesahan</h4>
        </div>
        <div class="stb-card-body">
            <div class="row">
                <div class="col-md-7 mb-4">
                    <div class="p-3 bg-light rounded border">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-map-marker-alt text-danger mr-1"></i> Tempat & Tanggal Penandatanganan</h6>
                        <div class="form-group mb-3">
                            <label class="stb-form-label" for="drop_kota">Kota Pengesahan <span class="text-danger">*</span></label>
                            <select id="drop_kota" class="form-control stb-form-control select2-stb" required>
                                <option value="">-- Pilih Kota --</option>
                                <?php foreach ($list_kota as $row): ?>
                                    <option value="<?= $row->id.'PengHubunG'.$row->nama ?>"><?= htmlspecialchars($row->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label class="stb-form-label" for="pengajuan">Tanggal Pengajuan <span class="text-danger">*</span></label>
                            <input type="text" id="pengajuan" class="form-control stb-form-control" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' placeholder="dd-mm-yyyy" value="<?= date('d-m-Y') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 mb-4">
                    <div class="stb-ttd-card">
                        <span class="text-muted small font-weight-bold d-block mb-1">Diajukan Oleh:</span>
                        <div style="min-height: 80px; display: flex; align-items: center; justify-content: center;" class="my-2">
                            <?php
                                $img_path = "uploads/file_karyawan/ttd/";
                                $ttdaju   = $img_path . "ttd_" . ($pengguna[0]->pengguna_id ?? '') . ".png";
                            ?>
                            <img src="<?= $ttdaju ?>" height="75" alt="Tanda Tangan" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div class="text-muted font-italic small" style="display:none;">(Tanda Tangan Digital)</div>
                        </div>
                        <div class="font-weight-bold text-dark" style="font-size: 14px; border-top: 1px solid #cbd5e1; padding-top: 6px;">
                            <?= htmlspecialchars($pengguna[0]->short_name ?? $pengguna[0]->nama ?? '-') ?>
                        </div>
                        <div class="text-muted small"><?= htmlspecialchars($pengguna[0]->jabatan ?? 'Karyawan') ?></div>
                    </div>
                </div>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="stb-actions-bar border-top pt-3">
                <button type="button" onclick="goBack()" class="btn btn-secondary font-weight-bold px-4">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </button>
                <button type="button" class="btn btn-success font-weight-bold px-4 btn-ajukan">
                    <i class="fas fa-paper-plane mr-1"></i> Ajukan Serah Terima Barang
                </button>
            </div>
        </div>
    </div>

    <?= form_close(); ?>
</div>

<script>
    let stbItemIndex = 0;

    function renderStbItem(idx, data = {}) {
        const no = data.no || (idx + 1);
        const nama = data.nama || '';
        const merk = data.merk || '';
        const nobatch = data.nobatch || '';
        const qty = data.qty || '';
        const satuan = data.satuan || '';
        const ket = data.ket || '';

        return `
            <div class="stb-item-card" id="stb-card-${idx}">
                <div class="stb-item-header">
                    <div class="stb-item-badge">
                        <span class="stb-item-num" id="stb-badge-num-${idx}">${idx + 1}</span>
                        <span>Item Barang #${idx + 1}</span>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-xs font-weight-bold" onclick="removeStbItem(${idx})" title="Hapus Baris Ini">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
                <div class="stb-item-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-3 col-12 mb-2">
                            <label class="stb-form-label">No Urut</label>
                            <input type="number" id="nomor_${idx + 1}" class="form-control stb-form-control text-center" value="${no}" required>
                        </div>
                        <div class="col-md-6 col-sm-9 col-12 mb-2">
                            <label class="stb-form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" id="nama_${idx + 1}" class="form-control stb-form-control" placeholder="Contoh: Reagen / Alat Kesehatan..." value="${nama}" required>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-2">
                            <label class="stb-form-label">Merk</label>
                            <input type="text" id="merk_${idx + 1}" class="form-control stb-form-control" placeholder="Merk / Brand" value="${merk}">
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-2">
                            <label class="stb-form-label">No. Batch / Lot</label>
                            <input type="text" id="nobatch_${idx + 1}" class="form-control stb-form-control" placeholder="No Batch" value="${nobatch}">
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-2">
                            <label class="stb-form-label">Qty <span class="text-danger">*</span></label>
                            <input type="number" id="qty_${idx + 1}" class="form-control stb-form-control text-center" min="1" step="any" placeholder="1" value="${qty}" required>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 mb-2">
                            <label class="stb-form-label">Satuan</label>
                            <input type="text" id="satuan_${idx + 1}" class="form-control stb-form-control text-center" placeholder="Pcs/Box/Unit" value="${satuan}">
                        </div>
                        <div class="col-md-4 col-sm-4 col-12 mb-2">
                            <label class="stb-form-label">Keterangan</label>
                            <input type="text" id="ket_${idx + 1}" class="form-control stb-form-control" placeholder="Kondisi baik, segel, dll" value="${ket}">
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function addStbItem(data = {}) {
        const container = document.getElementById('stb-items-container');
        if (!container) return;
        
        const div = document.createElement('div');
        div.innerHTML = renderStbItem(stbItemIndex, data);
        container.appendChild(div.firstElementChild);
        stbItemIndex++;
        updateStbItemCount();
    }

    function removeStbItem(idx) {
        const card = document.getElementById(`stb-card-${idx}`);
        if (!card) return;

        const allCards = document.querySelectorAll('.stb-item-card');
        if (allCards.length <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Minimal harus ada 1 baris barang dalam pengajuan serah terima.'
            });
            return;
        }

        card.remove();
        refreshStbItemIndices();
        updateStbItemCount();
    }

    function refreshStbItemIndices() {
        const container = document.getElementById('stb-items-container');
        const cards = container.querySelectorAll('.stb-item-card');
        
        cards.forEach((card, index) => {
            const newIdx = index + 1;
            card.id = `stb-card-${index}`;

            const badgeNum = card.querySelector('.stb-item-num');
            if (badgeNum) {
                badgeNum.id = `stb-badge-num-${index}`;
                badgeNum.textContent = newIdx;
            }

            const badgeLabel = card.querySelector('.stb-item-badge span:last-child');
            if (badgeLabel) {
                badgeLabel.textContent = `Item Barang #${newIdx}`;
            }

            const delBtn = card.querySelector('button');
            if (delBtn) {
                delBtn.setAttribute('onclick', `removeStbItem(${index})`);
            }

            const inputNomor   = card.querySelector('input[id^="nomor_"]');
            const inputNama    = card.querySelector('input[id^="nama_"]');
            const inputMerk    = card.querySelector('input[id^="merk_"]');
            const inputNobatch = card.querySelector('input[id^="nobatch_"]');
            const inputQty     = card.querySelector('input[id^="qty_"]');
            const inputSatuan  = card.querySelector('input[id^="satuan_"]');
            const inputKet     = card.querySelector('input[id^="ket_"]');

            if (inputNomor) {
                inputNomor.id = `nomor_${newIdx}`;
                inputNomor.value = newIdx;
            }
            if (inputNama) inputNama.id = `nama_${newIdx}`;
            if (inputMerk) inputMerk.id = `merk_${newIdx}`;
            if (inputNobatch) inputNobatch.id = `nobatch_${newIdx}`;
            if (inputQty) inputQty.id = `qty_${newIdx}`;
            if (inputSatuan) inputSatuan.id = `satuan_${newIdx}`;
            if (inputKet) inputKet.id = `ket_${newIdx}`;
        });

        stbItemIndex = cards.length;
    }

    function updateStbItemCount() {
        const count = document.querySelectorAll('.stb-item-card').length;
        const countEl = document.getElementById('stb-item-count');
        const itungEl = document.getElementById('itung');
        if (countEl) countEl.textContent = count;
        if (itungEl) itungEl.value = count;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi awal: buat 3 baris item
        addStbItem({ no: 1 });
        addStbItem({ no: 2 });
        addStbItem({ no: 3 });

        // Inisialisasi select2 jika ada
        if ($.fn.select2) {
            $('.select2-stb').select2({
                theme: 'bootstrap',
                width: '100%'
            });
        }

        // Action submit pengajuan
        $(document).on('click', '.btn-ajukan', function(e) {
            e.preventDefault();

            var dropKotaVal = $('#drop_kota').val();
            if (!dropKotaVal) {
                Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Silakan pilih Kota pengesahan terlebih dahulu.' });
                return;
            }

            var kota_aju = dropKotaVal;
            if (kota_aju.indexOf('PengHubunG') !== -1) {
                var isi = kota_aju.split("PengHubunG");
                kota_aju = isi[1];
            }

            var id_pihak1   = $('#id_pihak1').val();
            var id_customer = $('#id_customer').val();
            var pengajuan   = $('#pengajuan').val();

            if (!id_pihak1) {
                Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Silakan pilih Nama Pihak Pertama.' });
                return;
            }

            if (!id_customer) {
                Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Silakan pilih Nama Customer (Pihak Kedua).' });
                return;
            }

            if (!pengajuan) {
                Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Silakan isi Tanggal Pengajuan.' });
                return;
            }

            refreshStbItemIndices();
            var cards = document.querySelectorAll('.stb-item-card');

            var nom = [];
            var nam = [];
            var mer = [];
            var nob = [];
            var qt  = [];
            var sat = [];
            var ke  = [];

            var validCount = 0;
            cards.forEach(function(card, i) {
                var idx = i + 1;
                var valNama = $(`#nama_${idx}`).val();
                if (valNama && valNama.trim() !== "") {
                    validCount++;
                    nom[validCount] = $(`#nomor_${idx}`).val() || validCount;
                    nam[validCount] = valNama.trim();
                    mer[validCount] = $(`#merk_${idx}`).val() || '';
                    nob[validCount] = $(`#nobatch_${idx}`).val() || '';
                    qt[validCount]  = $(`#qty_${idx}`).val() || '1';
                    sat[validCount] = $(`#satuan_${idx}`).val() || '';
                    ke[validCount]  = $(`#ket_${idx}`).val() || '';
                }
            });

            if (validCount === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Barang Masih Kosong',
                    text: 'Minimal harus mengisi 1 nama barang yang diserahterimakan.'
                });
                return;
            }

            var totalItung = validCount + 1; // Server melakukan loop: for($x=1; $x < $itung; $x++)

            Swal.fire({
                title: 'Ajukan Serah Terima Barang?',
                text: `${validCount} barang akan diajukan ke sistem.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Ajukan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.value) {
                    var $btn = $('.btn-ajukan');
                    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

                    var postData = {
                        kota_aju: kota_aju,
                        pengajuan: pengajuan,
                        id_pihak1: id_pihak1,
                        id_customer: id_customer,
                        itung: totalItung,
                        nomor: nom,
                        nama: nam,
                        merk: mer,
                        nobatch: nob,
                        qty: qt,
                        satuan: sat,
                        ket: ke
                    };

                    if (typeof token !== 'undefined') {
                        postData.csrf_token = token;
                    }

                    $.ajax({
                        method: 'POST',
                        url: 'serah_terima_barang/add',
                        dataType: 'JSON',
                        data: postData,
                        success: function(resp) {
                            $btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-1"></i> Ajukan Serah Terima Barang');
                            if (typeof handleResponse === 'function') {
                                handleResponse(resp);
                            } else {
                                if (resp.status === 'success' || resp.success) {
                                    Swal.fire('Berhasil', resp.message || 'Serah Terima Barang Berhasil Diajukan', 'success').then(function() {
                                        window.location.href = 'serah_terima_barang';
                                    });
                                } else {
                                    Swal.fire('Gagal', resp.message || 'Terjadi kesalahan saat menyimpan data', 'error');
                                }
                            }
                        },
                        error: function(xhr) {
                            $btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-1"></i> Ajukan Serah Terima Barang');
                            Swal.fire('Error', 'Gagal memproses pengajuan. Silakan coba kembali.', 'error');
                        }
                    });
                }
            });
        });
    });

    function goBack() {
        window.history.back();
    }
</script>
