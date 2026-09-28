<header class="page-header">
   <h2><i class="icons fas fa-paper-plane"></i>&nbsp;<?= $page_title ?></h2>
   <div class="right-wrapper text-left">
      <ol class="breadcrumbs">
         <li><span><?= $page_desc ?></span></li>
      </ol>
   </div>
</header>

<style>
   :root {
      --primary: #3498db;
      --secondary: #6c757d;
      --success: #2ecc71;
      --warning: #f1c40f;
      --danger: #e74c3c;
      --info: #17a2b8;
   }

   .tracking-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
      background: #fff;
      margin-bottom: 24px;
   }

   .tracking-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #fff;
      padding: 25px;
      border-radius: 12px 12px 0 0;
      text-align: center;
   }

   .tracking-header h3 {
      margin: 0;
      font-weight: 700;
   }

   .tracking-header .kode {
      font-size: 1.5rem;
      font-weight: 800;
      margin-top: 10px;
   }

   .info-section {
      padding: 20px 25px;
      border-bottom: 1px solid #f0f0f0;
   }

   .info-section:last-child {
      border-bottom: none;
   }

   .info-label {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #8898aa;
      font-weight: 700;
      margin-bottom: 5px;
   }

   .info-value {
      font-size: 1rem;
      color: #32325d;
      font-weight: 600;
   }

   .status-badge {
      display: inline-block;
      padding: 8px 16px;
      border-radius: 20px;
      font-weight: 700;
      font-size: 0.85rem;
   }

   .status-badge.pending {
      background: #ffeaa7;
      color: #d68910;
   }

   .status-badge.proses {
      background: #74b9ff;
      color: #0056b3;
   }

   .status-badge.selesai {
      background: #00b894;
      color: #fff;
   }

   /* Timeline Styling */
   .timeline-tracking {
      position: relative;
      padding: 20px 0;
   }

   .timeline-tracking::before {
      content: '';
      position: absolute;
      left: 15px;
      top: 0;
      bottom: 0;
      width: 3px;
      background: #e9ecef;
   }

   .timeline-item {
      position: relative;
      padding-left: 45px;
      margin-bottom: 25px;
   }

   .timeline-item:last-child {
      margin-bottom: 0;
   }

   .timeline-dot {
      position: absolute;
      left: 6px;
      top: 0;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: #fff;
      border: 3px solid #dee2e6;
      z-index: 1;
   }

   .timeline-item:first-child .timeline-dot {
      border-color: #00b894;
      background: #00b894;
   }

   .timeline-content {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      border-left: 3px solid #667eea;
   }

   .timeline-title {
      font-weight: 700;
      color: #32325d;
      margin-bottom: 5px;
   }

   .timeline-date {
      font-size: 0.8rem;
      color: #8898aa;
   }

   .timeline-desc {
      font-size: 0.9rem;
      color: #525f7f;
      margin-top: 8px;
   }

   .action-buttons {
      padding: 20px 25px;
      background: #f8f9fa;
      border-radius: 0 0 12px 12px;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
   }

   .btn-action {
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s;
   }

   .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
   }
</style>

<?php
// Determine status text
$status_text = 'Baru Diajukan';
$status_class = 'pending';

if (isset($data_status[0]->id_status)) {
   switch ($data_status[0]->id_status) {
      case 0:
         $status_text = 'Baru Diajukan';
         $status_class = 'pending';
         break;
      case 1:
         $status_text = 'Proses Kirim';
         $status_class = 'proses';
         break;
      case 2:
         $status_text = 'Manifest Berangkat';
         $status_class = 'proses';
         break;
      case 3:
         $status_text = 'Proses Sortir';
         $status_class = 'proses';
         break;
      case 4:
         $status_text = 'Pengantaran Kurir';
         $status_class = 'proses';
         break;
      case 5:
         $status_text = 'Diterima';
         $status_class = 'selesai';
         break;
      case 6:
         $status_text = 'Menunggu Konfirmasi';
         $status_class = 'proses';
         break;
   }
}

// Determine asal
$asal_text = 'Tidak Diketahui';
if (isset($data_tracking[0]->asal)) {
   switch ($data_tracking[0]->asal) {
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

// Check if ekspedisi is TIKI
$is_tiki = (stripos($data_tracking[0]->ekspedisi ?? '', 'tiki') !== false);
?>

<div class="row">
   <div class="col-lg-8 mx-auto">
      <div class="tracking-card">
         <!-- Header -->
         <div class="tracking-header">
            <span class="badge badge-light mb-2">Tracking Kirim Dokumen</span>
            <h3>Data Pengiriman Dokumen</h3>
            <div class="kode"><?= $data_tracking[0]->kode ?></div>
         </div>

         <!-- Info Customer -->
         <div class="info-section">
            <div class="row">
               <div class="col-md-6 mb-3">
                  <div class="info-label">Nama Customer</div>
                  <div class="info-value"><?= $data_tracking[0]->nama_customer ?></div>
               </div>
               <div class="col-md-6 mb-3">
                  <div class="info-label">PIC Penerima</div>
                  <div class="info-value"><?= $data_tracking[0]->pic ?></div>
               </div>
            </div>
            <div class="mb-3">
               <div class="info-label">Alamat Penerima</div>
               <div class="info-value"><?= nl2br(htmlspecialchars($data_tracking[0]->alamat)) ?></div>
            </div>
            <div class="row">
               <div class="col-md-6 mb-3">
                  <div class="info-label">Asal Pengiriman</div>
                  <div class="info-value"><?= $asal_text ?></div>
               </div>
               <div class="col-md-6 mb-3">
                  <div class="info-label">Marketing</div>
                  <div class="info-value"><?= $data_tracking[0]->marketing ?></div>
               </div>
            </div>
         </div>

         <!-- Info Pengiriman -->
         <div class="info-section">
            <h6 class="text-uppercase text-muted font-weight-bold mb-3">
               <i class="fas fa-shipping-fast mr-2"></i>Informasi Pengiriman
            </h6>
            <div class="row">
               <div class="col-md-6 mb-3">
                  <div class="info-label">Ekspedisi</div>
                  <div class="info-value"><?= $data_tracking[0]->ekspedisi ?: '-' ?></div>
               </div>
               <div class="col-md-6 mb-3">
                  <div class="info-label">No Resi</div>
                  <div class="info-value"><?= $data_tracking[0]->no_resi ?: '-' ?></div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 mb-3">
                  <div class="info-label">Tanggal Pengiriman</div>
                  <div class="info-value">
                     <?= !empty($data_tracking[0]->tgl_kirim) ? date('d-M-Y', strtotime($data_tracking[0]->tgl_kirim)) : '-' ?>
                  </div>
               </div>
               <div class="col-md-6 mb-3">
                  <div class="info-label">Estimasi Sampai</div>
                  <div class="info-value">
                     <?= !empty($data_tracking[0]->tgl_sampai) ? date('d-M-Y', strtotime($data_tracking[0]->tgl_sampai)) : '-' ?>
                  </div>
               </div>
            </div>
            <?php if (!empty($data_tracking[0]->link_resi)): ?>
               <div class="mb-3">
                  <div class="info-label">Link Resi</div>
                  <a href="<?= $data_tracking[0]->link_resi ?>" target="_blank" class="btn btn-sm btn-info">
                     <i class="fas fa-external-link-alt mr-1"></i> Cek Resi
                  </a>
               </div>
            <?php endif; ?>
            <?php if (!empty($data_tracking[0]->link_doc)): ?>
               <div class="mb-3">
                  <div class="info-label">Link Dokumen</div>
                  <a href="<?= $data_tracking[0]->link_doc ?>" target="_blank" class="btn btn-sm btn-primary">
                     <i class="fas fa-file-alt mr-1"></i> Lihat Dokumen
                  </a>
               </div>
            <?php endif; ?>
         </div>

         <!-- Status -->
         <div class="info-section">
            <div class="row align-items-center">
               <div class="col-md-6">
                  <div class="info-label">Status Saat Ini</div>
                  <span class="status-badge <?= $status_class ?>"><?= $status_text ?></span>
               </div>
               <div class="col-md-6">
                  <div class="info-label">Keterangan</div>
                  <div class="info-value"><?= nl2br(htmlspecialchars($data_tracking[0]->keterangan ?: '-')) ?></div>
               </div>
            </div>
         </div>

         <!-- Timeline Tracking -->
         <div class="info-section">
            <h6 class="text-uppercase text-muted font-weight-bold mb-3">
               <i class="fas fa-history mr-2"></i>History Tracking
            </h6>
            <div class="timeline-tracking">
               <?php foreach ($data_status as $each):
                  $status_name = 'Pengajuan';
                  $status_class = 'secondary';
                  $status_icon = 'fa-circle';
                  switch ($each->id_status) {
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
                  <div class="timeline-item">
                     <div class="timeline-dot" style="background: var(--<?= $status_class ?>, #6c757d);"></div>
                     <div class="timeline-content">
                        <div class="d-flex align-items-center mb-2">
                           <span class="badge badge-<?= $status_class ?> mr-2">
                              <i class="fas <?= $status_icon ?> mr-1"></i><?= $status_name ?>
                           </span>
                        </div>
                        <ul class="list-unstyled mb-0 small">
                           <li class="mb-1">
                              <i class="far fa-calendar-alt text-muted mr-1"></i>
                              <strong>Tanggal:</strong> <?= date('d-M-Y', strtotime($each->created_at)) ?>
                           </li>
                           <li class="mb-1">
                              <i class="far fa-clock text-muted mr-1"></i>
                              <strong>Waktu:</strong> <?= date('H:i', strtotime($each->created_at)) ?> WIB
                           </li>
                           <li class="mb-1">
                              <i class="far fa-user text-muted mr-1"></i>
                              <strong>Oleh:</strong> <?= $each->nama_pembuat ?: 'Sistem' ?>
                           </li>
                           <?php if (!empty($each->keterangan_konfirmasi)): ?>
                              <li class="mb-1">
                                 <i class="far fa-comment text-muted mr-1"></i>
                                 <strong>Keterangan:</strong> <?= htmlspecialchars($each->keterangan_konfirmasi) ?>
                              </li>
                           <?php endif; ?>
                           <?php if (!empty($each->nama_penerima)): ?>
                              <li class="mb-1">
                                 <i class="fas fa-user-check text-success mr-1"></i>
                                 <strong>Diterima oleh:</strong> <?= htmlspecialchars($each->nama_penerima) ?>
                              </li>
                           <?php endif; ?>
                           <?php if (!empty($each->tgl_penerima)): ?>
                              <li class="mb-1">
                                 <i class="fas fa-calendar-check text-success mr-1"></i>
                                 <strong>Tgl Diterima:</strong> <?= date('d-M-Y H:i', strtotime($each->tgl_penerima)) ?>
                              </li>
                           <?php endif; ?>
                           <?php if (!empty($each->bukti_penerima)): ?>
                              <li class="mb-1">
                                 <i class="fas fa-camera text-primary mr-1"></i>
                                 <strong>Bukti:</strong>
                                 <a href="<?= $each->bukti_penerima ?>" target="_blank" class="btn btn-sm btn-outline-primary ml-1">
                                    <i class="fas fa-external-link-alt mr-1"></i>Lihat Bukti
                                 </a>
                              </li>
                           <?php endif; ?>
                        </ul>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>

         <!-- Action Buttons -->
         <div class="action-buttons">
            <div>
               <button type="button" onclick="goBack()" class="btn btn-secondary btn-action">
                  <i class="fas fa-arrow-left mr-1"></i> Kembali
               </button>
            </div>
            <div>
               <?php
               $canEdit = (sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId() == 73 || sessPenggunaId() == 749);
               $canAjukanTagihan = ($data_status[0]->id_status == 5 && !empty($data_tracking[0]->ekspedisi) && strtolower($data_tracking[0]->ekspedisi) !== 'diantarkan langsung');
               ?>

               <?php if ($canAjukanTagihan): ?>
                  <a href="<?= base_url('kirim/ajukan_tagihan/' . encrypt($data_tracking[0]->id)) ?>"
                     class="btn btn-warning btn-action">
                     <i class="fas fa-file-invoice-dollar mr-1"></i> Ajukan Tagihan Ekspedisi
                  </a>
               <?php endif; ?>

               <?php if ($canEdit): ?>
                  <button type="button" class="btn btn-success btn-action btn-edit" data-id="<?= encrypt($data_tracking[0]->id) ?>">
                     <i class="fas fa-edit mr-1"></i> Update Status
                  </button>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Modal Update Status -->
<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header bg-dark text-light">
            <h5 id="modal-label"><i class="fas fa-edit mr-2"></i>Update Status Kirim Dokumen</h5>
            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
         <div class="modal-body">
            <div class="form-group">
               <label for="ekspedisi" class="form-control-label">Pilih Ekspedisi <span class="text-danger">*</span></label>
               <select data-plugin-selectTwo class="form-control populate" id="ekspedisi" name="ekspedisi" required>
                  <option value="">- Pilih Ekspedisi -</option>
                  <?php foreach ($list_eks as $row): ?>
                     <option value="<?= $row->nama_ekspedisi ?>"><?= $row->nama_ekspedisi ?></option>
                  <?php endforeach; ?>
               </select>
            </div>

            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="tgl_kirim" class="form-control-label">Tanggal Pengiriman <span class="text-danger">*</span></label>
                     <div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "dd-mm-yyyy"}'>
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" class="form-control" id="tgl_kirim" name="tgl_kirim" required>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="tgl_sampai" class="form-control-label">Estimasi Sampai <span class="text-danger">*</span></label>
                     <div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "dd-mm-yyyy"}'>
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" class="form-control" id="tgl_sampai" name="tgl_sampai" required>
                     </div>
                  </div>
               </div>
            </div>

            <div class="form-group">
               <label for="no_resi" class="form-control-label">No Resi <span class="text-danger">*</span></label>
               <input type="text" class="form-control" id="no_resi" name="no_resi" required>
            </div>

            <div class="form-group">
               <label for="link_resi" class="form-control-label">Link Resi</label>
               <input type="text" class="form-control" id="link_resi" name="link_resi" placeholder="Masukkan link Google Drive resi (opsional)">
            </div>

            <div class="form-group">
               <label for="status" class="form-control-label">Status <span class="text-danger">*</span></label>
               <select class="form-control" id="status" name="status" required onchange="toggleFormStatus()">
                  <option value="">- Pilih Status -</option>
                  <option value="1">Proses Kirim</option>
                  <option value="2">Manifest Berangkat</option>
                  <option value="3">Proses Sortir</option>
                  <option value="6">Menunggu Konfirmasi</option>
                  <option value="4">Pengantaran Kurir</option>
                  <option value="5">Diterima</option>
               </select>
            </div>

            <!-- Form fields untuk status Diterima -->
            <div id="form_nama_penerima" class="form-group" style="display:none;">
               <label for="nama_penerima" class="form-control-label">Nama Penerima <span class="text-danger">*</span></label>
               <input type="text" class="form-control" id="nama_penerima" name="nama_penerima">
            </div>
            <div id="form_tgl_penerima" class="form-group" style="display:none;">
               <label for="tgl_penerima" class="form-control-label">Tanggal Penerimaan <span class="text-danger">*</span></label>
               <div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "dd-mm-yyyy"}'>
                  <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                  <input type="text" class="form-control" id="tgl_penerima" name="tgl_penerima">
               </div>
            </div>
            <div id="form_bukti_penerima" class="form-group" style="display:none;">
               <label for="bukti_penerima" class="form-control-label">Bukti Penerimaan (Link)</label>
               <textarea class="form-control" id="bukti_penerima" name="bukti_penerima" placeholder="Link foto bukti penerimaan"></textarea>
            </div>
            <div id="form_keterangan_konfirmasi" class="form-group" style="display:none;">
               <label for="keterangan_konfirmasi" class="form-control-label">Keterangan</label>
               <textarea class="form-control" id="keterangan_konfirmasi" name="keterangan_konfirmasi"></textarea>
            </div>
         </div>
         <div class="modal-footer">
            <input type="hidden" id="id_kirim" name="id_kirim">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-success btn-save">Simpan</button>
         </div>
         <?= form_close(); ?>
      </div>
   </div>
</div>

<script>
   function toggleFormStatus() {
      var status = document.getElementById("status").value;
      var formNamaPenerima = document.getElementById("form_nama_penerima");
      var formTglPenerima = document.getElementById("form_tgl_penerima");
      var formBukti = document.getElementById("form_bukti_penerima");
      var formKet = document.getElementById("form_keterangan_konfirmasi");

      if (status === "5") {
         formNamaPenerima.style.display = "block";
         formTglPenerima.style.display = "block";
         formBukti.style.display = "block";
         formKet.style.display = "none";
      } else {
         formNamaPenerima.style.display = "none";
         formTglPenerima.style.display = "none";
         formBukti.style.display = "none";
         formKet.style.display = "block";
      }
   }

   function goBack() {
      window.history.back();
   }

   document.addEventListener('DOMContentLoaded', function() {
      $(document).on('click', '.btn-edit', function() {
         var object = 'kirim';
         $('#main-modal #modal-form').attr('action', 'kirim/updateUtama');
         $('#main-modal').modal();

         var id = $(this).attr("data-id");
         fetch('<?= base_url() ?>' + object + '/edit/' + id)
            .then(function(resp) {
               return resp.json();
            })
            .then(function(data) {
               $('#main-modal #ekspedisi').val(data[0].ekspedisi).trigger('change');
               $('#main-modal #tgl_kirim').val(data[0].tgl_kirim);
               $('#main-modal #tgl_sampai').val(data[0].tgl_sampai);
               $('#main-modal #no_resi').val(data[0].no_resi);
               $('#main-modal #link_resi').val(data[0].link_resi);
               $('#main-modal #id_kirim').val(id);
            });
      });
   });
</script>