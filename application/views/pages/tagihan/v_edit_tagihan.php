<header class="page-header">
   <h2><i class="fas fa-edit"></i>&nbsp;<?= $page_title ?></h2>
   <div class="right-wrapper text-left">
      <ol class="breadcrumbs">
         <li><a href="<?= base_url('tagihan') ?>"><span>Daftar Tagihan</span></a></li>
         <li><span>Edit Data</span></li>
      </ol>
   </div>
</header>

<style>
   .card {
      border: none;
      border-radius: 12px;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
   }

   .card-header {
      border-radius: 12px 12px 0 0 !important;
   }

   .form-control:focus {
      border-color: #4e73df;
      box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
   }

   .input-group-text {
      min-width: 45px;
      justify-content: center;
   }

   .info-box {
      background: linear-gradient(135deg, #f8f9fc 0%, #e9ecef 100%);
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
   }

   .info-label {
      font-size: 0.75rem;
      color: #858796;
      margin-bottom: 2px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
   }

   .info-value {
      font-size: 0.95rem;
      color: #2d3748;
      font-weight: 600;
   }

   .total-highlight {
      background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
      border: 2px solid #4caf50;
      padding: 15px;
      border-radius: 10px;
   }

   .admin-badge {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
   }
</style>

<div class="row">
   <div class="col-lg-12">

      <!-- Alert Admin Only -->
      <div class="alert alert-info d-flex justify-content-between align-items-center mb-4 shadow-sm">
         <div>
            <i class="fas fa-user-shield mr-2"></i>
            <strong>Mode Edit Administrator</strong> - Anda dapat mengubah data tagihan yang sudah diajukan/approve
         </div>
         <span class="admin-badge">
            <i class="fas fa-crown mr-1"></i> Admin Access
         </span>
      </div>

      <!-- Info Tracking -->
      <div class="card shadow mb-4">
         <div class="card-header py-3 bg-secondary text-white">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Informasi Pengiriman (Read-Only)</h6>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-3">
                  <div class="info-box">
                     <div class="info-label">No. Surat Jalan / Kode</div>
                     <div class="info-value text-primary">
                        <?php
                        $is_kirim_dokumen = (!empty($tagihan->id_kirim) && empty($tagihan->id_tracking)) || ($tagihan->tagihan_type ?? '') == 'kirim_dokumen';
                        if ($is_kirim_dokumen) {
                           echo '<i class="fas fa-envelope mr-1"></i>' . ($tagihan->kode ?? '-');
                        } else {
                           echo '<i class="fas fa-file-alt mr-1"></i>' . ($tagihan->no_sj ?? '-');
                        }
                        ?>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="info-box">
                     <div class="info-label">Ekspedisi</div>
                     <div class="info-value">
                        <i class="fas fa-truck mr-1 text-info"></i>
                        <?= $tagihan->nama_ekspedisi ?? '-' ?>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="info-box">
                     <div class="info-label">No. Resi</div>
                     <div class="info-value">
                        <i class="fas fa-receipt mr-1 text-warning"></i>
                        <?= $tagihan->no_resi ?? '-' ?>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="info-box">
                     <div class="info-label">Status Approval</div>
                     <div class="info-value">
                        <?php
                        $statusLabel = 'Unknown';
                        $badgeColor  = 'badge-secondary';

                        if ($tagihan->status_approval == 0) {
                           $statusLabel = 'PERLU REVISI';
                           $badgeColor = 'badge-danger';
                        } elseif ($tagihan->status_approval == 5) {
                           $statusLabel = 'SELESAI (APPROVED)';
                           $badgeColor = 'badge-success';
                        } elseif ($tagihan->status_approval == 99) {
                           $statusLabel = 'DITOLAK';
                           $badgeColor = 'badge-dark';
                        } else {
                           // Cari Label berdasarkan status di Config (sama seperti di list)
                           $statusLabel = 'Menunggu Approval';
                           $badgeColor  = 'badge-warning';

                           if (isset($approval_configs) && !empty($approval_configs)) {
                              foreach ($approval_configs as $cfg) {
                                 if ($cfg->status_code == $tagihan->status_approval) {
                                    $statusLabel = 'Menunggu: ' . $cfg->role_label;
                                    break;
                                 }
                              }
                           }
                        }
                        ?>
                        <span class="badge <?= $badgeColor ?>"><?= $statusLabel ?></span>
                     </div>
                  </div>
               </div>
            </div>

            <div class="row mt-2">
               <div class="col-md-4">
                  <div class="info-box">
                     <div class="info-label">Diajukan Oleh</div>
                     <div class="info-value">
                        <i class="fas fa-user mr-1 text-primary"></i> <?= $tagihan->nama_pengaju ?? '-' ?>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="info-box">
                     <div class="info-label">Tanggal Pengajuan</div>
                     <div class="info-value">
                        <i class="far fa-calendar-alt mr-1 text-success"></i>
                        <?= isset($tagihan->created_at) ? date('d M Y H:i', strtotime($tagihan->created_at)) : '-' ?>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="info-box">
                     <div class="info-label">Tipe Pengiriman</div>
                     <div class="info-value">
                        <?php if ($is_kirim_dokumen): ?>
                           <span class="badge badge-info"><i class="fas fa-envelope mr-1"></i>Kirim Dokumen</span>
                        <?php else: ?>
                           <span class="badge badge-primary"><i class="fas fa-box mr-1"></i>Tracking Barang</span>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Form Edit Tagihan -->
      <div class="card shadow mb-4">
         <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-edit mr-2"></i>Edit Data Tagihan</h6>
         </div>
         <div class="card-body">
            <form id="form-edit-tagihan">
               <input type="hidden" name="id_tagihan" value="<?= $tagihan->id_tagihan ?>">

               <div class="row">
                  <!-- Kolom Kiri -->
                  <div class="col-md-6">

                     <!-- No Invoice -->
                     <div class="form-group">
                        <label class="font-weight-bold">No. Invoice <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text bg-light"><i class="fas fa-file-invoice"></i></span>
                           </div>
                           <input type="text" class="form-control" name="no_invoice" id="no_invoice"
                              value="<?= $tagihan->no_invoice ?? '' ?>" placeholder="Contoh: INV-001/XII/2025" required>
                        </div>
                     </div>
                     
                                          <!-- Pilihan Dialihkan Ke Siapa (Status Approval) -->
                     <div class="form-group">
                        <label class="font-weight-bold">Tahap Approval / Dialihkan Ke <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text bg-info text-white"><i class="fas fa-user-check"></i></span>
                           </div>
                           <select class="form-control" name="status_approval" id="status_approval" required>
                              <?php if (isset($approval_configs) && !empty($approval_configs)): ?>
                                 <?php foreach ($approval_configs as $cfg): ?>
                                    <option value="<?= $cfg->status_code ?>" <?= ($tagihan->status_approval == $cfg->status_code) ? 'selected' : '' ?>>
                                       Menunggu: <?= $cfg->role_label ?>
                                    </option>
                                 <?php endforeach; ?>
                              <?php endif; ?>
                              <option value="0" <?= ($tagihan->status_approval == 0) ? 'selected' : '' ?>>PERLU REVISI</option>
                              <option value="5" <?= ($tagihan->status_approval == 5) ? 'selected' : '' ?>>SELESAI (APPROVED)</option>
                              <option value="99" <?= ($tagihan->status_approval == 99) ? 'selected' : '' ?>>DITOLAK</option>
                           </select>
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Pilih posisi atasan/approver jika terjadi salah pilih.</small>
                     </div>

                     <!-- No Resi (Read Only) -->
                     <div class="form-group">
                        <label class="font-weight-bold">No. Resi</label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text bg-warning text-dark"><i class="fas fa-receipt"></i></span>
                           </div>
                           <input type="text" class="form-control bg-light" readonly
                              value="<?= $tagihan->no_resi ?? '-' ?>">
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Nomor resi dari data pengiriman (tidak dapat diubah)</small>
                     </div>

                    
                     <!-- Tanggal Invoice -->
                     <div class="form-group">
                        <label class="font-weight-bold">Tanggal Invoice <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text bg-light"><i class="far fa-calendar-alt"></i></span>
                           </div>
                           <input type="date" class="form-control" name="tanggal_invoice" id="tanggal_invoice"
                              value="<?= $tagihan->tanggal_invoice ?? '' ?>" required>
                        </div>
                     </div>

                     <!-- Link Invoice -->
                     <div class="form-group">
                        <label class="font-weight-bold">Link Invoice (Google Drive) <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text bg-light"><i class="fas fa-link"></i></span>
                           </div>
                           <input type="url" class="form-control" name="link_invoice" id="link_invoice"
                              value="<?= $tagihan->link_invoice ?? '' ?>" placeholder="https://drive.google.com/..." required>
                        </div>
                        <?php if (!empty($tagihan->link_invoice)): ?>
                           <small class="text-info">
                              <a href="<?= $tagihan->link_invoice ?>" target="_blank"><i class="fas fa-external-link-alt mr-1"></i>Buka Link Saat Ini</a>
                           </small>
                        <?php endif; ?>
                     </div>

                     <!-- Link Faktur Pajak -->
                     <div class="form-group">
                        <label class="font-weight-bold">Link Faktur Pajak</label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text bg-light"><i class="fas fa-file-alt"></i></span>
                           </div>
                           <input type="url" class="form-control" name="link_faktur_pajak" id="link_faktur_pajak"
                              value="<?= $tagihan->link_faktur_pajak ?? '' ?>" placeholder="https://drive.google.com/...">
                        </div>
                        <?php if (!empty($tagihan->link_faktur_pajak)): ?>
                           <small class="text-info">
                              <a href="<?= $tagihan->link_faktur_pajak ?>" target="_blank"><i class="fas fa-external-link-alt mr-1"></i>Buka Link Saat Ini</a>
                           </small>
                        <?php endif; ?>
                     </div>

                     <!-- Link Bukti Potong -->
                     <div class="form-group">
                        <label class="font-weight-bold">Link Bukti Potong</label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text bg-light"><i class="fas fa-cut"></i></span>
                           </div>
                           <input type="url" class="form-control" name="link_bukti_potong" id="link_bukti_potong"
                              value="<?= $tagihan->link_bukti_potong ?? '' ?>" placeholder="https://drive.google.com/...">
                        </div>
                        <?php if (!empty($tagihan->link_bukti_potong)): ?>
                           <small class="text-info">
                              <a href="<?= $tagihan->link_bukti_potong ?>" target="_blank"><i class="fas fa-external-link-alt mr-1"></i>Buka Link Saat Ini</a>
                           </small>
                        <?php endif; ?>
                     </div>

                     <!-- Link Dokumen Lain -->
                     <div class="form-group">
                        <label class="font-weight-bold">Link Dokumen Lainnya</label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text bg-light"><i class="fas fa-folder-open"></i></span>
                           </div>
                           <input type="url" class="form-control" name="link_dokumen_lain" id="link_dokumen_lain"
                              value="<?= $tagihan->link_dokumen_lain ?? '' ?>" placeholder="https://drive.google.com/...">
                        </div>
                     </div>

                  </div>

                  <!-- Kolom Kanan -->
                  <div class="col-md-6">

                     <!-- Nilai Tagihan (Ongkir) -->
                     <div class="form-group">
                        <label class="font-weight-bold">Nilai Tagihan / Ongkir (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text bg-success text-white font-weight-bold">Rp</span>
                           </div>
                           <input type="text" class="form-control format-number" name="nilai_tagihan" id="nilai_tagihan"
                              value="<?= number_format($tagihan->nilai_tagihan ?? 0, 0, ',', '.') ?>" placeholder="0" required>
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Masukkan nilai ongkos kirim sesuai invoice</small>
                     </div>

                     <!-- Biaya Asuransi -->
                     <div class="form-group">
                        <label class="font-weight-bold">Biaya Asuransi (Rp)</label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text bg-info text-white font-weight-bold">Rp</span>
                           </div>
                           <input type="text" class="form-control format-number" name="biaya_asuransi" id="biaya_asuransi"
                              value="<?= number_format($tagihan->biaya_asuransi ?? 0, 0, ',', '.') ?>" placeholder="0">
                        </div>
                        <small class="text-muted"><i class="fas fa-shield-alt mr-1"></i>Isi 0 jika tidak ada biaya asuransi</small>
                     </div>

                     <!-- Total Tagihan (Read-Only Calculated) -->
                     <div class="form-group">
                        <label class="font-weight-bold text-success"><i class="fas fa-calculator mr-1"></i>Total Tagihan (Rp)</label>
                        <div class="total-highlight">
                           <div class="input-group">
                              <div class="input-group-prepend">
                                 <span class="input-group-text bg-primary text-white font-weight-bold">Rp</span>
                              </div>
                              <input type="text" class="form-control bg-white font-weight-bold text-success" id="total_tagihan_display"
                                 value="<?= number_format(($tagihan->total_tagihan ?? $tagihan->nilai_tagihan ?? 0), 0, ',', '.') ?>" readonly style="font-size: 1.3rem;">
                           </div>
                           <small class="text-success mt-1 d-block"><i class="fas fa-check-circle mr-1"></i>Total = Nilai Tagihan + Biaya Asuransi (otomatis)</small>
                        </div>
                     </div>

                     <!-- Catatan Revisi (Jika Ada) -->
                     <?php if (!empty($tagihan->catatan_revisi)): ?>
                        <div class="alert alert-warning py-2 mt-3">
                           <label class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i>Catatan Revisi Sebelumnya:</label>
                           <p class="mb-0"><?= $tagihan->catatan_revisi ?></p>
                        </div>
                     <?php endif; ?>

                  </div>
               </div>

               <hr class="my-4">

               <!-- Action Buttons -->
               <div class="d-flex justify-content-between align-items-center">
                  <a href="<?= base_url('tagihan') ?>" class="btn btn-secondary px-4">
                     <i class="fas fa-arrow-left mr-2"></i>Kembali
                  </a>
                  <div>
                     <button type="reset" class="btn btn-outline-secondary mr-2">
                        <i class="fas fa-undo mr-1"></i>Reset
                     </button>
                     <button type="submit" class="btn btn-primary px-4 font-weight-bold" id="btn-submit">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                     </button>
                  </div>
               </div>

            </form>
         </div>
      </div>

   </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   $(document).ready(function() {

      // =========================================================================
      // FORMAT NUMBER FUNCTIONS
      // =========================================================================
      function formatNumber(num) {
         return new Intl.NumberFormat('id-ID').format(num);
      }

      function parseFormattedNumber(str) {
         if (!str) return 0;
         return parseInt(str.toString().replace(/\./g, '')) || 0;
      }

      // Calculate and display total
      function calculateTotal() {
         let nilai = parseFormattedNumber($('#nilai_tagihan').val());
         let asuransi = parseFormattedNumber($('#biaya_asuransi').val());
         let total = nilai + asuransi;
         $('#total_tagihan_display').val(formatNumber(total));
      }

      // Format input on keyup for number fields
      $('.format-number').on('input', function() {
         let value = $(this).val().replace(/\D/g, '');
         $(this).val(formatNumber(value));
         calculateTotal();
      });

      // Initial calculation
      calculateTotal();

      // =========================================================================
      // FORM SUBMISSION
      // =========================================================================
      $('#form-edit-tagihan').on('submit', function(e) {
         e.preventDefault();

         let btn = $('#btn-submit');
         let originalText = btn.html();

         // Validasi
         let nilai = parseFormattedNumber($('#nilai_tagihan').val());
         if (nilai <= 0) {
            Swal.fire('Peringatan', 'Nilai Tagihan harus lebih dari 0!', 'warning');
            return;
         }

         if (!$('#no_invoice').val().trim()) {
            Swal.fire('Peringatan', 'No. Invoice wajib diisi!', 'warning');
            return;
         }

         if (!$('#link_invoice').val().trim()) {
            Swal.fire('Peringatan', 'Link Invoice wajib diisi!', 'warning');
            return;
         }

         // Konfirmasi
         Swal.fire({
            title: 'Konfirmasi Perubahan',
            text: 'Apakah Anda yakin ingin menyimpan perubahan data tagihan ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-save mr-1"></i> Ya, Simpan',
            cancelButtonText: 'Batal'
         }).then((result) => {
            if (result.isConfirmed) {
               // Loading State
               btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

               $.ajax({
                  url: '<?= base_url("tagihan/update_tagihan") ?>',
                  type: 'POST',
                  data: $(this).serialize(),
                  dataType: 'json',
                  success: function(res) {
                     if (res.status == 'success') {
                        Swal.fire({
                           title: 'Berhasil!',
                           text: res.message,
                           icon: 'success',
                           timer: 2000,
                           showConfirmButton: false
                        }).then(() => {
                           window.location.href = '<?= base_url("tagihan") ?>';
                        });
                     } else {
                        Swal.fire('Gagal', res.message, 'error');
                        btn.prop('disabled', false).html(originalText);
                     }
                  },
                  error: function(xhr) {
                     console.log(xhr.responseText);
                     Swal.fire('Error', 'Terjadi kesalahan server. Silakan coba lagi.', 'error');
                     btn.prop('disabled', false).html(originalText);
                  }
               });
            }
         });
      });

   });
</script>