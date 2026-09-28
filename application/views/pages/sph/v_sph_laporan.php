<!-- View: v_sph_laporan.php - Laporan Surat Penawaran Harga -->

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-file-excel mr-2"></i>Export Laporan Surat Penawaran Harga
        </h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('sph/export_laporan') ?>" method="get" id="formLaporan">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Dari Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="dari" id="tanggalDari" required 
                               value="<?= date('Y-m-01') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Sampai Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="sampai" id="tanggalSampai" required 
                               value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel mr-1"></i> Export Excel
                            </button>
                            <button type="button" class="btn btn-danger" id="btnExportPdf">
                                <i class="fas fa-file-pdf mr-1"></i> Generate PDF
                            </button>
                            <a href="<?= base_url('sph') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
        <hr>
        
        <div class="alert alert-info">
            <h6 class="font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Informasi</h6>
            <p class="mb-0">
                <strong>Export Excel:</strong> File Excel berisi <strong>data lengkap SPH</strong> dalam beberapa sheet: Ringkasan SPH, detail produk/item yang ditawarkan, serta keterangan master dan custom.<br>
                <strong>Generate PDF:</strong> Menggabungkan semua surat SPH dalam rentang tanggal menjadi <strong>1 file PDF</strong> (format cetak lengkap).
            </p>
        </div>
        
        <!-- Quick Stats -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="countBulanIni"><?= $stats['bulan_ini'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tahun Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="countTahunIni"><?= $stats['tahun_ini'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Signed Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="countSigned"><?= $stats['signed_bulan_ini'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-signature fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Draft</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="countDraft"><?= $stats['draft'] ?? 0 ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-edit fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toastr fallback using PNotify
    if (typeof toastr === 'undefined') {
        window.toastr = {
            success: function(msg, title) { new PNotify({ title: title || 'Sukses', text: msg, type: 'success', delay: 3000 }); },
            error: function(msg, title) { new PNotify({ title: title || 'Error', text: msg, type: 'error', delay: 4000 }); },
            warning: function(msg, title) { new PNotify({ title: title || 'Peringatan', text: msg, type: 'warning', delay: 3500 }); },
            info: function(msg, title) { new PNotify({ title: title || 'Info', text: msg, type: 'info', delay: 3000 }); }
        };
    }
    
    // Validasi form
    $('#formLaporan').on('submit', function(e) {
        var dari = $('#tanggalDari').val();
        var sampai = $('#tanggalSampai').val();
        
        if (dari > sampai) {
            e.preventDefault();
            toastr.error('Tanggal Dari tidak boleh lebih besar dari Tanggal Sampai');
            return false;
        }
    });
    
    // Generate PDF Bulk
    $('#btnExportPdf').on('click', function() {
        var dari = $('#tanggalDari').val();
        var sampai = $('#tanggalSampai').val();
        
        if (!dari || !sampai) {
            toastr.error('Tanggal Dari dan Sampai harus diisi');
            return false;
        }
        
        if (dari > sampai) {
            toastr.error('Tanggal Dari tidak boleh lebih besar dari Tanggal Sampai');
            return false;
        }
        
        // Open PDF in new tab
        var url = '<?= base_url('sph/export_pdf_bulk') ?>?dari=' + dari + '&sampai=' + sampai;
        window.open(url, '_blank');
    });
});
</script>