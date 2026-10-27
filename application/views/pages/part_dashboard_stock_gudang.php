<!-- Visual Dashboard Stok Gudang & Pergerakan In/Out -->
<style>
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
            <!-- Pilihan Gudang Penyimpanan -->
            <div class="d-flex align-items-center" style="gap: 5px;">
                <label for="dash_select_gudang" class="mb-0" style="font-size: 12px; font-weight: 600; color: #475569; white-space: nowrap;">
                    <i class="fas fa-warehouse text-primary mr-1"></i> Pilihan Gudang:
                </label>
                <select id="dash_select_gudang" class="form-control dash-filter-select" style="min-width: 220px;">
                    <option value="0">-- Semua Gudang Penyimpanan --</option>
                    <?php if (!empty($gudang)) {
                        foreach ($gudang as $rowG) {
                            $gid = decrypt($rowG->id_gudang);
                            echo '<option value="' . $gid . '">' . $rowG->nama_gudang . '</option>';
                        }
                    } ?>
                </select>
            </div>

            <!-- Filter Tahun -->
            <div class="d-flex align-items-center" style="gap: 5px;">
                <label for="dash_select_tahun" class="mb-0" style="font-size: 12px; font-weight: 600; color: #475569;">
                    <i class="fas fa-calendar-alt text-success mr-1"></i> Tahun:
                </label>
                <select id="dash_select_tahun" class="form-control dash-filter-select" style="width: 90px;">
                    <?php
                    $curYear = (int)date('Y');
                    for ($y = $curYear; $y >= $curYear - 4; $y--) {
                        echo '<option value="' . $y . '" ' . ($y == $curYear ? 'selected' : '') . '>' . $y . '</option>';
                    }
                    ?>
                </select>
            </div>

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
                        <h5 class="stock-chart-title">
                            <i class="fas fa-tags text-primary"></i> Total Stok Berdasarkan Kategori
                        </h5>
                        <span class="badge badge-light border text-primary" id="badge_total_kategori" style="font-size: 11px;">0 Kategori</span>
                    </div>
                    <div style="position: relative; height: 280px; width: 100%;">
                        <canvas id="chartStokKategori"></canvas>
                    </div>
                    <div class="mt-2 text-center text-muted" style="font-size: 11px;">
                        <i class="fas fa-info-circle mr-1"></i> Menampilkan kategori dengan stok barang fisik ready.
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

        // Change Filter Gudang / Tahun
        $('#dash_select_gudang, #dash_select_tahun').on('change', function() {
            loadDashboardData();
        });

        function loadDashboardData() {
            const idGudang = $('#dash_select_gudang').val() || 0;
            const tahun    = $('#dash_select_tahun').val() || new Date().getFullYear();

            $('.dash-span-tahun').text(tahun);
            showLoading();

            const namaGudangSelected = $('#dash_select_gudang option:selected').text();
            if (idGudang == 0) {
                $('#kpi_gudang_label').text('Semua Gudang Penyimpanan');
                $('#badge_top_barang_context').text('Semua Gudang');
            } else {
                $('#kpi_gudang_label').text(namaGudangSelected);
                $('#badge_top_barang_context').text(namaGudangSelected);
            }

            $.ajax({
                url: '<?= base_url("stock_gudang/get_dashboard_stats") ?>',
                type: 'GET',
                data: {
                    tahun: tahun,
                    id_gudang: idGudang
                },
                dataType: 'json',
                success: function(res) {
                    hideLoading();
                    if (res && res.status === 'success') {
                        // 1. Update KPI
                        $('#kpi_total_stok').text(formatNumber(res.kpi.total_stok) + ' Unit');
                        $('#kpi_total_sku').text(formatNumber(res.kpi.total_sku) + ' Item');
                        $('#kpi_masuk_qty').text(formatNumber(res.kpi.masuk_qty) + ' Unit');
                        $('#kpi_masuk_trx').text(formatNumber(res.kpi.masuk_trx) + ' Trx Penerimaan');
                        $('#kpi_keluar_qty').text(formatNumber(res.kpi.keluar_qty) + ' Unit');
                        $('#kpi_keluar_trx').text(formatNumber(res.kpi.keluar_trx) + ' Trx Pengiriman');

                        // 2. Render Kategori Chart
                        renderKategoriChart(res.kategori_stok);

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

        // Render Kategori Chart (Horizontal Bar)
        function renderKategoriChart(kategoriData) {
            const canvas = document.getElementById('chartStokKategori');
            if (!canvas) return;

            if (chartKategoriInstance) {
                chartKategoriInstance.destroy();
            }

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
                            $('#dash_select_gudang').val(targetGudangId).trigger('change');
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

                const tr = $('<tr></tr>');
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
