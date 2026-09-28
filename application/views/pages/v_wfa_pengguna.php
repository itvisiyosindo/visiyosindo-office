<style>
    /* Professional WFA Management Styling */
    :root {
        --primary: #0d6efd;
        --info: #17a2b8;
        --success: #28a745;
        --warning: #ffc107;
        --danger: #dc3545;
        --light: #f8f9fa;
        --dark: #343a40;
        --border-radius: 8px;
        --transition: all 0.3s ease;
    }

    .wfa-page-wrapper {
        background-color: #f5f7fa;
        padding: 2rem 0;
    }

    .page-header-custom {
        background: linear-gradient(135deg, var(--primary) 0%, #0a58ca 100%);
        color: white;
        padding: 2.5rem;
        border-radius: var(--border-radius);
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .page-header-custom h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-header-custom p {
        margin: 0.75rem 0 0 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.75rem;
        border-radius: var(--border-radius);
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border-left: 5px solid var(--primary);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        transform: translateY(-3px);
    }

    .stat-card.active {
        border-left-color: var(--success);
    }

    .stat-card.active .stat-icon {
        color: var(--success);
    }

    .stat-icon {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 0.75rem;
        opacity: 0.9;
    }

    .stat-number {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0.5rem 0;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 500;
    }

    .section-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .section-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.75rem;
        border-bottom: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .section-header h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-header .badge {
        font-size: 0.8rem;
    }

    .alert-custom {
        padding: 1.5rem;
        border-radius: var(--border-radius);
        border: none;
        border-left: 5px solid;
        margin-bottom: 0;
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    .alert-custom i {
        font-size: 1.75rem;
        flex-shrink: 0;
        margin-top: 0.2rem;
    }

    .alert-custom-info {
        background-color: #cfe2ff;
        color: #084298;
        border-color: var(--info);
    }

    .alert-custom-warning {
        background-color: #fff3cd;
        color: #664d03;
        border-color: var(--warning);
    }

    .alert-custom-success {
        background-color: #d1e7dd;
        color: #0f5132;
        border-color: var(--success);
    }

    .alert-custom strong {
        display: block;
        margin-bottom: 0.25rem;
    }

    .alert-custom a {
        font-weight: 600;
        text-decoration: underline;
    }

    .table-container {
        padding: 0;
    }

    .table-header-custom {
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .table-header-custom .search-box {
        flex: 1;
        min-width: 200px;
        max-width: 400px;
    }

    .table-header-custom .search-box input {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .table-header-custom .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .table-custom thead tr {
        background-color: #f8f9fa;
        border-top: 2px solid #dee2e6;
        border-bottom: 2px solid #dee2e6;
    }

    .table-custom thead th {
        padding: 1.25rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--dark);
        text-transform: uppercase;
        letter-spacing: 0.7px;
    }

    .table-custom tbody tr {
        border-bottom: 1px solid #dee2e6;
        transition: var(--transition);
    }

    .table-custom tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table-custom tbody tr:last-child {
        border-bottom: none;
    }

    .table-custom tbody td {
        padding: 1.25rem;
        color: #495057;
        vertical-align: middle;
    }

    .employee-info {
        display: flex;
        flex-direction: column;
    }

    .employee-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 1rem;
        margin: 0;
    }

    .employee-position {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.3rem;
    }

    .badge-custom {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .badge-active {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .badge-inactive {
        background-color: #f8d7da;
        color: #842029;
    }

    /* Modern Toggle Switch */
    .form-switch-custom {
        display: inline-block;
        position: relative;
        width: 54px;
        height: 28px;
    }

    .form-switch-custom input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: var(--transition);
        border-radius: 28px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: var(--transition);
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .form-switch-custom input:checked + .toggle-slider {
        background-color: var(--success);
    }

    .form-switch-custom input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    .form-switch-custom input:disabled + .toggle-slider {
        background-color: #e9ecef;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .form-switch-custom input:disabled + .toggle-slider:before {
        cursor: not-allowed;
    }

    .no-data {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }

    .no-data i {
        font-size: 3.5rem;
        opacity: 0.2;
        display: block;
        margin-bottom: 1rem;
    }

    .no-data p {
        font-size: 1rem;
        margin: 0;
    }

    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: 1fr;
        }

        .page-header-custom {
            padding: 1.75rem;
        }

        .page-header-custom h1 {
            font-size: 1.5rem;
        }

        .table-header-custom {
            flex-direction: column;
            align-items: stretch;
        }

        .table-header-custom .search-box {
            max-width: 100%;
        }

        .table-custom {
            font-size: 0.9rem;
        }

        .table-custom td, .table-custom th {
            padding: 0.9rem 0.5rem;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .section-header h3 {
            font-size: 1.1rem;
        }
    }
</style>

<div class="wfa-page-wrapper">
    <div class="container-fluid" style="max-width: 1200px;">
        
        <!-- Page Header -->
        <div class="page-header-custom">
            <h1>
                <i class="bx bx-briefcase"></i>
                Manajemen Status WFA Karyawan
            </h1>
            <p class="text-white">Kelola akses Work From Anywhere untuk setiap karyawan operasional</p>
        </div>

        <!-- Statistics Section -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bx bx-user-check"></i>
                </div>
                <div class="stat-number" id="totalKaryawan">0</div>
                <div class="stat-label">Total Karyawan</div>
            </div>
            
            <div class="stat-card active">
                <div class="stat-icon">
                    <i class="bx bx-wifi"></i>
                </div>
                <div class="stat-number" id="wfaAktif">0</div>
                <div class="stat-label">WFA Aktif</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bx bx-building-house"></i>
                </div>
                <div class="stat-number" id="wfaInaktif">0</div>
                <div class="stat-label">WFA Nonaktif</div>
            </div>
        </div>

        <!-- Status Alert -->
        <div class="section-card">
            <div class="section-header">
                <h3>
                    <i class="bx bx-info-circle"></i>
                    Status Sistem
                </h3>
            </div>
            <div style="padding: 1.75rem;">
                <?php if (!isset($wfa_system_active) || !$wfa_system_active): ?>
                    <div class="alert alert-custom alert-custom-warning">
                        <i class="bx bx-alert-circle"></i>
                        <div>
                            <strong>Fitur WFA Belum Diaktifkan</strong>
                            <p style="margin: 0.25rem 0 0 0;">
                                Silakan aktivkan WFA terlebih dahulu di 
                                <a href="<?php echo base_url('absensi_config'); ?>">Konfigurasi Absensi</a>
                                untuk menggunakan fitur manajemen ini.
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-custom alert-custom-success">
                        <i class="bx bx-check-circle"></i>
                        <div>
                            <strong>Fitur WFA Aktif di Sistem</strong>
                            <p style="margin: 0.25rem 0 0 0;">
                                Gunakan toggle di bawah untuk mengaktifkan akses WFA per karyawan.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Employee Management Section -->
        <div class="section-card">
            <div class="section-header">
                <h3>
                    <i class="bx bx-list-check"></i>
                    Daftar Karyawan <span class="badge bg-primary" id="employeeCount">0</span>
                </h3>
            </div>
            
            <div class="table-header-custom">
                <div class="search-box">
                    <input type="text" id="searchEmployee" placeholder="🔍 Cari nama atau jabatan...">
                </div>
            </div>

            <div class="table-container">
                <?php if (count($list_pengguna) > 0): ?>
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 40%">Nama Karyawan</th>
                                <th style="width: 30%">Jabatan</th>
                                <th style="width: 15%">Status WFA</th>
                                <th style="width: 10%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTableBody">
                            <?php $no = 1; foreach ($list_pengguna as $pengguna): ?>
                                <tr class="employee-row" data-search="<?php echo strtolower($pengguna->nama . ' ' . ($pengguna->jabatan ?? '')); ?>">
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <div class="employee-info">
                                            <p class="employee-name"><?php echo $pengguna->nama; ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="employee-position"><?php echo $pengguna->jabatan ?? '-'; ?></div>
                                    </td>
                                    <td>
                                        <?php if ($pengguna->is_wfa == 1): ?>
                                            <span class="badge-custom badge-active">
                                                <i class="bx bx-check-circle"></i> Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-custom badge-inactive">
                                                <i class="bx bx-x-circle"></i> Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <label class="form-switch-custom" title="Toggle WFA <?php echo $pengguna->nama; ?>">
                                            <input type="checkbox" 
                                                   class="pengguna-wfa-toggle" 
                                                   data-pengguna-id="<?php echo encrypt($pengguna->pengguna_id); ?>"
                                                   data-nama="<?php echo $pengguna->nama; ?>"
                                                   <?php echo $pengguna->is_wfa == 1 ? 'checked' : ''; ?>
                                                   <?php echo (!isset($wfa_system_active) || !$wfa_system_active) ? 'disabled' : ''; ?>>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        <i class="bx bx-inbox"></i>
                        <p>Tidak ada karyawan untuk dikonfigurasi</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript untuk Handle Toggle WFA & Search -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    updateStatistics();
    setupSearch();
    setupToggleListeners();
});

function updateStatistics() {
    const totalRows = document.querySelectorAll('.employee-row').length;
    const activeCount = document.querySelectorAll('.pengguna-wfa-toggle:checked').length;
    const inactiveCount = totalRows - activeCount;
    
    document.getElementById('totalKaryawan').textContent = totalRows;
    document.getElementById('wfaAktif').textContent = activeCount;
    document.getElementById('wfaInaktif').textContent = inactiveCount;
    document.getElementById('employeeCount').textContent = totalRows;
}

function setupSearch() {
    const searchInput = document.getElementById('searchEmployee');
    if (!searchInput) return;
    
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.employee-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    });
}

function setupToggleListeners() {
    document.querySelectorAll('.pengguna-wfa-toggle').forEach(function(el) {
        el.addEventListener('change', function() {
            const toggleEl = this;
            const penggunaId = this.getAttribute('data-pengguna-id');
            const nama = this.getAttribute('data-nama');
            const isWfa = this.checked ? 'true' : 'false';

            function updateRowBadge(isActive) {
                const row = toggleEl.closest('tr');
                if (!row) {
                    return;
                }

                const badgeCell = row.querySelector('td:nth-child(4)');
                if (!badgeCell) {
                    return;
                }

                if (isActive) {
                    badgeCell.innerHTML = '<span class="badge-custom badge-active"><i class="bx bx-check-circle"></i> Aktif</span>';
                } else {
                    badgeCell.innerHTML = '<span class="badge-custom badge-inactive"><i class="bx bx-x-circle"></i> Nonaktif</span>';
                }
            }
            
            $.ajax({
                url: '<?php echo base_url("absensi_config/update_pengguna_wfa"); ?>',
                type: 'POST',
                data: {
                    pengguna_id: penggunaId,
                    is_wfa: isWfa,
                    csrf_token: token
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const status = isWfa === 'true' ? 'diaktifkan' : 'dinonaktifkan';
                        updateRowBadge(isWfa === 'true');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Status WFA ' + nama + ' ' + status,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        updateStatistics();
                    } else {
                        toggleEl.checked = !toggleEl.checked;
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    toggleEl.checked = !toggleEl.checked;
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Terjadi kesalahan saat mengupdate', 'error');
                }
            });
        });
    });
}
</script>
