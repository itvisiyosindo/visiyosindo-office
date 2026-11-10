<header class="page-header">
    <h2><i class="fas fa-shopping-cart"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    .po-form-wrap {
        max-width: 1180px;
        margin: 0 auto 30px;
    }

    .po-section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
        margin-bottom: 18px;
        overflow: hidden;
    }

    .po-section-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
        color: #ffffff;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }

    .po-section-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #ffffff;
    }

    .po-section-body {
        padding: 18px;
        color: #1e293b;
    }

    .po-form-label {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 4px;
        display: block;
    }

    .po-input {
        width: 100%;
        min-height: 38px;
        font-size: 14px !important;
        border-radius: 7px !important;
        border: 1px solid #cbd5e1 !important;
        padding: 6px 10px !important;
        background-color: #ffffff;
        color: #0f172a;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .po-input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
        outline: none;
    }

    .po-month-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 14px;
        margin-top: 10px;
    }

    .po-item-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-left: 4px solid #2563eb;
        border-radius: 10px;
        margin-bottom: 14px;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .po-item-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .po-item-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        font-size: 14px;
        color: #0f172a;
    }

    .po-item-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 999px;
        background: #2563eb;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .po-item-body {
        padding: 14px;
    }

    .po-stock-grid {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 10px 4px;
        margin-top: 8px;
        margin-bottom: 10px;
    }

    .po-highlight-rencana {
        background: #fffbeb !important;
        border-color: #f59e0b !important;
        font-weight: 700;
    }

    .po-highlight-detail {
        background: #fefce8 !important;
        border-color: #eab308 !important;
    }

    .po-ttd-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 16px;
        text-align: center;
        max-width: 280px;
        margin-left: auto;
    }

    .po-actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    @media (max-width: 767.98px) {
        .po-section-body {
            padding: 12px;
        }
        .po-item-body {
            padding: 12px;
        }
        .po-ttd-box {
            max-width: 100%;
            margin-left: 0;
            margin-top: 12px;
        }
        .po-actions-bar .btn-action-mobile {
            width: 100%;
            padding: 11px 16px;
            font-size: 15px;
        }
    }
</style>

<div class="po-form-wrap">
    <?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>

    <!-- 1. INFORMASI PENGAJU & PERIODE -->
    <div class="po-section-card">
        <div class="po-section-header">
            <h4><i class="fas fa-file-invoice mr-2"></i>Form Pengajuan Purchase Order</h4>
            <span class="badge badge-light text-dark px-2 py-1">Pengajuan Baru</span>
        </div>
        <div class="po-section-body">
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-3">
                    <label class="po-form-label">Nama Pengaju</label>
                    <input type="text" class="form-control po-input bg-light" value="<?= htmlspecialchars($pengguna[0]->nama ?? '-') ?>" readonly>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <label class="po-form-label">Jabatan</label>
                    <input type="text" class="form-control po-input bg-light" value="<?= htmlspecialchars($pengguna[0]->jabatan ?? '-') ?>" readonly>
                </div>
                <div class="col-md-4 col-12 mb-3">
                    <label class="po-form-label" for="lampiran">Lampiran Warehouse (Link) <span class="text-danger">*</span></label>
                    <input id="lampiran" type="text" class="form-control po-input" placeholder="Tempel link Google Drive / lampiran warehouse" required>
                </div>
            </div>

            <div class="po-month-box">
                <div class="small font-weight-bold text-success mb-2">
                    <i class="fas fa-calendar-alt mr-1"></i> Nama 3 Bulan Stock Keluar (Judul Kolom Stock Keluar):
                </div>
                <div class="row">
                    <div class="col-md-4 col-12 mb-2 mb-md-0">
                        <label class="po-form-label" for="bulan_1">Bulan Ke-1 <span class="text-danger">*</span></label>
                        <input id="bulan_1" type="text" class="form-control po-input month-label-input" data-target="1" placeholder="Contoh: Agustus" required>
                    </div>
                    <div class="col-md-4 col-12 mb-2 mb-md-0">
                        <label class="po-form-label" for="bulan_2">Bulan Ke-2 <span class="text-danger">*</span></label>
                        <input id="bulan_2" type="text" class="form-control po-input month-label-input" data-target="2" placeholder="Contoh: September" required>
                    </div>
                    <div class="col-md-4 col-12">
                        <label class="po-form-label" for="bulan_3">Bulan Ke-3 <span class="text-danger">*</span></label>
                        <input id="bulan_3" type="text" class="form-control po-input month-label-input" data-target="3" placeholder="Contoh: Oktober" required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. DAFTAR ITEM BARANG / JASA -->
    <div class="po-section-card">
        <div class="po-section-header">
            <h4><i class="fas fa-boxes mr-2"></i>Daftar Barang / Jasa yang Diajukan (<span id="po-item-count">1</span> Item)</h4>
            <button type="button" class="btn btn-sm btn-success font-weight-bold" onclick="addPoItemRow()">
                <i class="fas fa-plus mr-1"></i> Tambah Barang
            </button>
        </div>
        <div class="po-section-body">
            <div id="po-items-container"></div>
            <input type="hidden" id="itung" value="0">

            <div class="d-flex flex-wrap justify-content-between align-items-center mt-2" style="gap: 8px;">
                <button type="button" class="btn btn-success font-weight-bold" onclick="addPoItemRow()">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Baris Barang
                </button>
                <span class="small text-muted">
                    <i class="fas fa-info-circle mr-1"></i> Tampilan otomatis menyesuaikan layar HP maupun desktop agar mudah diisi.
                </span>
            </div>
        </div>
    </div>

    <!-- 3. PENGESAHAN & TANGGAL PENGAJUAN -->
    <div class="po-section-card">
        <div class="po-section-body">
            <div class="row align-items-center">
                <div class="col-md-7 col-12">
                    <div class="row">
                        <div class="col-sm-6 col-12 mb-3">
                            <label class="po-form-label" for="drop_kota">Kota Pengajuan <span class="text-danger">*</span></label>
                            <select id="drop_kota" class="form-control po-input" required>
                                <option value="">- Pilih Kota -</option>
                                <?php foreach ($list_kota as $row) { ?>
                                    <option value="<?= htmlspecialchars($row->id . 'PengHubunG' . $row->nama) ?>"><?= htmlspecialchars($row->nama) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-6 col-12 mb-3">
                            <label class="po-form-label" for="pengajuan">Tanggal Pengajuan <span class="text-danger">*</span></label>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' id="pengajuan" class="form-control po-input" value="<?= date('d-m-Y') ?>" placeholder="dd-mm-yyyy" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-12">
                    <?php
                        $img_path = "uploads/file_karyawan/ttd/";
                        $ttdaju   = $img_path . "ttd_" . $pengguna[0]->pengguna_id . ".png";
                    ?>
                    <div class="po-ttd-box">
                        <div class="small text-muted mb-1">Diajukan Oleh,</div>
                        <img src="<?= base_url($ttdaju) ?>" height="65" alt="TTD Pengaju" onerror="this.style.display='none'">
                        <div class="font-weight-bold text-dark mt-1" style="border-bottom: 1px solid #334155; padding-bottom: 3px;">
                            <?= htmlspecialchars($pengguna[0]->short_name ?: $pengguna[0]->nama) ?>
                        </div>
                        <div class="small font-italic text-muted mt-1"><?= htmlspecialchars($pengguna[0]->jabatan ?? '-') ?></div>
                    </div>
                </div>
            </div>

            <div class="po-actions-bar">
                <button type="button" onclick="goBack()" class="btn btn-secondary btn-action-mobile">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </button>
                <button type="button" class="btn btn-success btn-save btn-ajukan font-weight-bold btn-action-mobile">
                    <i class="fas fa-check mr-1"></i> Ajukan Purchase Order
                </button>
            </div>
        </div>
    </div>

    <?= form_close(); ?>
</div>

<template id="po-supplier-options-tpl">
    <option value="">- Pilih Supplier -</option>
    <?php foreach ($list_supplier as $row) { ?>
        <option value="<?= htmlspecialchars($row->nama_pemasok) ?>"><?= htmlspecialchars($row->nama_pemasok) ?></option>
    <?php } ?>
</template>

<script>
var poRowCounter = 0;

function getMonthLabel(num) {
    var val = ($('#bulan_' + num).val() || '').trim();
    return val ? val : ('Bulan ' + num);
}

function syncAllMonthLabels() {
    var b1 = getMonthLabel(1);
    var b2 = getMonthLabel(2);
    var b3 = getMonthLabel(3);
    $('.lbl-bulan-1').text('Stock Keluar (' + b1 + ')');
    $('.lbl-bulan-2').text('Stock Keluar (' + b2 + ')');
    $('.lbl-bulan-3').text('Stock Keluar (' + b3 + ')');
}

function updateItemOrderNumbers() {
    var cards = $('#po-items-container .po-item-card');
    cards.each(function(idx) {
        $(this).find('.po-item-num').text(idx + 1);
    });
    $('#po-item-count').text(cards.length);
}

function addPoItemRow() {
    poRowCounter++;
    var idx = poRowCounter;
    var supplierOptions = $('#po-supplier-options-tpl').html();
    var b1 = getMonthLabel(1);
    var b2 = getMonthLabel(2);
    var b3 = getMonthLabel(3);

    var html = `
    <div class="po-item-card" data-row-idx="${idx}" id="po-card-${idx}">
        <div class="po-item-header">
            <div class="po-item-badge">
                <span class="po-item-num">${idx}</span>
                <span class="po-item-title-preview">Item Barang / Jasa</span>
            </div>
            <button type="button" class="btn btn-xs btn-outline-danger font-weight-bold" onclick="removePoItemRow(${idx})">
                <i class="fas fa-trash-alt mr-1"></i> Hapus
            </button>
        </div>
        <div class="po-item-body">
            <div class="row">
                <div class="col-md-5 col-12 mb-2">
                    <label class="po-form-label" for="nama_${idx}">Nama Barang / Jasa <span class="text-danger">*</span></label>
                    <input type="text" class="form-control po-input po-nama-input" id="nama_${idx}" placeholder="Masukkan nama barang / jasa" required>
                </div>
                <div class="col-md-3 col-sm-6 col-12 mb-2">
                    <label class="po-form-label" for="kodebarang_${idx}">Kode Barang / Jasa</label>
                    <input type="text" class="form-control po-input" id="kodebarang_${idx}" placeholder="Kode barang">
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-2">
                    <label class="po-form-label" for="supplier_${idx}">Supplier</label>
                    <select class="form-control po-input" id="supplier_${idx}">
                        ${supplierOptions}
                    </select>
                </div>
            </div>

            <div class="po-stock-grid">
                <div class="row">
                    <div class="col-md-2 col-4 mb-2">
                        <label class="po-form-label lbl-bulan-1" for="bulan1_${idx}">Stock Keluar (${b1})</label>
                        <input type="number" class="form-control po-input text-center" id="bulan1_${idx}" placeholder="0">
                    </div>
                    <div class="col-md-2 col-4 mb-2">
                        <label class="po-form-label lbl-bulan-2" for="bulan2_${idx}">Stock Keluar (${b2})</label>
                        <input type="number" class="form-control po-input text-center" id="bulan2_${idx}" placeholder="0">
                    </div>
                    <div class="col-md-2 col-4 mb-2">
                        <label class="po-form-label lbl-bulan-3" for="bulan3_${idx}">Stock Keluar (${b3})</label>
                        <input type="number" class="form-control po-input text-center" id="bulan3_${idx}" placeholder="0">
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <label class="po-form-label" for="gudang_${idx}">Stock Gudang Saat Ini</label>
                        <input type="number" class="form-control po-input text-center" id="gudang_${idx}" placeholder="0">
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <label class="po-form-label" for="stokpo_${idx}">Stock Sudah di-PO</label>
                        <input type="number" class="form-control po-input text-center" id="stokpo_${idx}" placeholder="0">
                    </div>
                    <div class="col-md-2 col-12 mb-2">
                        <label class="po-form-label" for="kebutuhan_${idx}">Kebutuhan PO Pending</label>
                        <input type="number" class="form-control po-input text-center" id="kebutuhan_${idx}" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-12 mb-2 mb-md-0">
                    <label class="po-form-label text-dark" for="rencana_${idx}"><i class="fas fa-star text-warning mr-1"></i>Rencana PO Baru (Gudang) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control po-input text-center po-highlight-rencana" id="rencana_${idx}" placeholder="Qty PO baru" required>
                </div>
                <div class="col-md-9 col-12">
                    <label class="po-form-label" for="detail_${idx}">Detail / Keterangan</label>
                    <input type="text" class="form-control po-input po-highlight-detail" id="detail_${idx}" placeholder="Spesifikasi, satuan, atau keterangan tambahan">
                </div>
            </div>
        </div>
    </div>`;

    $('#po-items-container').append(html);
    $('#itung').val(poRowCounter);
    updateItemOrderNumbers();
}

function removePoItemRow(idx) {
    var totalCards = $('#po-items-container .po-item-card').length;
    if (totalCards <= 1) {
        Swal.fire('Perhatian', 'Minimal harus terdapat 1 baris item barang.', 'info');
        return;
    }
    $('#po-card-' + idx).remove();
    updateItemOrderNumbers();
}

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi 3 item card awal agar ringkas namun siap pakai di HP & Desktop
    for (var i = 0; i < 3; i++) {
        addPoItemRow();
    }

    $(document).on('input', '.month-label-input', function() {
        syncAllMonthLabels();
    });

    $(document).on('input', '.po-nama-input', function() {
        var card = $(this).closest('.po-item-card');
        var val = ($(this).val() || '').trim();
        card.find('.po-item-title-preview').text(val ? val : 'Item Barang / Jasa');
    });

    $(document).on('click', '.btn-ajukan', function() {
        var lampiran  = ($('#lampiran').val() || '').trim();
        var bulan_1   = ($('#bulan_1').val() || '').trim();
        var bulan_2   = ($('#bulan_2').val() || '').trim();
        var bulan_3   = ($('#bulan_3').val() || '').trim();
        var kotaRaw   = ($('#drop_kota').val() || '').trim();
        var pengajuan = ($('#pengajuan').val() || '').trim();

        if (!lampiran) {
            Swal.fire('Data Belum Lengkap', 'Silakan masukkan link Lampiran Warehouse terlebih dahulu.', 'warning');
            $('#lampiran').focus();
            return;
        }
        if (!bulan_1 || !bulan_2 || !bulan_3) {
            Swal.fire('Data Belum Lengkap', 'Silakan lengkapi nama 3 bulan Stock Keluar terlebih dahulu.', 'warning');
            return;
        }
        if (!kotaRaw || kotaRaw.indexOf('PengHubunG') === -1) {
            Swal.fire('Data Belum Lengkap', 'Silakan pilih Kota Pengajuan terlebih dahulu.', 'warning');
            $('#drop_kota').focus();
            return;
        }
        if (!pengajuan) {
            Swal.fire('Data Belum Lengkap', 'Silakan isi Tanggal Pengajuan.', 'warning');
            $('#pengajuan').focus();
            return;
        }

        var isi      = kotaRaw.split('PengHubunG');
        var kota_aju = isi[1] || '';

        var nam = [], kod = [], bu1 = [], bu2 = [], bu3 = [], gud = [], sto = [], keb = [], ren = [], sup = [], det = [];
        var packedIdx = 1;

        $('#po-items-container .po-item-card').each(function() {
            var idx = $(this).data('row-idx');
            var namaVal = ($('#nama_' + idx).val() || '').trim();
            if (namaVal !== '') {
                nam[packedIdx] = namaVal;
                kod[packedIdx] = $('#kodebarang_' + idx).val() || '';
                bu1[packedIdx] = $('#bulan1_' + idx).val() || 0;
                bu2[packedIdx] = $('#bulan2_' + idx).val() || 0;
                bu3[packedIdx] = $('#bulan3_' + idx).val() || 0;
                gud[packedIdx] = $('#gudang_' + idx).val() || 0;
                sto[packedIdx] = $('#stokpo_' + idx).val() || 0;
                keb[packedIdx] = $('#kebutuhan_' + idx).val() || 0;
                ren[packedIdx] = $('#rencana_' + idx).val() || 0;
                sup[packedIdx] = $('#supplier_' + idx).val() || '';
                det[packedIdx] = $('#detail_' + idx).val() || '';
                packedIdx++;
            }
        });

        var itung = nam.length;
        if (itung <= 1) {
            Swal.fire('Belum Ada Item', 'Silakan isi minimal 1 Nama Barang / Jasa terlebih dahulu.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Ajukan Purchase Order?',
            text: 'Total ' + (itung - 1) + ' item barang akan diajukan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ajukan',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (!result.value) {
                return;
            }

            $.ajax({
                method: 'POST',
                url: 'purchase_order/add',
                dataType: 'JSON',
                data: {
                    kota_aju: kota_aju,
                    pengajuan: pengajuan,
                    bulan_1: bulan_1,
                    bulan_2: bulan_2,
                    bulan_3: bulan_3,
                    lampiran: lampiran,
                    itung: itung,
                    nama: nam,
                    kodebarang: kod,
                    bulan1: bu1,
                    bulan2: bu2,
                    bulan3: bu3,
                    gudang: gud,
                    stokpo: sto,
                    kebutuhan: keb,
                    rencana: ren,
                    supplier: sup,
                    detail: det,
                    csrf_token: token
                },
                beforeSend: function() {
                    $('.btn-ajukan').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...');
                },
                success: function(resp) {
                    $('.btn-ajukan').prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Ajukan Purchase Order');
                    handleResponse(resp);
                },
                error: function() {
                    $('.btn-ajukan').prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Ajukan Purchase Order');
                    Swal.fire('Gagal', 'Terjadi kesalahan saat mengirim pengajuan PO.', 'error');
                }
            });
        });
    });
});

function goBack() {
    window.history.back();
}
</script>
