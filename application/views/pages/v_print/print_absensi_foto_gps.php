<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title_pdf) ? $title_pdf : 'Laporan Absensi Foto & GPS Karyawan'; ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        :root {
            --primary: #0f172a;
            --primary-accent: #0284c7;
            --emerald: #059669;
            --rose: #e11d48;
            --amber: #d97706;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-700: #334155;
            --slate-900: #0f172a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.5;
        }

        .no-print-bar {
            background: #0f172a;
            color: #ffffff;
            padding: 12px 24px;
            position: sticky;
            top: 0;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .no-print-bar label {
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
            margin-right: 6px;
        }

        .no-print-bar select, .no-print-bar input {
            background: #1e293b;
            border: 1px solid #334155;
            color: #ffffff;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-family: inherit;
        }

        .btn-print {
            background: #0284c7;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-size: 13px;
        }

        .btn-print:hover {
            background: #0369a1;
        }

        .btn-back {
            background: #475569;
            color: #ffffff;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .container {
            max-width: 1100px;
            margin: 20px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 10px 25px -5px rgba(0,0,0,0.05);
        }

        .header-kop {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px double #0f172a;
            padding-bottom: 15px;
        }

        .header-title {
            text-align: center;
            margin-bottom: 24px;
        }

        .header-title h2 {
            margin: 0 0 6px 0;
            color: #0f172a;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .header-title p {
            margin: 0;
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
        }

        .emp-info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }

        .info-group small {
            display: block;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .info-group strong {
            color: #0f172a;
            font-size: 14px;
            font-weight: 700;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 10px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .stat-card .num {
            font-size: 20px;
            font-weight: 800;
            line-height: 1.2;
            color: #0f172a;
        }

        .stat-card .label {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
        }

        .absen-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .absen-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
            display: flex;
            gap: 14px;
            position: relative;
            page-break-inside: avoid;
            break-inside: avoid;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            transition: border-color 0.2s;
        }

        .absen-card:hover {
            border-color: #cbd5e1;
        }

        .photo-box {
            width: 110px;
            height: 130px;
            flex-shrink: 0;
            border-radius: 8px;
            overflow: hidden;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-photo {
            color: #94a3b8;
            font-size: 10px;
            text-align: center;
            padding: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .no-photo i {
            font-size: 24px;
            color: #cbd5e1;
        }

        .absen-details {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .date-badge {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .time-text {
            font-size: 15px;
            font-weight: 800;
            color: #0284c7;
            margin-bottom: 6px;
        }

        .badges-row {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: 8px;
        }

        .badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-masuk { background: #dcfce7; color: #15803d; }
        .badge-istirahat { background: #e0f2fe; color: #0369a1; }
        .badge-keluar { background: #ffedd5; color: #c2410c; }
        .badge-izin { background: #f3e8ff; color: #7e22ce; }

        .badge-tepat { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .badge-terlambat { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }

        .badge-kantor { background: #f1f5f9; color: #475569; }
        .badge-dinas { background: #fef3c7; color: #b45309; }
        .badge-wfa { background: #ede9fe; color: #6d28d9; }

        .gps-info {
            font-size: 11px;
            color: #475569;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 6px;
            padding: 6px 8px;
            line-height: 1.4;
        }

        .gps-info a {
            color: #0284c7;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .gps-info a:hover {
            text-decoration: underline;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            color: #64748b;
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .sig-box {
            text-align: center;
            width: 200px;
        }

        .sig-space {
            height: 65px;
        }

        .sig-title {
            font-size: 11px;
            font-weight: 700;
            color: #475569;
        }

        .sig-name {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            border-bottom: 1px solid #0f172a;
            padding-bottom: 2px;
        }

        @media print {
            .no-print-bar {
                display: none !important;
            }

            body {
                background: #ffffff;
                font-size: 11px;
            }

            .container {
                max-width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }

            .absen-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .absen-card {
                padding: 10px;
                border: 1px solid #cbd5e1;
            }

            .photo-box {
                width: 95px;
                height: 115px;
            }

            a {
                text-decoration: none !important;
                color: #000000 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Kontrol Filter & Print (Tidak ikut tercetak) -->
    <div class="no-print-bar">
        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
            <a href="javascript:history.back()" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
            
            <div>
                <label for="select_karyawan">Karyawan:</label>
                <select id="select_karyawan" onchange="changeFilter()">
                    <?php if (isset($all_karyawan) && is_array($all_karyawan)): ?>
                        <?php foreach ($all_karyawan as $k): ?>
                            <option value="<?= encrypt($k->pengguna_id); ?>" <?= (isset($pengguna_id) && $k->pengguna_id == $pengguna_id) ? 'selected' : ''; ?>>
                                <?= $k->nama; ?> (<?= $k->no_pegawai; ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label for="select_month">Bulan:</label>
                <input type="month" id="select_month" value="<?= isset($month) ? $month : date('Y-m'); ?>" onchange="changeFilter()">
            </div>
        </div>

        <div>
            <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> Cetak / Print PDF</button>
        </div>
    </div>

    <div class="container">
        <!-- Header Kop Perusahaan -->
        <?php 
            $kopPath = FCPATH . 'assets/img/kop_baru.jpg';
            if (file_exists($kopPath)): 
        ?>
            <img src="<?= base_url('assets/img/kop_baru.jpg'); ?>" class="header-kop" alt="Kop Visiyosindo">
        <?php endif; ?>

        <div class="header-title">
            <h2>REKAPITULASI FOTO SELFIE & LOKASI GPS ABSENSI</h2>
            <p>Periode Bulan: <strong><?= date('F Y', strtotime((isset($month) ? $month : date('Y-m')) . '-01')); ?></strong></p>
        </div>

        <!-- Kartu Informasi Karyawan -->
        <div class="emp-info-card">
            <div class="info-group">
                <small>Nama Karyawan</small>
                <strong><?= isset($pengguna[0]->nama) ? ucwords($pengguna[0]->nama) : '-'; ?></strong>
            </div>
            <div class="info-group">
                <small>No. Pegawai / NIK</small>
                <strong><?= isset($pengguna[0]->no_pegawai) ? $pengguna[0]->no_pegawai : '-'; ?></strong>
            </div>
            <div class="info-group">
                <small>Jabatan</small>
                <strong><?= isset($pengguna[0]->jabatan) ? ucwords($pengguna[0]->jabatan) : '-'; ?></strong>
            </div>
            <div class="info-group">
                <small>Total Presensi Bulan Ini</small>
                <strong style="color: #0284c7;"><?= isset($absen_list) ? count($absen_list) : 0; ?> Log Absensi</strong>
            </div>
        </div>

        <?php
            $total_log = count($absen_list);
            $total_masuk = 0;
            $total_terlambat = 0;
            $total_foto = 0;
            $total_gps = 0;

            foreach ($absen_list as $row) {
                if ($row->type_absen == 'masuk') $total_masuk++;
                if ($row->status_absen == 'terlambat') $total_terlambat++;
                if (!empty($row->file_foto) && file_exists(FCPATH . 'assets/img/absen/' . $row->file_foto)) $total_foto++;
                if (!empty($row->latitude) && !empty($row->longitude)) $total_gps++;
            }
        ?>

        <!-- Ringkasan Statistik -->
        <div class="summary-stats">
            <div class="stat-card">
                <div class="num"><?= $total_log; ?></div>
                <div class="label">Total Absen</div>
            </div>
            <div class="stat-card">
                <div class="num" style="color: #059669;"><?= $total_masuk; ?></div>
                <div class="label">Hadir Masuk</div>
            </div>
            <div class="stat-card">
                <div class="num" style="color: <?= $total_terlambat > 0 ? '#e11d48' : '#059669'; ?>;"><?= $total_terlambat; ?></div>
                <div class="label">Terlambat</div>
            </div>
            <div class="stat-card">
                <div class="num" style="color: #0284c7;"><?= $total_foto; ?></div>
                <div class="label">Foto Selfie</div>
            </div>
            <div class="stat-card">
                <div class="num" style="color: #6d28d9;"><?= $total_gps; ?></div>
                <div class="label">Titik GPS</div>
            </div>
        </div>

        <!-- Grid Kartu Log Absensi Foto & GPS -->
        <div class="absen-grid">
            <?php if (!empty($absen_list)): ?>
                <?php foreach ($absen_list as $index => $row): ?>
                    <?php
                        $tglFormat = date('d M Y', strtotime($row->data_created));
                        $hariNama = array(
                            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
                        )[date('l', strtotime($row->data_created))];

                        $photoPath = FCPATH . 'assets/img/absen/' . $row->file_foto;
                        $hasPhoto = (!empty($row->file_foto) && file_exists($photoPath));
                        $hasGps = (!empty($row->latitude) && !empty($row->longitude));
                    ?>
                    <div class="absen-card">
                        <!-- Foto Selfie Absen -->
                        <div class="photo-box">
                            <?php if ($hasPhoto): ?>
                                <img src="<?= base_url('assets/img/absen/' . $row->file_foto); ?>" alt="Selfie Absen <?= $tglFormat; ?>" loading="lazy">
                            <?php else: ?>
                                <div class="no-photo">
                                    <i class="fas fa-camera"></i>
                                    <span>Foto Tidak Ada</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Detail Waktu, Type, Status & GPS -->
                        <div class="absen-details">
                            <div>
                                <div class="date-badge">
                                    <span><i class="far fa-calendar-alt"></i> <?= $hariNama; ?>, <?= $tglFormat; ?></span>
                                    <span style="color: #94a3b8; font-size: 10px;">#<?= $index + 1; ?></span>
                                </div>
                                <div class="time-text">
                                    <i class="far fa-clock"></i> <?= date('H:i:s', strtotime($row->data_created)); ?> WIB
                                </div>

                                <div class="badges-row">
                                    <!-- Type Absen -->
                                    <?php if ($row->type_absen == 'masuk'): ?>
                                        <span class="badge badge-masuk">Masuk</span>
                                    <?php elseif ($row->type_absen == 'istirahat'): ?>
                                        <span class="badge badge-istirahat">Istirahat</span>
                                    <?php elseif ($row->type_absen == 'keluar'): ?>
                                        <span class="badge badge-keluar">Keluar</span>
                                    <?php else: ?>
                                        <span class="badge badge-izin"><?= ucfirst($row->type_absen); ?></span>
                                    <?php endif; ?>

                                    <!-- Status Terlambat / Tepat Waktu -->
                                    <?php if ($row->status_absen == 'terlambat'): ?>
                                        <span class="badge badge-terlambat">Terlambat</span>
                                    <?php elseif ($row->status_absen == 'tepat_waktu'): ?>
                                        <span class="badge badge-tepat">Tepat Waktu</span>
                                    <?php endif; ?>

                                    <!-- Jenis Absen Kantor/Dinas/WFA -->
                                    <?php if (strtoupper($row->jenis_lokasi) == 'WFA'): ?>
                                        <span class="badge badge-wfa">WFA</span>
                                    <?php elseif (strtolower($row->jenis_absen) == 'dinas'): ?>
                                        <span class="badge badge-dinas">Dinas</span>
                                    <?php else: ?>
                                        <span class="badge badge-kantor">Kantor</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Informasi Lokasi GPS & IP Address -->
                            <div class="gps-info">
                                <?php if ($hasGps): ?>
                                    <div style="font-weight: 700; color: #0f172a; margin-bottom: 2px;">
                                        📍 Lat: <?= number_format((float)$row->latitude, 6); ?>, Long: <?= number_format((float)$row->longitude, 6); ?>
                                    </div>
                                    <div>
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?= $row->latitude; ?>,<?= $row->longitude; ?>" target="_blank" title="Buka Peta Google Maps">
                                            <i class="fas fa-external-link-alt"></i> Buka Google Maps
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div style="color: #94a3b8; font-style: italic;">
                                        📍 Lokasi GPS Tidak Terekam
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($row->ip_addr)): ?>
                                    <div style="font-size: 9.5px; color: #64748b; margin-top: 2px;">
                                        IP: <?= $row->ip_addr; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="far fa-folder-open"></i>
                    <h3 style="margin: 0 0 6px 0; font-size: 16px; color: #0f172a;">Tidak Ada Data Absensi Foto & GPS</h3>
                    <p style="margin: 0;">Karyawan belum melakukan presensi atau foto selfie pada bulan <?= date('F Y', strtotime((isset($month) ? $month : date('Y-m')) . '-01')); ?>.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Kolom Tanda Tangan Verifikasi -->
        <div class="signature-section">
            <div class="sig-box">
                <div class="sig-title">Dibuat Oleh,</div>
                <div class="sig-space"></div>
                <div class="sig-name"><?= isset($pengguna[0]->nama) ? ucwords($pengguna[0]->nama) : 'Karyawan'; ?></div>
                <div style="font-size: 10px; color: #64748b;">Karyawan</div>
            </div>

            <div class="sig-box">
                <div class="sig-title">Diverifikasi Oleh,</div>
                <div class="sig-space"></div>
                <div class="sig-name">HRGA & Operations</div>
                <div style="font-size: 10px; color: #64748b;">PT Visiyosindo Medika</div>
            </div>
        </div>
    </div>

    <script>
        function changeFilter() {
            var karyawanId = document.getElementById('select_karyawan').value;
            var monthVal = document.getElementById('select_month').value;
            if (karyawanId && monthVal) {
                window.location.href = '<?= base_url("absensi/print_foto_gps/"); ?>' + monthVal + '/' + karyawanId;
            }
        }
    </script>
</body>
</html>
