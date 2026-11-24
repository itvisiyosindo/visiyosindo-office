<header class="page-header">
    <h2><i class="icons fas fa-money-bill"></i>&nbsp;
        <?= $page_title ?>
    </h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>
                    <?= $page_desc ?>
                </span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col">
        <div class="card-body">
            <div class="table-responsive">
                <div class="row mb-3 ml-1">
                    <div>
                        <button type="button" print="gapok" class="btn btn-success btn-print-salary"><i
                                class="icons fas fa-print"></i> Print Gapok</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" print="tunjangan" class="btn btn-warning btn-print"><i
                                class="icons fas fa-print"></i> Print T. Jabatan</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" id="btn-tutupbuku" class="btn btn-danger btn-save"><i
                                class="icons fas fa-print"></i> Tutup Buku</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" id="btn-history" class="btn btn-outline-secondary btn-history"><i
                                class="icons fas fa-print"></i> History Salary</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" id="btn-resign" class="btn btn-outline-secondary btn-resign"><i
                                class="icons fas fa-print"></i> Print Salary Resign</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" class="btn btn-info btn-import-komisi"><i
                                class="icons fas fa-file-excel"></i> Import Komisi (Excel)</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" class="btn btn-primary btn-import-pph21"><i
                                class="icons fas fa-file-excel"></i> Import PPh 21 (Excel)</button>
                    </div>
                </div>
                <div class="row mb-3 align-items-end">
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label>Filter Bulan & Tahun:</label>
                            <input type="text" id="filter_month" class="form-control" 
                                   data-plugin-datepicker 
                                   data-plugin-options='{"format": "yyyy-mm", "minViewMode": "months", "orientation": "bottom"}' 
                                   placeholder="Pilih Bulan" value="<?= date('Y-m') ?>">
                        </div>
                    </div>
                    <div class="col-md-9 d-flex flex-wrap align-items-center" style="gap: 8px; padding-top: 8px;">
                        <button type="button" id="btn-download-slip-batch" class="btn btn-danger" disabled>
                            <i class="fas fa-file-archive"></i> Download ZIP Slip Terpilih (<span id="slip-selected-count">0</span>)
                        </button>
                        <button type="button" id="btn-select-all-slips" class="btn btn-outline-danger">
                            <i class="fas fa-check-double"></i> Pilih Semua Karyawan
                        </button>
                        <button type="button" id="btn-clear-slips" class="btn btn-outline-secondary d-none">
                            <i class="fas fa-times"></i> Reset Pilihan
                        </button>
                    </div>
                </div>
                <br>
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th style="white-space: nowrap;"><input type="checkbox" id="check-all-page" title="Pilih semua di halaman ini" style="cursor:pointer;"> #</th>
                            <th> Nama Karyawan</th>
                            <th> Gaji Pokok </th>
                            <th> Komisi </th>
                            <th> BPJS Kesehatan </th>
                            <th> BPJS TK </th>
                            <th> Pendapatan Lain </th>
                            <th> PPh 21 </th>
                            <th> Aksi </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="fas fa-money-check-alt text-grey-light"></i> Form Edit Salary &amp; Tunjangan <span class="badge badge-warning ml-2" id="edit_month_label"></span></h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="row">
                    <!-- Kolom Kiri: Penghasilan & Tunjangan Pokok -->
                    <div class="col-md-6 border-right">
                        <div class="alert alert-secondary py-1 px-2 mb-3 font-weight-bold" style="font-size: 13px;">
                            <i class="fas fa-hand-holding-usd text-primary"></i> A. PENGHASILAN & TUNJANGAN
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">1. Gaji Pokok :</label>
                            <input type="text" class="form-control input_salary" id="gaji_pokok" name="gaji_pokok" placeholder="0">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">2. Tunjangan Konsumsi :</label>
                            <input type="text" class="form-control input_salary" id="tunjangan_konsumsi" name="tunjangan_konsumsi" placeholder="0">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">3. Tunjangan Kinerja :</label>
                            <input type="text" class="form-control input_salary" id="tunjangan_kinerja" name="tunjangan_kinerja" placeholder="0">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">4. Tunjangan Komunikasi :</label>
                            <input type="text" class="form-control input_salary" id="tunjangan_komunikasi" name="tunjangan_komunikasi" placeholder="0">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">5. Tunjangan Transport :</label>
                            <input type="text" class="form-control input_salary" id="tunjangan_transportasi" name="tunjangan_transportasi" placeholder="0">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">6. Tunjangan BBM :</label>
                            <input type="text" class="form-control input_salary" id="tunjangan_bbm" name="tunjangan_bbm" placeholder="0">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">7. Tunjangan Jabatan :</label>
                            <input type="text" class="form-control input_salary" id="tunjangan_jabatan" name="tunjangan_jabatan" placeholder="0">
                        </div>
                    </div>

                    <!-- Kolom Kanan: Tunjangan Lain, Bonus & Potongan -->
                    <div class="col-md-6">
                        <div class="alert alert-secondary py-1 px-2 mb-3 font-weight-bold" style="font-size: 13px;">
                            <i class="fas fa-gift text-success"></i> BONUS & PENDAPATAN LAIN
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">8. Bonus :</label>
                            <input type="text" class="form-control input_salary" id="bonus_tahunan" name="bonus_tahunan" placeholder="0">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">9. Tunjangan Hari Raya :</label>
                            <input type="text" class="form-control input_salary" id="tunjangan_raya" name="tunjangan_raya" placeholder="0">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">10. Pendapatan Lainnya :</label>
                            <input type="text" class="form-control input_salary" id="pendapatan_lain" name="pendapatan_lain" placeholder="0">
                        </div>

                        <hr class="my-3">
                        <div class="alert alert-secondary py-1 px-2 mb-3 font-weight-bold" style="font-size: 13px;">
                            <i class="fas fa-calculator text-danger"></i> B. POTONGAN & DASAR BPJS
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">Dasar Potongan BPJS Kesehatan :</label>
                            <input type="text" class="form-control input_salary" id="dasar_potongan_bpjs" name="dasar_potongan_bpjs" placeholder="0">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">Dasar Potongan BPJS Ketenagakerjaan :</label>
                            <input type="text" class="form-control input_salary" id="dasar_potongan_bpjs_tk" name="dasar_potongan_bpjs_tk" placeholder="0">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-control-label font-weight-bold">Potongan Lainnya :</label>
                            <input type="text" class="form-control input_salary" id="potongan" name="potongan" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control">
                <input type="hidden" id="edit_month" name="edit_month">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save"><i class="fas fa-save"></i> Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="main-modal-komisi" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Komisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form-komisi', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">Komisi :</label>
                        <input type="text" class="form-control input_salary" id="komisi" name="komisi" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Bulan :</label>
                        <input type="text" data-plugin-datepicker
                            data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}'
                            class="form-control" id="bulan" name="bulan" placeholder="Pilih Bulan" required
                            data-plugin-datepicker>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Import Komisi Excel -->
<div id="modal-import-komisi" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-light">
                <h5 class="modal-title"><i class="fas fa-file-excel"></i> Import Data Komisi via Excel</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <?= form_open_multipart('salary/import_komisi', array('id' => 'form-import-komisi')); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template Excel:</label><br>
                        <a href="<?= base_url('salary/download_template_komisi'); ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-download"></i> Download Template Komisi.xlsx
                        </a>
                </div>
                <hr>
                <div class="form-group">
                    <label>Pilih File Excel (.xlsx / .xls):</label>
                    <input type="file" name="file_excel" class="form-control-file" accept=".xlsx, .xls, .csv" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Upload & Simpan ke Database</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Import PPh 21 Excel -->
<div id="modal-import-pph21" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title"><i class="fas fa-file-excel"></i> Import Data PPh 21 via Excel</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <?= form_open_multipart('salary/import_pph21', array('id' => 'form-import-pph21')); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template Excel:</label><br>
                        <a href="<?= base_url('salary/download_template_pph21'); ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-download"></i> Download Template PPh21.xlsx
                        </a>
                </div>
                <hr>
                <div class="form-group">
                    <label>Pilih File Excel (.xlsx / .xls):</label>
                    <input type="file" name="file_excel" class="form-control-file" accept=".xlsx, .xls, .csv" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Upload & Simpan ke Database</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="main-modal-pendapatan_lain" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pendapatan Lain</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form-pendapatan_lain', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">Pendapatan Lainnya (Gaji) :</label>
                        <input type="text" class="form-control input_salary" id="pendapatan" name="pendapatan" required>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Pengurangan Lainnya :</label>
                        <input type="text" class="form-control input_salary" id="pengurangan" name="pengurangan"
                            required>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" id="btn-pendapatanlain" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="main-modal-pph21" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form PPh 21</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form-pph21', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">PPh 21 :</label>
                        <input type="text" class="form-control input_salary" id="pph21" name="pph21" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#filter_jenis_penerimaan, #filter_gudang, #filter_month').change(function () {
            updateDatatable()
        })

        $('.input_salary').mask('000.000.000.000', {
            reverse: true
        });
        table = $('#kt_table_1').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            order: [[1, 'asc']], // Urutkan berdasarkan nama
            ajax: {
                url: 'salary/pagination',
                type: 'POST',
                data: function (e) {
                    e.filter_month = $('#filter_month').val(); // Ambil nilai filter bulan
                    e.csrf_token = token; // Pastikan token CSRF tetap terkirim
                }
            },
            columnDefs: [{
                targets: [0],
                className: 'text-center',
                orderable: false
            }],
            drawCallback: function () {
                syncSlipCheckboxes();
            }
        });

        // Multi-select & sequential download slip gaji satu per satu
        var selectedSlips = new Map();

        function updateSlipBatchUI() {
            var count = selectedSlips.size;
            $('#slip-selected-count').text(count);
            $('#btn-download-slip-batch').prop('disabled', count === 0);
            $('#btn-clear-slips').toggleClass('d-none', count === 0);
            var $pageChecks = $('#kt_table_1 tbody .slip-check');
            var allChecked = $pageChecks.length > 0 && $pageChecks.filter(':checked').length === $pageChecks.length;
            $('#check-all-page').prop('checked', allChecked);
        }

        function syncSlipCheckboxes() {
            $('#kt_table_1 tbody .slip-check').each(function () {
                var id = $(this).val();
                $(this).prop('checked', selectedSlips.has(id));
            });
            updateSlipBatchUI();
        }

        $(document).on('change', '.slip-check', function () {
            var id = $(this).val();
            if ($(this).is(':checked')) {
                selectedSlips.set(id, { nama: $(this).data('nama'), url: $(this).data('url') });
            } else {
                selectedSlips.delete(id);
            }
            updateSlipBatchUI();
        });

        $('#check-all-page').on('change', function () {
            var checked = $(this).is(':checked');
            $('#kt_table_1 tbody .slip-check').each(function () {
                $(this).prop('checked', checked);
                var id = $(this).val();
                if (checked) {
                    selectedSlips.set(id, { nama: $(this).data('nama'), url: $(this).data('url') });
                } else {
                    selectedSlips.delete(id);
                }
            });
            updateSlipBatchUI();
        });

        $('#btn-clear-slips').on('click', function () {
            selectedSlips.clear();
            syncSlipCheckboxes();
        });

        $('#btn-select-all-slips').on('click', function () {
            var $btn = $(this);
            var month = $('#filter_month').val() || '';
            $btn.prop('disabled', true);
            fetch('<?= base_url("salary/slip_list") ?>?filter_month=' + encodeURIComponent(month), { credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res && res.data) {
                        res.data.forEach(function (item) {
                            selectedSlips.set(item.id, { nama: item.nama, url: item.url });
                        });
                        syncSlipCheckboxes();
                    }
                })
                .catch(function () {
                    swal.fire('Gagal', 'Tidak dapat mengambil daftar karyawan.', 'error');
                })
                .finally(function () {
                    $btn.prop('disabled', false);
                });
        });

        // Minimal standard ZIP (STORE) builder in browser so PDFs are fetched 1-by-1 without server timeout
        function buildZipBlob(files) {
            var crcTable = new Uint32Array(256);
            for (var n = 0; n < 256; n++) {
                var c = n;
                for (var k = 0; k < 8; k++) c = (c & 1) ? (0xEDB88320 ^ (c >>> 1)) : (c >>> 1);
                crcTable[n] = c >>> 0;
            }
            function crc32(bytes) {
                var crc = 0xFFFFFFFF;
                for (var i = 0; i < bytes.length; i++) crc = crcTable[(crc ^ bytes[i]) & 0xFF] ^ (crc >>> 8);
                return (crc ^ 0xFFFFFFFF) >>> 0;
            }
            var enc = new TextEncoder();
            var localParts = [];
            var centralParts = [];
            var offset = 0;
            var now = new Date();
            var dosTime = ((now.getHours() & 31) << 11) | ((now.getMinutes() & 63) << 5) | ((now.getSeconds() >> 1) & 31);
            var dosDate = (((Math.max(1980, now.getFullYear()) - 1980) & 127) << 9) | (((now.getMonth() + 1) & 15) << 5) | (now.getDate() & 31);

            for (var i = 0; i < files.length; i++) {
                var nameBytes = enc.encode(files[i].name);
                var data = files[i].data;
                var crc = crc32(data);
                var lh = new ArrayBuffer(30);
                var lv = new DataView(lh);
                lv.setUint32(0, 0x04034b50, true);
                lv.setUint16(4, 20, true);
                lv.setUint16(6, 0x0800, true);
                lv.setUint16(8, 0, true);
                lv.setUint16(10, dosTime, true);
                lv.setUint16(12, dosDate, true);
                lv.setUint32(14, crc, true);
                lv.setUint32(18, data.length, true);
                lv.setUint32(22, data.length, true);
                lv.setUint16(26, nameBytes.length, true);
                lv.setUint16(28, 0, true);
                localParts.push(new Uint8Array(lh), nameBytes, data);

                var ch = new ArrayBuffer(46);
                var cv = new DataView(ch);
                cv.setUint32(0, 0x02014b50, true);
                cv.setUint16(4, 20, true);
                cv.setUint16(6, 20, true);
                cv.setUint16(8, 0x0800, true);
                cv.setUint16(10, 0, true);
                cv.setUint16(12, dosTime, true);
                cv.setUint16(14, dosDate, true);
                cv.setUint32(16, crc, true);
                cv.setUint32(20, data.length, true);
                cv.setUint32(24, data.length, true);
                cv.setUint16(28, nameBytes.length, true);
                cv.setUint16(30, 0, true);
                cv.setUint16(32, 0, true);
                cv.setUint16(34, 0, true);
                cv.setUint16(36, 0, true);
                cv.setUint32(38, 0, true);
                cv.setUint32(42, offset, true);
                centralParts.push(new Uint8Array(ch), nameBytes);

                offset += 30 + nameBytes.length + data.length;
            }

            var centralSize = centralParts.reduce(function (s, p) { return s + p.length; }, 0);
            var eocd = new ArrayBuffer(22);
            var ev = new DataView(eocd);
            ev.setUint32(0, 0x06054b50, true);
            ev.setUint16(4, 0, true);
            ev.setUint16(6, 0, true);
            ev.setUint16(8, files.length, true);
            ev.setUint16(10, files.length, true);
            ev.setUint32(12, centralSize, true);
            ev.setUint32(16, offset, true);
            ev.setUint16(20, 0, true);

            return new Blob(localParts.concat(centralParts, [new Uint8Array(eocd)]), { type: 'application/zip' });
        }

        $('#btn-download-slip-batch').on('click', async function () {
            var items = Array.from(selectedSlips.values());
            if (items.length === 0) return;

            var monthVal = $('#filter_month').val() || 'Terbaru';
            var cancelled = false;
            var zipEntries = [];
            var usedNames = {};
            var failList = [];

            swal.fire({
                title: 'Menyiapkan ZIP Slip Gaji...',
                html: '<div id="slip-dl-status">Memproses 0 / ' + items.length + '...</div>',
                allowOutsideClick: false,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Batal',
                didOpen: function () { swal.showLoading(); }
            }).then(function (res) {
                if (res.dismiss === swal.DismissReason.cancel) cancelled = true;
            });

            for (var i = 0; i < items.length; i++) {
                if (cancelled) break;
                var item = items[i];
                var statusEl = document.getElementById('slip-dl-status');
                if (statusEl) {
                    statusEl.textContent = 'Mengambil slip ' + (i + 1) + ' / ' + items.length + ': ' + item.nama;
                }
                try {
                    var sep = item.url.indexOf('?') === -1 ? '?' : '&';
                    var resp = await fetch(item.url + sep + 'download=1', { credentials: 'same-origin' });
                    var ct = (resp.headers.get('content-type') || '').toLowerCase();
                    if (!resp.ok || ct.indexOf('pdf') === -1) {
                        throw new Error('HTTP ' + resp.status);
                    }
                    var buf = await resp.arrayBuffer();
                    var safeName = String(item.nama || 'Karyawan').replace(/[^A-Za-z0-9 _.-]/g, '').trim() || 'Karyawan';
                    var baseFile = 'Slip Gaji - ' + safeName + ' - ' + monthVal;
                    var finalName = baseFile + '.pdf';
                    if (usedNames[finalName]) {
                        usedNames[finalName]++;
                        finalName = baseFile + ' (' + usedNames[finalName] + ').pdf';
                    } else {
                        usedNames[finalName] = 1;
                    }
                    zipEntries.push({ name: finalName, data: new Uint8Array(buf) });
                } catch (err) {
                    failList.push(item.nama);
                }
            }

            if (cancelled) {
                swal.fire('Dibatalkan', 'Proses download ZIP dibatalkan.', 'info');
                return;
            }
            if (zipEntries.length === 0) {
                swal.fire('Gagal', 'Tidak ada slip gaji yang berhasil diambil.', 'error');
                return;
            }

            var zipBlob = buildZipBlob(zipEntries);
            var a = document.createElement('a');
            var blobUrl = URL.createObjectURL(zipBlob);
            a.href = blobUrl;
            a.download = 'Slip Gaji - ' + monthVal + '.zip';
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(function () { URL.revokeObjectURL(blobUrl); }, 2000);

            if (failList.length > 0) {
                swal.fire('Selesai Sebagian', zipEntries.length + ' slip masuk ke ZIP. Gagal (' + failList.length + '): ' + failList.join(', '), 'warning');
            } else {
                swal.fire('Selesai', 'File ZIP berisi ' + zipEntries.length + ' slip gaji berhasil didownload.', 'success');
            }
        });

        $('#filter_month').change(function () {
            selectedSlips.clear();
            updateSlipBatchUI();
            table.ajax.reload();
        });

        $(document).on('click', '.btn-edit', function () {
            $('.modal .form-control').val(null)
            var object = 'salary'
            var editMonth = $('#filter_month').val() || '<?= date("Y-m") ?>'
            var bulanNama = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
            var parts = editMonth.split('-')
            $('#main-modal #edit_month').val(editMonth)
            $('#main-modal #edit_month_label').text('Periode: ' + (bulanNama[parseInt(parts[1], 10) - 1] || '') + ' ' + parts[0])
            $('#main-modal #modal-form').attr('action', 'salary/add')
            $('#main-modal').modal()

            var id = $(this).attr("data-id")
            $('#pengguna_id').val(id)

            fetch(object + '/edit/' + id + '?month=' + encodeURIComponent(editMonth))
                .then(function (resp) {
                    return resp.json()
                })
                .then(function (data) {
                    if (data && data.length > 0) {
                        $('#main-modal #pengguna_id').val(data[0].pengguna_id)
                        $('#main-modal #gaji_pokok').val(data[0].gaji_pokok)
                        $('#main-modal #tunjangan_konsumsi').val(data[0].tunjangan_konsumsi)
                        $('#main-modal #tunjangan_kinerja').val(data[0].tunjangan_kinerja)
                        $('#main-modal #tunjangan_komunikasi').val(data[0].tunjangan_komunikasi)
                        $('#main-modal #tunjangan_transportasi').val(data[0].tunjangan_transportasi)
                        $('#main-modal #tunjangan_bbm').val(data[0].tunjangan_bbm)
                        $('#main-modal #tunjangan_jabatan').val(data[0].tunjangan_jabatan)
                        $('#main-modal #bonus_tahunan').val(data[0].bonus_tahunan)
                        $('#main-modal #tunjangan_raya').val(data[0].tunjangan_raya)
                        $('#main-modal #pendapatan_lain').val(data[0].pendapatan_lain)
                        $('#main-modal #dasar_potongan_bpjs').val(data[0].dasar_potong_bpjs)
                        $('#main-modal #dasar_potongan_bpjs_tk').val(data[0].dasar_potong_bpjs_tk)
                        $('#main-modal #potongan').val(data[0].potongan)
                    }
                })
        })

        $(document).on('click', '.btn-komisi', function () {
            $('.modal .form-control').val(null)

            var object = 'salary'
            $('#main-modal-komisi #modal-form-komisi').attr('action', 'salary/add_komisi')
            $('#main-modal-komisi').modal()

            var id = $(this).attr("data-id")
            $('#main-modal-komisi #pengguna_id').val(id)

            fetch(object + '/edit_komisi/' + id)
                .then(function (resp) {
                    return resp.json()
                })
                .then(function (data) {
                    $('#main-modal #pengguna_id').val(data[0].pengguna_id)
                    $('#main-modal #komisi').val(data[0].jumlah)
                    $('#main-modal #bulan').val(data[0].bulan)
                })
        })

        $(document).on('click', '.btn-import-komisi', function() {
            $('#modal-import-komisi').modal('show');
        });

        $('#form-import-komisi').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('csrf_token', token);
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(resp) {
                    var data = typeof resp === 'string' ? JSON.parse(resp) : resp;
                    if (data.status === 'success' || data.type === 'success') {
                        swal.fire('Berhasil', data.message || 'Data Komisi Berhasil Diimpor', 'success');
                        $('#modal-import-komisi').modal('hide');
                        updateDatatable();
                    } else {
                        swal.fire('Gagal', data.message || 'Gagal mengimpor file', 'error');
                    }
                },
                error: function() {
                    swal.fire('Gagal', 'Terjadi kesalahan sistem saat upload file', 'error');
                }
            });
        });

        $(document).on('click', '.btn-import-pph21', function() {
            $('#modal-import-pph21').modal('show');
        });

        $('#form-import-pph21').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('csrf_token', token);
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(resp) {
                    var data = typeof resp === 'string' ? JSON.parse(resp) : resp;
                    if (data.status === 'success' || data.type === 'success') {
                        swal.fire('Berhasil', data.message || 'Data PPh 21 Berhasil Diimpor', 'success');
                        $('#modal-import-pph21').modal('hide');
                        updateDatatable();
                    } else {
                        swal.fire('Gagal', data.message || 'Gagal mengimpor file', 'error');
                    }
                },
                error: function() {
                    swal.fire('Gagal', 'Terjadi kesalahan sistem saat upload file', 'error');
                }
            });
        });

        $(document).on('click', '.btn-pen_lain', function () {
            $('.modal .form-control').val(null)

            var object = 'salary'
            $('#main-modal-pendapatan_lain #modal-form-pendapatan_lain').attr('action', 'salary/add_pendapatan_lain')
            $('#main-modal-pendapatan_lain').modal()

            var id = $(this).attr("data-id")
            $('#main-modal-pendapatan_lain #pengguna_id').val(id)

            fetch(object + '/edit_pendapatan_lain/' + id)
                .then(function (resp) {
                    return resp.json()
                })
                .then(function (data) {
                    $('#main-modal-pendapatan_lain #pengguna_id').val(data[0].pengguna_id)
                    $('#main-modal-pendapatan_lain #pendapatan').val(data[0].jumlah)
                    $('#main-modal-pendapatan_lain #pengurangan').val(data[0].pengurangan)
                })
        })

        $(document).on('click', '.btn-pph21', function() {
            $('.modal .form-control').val(null)
           
            var object = 'salary'
            $('#main-modal-pph21 #modal-form-pph21').attr('action', 'salary/add_pph21')
            $('#main-modal-pph21').modal()

            var id = $(this).attr("data-id")
            $('#main-modal-pph21 #pengguna_id').val(id)

            fetch(object + '/edit_pph21/' + id)
                .then(function(resp) {
                    return resp.json()
                })
                .then(function(data) {
                    $('#main-modal-pph21 #pengguna_id').val(data[0].pengguna_id)
                    $('#main-modal-pph21 #pph21').val(data[0].jumlah)
                })
        })

        $(document).on('click', '.btn-print', function () {
            var print = $(this).attr('print')
            var link = 'salary/show/print/' + print
            window.open('<?= base_url() ?>' + link)
        })

        $(document).on('click', '.btn-print-salary', function () {
            var print = $(this).attr('print');
            var month = $('#filter_month').val() || '<?= date("Y-m") ?>';
            var link = 'salary/print/' + month;
            window.open('<?= base_url() ?>' + link);
        });
        $('#btn-tutupbuku').click(function() {
            var print = $(this).attr('print')
            console.log(print);
            var link = 'salary/tutupbuku'
            window.open('<?= base_url() ?>' + link,"_self")
        })
        $('#btn-history').click(function() {
            var print = $(this).attr('print')
            console.log(print);
            var link = 'salary/history_salary'
            window.open('<?= base_url() ?>' + link,"_self")
        })
        $(document).on('click', '.btn-resign', function () {
            var print = $(this).attr('print')
            var link = 'salary/print_resign/'
            window.open('<?= base_url() ?>' + link)
        })
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>
