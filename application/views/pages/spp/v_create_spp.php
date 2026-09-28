<header class="page-header">
   <h2><i class="fas fa-plus-circle"></i>&nbsp;<?= $page_title ?></h2>
   <div class="right-wrapper text-left">
      <ol class="breadcrumbs">
         <li><a href="<?= base_url('spp') ?>"><span>SPP</span></a></li>
         <li><span><?= $page_desc ?></span></li>
      </ol>
   </div>
</header>

<style>
   .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
   }

   .invoice-card {
      cursor: pointer;
      transition: all 0.3s ease;
      border: 2px solid transparent;
   }

   .invoice-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
   }

   .invoice-card.selected {
      border-color: #28a745;
      background-color: #f8fff8;
   }

   .invoice-card.selected .check-icon {
      display: block;
   }

   .check-icon {
      display: none;
      position: absolute;
      top: 10px;
      right: 10px;
      color: #28a745;
      font-size: 1.5rem;
   }

   .text-label {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #8898aa;
      font-weight: 700;
   }

   .preview-table {
      max-height: 400px;
      overflow-y: auto;
   }

   .sticky-sidebar {
      position: sticky;
      top: 20px;
   }
</style>

<div class="row">
   <!-- Sidebar Form -->
   <div class="col-lg-4 col-md-12">
      <div class="sticky-sidebar">
         <div class="card mb-4">
            <div class="card-header bg-primary text-white">
               <h6 class="mb-0 font-weight-bold"><i class="fas fa-file-signature mr-2"></i>Form Pengajuan SPP</h6>
            </div>
            <div class="card-body">
               <form id="form-spp">
                  <input type="hidden" name="no_invoice" id="selected_invoice">
                  <input type="hidden" name="nama_ekspedisi" id="selected_ekspedisi">

                  <div class="alert alert-info p-3 mb-3" style="font-size: 0.85rem;">
                     <i class="fas fa-info-circle mr-2"></i>
                     Pilih invoice di sebelah kanan untuk mengajukan SPP. Semua tagihan dengan nomor invoice yang sama akan otomatis dimasukkan.
                  </div>
                  
                  <h6 class="font-weight-bold text-primary mb-3" style="font-size: 0.8rem;">
                    <i class="fas fa-university mr-1"></i> Informasi Transfer
                </h6>
                
                <div class="form-group">
                   <label class="text-label">Nama Bank</label>
                   <input type="text" class="form-control" name="nama_bank" placeholder="Contoh: BCA, Mandiri" required>
                </div>
                
                <div class="form-group">
                   <label class="text-label">Nomor Rekening</label>
                   <input type="text" class="form-control" name="no_rekening" placeholder="Nomor Rekening" required>
                </div>
                
                <div class="form-group">
                   <label class="text-label">Atas Nama</label>
                   <input type="text" class="form-control" name="atas_nama" placeholder="Nama Pemilik Rekening" required>
                </div>

                  <div class="form-group">
                     <label class="text-label">No. Invoice Terpilih</label>
                     <input type="text" class="form-control" id="display_invoice" readonly placeholder="Pilih invoice...">
                  </div>

                  <div class="form-group">
                     <label class="text-label">Jumlah Tagihan</label>
                     <input type="text" class="form-control" id="display_jumlah" readonly value="-">
                  </div>

                  <div class="form-group">
                     <label class="text-label">Total Nilai Tagihan</label>
                     <input type="text" class="form-control font-weight-bold text-success" id="display_total" readonly value="-">
                     <input type="hidden" name="total_nilai_hidden" id="total_nilai_hidden" value="0">
                  </div>

                  <hr>

                  <div class="form-group">
                     <label class="text-label">PPN (Rp)</label>
                     <input type="text" class="form-control" name="ppn" id="ppn" placeholder="0" value="0" inputmode="numeric" pattern="[0-9]*" onkeyup="hitungGrandTotal()">
                     <small class="text-muted">Masukkan nilai PPN (Menambah Grand Total)</small>
                  </div>

                  <div class="form-group">
                      <label class="text-label">Biaya Lainnya (Rp)</label>
                      <input type="text" class="form-control" name="biaya_lainnya" id="biaya_lainnya" placeholder="0" value="0" inputmode="numeric" pattern="[0-9]*" onkeyup="hitungGrandTotal()">
                      <small class="text-muted">Biaya admin, materai, dll (Menambah Grand Total)</small>
                  </div>
                  
                  <div class="form-group">
                     <label class="text-label">Diskon dari Ekspedisi (Rp)</label>
                     <input type="text" class="form-control" name="diskon" id="diskon" placeholder="0" value="0" inputmode="numeric" pattern="[0-9]*" onkeyup="hitungGrandTotal()">
                     <small class="text-muted">Masukkan diskon dari ekspedisi (opsional)</small>
                  </div>

                  <div class="form-group">
                     <label class="text-label">Grand Total (Setelah Diskon)</label>
                     <input type="text" class="form-control font-weight-bold text-primary" id="display_grand_total" readonly value="-">
                     <input type="hidden" name="grand_total_hidden" id="grand_total_hidden" value="0">
                     <small class="text-muted">Total Nilai - Diskon</small>
                  </div>

                  <hr>

                  <div class="form-group">
                     <label class="text-label">Link Bukti Potong</label>
                     <input type="url" class="form-control" name="link_bukti_potong" id="link_bukti_potong" placeholder="https://drive.google.com/...">
                     <small class="text-muted">Link bukti potong pajak (jika ada)</small>
                  </div>

                  <div class="form-group">
                     <label class="text-label">Nilai Bukti Potong (Rp)</label>
                     <input type="text" class="form-control" name="nilai_bukti_potong" id="nilai_bukti_potong" placeholder="0" value="0" inputmode="numeric" pattern="[0-9]*" onkeyup="hitungPembayaran()">
                     <small class="text-muted">Masukkan nominal bukti potong</small>
                  </div>

                  <div class="form-group">
                     <label class="text-label">Nilai Pembayaran</label>
                     <input type="text" class="form-control font-weight-bold text-primary" id="display_pembayaran" readonly value="-">
                     <small class="text-muted">Grand Total - Bukti Potong</small>
                  </div>

                  <hr>

                  <div class="form-group">
                     <label class="text-label">Link Dokumen SPP (Opsional)</label>
                     <input type="url" class="form-control" name="link_dokumen_spp" placeholder="https://drive.google.com/...">
                     <small class="text-muted">Upload dokumen pendukung ke Google Drive</small>
                  </div>

                  <div class="form-group mb-2">
                     <label class="text-label">Catatan</label>
                     <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan tambahan..."></textarea>
                  </div>

                  <button type="button" class="btn btn-success btn-block font-weight-bold py-3" id="btn-submit" disabled>
                     <i class="fas fa-paper-plane mr-2"></i> AJUKAN SPP
                  </button>
               </form>
            </div>
         </div>
      </div>
   </div>

   <!-- List Invoice -->
   <div class="col-lg-8 col-md-12">
      <div class="card mb-4">
         <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 font-weight-bold text-primary">
               <i class="fas fa-list mr-2"></i>Invoice yang Siap Diajukan SPP
            </h6>
            <small class="text-muted">Tagihan yang sudah approved dan belum diajukan SPP</small>
         </div>
         <div class="card-body">
            <?php if (empty($grouped_tagihan)): ?>
               <div class="text-center py-5 text-muted">
                  <i class="fas fa-inbox fa-4x mb-3 d-block opacity-50"></i>
                  <h5>Tidak ada invoice yang tersedia</h5>
                  <p class="mb-0">Semua tagihan sudah diajukan SPP atau belum ada yang approved</p>
               </div>
            <?php else: ?>
               <div class="row">
                  <?php foreach ($grouped_tagihan as $inv): ?>
                     <div class="col-md-6 mb-3">
                        <div class="card invoice-card position-relative" data-invoice="<?= $inv->no_invoice ?>" data-ekspedisi="<?= htmlspecialchars($inv->nama_ekspedisi) ?>" data-jumlah="<?= $inv->jumlah_tagihan ?>" data-total="<?= $inv->total_nilai ?>">
                           <i class="fas fa-check-circle check-icon"></i>
                           <div class="card-body">
                              <div class="d-flex justify-content-between align-items-start mb-2">
                                 <div>
                                    <span class="text-label d-block">No. Invoice</span>
                                    <span class="font-weight-bold text-primary" style="font-size: 1.1rem;">
                                       <?= $inv->no_invoice ?>
                                    </span>
                                 </div>
                                 <span class="badge badge-info"><?= $inv->jumlah_tagihan ?> tagihan</span>
                              </div>

                              <div class="mb-2">
                                 <span class="text-label d-block">Ekspedisi</span>
                                 <span class="text-dark"><?= $inv->nama_ekspedisi ?? '-' ?></span>
                              </div>

                              <div class="mb-2">
                                 <span class="text-label d-block">Periode Invoice</span>
                                 <span class="text-dark text-small">
                                    <?= date('d M Y', strtotime($inv->tanggal_invoice_awal)) ?>
                                    <?php if ($inv->tanggal_invoice_awal != $inv->tanggal_invoice_akhir): ?>
                                       - <?= date('d M Y', strtotime($inv->tanggal_invoice_akhir)) ?>
                                    <?php endif; ?>
                                 </span>
                              </div>

                              <hr class="my-2">

                              <div class="d-flex justify-content-between align-items-center">
                                 <span class="text-label">Total Nilai</span>
                                 <span class="font-weight-bold text-success" style="font-size: 1.1rem;">
                                    Rp <?= number_format($inv->total_nilai, 0, ',', '.') ?>
                                 </span>
                              </div>
                           </div>
                        </div>
                     </div>
                  <?php endforeach; ?>
               </div>
            <?php endif; ?>
         </div>
      </div>

      <!-- Preview Detail Tagihan -->
      <div class="card" id="preview-section" style="display: none;">
         <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 font-weight-bold text-primary">
               <i class="fas fa-search mr-2"></i>Detail Tagihan dalam Invoice
            </h6>
         </div>
         <div class="card-body preview-table">
            <table class="table table-sm table-bordered" id="preview-table">
               <thead class="thead-light">
                  <tr>
                     <th width="40">No</th>
                     <th>No. SJ</th>
                     <th>Customer</th>
                     <th width="100">Tgl Invoice</th>
                     <th width="120" class="text-right">Nilai Tagihan<br><small>(Ongkir)</small></th>
                     <th width="120" class="text-right">Biaya<br>Asuransi</th>
                     <th width="130" class="text-right">Total Tagihan</th>
                  </tr>
               </thead>
               <tbody id="preview-tbody">
                  <!-- Filled by AJAX -->
               </tbody>
               <tfoot class="bg-light font-weight-bold">
                  <tr>
                     <td colspan="4" class="text-right">TOTAL KESELURUHAN:</td>
                     <td class="text-right" id="preview-total-nilai">-</td>
                     <td class="text-right" id="preview-total-asuransi">-</td>
                     <td class="text-right text-success" id="preview-total">-</td>
                  </tr>
               </tfoot>
            </table>
         </div>
      </div>
   </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   $(document).ready(function() {

      // Handle click on invoice card
      $('.invoice-card').click(function() {
         // Remove selection from others
         $('.invoice-card').removeClass('selected');
         $(this).addClass('selected');

         let noInvoice = $(this).data('invoice');
         let namaEkspedisi = $(this).data('ekspedisi');
         let jumlah = $(this).data('jumlah');
         let total = $(this).data('total');

         // Update form
         $('#selected_invoice').val(noInvoice);
         $('#selected_ekspedisi').val(namaEkspedisi);
         $('#display_invoice').val(noInvoice);
         $('#display_jumlah').val(jumlah + ' tagihan');
         $('#display_total').val('Rp ' + number_format(total));
         $('#total_nilai_hidden').val(total);

         // Reset dan hitung pembayaran
         $('#diskon').val('0');
         $('#nilai_bukti_potong').val('0');
         hitungGrandTotal();

         // Enable submit
         $('#btn-submit').prop('disabled', false);

         // Load preview
         loadPreview(noInvoice, namaEkspedisi);
      });

      // Load preview via AJAX
      function loadPreview(noInvoice, namaEkspedisi) {
         $.ajax({
            url: '<?= base_url("spp/preview_invoice") ?>',
            type: 'POST',
            data: {
               no_invoice: noInvoice,
               nama_ekspedisi: namaEkspedisi
            },
            dataType: 'json',
            beforeSend: function() {
               $('#preview-section').slideDown();
               $('#preview-tbody').html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
            },
            success: function(res) {
               if (res.status && res.data.length > 0) {
                  let html = '';
                  let no = 1;
                  let totalNilai = 0;
                  let totalAsuransi = 0;
                  let totalKeseluruhan = 0;

                  res.data.forEach(function(item) {
                     let nilaiTagihan = parseInt(item.nilai_tagihan) || 0;
                     let biayaAsuransi = parseInt(item.biaya_asuransi) || 0;
                     let itemTotal = item.total_tagihan ? parseInt(item.total_tagihan) : (nilaiTagihan + biayaAsuransi);

                     totalNilai += nilaiTagihan;
                     totalAsuransi += biayaAsuransi;
                     totalKeseluruhan += itemTotal;

                     html += `<tr>
                <td class="text-center">${no++}</td>
                <td>${item.no_sj}</td>
                <td>${item.nama_customer || '-'}</td>
                <td class="text-center">${formatDate(item.tanggal_invoice)}</td>
                <td class="text-right">Rp ${number_format(nilaiTagihan)}</td>
                <td class="text-right">${biayaAsuransi > 0 ? 'Rp ' + number_format(biayaAsuransi) : '-'}</td>
                <td class="text-right font-weight-bold">Rp ${number_format(itemTotal)}</td>
              </tr>`;
                  });

                  $('#preview-tbody').html(html);
                  $('#preview-total-nilai').text('Rp ' + number_format(totalNilai));
                  $('#preview-total-asuransi').text(totalAsuransi > 0 ? 'Rp ' + number_format(totalAsuransi) : '-');
                  $('#preview-total').text('Rp ' + number_format(totalKeseluruhan));
               } else {
                  $('#preview-tbody').html('<tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>');
               }
            },
            error: function() {
               $('#preview-tbody').html('<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>');
            }
         });
      }

      // Submit SPP
      $('#btn-submit').click(function() {
         let btn = $(this);
         let originalText = btn.html();

         if (!$('#selected_invoice').val()) {
            Swal.fire('Peringatan', 'Pilih invoice terlebih dahulu!', 'warning');
            return;
         }

         Swal.fire({
            title: 'Ajukan SPP?',
            text: "Pastikan data sudah benar. SPP akan masuk ke proses approval.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Ajukan!',
            cancelButtonText: 'Batal'
         }).then((result) => {
            if (result.isConfirmed) {
               btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');

               $.ajax({
                  url: '<?= base_url("spp/submit") ?>',
                  type: 'POST',
                  data: $('#form-spp').serialize(),
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
                           // Redirect ke halaman list SPP
                           window.location.href = res.redirect || '<?= base_url("spp") ?>';
                        });
                     } else {
                        Swal.fire('Gagal', res.message, 'error');
                     }
                  },
                  error: function(xhr, status, error) {
                     btn.prop('disabled', false).html(originalText);
                     console.log('Error:', xhr.responseText);
                     Swal.fire('Error', 'Terjadi kesalahan server. Silakan coba lagi.', 'error');
                  }
               });
            }
         });
      });

      // Helper functions
      function number_format(num) {
         return parseInt(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }

      function formatDate(dateStr) {
         if (!dateStr) return '-';
         let d = new Date(dateStr);
         let months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
         return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
      }

   });

   function hitungGrandTotal() {
      // 1. Ambil Total Tagihan (Murni)
      let totalNilai = parseInt($('#total_nilai_hidden').val()) || 0;

      // 2. Ambil PPN (Bersihkan karakter non-angka)
      let ppn = parseInt($('#ppn').val().replace(/[^0-9]/g, '')) || 0;

      // [BARU] 3. Ambil Biaya Lainnya
      let biayaLainnya = parseInt($('#biaya_lainnya').val().replace(/[^0-9]/g, '')) || 0;

      // 4. Ambil Diskon (Bersihkan karakter non-angka)
      let diskon = parseInt($('#diskon').val().replace(/[^0-9]/g, '')) || 0;

      // --- RUMUS UPDATE: (Total Tagihan + PPN + Biaya Lainnya) - Diskon ---
      let grandTotal = (totalNilai + ppn + biayaLainnya) - diskon;

      if (grandTotal < 0) grandTotal = 0;

      // Tampilkan Grand Total
      $('#display_grand_total').val('Rp ' + number_format_global(grandTotal));
      $('#grand_total_hidden').val(grandTotal);

      // Panggil fungsi hitung pembayaran (agar nilai pembayaran ikut update)
      hitungPembayaran();
   }

   // Fungsi hitung pembayaran (Yang akan ditransfer)
   function hitungPembayaran() {
      // 1. Ambil Grand Total (Hasil perhitungan di atas)
      let grandTotal = parseInt($('#grand_total_hidden').val()) || 0;

      // 2. Ambil Nilai Bukti Potong
      let nilaiBuktiPotong = parseInt($('#nilai_bukti_potong').val().replace(/[^0-9]/g, '')) || 0;

      // --- RUMUS: Grand Total - Bukti Potong ---
      let nilaiPembayaran = grandTotal - nilaiBuktiPotong;

      if (nilaiPembayaran < 0) nilaiPembayaran = 0;

      // Tampilkan Nilai Pembayaran
      $('#display_pembayaran').val('Rp ' + number_format_global(nilaiPembayaran));
   }

   // Fungsi format angka global
   function number_format_global(num) {
      return parseInt(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
   }
</script>