<?php
$status_now = $spp->status_approval;
$is_revisi = ($status_now == 0);
$is_ditolak = ($status_now == 99);
?>

<header class="page-header">
   <h2><i class="fas fa-edit"></i> <?= $page_title ?></h2>
   <div class="right-wrapper text-left">
      <ol class="breadcrumbs">
         <li><a href="<?= base_url('spp') ?>"><span>SPP</span></a></li>
         <li><a href="<?= base_url('spp/detail/' . encrypt($spp->id_spp)) ?>"><span><?= $spp->no_spp ?></span></a></li>
         <li><span>Revisi</span></li>
      </ol>
   </div>
</header>

<style>
   .text-label {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #8898aa;
      font-weight: 700;
      display: block;
      margin-bottom: 4px;
   }

   .card-modern {
      border: 0;
      border-radius: 10px;
      box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
      background: #fff;
      margin-bottom: 24px;
   }

   .card-header-modern {
      background: #fff;
      border-bottom: 1px solid #f6f9fc;
      padding: 1.25rem 1.5rem;
   }

   .info-readonly {
      background-color: #f8f9fa;
      border: 1px solid #e9ecef;
      border-radius: 6px;
      padding: 10px 15px;
   }
</style>

<div class="row">
   <!-- Form Revisi -->
   <div class="col-lg-8 col-md-12">
      <!-- Alert Status -->
      <?php if ($is_revisi): ?>
         <div class="alert alert-warning mb-4">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle mr-2"></i>SPP Membutuhkan Revisi</h5>
            <?php if (!empty($spp->catatan_revisi)): ?>
               <hr>
               <p class="mb-0"><strong>Catatan dari Approver:</strong><br><?= nl2br($spp->catatan_revisi) ?></p>
            <?php endif; ?>
         </div>
      <?php elseif ($is_ditolak): ?>
         <div class="alert alert-danger mb-4">
            <h5 class="alert-heading"><i class="fas fa-times-circle mr-2"></i>SPP Ditolak</h5>
            <p>Anda dapat mengajukan ulang SPP ini dengan melakukan perubahan yang diperlukan.</p>
            <?php if (!empty($spp->catatan_revisi)): ?>
               <hr>
               <p class="mb-0"><strong>Alasan Penolakan:</strong><br><?= nl2br($spp->catatan_revisi) ?></p>
            <?php endif; ?>
         </div>
      <?php endif; ?>

   <!-- Info SPP (Read Only) -->
   <div class="card card-modern">
      <div class="card-header-modern">
         <h6 class="mb-0 font-weight-bold text-primary">
            <i class="fas fa-info-circle mr-2"></i>Informasi SPP
         </h6>
      </div>
      <div class="card-body">
         <div class="row">
            <div class="col-md-6 mb-3">
               <span class="text-label">No. SPP</span>
               <div class="info-readonly font-weight-bold text-primary"><?= $spp->no_spp ?></div>
            </div>
            <div class="col-md-6 mb-3">
               <span class="text-label">No. Invoice</span>
               <div class="info-readonly"><?= $spp->no_invoice ?></div>
            </div>
            <div class="col-md-6 mb-3">
               <span class="text-label">Jumlah Tagihan</span>
               <div class="info-readonly"><?= $spp->jumlah_tagihan ?> item</div>
            </div>
            <div class="col-md-6 mb-3">
               <span class="text-label">Total Nilai</span>
               <div class="info-readonly text-success font-weight-bold">
                  Rp <?= number_format($spp->total_nilai, 0, ',', '.') ?>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Form Edit -->
      <div class="card card-modern">
         <div class="card-header-modern">
            <h6 class="mb-0 font-weight-bold text-primary">
               <i class="fas fa-edit mr-2"></i>Form Revisi
            </h6>
         </div>
         <div class="card-body">
            <form id="form-edit-spp">
               <input type="hidden" name="id_spp" value="<?= encrypt($spp->id_spp) ?>">

               <h6 class="font-weight-bold text-primary mb-3" style="font-size: 0.8rem;">
                  <i class="fas fa-university mr-1"></i> Informasi Transfer
               </h6>
               
               <div class="form-group">
                  <label class="text-label">Nama Bank</label>
                  <input type="text" class="form-control" name="nama_bank" value="<?= $spp->nama_bank ?? '' ?>" placeholder="Contoh: BCA, Mandiri" required>
               </div>
               
               <div class="form-group">
                  <label class="text-label">Nomor Rekening</label>
                  <input type="text" class="form-control" name="no_rekening" value="<?= $spp->no_rekening ?? '' ?>" placeholder="Nomor Rekening" inputmode="numeric" pattern="[0-9]*" required>
               </div>
               
               <div class="form-group">
                  <label class="text-label">Atas Nama</label>
                  <input type="text" class="form-control" name="atas_nama" value="<?= $spp->atas_nama ?? '' ?>" placeholder="Nama Pemilik Rekening" required>
               </div>

               <hr>

               <h6 class="font-weight-bold text-primary mb-3" style="font-size: 0.8rem;">
                  <i class="fas fa-calculator mr-1"></i> Perhitungan
               </h6>

               <div class="form-group">
                  <label class="text-label">Total Nilai Tagihan</label>
                  <input type="text" class="form-control font-weight-bold text-info" id="display_total_tagihan" readonly
                     value="Rp <?= number_format($spp->total_nilai, 0, ',', '.') ?>">
                  <input type="hidden" id="total_nilai_hidden" value="<?= $spp->total_nilai ?>">
                  <small class="text-muted">Total dari semua tagihan dalam SPP ini</small>
               </div>

               <div class="form-group">
                  <label class="text-label">PPN (Rp)</label>
                  <input type="text" class="form-control" name="ppn" id="ppn"
                     value="<?= intval($spp->ppn ?? 0) ?>"
                     placeholder="0" inputmode="numeric" pattern="[0-9]*" onkeyup="hitungGrandTotal()">
                  <small class="text-muted">Masukkan nilai PPN (Menambah Grand Total)</small>
               </div>

               <div class="form-group">
                  <label class="text-label">Biaya Lainnya (Rp)</label>
                  <input type="text" class="form-control" name="biaya_lainnya" id="biaya_lainnya"
                     value="<?= intval($spp->biaya_lainnya ?? 0) ?>"
                     placeholder="0" inputmode="numeric" pattern="[0-9]*" onkeyup="hitungGrandTotal()">
                  <small class="text-muted">Biaya admin, materai, dll (Menambah Grand Total)</small>
               </div>

               <div class="form-group">
                  <label class="text-label">Diskon dari Ekspedisi (Rp)</label>
                  <input type="text" class="form-control" name="diskon" id="diskon"
                     value="<?= intval($spp->diskon ?? 0) ?>"
                     placeholder="0" inputmode="numeric" pattern="[0-9]*" onkeyup="hitungGrandTotal()">
                  <small class="text-muted">Masukkan diskon dari ekspedisi (Mengurangi Grand Total)</small>
               </div>

               <div class="form-group">
                  <label class="text-label">Grand Total</label>
                  <input type="text" class="form-control font-weight-bold text-success" id="display_grand_total" readonly
                     value="Rp <?= number_format(($spp->total_nilai + intval($spp->ppn ?? 0) + intval($spp->biaya_lainnya ?? 0) - intval($spp->diskon ?? 0)), 0, ',', '.') ?>">
                  <input type="hidden" id="grand_total_hidden" value="<?= $spp->total_nilai + intval($spp->ppn ?? 0) + intval($spp->biaya_lainnya ?? 0) - intval($spp->diskon ?? 0) ?>">
                  <small class="text-muted">(Total Tagihan + PPN + Biaya Lainnya) - Diskon</small>
               </div>

               <hr>

               <div class="form-group">
                  <label class="text-label">Link Bukti Potong</label>
                  <input type="url" class="form-control" name="link_bukti_potong"
                     value="<?= $spp->link_bukti_potong ?? '' ?>"
                     placeholder="https://drive.google.com/...">
                  <small class="text-muted">Link bukti potong pajak (jika ada)</small>
               </div>

               <div class="form-group">
                  <label class="text-label">Nilai Bukti Potong (Rp)</label>
                  <input type="text" class="form-control" name="nilai_bukti_potong" id="nilai_bukti_potong"
                     value="<?= intval($spp->nilai_bukti_potong ?? 0) ?>"
                     placeholder="0" inputmode="numeric" pattern="[0-9]*" onkeyup="hitungPembayaran()">
                  <small class="text-muted">Masukkan nominal bukti potong (Mengurangi Nilai Pembayaran)</small>
               </div>

               <div class="form-group">
                  <label class="text-label">Nilai Pembayaran (Yang Akan Ditransfer)</label>
                  <input type="text" class="form-control font-weight-bold text-primary" id="display_pembayaran" readonly
                     value="Rp <?= number_format((($spp->total_nilai + intval($spp->ppn ?? 0) + intval($spp->biaya_lainnya ?? 0) - intval($spp->diskon ?? 0)) - intval($spp->nilai_bukti_potong ?? 0)), 0, ',', '.') ?>">
                  <small class="text-muted">Grand Total - Bukti Potong</small>
               </div>

               <hr>

               <div class="form-group">
                  <label class="text-label">Link Dokumen SPP</label>
                  <input type="url" class="form-control" name="link_dokumen_spp"
                     value="<?= $spp->link_dokumen_spp ?>"
                     placeholder="https://drive.google.com/...">
                  <small class="text-muted">Upload dokumen pendukung ke Google Drive dan paste linknya di sini</small>
               </div>

               <div class="form-group">
                  <label class="text-label">Catatan / Keterangan Revisi</label>
                  <textarea class="form-control" name="catatan" rows="4"
                     placeholder="Jelaskan perubahan yang dilakukan..."><?= $spp->catatan ?></textarea>
               </div>

               <hr>

               <div class="d-flex justify-content-between">
                  <a href="<?= base_url('spp/detail/' . encrypt($spp->id_spp)) ?>" class="btn btn-secondary">
                     <i class="fas fa-arrow-left mr-2"></i>Kembali
                  </a>
                  <button type="button" class="btn btn-success btn-lg px-5" id="btn-submit">
                     <i class="fas fa-paper-plane mr-2"></i>Ajukan Ulang
                  </button>
               </div>
            </form>
         </div>
      </div>

      <!-- Daftar Tagihan -->
      <div class="card card-modern">
         <div class="card-header-modern">
            <h6 class="mb-0 font-weight-bold text-primary">
               <i class="fas fa-list mr-2"></i>Daftar Tagihan dalam SPP
            </h6>
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-hover table-sm">
                  <thead class="thead-light">
                     <tr>
                        <th>No</th>
                        <th>No. SJ</th>
                        <th>Customer</th>
                        <th>Ekspedisi</th>
                        <th class="text-right">Nilai</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php $no = 1;
                     foreach ($tagihan_list as $t): ?>
                        <tr>
                           <td><?= $no++ ?></td>
                           <td>
                              <a href="<?= base_url('tagihan/detail/' . encrypt($t->id_tagihan)) ?>" target="_blank">
                                 <?= $t->no_sj ?>
                                 <i class="fas fa-external-link-alt ml-1 text-muted" style="font-size: 0.7rem;"></i>
                              </a>
                           </td>
                           <td><?= $t->nama_customer ?? '-' ?></td>
                           <td><?= $t->nama_ekspedisi ?? '-' ?></td>
                           <td class="text-right">Rp <?= get_display_total_tagihan($t) ?></td>
                        </tr>
                     <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                     <tr class="bg-light">
                        <td colspan="4" class="text-right font-weight-bold">TOTAL:</td>
                        <td class="text-right font-weight-bold text-success">
                           Rp <?= number_format($spp->total_nilai, 0, ',', '.') ?>
                        </td>
                     </tr>
                  </tfoot>
               </table>
            </div>
         </div>
      </div>
   </div>

   <!-- Sidebar -->
   <div class="col-lg-4 col-md-12">
      <div class="card card-modern bg-light">
         <div class="card-body">
            <h6 class="font-weight-bold mb-3"><i class="fas fa-info-circle mr-2"></i>Panduan Revisi</h6>
            <ul class="pl-3 mb-0" style="line-height: 1.8;">
               <li>Periksa catatan dari approver di atas</li>
               <li>Perbaiki dokumen SPP jika diperlukan</li>
               <li>Upload dokumen yang sudah direvisi ke Google Drive</li>
               <li>Update link dokumen di form</li>
               <li>Tambahkan catatan mengenai perubahan yang dilakukan</li>
               <li>Klik "Ajukan Ulang" untuk mengirimkan kembali</li>
            </ul>
         </div>
      </div>

      <div class="card card-modern">
         <div class="card-body text-center">
            <div class="mb-3">
               <i class="fas fa-sync-alt text-warning fa-3x"></i>
            </div>
            <h5 class="font-weight-bold">Status: <?= $is_ditolak ? 'Ditolak' : 'Revisi' ?></h5>
            <p class="text-muted small mb-0">
               Setelah diajukan ulang, SPP akan masuk kembali ke proses approval dari awal.
            </p>
         </div>
      </div>
   </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   $(document).ready(function() {
      $('#btn-submit').click(function() {
         let btn = $(this);
         let originalText = btn.html();

         Swal.fire({
            title: 'Ajukan Ulang SPP?',
            text: "SPP akan masuk kembali ke proses approval dari awal.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Ajukan Ulang!',
            cancelButtonText: 'Batal'
         }).then((result) => {
            if (result.isConfirmed) {
               btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');

               $.ajax({
                  url: '<?= base_url("spp/update") ?>',
                  type: 'POST',
                  data: $('#form-edit-spp').serialize(),
                  dataType: 'json',
                  success: function(res) {
                     btn.prop('disabled', false).html(originalText);

                     if (res.status == 'success') {
                        Swal.fire({
                           icon: 'success',
                           title: 'Berhasil!',
                           text: res.message,
                           showConfirmButton: true,
                           allowOutsideClick: false
                        }).then(() => {
                           window.location.href = res.redirect || '<?= base_url("spp") ?>';
                        });
                     } else {
                        Swal.fire('Gagal', res.message, 'error');
                     }
                  },
                  error: function(xhr) {
                     btn.prop('disabled', false).html(originalText);
                     console.log('Error:', xhr.responseText);
                     Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
                  }
               });
            }
         });
      });
   });

   // Fungsi hitung Grand Total: (Total + PPN + Biaya Lainnya) - Diskon
   function hitungGrandTotal() {
      let totalNilai = parseInt($('#total_nilai_hidden').val()) || 0;
      let ppn = parseInt($('#ppn').val().replace(/[^0-9]/g, '')) || 0;
      let biayaLainnya = parseInt($('#biaya_lainnya').val().replace(/[^0-9]/g, '')) || 0;
      let diskon = parseInt($('#diskon').val().replace(/[^0-9]/g, '')) || 0;
      
      let grandTotal = (totalNilai + ppn + biayaLainnya) - diskon;

      if (grandTotal < 0) grandTotal = 0;

      $('#display_grand_total').val('Rp ' + number_format(grandTotal));
      $('#grand_total_hidden').val(grandTotal);

      // Hitung ulang pembayaran
      hitungPembayaran();
   }

   // Fungsi hitung pembayaran: Grand Total - Bukti Potong
   function hitungPembayaran() {
      let grandTotal = parseInt($('#grand_total_hidden').val()) || 0;
      let nilaiBuktiPotong = parseInt($('#nilai_bukti_potong').val().replace(/[^0-9]/g, '')) || 0;
      let nilaiPembayaran = grandTotal - nilaiBuktiPotong;

      if (nilaiPembayaran < 0) nilaiPembayaran = 0;

      $('#display_pembayaran').val('Rp ' + number_format(nilaiPembayaran));
   }

   function number_format(num) {
      return parseInt(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
   }
</script>