<!-- Visual Dashboard Stok Gudang & Pergerakan In/Out -->
<style>
    #menu_gudang_filter .dropdown-item:hover,
    #menu_kategori_filter .dropdown-item:hover,
    #menu_barang_filter .dropdown-item:hover {
        background-color: #f1f5f9;
    }
    #menu_gudang_filter input[type="checkbox"],
    #menu_kategori_filter input[type="checkbox"],
    #menu_barang_filter input[type="checkbox"] {
        accent-color: #2563eb;
    }
    .stock-dash-wrapper {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        position: relative;
        transition: all 0.3s ease;
    }

    .stock-dash-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding-bottom: 16px;
        margin-bottom: 18px;
        border-bottom: 1px solid #e2e8f0;
    }

    .stock-dash-title-box {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .stock-dash-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
        flex-shrink: 0;
    }

    .stock-dash-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
    }

    .stock-dash-desc {
        font-size: 12px;
        color: #64748b;
        margin: 0;
    }

    .stock-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: calc(100% - 16px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stock-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.06);
    }

    .kpi-icon-pill {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .kpi-stock-val {
        font-size: 24px;
        font-weight: 800;
        line-height: 1.1;
        margin: 6px 0 2px 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .stock-chart-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        height: calc(100% - 16px);
        display: flex;
        flex-direction: column;
    }

    .stock-chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 1px dashed #e2e8f0;
    }

    .stock-chart-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-top-stock {
        font-size: 12px;
        margin: 0;
    }

    .table-top-stock th {
        font-weight: 700;
        color: #64748b;
        background: #f8fafc;
        border-top: none;
        padding: 8px 10px;
        text-transform: uppercase;
        font-size: 11px;
    }

    .table-top-stock td {
        padding: 8px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .badge-rank {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-rank-1 { background: #fef3c7; color: #b45309; }
    .badge-rank-2 { background: #f1f5f9; color: #475569; }
    .badge-rank-3 { background: #ffedd5; color: #c2410c; }
    .badge-rank-other { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

    .dash-filter-select {
        height: 34px;
        font-size: 12.5px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
        font-weight: 500;
        color: #1e293b;
    }

    .dash-filter-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
    }

    .btn-dash-toggle {
        font-size: 12px;
        font-weight: 600;
        border-radius: 8px;
        padding: 6px 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .dash-loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(248, 250, 252, 0.7);
        backdrop-filter: blur(2px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 20;
        border-radius: 14px;
    }
</style>

<div class="stock-dash-wrapper" id="stock_dash_container">
    <!-- Loading overlay -->
    <div class="dash-loading-overlay" id="dash_loading">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem;"></div>
            <div style="font-size: 12px; font-weight: 600; color: #1e293b; margin-top: 6px;">Memuat Data Dashboard...</div>
        </div>
    </div>

    <!-- Header bar -->
    <div class="stock-dash-header">
        <div class="stock-dash-title-box">
            <div class="stock-dash-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <h4 class="stock-dash-title">Visual Dashboard Stok Gudang & Pergerakan In/Out</h4>
                <p class="stock-dash-desc">Analitik visual stok per kategori, per gudang penyimpanan, serta tren pergerakan stok keluar & masuk.</p>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
            <!-- Pilihan Gudang Penyimpanan (Multi-select Checkbox) -->
            <div class="d-flex align-items-center" style="gap: 5px;">
                <label class="mb-0" style="font-size: 12px; font-weight: 600; color: #475569; white-space: nowrap;">
                    <i class="fas fa-warehouse text-primary mr-1"></i> Gudang:
                </label>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-default btn-sm dropdown-toggle dash-filter-btn" type="button" id="dropdownGudangFilter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: #ffffff; border: 1px solid #cbd5e1; font-weight: 600; font-size: 12px; color: #1e293b; padding: 6px 12px; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); min-width: 195px; text-align: left; display: flex; align-items: center; justify-content: space-between;">
                        <span class="text-truncate mr-2"><span id="label_filter_gudang">Semua Gudang</span></span>
                        <span class="d-flex align-items-center">
                            <span class="badge badge-primary mr-1" id="badge_filter_gudang_count" style="display:none; font-size:10px; padding: 2px 6px; border-radius: 6px;">0</span>
                            <i class="fas fa-chevron-down text-muted" style="font-size: 10px;"></i>
                        </span>
                    </button>
                    <div class="dropdown-menu p-2 shadow-lg" aria-labelledby="dropdownGudangFilter" id="menu_gudang_filter" style="min-width: 270px; max-height: 320px; overflow-y: auto; border-radius: 10px; border: 1px solid #e2e8f0; z-index: 1050;">
                        <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                            <span class="small font-weight-bold text-muted text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">Bisa Centang &gt; 1 Gudang</span>
                            <div>
                                <a href="javascript:;" id="btn_check_all_gudang" class="small text-primary font-weight-bold mr-2" style="font-size: 11px;">Pilih Semua</a>
                                <a href="javascript:;" id="btn_clear_gudang" class="small text-danger" style="font-size: 11px;">Reset</a>
                            </div>
                        </div>
                        <div class="gudang-checkbox-list">
                            <?php if (!empty($gudang)) {
                                foreach ($gudang as $rowG) {
                                    $gid = is_numeric($rowG->id_gudang) ? (int)$rowG->id_gudang : (int)decrypt($rowG->id_gudang);
                                    echo '<label class="dropdown-item px-2 py-1 mb-1 rounded d-flex align-items-center" style="cursor: pointer; font-size: 12px; font-weight: 500; color: #334155; margin-bottom: 2px;">';
                                    echo '<input type="checkbox" class="chk-gudang-item mr-2" value="' . $gid . '" data-nama="' . htmlspecialchars($rowG->nama_gudang) . '" style="cursor: pointer; width: 15px; height: 15px;"> ';
                                    echo '<span class="text-truncate">' . htmlspecialchars($rowG->nama_gudang) . '</span>';
                                    echo '</label>';
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Kategori Barang (Multi-select Checkbox) -->
            <div class="d-flex align-items-center" style="gap: 5px;">
                <label class="mb-0" style="font-size: 12px; font-weight: 600; color: #475569; white-space: nowrap;">
                    <i class="fas fa-tags text-info mr-1"></i> Kategori:
                </label>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-default btn-sm dropdown-toggle dash-filter-btn" type="button" id="dropdownKategoriFilter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: #ffffff; border: 1px solid #cbd5e1; font-weight: 600; font-size: 12px; color: #1e293b; padding: 6px 12px; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); min-width: 180px; text-align: left; display: flex; align-items: center; justify-content: space-between;">
                        <span class="text-truncate mr-2"><span id="label_filter_kategori">Semua Kategori</span></span>
                        <span class="d-flex align-items-center">
                            <span class="badge badge-info mr-1" id="badge_filter_kategori_count" style="display:none; font-size:10px; padding: 2px 6px; border-radius: 6px;">0</span>
                            <i class="fas fa-chevron-down text-muted" style="font-size: 10px;"></i>
                        </span>
                    </button>
                    <div class="dropdown-menu p-2 shadow-lg" aria-labelledby="dropdownKategoriFilter" id="menu_kategori_filter" style="min-width: 270px; max-height: 320px; overflow-y: auto; border-radius: 10px; border: 1px solid #e2e8f0; z-index: 1050;">
                        <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                            <span class="small font-weight-bold text-muted text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">Bisa Centang &gt; 1 Kategori</span>
                            <div>
                                <a href="javascript:;" id="btn_check_all_kategori" class="small text-primary font-weight-bold mr-2" style="font-size: 11px;">Pilih Semua</a>
                                <a href="javascript:;" id="btn_clear_kategori" class="small text-danger" style="font-size: 11px;">Reset</a>
                            </div>
                        </div>
                        <div class="kategori-checkbox-list">
                            <?php if (!empty($kategori_barang)) {
                                foreach ($kategori_barang as $rowK) {
                                    $kid = is_numeric($rowK->id_kategori) ? (int)$rowK->id_kategori : (int)decrypt($rowK->id_kategori);
                                    $kenc = is_numeric($rowK->id_kategori) ? encrypt($rowK->id_kategori) : $rowK->id_kategori;
                                    echo '<label class="dropdown-item px-2 py-1 mb-1 rounded d-flex align-items-center" style="cursor: pointer; font-size: 12px; font-weight: 500; color: #334155; margin-bottom: 2px;">';
                                    echo '<input type="checkbox" class="chk-kategori-item mr-2" value="' . $kid . '" data-enc="' . htmlspecialchars($kenc) . '" data-nama="' . htmlspecialchars($rowK->nama_kategori) . '" style="cursor: pointer; width: 15px; height: 15px;"> ';
                                    echo '<span class="text-truncate">' . htmlspecialchars($rowK->nama_kategori) . '</span>';
                                    echo '</label>';
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Nama Barang (Multi-select Checkbox dengan Live Search) -->
            <div class="d-flex align-items-center" style="gap: 5px;">
                <label class="mb-0" style="font-size: 12px; font-weight: 600; color: #475569; white-space: nowrap;">
                    <i class="fas fa-box-open text-warning mr-1"></i> Nama Barang:
                </label>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-default btn-sm dropdown-toggle dash-filter-btn" type="button" id="dropdownBarangFilter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: #ffffff; border: 1px solid #cbd5e1; font-weight: 600; font-size: 12px; color: #1e293b; padding: 6px 12px; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); min-width: 195px; text-align: left; display: flex; align-items: center; justify-content: space-between;">
                        <span class="text-truncate mr-2"><span id="label_filter_barang">Semua Barang</span></span>
                        <span class="d-flex align-items-center">
                            <span class="badge badge-warning mr-1" id="badge_filter_barang_count" style="display:none; font-size:10px; padding: 2px 6px; border-radius: 6px;">0</span>
                            <i class="fas fa-chevron-down text-muted" style="font-size: 10px;"></i>
                        </span>
                    </button>
                    <div class="dropdown-menu p-2 shadow-lg" aria-labelledby="dropdownBarangFilter" id="menu_barang_filter" style="min-width: 320px; max-height: 380px; overflow-y: hidden; border-radius: 10px; border: 1px solid #e2e8f0; z-index: 1050;">
                        <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                            <span class="small font-weight-bold text-muted text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">Bisa Centang &gt; 1 Barang</span>
                            <a href="javascript:;" id="btn_clear_barang" class="small text-danger font-weight-bold" style="font-size: 11px;">Reset</a>
                        </div>
                        <div class="px-1 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted" style="font-size: 11px;"></i></span>
                                </div>
                                <input type="text" id="input_search_barang_item" class="form-control form-control-sm border-left-0" placeholder="Ketik cari nama / kode barang...">
                            </div>
                        </div>
                        <div id="container_barang_checkboxes" style="max-height: 240px; overflow-y: auto;">
                            <div class="p-2 text-center text-muted small"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat daftar barang...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tahun -->
            <div class="d-flex align-items-center" style="gap: 5px;">
                <label for="dash_select_tahun" class="mb-0" style="font-size: 12px; font-weight: 600; color: #475569;">
                    <i class="fas fa-calendar-alt text-success mr-1"></i> Tahun:
                </label>
                <select id="dash_select_tahun" class="form-control dash-filter-select" style="width: 85px;">
                    <?php
                    $curYear = (int)date('Y');
                    for ($y = $curYear; $y >= $curYear - 4; $y--) {
                        echo '<option value="' . $y . '" ' . ($y == $curYear ? 'selected' : '') . '>' . $y . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Tombol Reset Filter Kombinasi -->
            <button type="button" class="btn btn-light btn-dash-toggle border text-danger" id="btn_reset_dash_filter" title="Reset Semua Filter" style="display:none;">
                <i class="fas fa-undo"></i>
                <span>Reset</span>
            </button>

            <!-- Toggle Tombol Sembunyikan / Tampilkan -->
            <button type="button" class="btn btn-light btn-dash-toggle border" id="btn_toggle_dash_body" title="Tampilkan/Sembunyikan Visual">
                <i class="fas fa-chevron-up" id="icon_toggle_dash"></i>
                <span id="text_toggle_dash">Sembunyikan</span>
            </button>
        </div>
    </div>

    <!-- Dashboard Body (Collapsible) -->
    <div id="stock_dash_body">
        <!-- 1. Empat Kartu KPI Metrics -->
        <div class="row">
            <!-- Total Stok Fisik -->
            <div class="col-xl-3 col-sm-6">
                <div class="stock-kpi-card" style="border-left: 4px solid #2563eb;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Stok Fisik Ready</span>
                            <div class="kpi-stock-val text-primary" id="kpi_total_stok">-</div>
                            <span style="font-size: 11.5px; color: #64748b;" id="kpi_gudang_label">Semua Gudang Penyimpanan</span>
                        </div>
                        <div class="kpi-icon-pill" style="background: #eff6ff; color: #2563eb;">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total SKU Item Ready -->
            <div class="col-xl-3 col-sm-6">
                <div class="stock-kpi-card" style="border-left: 4px solid #7c3aed;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Item Aktif (Stok &gt; 0)</span>
                            <div class="kpi-stock-val text-purple" style="color: #7c3aed;" id="kpi_total_sku">-</div>
                            <span style="font-size: 11.5px; color: #64748b;"><i class="fas fa-check-circle text-success mr-1"></i>Produk Siap Kirim</span>
                        </div>
                        <div class="kpi-icon-pill" style="background: #f5f3ff; color: #7c3aed;">
                            <i class="fas fa-cubes"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stok Masuk (Inbound) -->
            <div class="col-xl-3 col-sm-6">
                <div class="stock-kpi-card" style="border-left: 4px solid #10b981;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Stok Masuk (<span class="dash-span-tahun"><?= date('Y') ?></span>)</span>
                            <div class="kpi-stock-val text-success" id="kpi_masuk_qty">-</div>
                            <span style="font-size: 11.5px; color: #10b981; font-weight: 600;" id="kpi_masuk_trx">- Trx Penerimaan</span>
                        </div>
                        <div class="kpi-icon-pill" style="background: #ecfdf5; color: #10b981;">
                            <i class="fas fa-arrow-circle-down"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stok Keluar (Outbound) -->
            <div class="col-xl-3 col-sm-6">
                <div class="stock-kpi-card" style="border-left: 4px solid #f59e0b;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Stok Keluar (<span class="dash-span-tahun"><?= date('Y') ?></span>)</span>
                            <div class="kpi-stock-val text-warning" style="color: #d97706 !important;" id="kpi_keluar_qty">-</div>
                            <span style="font-size: 11.5px; color: #d97706; font-weight: 600;" id="kpi_keluar_trx">- Trx Pengiriman</span>
                        </div>
                        <div class="kpi-icon-pill" style="background: #fffbeb; color: #d97706;">
                            <i class="fas fa-arrow-circle-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Dual Visual Charts: Stok per Kategori & Stok per Gudang -->
        <div class="row">
            <!-- Chart Kategori -->
            <div class="col-lg-6">
                <div class="stock-chart-panel">
                    <div class="stock-chart-header">
                        <h5 class="stock-chart-title" id="title_chart_kategori">
                            <i class="fas fa-tags text-primary"></i> Total Stok Berdasarkan Kategori
                        </h5>
                        <div class="d-flex align-items-center" style="gap: 6px;">
                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2" id="btn_back_all_kategori" style="font-size: 11px; display: none;" title="Kembali ke Semua Kategori">
                                <i class="fas fa-arrow-left mr-1"></i>Semua Kategori
                            </button>
                            <span class="badge badge-light border text-primary" id="badge_total_kategori" style="font-size: 11px;">0 Kategori</span>
                        </div>
                    </div>
                    <div style="position: relative; height: 280px; width: 100%;">
                        <canvas id="chartStokKategori"></canvas>
                    </div>
                    <div class="mt-2 text-center text-muted" style="font-size: 11px;" id="hint_chart_kategori">
                        <i class="fas fa-mouse-pointer mr-1"></i> Klik pada bar kategori untuk memfilter grafik & tabel ke kategori tersebut.
                    </div>
                </div>
            </div>

            <!-- Chart Gudang -->
            <div class="col-lg-6">
                <div class="stock-chart-panel">
                    <div class="stock-chart-header">
                        <h5 class="stock-chart-title">
                            <i class="fas fa-warehouse text-success"></i> Total Stok per Pilihan Gudang Penyimpanan
                        </h5>
                        <span class="badge badge-light border text-success" id="badge_total_gudang" style="font-size: 11px;">0 Gudang</span>
                    </div>
                    <div style="position: relative; height: 280px; width: 100%;">
                        <canvas id="chartStokGudang"></canvas>
                    </div>
                    <div class="mt-2 text-center text-muted" style="font-size: 11px;">
                        <i class="fas fa-mouse-pointer mr-1"></i> Klik pada bar/diagram gudang untuk memfilter dashboard ke gudang tersebut.
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Pergerakan Stok Keluar & Masuk + Top 10 Barang -->
        <div class="row">
            <!-- Chart Inbound vs Outbound Movement -->
            <div class="col-lg-7">
                <div class="stock-chart-panel">
                    <div class="stock-chart-header">
                        <h5 class="stock-chart-title">
                            <i class="fas fa-exchange-alt text-info"></i> Tren Stok Masuk vs Stok Keluar (<span class="dash-span-tahun"><?= date('Y') ?></span>)
                        </h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-sm btn-primary active" id="btn_mode_qty" style="font-size: 11px; padding: 3px 8px;">Unit (Qty)</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn_mode_trx" style="font-size: 11px; padding: 3px 8px;">Transaksi</button>
                        </div>
                    </div>
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="chartStokMovement"></canvas>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top" style="font-size: 11.5px;">
                        <span class="text-success"><i class="fas fa-square mr-1"></i> Inbound (Masuk): <strong id="sum_inbound_mode">0</strong></span>
                        <span class="text-warning"><i class="fas fa-square mr-1"></i> Outbound (Keluar): <strong id="sum_outbound_mode">0</strong></span>
                        <span class="text-muted"><i class="fas fa-balance-scale mr-1"></i> Net: <strong id="sum_net_mode">0</strong></span>
                    </div>
                </div>
            </div>

            <!-- Top 10 Barang Terbanyak -->
            <div class="col-lg-5">
                <div class="stock-chart-panel">
                    <div class="stock-chart-header">
                        <h5 class="stock-chart-title">
                            <i class="fas fa-trophy text-warning"></i> Top 10 Barang Stok Terbanyak
                        </h5>
                        <span class="badge badge-light border text-muted" id="badge_top_barang_context" style="font-size: 11px;">Semua Gudang</span>
                    </div>
                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-hover table-top-stock">
                            <thead>
                                <tr>
                                    <th style="width: 32px; text-align: center;">#</th>
                                    <th>Nama Barang</th>
                                    <th style="text-align: right; width: 85px;">Stok Ready</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_top_barang">
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Memuat rincian stok barang...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js (v3) for high performance modern charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>

<script>
    window._ChartV3StockDash = window.Chart;
    document.addEventListener('DOMContentLoaded', function() {
        const ChartLib = window._ChartV3StockDash || window.Chart;
        let chartKategoriInstance = null;
        let chartGudangInstance   = null;
        let chartMovementInstance = null;
        let cachedMovementData    = [];
        let currentMovementMode   = 'qty'; // 'qty' | 'trx'

        const colorPalette = [
            '#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4',
            '#ec4899', '#3b82f6', '#14b8a6', '#f97316', '#6366f1',
            '#84cc16', '#a855f7', '#0ea5e9', '#eab308', '#64748b'
        ];

        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        }

        function showLoading() {
            $('#dash_loading').css('display', 'flex');
        }

        function hideLoading() {
            $('#dash_loading').hide();
        }

        // Toggle Dashboard Collapsible Body
        $('#btn_toggle_dash_body').on('click', function() {
            const body = $('#stock_dash_body');
            const icon = $('#icon_toggle_dash');
            const text = $('#text_toggle_dash');
            if (body.is(':visible')) {
                body.slideUp(200);
                icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                text.text('Tampilkan');
            } else {
                body.slideDown(200);
                icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                text.text('Sembunyikan');
            }
        });

        // Mode Switch (Qty vs Transaksi)
        $('#btn_mode_qty').on('click', function() {
            if (currentMovementMode === 'qty') return;
            currentMovementMode = 'qty';
            $(this).addClass('btn-primary active').removeClass('btn-outline-primary');
            $('#btn_mode_trx').addClass('btn-outline-primary').removeClass('btn-primary active');
            renderMovementChart(cachedMovementData);
        });

        $('#btn_mode_trx').on('click', function() {
            if (currentMovementMode === 'trx') return;
            currentMovementMode = 'trx';
            $(this).addClass('btn-primary active').removeClass('btn-outline-primary');
            $('#btn_mode_qty').addClass('btn-outline-primary').removeClass('btn-primary active');
            renderMovementChart(cachedMovementData);
        });

        // Change Filter Gudang / Kategori / Tahun / Nama Barang
        let searchNamaTimer = null;
        let isSyncingKategori = false;

        function syncTableFilterKategoriFromDash() {
            if (isSyncingKategori) return;
            const $tableKat = $('#filter_kategori');
            if (!$tableKat.length) return;
            const checkedKats = $('.chk-kategori-item:checked');
            let encVal = '';
            if (checkedKats.length === 1) {
                encVal = checkedKats.first().attr('data-enc') || '';
            }
            if (($tableKat.val() || '') !== encVal) {
                isSyncingKategori = true;
                $tableKat.val(encVal);
                if (typeof updateDatatable === 'function') {
                    updateDatatable();
                } else {
                    $tableKat.trigger('change');
                }
                isSyncingKategori = false;
            }
        }

        // Multi-select Kategori Event Handlers
        $('#menu_kategori_filter').on('click', function(e) {
            e.stopPropagation();
        });

        function updateKategoriFilterLabel() {
            const totalKat = $('.chk-kategori-item').length;
            const checkedCount = $('.chk-kategori-item:checked').length;
            const $label = $('#label_filter_kategori');
            const $badge = $('#badge_filter_kategori_count');

            if (checkedCount === 0 || checkedCount === totalKat) {
                $label.text('Semua Kategori');
                $badge.hide();
            } else if (checkedCount === 1) {
                const name = $('.chk-kategori-item:checked').first().data('nama') || '1 Kategori';
                $label.text(name);
                $badge.text('1').show();
            } else if (checkedCount === 2) {
                const names = [];
                $('.chk-kategori-item:checked').each(function() { names.push($(this).data('nama')); });
                $label.text(names.join(', '));
                $badge.text('2').show();
            } else {
                $label.text(checkedCount + ' Kategori Terpilih');
                $badge.text(checkedCount).show();
            }
        }

        $(document).on('change', '.chk-kategori-item', function() {
            updateKategoriFilterLabel();
            syncTableFilterKategoriFromDash();
            loadDashboardData();
        });

        $('#btn_check_all_kategori').on('click', function(e) {
            e.preventDefault();
            $('.chk-kategori-item').prop('checked', true);
            updateKategoriFilterLabel();
            syncTableFilterKategoriFromDash();
            loadDashboardData();
        });

        $('#btn_clear_kategori').on('click', function(e) {
            e.preventDefault();
            $('.chk-kategori-item').prop('checked', false);
            updateKategoriFilterLabel();
            syncTableFilterKategoriFromDash();
            loadDashboardData();
        });

        // Multi-select Barang Event Handlers
        $('#menu_barang_filter').on('click', function(e) {
            e.stopPropagation();
        });

        function updateBarangFilterLabel() {
            const checkedCount = $('.chk-barang-item:checked').length;
            const $label = $('#label_filter_barang');
            const $badge = $('#badge_filter_barang_count');

            if (checkedCount === 0) {
                $label.text('Semua Barang');
                $badge.hide();
            } else if (checkedCount === 1) {
                const name = $('.chk-barang-item:checked').first().data('nama') || '1 Barang';
                $label.text(name.length > 20 ? name.substring(0, 18) + '...' : name);
                $badge.text('1').show();
            } else if (checkedCount === 2) {
                const names = [];
                $('.chk-barang-item:checked').each(function() { names.push($(this).data('nama')); });
                $label.text(names.join(', '));
                $badge.text('2').show();
            } else {
                $label.text(checkedCount + ' Barang Terpilih');
                $badge.text(checkedCount).show();
            }
        }

        $(document).on('change', '.chk-barang-item', function() {
            updateBarangFilterLabel();
            loadDashboardData();
        });

        $('#btn_clear_barang').on('click', function(e) {
            e.preventDefault();
            $('.chk-barang-item').prop('checked', false);
            updateBarangFilterLabel();
            loadDashboardData();
        });

        $('#input_search_barang_item').on('input', function() {
            const q = $(this).val().toLowerCase().trim();
            if (!q) {
                $('.barang-item-row').show();
            } else {
                $('.barang-item-row').each(function() {
                    const txt = ($(this).attr('data-search') || $(this).text()).toLowerCase();
                    if (txt.indexOf(q) !== -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });

        // Multi-select Gudang Event Handlers
        $('#menu_gudang_filter').on('click', function(e) {
            e.stopPropagation();
        });

        function updateGudangFilterLabel() {
            const totalGudang = $('.chk-gudang-item').length;
            const checkedCount = $('.chk-gudang-item:checked').length;
            const $label = $('#label_filter_gudang');
            const $badge = $('#badge_filter_gudang_count');

            if (checkedCount === 0 || checkedCount === totalGudang) {
                $label.text('Semua Gudang');
                $badge.hide();
            } else if (checkedCount === 1) {
                const name = $('.chk-gudang-item:checked').first().data('nama') || '1 Gudang';
                $label.text(name);
                $badge.text('1').show();
            } else if (checkedCount === 2) {
                const names = [];
                $('.chk-gudang-item:checked').each(function() { names.push($(this).data('nama')); });
                $label.text(names.join(', '));
                $badge.text('2').show();
            } else {
                $label.text(checkedCount + ' Gudang Terpilih');
                $badge.text(checkedCount).show();
            }
        }

        $(document).on('change', '.chk-gudang-item', function() {
            updateGudangFilterLabel();
            loadDashboardData();
        });

        $('#btn_check_all_gudang').on('click', function(e) {
            e.preventDefault();
            $('.chk-gudang-item').prop('checked', true);
            updateGudangFilterLabel();
            loadDashboardData();
        });

        $('#btn_clear_gudang').on('click', function(e) {
            e.preventDefault();
            $('.chk-gudang-item').prop('checked', false);
            updateGudangFilterLabel();
            loadDashboardData();
        });

        $('#dash_select_tahun').on('change', function() {
            loadDashboardData();
        });

        $('#dash_select_kategori').on('change', function() {
            syncTableFilterKategoriFromDash();
            loadDashboardData();
        });

        // Sinkronisasi apabila user memilih "Filter By Kategori" di atas tabel bawah
        $(document).on('change', '#filter_kategori', function() {
            if (isSyncingKategori) return;
            const encVal = $(this).val() || '';
            const selectedText = ($(this).find('option:selected').text() || '').trim().toLowerCase();
            let matchedId = '0';
            $('.chk-kategori-item').prop('checked', false);
            if (encVal !== '') {
                $('.chk-kategori-item').each(function() {
                    const optEnc = $(this).attr('data-enc') || '';
                    const optText = ($(this).attr('data-nama') || '').trim().toLowerCase();
                    if (optEnc === encVal || (selectedText && optText === selectedText)) {
                        $(this).prop('checked', true);
                        return false;
                    }
                });
            }
            updateKategoriFilterLabel();
            loadDashboardData();
        });

        $('#btn_back_all_kategori').on('click', function() {
            $('#dash_select_kategori').val('0').trigger('change');
        });

        $('#dash_search_nama').on('input change', function() {
            clearTimeout(searchNamaTimer);
            searchNamaTimer = setTimeout(function() {
                loadDashboardData();
            }, 350);
        });

        $('#btn_reset_dash_filter').on('click', function() {
            $('.chk-gudang-item').prop('checked', false);
            updateGudangFilterLabel();
            $('.chk-kategori-item').prop('checked', false);
            updateKategoriFilterLabel();
            $('.chk-barang-item').prop('checked', false);
            updateBarangFilterLabel();
            $('#input_search_barang_item').val('').trigger('input');
            syncTableFilterKategoriFromDash();
            loadDashboardData();
        });

        function loadDashboardData() {
            const selectedGudangIds = [];
            const selectedGudangNames = [];
            $('.chk-gudang-item:checked').each(function() {
                selectedGudangIds.push($(this).val());
                selectedGudangNames.push($(this).data('nama'));
            });
            const totalGudang = $('.chk-gudang-item').length;
            const isGudangFiltered = (selectedGudangIds.length > 0 && selectedGudangIds.length < totalGudang);

            const selectedKatIds = [];
            const selectedKatNames = [];
            $('.chk-kategori-item:checked').each(function() {
                selectedKatIds.push($(this).val());
                selectedKatNames.push($(this).data('nama'));
            });
            const totalKat = $('.chk-kategori-item').length;
            const isKatFiltered = (selectedKatIds.length > 0 && selectedKatIds.length < totalKat);

            const selectedBarangIds = [];
            const selectedBarangNames = [];
            $('.chk-barang-item:checked').each(function() {
                selectedBarangIds.push($(this).val());
                selectedBarangNames.push($(this).data('nama'));
            });
            const isBarangFiltered = (selectedBarangIds.length > 0);

            const tahun = $('#dash_select_tahun').val() || new Date().getFullYear();

            if (isGudangFiltered || isKatFiltered || isBarangFiltered) {
                $('#btn_reset_dash_filter').show();
            } else {
                $('#btn_reset_dash_filter').hide();
            }

            $('.dash-span-tahun').text(tahun);
            showLoading();

            const activeFilters = [];
            if (isGudangFiltered) {
                if (selectedGudangNames.length <= 2) {
                    activeFilters.push(selectedGudangNames.join(', '));
                } else {
                    activeFilters.push(selectedGudangNames.length + ' Gudang Terpilih');
                }
            }
            if (isKatFiltered) {
                if (selectedKatNames.length <= 2) {
                    activeFilters.push(selectedKatNames.join(', '));
                } else {
                    activeFilters.push(selectedKatNames.length + ' Kategori Terpilih');
                }
            }
            if (isBarangFiltered) {
                if (selectedBarangNames.length <= 2) {
                    activeFilters.push(selectedBarangNames.join(', '));
                } else {
                    activeFilters.push(selectedBarangNames.length + ' Barang Terpilih');
                }
            }

            const contextText = activeFilters.length > 0 ? activeFilters.join(' • ') : 'Semua Gudang, Kategori & Barang';
            $('#kpi_gudang_label').text(contextText);
            $('#badge_top_barang_context').text(contextText);

            $.ajax({
                url: '<?= base_url("stock_gudang/get_dashboard_stats") ?>',
                type: 'GET',
                data: {
                    tahun: tahun,
                    id_gudang: isGudangFiltered ? selectedGudangIds.join(',') : '',
                    id_kategori: isKatFiltered ? selectedKatIds.join(',') : '',
                    id_barang: isBarangFiltered ? selectedBarangIds.join(',') : ''
                },
                dataType: 'json',
                success: function(res) {
                    hideLoading();
                    if (res && res.status === 'success') {
                        // Render daftar checkbox barang dinamis (mempertahankan pilihan yang dicentang)
                        if (res.barang_options && Array.isArray(res.barang_options)) {
                            const prevCheckedIds = [];
                            $('.chk-barang-item:checked').each(function() {
                                prevCheckedIds.push(String($(this).val()));
                            });

                            const $container = $('#container_barang_checkboxes');
                            $container.empty();

                            if (res.barang_options.length === 0) {
                                $container.html('<div class="p-2 text-center text-muted small">Tidak ada barang yang cocok</div>');
                            } else {
                                res.barang_options.forEach(function(item) {
                                    const bId = String(item.id_barang || '');
                                    const bNama = item.nama_barang || '';
                                    const bKode = item.kode_barang || '';
                                    const bStok = parseInt(item.stok || 0, 10);
                                    const isChecked = prevCheckedIds.includes(bId);

                                    const rowHtml = '<label class="dropdown-item px-2 py-1 mb-1 rounded d-flex align-items-center barang-item-row" data-search="' + $('<div>').text(bNama + ' ' + bKode).html() + '" style="cursor: pointer; font-size: 12px; font-weight: 500; color: #334155; margin-bottom: 2px;">' +
                                        '<input type="checkbox" class="chk-barang-item mr-2" value="' + bId + '" data-nama="' + $('<div>').text(bNama).html() + '" ' + (isChecked ? 'checked' : '') + ' style="cursor: pointer; width: 15px; height: 15px;"> ' +
                                        '<span class="text-truncate flex-grow-1 mr-1" title="' + $('<div>').text(bNama).html() + '">' + $('<div>').text(bNama).html() + '</span>' +
                                        '<span class="badge badge-light border" style="font-size: 10px; flex-shrink: 0;">' + formatNumber(bStok) + '</span>' +
                                        '</label>';
                                    $container.append(rowHtml);
                                });
                            }
                            updateBarangFilterLabel();
                        }

                        // 1. Update KPI
                        $('#kpi_total_stok').text(formatNumber(res.kpi.total_stok) + ' Unit');
                        $('#kpi_total_sku').text(formatNumber(res.kpi.total_sku) + ' Item');
                        $('#kpi_masuk_qty').text(formatNumber(res.kpi.masuk_qty) + ' Unit');
                        $('#kpi_masuk_trx').text(formatNumber(res.kpi.masuk_trx) + ' Trx Penerimaan');
                        $('#kpi_keluar_qty').text(formatNumber(res.kpi.keluar_qty) + ' Unit');
                        $('#kpi_keluar_trx').text(formatNumber(res.kpi.keluar_trx) + ' Trx Pengiriman');

                        // 2. Render Kategori Chart (atau Rincian Barang Kategori saat kategori dipilih)
                        renderKategoriChart(res.kategori_stok, res.top_barang, parseInt(idKategori, 10));

                        // 3. Render Gudang Chart
                        renderGudangChart(res.gudang_stok, idGudang);

                        // 4. Render Movement Chart
                        cachedMovementData = res.movement || [];
                        renderMovementChart(cachedMovementData);

                        // 5. Render Top Barang Table
                        renderTopBarang(res.top_barang);
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error('Error fetching dashboard stats:', error);
                }
            });
        }

        // Render Kategori Chart (Horizontal Bar) - mendukung drill-down per Barang saat Kategori dipilih
        function renderKategoriChart(kategoriData, topBarangData, selectedKategoriId) {
            const canvas = document.getElementById('chartStokKategori');
            if (!canvas) return;

            if (chartKategoriInstance) {
                chartKategoriInstance.destroy();
            }

            // Mode Drill-Down: Jika Kategori tertentu dipilih (misal Iatome / Uniray), tampilkan grafik stok per barang di kategori tsb
            if (selectedKategoriId && selectedKategoriId > 0) {
                const katName = ($('#dash_select_kategori option:selected').text() || 'Kategori Terpilih').trim();
                $('#title_chart_kategori').html(`<i class="fas fa-boxes text-primary"></i> Grafik Stok Kategori: <span class="text-primary">${katName}</span>`);
                $('#btn_back_all_kategori').show();
                $('#hint_chart_kategori').html('<i class="fas fa-mouse-pointer mr-1"></i> Klik bar barang untuk fokus ke item tersebut, atau klik <b>Semua Kategori</b> untuk kembali.');

                const items = (topBarangData || []).slice(0, 8);
                $('#badge_total_kategori').text(items.length + ' Item Teratas');

                if (items.length === 0) {
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.font = '13px Plus Jakarta Sans';
                    ctx.fillStyle = '#94a3b8';
                    ctx.textAlign = 'center';
                    ctx.fillText('Tidak ada stok barang ready pada kategori ' + katName, canvas.width / 2, canvas.height / 2);
                    return;
                }

                const labels    = items.map(it => (it.nama_barang || '').length > 26 ? it.nama_barang.substring(0, 24) + '...' : it.nama_barang);
                const fullNames = items.map(it => it.nama_barang || '');
                const dataVals  = items.map(it => parseInt(it.stok, 10));
                const satuans   = items.map(it => it.nama_satuan || 'Unit');

                const ctx = canvas.getContext('2d');
                chartKategoriInstance = new ChartLib(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Stok Ready',
                            data: dataVals,
                            backgroundColor: colorPalette.slice(0, items.length),
                            borderRadius: 6,
                            maxBarThickness: 18
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: function(e, elements) {
                            if (elements && elements.length > 0) {
                                const idx = elements[0].index;
                                const targetItemName = fullNames[idx] || '';
                                if (targetItemName) {
                                    const currentSearch = ($('#dash_search_nama').val() || '').trim();
                                    $('#dash_search_nama').val(currentSearch === targetItemName ? '' : targetItemName);
                                    loadDashboardData();
                                }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: function(tooltipItems) {
                                        return tooltipItems.length > 0 ? fullNames[tooltipItems[0].dataIndex] : '';
                                    },
                                    label: function(context) {
                                        const idx = context.dataIndex;
                                        return ` Stok Ready: ${formatNumber(context.raw)} ${satuans[idx]}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9' },
                                ticks: {
                                    font: { size: 11 },
                                    callback: function(val) { return formatNumber(val); }
                                }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { font: { size: 11, weight: '600' } }
                            }
                        }
                    }
                });
                return;
            }

            // Mode Default: Semua Kategori
            $('#title_chart_kategori').html('<i class="fas fa-tags text-primary"></i> Total Stok Berdasarkan Kategori');
            $('#btn_back_all_kategori').hide();
            $('#hint_chart_kategori').html('<i class="fas fa-mouse-pointer mr-1"></i> Klik pada bar kategori (misal Uniray / Iatome) untuk fokus ke grafik kategori tersebut.');

            if (!kategoriData || kategoriData.length === 0) {
                $('#badge_total_kategori').text('0 Kategori');
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.font = '13px Plus Jakarta Sans';
                ctx.fillStyle = '#94a3b8';
                ctx.textAlign = 'center';
                ctx.fillText('Tidak ada data stok kategori untuk filter ini', canvas.width / 2, canvas.height / 2);
                return;
            }

            $('#badge_total_kategori').text(kategoriData.length + ' Kategori');

            // Ambil Top 8 kategori untuk tampilan rapi
            const topKat = kategoriData.slice(0, 8);
            const labels = topKat.map(k => k.nama_kategori || 'Tanpa Kategori');
            const dataVals = topKat.map(k => parseInt(k.total_stok, 10));
            const skuVals  = topKat.map(k => parseInt(k.total_sku, 10));
            const katIds   = topKat.map(k => parseInt(k.id_kategori || 0, 10));

            const ctx = canvas.getContext('2d');
            chartKategoriInstance = new ChartLib(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Stok (Unit)',
                        data: dataVals,
                        backgroundColor: colorPalette.slice(0, topKat.length),
                        borderRadius: 6,
                        maxBarThickness: 18
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: function(e, elements) {
                        if (elements && elements.length > 0) {
                            const idx = elements[0].index;
                            const targetKatId = katIds[idx] || 0;
                            if (targetKatId > 0) {
                                // Fokuskan filter hanya ke kategori yang diklik
                                $('.chk-kategori-item').prop('checked', false);
                                $('.chk-kategori-item[value="' + targetKatId + '"]').prop('checked', true);
                                updateKategoriFilterLabel();
                                syncTableFilterKategoriFromDash();
                                loadDashboardData();
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const idx = context.dataIndex;
                                    const qty = context.raw;
                                    const sku = skuVals[idx] || 0;
                                    return ` Total Stok: ${formatNumber(qty)} unit (${sku} SKU ready)`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { size: 11 },
                                callback: function(val) { return formatNumber(val); }
                            }
                        },
                        y: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 11, weight: '600' }
                            }
                        }
                    }
                }
            });
        }

        // Render Gudang Chart (Vertical Bar)
        function renderGudangChart(gudangData, selectedGudangId) {
            const canvas = document.getElementById('chartStokGudang');
            if (!canvas) return;

            if (chartGudangInstance) {
                chartGudangInstance.destroy();
            }

            // Filter hanya gudang dengan stok > 0
            const activeGudang = (gudangData || []).filter(g => parseInt(g.total_stok, 10) > 0);
            $('#badge_total_gudang').text(activeGudang.length + ' Gudang Ada Stok');

            if (activeGudang.length === 0) {
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.font = '13px Plus Jakarta Sans';
                ctx.fillStyle = '#94a3b8';
                ctx.textAlign = 'center';
                ctx.fillText('Tidak ada data stok gudang aktif', canvas.width / 2, canvas.height / 2);
                return;
            }

            // Potong nama gudang yang terlalu panjang untuk label chart
            const labels = activeGudang.map(g => {
                let name = g.nama_gudang || '';
                return name.length > 20 ? name.substring(0, 18) + '...' : name;
            });
            const fullNames = activeGudang.map(g => g.nama_gudang);
            const dataVals  = activeGudang.map(g => parseInt(g.total_stok, 10));
            const skuVals   = activeGudang.map(g => parseInt(g.total_sku, 10));
            const gudangIds = activeGudang.map(g => g.id_gudang);

            // Warna bar: highlight gudang yang sedang terpilih
            const bgColors = activeGudang.map(g => {
                if (selectedGudangId && selectedGudangId == g.id_gudang) {
                    return '#2563eb'; // active blue
                }
                return '#10b981'; // emerald green
            });

            const ctx = canvas.getContext('2d');
            chartGudangInstance = new ChartLib(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Stok Fisik (Unit)',
                        data: dataVals,
                        backgroundColor: bgColors,
                        borderRadius: 6,
                        maxBarThickness: 26
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: function(e, elements) {
                        if (elements && elements.length > 0) {
                            const idx = elements[0].index;
                            const targetGudangId = gudangIds[idx];
                            // Fokuskan filter hanya ke gudang yang diklik
                            $('.chk-gudang-item').prop('checked', false);
                            $('.chk-gudang-item[value="' + targetGudangId + '"]').prop('checked', true);
                            updateGudangFilterLabel();
                            loadDashboardData();
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: function(items) {
                                    if (items.length > 0) {
                                        return fullNames[items[0].dataIndex];
                                    }
                                    return '';
                                },
                                label: function(context) {
                                    const idx = context.dataIndex;
                                    const qty = context.raw;
                                    const sku = skuVals[idx] || 0;
                                    return ` Stok: ${formatNumber(qty)} unit (${sku} SKU ready)`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { size: 11 },
                                callback: function(val) { return formatNumber(val); }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10.5 },
                                maxRotation: 45,
                                minRotation: 25
                            }
                        }
                    }
                }
            });
        }

        // Render Inbound vs Outbound Movement Chart
        function renderMovementChart(movementData) {
            const canvas = document.getElementById('chartStokMovement');
            if (!canvas) return;

            if (chartMovementInstance) {
                chartMovementInstance.destroy();
            }

            const isQty = currentMovementMode === 'qty';
            const months = (movementData || []).map(m => m.nama_bulan);
            const dataMasuk = (movementData || []).map(m => isQty ? parseInt(m.masuk_qty, 10) : parseInt(m.masuk_trx, 10));
            const dataKeluar = (movementData || []).map(m => isQty ? parseInt(m.keluar_qty, 10) : parseInt(m.keluar_trx, 10));

            // Summary totals
            const totalMasuk  = dataMasuk.reduce((a, b) => a + b, 0);
            const totalKeluar = dataKeluar.reduce((a, b) => a + b, 0);
            const netFlow     = totalMasuk - totalKeluar;

            const unitSuffix = isQty ? ' Unit' : ' Trx';
            $('#sum_inbound_mode').text(formatNumber(totalMasuk) + unitSuffix);
            $('#sum_outbound_mode').text(formatNumber(totalKeluar) + unitSuffix);
            $('#sum_net_mode').text((netFlow >= 0 ? '+' : '') + formatNumber(netFlow) + unitSuffix);

            const ctx = canvas.getContext('2d');
            chartMovementInstance = new ChartLib(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: isQty ? 'Stok Masuk (Unit)' : 'Penerimaan (Trx)',
                            data: dataMasuk,
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            borderColor: '#10b981',
                            borderWidth: 1.5,
                            borderRadius: 5,
                            maxBarThickness: 16
                        },
                        {
                            label: isQty ? 'Stok Keluar (Unit)' : 'Pengeluaran (Trx)',
                            data: dataKeluar,
                            backgroundColor: 'rgba(245, 158, 11, 0.85)',
                            borderColor: '#f59e0b',
                            borderWidth: 1.5,
                            borderRadius: 5,
                            maxBarThickness: 16
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                font: { size: 11.5, weight: '600' },
                                boxWidth: 12,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.dataset.label}: ${formatNumber(context.raw)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { size: 11 },
                                callback: function(val) { return formatNumber(val); }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        }

        // Render Top 10 Barang
        function renderTopBarang(topBarang) {
            const tbody = $('#tbody_top_barang');
            tbody.empty();

            if (!topBarang || topBarang.length === 0) {
                tbody.html('<tr><td colspan="3" class="text-center text-muted py-3">Tidak ada data stok barang ready</td></tr>');
                return;
            }

            topBarang.forEach((item, index) => {
                const rank = index + 1;
                let rankClass = 'badge-rank-other';
                if (rank === 1) rankClass = 'badge-rank-1';
                else if (rank === 2) rankClass = 'badge-rank-2';
                else if (rank === 3) rankClass = 'badge-rank-3';

                const tr = $('<tr style="cursor: pointer;" title="Klik untuk memfilter barang ini"></tr>');
                tr.on('click', function() {
                    const currentSearch = ($('#dash_search_nama').val() || '').trim();
                    $('#dash_search_nama').val(currentSearch === item.nama_barang ? '' : item.nama_barang);
                    loadDashboardData();
                });
                tr.append(`<td class="text-center"><span class="badge-rank ${rankClass}">${rank}</span></td>`);
                tr.append(`
                    <td>
                        <div style="font-weight: 600; color: #1e293b; line-height: 1.25;">${item.nama_barang}</div>
                        <small class="text-muted"><i class="fas fa-tag mr-1"></i>${item.nama_kategori || 'Umum'} ${item.kode_barang ? '• ' + item.kode_barang : ''}</small>
                    </td>
                `);
                tr.append(`
                    <td class="text-right">
                        <span class="badge badge-primary font-weight-bold" style="font-size: 12px; padding: 5px 8px; border-radius: 6px;">
                            ${formatNumber(item.stok)} ${item.nama_satuan || 'Unit'}
                        </span>
                    </td>
                `);
                tbody.append(tr);
            });
        }

        window.refreshStockDashboard = loadDashboardData;
        loadDashboardData();
    });
</script>
