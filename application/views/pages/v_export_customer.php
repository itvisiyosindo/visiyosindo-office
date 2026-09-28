<style>
    .export-card {
        border-radius: 16px;
        overflow: hidden;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .export-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .export-header {
        padding: 24px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .export-header::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 60%);
        pointer-events: none;
    }

    .bg-customer {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    }

    .bg-pelanggan {
        background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
    }

    .bg-calon {
        background: linear-gradient(135deg, #7c2d12 0%, #f97316 100%);
    }

    .export-body {
        padding: 24px;
    }

    .db-count {
        font-size: 2.25rem;
        font-weight: 800;
        line-height: 1;
        color: #111827;
        margin-bottom: 4px;
    }

    .badge-premium {
        padding: 6px 12px;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .badge-customer {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .badge-pelanggan {
        background-color: #ccfbf1;
        color: #0f766e;
    }

    .badge-calon {
        background-color: #ffedd5;
        color: #9a3412;
    }

    .btn-export {
        border-radius: 12px;
        font-weight: 700;
        letter-spacing: 0.025em;
        padding: 12px 24px;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-customer {
        background-color: #2563eb;
        color: #ffffff;
    }

    .btn-customer:hover {
        background-color: #1d4ed8;
        color: #ffffff;
        transform: scale(1.02);
    }

    .btn-pelanggan {
        background-color: #0d9488;
        color: #ffffff;
    }

    .btn-pelanggan:hover {
        background-color: #0f766e;
        color: #ffffff;
        transform: scale(1.02);
    }

    .btn-calon {
        background-color: #ea580c;
        color: #ffffff;
    }

    .btn-calon:hover {
        background-color: #c2410c;
        color: #ffffff;
        transform: scale(1.02);
    }
</style>

<header class="page-header">
    <h2><i class="icons fas fa-file-excel"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="alert alert-info py-3 px-4 shadow-sm border-0 d-flex align-items-center" style="border-radius: 12px; background-color: #f0f9ff; border-left: 5px solid #0284c7 !important;">
            <i class="fas fa-info-circle fa-2x mr-3 text-info"></i>
            <div>
                <h5 class="alert-heading font-weight-bold text-info mb-1" style="font-size: 1rem;">Administrator Panel</h5>
                <p class="mb-0 text-muted small">Ekspor seluruh database pelanggan ke format Microsoft Excel (.xlsx) untuk mempermudah audit, normalisasi, dan pencadangan data offline.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- CARD 1: TABLE CUSTOMER -->
    <div class="col-lg-4 mb-4">
        <section class="card export-card h-100">
            <div class="export-header bg-customer">
                <h3 class="font-weight-bold m-0" style="font-size: 1.25rem;">
                    <i class="fas fa-warehouse mr-2"></i> Table Customer
                </h3>
                <small class="opacity-75">Sistem Inventaris & Logistik</small>
            </div>
            <div class="export-body d-flex flex-column justify-content-between h-100" style="min-height: 280px;">
                <div>
                    <div class="d-flex align-items-baseline mb-3">
                        <span class="db-count"><?= number_format($count_customer) ?></span>
                        <span class="text-muted ml-2 small">Baris Terdaftar</span>
                    </div>
                    <p class="text-muted small mb-4" style="line-height: 1.6;">
                        Tabel `customer` menyimpan data pelanggan yang terhubung langsung dengan modul <strong>Logistik & Inventaris</strong>. Data ini digunakan pada transaksi pengeluaran barang, serah terima barang, invoicing, surat jalan, dan tagihan.
                    </p>
                    <div class="mb-4">
                        <span class="badge-premium badge-customer"><i class="fas fa-link mr-1"></i> Modul Utama</span>
                        <ul class="pl-3 mt-2 text-muted small" style="list-style-type: square;">
                            <li>Surat Jalan & Pengiriman</li>
                            <li>Invoicing & Tagihan</li>
                            <li>Serah Terima Barang</li>
                        </ul>
                    </div>
                </div>
                <div>
                    <a href="<?= base_url('customer/export_action/customer') ?>" class="btn btn-customer btn-export w-100 shadow-sm">
                        <i class="fas fa-download"></i> Export data customer
                    </a>
                </div>
            </div>
        </section>
    </div>

    <!-- CARD 2: TABLE PELANGGAN -->
    <div class="col-lg-4 mb-4">
        <section class="card export-card h-100">
            <div class="export-header bg-pelanggan">
                <h3 class="font-weight-bold m-0" style="font-size: 1.25rem;">
                    <i class="fas fa-user-check mr-2"></i> Table Pelanggan
                </h3>
                <small class="opacity-75">Sistem Visilab, Servis & Support</small>
            </div>
            <div class="export-body d-flex flex-column justify-content-between h-100" style="min-height: 280px;">
                <div>
                    <div class="d-flex align-items-baseline mb-3">
                        <span class="db-count"><?= number_format($count_pelanggan) ?></span>
                        <span class="text-muted ml-2 small">Baris Terdaftar</span>
                    </div>
                    <p class="text-muted small mb-4" style="line-height: 1.6;">
                        Tabel `pelanggan` menyimpan data profil lengkap pelanggan yang terhubung dengan modul <strong>Operasional Visilab & Servis</strong>. Data ini digunakan untuk penjadwalan teknisi (ukes/upar), preventif maintenance, kalibrasi, pengujian, dan tiket helpdesk.
                    </p>
                    <div class="mb-4">
                        <span class="badge-premium badge-pelanggan"><i class="fas fa-link mr-1"></i> Modul Utama</span>
                        <ul class="pl-3 mt-2 text-muted small" style="list-style-type: square;">
                            <li>Jadwal Kalibrasi & Servis</li>
                            <li>Preventif Maintenance</li>
                            <li>Tiket Helpdesk & Support</li>
                        </ul>
                    </div>
                </div>
                <div>
                    <a href="<?= base_url('customer/export_action/pelanggan') ?>" class="btn btn-pelanggan btn-export w-100 shadow-sm">
                        <i class="fas fa-download"></i> Export data pelanggan
                    </a>
                </div>
            </div>
        </section>
    </div>

    <!-- CARD 3: TABLE CALON PELANGGAN -->
    <div class="col-lg-4 mb-4">
        <section class="card export-card h-100">
            <div class="export-header bg-calon">
                <h3 class="font-weight-bold m-0" style="font-size: 1.25rem;">
                    <i class="fas fa-user-clock mr-2"></i> Table Calon Pelanggan
                </h3>
                <small class="opacity-75">Sistem Prospek & Funnel Marketing</small>
            </div>
            <div class="export-body d-flex flex-column justify-content-between h-100" style="min-height: 280px;">
                <div>
                    <div class="d-flex align-items-baseline mb-3">
                        <span class="db-count"><?= number_format($count_calonpelanggan) ?></span>
                        <span class="text-muted ml-2 small">Baris Terdaftar</span>
                    </div>
                    <p class="text-muted small mb-4" style="line-height: 1.6;">
                        Tabel `calonpelanggan` menyimpan data prospek atau leads yang diperoleh oleh tim sales. Terintegrasi erat dengan modul <strong>Marketing Funnel</strong>, monitoring target penjualan bulanan marketing, serta pembuatan dokumen penawaran harga (SPH).
                    </p>
                    <div class="mb-4">
                        <span class="badge-premium badge-calon"><i class="fas fa-link mr-1"></i> Modul Utama</span>
                        <ul class="pl-3 mt-2 text-muted small" style="list-style-type: square;">
                            <li>Sales Prospek & Leads</li>
                            <li>Funnel Sales & Marketing</li>
                            <li>Surat Penawaran Harga (SPH)</li>
                        </ul>
                    </div>
                </div>
                <div>
                    <a href="<?= base_url('customer/export_action/calonpelanggan') ?>" class="btn btn-calon btn-export w-100 shadow-sm">
                        <i class="fas fa-download"></i> Export data calon pelanggan
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>