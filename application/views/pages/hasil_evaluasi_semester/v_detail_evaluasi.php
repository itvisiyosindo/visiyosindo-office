<header class="page-header">
   <h2><i class="fas fa-chart-line"></i>&nbsp;<?= $page_title ?></h2>
   <div class="right-wrapper text-left">
      <ol class="breadcrumbs">
         <li><span><?= $page_desc ?></span></li>
      </ol>
   </div>
</header>

<?php
// Generate initials from name
function getInitials($name)
{
   $words = explode(' ', trim($name));
   $initials = '';
   foreach ($words as $word) {
      if (!empty($word)) {
         $initials .= strtoupper(substr($word, 0, 1));
         if (strlen($initials) >= 2) break;
      }
   }
   return $initials ?: 'NA';
}
$initials = getInitials($pegawai->nama);

// Generate consistent color based on name
function getAvatarColor($name)
{
   $colors = [
      ['#667eea', '#764ba2'], // Purple
      ['#f093fb', '#f5576c'], // Pink
      ['#4facfe', '#00f2fe'], // Blue
      ['#43e97b', '#38f9d7'], // Green
      ['#fa709a', '#fee140'], // Orange-Pink
      ['#a8edea', '#fed6e3'], // Light Teal
      ['#ff9a9e', '#fecfef'], // Light Pink
      ['#ffecd2', '#fcb69f'], // Peach
   ];
   $index = crc32($name) % count($colors);
   return $colors[abs($index)];
}
$avatarColors = getAvatarColor($pegawai->nama);
?>

<style>
   .card-custom {
      border: none;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      margin-bottom: 24px;
      transition: box-shadow 0.3s ease;
   }

   .card-custom:hover {
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
   }

   .card-header-gradient {
      padding: 18px 24px;
      color: white;
   }

   .card-header-gradient h6 {
      font-weight: 600;
      font-size: 15px;
   }

   .gradient-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
   }

   .gradient-info {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
   }

   .gradient-success {
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
   }

   .gradient-warning {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
   }

   .gradient-danger {
      background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
   }

   .profile-card {
      background: linear-gradient(135deg, <?= $avatarColors[0] ?> 0%, <?= $avatarColors[1] ?> 100%);
      color: white;
      border-radius: 16px;
      padding: 32px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(102, 126, 234, 0.25);
   }

   .profile-card::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 100%;
      height: 200%;
      background: rgba(255, 255, 255, 0.08);
      transform: rotate(30deg);
   }

   .profile-card::after {
      content: '';
      position: absolute;
      bottom: -30%;
      left: -20%;
      width: 60%;
      height: 100%;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
   }

   .avatar-circle {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.25);
      border: 4px solid rgba(255, 255, 255, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
      font-weight: 700;
      color: white;
      text-transform: uppercase;
      letter-spacing: 2px;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
   }

   .profile-info h2 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 12px;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
      color: white;
   }

   .profile-info p {
      margin-bottom: 8px;
      font-size: 14px;
      color: rgba(255, 255, 255, 0.95);
   }

   .profile-info p i {
      width: 20px;
      text-align: center;
      color: white;
   }

   .status-badge {
      padding: 10px 24px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 30px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      backdrop-filter: blur(10px);
      color: white;
   }

   .status-badge.active {
      background: rgba(40, 167, 69, 0.95);
   }

   .status-badge.inactive {
      background: rgba(220, 53, 69, 0.95);
   }

   .meta-info {
      background: rgba(255, 255, 255, 0.2);
      padding: 12px 20px;
      border-radius: 12px;
      margin-top: 16px;
      backdrop-filter: blur(10px);
   }

   .meta-info small {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      color: white;
   }

   .stat-card {
      border-radius: 16px;
      padding: 28px 24px;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
      height: 100%;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
   }

   .stat-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
   }

   .stat-card .icon {
      font-size: 48px;
      opacity: 0.2;
      position: absolute;
      right: 20px;
      top: 20px;
   }

   .stat-card .value {
      font-size: 42px;
      font-weight: 800;
      line-height: 1;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
   }

   .stat-card .label {
      font-size: 12px;
      opacity: 0.9;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-top: 8px;
      font-weight: 600;
   }

   .stat-card .badge-predikat {
      background: rgba(255, 255, 255, 0.25);
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 13px;
      margin-top: 12px;
      display: inline-block;
      font-weight: 600;
      backdrop-filter: blur(5px);
   }

   .stat-card .sub-info {
      margin-top: 10px;
      font-size: 12px;
      opacity: 0.85;
   }

   .detail-table {
      border-radius: 8px;
      overflow: hidden;
   }

   .detail-table thead {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
   }

   .detail-table thead th {
      border: none;
      padding: 14px 16px;
      font-weight: 700;
      color: #495057;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
   }

   .detail-table tbody td {
      padding: 12px 16px;
      vertical-align: middle;
      border-bottom: 1px solid #f1f3f4;
   }

   .detail-table tbody tr:hover {
      background-color: #f8f9ff;
   }

   .detail-table tbody tr:last-child td {
      border-bottom: none;
   }

   .badge-nilai {
      padding: 6px 14px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 12px;
      min-width: 45px;
      display: inline-block;
   }

   .chart-container {
      position: relative;
      height: 200px;
   }

   .attendance-stat {
      display: flex;
      align-items: center;
      padding: 14px 16px;
      border-radius: 12px;
      margin-bottom: 12px;
      transition: all 0.2s ease;
      border: 1px solid transparent;
   }

   .attendance-stat:hover {
      transform: translateX(5px);
      border-color: rgba(0, 0, 0, 0.05);
   }

   .attendance-stat .icon-box {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 16px;
      font-size: 18px;
   }

   .attendance-stat .count {
      font-size: 26px;
      font-weight: 800;
      line-height: 1;
   }

   .attendance-stat .text {
      font-size: 12px;
      color: #6c757d;
      font-weight: 500;
      margin-top: 2px;
   }

   .pk-display {
      text-align: center;
      padding: 24px;
   }

   .pk-display .score {
      font-size: 72px;
      font-weight: 800;
      line-height: 1;
   }

   .pk-display .predikat-badge {
      font-size: 16px;
      padding: 10px 28px;
      border-radius: 25px;
      display: inline-block;
      margin-top: 16px;
      font-weight: 600;
   }

   .scroll-area {
      max-height: 320px;
      overflow-y: auto;
   }

   .scroll-area::-webkit-scrollbar {
      width: 5px;
   }

   .scroll-area::-webkit-scrollbar-track {
      background: #f8f9fa;
      border-radius: 3px;
   }

   .scroll-area::-webkit-scrollbar-thumb {
      background: #dee2e6;
      border-radius: 3px;
   }

   .scroll-area::-webkit-scrollbar-thumb:hover {
      background: #adb5bd;
   }

   .empty-state {
      text-align: center;
      padding: 48px 24px;
   }

   .empty-state i {
      font-size: 48px;
      color: #dee2e6;
      margin-bottom: 16px;
   }

   .empty-state p {
      color: #6c757d;
      font-size: 14px;
   }

   .sp-item {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      background: #fff5f5;
      border-radius: 10px;
      margin-bottom: 10px;
      border-left: 4px solid #dc3545;
   }

   .sp-item .badge {
      font-size: 12px;
      padding: 6px 12px;
   }

   @media (max-width: 768px) {
      .profile-card {
         padding: 24px;
         text-align: center;
      }

      .avatar-circle {
         margin: 0 auto 20px;
      }

      .profile-info {
         text-align: center;
      }

      .profile-info h2 {
         font-size: 22px;
      }

      .stat-card .value {
         font-size: 32px;
      }

      .stat-card .label {
         font-size: 10px;
      }
   }
</style>

<!-- Back Button & Period Info -->
<div class="row mb-4 align-items-center">
   <div class="col-md-6 mb-2 mb-md-0">
      <a href="<?= site_url('hasil_evaluasi_semester') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>"
         class="btn btn-light px-4" style="border-radius: 25px; font-weight: 500;">
         <i class="fa fa-arrow-left mr-2"></i> Kembali ke Daftar
      </a>
   </div>
   <div class="col-md-6 text-md-right">
      <span class="badge badge-primary px-4 py-2" style="font-size: 14px; border-radius: 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
         <i class="fa fa-calendar-alt mr-2"></i> <?= $periode_label ?>
      </span>
   </div>
</div>

<!-- Profile Card -->
<div class="row mb-4">
   <div class="col-12">
      <div class="profile-card">
         <div class="row align-items-center">
            <div class="col-lg-2 col-md-3 text-center mb-3 mb-md-0">
               <div class="avatar-circle mx-auto"><?= $initials ?></div>
            </div>
            <div class="col-lg-6 col-md-5 profile-info mb-3 mb-md-0">
               <h2><?= $pegawai->nama ?></h2>
               <p><i class="fa fa-id-badge"></i> <?= $pegawai->no_pegawai ?: '-' ?></p>
               <p><i class="fa fa-briefcase"></i> <?= $pegawai->jabatan ?: '-' ?></p>
               <p><i class="fa fa-envelope"></i> <?= $pegawai->email ?: '-' ?></p>
            </div>
            <div class="col-lg-4 col-md-4 text-md-right">
               <span class="status-badge <?= $pegawai->is_active ? 'active' : 'inactive' ?>">
                  <i class="fa <?= $pegawai->is_active ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                  <?= $pegawai->is_active ? 'Karyawan Aktif' : 'Non-Aktif' ?>
               </span>
               <div class="meta-info">
                  <small class="mb-2">
                     <i class="fa fa-calendar-alt"></i>
                     Bergabung: <?= $pegawai->tgl_masuk ? date('d M Y', strtotime($pegawai->tgl_masuk)) : '-' ?>
                  </small>
                  <small>
                     <i class="fa fa-phone"></i>
                     <?= $pegawai->no_hp ?: '-' ?>
                  </small>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Summary Stats -->
<div class="row mb-4">
   <!-- Rata Laporan -->
   <div class="col-lg-3 col-md-6 col-6 mb-3">
      <div class="stat-card gradient-info">
         <i class="fa fa-file-alt icon"></i>
         <div class="value"><?= number_format($rata_laporan, 1) ?></div>
         <div class="label">Rata Laporan</div>
         <div class="badge-predikat"><?= $predikat_laporan['predikat'] ?> - <?= $predikat_laporan['label'] ?></div>
         <div class="sub-info"><?= $jumlah_laporan ?> laporan</div>
      </div>
   </div>

   <!-- Rata Evaluasi -->
   <div class="col-lg-3 col-md-6 col-6 mb-3">
      <div class="stat-card gradient-primary">
         <i class="fa fa-star icon"></i>
         <div class="value"><?= number_format($rata_evaluasi, 1) ?></div>
         <div class="label">Rata Evaluasi</div>
         <div class="badge-predikat"><?= $predikat_evaluasi['predikat'] ?> - <?= $predikat_evaluasi['label'] ?></div>
         <div class="sub-info"><?= count($detail_evaluasi) ?> item</div>
      </div>
   </div>

   <!-- Total Hadir -->
   <div class="col-lg-3 col-md-6 col-6 mb-3">
      <div class="stat-card gradient-success">
         <i class="fa fa-calendar-check icon"></i>
         <div class="value"><?= $total_hadir ?></div>
         <div class="label">Total Hadir</div>
         <div class="badge-predikat">Hari Kerja</div>
         <div class="sub-info">H:<?= $statistik_hadir['hadir'] ?> | T:<?= $statistik_hadir['terlambat'] ?></div>
      </div>
   </div>

   <!-- Nilai PK & SP -->
   <div class="col-lg-3 col-md-6 col-6 mb-3">
      <div class="stat-card <?= $total_sp > 0 ? 'gradient-danger' : 'gradient-warning' ?>">
         <i class="fa fa-<?= $total_sp > 0 ? 'exclamation-triangle' : 'book' ?> icon"></i>
         <div class="value"><?= $nilai_pk > 0 ? number_format($nilai_pk, 0) : '-' ?></div>
         <div class="label">Nilai PK</div>
         <?php if ($nilai_pk > 0): ?>
            <div class="badge-predikat"><?= $predikat_pk['predikat'] ?></div>
         <?php else: ?>
            <div class="badge-predikat">Belum Input</div>
         <?php endif; ?>
         <div class="sub-info"><i class="fa fa-exclamation-triangle"></i> <?= isset($sp_terakhir) && $sp_terakhir ? $this->md_hasil_evaluasi_semester->getLabelJenisSP($sp_terakhir->jenis_sp) : 'Tidak ada SP' ?> (<?= $nilai_sp ?>)</div>
      </div>
   </div>
</div>

<!-- Total Nilai Evaluasi Semester Card -->
<div class="row mb-4">
   <div class="col-12">
      <div class="card card-custom">
         <div class="card-header card-header-gradient gradient-primary">
            <h6 class="mb-0"><i class="fa fa-chart-line mr-2"></i> Total Nilai Evaluasi Semester (FINAL)</h6>
         </div>
         <div class="card-body">
            <div class="row align-items-center">
               <div class="col-lg-4 text-center mb-3 mb-lg-0">
                  <div style="font-size: 72px; font-weight: 800; line-height: 1; color: <?= $predikat_final['class'] == 'success' ? '#28a745' : ($predikat_final['class'] == 'primary' ? '#667eea' : ($predikat_final['class'] == 'warning' ? '#ffc107' : '#dc3545')) ?>;">
                     <?= number_format($hasil_evaluasi_final['total_nilai'], 2) ?>
                  </div>
                  <div class="badge badge-<?= $predikat_final['class'] ?>" style="padding: 10px 25px; font-size: 16px; border-radius: 25px; margin-top: 10px;">
                     <?= $predikat_final['predikat'] ?> - <?= $predikat_final['label'] ?>
                  </div>
               </div>
               <div class="col-lg-8">
                  <h6 class="mb-3"><strong>Rumus Perhitungan:</strong> <small class="text-muted"><?= $formula_desc ?></small></h6>
                  <div class="row">
                     <div class="col-6 col-md-4 mb-3">
                        <div class="p-3 rounded" style="background: rgba(102, 126, 234, 0.1);">
                           <small class="text-muted">Nilai Evaluasi</small>
                           <div class="font-weight-bold" style="font-size: 20px;"><?= number_format($hasil_evaluasi_final['komponen']['evaluasi'], 2) ?></div>
                           <?php if (isset($hasil_evaluasi_final['detail_hitung'])): ?>
                              <?php
                              $detail = $hasil_evaluasi_final['detail_hitung'];
                              // Detect evaluasi key (30, 40, 50, 70, 85)
                              $eval_key = null;
                              foreach (['evaluasi_30', 'evaluasi_40', 'evaluasi_50', 'evaluasi_70', 'evaluasi_85'] as $key) {
                                 if (isset($detail[$key])) {
                                    $eval_key = $key;
                                    break;
                                 }
                              }
                              if ($eval_key):
                                 $persen_eval = str_replace('evaluasi_', '', $eval_key);
                              ?>
                                 <small class="text-success"><?= $persen_eval ?>% = <?= number_format($detail[$eval_key] ?? 0, 2) ?></small>
                              <?php endif; ?>
                           <?php endif; ?>
                        </div>
                     </div>
                     <div class="col-6 col-md-4 mb-3">
                        <div class="p-3 rounded" style="background: rgba(235, 51, 73, 0.1);">
                           <small class="text-muted">Nilai SP</small>
                           <div class="font-weight-bold" style="font-size: 20px;"><?= number_format($hasil_evaluasi_final['komponen']['sp'], 2) ?></div>
                           <?php if (isset($hasil_evaluasi_final['detail_hitung'])): ?>
                              <?php
                              $detail = $hasil_evaluasi_final['detail_hitung'];
                              // Detect SP key (3, 5, 7, 10, 15, 20, 30)
                              $sp_key = null;
                              foreach (['sp_3', 'sp_5', 'sp_7', 'sp_10', 'sp_15', 'sp_20', 'sp_30'] as $key) {
                                 if (isset($detail[$key])) {
                                    $sp_key = $key;
                                    break;
                                 }
                              }
                              if ($sp_key):
                                 $persen_sp = str_replace('sp_', '', $sp_key);
                              ?>
                                 <small class="text-success"><?= $persen_sp ?>% = <?= number_format($detail[$sp_key] ?? 0, 2) ?></small>
                              <?php else: ?>
                                 <small class="text-muted">Tidak termasuk rumus</small>
                              <?php endif; ?>
                           <?php endif; ?>
                        </div>
                     </div>
                     <div class="col-6 col-md-4 mb-3">
                        <div class="p-3 rounded" style="background: rgba(240, 147, 251, 0.1);">
                           <small class="text-muted">Nilai PK</small>
                           <div class="font-weight-bold" style="font-size: 20px;"><?= number_format($hasil_evaluasi_final['komponen']['pk'], 2) ?></div>
                           <?php if (isset($hasil_evaluasi_final['detail_hitung'])): ?>
                              <?php
                              $detail = $hasil_evaluasi_final['detail_hitung'];
                              // Detect PK key (5, 10, 25)
                              $pk_key = null;
                              foreach (['pk_5', 'pk_10', 'pk_25'] as $key) {
                                 if (isset($detail[$key])) {
                                    $pk_key = $key;
                                    break;
                                 }
                              }
                              if ($pk_key):
                                 $persen_pk = str_replace('pk_', '', $pk_key);
                              ?>
                                 <small class="text-success"><?= $persen_pk ?>% = <?= number_format($detail[$pk_key] ?? 0, 2) ?></small>
                              <?php else: ?>
                                 <small class="text-muted">Tidak termasuk rumus</small>
                              <?php endif; ?>
                           <?php endif; ?>
                        </div>
                     </div>
                     <div class="col-6 col-md-4 mb-3">
                        <div class="p-3 rounded" style="background: rgba(17, 153, 142, 0.1);">
                           <small class="text-muted">Nilai Kehadiran</small>
                           <div class="font-weight-bold" style="font-size: 20px;"><?= number_format($hasil_evaluasi_final['komponen']['kehadiran'], 2) ?>%</div>
                           <small class="text-muted">(<?= $hasil_evaluasi_final['komponen']['kehadiran_hari'] ?> / <?= $batas_kehadiran ?> hari)</small>
                           <?php if (isset($hasil_evaluasi_final['detail_hitung'])): ?>
                              <?php
                              $detail = $hasil_evaluasi_final['detail_hitung'];
                              // Detect kehadiran key (2, 5, 8, 15, 20, 30)
                              $kehadiran_key = null;
                              foreach (['kehadiran_2', 'kehadiran_5', 'kehadiran_8', 'kehadiran_15', 'kehadiran_20', 'kehadiran_30'] as $key) {
                                 if (isset($detail[$key])) {
                                    $kehadiran_key = $key;
                                    break;
                                 }
                              }
                              if ($kehadiran_key):
                                 $persen_kehadiran = str_replace('kehadiran_', '', $kehadiran_key);
                              ?>
                                 <br><small class="text-success"><?= $persen_kehadiran ?>% = <?= number_format($detail[$kehadiran_key] ?? 0, 2) ?></small>
                              <?php endif; ?>
                           <?php endif; ?>
                        </div>
                     </div>
                     <div class="col-6 col-md-4 mb-3">
                        <div class="p-3 rounded" style="background: rgba(79, 172, 254, 0.1);">
                           <small class="text-muted">Nilai Laporan</small>
                           <div class="font-weight-bold" style="font-size: 20px;"><?= number_format($hasil_evaluasi_final['komponen']['laporan'], 2) ?></div>
                           <?php if (isset($hasil_evaluasi_final['detail_hitung'])): ?>
                              <?php
                              $detail = $hasil_evaluasi_final['detail_hitung'];
                              // Detect laporan key (20, 25, 30, 35)
                              $laporan_key = null;
                              foreach (['laporan_20', 'laporan_25', 'laporan_30', 'laporan_35'] as $key) {
                                 if (isset($detail[$key])) {
                                    $laporan_key = $key;
                                    break;
                                 }
                              }
                              if ($laporan_key):
                                 $persen_laporan = str_replace('laporan_', '', $laporan_key);
                              ?>
                                 <small class="text-success"><?= $persen_laporan ?>% = <?= number_format($detail[$laporan_key] ?? 0, 2) ?></small>
                              <?php else: ?>
                                 <small class="text-muted">Tidak termasuk rumus</small>
                              <?php endif; ?>
                           <?php endif; ?>
                        </div>
                     </div>
                     <div class="col-6 col-md-4 mb-3">
                        <div class="p-3 rounded" style="background: rgba(102, 126, 234, 0.15);">
                           <small class="text-muted">Jabatan</small>
                           <div class="font-weight-bold" style="font-size: 14px;"><?= $pegawai->jabatan ?: 'Karyawan Biasa' ?></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Detail Sections -->
<div class="row">
   <!-- Detail Laporan -->
   <div class="col-lg-6 mb-4">
      <div class="card card-custom">
         <div class="card-header card-header-gradient gradient-info">
            <h6 class="mb-0"><i class="fa fa-file-alt mr-2"></i> Detail Laporan Mingguan</h6>
         </div>
         <div class="card-body p-0">
            <?php if (!empty($detail_laporan)): ?>
               <div class="scroll-area">
                  <table class="table detail-table mb-0">
                     <thead>
                        <tr>
                           <th style="width: 100px;">Tanggal</th>
                           <th>Jobdesc</th>
                           <th class="text-center" style="width: 70px;">Nilai A</th>
                           <th class="text-center" style="width: 70px;">Nilai B</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($detail_laporan as $lap): ?>
                           <tr>
                              <td>
                                 <span class="text-muted" style="font-size: 12px;"><?= date('d M Y', strtotime($lap->tanggal)) ?></span>
                              </td>
                              <td>
                                 <span style="font-size: 13px;"><?= $lap->nama_job ?: ($lap->jobdesc ?? '-') ?></span>
                              </td>
                              <td class="text-center">
                                 <span class="badge badge-<?= $lap->nilai >= 70 ? 'success' : ($lap->nilai > 0 ? 'warning' : 'secondary') ?> badge-nilai">
                                    <?= $lap->nilai ?: '-' ?>
                                 </span>
                              </td>
                              <td class="text-center">
                                 <span class="badge badge-<?= $lap->nilai_b >= 70 ? 'success' : ($lap->nilai_b > 0 ? 'warning' : 'secondary') ?> badge-nilai">
                                    <?= $lap->nilai_b ?: '-' ?>
                                 </span>
                              </td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
            <?php else: ?>
               <div class="empty-state">
                  <i class="fa fa-inbox"></i>
                  <p class="mb-0">Belum ada laporan mingguan</p>
               </div>
            <?php endif; ?>
         </div>
      </div>
   </div>

   <!-- Detail Evaluasi -->
   <div class="col-lg-6 mb-4">
      <div class="card card-custom">
         <div class="card-header card-header-gradient gradient-primary">
            <h6 class="mb-0"><i class="fa fa-star mr-2"></i> Detail Evaluasi Semester</h6>
         </div>
         <div class="card-body p-0">
            <?php if (!empty($detail_evaluasi)): ?>
               <div class="scroll-area">
                  <table class="table detail-table mb-0">
                     <thead>
                        <tr>
                           <th>Jobdesc</th>
                           <th class="text-center" style="width: 65px;">Nilai A</th>
                           <th class="text-center" style="width: 65px;">Nilai B</th>
                           <th class="text-center" style="width: 65px;">Nilai C</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($detail_evaluasi as $eval): ?>
                           <tr>
                              <td><span style="font-size: 13px;"><?= $eval->nama_job ?: 'Item Evaluasi' ?></span></td>
                              <td class="text-center">
                                 <span class="badge badge-<?= $eval->nilaia >= 70 ? 'success' : ($eval->nilaia > 0 ? 'warning' : 'secondary') ?> badge-nilai">
                                    <?= $eval->nilaia ?: '-' ?>
                                 </span>
                              </td>
                              <td class="text-center">
                                 <span class="badge badge-<?= $eval->nilaib >= 70 ? 'success' : ($eval->nilaib > 0 ? 'warning' : 'secondary') ?> badge-nilai">
                                    <?= $eval->nilaib ?: '-' ?>
                                 </span>
                              </td>
                              <td class="text-center">
                                 <span class="badge badge-<?= $eval->nilaic >= 70 ? 'success' : ($eval->nilaic > 0 ? 'warning' : 'secondary') ?> badge-nilai">
                                    <?= $eval->nilaic ?: '-' ?>
                                 </span>
                              </td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
            <?php else: ?>
               <div class="empty-state">
                  <i class="fa fa-inbox"></i>
                  <p class="mb-0">Belum ada evaluasi semester</p>
               </div>
            <?php endif; ?>
         </div>
      </div>
   </div>
</div>

<!-- Kehadiran & SP -->
<div class="row">
   <!-- Statistik Kehadiran -->
   <div class="col-lg-6 mb-4">
      <div class="card card-custom">
         <div class="card-header card-header-gradient gradient-success">
            <h6 class="mb-0"><i class="fa fa-chart-pie mr-2"></i> Statistik Kehadiran</h6>
         </div>
         <div class="card-body">
            <div class="row align-items-center">
               <div class="col-md-5 mb-3 mb-md-0">
                  <div class="chart-container">
                     <canvas id="chartKehadiran"></canvas>
                  </div>
               </div>
               <div class="col-md-7">
                  <div class="attendance-stat" style="background: rgba(40,167,69,0.08);">
                     <div class="icon-box" style="background: linear-gradient(135deg, #28a745, #20c997); color: white;">
                        <i class="fa fa-check"></i>
                     </div>
                     <div>
                        <div class="count text-success"><?= $statistik_hadir['hadir'] ?></div>
                        <div class="text">Hadir Tepat Waktu</div>
                     </div>
                  </div>
                  <div class="attendance-stat" style="background: rgba(255,193,7,0.08);">
                     <div class="icon-box" style="background: linear-gradient(135deg, #ffc107, #fd7e14); color: white;">
                        <i class="fa fa-clock"></i>
                     </div>
                     <div>
                        <div class="count text-warning"><?= $statistik_hadir['terlambat'] ?></div>
                        <div class="text">Terlambat</div>
                     </div>
                  </div>
                  <div class="attendance-stat" style="background: rgba(23,162,184,0.08);">
                     <div class="icon-box" style="background: linear-gradient(135deg, #17a2b8, #6f42c1); color: white;">
                        <i class="fa fa-file-alt"></i>
                     </div>
                     <div>
                        <div class="count text-info"><?= $statistik_hadir['izin'] ?></div>
                        <div class="text">Izin</div>
                     </div>
                  </div>
                  <div class="attendance-stat" style="background: rgba(220,53,69,0.08);">
                     <div class="icon-box" style="background: linear-gradient(135deg, #dc3545, #e83e8c); color: white;">
                        <i class="fa fa-medkit"></i>
                     </div>
                     <div>
                        <div class="count text-danger"><?= $statistik_hadir['sakit'] ?></div>
                        <div class="text">Sakit</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Surat Peringatan & Nilai PK -->
   <div class="col-lg-6 mb-4">
      <div class="row">
         <!-- Surat Peringatan -->
         <div class="col-12 mb-4">
            <div class="card card-custom">
               <div class="card-header card-header-gradient gradient-danger">
                  <h6 class="mb-0"><i class="fa fa-exclamation-triangle mr-2"></i> Surat Peringatan (Nilai SP: <?= $nilai_sp ?>)</h6>
               </div>
               <div class="card-body">
                  <?php if (!empty($detail_sp)): ?>
                     <div class="scroll-area" style="max-height: 200px;">
                        <?php foreach ($detail_sp as $sp): ?>
                           <div class="sp-item">
                              <span class="badge badge-danger mr-3"><?= $sp->jenis_sp ?></span>
                              <div class="flex-grow-1">
                                 <div class="font-weight-bold" style="font-size: 13px;"><?= $sp->kode ?></div>
                                 <small class="text-muted"><?= date('d M Y', strtotime($sp->tgl_pengajuan)) ?></small>
                                 <?php if (!empty($sp->kesalahan)): ?>
                                    <div class="mt-1" style="font-size: 12px; color: #666;">
                                       <strong>Kesalahan:</strong> <?= substr($sp->kesalahan, 0, 100) ?><?= strlen($sp->kesalahan) > 100 ? '...' : '' ?>
                                    </div>
                                 <?php endif; ?>
                                 <?php if (!empty($sp->sanksi)): ?>
                                    <div style="font-size: 12px; color: #666;">
                                       <strong>Sanksi:</strong> <?= substr($sp->sanksi, 0, 100) ?><?= strlen($sp->sanksi) > 100 ? '...' : '' ?>
                                    </div>
                                 <?php endif; ?>
                              </div>
                           </div>
                        <?php endforeach; ?>
                     </div>
                     <?php if (isset($sp_terakhir) && $sp_terakhir): ?>
                        <div class="alert alert-danger mt-3 mb-0" style="font-size: 13px;">
                           <strong>SP Terakhir:</strong> <?= $this->md_hasil_evaluasi_semester->getLabelJenisSP($sp_terakhir->jenis_sp) ?>
                           (<?= date('d M Y', strtotime($sp_terakhir->tgl_pengajuan)) ?>) -
                           <strong>Nilai SP: <?= $nilai_sp ?></strong>
                        </div>
                     <?php endif; ?>
                  <?php else: ?>
                     <div class="text-center py-4">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(40,167,69,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                           <i class="fa fa-check-circle fa-2x text-success"></i>
                        </div>
                        <p class="text-success mb-0 font-weight-500">Tidak ada Surat Peringatan</p>
                        <small class="text-muted">Nilai SP: 100 (Maksimal)</small>
                     </div>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- Nilai PK -->
         <div class="col-12">
            <div class="card card-custom">
               <div class="card-header card-header-gradient gradient-warning">
                  <h6 class="mb-0"><i class="fa fa-book mr-2"></i> Nilai Product Knowledge</h6>
               </div>
               <div class="card-body">
                  <?php if ($detail_pk): ?>
                     <div class="pk-display">
                        <div class="score text-<?= $predikat_pk['class'] ?>"><?= number_format($detail_pk->nilai, 0) ?></div>
                        <div class="predikat-badge bg-<?= $predikat_pk['class'] ?> text-white">
                           <?= $predikat_pk['predikat'] ?> - <?= $predikat_pk['label'] ?>
                        </div>
                        <?php if ($detail_pk->keterangan): ?>
                           <p class="text-muted mt-3 mb-0" style="font-size: 13px;"><?= $detail_pk->keterangan ?></p>
                        <?php endif; ?>
                     </div>
                  <?php else: ?>
                     <div class="text-center py-4">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(108,117,125,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                           <i class="fa fa-edit fa-2x text-muted"></i>
                        </div>
                        <p class="text-muted mb-3">Belum ada nilai</p>
                        <a href="<?= site_url('hasil_evaluasi_semester/nilai_pk') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>"
                           class="btn btn-warning px-4" style="border-radius: 25px;">
                           <i class="fa fa-edit mr-1"></i> Input Nilai
                        </a>
                     </div>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
      var ctx = document.getElementById('chartKehadiran').getContext('2d');
      new Chart(ctx, {
         type: 'doughnut',
         data: {
            labels: ['Hadir', 'Terlambat', 'Izin', 'Sakit'],
            datasets: [{
               data: [<?= $statistik_hadir['hadir'] ?>, <?= $statistik_hadir['terlambat'] ?>, <?= $statistik_hadir['izin'] ?>, <?= $statistik_hadir['sakit'] ?>],
               backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
               borderWidth: 0
            }]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
               legend: {
                  display: false
               }
            }
         }
      });
   });
</script>