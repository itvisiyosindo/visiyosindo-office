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
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }

  .card-custom:hover {
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
  }

  .card-header-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0 !important;
    padding: 15px 20px;
  }

  .card-header-filter {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  }

  .card-header-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  }

  .btn-filter-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 10px 25px;
    font-weight: 500;
  }

  .btn-filter-custom:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: translateY(-2px);
  }

  .btn-pk {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border: none;
  }

  .btn-export {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border: none;
  }

  .table-modern thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }

  .table-modern thead th {
    border: none;
    padding: 15px 10px;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
  }

  .table-modern tbody tr:hover {
    background-color: #f8f9ff;
  }

  .table-modern tbody td {
    vertical-align: middle;
    padding: 12px 10px;
  }

  .badge-nilai {
    padding: 8px 12px;
    border-radius: 20px;
    font-weight: 500;
    min-width: 60px;
    display: inline-block;
  }

  .badge-a {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
  }

  .badge-b {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }

  .badge-c {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
  }

  .badge-d {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
  }

  .badge-e {
    background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    color: white;
  }

  .badge-sp {
    background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    color: white;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {

    0%,
    100% {
      opacity: 1;
    }

    50% {
      opacity: 0.7;
    }
  }

  .btn-detail {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 6px 15px;
    border-radius: 20px;
    color: white;
    font-size: 12px;
    transition: all 0.3s ease;
  }

  .btn-detail:hover {
    transform: scale(1.05);
    color: white;
    box-shadow: 0 3px 10px rgba(102, 126, 234, 0.4);
  }

  .select-modern {
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    padding: 10px 15px;
    transition: all 0.3s ease;
  }

  .select-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
  }

  .info-item {
    padding: 10px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
    border-left: 4px solid #667eea;
  }

  .loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
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
    background: linear-gradient(135deg, #667eea, #764ba2);
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

  /* DataTables Styling */
  .dataTables_wrapper {
    padding: 0;
  }

  .dataTables_wrapper .dataTables_length {
    display: none;
  }

  .dataTables_wrapper .dataTables_filter {
    display: none;
  }

  .dataTables_wrapper .dataTables_processing {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
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

  .custom-search-box input::placeholder {
    color: #adb5bd;
  }

  .custom-search-box .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #adb5bd;
    font-size: 14px;
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
    transition: all 0.2s ease;
  }

  .custom-length-select select:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
  }

  .table-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 12px;
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
    font-size: 13px;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none !important;
    color: white !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
    background: #f0f0f0 !important;
    border: 1px solid #ddd !important;
    color: #333 !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
</style>

<!-- Filter Card -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card card-custom">
      <div class="card-header card-header-custom card-header-filter">
        <h5 class="mb-0"><i class="fa fa-filter mr-2"></i> Filter Periode</h5>
      </div>
      <div class="card-body">
        <div class="row align-items-end">
          <div class="col-md-3 mb-3 mb-md-0">
            <label class="font-weight-bold text-muted small">TAHUN</label>
            <select name="tahun" id="filterTahun" class="form-control select-modern">
              <?php
              $thn_skrg = date('Y');
              for ($i = $thn_skrg; $i >= $thn_skrg - 5; $i--) {
                $sel = ($i == $tahun) ? 'selected' : '';
                echo "<option value='$i' $sel>$i</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-3 mb-3 mb-md-0">
            <label class="font-weight-bold text-muted small">SEMESTER</label>
            <select name="semester" id="filterSemester" class="form-control select-modern">
              <option value="1" <?= $semester == 1 ? 'selected' : '' ?>>Semester 1 (Jan - Jun)</option>
              <option value="2" <?= $semester == 2 ? 'selected' : '' ?>>Semester 2 (Jul - Des)</option>
            </select>
          </div>
          <div class="col-md-2 mb-3 mb-md-0">
            <button type="button" id="btnFilter" class="btn btn-primary btn-filter-custom btn-block text-white">
              <i class="fa fa-search mr-1"></i> Tampilkan
            </button>
          </div>
          <div class="col-md-4 text-md-right">
            <div class="btn-group mr-2">
              <button type="button" class="btn btn-pk text-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-edit mr-1"></i> Input Nilai
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="<?= site_url('hasil_evaluasi_semester/nilai_pk') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>" id="btnNilaiPK">
                  <i class="fa fa-book text-info mr-2"></i> Nilai Product Knowledge
                </a>
                <a class="dropdown-item" href="<?= site_url('hasil_evaluasi_semester/nilai_laporan') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>" id="btnNilaiLaporan">
                  <i class="fa fa-file-alt text-primary mr-2"></i> Nilai Rata-rata Laporan
                </a>
                <?php if (isAdmin() || isHrd() || (!empty($is_allowed_admin) && $is_allowed_admin)): ?>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="<?= site_url('hasil_evaluasi_semester/pengaturan') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>" id="btnPengaturan">
                    <i class="fa fa-cog text-secondary mr-2"></i> Pengaturan Mode Input
                  </a>
                <?php endif; ?>
              </div>
            </div>
            <a href="<?= site_url('hasil_evaluasi_semester/print_evaluasi') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>"
              class="btn btn-export text-white" id="btnExport" target="_blank">
              <i class="fa fa-print mr-1"></i> Print
            </a>
            <a href="<?= site_url('hasil_evaluasi_semester/export_excel') ?>?tahun=<?= $tahun ?>&semester=<?= $semester ?>" 
             class="btn btn-success text-white mr-2" id="btnExportExcel" target="_blank" 
             style="background: linear-gradient(135deg, #1d976c 0%, #93f9b9 100%); border:none;">
             <i class="fa fa-file-excel mr-1"></i> Excel
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Data Table Card -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card card-custom">
      <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fa fa-table mr-2"></i> Daftar Hasil Evaluasi Karyawan</h5>
        <span class="badge badge-light text-dark" id="totalRecords" style="padding: 8px 16px; font-size: 13px;">0 Karyawan</span>
      </div>
      <div class="card-body">
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
          <table class="table table-modern mb-0" id="table-evaluasi" style="width:100%">
            <thead>
              <tr>
                <th class="text-center" width="4%">No</th>
                <th width="16%">Nama Pegawai</th>
                <th width="10%">Jabatan</th>
                <th class="text-center" width="9%">Rata Laporan</th>
                <th class="text-center" width="9%">Rata Evaluasi</th>
                <th class="text-center" width="8%">Total Hadir</th>
                <th class="text-center" width="10%">Nilai SP</th>
                <th class="text-center" width="8%">Nilai PK</th>
                <th class="text-center" width="12%">Total Nilai Evaluasi</th>
                <th class="text-center" width="10%">Aksi</th>
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

<!-- Info Card -->
<div class="row">
  <div class="col-12">
    <div class="card card-custom">
      <div class="card-header card-header-custom card-header-info">
        <h5 class="mb-0"><i class="fa fa-info-circle mr-2"></i> Keterangan</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="info-item">
              <strong><i class="fa fa-file-alt text-primary mr-2"></i> Rata Laporan</strong>
              <p class="mb-0 text-muted small mt-1">Rata-rata nilai laporan mingguan + penilaian umum dalam 1 semester</p>
            </div>
            <div class="info-item">
              <strong><i class="fa fa-star text-warning mr-2"></i> Rata Evaluasi</strong>
              <p class="mb-0 text-muted small mt-1">Rata-rata nilai evaluasi semester dari penilai</p>
            </div>
            <div class="info-item">
              <strong><i class="fa fa-calendar-check text-success mr-2"></i> Total Hadir</strong>
              <p class="mb-0 text-muted small mt-1">Jumlah hari kehadiran (masuk + cuti) dalam 1 semester</p>
            </div>
            <div class="info-item" style="border-left-color: #38ef7d;">
              <strong><i class="fa fa-graduation-cap text-success mr-2"></i> Predikat</strong>
              <p class="mb-0 text-muted small mt-1">A ≥90 | B ≥80 | C ≥70 | D ≥60 | E &lt;60</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-item" style="border-left-color: #f45c43;">
              <strong><i class="fa fa-exclamation-triangle text-danger mr-2"></i> Nilai SP</strong>
              <p class="mb-0 text-muted small mt-1">Tidak ada SP = 100 | SP 1 = 80 | SP 2 = 60 | SP 3 = 40</p>
            </div>
            <div class="info-item" style="border-left-color: #f5576c;">
              <strong><i class="fa fa-book text-info mr-2"></i> Nilai PK</strong>
              <p class="mb-0 text-muted small mt-1">Nilai Product Knowledge (input manual)</p>
            </div>
            <div class="info-item" style="border-left-color: #667eea;">
              <strong><i class="fa fa-chart-line text-primary mr-2"></i> Total Nilai Evaluasi Semester</strong>
              <p class="mb-0 text-muted small mt-1">
                <strong>Karyawan Biasa:</strong> 40% Evaluasi + 5% SP + 25% PK + 5% Kehadiran + 25% Laporan<br>
                <strong>HR & Legal / GA:</strong> 30% Evaluasi + 20% SP + 20% Kehadiran + 30% Laporan<br>
                <strong>Security:</strong> 70% Evaluasi + 15% Kehadiran + 15% SP<br>
                <strong>Helper:</strong> 50% Evaluasi + 10% SP + 10% PK + 30% Kehadiran
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var table = $('#table-evaluasi').DataTable({
      processing: true,
      serverSide: true,
      ordering: false,
      deferRender: true,
      ajax: {
        url: "<?= site_url('hasil_evaluasi_semester/pagination') ?>",
        type: "POST",
        data: function(d) {
          d.tahun = $('#filterTahun').val();
          d.semester = $('#filterSemester').val();
        },
        dataSrc: function(json) {
          $('#totalRecords').text(json.recordsTotal + ' Karyawan');
          return json.data;
        }
      },
      columns: [{
          data: 0,
          className: 'text-center'
        },
        {
          data: 1
        },
        {
          data: 2
        },
        {
          data: 3,
          className: 'text-center'
        },
        {
          data: 4,
          className: 'text-center'
        },
        {
          data: 5,
          className: 'text-center'
        },
        {
          data: 6,
          className: 'text-center'
        },
        {
          data: 7,
          className: 'text-center'
        },
        {
          data: 8,
          className: 'text-center'
        },
        {
          data: 9,
          className: 'text-center'
        }
      ],
      language: {
        processing: '<div class="loading-dots"><span></span><span></span><span></span></div>',
        emptyTable: '<div class="text-center py-5"><i class="fa fa-inbox fa-3x mb-3" style="color:#dee2e6;"></i><p class="text-muted mb-0">Tidak ada data karyawan</p></div>',
        info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
        infoEmpty: 'Tidak ada data',
        infoFiltered: '(filter dari _MAX_ total)',
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

    // Filter button
    $('#btnFilter').on('click', function() {
      var btn = $(this);
      btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Memuat...').prop('disabled', true);
      table.ajax.reload(function() {
        btn.html('<i class="fa fa-search mr-1"></i> Tampilkan').prop('disabled', false);
      });
    });

    // Update links when filter changes
    $('#filterTahun, #filterSemester').on('change', function() {
      var tahun = $('#filterTahun').val();
      var semester = $('#filterSemester').val();

      $('#btnNilaiPK').attr('href', '<?= site_url('hasil_evaluasi_semester/nilai_pk') ?>?tahun=' + tahun + '&semester=' + semester);
      $('#btnNilaiLaporan').attr('href', '<?= site_url('hasil_evaluasi_semester/nilai_laporan') ?>?tahun=' + tahun + '&semester=' + semester);
      $('#btnPengaturan').attr('href', '<?= site_url('hasil_evaluasi_semester/pengaturan') ?>?tahun=' + tahun + '&semester=' + semester);
      $('#btnExport').attr('href', '<?= site_url('hasil_evaluasi_semester/print_evaluasi') ?>?tahun=' + tahun + '&semester=' + semester);

      var newUrl = '<?= site_url('hasil_evaluasi_semester') ?>?tahun=' + tahun + '&semester=' + semester;
      window.history.pushState({}, '', newUrl);
    });
    
    $('#filterTahun, #filterSemester').on('change', function() {
      var tahun = $('#filterTahun').val();
      var semester = $('#filterSemester').val();

      // Update link tombol Nilai PK
      $('#btnNilaiPK').attr('href', '<?= site_url('hasil_evaluasi_semester/nilai_pk') ?>?tahun=' + tahun + '&semester=' + semester);
      
      // Update link tombol Print
      $('#btnExport').attr('href', '<?= site_url('hasil_evaluasi_semester/print_evaluasi') ?>?tahun=' + tahun + '&semester=' + semester);

      // Update link tombol Excel (BARU)
      $('#btnExportExcel').attr('href', '<?= site_url('hasil_evaluasi_semester/export_excel') ?>?tahun=' + tahun + '&semester=' + semester); // <--- TAMBAHKAN INI

      var newUrl = '<?= site_url('hasil_evaluasi_semester') ?>?tahun=' + tahun + '&semester=' + semester;
      window.history.pushState({}, '', newUrl);
    });

    // Send WhatsApp Notification
    $(document).on('click', '.btn-send-wa', function() {
      var id = $(this).data('id');
      var nama = $(this).data('nama');
      var hp = $(this).data('hp');
      var tahun = $('#filterTahun').val();
      var semester = $('#filterSemester').val();

      if (!hp) {
        Swal.fire({
          icon: 'warning',
          title: 'Nomor HP Kosong',
          text: 'Nomor HP untuk ' + nama + ' belum diisi di data pengguna.',
          confirmButtonColor: '#667eea'
        });
        return;
      }

      Swal.fire({
        title: 'Kirim Notifikasi WhatsApp?',
        html: 'Kirim hasil evaluasi semester ke:<br><strong>' + nama + '</strong><br><small class="text-muted">' + hp + '</small>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#25d366',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fab fa-whatsapp mr-1"></i> Kirim',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Show loading
          Swal.fire({
            title: 'Mengirim...',
            html: 'Mohon tunggu, sedang mengirim notifikasi ke WhatsApp.',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          // Send AJAX
          $.ajax({
            url: '<?= site_url('hasil_evaluasi_semester/send_wa_notif') ?>',
            type: 'POST',
            data: {
              id_pengguna: id,
              tahun: tahun,
              semester: semester
            },
            dataType: 'json',
            success: function(res) {
              if (res.status) {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil!',
                  text: res.message,
                  confirmButtonColor: '#25d366'
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: res.message,
                  confirmButtonColor: '#dc3545'
                });
              }
            },
            error: function() {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mengirim notifikasi.',
                confirmButtonColor: '#dc3545'
              });
            }
          });
        }
      });
    });
  });
</script>