<header class="page-header">
   <h2><i class="fas fa-chart-line"></i>&nbsp;<?= $page_title ?></h2>
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

   .gradient-warning {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
   }

   .gradient-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
   }

   .select-modern {
      border-radius: 10px;
      border: 2px solid #e0e0e0;
      padding: 12px 18px;
      font-size: 15px;
      transition: all 0.3s ease;
   }

   .select-modern:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
   }

   .btn-filter-custom {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 12px 30px;
      border-radius: 10px;
      font-weight: 500;
      transition: all 0.3s ease;
   }

   .btn-filter-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
   }

   .table-modern {
      border-radius: 10px;
      overflow: hidden;
   }

   .table-modern thead {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
   }

   .table-modern thead th {
      border: none;
      padding: 16px 12px;
      font-weight: 500;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 0.8px;
   }

   .table-modern tbody td {
      padding: 14px 12px;
      vertical-align: middle;
   }

   .table-modern tbody tr {
      transition: all 0.2s ease;
   }

   .table-modern tbody tr:hover {
      background-color: #f8f9ff;
   }

   /* Custom DataTable Controls */
   .dataTables_wrapper .dataTables_length,
   .dataTables_wrapper .dataTables_filter {
      display: none;
   }

   .table-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
      flex-wrap: wrap;
      gap: 12px;
   }

   .custom-search-box {
      position: relative;
      max-width: 280px;
   }

   .custom-search-box input {
      width: 100%;
      padding: 10px 15px 10px 40px;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
      background: #f8f9fa;
   }

   .custom-search-box input:focus {
      border-color: #667eea;
      outline: none;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
      background: white;
   }

   .custom-search-box .search-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #adb5bd;
   }

   .custom-length-select {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      color: #6c757d;
   }

   .custom-length-select select {
      padding: 8px 12px;
      border: 1px solid #e0e0e0;
      border-radius: 6px;
      font-size: 14px;
      background: #f8f9fa;
      cursor: pointer;
   }

   .custom-length-select select:focus {
      border-color: #667eea;
      outline: none;
   }

   .dataTables_wrapper .dataTables_info {
      padding: 12px 0;
      color: #6c757d;
      font-size: 13px;
   }

   .dataTables_wrapper .dataTables_paginate {
      padding: 12px 0;
   }

   .dataTables_wrapper .dataTables_paginate .paginate_button {
      border-radius: 6px !important;
      margin: 0 2px;
      padding: 6px 12px !important;
   }

   .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
      border: none !important;
      color: white !important;
   }

   .dataTables_wrapper .dataTables_processing {
      background: transparent !important;
      border: none !important;
      box-shadow: none !important;
   }

   /* Simple Loading Dots */
   .loading-dots {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 40px;
   }

   .loading-dots span {
      width: 10px;
      height: 10px;
      background: linear-gradient(135deg, #f093fb, #f5576c);
      border-radius: 50%;
      animation: bounce 1.4s ease-in-out infinite both;
   }

   .loading-dots span:nth-child(1) {
      animation-delay: -0.32s;
   }

   .loading-dots span:nth-child(2) {
      animation-delay: -0.16s;
   }

   .loading-dots span:nth-child(3) {
      animation-delay: 0s;
   }

   @keyframes bounce {

      0%,
      80%,
      100% {
         transform: scale(0.6);
         opacity: 0.5;
      }

      40% {
         transform: scale(1);
         opacity: 1;
      }
   }

   .badge-nilai {
      padding: 8px 16px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 13px;
   }

   .badge-a {
      background: linear-gradient(135deg, #11998e, #38ef7d);
      color: white;
   }

   .badge-b {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
   }

   .badge-c {
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: white;
   }

   .badge-d {
      background: linear-gradient(135deg, #f093fb, #f5576c);
      color: white;
   }

   .badge-e {
      background: linear-gradient(135deg, #eb3349, #f45c43);
      color: white;
   }

   .badge-none {
      background: #e0e0e0;
      color: #666;
   }

   .btn-edit-pk {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      border: none;
      color: white;
      padding: 8px 20px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
      transition: all 0.3s ease;
   }

   .btn-edit-pk:hover {
      color: white;
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(240, 147, 251, 0.4);
   }

   .modal-modern .modal-content {
      border: none;
      border-radius: 15px;
      overflow: hidden;
   }

   .modal-modern .modal-header {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      color: white;
      border: none;
      padding: 20px 25px;
   }

   .modal-modern .modal-header .close {
      color: white;
      opacity: 0.8;
   }

   .modal-modern .modal-body {
      padding: 30px;
   }

   .modal-modern .modal-footer {
      border: none;
      padding: 20px 25px;
      background: #f8f9fa;
   }

   .form-group-modern label {
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
   }

   .form-group-modern .form-control {
      border-radius: 10px;
      border: 2px solid #e0e0e0;
      padding: 12px 15px;
      transition: all 0.3s ease;
   }

   .form-group-modern .form-control:focus {
      border-color: #f093fb;
      box-shadow: 0 0 0 4px rgba(240, 147, 251, 0.15);
   }

   .form-group-modern .form-control:read-only {
      background: #f8f9fa;
   }

   .nilai-slider {
      -webkit-appearance: none;
      width: 100%;
      height: 8px;
      border-radius: 4px;
      background: linear-gradient(90deg, #eb3349 0%, #ffc107 50%, #28a745 100%);
      outline: none;
      margin: 15px 0;
   }

   .nilai-slider::-webkit-slider-thumb {
      -webkit-appearance: none;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: white;
      cursor: pointer;
      border: 3px solid #667eea;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
   }

   .preview-nilai {
      text-align: center;
      padding: 20px;
      background: #f8f9fa;
      border-radius: 12px;
      margin-top: 15px;
   }

   .preview-nilai .score {
      font-size: 48px;
      font-weight: 700;
      line-height: 1;
   }

   .info-alert {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
      border: none;
      border-left: 4px solid #667eea;
      border-radius: 10px;
      padding: 15px 20px;
   }

   .employee-info {
      display: flex;
      align-items: center;
      padding: 15px;
      background: linear-gradient(135deg, rgba(240, 147, 251, 0.1), rgba(245, 87, 108, 0.1));
      border-radius: 12px;
      margin-bottom: 20px;
   }

   .employee-info .avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: linear-gradient(135deg, #f093fb, #f5576c);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 20px;
      margin-right: 15px;
   }
</style>

<!-- Filter Card -->
<div class="row mb-4">
   <div class="col-12">
      <div class="card card-custom">
         <div class="card-header card-header-gradient gradient-primary">
            <h5 class="mb-0"><i class="fa fa-filter mr-2"></i> Filter Periode</h5>
         </div>
         <div class="card-body">
            <div class="row align-items-end">
               <div class="col-md-3 mb-3 mb-md-0">
                  <label class="font-weight-bold text-muted small">TAHUN</label>
                  <select name="tahun" id="filter_tahun" class="form-control select-modern">
                     <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == $tahun ? 'selected' : '' ?>><?= $y ?></option>
                     <?php endfor; ?>
                  </select>
               </div>
               <div class="col-md-3 mb-3 mb-md-0">
                  <label class="font-weight-bold text-muted small">SEMESTER</label>
                  <select name="semester" id="filter_semester" class="form-control select-modern">
                     <option value="1" <?= $semester == 1 ? 'selected' : '' ?>>Semester 1 (Jan - Jun)</option>
                     <option value="2" <?= $semester == 2 ? 'selected' : '' ?>>Semester 2 (Jul - Des)</option>
                  </select>
               </div>
               <div class="col-md-3 mb-3 mb-md-0">
                  <button type="button" id="btn-filter" class="btn btn-primary btn-filter-custom btn-block text-white">
                     <i class="fa fa-search mr-2"></i> Tampilkan
                  </button>
               </div>
               <div class="col-md-3 text-md-right">
                  <a href="<?= site_url('hasil_evaluasi_semester') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>"
                     class="btn btn-outline-secondary btn-block">
                     <i class="fa fa-arrow-left mr-2"></i> Kembali
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Data Table Card -->
<div class="row">
   <div class="col-12">
      <div class="card card-custom">
         <div class="card-header card-header-gradient gradient-warning d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-book mr-2"></i> Input Nilai Product Knowledge</h5>
            <span class="badge badge-light text-dark" style="font-size: 13px; padding: 8px 15px;">
               <?= $periode_label ?>
            </span>
         </div>
         <div class="card-body">
            <div class="alert info-alert mb-4">
               <div class="d-flex align-items-center">
                  <i class="fa fa-lightbulb fa-2x text-warning mr-3"></i>
                  <div>
                     <strong>Petunjuk Pengisian:</strong><br>
                     <small>Klik tombol <strong>Edit</strong> untuk mengisi atau mengubah Nilai Product Knowledge. Nilai berkisar antara 0 - 100.</small>
                  </div>
               </div>
            </div>

            <!-- Custom Table Controls -->
            <div class="table-controls">
               <div class="custom-length-select">
                  <span>Tampilkan</span>
                  <select id="customLength">
                     <option value="10">10</option>
                     <option value="25" selected>25</option>
                     <option value="50">50</option>
                     <option value="100">100</option>
                  </select>
                  <span>data</span>
               </div>
               <div class="custom-search-box">
                  <i class="fa fa-search search-icon"></i>
                  <input type="text" id="customSearch" placeholder="Cari nama karyawan...">
               </div>
            </div>

            <div class="table-responsive">
               <table id="table-nilai-pk" class="table table-modern" style="width:100%">
                  <thead>
                     <tr>
                        <th class="text-center" width="5%">No</th>
                        <th width="25%">Nama Pegawai</th>
                        <th width="15%">Jabatan</th>
                        <th class="text-center" width="12%">Nilai PK</th>
                        <th class="text-center" width="12%">Predikat</th>
                        <th class="text-center" width="12%">Aksi</th>
                     </tr>
                  </thead>
                  <tbody>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Modal Input Nilai PK -->
<div class="modal fade modal-modern" id="modalNilaiPK" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <form id="form-nilai-pk">
            <div class="modal-header">
               <h5 class="modal-title"><i class="fa fa-edit mr-2"></i> Input Nilai Product Knowledge</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <input type="hidden" name="id_pengguna" id="input_id_pengguna">
               <input type="hidden" name="tahun" id="input_tahun" value="<?= $tahun ?>">
               <input type="hidden" name="semester" id="input_semester" value="<?= $semester ?>">

               <!-- Employee Info -->
               <div class="employee-info">
                  <div class="avatar" id="avatar-initial">-</div>
                  <div>
                     <strong id="display_nama">-</strong><br>
                     <small class="text-muted" id="display_jabatan">-</small>
                  </div>
               </div>

               <div class="form-group form-group-modern">
                  <label>Nilai Product Knowledge <span class="text-danger">*</span></label>
                  <input type="range" id="input_nilai_slider" class="nilai-slider" min="0" max="100" value="0">
                  <input type="number" name="nilai" id="input_nilai" class="form-control text-center"
                     min="0" max="100" step="0.01" required placeholder="0 - 100" style="font-size: 24px; font-weight: 700;">
               </div>

               <div class="preview-nilai" id="preview-predikat">
                  <div class="score" id="preview-score">0</div>
                  <div class="mt-2">
                     <span id="badge-predikat" class="badge-nilai badge-none">Belum Ada Nilai</span>
                  </div>
               </div>

               <div class="form-group form-group-modern mt-4">
                  <label>Keterangan (Opsional)</label>
                  <textarea name="keterangan" id="input_keterangan" class="form-control" rows="3"
                     placeholder="Catatan tambahan..."></textarea>
               </div>

               <!-- Checkbox untuk kirim WA -->
               <div class="form-group mt-3">
                  <div class="custom-control custom-checkbox">
                     <input type="checkbox" class="custom-control-input" id="send_wa" name="send_wa" value="1">
                     <label class="custom-control-label" for="send_wa">
                        <i class="fab fa-whatsapp text-success mr-1"></i> Kirim notifikasi WhatsApp ke karyawan
                     </label>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-light" data-dismiss="modal">
                  <i class="fa fa-times mr-1"></i> Batal
               </button>
               <button type="submit" class="btn btn-edit-pk" id="btn-simpan" style="padding: 10px 30px;">
                  <i class="fa fa-save mr-1"></i> Simpan Nilai
               </button>
            </div>
         </form>
      </div>
   </div>
</div>

<script>
   var table;

   document.addEventListener('DOMContentLoaded', function() {
      // Initialize DataTable
      loadTable();

      // Filter button
      $('#btn-filter').click(function() {
         var btn = $(this);
         var tahun = $('#filter_tahun').val();
         var semester = $('#filter_semester').val();

         $('#input_tahun').val(tahun);
         $('#input_semester').val(semester);

         btn.html('<i class="fa fa-spinner fa-spin mr-2"></i> Memuat...').prop('disabled', true);
         table.ajax.reload(function() {
            btn.html('<i class="fa fa-search mr-2"></i> Tampilkan').prop('disabled', false);
         });

         var newUrl = '<?= site_url('hasil_evaluasi_semester/nilai_pk') ?>?tahun=' + tahun + '&semester=' + semester;
         window.history.pushState({}, '', newUrl);
      });

      // Sync slider with input
      $('#input_nilai_slider').on('input', function() {
         $('#input_nilai').val($(this).val());
         updatePreview($(this).val());
      });

      $('#input_nilai').on('input', function() {
         var val = Math.min(100, Math.max(0, $(this).val() || 0));
         $('#input_nilai_slider').val(val);
         updatePreview(val);
      });

      // Form submit
      $('#form-nilai-pk').submit(function(e) {
         e.preventDefault();

         var nilai = parseFloat($('#input_nilai').val());
         if (isNaN(nilai) || nilai < 0 || nilai > 100) {
            Swal.fire('Peringatan', 'Nilai harus antara 0 - 100', 'warning');
            return;
         }

         var sendWa = $('#send_wa').is(':checked') ? '1' : '0';
         var formData = $(this).serialize() + '&send_wa=' + sendWa;

         $('#btn-simpan').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Menyimpan...');

         $.ajax({
            url: '<?= site_url('hasil_evaluasi_semester/save_nilai_pk') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
               if (response.status) {
                  $('#modalNilaiPK').modal('hide');
                  table.ajax.reload(null, false);
                  Swal.fire({
                     icon: 'success',
                     title: 'Berhasil!',
                     text: response.message,
                     timer: 2500,
                     showConfirmButton: false
                  });
               } else {
                  Swal.fire('Error', response.message || 'Terjadi kesalahan', 'error');
               }
            },
            error: function(xhr, status, error) {
               console.log('Error:', xhr.responseText);
               Swal.fire('Error', 'Terjadi kesalahan pada server: ' + error, 'error');
            },
            complete: function() {
               $('#btn-simpan').prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Simpan Nilai');
            }
         });
      });
   });

   function loadTable() {
      table = $('#table-nilai-pk').DataTable({
         processing: true,
         serverSide: true,
         deferRender: true,
         ajax: {
            url: '<?= site_url('hasil_evaluasi_semester/pagination_nilai_pk') ?>',
            type: 'POST',
            data: function(d) {
               d.tahun = $('#filter_tahun').val();
               d.semester = $('#filter_semester').val();
            }
         },
         columns: [{
               data: 'no',
               orderable: false,
               searchable: false,
               className: 'text-center'
            },
            {
               data: 'nama'
            },
            {
               data: 'jabatan'
            },
            {
               data: 'nilai_pk',
               className: 'text-center',
               render: function(data) {
                  if (!data || data == 0) return '<span class="text-muted">-</span>';
                  return '<strong>' + parseFloat(data).toFixed(1) + '</strong>';
               }
            },
            {
               data: 'nilai_pk',
               className: 'text-center',
               orderable: false,
               render: function(data) {
                  if (!data || data == 0) return '<span class="badge-nilai badge-none">-</span>';
                  var p = getPredikat(parseFloat(data));
                  return '<span class="badge-nilai badge-' + p.code.toLowerCase() + '">' + p.code + '</span>';
               }
            },
            {
               data: 'action',
               orderable: false,
               searchable: false,
               className: 'text-center'
            }
         ],
         order: [
            [1, 'asc']
         ],
         language: {
            processing: '<div class="loading-dots"><span></span><span></span><span></span></div>',
            emptyTable: '<div class="text-center py-5"><i class="fa fa-users fa-3x mb-3" style="color:#dee2e6;"></i><p class="text-muted mb-0">Tidak ada data karyawan</p></div>',
            info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            zeroRecords: '<div class="text-center py-5"><i class="fa fa-search fa-3x mb-3" style="color:#dee2e6;"></i><p class="text-muted mb-0">Data tidak ditemukan</p></div>',
            paginate: {
               first: '<i class="fa fa-angle-double-left"></i>',
               last: '<i class="fa fa-angle-double-right"></i>',
               next: '<i class="fa fa-angle-right"></i>',
               previous: '<i class="fa fa-angle-left"></i>'
            }
         },
         pageLength: 25,
         dom: 'rtip'
      });

      // Custom search box
      var searchTimeout;
      $('#customSearch').on('keyup', function() {
         var searchVal = $(this).val();
         clearTimeout(searchTimeout);
         searchTimeout = setTimeout(function() {
            table.search(searchVal).draw();
         }, 400);
      });

      // Custom length select
      $('#customLength').on('change', function() {
         table.page.len($(this).val()).draw();
      });
   }

   function editNilaiPK(id, nama, jabatan, nilai, keterangan) {
      $('#input_id_pengguna').val(id);
      $('#display_nama').text(nama);
      $('#display_jabatan').text(jabatan);
      $('#avatar-initial').text(nama.charAt(0).toUpperCase());
      $('#input_nilai').val(nilai || '');
      $('#input_nilai_slider').val(nilai || 0);
      $('#input_keterangan').val(keterangan || '');

      updatePreview(nilai || 0);
      $('#modalNilaiPK').modal('show');
   }

   function updatePreview(nilai) {
      nilai = parseFloat(nilai) || 0;
      var p = getPredikat(nilai);

      $('#preview-score').text(nilai.toFixed(0)).removeClass().addClass('score text-' + p.class);
      $('#badge-predikat').removeClass().addClass('badge-nilai badge-' + p.code.toLowerCase()).text(p.label);
   }

   function getPredikat(nilai) {
      if (nilai >= 90) return {
         code: 'A',
         class: 'success',
         label: 'A - Sangat Baik'
      };
      if (nilai >= 80) return {
         code: 'B',
         class: 'primary',
         label: 'B - Baik'
      };
      if (nilai >= 70) return {
         code: 'C',
         class: 'info',
         label: 'C - Cukup'
      };
      if (nilai >= 60) return {
         code: 'D',
         class: 'warning',
         label: 'D - Kurang'
      };
      if (nilai > 0) return {
         code: 'E',
         class: 'danger',
         label: 'E - Sangat Kurang'
      };
      return {
         code: 'none',
         class: 'secondary',
         label: 'Belum Ada Nilai'
      };
   }
</script>