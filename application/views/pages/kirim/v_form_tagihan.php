<header class="page-header">
   <h2><i class="icons fas fa-file-invoice-dollar"></i>&nbsp;<?= $page_title ?></h2>
   <div class="right-wrapper text-left">
      <ol class="breadcrumbs">
         <li><span><?= $page_desc ?></span></li>
      </ol>
   </div>
</header>

<style>
   :root {
      --primary-soft: #e3f2fd;
      --primary-dark: #1565c0;
      --success-soft: #e8f5e9;
      --text-label: #8898aa;
      --text-dark: #32325d;
      --card-radius: 10px;
      /* Status Colors */
      --primary: #3498db;
      --secondary: #6c757d;
      --success: #2ecc71;
      --warning: #f1c40f;
      --danger: #e74c3c;
      --info: #17a2b8;
   }

   .text-label {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--text-label);
      font-weight: 700;
      display: block;
      margin-bottom: 4px;
   }

   .text-value {
      font-size: 0.9rem;
      color: var(--text-dark);
      font-weight: 600;
      line-height: 1.4;
   }

   .text-value-lg {
      font-size: 1.35rem;
      color: var(--primary-dark);
      font-weight: 800;
   }

   .card-modern {
      border: 0;
      border-radius: var(--card-radius);
      box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
      background: #fff;
      margin-bottom: 24px;
      transition: transform 0.2s;
      overflow: hidden;
   }

   .card-header-modern {
      background: #fff;
      border-bottom: 1px solid #f6f9fc;
      padding: 1.25rem 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
   }

   .card-header-modern h6 {
      margin: 0;
      font-weight: 700;
      color: #32325d;
      font-size: 0.95rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
   }

   .sticky-sidebar {
      position: sticky;
      top: 20px;
      z-index: 99;
   }

   .info-box {
      background-color: #f6f9fc;
      border-radius: 8px;
      padding: 15px;
      border: 1px solid #e9ecef;
   }

   .approval-track {
      display: flex;
      justify-content: space-between;
      position: relative;
      margin: 25px 0 10px;
   }

   .approval-track::before {
      content: '';
      position: absolute;
      top: 15px;
      left: 0;
      right: 0;
      height: 2px;
      background: #e9ecef;
      z-index: 0;
   }

   .approval-step {
      position: relative;
      z-index: 1;
      text-align: center;
      background: #fff;
      padding: 0 10px;
   }

   .approval-icon {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #fff;
      border: 2px solid #e9ecef;
      color: #adb5bd;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 8px;
      transition: all 0.3s;
      font-size: 0.8rem;
   }

   .approval-step.active .approval-icon {
      border-color: #3498db;
      background: #3498db;
      color: #fff;
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
   }

   .approval-step.completed .approval-icon {
      border-color: #2ecc71;
      background: #2ecc71;
      color: #fff;
   }

   .approval-label {
      font-size: 0.65rem;
      font-weight: bold;
      color: #8898aa;
      text-transform: uppercase;
   }

   .approval-step.active .approval-label {
      color: #3498db;
   }

   .approval-step.completed .approval-label {
      color: #2ecc71;
   }

   .vertical-timeline {
      list-style: none;
      padding: 0;
      position: relative;
      margin: 0;
   }

   .vertical-timeline::before {
      content: '';
      position: absolute;
      top: 5px;
      bottom: 0;
      left: 10px;
      width: 2px;
      background: #e9ecef;
   }

   .vertical-timeline li {
      position: relative;
      padding-left: 35px;
      margin-bottom: 20px;
   }

   .timeline-dot {
      position: absolute;
      left: 4px;
      top: 2px;
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background: #fff;
      border: 3px solid #dee2e6;
      z-index: 1;
   }

   .vertical-timeline li:first-child .timeline-dot {
      box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.3);
   }

   .timeline-content {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 12px 15px;
      border-left: 3px solid #dee2e6;
   }

   .vertical-timeline li:first-child .timeline-content {
      border-left-color: #2ecc71;
      background: #f8fff8;
   }

   .timeline-content ul li {
      padding-left: 0 !important;
      margin-bottom: 4px !important;
   }
</style>

<?php
// Determine asal text
$asal_text = 'Tidak Diketahui';
if (isset($kirim->asal)) {
   switch ($kirim->asal) {
      case 1:
         $asal_text = 'Kantor Pekanbaru';
         break;
      case 2:
         $asal_text = 'Gudang Pekanbaru';
         break;
      case 3:
         $asal_text = 'Gudang Jakarta';
         break;
      case 4:
         $asal_text = 'Kantor Yogyakarta';
         break;
      case 5:
         $asal_text = 'Kantor Axa Jakarta';
         break;
   }
}

// Determine status tracking
$status_tracking_text = 'Belum Dikirim';
if (isset($kirim->status_tracking)) {
   switch ($kirim->status_tracking) {
      case 0:
         $status_tracking_text = 'Baru Diajukan';
         break;
      case 1:
         $status_tracking_text = 'Proses Kirim';
         break;
      case 2:
         $status_tracking_text = 'Manifest Berangkat';
         break;
      case 3:
         $status_tracking_text = 'Proses Sortir';
         break;
      case 4:
         $status_tracking_text = 'Pengantaran Kurir';
         break;
      case 5:
         $status_tracking_text = 'Diterima';
         break;
      case 6:
         $status_tracking_text = 'Menunggu Konfirmasi';
         break;
   }
}
?>

<div class="row">
   <!-- LEFT COLUMN: Info Data -->
   <div class="col-lg-7">
      <!-- Card: Info Kirim Dokumen -->
      <div class="card card-modern">
         <div class="card-header-modern">
            <h6><i class="fas fa-paper-plane mr-2 text-primary"></i>Informasi Kirim Dokumen</h6>
            <span class="badge badge-info"><?= $kirim->kode ?></span>
         </div>
         <div class="card-body">
            <div class="row mb-3">
               <div class="col-md-6">
                  <span class="text-label">Nama Customer</span>
                  <span class="text-value"><?= $kirim->nama_customer ?></span>
               </div>
               <div class="col-md-6">
                  <span class="text-label">PIC Penerima</span>
                  <span class="text-value"><?= $kirim->pic ?></span>
               </div>
            </div>
            <div class="row mb-3">
               <div class="col-12">
                  <span class="text-label">Alamat Penerima</span>
                  <span class="text-value"><?= nl2br(htmlspecialchars($kirim->alamat)) ?></span>
               </div>
            </div>
            <div class="row mb-3">
               <div class="col-md-6">
                  <span class="text-label">Asal Pengiriman</span>
                  <span class="text-value"><?= $asal_text ?></span>
               </div>
               <div class="col-md-6">
                  <span class="text-label">Marketing</span>
                  <span class="text-value"><?= $kirim->marketing ?></span>
               </div>
            </div>
            <div class="row mb-3">
               <div class="col-12">
                  <span class="text-label">Keterangan</span>
                  <span class="text-value"><?= nl2br(htmlspecialchars($kirim->keterangan ?: '-')) ?></span>
               </div>
            </div>
         </div>
      </div>

      <!-- Card: Info Ekspedisi -->
      <div class="card card-modern">
         <div class="card-header-modern">
            <h6><i class="fas fa-truck mr-2 text-warning"></i>Informasi Ekspedisi</h6>
         </div>
         <div class="card-body">
            <?php
            // Cek apakah ada data ekspedisi (baik dari id_ekspedisi atau dari nama)
            $has_ekspedisi_data = !empty($kirim->ekspedisi) && (!empty($kirim->id_ekspedisi) || !empty($kirim->ekspedisi_id_found) || !empty($kirim->nama_ekspedisi));
            ?>
            <div class="row mb-3">
               <div class="col-md-6">
                  <span class="text-label">Nama Ekspedisi</span>
                  <?php if ($has_ekspedisi_data): ?>
                     <a href="javascript:void(0)" class="text-value d-block ekspedisi-detail-link"
                        data-toggle="modal" data-target="#modalDetailEkspedisi"
                        style="color: #3498db; text-decoration: underline; cursor: pointer;">
                        <i class="fas fa-info-circle mr-1"></i><?= $kirim->ekspedisi ?>
                     </a>
                  <?php else: ?>
                     <span class="text-value"><?= $kirim->ekspedisi ?: '-' ?></span>
                  <?php endif; ?>
               </div>
               <div class="col-md-6">
                  <span class="text-label">No Resi</span>
                  <span class="text-value"><?= $kirim->no_resi ?: '-' ?></span>
               </div>
            </div>
            <div class="row mb-3">
               <div class="col-md-6">
                  <span class="text-label">Tanggal Pengiriman</span>
                  <span class="text-value">
                     <?= !empty($kirim->tgl_kirim) ? date('d-M-Y', strtotime($kirim->tgl_kirim)) : '-' ?>
                  </span>
               </div>
               <div class="col-md-6">
                  <span class="text-label">Estimasi Sampai</span>
                  <span class="text-value">
                     <?= !empty($kirim->tgl_sampai) ? date('d-M-Y', strtotime($kirim->tgl_sampai)) : '-' ?>
                  </span>
               </div>
            </div>
            <div class="row mb-3">
               <div class="col-md-6">
                  <span class="text-label">Status Tracking</span>
                  <span class="badge badge-primary"><?= $status_tracking_text ?></span>
               </div>
               <div class="col-md-6">
                  <span class="text-label">Link Resi</span>
                  <?php if (!empty($kirim->link_resi)): ?>
                     <a href="<?= $kirim->link_resi ?>" target="_blank" class="btn btn-sm btn-info">
                        <i class="fas fa-external-link-alt mr-1"></i> Cek Resi
                     </a>
                  <?php else: ?>
                     <span class="text-value">-</span>
                  <?php endif; ?>
               </div>
            </div>

            <?php if (!empty($kirim->nama_ekspedisi) || !empty($kirim->id_ekspedisi)): ?>
               <hr>
               <h6 class="text-muted mb-3"><i class="fas fa-building mr-1"></i>Detail Ekspedisi</h6>
               <div class="row mb-3">
                  <div class="col-md-6">
                     <span class="text-label">Alamat Ekspedisi</span>
                     <span class="text-value"><?= $kirim->alamat_ekspedisi ?: '-' ?></span>
                  </div>
                  <div class="col-md-6">
                     <span class="text-label">Kontak Ekspedisi</span>
                     <span class="text-value"><?= $kirim->kontak_ekspedisi ?: '-' ?></span>
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-md-6">
                     <span class="text-label">PIC Ekspedisi</span>
                     <span class="text-value"><?= $kirim->pic_ekspedisi ?: '-' ?></span>
                  </div>
                  <div class="col-md-6">
                     <span class="text-label">Jabatan PIC</span>
                     <span class="text-value"><?= $kirim->jabatan_pic ?: '-' ?></span>
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-md-6">
                     <span class="text-label">Payment Term</span>
                     <span class="text-value">
                        <?php if (!empty($kirim->payment_term)): ?>
                           <span class="badge badge-info"><?= $kirim->payment_term ?></span>
                        <?php else: ?>
                           -
                        <?php endif; ?>
                     </span>
                  </div>
                  <div class="col-md-6">
                     <span class="text-label">PPH 23</span>
                     <span class="text-value">
                        <?php if (!empty($kirim->pph23)): ?>
                           <span class="badge badge-warning"><?= $kirim->pph23 ?></span>
                        <?php else: ?>
                           -
                        <?php endif; ?>
                     </span>
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-md-6">
                     <span class="text-label">Status Kerjasama</span>
                     <span class="text-value">
                        <?php if (!empty($kirim->kerjasama)): ?>
                           <span class="badge badge-success"><?= $kirim->kerjasama ?></span>
                        <?php else: ?>
                           -
                        <?php endif; ?>
                     </span>
                  </div>
                  <div class="col-md-6">
                     <span class="text-label">Min. Berat</span>
                     <span class="text-value"><?= $kirim->min_berat ? $kirim->min_berat . ' kg' : '-' ?></span>
                  </div>
               </div>
               <div class="row mb-3">
                  <div class="col-md-6">
                     <span class="text-label">Link MOU</span>
                     <?php if (!empty($kirim->link_mou)): ?>
                        <a href="<?= $kirim->link_mou ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                           <i class="fas fa-file-contract mr-1"></i> Lihat MOU
                        </a>
                     <?php else: ?>
                        <span class="text-value text-muted">Tidak tersedia</span>
                     <?php endif; ?>
                  </div>
                  <div class="col-md-6">
                     <span class="text-label">Link Legalitas (Perjanjian Kerja Sama)</span>
                     <?php if (!empty($kirim->link_legalitas)): ?>
                        <a href="<?= $kirim->link_legalitas ?>" target="_blank" class="btn btn-sm btn-outline-success">
                           <i class="fas fa-file-alt mr-1"></i> Lihat Legalitas/PKS
                        </a>
                     <?php else: ?>
                        <span class="text-value text-muted">Tidak tersedia</span>
                     <?php endif; ?>
                  </div>
               </div>
            <?php endif; ?>
         </div>
      </div>

      <!-- Card: History Tracking -->
      <div class="card card-modern">
         <div class="card-header-modern">
            <h6><i class="fas fa-history mr-2 text-success"></i>History Tracking</h6>
         </div>
         <div class="card-body">
            <?php if (!empty($tracking_history)): ?>
               <ul class="vertical-timeline">
                  <?php foreach ($tracking_history as $history):
                     $status_name = 'Pengajuan';
                     $status_class = 'secondary';
                     $status_icon = 'fa-circle';
                     switch ($history->id_status) {
                        case 0:
                           $status_name = 'Pengajuan';
                           $status_class = 'info';
                           $status_icon = 'fa-file-alt';
                           break;
                        case 1:
                           $status_name = 'Proses Kirim';
                           $status_class = 'primary';
                           $status_icon = 'fa-shipping-fast';
                           break;
                        case 2:
                           $status_name = 'Manifest Berangkat';
                           $status_class = 'primary';
                           $status_icon = 'fa-truck-loading';
                           break;
                        case 3:
                           $status_name = 'Proses Sortir';
                           $status_class = 'warning';
                           $status_icon = 'fa-boxes';
                           break;
                        case 4:
                           $status_name = 'Pengantaran Kurir';
                           $status_class = 'warning';
                           $status_icon = 'fa-motorcycle';
                           break;
                        case 5:
                           $status_name = 'Diterima';
                           $status_class = 'success';
                           $status_icon = 'fa-check-circle';
                           break;
                        case 6:
                           $status_name = 'Menunggu Konfirmasi';
                           $status_class = 'danger';
                           $status_icon = 'fa-hourglass-half';
                           break;
                     }
                  ?>
                     <li>
                        <span class="timeline-dot" style="border-color: var(--<?= $status_class ?>); background: var(--<?= $status_class ?>);"></span>
                        <div class="timeline-content">
                           <div class="d-flex align-items-center mb-1">
                              <span class="badge badge-<?= $status_class ?> mr-2">
                                 <i class="fas <?= $status_icon ?> mr-1"></i><?= $status_name ?>
                              </span>
                           </div>
                           <ul class="list-unstyled mb-0 ml-2 small">
                              <li class="mb-1">
                                 <i class="far fa-calendar-alt text-muted mr-1"></i>
                                 <strong>Tanggal:</strong> <?= date('d-M-Y', strtotime($history->created_at)) ?>
                              </li>
                              <li class="mb-1">
                                 <i class="far fa-clock text-muted mr-1"></i>
                                 <strong>Waktu:</strong> <?= date('H:i', strtotime($history->created_at)) ?> WIB
                              </li>
                              <li class="mb-1">
                                 <i class="far fa-user text-muted mr-1"></i>
                                 <strong>Oleh:</strong> <?= $history->nama_pengguna ?: 'Sistem' ?>
                              </li>
                              <?php if (!empty($history->keterangan_konfirmasi)): ?>
                                 <li class="mb-1">
                                    <i class="far fa-comment text-muted mr-1"></i>
                                    <strong>Keterangan:</strong> <?= htmlspecialchars($history->keterangan_konfirmasi) ?>
                                 </li>
                              <?php endif; ?>
                              <?php if ($history->id_status == 5 && !empty($history->nama_penerima)): ?>
                                 <li class="mb-1">
                                    <i class="fas fa-user-check text-success mr-1"></i>
                                    <strong>Diterima oleh:</strong> <?= htmlspecialchars($history->nama_penerima) ?>
                                 </li>
                              <?php endif; ?>
                              <?php if ($history->id_status == 5 && !empty($history->tgl_penerima)): ?>
                                 <li class="mb-1">
                                    <i class="fas fa-calendar-check text-success mr-1"></i>
                                    <strong>Tgl Diterima:</strong> <?= date('d-M-Y H:i', strtotime($history->tgl_penerima)) ?>
                                 </li>
                              <?php endif; ?>
                              <?php if ($history->id_status == 5 && !empty($history->bukti_penerima)): ?>
                                 <li class="mb-1">
                                    <i class="fas fa-camera text-success mr-1"></i>
                                    <strong>Bukti:</strong>
                                    <a href="<?= $history->bukti_penerima ?>" target="_blank" class="text-primary">Lihat Bukti</a>
                                 </li>
                              <?php endif; ?>
                           </ul>
                        </div>
                     </li>
                  <?php endforeach; ?>
               </ul>
            <?php else: ?>
               <div class="text-center py-4">
                  <i class="fas fa-history fa-3x text-muted mb-3"></i>
                  <p class="text-muted mb-0">Belum ada history tracking</p>
               </div>
            <?php endif; ?>
         </div>
      </div>
   </div>

   <!-- RIGHT COLUMN: Form Pengajuan -->
   <div class="col-lg-5">
      <div class="sticky-sidebar">
         <!-- Card: Form Pengajuan -->
         <div class="card card-modern">
            <div class="card-header-modern bg-primary text-white" style="border-radius: 10px 10px 0 0;">
               <h6 class="text-white mb-0">
                  <i class="fas fa-file-invoice mr-2"></i>
                  <?= $is_revisi ? 'Form Revisi Tagihan' : 'Form Pengajuan Tagihan' ?>
               </h6>
            </div>
            <div class="card-body">
               <?php if ($is_revisi && !empty($data_lama->catatan_revisi)): ?>
                 <?php 
                    // Logika menentukan tampilan berdasarkan status
                    $is_ditolak = ($data_lama->status_approval == 99);
                    $alert_class = $is_ditolak ? 'alert-danger' : 'alert-warning';
                    $alert_icon  = $is_ditolak ? 'fa-times-circle' : 'fa-exclamation-triangle';
                    $alert_label = $is_ditolak ? 'Alasan Penolakan:' : 'Catatan Revisi:';
                 ?>
                 <div class="alert <?= $alert_class ?> shadow-sm">
                    <strong><i class="fas <?= $alert_icon ?> mr-1"></i> <?= $alert_label ?></strong><br>
                    <div class="mt-1"><?= nl2br(htmlspecialchars($data_lama->catatan_revisi)) ?></div>
                 </div>
              <?php endif; ?>

               <?= form_open('tagihan/submit_kirim', ['id' => 'form-pengajuan']) ?>
               <input type="hidden" name="id_kirim" value="<?= $kirim->id ?>">
               <input type="hidden" name="id_ekspedisi" value="<?= $kirim->id_ekspedisi ?? '' ?>">
               <input type="hidden" name="is_revisi" value="<?= $is_revisi ? 'true' : 'false' ?>">
               <?php if ($is_revisi): ?>
                  <input type="hidden" name="id_tagihan" value="<?= $data_lama->id_tagihan ?>">
               <?php endif; ?>

               <!-- No Invoice -->
               <div class="form-group">
                  <label class="text-label">No Invoice <span class="text-danger">*</span></label>
                  <input type="text" name="no_invoice" class="form-control"
                     value="<?= $is_revisi ? $data_lama->no_invoice : '' ?>" required
                     placeholder="Masukkan nomor invoice dari ekspedisi">
               </div>

               <!-- Tanggal Invoice -->
               <div class="form-group">
                  <label class="text-label">Tanggal Invoice <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_invoice" class="form-control"
                     value="<?= $is_revisi ? $data_lama->tanggal_invoice : date('Y-m-d') ?>" required>
               </div>

               <!-- Nilai Tagihan -->
               <div class="form-group">
                  <label class="text-label">Nilai Tagihan (Rp) <span class="text-danger">*</span></label>
                  <input type="text" name="nilai_tagihan" id="nilai_tagihan" class="form-control"
                     value="<?= $is_revisi ? number_format($data_lama->nilai_tagihan, 0, ',', '.') : '' ?>" required
                     placeholder="Contoh: 500.000">
               </div>

               <!-- Biaya Asuransi (Opsional) -->
               <div class="form-group">
                  <label class="text-label">Biaya Asuransi (Rp) <span class="text-muted font-weight-normal">(Opsional)</span></label>
                  <input type="text" name="biaya_asuransi" id="biaya_asuransi" class="form-control"
                     value="<?= $is_revisi && !empty($data_lama->biaya_asuransi) ? number_format($data_lama->biaya_asuransi, 0, ',', '.') : '' ?>"
                     placeholder="Contoh: 50.000">
                  <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Isi biaya asuransi jika ada dalam invoice ekspedisi.</small>
               </div>

               <!-- Total Tagihan (Auto Calculate) -->
               <div class="form-group">
                  <label class="text-label text-success"><i class="fas fa-calculator mr-1"></i>Total Tagihan (Rp)</label>
                  <input type="text" id="total_tagihan_display" class="form-control bg-light font-weight-bold" readonly
                     placeholder="0" style="font-size: 1.1rem; color: #28a745;">
                  <small class="text-success">Total = Nilai Tagihan + Biaya Asuransi (otomatis terhitung)</small>
               </div>

               <!-- Link Invoice -->
               <div class="form-group">
                  <label class="text-label">Link Invoice <span class="text-danger">*</span></label>
                  <input type="url" name="link_invoice" class="form-control"
                     value="<?= $is_revisi ? $data_lama->link_invoice : '' ?>" required
                     placeholder="Link Google Drive / Scan Invoice">
               </div>

               <!-- Link Faktur Pajak -->
               <div class="form-group">
                  <label class="text-label">Link Faktur Pajak</label>
                  <input type="url" name="link_faktur_pajak" class="form-control"
                     value="<?= $is_revisi ? $data_lama->link_faktur_pajak : '' ?>"
                     placeholder="Link faktur pajak (jika ada)">
               </div>

               <!-- Link Dokumen Lain -->
               <div class="form-group">
                  <label class="text-label">Link Dokumen Pendukung Lain</label>
                  <input type="url" name="link_dokumen_lain" class="form-control"
                     value="<?= $is_revisi ? $data_lama->link_dokumen_lain : '' ?>"
                     placeholder="Link dokumen pendukung lainnya">
               </div>

               <hr>

               <div class="d-flex justify-content-between">
                  <a href="javascript:void(0);" onclick="if(document.referrer) { window.history.back(); return false; }" class="btn btn-secondary">
                     <i class="fas fa-arrow-left mr-1"></i> Kembali
                  </a>
                  <button type="submit" class="btn btn-success">
                     <i class="fas fa-paper-plane mr-1"></i>
                     <?= $is_revisi ? 'Kirim Revisi' : 'Ajukan Tagihan' ?>
                  </button>
               </div>
               <?= form_close() ?>
            </div>
         </div>

         <!-- Info Box -->
         <div class="info-box">
            <h6 class="text-muted mb-2"><i class="fas fa-info-circle mr-1"></i>Informasi</h6>
            <ul class="mb-0 pl-3 small text-muted">
               <li>Pastikan semua dokumen sudah di-upload ke Google Drive</li>
               <li>Invoice harus sesuai dengan data pengiriman</li>
               <li>Pengajuan akan diproses melalui alur approval</li>
            </ul>
         </div>
      </div>
   </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
      // Format currency input
      const nilaiInput = document.getElementById('nilai_tagihan');
      const asuransiInput = document.getElementById('biaya_asuransi');
      const totalDisplay = document.getElementById('total_tagihan_display');

      // Function to parse formatted number
      function parseFormattedNumber(str) {
         return parseInt(str.replace(/\./g, '')) || 0;
      }

      // Function to format number
      function formatNumber(num) {
         return new Intl.NumberFormat('id-ID').format(num);
      }

      // Function to calculate and display total
      function calculateTotal() {
         let nilai = parseFormattedNumber(nilaiInput.value);
         let asuransi = asuransiInput ? parseFormattedNumber(asuransiInput.value) : 0;
         let total = nilai + asuransi;
         if (totalDisplay) {
            totalDisplay.value = formatNumber(total);
         }
      }

      // Format nilai_tagihan on input
      if (nilaiInput) {
         nilaiInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
            calculateTotal();
         });
      }

      // Format biaya_asuransi on input
      if (asuransiInput) {
         asuransiInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
            calculateTotal();
         });
      }

      // Initial calculation on page load
      calculateTotal();

      // Form validation
      document.getElementById('form-pengajuan').addEventListener('submit', function(e) {
         const requiredFields = this.querySelectorAll('[required]');
         let valid = true;

         requiredFields.forEach(function(field) {
            if (!field.value.trim()) {
               field.classList.add('is-invalid');
               valid = false;
            } else {
               field.classList.remove('is-invalid');
            }
         });

         if (!valid) {
            e.preventDefault();
            Swal.fire('Error', 'Mohon lengkapi semua field yang wajib diisi', 'error');
         }
      });
   });
</script>

<!-- Modal Detail Ekspedisi -->
<div class="modal fade" id="modalDetailEkspedisi" tabindex="-1" role="dialog" aria-labelledby="modalDetailEkspedisiLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header bg-warning">
            <h5 class="modal-title" id="modalDetailEkspedisiLabel">
               <i class="fas fa-truck mr-2"></i>Detail Ekspedisi: <?= $kirim->ekspedisi ?: '-' ?>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="row">
               <!-- Informasi Umum -->
               <div class="col-md-6">
                  <div class="card border-0 bg-light mb-3">
                     <div class="card-body">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-3">
                           <i class="fas fa-building mr-1"></i> Informasi Umum
                        </h6>
                        <table class="table table-sm table-borderless mb-0">
                           <tr>
                              <td class="text-muted" width="40%">Nama Ekspedisi</td>
                              <td><strong><?= $kirim->nama_ekspedisi ?: $kirim->ekspedisi ?: '-' ?></strong></td>
                           </tr>
                           <tr>
                              <td class="text-muted">Alamat</td>
                              <td><?= $kirim->alamat_ekspedisi ?: '-' ?></td>
                           </tr>
                           <tr>
                              <td class="text-muted">Kontak</td>
                              <td>
                                 <?php if (!empty($kirim->kontak_ekspedisi)): ?>
                                    <a href="tel:<?= $kirim->kontak_ekspedisi ?>">
                                       <i class="fas fa-phone mr-1"></i><?= $kirim->kontak_ekspedisi ?>
                                    </a>
                                 <?php else: ?>
                                    -
                                 <?php endif; ?>
                              </td>
                           </tr>
                        </table>
                     </div>
                  </div>
               </div>

               <!-- PIC & Jabatan -->
               <div class="col-md-6">
                  <div class="card border-0 bg-light mb-3">
                     <div class="card-body">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-3">
                           <i class="fas fa-user-tie mr-1"></i> PIC Ekspedisi
                        </h6>
                        <table class="table table-sm table-borderless mb-0">
                           <tr>
                              <td class="text-muted" width="40%">Nama PIC</td>
                              <td><strong><?= $kirim->pic_ekspedisi ?: '-' ?></strong></td>
                           </tr>
                           <tr>
                              <td class="text-muted">Jabatan</td>
                              <td><?= $kirim->jabatan_pic ?: '-' ?></td>
                           </tr>
                        </table>
                     </div>
                  </div>
               </div>
            </div>

            <div class="row">
               <!-- Info Kerjasama -->
               <div class="col-md-6">
                  <div class="card border-0 bg-light mb-3">
                     <div class="card-body">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-3">
                           <i class="fas fa-handshake mr-1"></i> Info Kerjasama
                        </h6>
                        <table class="table table-sm table-borderless mb-0">
                           <tr>
                              <td class="text-muted" width="40%">Status Kerjasama</td>
                              <td>
                                 <?php if (!empty($kirim->kerjasama)): ?>
                                    <?php if (strtolower($kirim->kerjasama) == 'ya' || strtolower($kirim->kerjasama) == 'aktif'): ?>
                                       <span class="badge badge-success"><?= $kirim->kerjasama ?></span>
                                    <?php else: ?>
                                       <span class="badge badge-secondary"><?= $kirim->kerjasama ?></span>
                                    <?php endif; ?>
                                 <?php else: ?>
                                    <span class="badge badge-secondary">-</span>
                                 <?php endif; ?>
                              </td>
                           </tr>
                           <tr>
                              <td class="text-muted">Payment Term</td>
                              <td>
                                 <?php if (!empty($kirim->payment_term)): ?>
                                    <span class="badge badge-info"><?= $kirim->payment_term ?></span>
                                 <?php else: ?>
                                    -
                                 <?php endif; ?>
                              </td>
                           </tr>
                           <tr>
                              <td class="text-muted">PPH 23</td>
                              <td>
                                 <?php if (!empty($kirim->pph23)): ?>
                                    <span class="badge badge-warning"><?= $kirim->pph23 ?></span>
                                 <?php else: ?>
                                    -
                                 <?php endif; ?>
                              </td>
                           </tr>
                           <tr>
                              <td class="text-muted">Min. Berat</td>
                              <td><?= !empty($kirim->min_berat) ? $kirim->min_berat . ' kg' : '-' ?></td>
                           </tr>
                        </table>
                     </div>
                  </div>
               </div>

               <!-- Coverage & Keterangan -->
               <div class="col-md-6">
                  <div class="card border-0 bg-light mb-3">
                     <div class="card-body">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-3">
                           <i class="fas fa-map-marked-alt mr-1"></i> Coverage & Info
                        </h6>
                        <table class="table table-sm table-borderless mb-0">
                           <tr>
                              <td class="text-muted" width="40%">Coverage</td>
                              <td><?= $kirim->coverage ?: '-' ?></td>
                           </tr>
                           <tr>
                              <td class="text-muted">Keterangan</td>
                              <td><?= $kirim->keterangan_ekspedisi ?: '-' ?></td>
                           </tr>
                        </table>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Dokumen -->
            <div class="card border-0 bg-light">
               <div class="card-body">
                  <h6 class="text-uppercase text-muted font-weight-bold mb-3">
                     <i class="fas fa-file-alt mr-1"></i> Dokumen Ekspedisi
                  </h6>
                  <div class="row">
                     <div class="col-md-4 text-center">
                        <div class="p-3">
                           <i class="fas fa-file-contract fa-2x mb-2 text-primary"></i>
                           <p class="mb-2 font-weight-bold">MOU</p>
                           <?php if (!empty($kirim->link_mou)): ?>
                              <a href="<?= $kirim->link_mou ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                 <i class="fas fa-external-link-alt mr-1"></i> Lihat MOU
                              </a>
                           <?php else: ?>
                              <span class="text-muted small">Tidak tersedia</span>
                           <?php endif; ?>
                        </div>
                     </div>
                     <div class="col-md-4 text-center">
                        <div class="p-3">
                           <i class="fas fa-file-alt fa-2x mb-2 text-success"></i>
                           <p class="mb-2 font-weight-bold">Legalitas</p>
                           <?php if (!empty($kirim->link_legalitas)): ?>
                              <a href="<?= $kirim->link_legalitas ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                 <i class="fas fa-external-link-alt mr-1"></i> Lihat Legalitas
                              </a>
                           <?php else: ?>
                              <span class="text-muted small">Tidak tersedia</span>
                           <?php endif; ?>
                        </div>
                     </div>
                     <div class="col-md-4 text-center">
                        <div class="p-3">
                           <i class="fas fa-search-location fa-2x mb-2 text-info"></i>
                           <p class="mb-2 font-weight-bold">Link Tracking</p>
                           <?php if (!empty($kirim->link_tracking_ekspedisi)): ?>
                              <a href="<?= $kirim->link_tracking_ekspedisi ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                 <i class="fas fa-external-link-alt mr-1"></i> Buka Tracking
                              </a>
                           <?php else: ?>
                              <span class="text-muted small">Tidak tersedia</span>
                           <?php endif; ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
               <i class="fas fa-times mr-1"></i> Tutup
            </button>
         </div>
      </div>
   </div>
</div>