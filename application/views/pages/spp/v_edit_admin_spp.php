 <?php
   /**
    * View Edit Full SPP (Admin Only)
    * Halaman untuk admin mengedit semua field SPP
    */
   $is_admin = ($spp->is_admin ?? false) || (isset($_SESSION['nama']) && $_SESSION['nama'] == 'Administrator');
   ?>

 <header class="page-header">
    <h2><i class="fas fa-edit"></i> Edit SPP (Admin)</h2>
    <div class="right-wrapper text-left">
       <ol class="breadcrumbs">
          <li><a href="<?= base_url('spp') ?>"><span>SPP</span></a></li>
          <li><a href="<?= base_url('spp/detail/' . encrypt($spp->id_spp)) ?>"><span><?= $spp->no_spp ?></span></a></li>
          <li><span>Edit Admin</span></li>
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

    .section-title {
       background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
       color: white;
       padding: 10px 15px;
       border-radius: 6px;
       margin-bottom: 15px;
    }
 </style>

 <!-- Tombol Kembali -->
 <div class="mb-3">
    <a href="<?= base_url('spp/detail/' . encrypt($spp->id_spp)) ?>" class="btn btn-secondary btn-sm">
       <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail
    </a>
 </div>

 <div class="row">
    <div class="col-lg-8 col-md-12">
       <!-- Info SPP (Read Only) -->
       <div class="card card-modern">
          <div class="card-header-modern">
             <h6 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-info-circle mr-2"></i>Informasi SPP
             </h6>
          </div>
          <div class="card-body">
             <div class="row">
                <div class="col-md-4 mb-3">
                   <span class="text-label">No. SPP</span>
                   <div class="info-readonly font-weight-bold text-primary"><?= $spp->no_spp ?></div>
                </div>
                <div class="col-md-4 mb-3">
                   <span class="text-label">No. Invoice (Editable)</span>
                   <input type="text" class="form-control" name="no_invoice" value="<?= $spp->no_invoice ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                   <span class="text-label">Jumlah Tagihan</span>
                   <div class="info-readonly"><span id="display_jumlah_tagihan"><?= $spp->jumlah_tagihan ?></span> item</div>
                </div>
                <div class="col-md-4 mb-3">
                   <span class="text-label">Total Nilai Tagihan</span>
                   <div class="info-readonly text-success font-weight-bold" id="display_total_tagihan">
                      Rp <?= number_format($spp->total_nilai, 0, ',', '.') ?>
                   </div>
                </div>
                <div class="col-md-4 mb-3">
                   <span class="text-label">Tanggal Pengajuan</span>
                   <div class="info-readonly"><?= date('d M Y H:i', strtotime($spp->created_at)) ?></div>
                </div>
                <div class="col-md-4 mb-3">
                   <span class="text-label">Pengaju</span>
                   <div class="info-readonly"><?= $spp->nama_pengaju ?? '-' ?></div>
                </div>
             </div>
          </div>
       </div>

       <!-- Form Edit Admin -->
       <div class="card card-modern">
          <div class="card-header-modern">
             <h6 class="mb-0 font-weight-bold text-warning">
                <i class="fas fa-edit mr-2"></i>Form Edit (Admin Only)
             </h6>
          </div>
          <div class="card-body">
             <form id="form-edit-admin">
                <input type="hidden" name="id_spp" value="<?= encrypt($spp->id_spp) ?>">
                <input type="hidden" id="total_nilai_hidden" value="<?= $spp->total_nilai ?>">

                <!-- Section: Status Approval -->
                <div class="section-title">
                   <i class="fas fa-clipboard-check mr-2"></i>Status Approval
                </div>
                <div class="form-group">
                   <label class="text-label">Status Approval</label>
                   <select class="form-control" name="status_approval" id="status_approval">
                      <?php foreach ($approval_configs as $cfg): ?>
                         <option value="<?= $cfg->status_code ?>" <?= ($spp->status_approval == $cfg->status_code) ? 'selected' : '' ?>>
                            <?= $cfg->status_code ?>. <?= $cfg->role_label ?>
                         </option>
                      <?php endforeach; ?>
                      <option value="5" <?= ($spp->status_approval == 5) ? 'selected' : '' ?>>5. APPROVED (Selesai)</option>
                      <option value="0" <?= ($spp->status_approval == 0) ? 'selected' : '' ?>>0. REVISI</option>
                      <option value="99" <?= ($spp->status_approval == 99) ? 'selected' : '' ?>>99. DITOLAK</option>
                   </select>
                </div>

                <!-- Section: Nilai & Diskon -->
                <div class="section-title mt-4">
                   <i class="fas fa-money-bill-wave mr-2"></i>Nilai & Perhitungan
                </div>

                <div class="row">
                   <div class="col-md-4">
                      <div class="form-group">
                         <label class="text-label">Total Nilai Tagihan</label>
                         <input type="text" class="form-control font-weight-bold text-success" id="display_total_nilai" readonly
                            value="Rp <?= number_format($spp->total_nilai, 0, ',', '.') ?>">
                         <input type="hidden" id="total_nilai_hidden" value="<?= $spp->total_nilai ?>">
                         <small class="text-muted">Otomatis dari daftar tagihan</small>
                      </div>
                   </div>
                   <div class="col-md-4">
                      <div class="form-group">
                         <label class="text-label">PPN (Rp)</label>
                         <input type="text" class="form-control" name="ppn" id="ppn"
                            value="<?= $spp->ppn ?? 0 ?>" placeholder="0" onkeyup="hitungGrandTotal()">
                      </div>
                   </div>
                   <div class="col-md-4">
                      <div class="form-group">
                         <label class="text-label">Diskon dari Ekspedisi (Rp)</label>
                         <input type="text" class="form-control" name="diskon" id="diskon"
                            value="<?= $spp->diskon ?? 0 ?>" placeholder="0" onkeyup="hitungGrandTotal()">
                      </div>
                   </div>
                </div>

                <div class="row">
                   <div class="col-md-6">
                      <div class="form-group">
                         <label class="text-label">Grand Total (Total + PPN - Diskon)</label>
                         <input type="text" class="form-control font-weight-bold text-primary" id="display_grand_total" readonly
                            value="Rp <?= number_format($spp->grand_total, 0, ',', '.') ?>">
                         <input type="hidden" id="grand_total_hidden" value="<?= $spp->grand_total ?>">
                      </div>
                   </div>
                   <div class="col-md-6">
                      <div class="form-group">
                         <label class="text-label">Nilai Bukti Potong (Rp)</label>
                         <input type="text" class="form-control" name="nilai_bukti_potong" id="nilai_bukti_potong"
                            value="<?= $spp->nilai_bukti_potong ?? 0 ?>" placeholder="0" onkeyup="hitungPembayaran()">
                      </div>
                   </div>
                </div>

                <div class="row">
                   <div class="col-12">
                      <div class="alert alert-info">
                         <strong><i class="fas fa-calculator mr-2"></i>Nilai Pembayaran (Final):</strong>
                         <h3 class="mb-0 mt-2" id="display_pembayaran">
                            Rp <?= number_format($spp->nilai_pembayaran ?? 0, 0, ',', '.') ?>
                         </h3>
                         <small>Grand Total - Bukti Potong</small>
                      </div>
                   </div>
                </div>

                <!-- Section: Dokumen -->
                <div class="section-title mt-4">
                   <i class="fas fa-file-alt mr-2"></i>Dokumen
                </div>

                <div class="form-group">
                   <label class="text-label">Link Dokumen SPP</label>
                   <input type="url" class="form-control" name="link_dokumen_spp"
                      value="<?= $spp->link_dokumen_spp ?>" placeholder="https://drive.google.com/...">
                </div>

                <div class="form-group">
                   <label class="text-label">Link Bukti Potong</label>
                   <input type="url" class="form-control" name="link_bukti_potong"
                      value="<?= $spp->link_bukti_potong ?? '' ?>" placeholder="https://drive.google.com/...">
                </div>

                <!-- Section: Catatan -->
                <div class="section-title mt-4">
                   <i class="fas fa-sticky-note mr-2"></i>Catatan
                </div>

                <div class="form-group">
                   <label class="text-label">Catatan</label>
                   <textarea class="form-control" name="catatan" rows="3"><?= $spp->catatan ?></textarea>
                </div>

                <div class="form-group">
                   <label class="text-label">Catatan Revisi (dari Approver)</label>
                   <textarea class="form-control" name="catatan_revisi" rows="3"><?= $spp->catatan_revisi ?></textarea>
                   <small class="text-muted">Isi jika status diubah ke Revisi atau Ditolak</small>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                   <a href="<?= base_url('spp/detail/' . encrypt($spp->id_spp)) ?>" class="btn btn-secondary">
                      <i class="fas fa-arrow-left mr-2"></i>Batal
                   </a>
                   <button type="button" class="btn btn-warning btn-lg px-5" id="btn-submit">
                      <i class="fas fa-save mr-2"></i>Simpan Perubahan
                   </button>
                </div>
             </form>
          </div>
       </div>

       <!-- Daftar Tagihan (Editable - Tambah/Hapus) -->
       <div class="card card-modern">
          <div class="card-header-modern bg-light">
             <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-weight-bold text-primary">
                   <i class="fas fa-list mr-2"></i>Daftar Tagihan dalam SPP (<span id="count_tagihan"><?= count($tagihan_list) ?></span> item)
                </h6>
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAddTagihan">
                   <i class="fas fa-plus mr-1"></i> Tambah Tagihan
                </button>
             </div>
          </div>
          <div class="card-body">
             <?php
             // Pre-scan untuk deteksi data invalid
             $has_invalid_data = false;
             foreach ($tagihan_list as $check) {
                if (empty($check->no_sj) && empty($check->kode_kirim)) {
                   $has_invalid_data = true;
                   break;
                }
             }
             
             if ($has_invalid_data): ?>
                <div class="alert alert-danger">
                   <h6 class="alert-heading"><i class="fas fa-exclamation-triangle mr-2"></i>⚠️ DATA TIDAK VALID TERDETEKSI!</h6>
                   <p class="mb-2">Ada tagihan dalam SPP ini yang <strong>tidak memiliki referensi tracking_barang atau kirim_dokumen</strong>.</p>
                   <ul class="mb-0 pl-4">
                      <li>Tagihan tersebut ditandai dengan <span class="badge badge-danger">background merah</span></li>
                      <li>Kemungkinan data corrupt atau input yang tidak lengkap</li>
                      <li>Nilai tagihan tetap dihitung dalam total, tapi data referensi kosong</li>
                      <li><strong>REKOMENDASI:</strong> Hapus tagihan yang tidak valid dan tambahkan kembali dengan data yang benar</li>
                   </ul>
                </div>
             <?php endif; ?>
             
             <div class="table-responsive">
                <table class="table table-hover table-sm table-bordered" id="tableTagihan">
                   <thead class="thead-light">
                      <tr>
                         <th width="5%">No</th>
                         <th width="15%">No. SJ</th>
                         <th width="25%">Customer</th>
                         <th width="20%">Ekspedisi</th>
                         <th width="20%" class="text-right">Nilai</th>
                         <th width="15%" class="text-center">Aksi</th>
                      </tr>
                   </thead>
                   <tbody id="tbody-tagihan">
                      <?php $no = 1;
                        $total_display = 0;
                        $ada_data_invalid = false;
                        foreach ($tagihan_list as $t):
                           $total_display += ($t->nilai_tagihan ?? 0) + ($t->biaya_asuransi ?? 0);
                           
                           // Deteksi data tidak valid (tidak punya no_sj dan kode_kirim)
                           $is_invalid = empty($t->no_sj) && empty($t->kode_kirim);
                           if ($is_invalid) $ada_data_invalid = true;
                           $row_class = $is_invalid ? 'table-danger' : '';
                        ?>
                         <tr data-id-tagihan="<?= $t->id_tagihan ?>" 
                             data-nilai="<?= ($t->nilai_tagihan ?? 0) + ($t->biaya_asuransi ?? 0) ?>"
                             class="<?= $row_class ?>">
                            <td><?= $no++ ?></td>
                            <td>
                               <?php if ($is_invalid): ?>
                                  <span class="badge badge-danger">DATA TIDAK VALID</span>
                                  <br><small class="text-muted">ID: <?= $t->id_tagihan ?></small>
                               <?php else: ?>
                                  <a href="<?= base_url('tagihan/detail/' . encrypt($t->id_tagihan)) ?>" target="_blank">
                                     <?= $t->no_sj ?? $t->kode_kirim ?? '-' ?>
                                     <i class="fas fa-external-link-alt ml-1 text-muted" style="font-size: 0.7rem;"></i>
                                  </a>
                               <?php endif; ?>
                            </td>
                            <td>
                               <?= $t->nama_customer ?? '-' ?>
                               <?php if ($is_invalid): ?>
                                  <br><small class="text-danger">⚠️ id_tracking: <?= $t->id_tracking ?? 'NULL' ?> | id_kirim: <?= $t->id_kirim ?? 'NULL' ?></small>
                               <?php endif; ?>
                            </td>
                            <td><?= $t->nama_ekspedisi ?? '-' ?></td>
                            <td class="text-right font-weight-bold">Rp <?= get_display_total_tagihan($t) ?></td>
                            <td class="text-center">
                               <button type="button" class="btn btn-danger btn-sm btn-remove-tagihan"
                                  data-id-tagihan="<?= $t->id_tagihan ?>"
                                  title="Hapus dari SPP">
                                  <i class="fas fa-trash"></i>
                               </button>
                            </td>
                         </tr>
                      <?php endforeach; ?>
                   </tbody>
                   <tfoot>
                      <tr class="bg-light">
                         <td colspan="4" class="text-right font-weight-bold">
                            TOTAL (<?= count($tagihan_list) ?> tagihan):
                            <?php if ($ada_data_invalid): ?>
                               <br><small class="text-danger">⚠️ Termasuk data tidak valid</small>
                            <?php endif; ?>
                         </td>
                         <td class="text-right font-weight-bold text-success" id="footer-total">
                            Rp <?= number_format($total_display, 0, ',', '.') ?>
                         </td>
                         <td></td>
                      </tr>
                   </tfoot>
                </table>
             </div>
          </div>
       </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4 col-md-12">
       <div class="card card-modern bg-warning text-dark">
          <div class="card-body">
             <h6 class="font-weight-bold mb-3"><i class="fas fa-exclamation-triangle mr-2"></i>Perhatian Admin</h6>
             <ul class="pl-3 mb-0" style="line-height: 1.8;">
                <li>Halaman ini khusus untuk <strong>Administrator</strong></li>
                <li>Perubahan status approval akan langsung berlaku</li>
                <li>Jika status diubah ke Revisi/Ditolak, isi catatan revisi</li>
                <li>Nilai pembayaran dihitung otomatis dari Grand Total - Bukti Potong</li>
                <li>Pastikan data sudah benar sebelum menyimpan</li>
             </ul>
          </div>
       </div>

       <div class="card card-modern">
          <div class="card-body">
             <h6 class="font-weight-bold mb-3"><i class="fas fa-info-circle mr-2"></i>Status Approval</h6>
             <table class="table table-sm table-borderless mb-0">
                <?php foreach ($approval_configs as $cfg): ?>
                   <tr>
                      <td><span class="badge badge-info"><?= $cfg->status_code ?></span></td>
                      <td><?= $cfg->role_label ?></td>
                   </tr>
                <?php endforeach; ?>
                <tr>
                   <td><span class="badge badge-success">5</span></td>
                   <td>APPROVED (Selesai)</td>
                </tr>
                <tr>
                   <td><span class="badge badge-warning">0</span></td>
                   <td>REVISI</td>
                </tr>
                <tr>
                   <td><span class="badge badge-danger">99</span></td>
                   <td>DITOLAK</td>
                </tr>
             </table>
          </div>
       </div>
    </div>
 </div>

 <!-- Modal Tambah Tagihan -->
 <div class="modal fade" id="modalAddTagihan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
          <div class="modal-header bg-success text-white">
             <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Tambah Tagihan ke SPP</h5>
             <button type="button" class="close text-white" data-dismiss="modal">
                <span>&times;</span>
             </button>
          </div>
          <div class="modal-body">
             <div class="form-group">
                <label class="font-weight-bold">Pilih Tagihan yang Sudah Approved</label>
                <select class="form-control" id="select-tagihan">
                   <option value="">-- Pilih Tagihan --</option>
                   <?php foreach ($available_tagihan as $t):
                        // Skip tagihan yang sudah ada di SPP ini
                        $already_in = false;
                        foreach ($tagihan_list as $existing) {
                           if ($existing->id_tagihan == $t->id_tagihan) {
                              $already_in = true;
                              break;
                           }
                        }
                        if ($already_in) continue;
                     ?>
                      <option value="<?= $t->id_tagihan ?>"
                         data-nosj="<?= $t->no_sj ?? $t->kode_kirim ?? '-' ?>"
                         data-customer="<?= $t->nama_customer ?? '-' ?>"
                         data-ekspedisi="<?= $t->nama_ekspedisi ?? '-' ?>"
                         data-nilai="<?= ($t->nilai_tagihan ?? 0) + ($t->biaya_asuransi ?? 0) ?>">
                         <?= $t->no_sj ?? $t->kode_kirim ?? '-' ?> - <?= $t->nama_customer ?? '-' ?> - Rp <?= number_format(($t->nilai_tagihan ?? 0) + ($t->biaya_asuransi ?? 0), 0, ',', '.') ?>
                      </option>
                   <?php endforeach; ?>
                </select>
             </div>
             <div id="preview-tagihan" class="alert alert-info" style="display:none;">
                <strong>Preview:</strong>
                <ul id="preview-content" class="mb-0 mt-2"></ul>
             </div>
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
             <button type="button" class="btn btn-success" id="btn-add-tagihan">
                <i class="fas fa-plus mr-1"></i> Tambahkan
             </button>
          </div>
       </div>
    </div>
 </div>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
    $(document).ready(function() {
       // Preview tagihan saat dipilih
       $('#select-tagihan').change(function() {
          let selected = $(this).find(':selected');
          if (selected.val()) {
             let nosj = selected.data('nosj');
             let customer = selected.data('customer');
             let ekspedisi = selected.data('ekspedisi');
             let nilai = selected.data('nilai');

             $('#preview-content').html(`
                <li><strong>No. SJ:</strong> ${nosj}</li>
                <li><strong>Customer:</strong> ${customer}</li>
                <li><strong>Ekspedisi:</strong> ${ekspedisi}</li>
                <li><strong>Nilai:</strong> Rp ${number_format(nilai)}</li>
             `);
             $('#preview-tagihan').show();
          } else {
             $('#preview-tagihan').hide();
          }
       });

       // Tambah tagihan ke SPP
       $('#btn-add-tagihan').click(function() {
          let id_tagihan = $('#select-tagihan').val();
          if (!id_tagihan) {
             Swal.fire('Peringatan', 'Silahkan pilih tagihan terlebih dahulu.', 'warning');
             return;
          }

          let btn = $(this);
          let originalText = btn.html();
          btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menambahkan...');

          $.ajax({
             url: '<?= base_url("spp/add_tagihan_to_spp") ?>',
             type: 'POST',
             data: {
                id_spp: '<?= encrypt($spp->id_spp) ?>',
                id_tagihan: id_tagihan
             },
             dataType: 'json',
             success: function(res) {
                btn.prop('disabled', false).html(originalText);
                if (res.status == 'success') {
                   Swal.fire({
                      icon: 'success',
                      title: 'Berhasil!',
                      text: res.message
                   }).then(() => {
                      location.reload();
                   });
                } else {
                   Swal.fire('Gagal', res.message, 'error');
                }
             },
             error: function() {
                btn.prop('disabled', false).html(originalText);
                Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
             }
          });
       });

       // Hapus tagihan dari SPP
       $(document).on('click', '.btn-remove-tagihan', function() {
          let id_tagihan = $(this).data('id-tagihan');
          let row = $(this).closest('tr');

          Swal.fire({
             title: 'Hapus Tagihan?',
             text: "Tagihan ini akan dihapus dari SPP.",
             icon: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#d33',
             cancelButtonColor: '#6c757d',
             confirmButtonText: 'Ya, Hapus!',
             cancelButtonText: 'Batal'
          }).then((result) => {
             if (result.isConfirmed) {
                $.ajax({
                   url: '<?= base_url("spp/remove_tagihan_from_spp") ?>',
                   type: 'POST',
                   data: {
                      id_spp: '<?= encrypt($spp->id_spp) ?>',
                      id_tagihan: id_tagihan
                   },
                   dataType: 'json',
                   success: function(res) {
                      if (res.status == 'success') {
                         Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message
                         }).then(() => {
                            location.reload();
                         });
                      } else {
                         Swal.fire('Gagal', res.message, 'error');
                      }
                   },
                   error: function() {
                      Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
                   }
                });
             }
          });
       });

       // Submit form edit admin
       $('#btn-submit').click(function() {
          let btn = $(this);
          let originalText = btn.html();

          Swal.fire({
             title: 'Simpan Perubahan?',
             text: "Perubahan akan langsung berlaku pada SPP ini.",
             icon: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#ffc107',
             cancelButtonColor: '#6c757d',
             confirmButtonText: 'Ya, Simpan!',
             cancelButtonText: 'Batal'
          }).then((result) => {
             if (result.isConfirmed) {
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');

                $.ajax({
                   url: '<?= base_url("spp/update_admin") ?>',
                   type: 'POST',
                   data: $('#form-edit-admin').serialize(),
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
                            window.location.href = res.redirect || '<?= base_url("spp/detail/" . encrypt($spp->id_spp)) ?>';
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

    // Fungsi hitung Grand Total (total + PPN - diskon)
    function hitungGrandTotal() {
       let totalNilai = parseInt($('#total_nilai_hidden').val()) || 0;
       let ppn = parseInt($('#ppn').val().replace(/[^0-9]/g, '')) || 0;
       let diskon = parseInt($('#diskon').val().replace(/[^0-9]/g, '')) || 0;
       let grandTotal = (totalNilai + ppn) - diskon;

       if (grandTotal < 0) grandTotal = 0;

       $('#display_grand_total').val('Rp ' + number_format(grandTotal));
       $('#grand_total_hidden').val(grandTotal);

       // Hitung ulang pembayaran
       hitungPembayaran();
    }

    // Fungsi hitung pembayaran
    function hitungPembayaran() {
       let grandTotal = parseInt($('#grand_total_hidden').val()) || 0;
       let nilaiBuktiPotong = parseInt($('#nilai_bukti_potong').val().replace(/[^0-9]/g, '')) || 0;
       let nilaiPembayaran = grandTotal - nilaiBuktiPotong;

       if (nilaiPembayaran < 0) nilaiPembayaran = 0;

       $('#display_pembayaran').html('Rp ' + number_format(nilaiPembayaran));
    }

    function number_format(num) {
       return parseInt(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
 </script>