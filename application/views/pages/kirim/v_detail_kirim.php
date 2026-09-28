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
      position: relative;
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

   .btn-edit-top {
      position: absolute;
      top: 20px;
      right: 25px;
      background: rgba(255, 255, 255, 0.2);
      border: 2px solid #fff;
      color: #fff;
      font-weight: 600;
      padding: 8px 20px;
      border-radius: 8px;
      transition: all 0.3s;
   }

   .btn-edit-top:hover {
      background: #fff;
      color: #667eea;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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

   .action-buttons {
      padding: 20px 25px;
      background: #f8f9fa;
      border-radius: 0 0 12px 12px;
      text-align: left;
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
         <!-- Header dengan tombol edit -->
         <div class="tracking-header">
            <span class="badge badge-light mb-2">Detail Kirim Dokumen</span>
            <h3>Data Pengiriman Dokumen</h3>
            <div class="kode"><?= $data_tracking[0]->kode ?></div>

            <?php if (in_array(sessPenggunaId(), [1, 15, 33, 7, 73, 749])): ?>
               <button type="button" class="btn btn-edit-top btn-edit" data-id="<?= encrypt($data_tracking[0]->id) ?>">
                  <i class="fas fa-edit mr-1"></i> Edit Data
               </button>
            <?php endif; ?>
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
            <div class="row">
               <div class="col-md-12 mb-3">
                  <div class="info-label">Status Saat Ini</div>
                  <span class="status-badge <?= $status_class ?>"><?= $status_text ?></span>
               </div>
               <div class="col-md-12">
                  <div class="info-label">Keterangan</div>
                  <div class="info-value"><?= nl2br(htmlspecialchars($data_tracking[0]->keterangan ?: '-')) ?></div>
               </div>
            </div>
         </div>

         <!-- Timeline Tracking (READ ONLY) -->
         <?php if (!empty($data_status)) { ?>
            <div class="info-section">
               <h6 class="text-uppercase text-muted font-weight-bold mb-3">
                  <i class="fas fa-history mr-2"></i>History Tracking
               </h6>
               <ul class="timeline-3">
                  <?php
                  foreach ($data_status as $each) {
                     $dari = date_create($each->created_at);
                     $sampai = date_create();
                     $diff  = date_diff($dari, $sampai);

                     if ($each->id_status == 0) {
                        $status_history = "Pending";
                     } else if ($each->id_status == 1) {
                        $status_history = "Proses Kirim";
                     } else if ($each->id_status == 2) {
                        $status_history = "Pickup";
                     } else if ($each->id_status == 3) {
                        $status_history = "On Transit";
                     } else if ($each->id_status == 4) {
                        $status_history = "Out for Delivery";
                     } else if ($each->id_status == 5) {
                        $status_history = "Diterima";
                     }
                  ?>
                     <li>
                        <a><b><?php echo $status_history ?></b></a>
                        <a class="float-right"><?php echo date('d-M-Y | H:i:s', strtotime($each->created_at)) ?></a>
                        <p class="mt-2"><?php echo "Update oleh : " . ($each->nama_pembuat ?? 'System'); ?></p>

                        <?php if ($each->keterangan_konfirmasi != "") { ?>
                           <a><b><?php echo "Keterangan : " . $each->keterangan_konfirmasi; ?></b></a>
                        <?php } ?>

                        <?php if ($each->nama_penerima != "") { ?>
                           <a><b><?php echo "Nama Penerima : " . $each->nama_penerima; ?></b></a>
                        <?php } ?>

                        <br>
                        <?php if ($each->tgl_penerima != "") { ?>
                           <a class="float-left"><?php echo "Tanggal Penerimaan : " . date('d-M-Y', strtotime($each->tgl_penerima)); ?></a>
                        <?php } ?>
                        <br><br>
                        <?php if ($each->bukti_penerima != "") { ?>
                           <a href="<?= $each->bukti_penerima ?>" target="_blank" class="btn btn-primary float-left" style="margin-left:0px;">
                              <i class="fas fa-info"></i>&nbsp;&nbsp;Bukti Penerimaan
                           </a>
                           <br><br>
                        <?php } ?>
                     </li>
                  <?php } ?>
               </ul>
            </div>
         <?php } ?>

         <!-- Action Buttons -->
         <div class="action-buttons">
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-action">
               <i class="fas fa-arrow-left mr-1"></i> Kembali
            </button>
         </div>
      </div>
   </div>
</div>

<!-- Modal Edit Data -->
<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header bg-dark text-light">
            <h5 id="modal-label"><i class="fas fa-edit mr-2"></i>Edit Data Kirim Dokumen</h5>
            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="nama_customer" class="form-control-label">Nama Customer <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="nama_customer" name="nama_customer" required>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="pic" class="form-control-label">PIC Penerima <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="pic" name="pic" required>
                  </div>
               </div>
            </div>

            <div class="form-group">
               <label for="alamat" class="form-control-label">Alamat Penerima <span class="text-danger">*</span></label>
               <textarea class="form-control" id="alamat" name="alamat" rows="2" required></textarea>
            </div>

            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="asal" class="form-control-label">Asal Pengiriman <span class="text-danger">*</span></label>
                     <select class="form-control" id="asal" name="asal" required>
                        <option value="">- Pilih Asal -</option>
                        <option value="1">Kantor Pekanbaru</option>
                        <option value="2">Gudang Pekanbaru</option>
                        <option value="3">Gudang Jakarta</option>
                        <option value="4">Kantor Yogyakarta</option>
                        <option value="5">Kantor Axa Jakarta</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="marketing" class="form-control-label">Marketing <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="marketing" name="marketing" required>
                  </div>
               </div>
            </div>

            <hr class="my-4">
            <h6 class="mb-3"><strong>Informasi Pengiriman</strong></h6>

            <div class="form-group">
               <label for="ekspedisi" class="form-control-label">Pilih Ekspedisi</label>
               <select data-plugin-selectTwo class="form-control populate" id="ekspedisi" name="ekspedisi">
                  <option value="">- Pilih Ekspedisi -</option>
                  <?php foreach ($list_eks as $row): ?>
                     <option value="<?= $row->nama_ekspedisi ?>"><?= $row->nama_ekspedisi ?></option>
                  <?php endforeach; ?>
               </select>
            </div>

            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="tgl_kirim" class="form-control-label">Tanggal Pengiriman</label>
                     <div class="input-group">
                        <div class="input-group-prepend">
                           <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" class="form-control datepicker" id="tgl_kirim" name="tgl_kirim" placeholder="dd-mm-yyyy">
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="tgl_sampai" class="form-control-label">Estimasi Sampai</label>
                     <div class="input-group">
                        <div class="input-group-prepend">
                           <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" class="form-control datepicker" id="tgl_sampai" name="tgl_sampai" placeholder="dd-mm-yyyy">
                     </div>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="no_resi" class="form-control-label">No Resi</label>
                     <input type="text" class="form-control" id="no_resi" name="no_resi">
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="link_resi" class="form-control-label">Link Resi</label>
                     <input type="text" class="form-control" id="link_resi" name="link_resi" placeholder="Link Google Drive">
                  </div>
               </div>
            </div>

            <div class="form-group">
               <label for="keterangan" class="form-control-label">Keterangan</label>
               <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
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
   function goBack() {
      window.history.back();
   }

   document.addEventListener('DOMContentLoaded', function() {
      // Initialize datepicker
      $('.datepicker').datepicker({
         format: 'dd-mm-yyyy',
         autoclose: true,
         todayHighlight: true
      });

      // Handle edit button click
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
               $('#main-modal #nama_customer').val(data[0].nama_customer);
               $('#main-modal #pic').val(data[0].pic);
               $('#main-modal #alamat').val(data[0].alamat);
               $('#main-modal #asal').val(data[0].asal);
               $('#main-modal #marketing').val(data[0].marketing);
               $('#main-modal #ekspedisi').val(data[0].ekspedisi).trigger('change');
               $('#main-modal #tgl_kirim').val(data[0].tgl_kirim);
               $('#main-modal #tgl_sampai').val(data[0].tgl_sampai);
               $('#main-modal #no_resi').val(data[0].no_resi);
               $('#main-modal #link_resi').val(data[0].link_resi);
               $('#main-modal #keterangan').val(data[0].keterangan);
               $('#main-modal #id_kirim').val(id);
            });
      });

      // Handle save button
      $('.btn-save').click(function() {
         var formData = $('#modal-form').serialize();
         $.ajax({
            url: '<?= base_url() ?>kirim/updateUtama',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
               if (response.status === 'success') {
                  $('#main-modal').modal('hide');
                  Swal.fire('Berhasil', response.message, 'success').then(() => {
                     location.reload();
                  });
               } else {
                  Swal.fire('Error', response.message, 'error');
               }
            },
            error: function(xhr, status, error) {
               Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
            }
         });
      });
   });
</script>

<link rel="stylesheet" href="<?= base_url('assets/css/timeline.css') ?>">