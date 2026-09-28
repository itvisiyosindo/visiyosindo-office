<header class="page-header">
   <h2><i class="fas fa-cog"></i>&nbsp;<?= $page_title ?></h2>
   <div class="right-wrapper text-left">
      <ol class="breadcrumbs">
         <li><a href="<?= base_url('spp') ?>"><span>SPP</span></a></li>
         <li><span>Konfigurasi</span></li>
      </ol>
   </div>
</header>

<style>
   .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
      margin-bottom: 24px;
   }

   .config-item {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      border: 1px solid #e9ecef;
   }

   .config-item:last-child {
      margin-bottom: 0;
   }

   .level-badge {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 1.1rem;
   }

   .text-label {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #8898aa;
      font-weight: 700;
   }

   .log-item {
      border-left: 3px solid #dee2e6;
      padding-left: 15px;
      margin-bottom: 15px;
   }

   .log-item:last-child {
      margin-bottom: 0;
   }
</style>

<div class="row">
   <!-- Config Approval -->
   <div class="col-lg-6 col-md-12">
      <div class="card">
         <div class="card-header bg-primary text-white">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-sliders-h mr-2"></i>Konfigurasi Level Approval</h6>
         </div>
         <div class="card-body">
            <p class="text-muted mb-4">
               <i class="fas fa-info-circle mr-1"></i>
               Atur urutan approval dan nomor WhatsApp untuk notifikasi. Urutan approval mengikuti level_order dari kecil ke besar.
            </p>

            <?php foreach ($configs as $cfg): ?>
               <div class="config-item">
                  <form class="form-config" data-id="<?= $cfg->id_config ?>">
                     <div class="d-flex align-items-start">
                        <div class="level-badge mr-3"><?= $cfg->level_order ?></div>
                        <div class="flex-grow-1">
                           <div class="row">
                              <div class="col-md-6 mb-2">
                                 <label class="text-label">Nama Role</label>
                                 <input type="text" class="form-control form-control-sm" name="role_label" value="<?= $cfg->role_label ?>">
                              </div>
                              <div class="col-md-6 mb-2">
                                 <label class="text-label">No. WA Notifikasi</label>
                                 <input type="text" class="form-control form-control-sm" name="notif_number" value="<?= $cfg->notif_number ?>" placeholder="628xxx">
                              </div>
                              <div class="col-md-6 mb-2">
                                 <label class="text-label">Status</label>
                                 <select class="form-control form-control-sm" name="is_active">
                                    <option value="1" <?= $cfg->is_active == 1 ? 'selected' : '' ?>>Aktif</option>
                                    <option value="0" <?= $cfg->is_active == 0 ? 'selected' : '' ?>>Nonaktif</option>
                                 </select>
                              </div>
                              <div class="col-md-6 mb-2">
                                 <label class="text-label">Bisa Edit Data</label>
                                 <select class="form-control form-control-sm" name="can_edit">
                                    <option value="0" <?= $cfg->can_edit == 0 ? 'selected' : '' ?>>Tidak</option>
                                    <option value="1" <?= $cfg->can_edit == 1 ? 'selected' : '' ?>>Ya</option>
                                 </select>
                              </div>
                           </div>
                           <div class="text-right mt-2">
                              <button type="submit" class="btn btn-sm btn-primary">
                                 <i class="fas fa-save mr-1"></i> Simpan
                              </button>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
   </div>

   <!-- Log Notifikasi -->
   <div class="col-lg-6 col-md-12">
      <div class="card">
         <div class="card-header bg-info text-white">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-history mr-2"></i>Riwayat Notifikasi WA</h6>
         </div>
         <div class="card-body" style="max-height: 600px; overflow-y: auto;">
            <?php if (empty($logs)): ?>
               <div class="text-center py-4 text-muted">
                  <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                  Belum ada riwayat notifikasi
               </div>
            <?php else: ?>
               <?php foreach ($logs as $log): ?>
                  <div class="log-item">
                     <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                           <span class="font-weight-bold text-primary"><?= $log->no_spp ?? 'SPP #' . $log->id_spp ?></span>
                           <span class="badge badge-secondary ml-2"><?= $log->target_role ?></span>
                        </div>
                        <small class="text-muted"><?= date('d M Y H:i', strtotime($log->created_at)) ?></small>
                     </div>
                     <div class="mb-2">
                        <small class="text-muted">Dikirim ke: <strong><?= $log->target_number ?></strong></small>
                        <?php if ($log->resend_count > 0): ?>
                           <span class="badge badge-warning ml-2">Resend: <?= $log->resend_count ?>x</span>
                        <?php endif; ?>
                     </div>
                     <div class="text-right">
                        <a href="javascript:void(0)" class="btn btn-outline-success btn-sm btn-resend" data-url="<?= base_url('spp/resend_wa/' . $log->id_log) ?>" data-number="<?= $log->target_number ?>">
                           <i class="fas fa-paper-plane mr-1"></i> Kirim Ulang
                        </a>
                     </div>
                  </div>
               <?php endforeach; ?>
            <?php endif; ?>
         </div>
      </div>
   </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   $(document).ready(function() {
      // Handle Resend Button dengan SweetAlert
      $('.btn-resend').click(function(e) {
         e.preventDefault();
         let url = $(this).data('url');
         let number = $(this).data('number');

         Swal.fire({
            title: 'Kirim Ulang Notifikasi?',
            html: 'Notifikasi akan dikirim ulang ke nomor:<br><strong>' + number + '</strong>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-paper-plane"></i> Ya, Kirim Ulang',
            cancelButtonText: 'Batal'
         }).then((result) => {
            if (result.isConfirmed) {
               Swal.fire({
                  title: 'Mengirim...',
                  html: 'Mohon tunggu, sedang mengirim notifikasi WhatsApp',
                  allowOutsideClick: false,
                  didOpen: () => {
                     Swal.showLoading();
                  }
               });
               window.location.href = url;
            }
         });
      });

      // Handle form submit
      $('.form-config').submit(function(e) {
         e.preventDefault();

         let form = $(this);
         let id = form.data('id');
         let btn = form.find('button[type="submit"]');
         let originalText = btn.html();

         btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

         $.ajax({
            url: '<?= base_url("spp/update_setting") ?>',
            type: 'POST',
            data: form.serialize() + '&id_config=' + id,
            dataType: 'json',
            success: function(res) {
               if (res.status == 'success') {
                  Swal.fire({
                     icon: 'success',
                     title: 'Berhasil!',
                     text: res.message,
                     timer: 1500,
                     showConfirmButton: false
                  });
               } else {
                  Swal.fire('Gagal', res.message, 'error');
               }
               btn.prop('disabled', false).html(originalText);
            },
            error: function() {
               Swal.fire('Error', 'Terjadi kesalahan server', 'error');
               btn.prop('disabled', false).html(originalText);
            }
         });
      });
   });
</script>