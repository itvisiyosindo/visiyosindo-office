<!-- HEADER -->
<header class="page-header">
   <h2><i class="icons fas fa-file-upload"></i>&nbsp;<?= $page_title ?></h2>
   <div class="right-wrapper text-left">
      <ol class="breadcrumbs">
         <li><span><?= $page_desc ?></span></li>
      </ol>
   </div>
</header>

<div class="row">
   <div class="col">
      <section class="card">
         <div class="card-body">
            <!-- Alert Info -->
            <div class="alert alert-info">
               <h4><i class="fa fa-info-circle"></i> Petunjuk Import:</h4>
               <ol>
                  <li>Download template Excel terlebih dahulu</li>
                  <li>Isi data sesuai format template (NO RESI, TGL RESI, PENERIMA, No Surat Jalan, Desc Product)</li>
                  <li>Pilih ekspedisi, periode, dan status default</li>
                  <li>Upload file Excel yang sudah diisi</li>
                  <li><strong>Catatan:</strong> Nama customer akan dicari otomatis di database. Jika ditemukan, data alamat dan PIC akan diisi otomatis</li>
                  <li><strong>Invoice</strong> akan diinput nanti saat pengajuan tagihan</li>
               </ol>
            </div>

            <!-- Form Import -->
            <form id="form_import_dokumen" method="post" enctype="multipart/form-data">
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="id_ekspedisi">Ekspedisi <span class="text-danger">*</span></label>
                        <select class="form-control" id="id_ekspedisi" name="id_ekspedisi" required>
                           <option value="">-- Pilih Ekspedisi --</option>
                           <?php foreach ($list_eks as $eks): ?>
                              <option value="<?= $eks->id_ekspedisi ?>" data-nama="<?= $eks->nama_ekspedisi ?>">
                                 <?= $eks->nama_ekspedisi ?>
                              </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="periode">Periode <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="periode" name="periode"
                           placeholder="Contoh: September 2025" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="status_default">Status Default <span class="text-danger">*</span></label>
                        <select class="form-control" id="status_default" name="status_default" required>
                           <option value="">-- Pilih Status Default --</option>
                           <option value="1" selected>Proses Kirim</option>
                           <option value="2">Manifest Berangkat</option>
                           <option value="3">Proses Sortir</option>
                           <option value="4">Pengantaran Kurir</option>
                           <option value="5">Diterima</option>
                           <option value="6">Menunggu Konfirmasi</option>
                        </select>
                        <small class="text-muted">Status awal untuk semua data yang diimport</small>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="berkas_excel">File Excel <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="berkas_excel" name="berkas_excel"
                           accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Format: .xlsx, .xls, atau .csv</small>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-12">
                     <hr>
                     <button type="button" id="btn_download_template" class="btn btn-info">
                        <i class="fa fa-download"></i> Download Template Excel
                     </button>
                     <button type="submit" class="btn btn-success">
                        <i class="fa fa-upload"></i> Upload & Import
                     </button>
                     <a href="<?= base_url('tracking/tracking_dokumen') ?>" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Kembali
                     </a>
                  </div>
               </div>
            </form>

            <!-- Progress Bar -->
            <div id="import_progress" class="progress mt-3" style="display:none; margin-top: 20px;">
               <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                  style="width: 0%">0%</div>
            </div>
         </div>
   </div>
</div>
</div>
</div>
</div>

<script>
   // Define base_url for JavaScript
   var base_url = '<?= base_url() ?>';

   document.addEventListener('DOMContentLoaded', function() {
      $(document).ready(function() {
         // Download Template
         $('#btn_download_template').click(function() {
            window.location.href = base_url + 'tracking/download_template_dokumen';
         });

         // Submit Form Import
         $('#form_import_dokumen').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var ekspedisi = $('#id_ekspedisi option:selected').data('nama');
            formData.append('nama_ekspedisi', ekspedisi);

            // Validasi
            if ($('#id_ekspedisi').val() == '') {
               Swal.fire("Error", "Pilih ekspedisi terlebih dahulu", "error");
               return false;
            }

            if ($('#berkas_excel').val() == '') {
               Swal.fire("Error", "Pilih file Excel terlebih dahulu", "error");
               return false;
            }

            // Show progress bar
            $('#import_progress').show();
            $('.progress-bar').css('width', '30%').text('Uploading...');

            $.ajax({
               url: base_url + 'tracking/import_dokumen',
               type: 'POST',
               data: formData,
               contentType: false,
               processData: false,
               dataType: 'json',
               xhr: function() {
                  var xhr = new window.XMLHttpRequest();
                  xhr.upload.addEventListener("progress", function(evt) {
                     if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        $('.progress-bar').css('width', percentComplete + '%')
                           .text(Math.round(percentComplete) + '%');
                     }
                  }, false);
                  return xhr;
               },
               success: function(response) {
                  $('.progress-bar').css('width', '100%').text('Complete!');

                  if (response.status == 'success') {
                     Swal.fire({
                        title: "Berhasil!",
                        text: response.message,
                        icon: "success"
                     }).then(function() {
                        window.location.href = base_url + 'tracking/tracking_dokumen';
                     });
                  } else {
                     Swal.fire("Error", response.message, "error");
                     $('#import_progress').hide();
                  }
               },
               error: function(xhr, status, error) {
                  Swal.fire("Error", "Terjadi kesalahan saat upload: " + error, "error");
                  $('#import_progress').hide();
               }
            });

            return false;
         });
      });
   });
</script>
</div>
</section>
</div>