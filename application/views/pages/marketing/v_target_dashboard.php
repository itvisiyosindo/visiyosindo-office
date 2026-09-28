<header class="page-header">
    <h2><i class="fas fa-chart-line"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    .premium-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        margin-bottom: 25px;
        transition: box-shadow 0.2s ease;
    }
    .premium-card:hover {
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
    }
    .stats-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
    }
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: #2563EB;
    }
    .stats-card.card-blue::before { background: #2563EB; }
    .stats-card.card-purple::before { background: #8B5CF6; }
    .stats-card.card-orange::before { background: #F97316; }
    .stats-card.card-green::before { background: #10B981; }
    .stats-card.card-teal::before { background: #0D9488; }
    .stats-card.card-red::before { background: #EF4444; }

    .stats-card.card-blue:hover { border-color: #2563EB; }
    .stats-card.card-purple:hover { border-color: #8B5CF6; }
    .stats-card.card-orange:hover { border-color: #F97316; }
    .stats-card.card-green:hover { border-color: #10B981; }
    .stats-card.card-teal:hover { border-color: #0D9488; }
    .stats-card.card-red:hover { border-color: #EF4444; }

    .stats-content {
        flex: 1;
    }
    .stats-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6B7280;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
    }
    .stats-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
    }
    .stats-icon-container {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
    }
    .card-blue .stats-icon-container { background: #EFF6FF; color: #2563EB; }
    .card-purple .stats-icon-container { background: #F5F3FF; color: #8B5CF6; }
    .card-orange .stats-icon-container { background: #FFF7ED; color: #F97316; }
    .card-green .stats-icon-container { background: #ECFDF5; color: #10B981; }
    .card-teal .stats-icon-container { background: #F0FDFA; color: #0D9488; }
    .card-red .stats-icon-container { background: #FEF2F2; color: #EF4444; }

    .card-blue:hover .stats-icon-container { background: #2563EB; color: #ffffff; }
    .card-purple:hover .stats-icon-container { background: #8B5CF6; color: #ffffff; }
    .card-orange:hover .stats-icon-container { background: #F97316; color: #ffffff; }
    .card-green:hover .stats-icon-container { background: #10B981; color: #ffffff; }
    .card-teal:hover .stats-icon-container { background: #0D9488; color: #ffffff; }
    .card-red:hover .stats-icon-container { background: #EF4444; color: #ffffff; }

    .stats-icon-container i {
        font-size: 1.3rem;
    }
    .form-control-premium {
        border-radius: 8px;
        border: 1px solid #D1D5DB;
        padding: 8px 12px;
    }
    .custom-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .custom-table th {
        background-color: #F3F4F6;
        color: #374151;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        border-bottom: 2px solid #E5E7EB;
        padding: 14px 12px;
    }
    .custom-table td {
        padding: 14px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #E5E7EB;
        color: #1F2937;
    }
</style>

<!-- High-Level Aggregate Metrics Card -->
<div class="row">
    <?php
    $total_hot_funnels = 0;
    $total_hot_pipeline = 0;
    $total_monthly_target = 0;
    $total_potensi_closing = 0;
    $total_achieved_funnels = 0;
    $total_realisasi_achievement = 0;

    foreach ($rekap as $r) {
        $total_hot_funnels += $r['jumlah_hot'];
        $total_hot_pipeline += $r['nilai_hot_pipeline'];
        $total_monthly_target += $r['target_bulanan'];
        $total_potensi_closing += $r['potensi_closing'];
        $total_achieved_funnels += $r['jumlah_achieved'];
        $total_realisasi_achievement += $r['nilai_achievement'];
    }

    $avg_achievement = 0;
    if ($total_monthly_target > 0) {
        $avg_achievement = ($total_potensi_closing / $total_monthly_target) * 100;
    }

    $avg_realisasi = 0;
    if ($total_monthly_target > 0) {
        $avg_realisasi = ($total_realisasi_achievement / $total_monthly_target) * 100;
    }
    ?>

    <!-- ROW 1 OF METRICS -->
    <div class="col-md-3">
        <div class="stats-card card-blue">
            <div class="stats-content">
                <div class="stats-label">Total Target Bulanan</div>
                <div class="stats-value">Rp <?= rupiah($total_monthly_target) ?: '0' ?></div>
            </div>
            <div class="stats-icon-container">
                <i class="fas fa-bullseye"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card-purple">
            <div class="stats-content">
                <div class="stats-label">Total Funnel Hot</div>
                <div class="stats-value"><?= $total_hot_funnels ?> Funnel</div>
            </div>
            <div class="stats-icon-container">
                <i class="fas fa-fire"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card-orange">
            <div class="stats-content">
                <div class="stats-label">Nilai Hot Pipeline</div>
                <div class="stats-value">Rp <?= rupiah($total_hot_pipeline) ?: '0' ?></div>
            </div>
            <div class="stats-icon-container">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card-red">
            <div class="stats-content">
                <div class="stats-label">Total Potensi Closing</div>
                <div class="stats-value">Rp <?= rupiah($total_potensi_closing) ?: '0' ?></div>
            </div>
            <div class="stats-icon-container">
                <i class="fas fa-chart-pie"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- ROW 2 OF METRICS -->
    <div class="col-md-3">
        <div class="stats-card card-teal">
            <div class="stats-content">
                <div class="stats-label">Total Funnel Closing</div>
                <div class="stats-value"><?= $total_achieved_funnels ?> Funnel</div>
            </div>
            <div class="stats-icon-container">
                <i class="fas fa-check-double"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card-green">
            <div class="stats-content">
                <div class="stats-label">Realisasi Penjualan</div>
                <div class="stats-value">Rp <?= rupiah($total_realisasi_achievement) ?: '0' ?></div>
            </div>
            <div class="stats-icon-container">
                <i class="fas fa-trophy"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card-orange">
            <div class="stats-content">
                <div class="stats-label">Rata-rata Forecast (%)</div>
                <div class="stats-value"><?= round($avg_achievement, 1) ?>%</div>
            </div>
            <div class="stats-icon-container">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card-green">
            <div class="stats-content">
                <div class="stats-label">Rata-rata Realisasi (%)</div>
                <div class="stats-value"><?= round($avg_realisasi, 1) ?>%</div>
            </div>
            <div class="stats-icon-container">
                <i class="fas fa-award"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- Date & Marketing Selector Card -->
        <div class="card premium-card">
            <div class="card-body">
                <?= form_open('marketing_target/dashboard', ['method' => 'GET', 'class' => 'row align-items-center']); ?>
                <div class="<?= $is_admin_or_leader ? 'col-md-3' : 'col-md-5' ?> mb-2">
                    <label class="font-weight-bold text-muted small text-uppercase">Pilih Tahun</label>
                    <select name="tahun" id="tahun" class="form-control form-control-premium" onchange="this.form.submit()">
                        <?php
                        $current_year = date('Y');
                        for ($y = $current_year - 5; $y <= $current_year + 5; $y++) {
                            $selected = ($y == $tahun) ? 'selected' : '';
                            echo "<option value='{$y}' {$selected}>{$y}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="<?= $is_admin_or_leader ? 'col-md-3' : 'col-md-5' ?> mb-2">
                    <label class="font-weight-bold text-muted small text-uppercase">Pilih Bulan</label>
                    <select name="bulan" id="bulan" class="form-control form-control-premium" onchange="this.form.submit()">
                        <?php
                        $months = [
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ];
                        foreach ($months as $key => $val) {
                            $selected = ($key == $bulan) ? 'selected' : '';
                            echo "<option value='{$key}' {$selected}>{$val}</option>";
                        }
                        ?>
                    </select>
                </div>
                <?php if ($is_admin_or_leader): ?>
                <div class="col-md-4 mb-2">
                    <label class="font-weight-bold text-muted small text-uppercase">Pilih Marketing</label>
                    <select name="marketing_id" id="marketing_id" class="form-control form-control-premium" onchange="this.form.submit()">
                        <option value="all" <?= !$marketing_id ? 'selected' : '' ?>>-- Semua Marketing --</option>
                        <?php foreach ($marketing_list as $m): ?>
                            <option value="<?= $m->pengguna_id ?>" <?= $marketing_id == $m->pengguna_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m->nama) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-md-2 mb-2 text-right pt-4">
                    <button type="submit" class="btn btn-premium btn-premium-primary btn-block"><i class="fas fa-filter"></i> Terapkan</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>

        <!-- Rekap Table Card -->
        <div class="card premium-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table custom-table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 20%">Nama Marketing</th>
                                <th style="width: 10%" class="text-center">Funnel Hot</th>
                                <th style="width: 12%" class="text-right">Hot Pipeline</th>
                                <th style="width: 12%" class="text-right">Target Bulanan</th>
                                <th style="width: 12%" class="text-right">Potensi Closing</th>
                                <th style="width: 10%" class="text-center">Forecast (%)</th>
                                <th style="width: 10%" class="text-center">Funnel Achieve</th>
                                <th style="width: 12%" class="text-right">Realisasi Penjualan</th>
                                <th style="width: 10%" class="text-center">Realisasi (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rekap)): ?>
                                <?php $no = 1; foreach ($rekap as $r): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td class="font-weight-bold"><?= htmlspecialchars($r['nama_marketing']) ?></td>
                                        <td class="text-center">
                                            <span class="badge font-weight-bold" style="background-color: #3B82F6; color: white; padding: 6px 10px; border-radius: 6px;">
                                                <?= $r['jumlah_hot'] ?> Funnel
                                            </span>
                                        </td>
                                        <td class="text-right font-weight-bold">Rp <?= rupiah($r['nilai_hot_pipeline']) ?: '0' ?></td>
                                        <td class="text-right">Rp <?= rupiah($r['target_bulanan']) ?: '0' ?></td>
                                        <td class="text-right text-primary font-weight-bold">Rp <?= rupiah($r['potensi_closing']) ?: '0' ?></td>
                                        <td class="text-center">
                                            <?php
                                            $ach = $r['achievement'];
                                            $badge_style = 'background-color: #EF4444; color: white;'; // Red default
                                            if ($ach >= 100) {
                                                $badge_style = 'background-color: #10B981; color: white;'; // Green
                                            } elseif ($ach >= 50) {
                                                $badge_style = 'background-color: #F59E0B; color: black;'; // Yellow/Orange
                                            } elseif ($ach > 0) {
                                                $badge_style = 'background-color: #3B82F6; color: white;'; // Blue
                                            }
                                            ?>
                                            <span class="badge font-weight-bold" style="<?= $badge_style ?> padding: 6px 12px; border-radius: 6px; font-size: 0.9rem;">
                                                <?= $ach ?>%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge font-weight-bold" style="background-color: #0D9488; color: white; padding: 6px 10px; border-radius: 6px;">
                                                <?= $r['jumlah_achieved'] ?> Funnel
                                            </span>
                                        </td>
                                        <td class="text-right text-success font-weight-bold">Rp <?= rupiah($r['nilai_achievement']) ?: '0' ?></td>
                                        <td class="text-center">
                                            <?php
                                            $real_pct = $r['realisasi_pct'];
                                            $badge_style_real = 'background-color: #EF4444; color: white;'; // Red default
                                            if ($real_pct >= 100) {
                                                $badge_style_real = 'background-color: #10B981; color: white;'; // Green
                                            } elseif ($real_pct >= 50) {
                                                $badge_style_real = 'background-color: #F59E0B; color: black;'; // Yellow/Orange
                                            } elseif ($real_pct > 0) {
                                                $badge_style_real = 'background-color: #3B82F6; color: white;'; // Blue
                                            }
                                            ?>
                                            <span class="badge font-weight-bold" style="<?= $badge_style_real ?> padding: 6px 12px; border-radius: 6px; font-size: 0.9rem;">
                                                <?= $real_pct ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">Tidak ada data rekapitulasi target penjualan untuk periode ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Charts Section -->
<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card premium-card h-100">
            <div class="card-body">
                <h4 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-chart-bar"></i> 
                    <?php if ($marketing_id): ?>
                        Perbandingan Target, Potensi vs Realisasi Penjualan Bulanan (Tahun <?= $tahun ?>)
                    <?php else: ?>
                        Perbandingan Target, Potensi vs Realisasi Penjualan Marketing (Bulan Ini)
                    <?php endif; ?>
                </h4>
                <div style="height: 320px; position: relative;">
                    <canvas id="target_comparison_chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card premium-card h-100">
            <div class="card-body">
                <h4 class="font-weight-bold text-dark mb-3"><i class="fas fa-chart-pie"></i> Status Funnel Prospek (Tahun <?= $tahun ?>)</h4>
                <div style="height: 320px; position: relative;">
                    <canvas id="funnel_status_chart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card premium-card">
            <div class="card-body">
                <h4 class="font-weight-bold text-dark mb-3"><i class="fas fa-chart-line"></i> Tren Potensi vs Realisasi Penjualan Bulanan (Tahun <?= $tahun ?>)</h4>
                <div style="height: 320px; position: relative;">
                    <canvas id="forecast_trend_chart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rekapData = <?= json_encode($rekap) ?>;
        const allTargetsYear = <?= json_encode($all_targets_year ?: []) ?>;
        const activeTahun = '<?= $tahun ?>';
        const activeMarketingId = <?= json_encode($marketing_id) ?>;
        const monthlyTargets = <?= json_encode($monthly_targets) ?>;

        // === 1. Target vs Potensi vs Realisasi Chart (Bar) ===
        const labels = [];
        const targetNominals = [];
        const potensiClosing = [];
        const realisasiAchievement = [];

        if (activeMarketingId) {
            // Chart shows target vs potensi closing bulanan (12 months) for the selected marketing
            const monthNames = [
                "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", 
                "Jul", "Ags", "Sep", "Okt", "Nov", "Des"
            ];
            
            // Calculate potensi closing monthly (1 to 12)
            const monthlyForecast = Array(12).fill(0);
            const monthlyRealAchievement = Array(12).fill(0);
            
            allTargetsYear.forEach(function(item) {
                if (item.is_achieved === "1" || item.is_achieved === 1) {
                    if (item.tgl_achievement) {
                        let achDate = new Date(item.tgl_achievement);
                        let monthIdx = achDate.getMonth();
                        monthlyRealAchievement[monthIdx] += parseFloat(item.nilai_achievement);
                    }
                } else {
                    if (item.estimasi_closing) {
                        let closingDate = new Date(item.estimasi_closing);
                        let monthIdx = closingDate.getMonth();
                        let forecastVal = (parseFloat(item.harga_jual) * parseInt(item.persentase_kecapaian)) / 100;
                        monthlyForecast[monthIdx] += forecastVal;
                    }
                }
            });

            for (let b = 1; b <= 12; b++) {
                labels.push(monthNames[b - 1]);
                targetNominals.push(monthlyTargets[b] || 0);
                potensiClosing.push(monthlyForecast[b - 1]);
                realisasiAchievement.push(monthlyRealAchievement[b - 1]);
            }
        } else {
            // Chart shows all marketing comparison for the selected month
            rekapData.forEach(function(item) {
                labels.push(item.nama_marketing);
                targetNominals.push(item.target_bulanan);
                potensiClosing.push(item.potensi_closing);
                realisasiAchievement.push(item.nilai_achievement);
            });
        }

        const ctx1 = document.getElementById('target_comparison_chart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Target Bulanan (Rp)',
                        data: targetNominals,
                        backgroundColor: '#3B82F6', // Blue
                        borderColor: '#2563EB',
                        borderWidth: 1,
                        borderRadius: 6
                    },
                    {
                        label: 'Potensi Closing (Forecast) (Rp)',
                        data: potensiClosing,
                        backgroundColor: '#F59E0B', // Yellow/Orange
                        borderColor: '#D97706',
                        borderWidth: 1,
                        borderRadius: 6
                    },
                    {
                        label: 'Realisasi Penjualan (Achievement) (Rp)',
                        data: realisasiAchievement,
                        backgroundColor: '#10B981', // Green
                        borderColor: '#059669',
                        borderWidth: 1,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            min: 0,
                            suggestedMax: 10000000,
                            callback: function(value) {
                                if (value >= 1e9) {
                                    return 'Rp ' + (value / 1e9).toFixed(1) + 'M';
                                } else if (value >= 1e6) {
                                    return 'Rp ' + (value / 1e6).toFixed(0) + 'Jt';
                                }
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let label = data.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) {
                                label += ': ';
                            }
                            let rawValue = tooltipItem.yLabel;
                            if (rawValue !== null && rawValue !== undefined) {
                                label += 'Rp ' + rawValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                            return label;
                        }
                    }
                }
            }
        });

        // === 2. Funnel Status Distribution Chart (Doughnut) ===
        let coldCount = 0;
        let warmCount = 0;
        let hotCount = 0;

        allTargetsYear.forEach(function(item) {
            if (item.status_funnel === 'Hot') {
                hotCount++;
            } else if (item.status_funnel === 'Warm') {
                warmCount++;
            } else {
                coldCount++;
            }
        });

        const ctx2 = document.getElementById('funnel_status_chart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Cold (0-39%)', 'Warm (40-74%)', 'Hot (75-100%)'],
                datasets: [{
                    data: [coldCount, warmCount, hotCount],
                    backgroundColor: ['#EF4444', '#F59E0B', '#10B981'],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom'
                }
            }
        });

        // === 3. Monthly Forecast & Achievement Trend Chart (Line) ===
        const monthlyForecast = Array(12).fill(0);
        const monthlyRealAchievement = Array(12).fill(0);
        const monthNames = [
            "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", 
            "Jul", "Ags", "Sep", "Okt", "Nov", "Des"
        ];

        allTargetsYear.forEach(function(item) {
            if (item.is_achieved === "1" || item.is_achieved === 1) {
                if (item.tgl_achievement) {
                    let achDate = new Date(item.tgl_achievement);
                    let monthIdx = achDate.getMonth();
                    monthlyRealAchievement[monthIdx] += parseFloat(item.nilai_achievement);
                }
            } else {
                if (item.estimasi_closing) {
                    let closingDate = new Date(item.estimasi_closing);
                    let monthIdx = closingDate.getMonth();
                    let forecastVal = (parseFloat(item.harga_jual) * parseInt(item.persentase_kecapaian)) / 100;
                    monthlyForecast[monthIdx] += forecastVal;
                }
            }
        });

        const ctx3 = document.getElementById('forecast_trend_chart').getContext('2d');
        new Chart(ctx3, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [
                    {
                        label: 'Potensi Closing (Forecast) (Rp)',
                        data: monthlyForecast,
                        fill: true,
                        backgroundColor: 'rgba(245, 158, 11, 0.05)',
                        borderColor: '#F59E0B',
                        borderWidth: 3,
                        tension: 0.3,
                        pointBackgroundColor: '#F59E0B',
                        pointRadius: 4
                    },
                    {
                        label: 'Realisasi Penjualan (Achievement) (Rp)',
                        data: monthlyRealAchievement,
                        fill: true,
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        borderColor: '#10B981',
                        borderWidth: 3,
                        tension: 0.3,
                        pointBackgroundColor: '#10B981',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'top'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            min: 0,
                            suggestedMax: 10000000,
                            callback: function(value) {
                                if (value >= 1e9) {
                                    return 'Rp ' + (value / 1e9).toFixed(1) + 'M';
                                } else if (value >= 1e6) {
                                    return 'Rp ' + (value / 1e6).toFixed(0) + 'Jt';
                                }
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let label = data.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) {
                                label += ': ';
                            }
                            let rawValue = tooltipItem.yLabel;
                            if (rawValue !== null && rawValue !== undefined) {
                                label += 'Rp ' + rawValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                            return label;
                        }
                    }
                }
            }
        });
    });
</script>
