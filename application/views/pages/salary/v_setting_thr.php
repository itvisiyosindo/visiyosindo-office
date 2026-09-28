<style>
  /* CSS UNTUK TOGGLE SWITCH */
  .switch-container {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 30px;
    margin-bottom: 0;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #28a745;
    /* Warna Default Hijau (Muslim) */
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 34px;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 50%;
  }

  /* State Checked (Natal) */
  input:checked+.slider {
    background-color: #dc3545;
    /* Warna Merah (Natal) */
  }

  input:checked+.slider:before {
    -webkit-transform: translateX(30px);
    -ms-transform: translateX(30px);
    transform: translateX(30px);
  }

  /* Label Text Styling */
  .thr-label {
    font-weight: bold;
    font-size: 14px;
    min-width: 150px;
    /* Supaya rapi tidak geser-geser */
  }

  .text-muslim {
    color: #28a745;
  }

  .text-natal {
    color: #dc3545;
  }
</style>

<header class="page-header">
  <h2><i class="fas fa-cogs"></i>&nbsp; <?= $page_title ?></h2>
  <div class="right-wrapper text-left">
    <ol class="breadcrumbs">
      <li><span><?= $page_desc ?></span></li>
    </ol>
  </div>
</header>

<div class="row">
  <div class="col-12 mb-2">
    <div class="card-body bg-info">
      <h5 class="text-white"><i class="fas fa-info-circle"></i> Petunjuk</h5>
      <p class="text-white mb-0">
        Gunakan <strong>Switch</strong> di bawah untuk mengatur jenis THR. <br>
        <span class="badge badge-success">Hijau</span> = Idul Fitri (Muslim) |
        <span class="badge badge-danger">Merah</span> = Natal (Kristen/Katolik).
      </p>
    </div>
  </div>

  <div class="col-12 mb-3">
    <div class="alert alert-warning border-left-warning shadow-sm mb-3" role="alert">
      <div class="d-flex align-items-center">
        <div class="mr-3">
          <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
        </div>
        <div>
          <h6 class="font-weight-bold mb-1">Penting Sebelum Generate:</h6>
          <ul class="mb-0 pl-3 small">
            <li>Fitur Generate hanya akan memproses karyawan yang <strong>sudah memiliki NPP (Nomor Pegawai)</strong>.</li>
            <li>Pastikan data NPP di menu Master Karyawan sudah lengkap.</li>
            <li>Data akan diset default menjadi <strong>IDUL FITRI (Muslim)</strong>, silakan ubah manual jika karyawan tersebut Non-Muslim.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <div class="mb-3 d-flex justify-content-between align-items-center">
            <a href="<?= base_url('salary_thr') ?>" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Kembali ke Menu THR
            </a>

            <button type="button" class="btn btn-warning font-weight-bold" id="btn-generate">
              <i class="fas fa-magic"></i> Generate Data Default
            </button>
          </div>

          <table class="table table-striped table-bordered table-hover" id="table-setting-thr">
            <thead class="thead-dark">
              <tr>
                <th width="5%" class="text-center">No</th>
                <th width="10%" class="text-center">NPP</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th width="25%" class="text-center">Setting THR</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($karyawan as $row) :
                // Logic: Jika NULL atau IDUL_FITRI maka dianggap Muslim (Switch OFF)
                // Jika NATAL maka Switch ON
                $is_natal = ($row->tipe_thr == 'NATAL');
              ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td class="text-center font-weight-bold"><?= $row->no_pegawai ?></td>
                  <td style="font-weight:bold;"><?= $row->nama ?></td>
                  <td><?= $row->jabatan ?></td>
                  <td>
                    <div class="switch-container">
                      <label class="switch">
                        <input type="checkbox" class="toggle-thr"
                          data-id="<?= encrypt($row->pengguna_id) ?>"
                          <?= $is_natal ? 'checked' : '' ?>>
                        <span class="slider"></span>
                      </label>

                      <span class="thr-label <?= $is_natal ? 'text-natal' : 'text-muslim' ?>"
                        id="label-<?= encrypt($row->pengguna_id) ?>">
                        <?= $is_natal ? 'NATAL' : 'IDUL FITRI (Muslim)' ?>
                      </span>
                    </div>
                    <small class="status-msg" id="msg-<?= encrypt($row->pengguna_id) ?>"></small>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-preview-generate" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title font-weight-bold">
            <i class="fas fa-clipboard-check"></i> Konfirmasi Generate Data
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Sistem menemukan data karyawan berikut yang belum masuk ke database THR. <br>
            Apakah Anda yakin ingin memasukkan mereka dengan status default <strong>IDUL FITRI</strong>?</p>

          <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-sm table-bordered table-striped">
              <thead class="thead-light">
                <tr>
                  <th class="text-center" width="5%">No</th>
                  <th class="text-center" width="20%">NPP</th>
                  <th>Nama Karyawan</th>
                  <th>Jabatan</th>
                </tr>
              </thead>
              <tbody id="preview-table-body">
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary font-weight-bold" id="btn-confirm-execute">
            <i class="fas fa-save"></i> Ya, Simpan ke Database
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {

    // --- 1. INISIALISASI DATATABLE ---
    $('#table-setting-thr').DataTable({
      "pageLength": 10,
      "columnDefs": [{
        "orderable": false,
        "targets": 4
      }]
    });

    // --- 2. EVENT SWITCH TOGGLE (UPDATE REALTIME) ---
    $('body').on('change', '.toggle-thr', function() {
      var encryptedId = $(this).data('id');
      var isChecked = $(this).is(':checked');
      var labelSpan = $('#label-' + encryptedId);
      var statusMsg = $('#msg-' + encryptedId);

      // Tentukan Value
      var selectedType = isChecked ? 'NATAL' : 'IDUL_FITRI';

      // Visual Feedback Label
      if (isChecked) {
        labelSpan.text('NATAL').removeClass('text-muslim').addClass('text-natal');
      } else {
        labelSpan.text('IDUL FITRI (Muslim)').removeClass('text-natal').addClass('text-muslim');
      }

      // Loading Text
      statusMsg.text('Saving...').css('color', 'orange');

      // Ajax Update
      $.ajax({
        url: "<?= base_url('setting_thr/update_action') ?>",
        type: "POST",
        dataType: "JSON",
        data: {
          id: encryptedId,
          tipe: selectedType,
          csrf_token: '<?= $this->security->get_csrf_hash(); ?>'
        },
        success: function(response) {
          if (response.status == 'success') {
            statusMsg.text('').fadeOut(1000); // Bersih jika sukses
          } else {
            statusMsg.text('Gagal!').css('color', 'red');
            // SweetAlert Error
            Swal.fire({
              icon: 'error',
              title: 'Gagal Menyimpan',
              text: response.message
            });
            // Kembalikan posisi switch
            $(this).prop('checked', !isChecked);
          }
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
          statusMsg.text('Error!').css('color', 'red');
          Swal.fire({
            icon: 'error',
            title: 'Kesalahan Koneksi',
            text: 'Tidak dapat terhubung ke server.'
          });
        }
      });
    });

    // --- 3. LOGIC TOMBOL GENERATE (CEK DATA DULU) ---
    $('#btn-generate').click(function() {
      var btn = $(this);
      var originalText = btn.html();

      // Loading Button
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengecek Data...');

      $.ajax({
        url: "<?= base_url('setting_thr/preview_generate') ?>",
        type: "POST",
        dataType: "JSON",
        data: {
          csrf_token: '<?= $this->security->get_csrf_hash(); ?>'
        },
        success: function(response) {
          btn.prop('disabled', false).html(originalText);

          if (response.status == 'success') {
            // A. JIKA ADA DATA BARU -> TAMPILKAN MODAL TABEL
            $('#preview-table-body').empty();

            $.each(response.data, function(index, item) {
              var row = `<tr>
                            <td class="text-center">${index + 1}</td>
                            <td class="text-center font-weight-bold text-primary">${item.no_pegawai}</td>
                            <td>${item.nama}</td>
                            <td><small>${item.jabatan}</small></td>
                         </tr>`;
              $('#preview-table-body').append(row);
            });

            // Tampilkan Modal Bootstrap
            $('#modal-preview-generate').modal('show');

          } else if (response.status == 'empty') {
            // B. JIKA TIDAK ADA DATA BARU -> SWEETALERT INFO
            Swal.fire({
              icon: 'info',
              title: 'Data Sudah Lengkap',
              text: response.message,
              confirmButtonColor: '#3085d6',
            });
          }
        },
        error: function() {
          btn.prop('disabled', false).html(originalText);
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan saat mengecek data.',
          });
        }
      });
    });

    // --- 4. LOGIC EKSEKUSI SIMPAN (DARI MODAL) ---
    $('#btn-confirm-execute').click(function() {
      var btn = $(this);
      var originalText = btn.text();

      btn.prop('disabled', true).text('Sedang Menyimpan...');

      $.ajax({
        url: "<?= base_url('setting_thr/execute_generate') ?>",
        type: "POST",
        dataType: "JSON",
        data: {
          csrf_token: '<?= $this->security->get_csrf_hash(); ?>'
        },
        success: function(response) {
          if (response.status == 'success') {
            // Tutup Modal
            $('#modal-preview-generate').modal('hide');

            // SWEETALERT SUCCESS
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: response.message,
              showConfirmButton: true,
              confirmButtonText: 'OK',
              confirmButtonColor: '#28a745'
            }).then((result) => {
              // Reload halaman setelah user klik OK
              if (result.isConfirmed) {
                location.reload();
              }
            });

          } else {
            // Gagal Simpan
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: response.message
            });
            btn.prop('disabled', false).text(originalText);
          }
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Kesalahan Server',
            text: 'Gagal menyimpan data ke database.'
          });
          btn.prop('disabled', false).text(originalText);
        }
      });
    });

  });
</script>