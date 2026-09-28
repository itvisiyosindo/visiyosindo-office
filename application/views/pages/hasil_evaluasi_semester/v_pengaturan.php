<header class="page-header">
   <h2><i class="fas fa-cog"></i>&nbsp;<?= $page_title ?></h2>
   <div class="right-wrapper text-left">
      <ol class="breadcrumbs">
         <li><span><?= $page_desc ?></span></li>
      </ol>
   </div>
</header>

<style>
   .card-custom {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
      overflow: hidden;
   }

   .card-header-gradient {
      padding: 18px 25px;
      color: white;
   }

   .gradient-settings {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
   }

   .config-card {
      border: 2px solid #e0e0e0;
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 20px;
      transition: all 0.3s ease;
   }

   .config-card:hover {
      border-color: #667eea;
      box-shadow: 0 5px 20px rgba(102, 126, 234, 0.15);
   }

   .config-card.active {
      border-color: #28a745;
      background: linear-gradient(135deg, rgba(40, 167, 69, 0.05), rgba(40, 167, 69, 0.1));
   }

   .config-title {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin-bottom: 10px;
   }

   .config-desc {
      color: #666;
      font-size: 14px;
      margin-bottom: 15px;
   }

   .switch-container {
      display: flex;
      align-items: center;
      gap: 15px;
   }

   .switch-modern {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 32px;
   }

   .switch-modern input {
      opacity: 0;
      width: 0;
      height: 0;
   }

   .slider-modern {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: 0.4s;
      border-radius: 32px;
   }

   .slider-modern:before {
      position: absolute;
      content: "";
      height: 24px;
      width: 24px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      transition: 0.4s;
      border-radius: 50%;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
   }

   input:checked+.slider-modern {
      background: linear-gradient(135deg, #11998e, #38ef7d);
   }

   input:checked+.slider-modern:before {
      transform: translateX(28px);
   }

   .switch-label {
      font-size: 14px;
      color: #666;
   }

   .switch-label.active {
      color: #28a745;
      font-weight: 600;
   }

   .mode-indicator {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      border-radius: 25px;
      font-weight: 500;
      font-size: 14px;
   }

   .mode-sistem {
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: white;
   }

   .mode-manual {
      background: linear-gradient(135deg, #f093fb, #f5576c);
      color: white;
   }

   .btn-save-config {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 12px 30px;
      border-radius: 10px;
      color: white;
      font-weight: 500;
      transition: all 0.3s ease;
   }

   .btn-save-config:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
      color: white;
   }

   .info-box {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
      border-left: 4px solid #667eea;
      padding: 15px 20px;
      border-radius: 0 10px 10px 0;
      margin-top: 20px;
   }

   .info-box h6 {
      color: #667eea;
      font-weight: 600;
      margin-bottom: 10px;
   }

   .info-box ul {
      margin: 0;
      padding-left: 20px;
   }

   .info-box li {
      color: #555;
      font-size: 13px;
      margin-bottom: 5px;
   }
</style>

<!-- Back Button -->
<div class="row mb-4">
   <div class="col-12">
      <a href="<?= site_url('hasil_evaluasi_semester') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>" class="btn btn-outline-secondary">
         <i class="fa fa-arrow-left mr-2"></i> Kembali ke Hasil Evaluasi
      </a>
   </div>
</div>

<!-- Config Card -->
<div class="row">
   <div class="col-lg-8">
      <div class="card card-custom">
         <div class="card-header card-header-gradient gradient-settings">
            <h5 class="mb-0"><i class="fa fa-sliders-h mr-2"></i> Pengaturan Mode Input</h5>
         </div>
         <div class="card-body">
            <!-- Mode Rata-rata Laporan -->
            <div class="config-card <?= $mode_laporan == 'manual' ? 'active' : '' ?>" id="config-laporan">
               <div class="d-flex justify-content-between align-items-start flex-wrap">
                  <div class="flex-grow-1">
                     <div class="config-title">
                        <i class="fa fa-file-alt text-primary mr-2"></i>
                        Mode Rata-rata Laporan Mingguan
                     </div>
                     <div class="config-desc">
                        Pilih apakah nilai rata-rata laporan mingguan dihitung otomatis dari sistem atau diinput manual.
                     </div>

                     <div class="switch-container">
                        <span class="switch-label <?= $mode_laporan == 'sistem' ? 'active' : '' ?>" id="label-sistem">
                           <i class="fa fa-robot mr-1"></i> Sistem (Otomatis)
                        </span>

                        <label class="switch-modern">
                           <input type="checkbox" id="switch-mode-laporan" <?= $mode_laporan == 'manual' ? 'checked' : '' ?>>
                           <span class="slider-modern"></span>
                        </label>

                        <span class="switch-label <?= $mode_laporan == 'manual' ? 'active' : '' ?>" id="label-manual">
                           <i class="fa fa-edit mr-1"></i> Manual (Input)
                        </span>
                     </div>
                  </div>

                  <div class="mt-3 mt-md-0">
                     <span class="mode-indicator <?= $mode_laporan == 'sistem' ? 'mode-sistem' : 'mode-manual' ?>" id="mode-indicator">
                        <i class="fa <?= $mode_laporan == 'sistem' ? 'fa-robot' : 'fa-edit' ?>"></i>
                        <span id="mode-text"><?= $mode_laporan == 'sistem' ? 'Sistem' : 'Manual' ?></span>
                     </span>
                  </div>
               </div>

               <div class="info-box">
                  <h6><i class="fa fa-info-circle mr-1"></i> Penjelasan Mode:</h6>
                  <ul>
                     <li><strong>Sistem (Otomatis):</strong> Nilai rata-rata laporan dihitung otomatis dari data laporan mingguan & penilaian umum yang sudah diinput ke sistem.</li>
                     <li><strong>Manual (Input):</strong> Nilai rata-rata laporan diinput manual oleh admin/HRD. Mode ini berguna jika ada kebutuhan urgent untuk input nilai secara langsung.</li>
                  </ul>
               </div>
            </div>

            <!-- Batas Absensi (Hari Kerja) -->
            <div class="config-card active" id="config-absensi" style="margin-top: 20px;">
               <div class="d-flex justify-content-between align-items-start flex-wrap">
                  <div class="flex-grow-1">
                     <div class="config-title">
                        <i class="fa fa-calendar-check text-primary mr-2"></i>
                        Target Batas Kehadiran (Hari Kerja)
                     </div>
                     <div class="config-desc">
                        Masukkan jumlah target/batas hari kerja absensi untuk semester yang sedang berjalan.
                     </div>
                     <div class="form-group row">
                        <div class="col-sm-5 col-md-4">
                           <div class="input-group">
                              <input type="number" id="batas_kehadiran" class="form-control" value="<?= $batas_kehadiran ?>" min="1" style="border-radius: 8px 0 0 8px; font-weight: 600; text-align: center;">
                              <div class="input-group-append">
                                 <span class="input-group-text" style="border-radius: 0 8px 8px 0; background: #e9ecef; border-left: none;">Hari</span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="info-box">
                  <h6><i class="fa fa-info-circle mr-1"></i> Penjelasan Batas Absensi:</h6>
                  <ul>
                     <li>Jika jumlah kehadiran karyawan yang sedang berjalan telah mencapai batas target hari ini, maka nilainya otomatis <strong>100</strong>.</li>
                     <li>Jika belum mencapai batas, nilainya akan dihitung proporsional: <code>(Total Hadir / Batas Kehadiran) x 100</code>.</li>
                  </ul>
               </div>
            </div>

            <!-- Save Button -->
            <div class="text-right mt-4">
               <button type="button" id="btn-save-config" class="btn btn-save-config">
                  <i class="fa fa-save mr-2"></i> Simpan Pengaturan
               </button>
            </div>
         </div>
      </div>
   </div>

   <div class="col-lg-4">
      <!-- Quick Links -->
      <div class="card card-custom">
         <div class="card-header card-header-gradient" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
            <h5 class="mb-0"><i class="fa fa-link mr-2"></i> Menu Terkait</h5>
         </div>
         <div class="card-body">
            <a href="<?= site_url('hasil_evaluasi_semester') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>" class="btn btn-outline-primary btn-block mb-2">
               <i class="fa fa-chart-line mr-2"></i> Lihat Hasil Evaluasi
            </a>
            <a href="<?= site_url('hasil_evaluasi_semester/nilai_pk') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>" class="btn btn-outline-info btn-block mb-2">
               <i class="fa fa-book mr-2"></i> Input Nilai PK
            </a>
            <a href="<?= site_url('hasil_evaluasi_semester/nilai_laporan') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>" class="btn btn-outline-warning btn-block mb-2" id="btn-input-laporan">
               <i class="fa fa-file-alt mr-2"></i> Input Nilai Laporan
            </a>
         </div>
      </div>

      <!-- Current Config Info -->
      <div class="card card-custom mt-4">
         <div class="card-header card-header-gradient" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
            <h5 class="mb-0"><i class="fa fa-info-circle mr-2"></i> Status Saat Ini</h5>
         </div>
         <div class="card-body">
            <table class="table table-sm table-borderless mb-0">
               <tr>
                  <td><strong>Mode Laporan:</strong></td>
                  <td>
                     <span class="badge badge-<?= $mode_laporan == 'sistem' ? 'info' : 'warning' ?>" style="padding: 5px 10px;">
                        <?= $mode_laporan == 'sistem' ? 'Sistem (Otomatis)' : 'Manual (Input)' ?>
                     </span>
                  </td>
               </tr>
               <tr>
                  <td><strong>Batas Absensi:</strong></td>
                  <td><span id="status-batas-absen" style="font-weight: 600;"><?= $batas_kehadiran ?> hari</span></td>
               </tr>
               <tr>
                  <td><strong>Tahun:</strong></td>
                  <td><?= $tahun ?></td>
               </tr>
               <tr>
                  <td><strong>Semester:</strong></td>
                  <td><?= $semester == 1 ? 'Semester 1 (Jan-Jun)' : 'Semester 2 (Jul-Des)' ?></td>
               </tr>
            </table>
         </div>
      </div>
   </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
      // Wait for jQuery to be available
      var checkJQuery = setInterval(function() {
         if (typeof $ !== 'undefined') {
            clearInterval(checkJQuery);
            initPengaturan();
         }
      }, 50);

      function initPengaturan() {
         // Toggle switch handler
         $('#switch-mode-laporan').on('change', function() {
            var isManual = $(this).is(':checked');
            updateModeUI(isManual);
         });

         // Save config
         $('#btn-save-config').on('click', function() {
            var btn = $(this);
            var isManual = $('#switch-mode-laporan').is(':checked');
            var mode = isManual ? 'manual' : 'sistem';
            var batas = $('#batas_kehadiran').val();

            btn.html('<i class="fa fa-spinner fa-spin mr-2"></i> Menyimpan...').prop('disabled', true);

            $.ajax({
               url: '<?= site_url('hasil_evaluasi_semester/save_pengaturan') ?>',
               type: 'POST',
               data: {
                  mode_laporan: mode,
                  batas_kehadiran: batas
               },
               dataType: 'json',
               success: function(res) {
                  if (res.status) {
                     Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                     });

                     $('#status-batas-absen').text(batas + ' hari');

                     // Update card style
                     if (mode == 'manual') {
                        $('#config-laporan').addClass('active');
                     } else {
                        $('#config-laporan').removeClass('active');
                     }
                  } else {
                     Swal.fire('Error', res.message, 'error');
                  }
               },
               error: function() {
                  Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
               },
               complete: function() {
                  btn.html('<i class="fa fa-save mr-2"></i> Simpan Pengaturan').prop('disabled', false);
               }
            });
         });

         function updateModeUI(isManual) {
            if (isManual) {
               $('#label-sistem').removeClass('active');
               $('#label-manual').addClass('active');
               $('#mode-indicator').removeClass('mode-sistem').addClass('mode-manual');
               $('#mode-indicator i').removeClass('fa-robot').addClass('fa-edit');
               $('#mode-text').text('Manual');
            } else {
               $('#label-sistem').addClass('active');
               $('#label-manual').removeClass('active');
               $('#mode-indicator').removeClass('mode-manual').addClass('mode-sistem');
               $('#mode-indicator i').removeClass('fa-edit').addClass('fa-robot');
               $('#mode-text').text('Sistem');
            }
         }
      }
   });
</script>