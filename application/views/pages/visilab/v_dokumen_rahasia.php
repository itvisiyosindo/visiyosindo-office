<style>
  /* --- STYLES --- */
  :root {
    --text-label: #8898aa;
    --text-dark: #32325d;
    --card-radius: 10px;
    --red-color: #ff5757;
    --green-color: #7ac142;
  }

  .card {
    border-radius: var(--card-radius);
    border: 1px solid #e5e9f2;
    transition: 0.2s;
    margin-bottom: 24px;
    box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
  }

  .card:hover {
    border-color: #d0d7e0;
  }

  .card-header {
    border-bottom: 1px solid #eef1f5;
    padding: 1.25rem 1.5rem;
    background: #fff;
    border-radius: 10px 10px 0 0;
  }

  .card-title {
    font-weight: 700;
    color: #32325d;
    font-size: 0.95rem;
    text-transform: uppercase;
    margin: 0;
  }

  .form-control {
    border-radius: 6px;
    height: 42px;
    border: 1px solid #dcdde1;
  }

  .form-control:focus {
    border-color: #6c5ce7;
    box-shadow: 0 0 5px rgba(108, 92, 231, .2);
  }

  .collapse {
    transition: all 250ms ease-in-out !important;
  }

  #collapseFormArea {
    background: #fafafa;
    border-radius: 0 0 10px 10px;
  }

  /* --- ANIMATIONS FOR MODAL --- */
  .wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
  }

  /* Checkmark & Crossmark CSS (Keep your original CSS here) */
  .checkmark {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #fff;
    stroke-miterlimit: 10;
    box-shadow: inset 0px 0px 0px var(--green-color);
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
  }

  .checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: var(--green-color);
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
  }

  .checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
  }

  @keyframes stroke {
    100% {
      stroke-dashoffset: 0;
    }
  }

  @keyframes scale {

    0%,
    100% {
      transform: none;
    }

    50% {
      transform: scale3d(1.1, 1.1, 1);
    }
  }

  @keyframes fill {
    100% {
      box-shadow: inset 0px 0px 0px 30px var(--green-color);
    }
  }

  .crossmark {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #fff;
    stroke-miterlimit: 10;
    box-shadow: inset 0px 0px 0px var(--red-color);
    animation: fill_cross .4s ease-in-out .4s forwards, scale_cross .3s ease-in-out .9s both;
  }

  .crossmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: var(--red-color);
    fill: none;
    animation: stroke_cross 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
  }

  .crossmark__line {
    fill: none;
    stroke-dasharray: 40;
    stroke-dashoffset: 40;
  }

  .crossmark__line--first {
    transform-origin: 50% 50%;
    animation: stroke_cross 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
  }

  .crossmark__line--second {
    transform-origin: 50% 50%;
    animation: stroke_cross 0.3s cubic-bezier(0.65, 0, 0.45, 1) 1.0s forwards;
  }

  @keyframes stroke_cross {
    100% {
      stroke-dashoffset: 0;
    }
  }

  @keyframes fill_cross {
    100% {
      box-shadow: inset 0px 0px 0px 30px var(--red-color);
    }
  }

  @keyframes scale_cross {

    0%,
    100% {
      transform: none;
    }

    50% {
      transform: scale3d(1.1, 1.1, 1);
    }
  }

  .modal-content-modern {
    border: none;
    border-radius: 15px;
  }

  .modal-body-modern {
    text-align: center;
    padding: 30px;
  }

  .text-success-modern {
    color: var(--green-color);
    font-weight: 700;
    margin-top: 10px;
  }

  .text-danger-modern {
    color: var(--red-color);
    font-weight: 700;
    margin-top: 10px;
  }
</style>

<header class="page-header">
  <h2><i class="far fa-newspaper "></i>&nbsp;<?= $page_title ?></h2>
  <div class="right-wrapper text-left">
    <ol class="breadcrumbs">
      <li><span><?= $page_desc ?></span></li>
    </ol>
  </div>
</header>

<div class="row">
  <div class="col-md-12">
    <div class="card shadow-sm" id="card-form-container">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title" id="form-title"><i class="fa fa-list"></i> Data Dokumen</h5>
          <button class="btn btn-success btn-sm shadow-sm" type="button" id="btn-tambah-toggle">
            <i class="fa fa-plus"></i> Tambah Dokumen Baru
          </button>
        </div>
      </div>
      <div class="collapse" id="collapseFormArea">
        <div class="card-body border-top">
          <form id="form-dokumen" autocomplete="off">
            <input type="hidden" id="id_dokumen" name="id_dokumen">
            <input type="hidden" id="method" value="add">

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="nama">Nama Dokumen <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Laporan Keuangan" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="kategori">Kategori <span class="text-danger">*</span></label>
                  <select class="form-control" id="kategori" name="kategori" required>
                    <option value="">- Pilih Kategori -</option>
                    <?php
                    if (isset($list_kategori) && !empty($list_kategori)) {
                      foreach ($list_kategori as $row) {
                        echo "<option value='" . $row->id . "'>" . $row->nama . "</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="file">Link File / URL <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="file" name="file" placeholder="https://..." required>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-12 text-right">
                <button type="button" class="btn btn-secondary" id="btn-batal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan Data</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="table_dokumen" width="100%">
            <thead class="bg-light">
              <tr>
                <th width="5%">No</th>
                <th>Nama Dokumen</th>
                <th>Kategori</th>
                <th>Link Download</th>
                <th width="15%" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content modal-content-modern">
      <div class="modal-body modal-body-modern">
        <div class="wrapper">
          <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
          </svg>
        </div>
        <h3 class="text-success-modern">Berhasil!</h3>
        <p class="text-muted" id="msg-success-text">Data berhasil disimpan.</p>
        <button type="button" class="btn btn-sm btn-outline-success mt-3" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content modal-content-modern">
      <div class="modal-body modal-body-modern">
        <div class="wrapper rejected">
          <svg class="crossmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="crossmark__circle" cx="26" cy="26" r="25" fill="none" />
            <path class="crossmark__line crossmark__line--first" fill="none" d="M16 16l20 20" />
            <path class="crossmark__line crossmark__line--second" fill="none" d="M36 16L16 36" />
          </svg>
        </div>
        <h3 class="text-danger-modern">Gagal!</h3>
        <p class="text-muted" id="msg-error-text">Terjadi kesalahan sistem.</p>
        <button type="button" class="btn btn-sm btn-outline-danger mt-3" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // --- KONFIGURASI UTAMA (FIX URL & TOKEN) ---
  // Definisikan URL di sini agar tidak undefined saat dipanggil di bawah
  const BASE_URL = "<?= base_url('Dokumen_rahasia_visilab/') ?>";
  const CSRF_NAME = '<?= $this->security->get_csrf_token_name(); ?>';
  const CSRF_HASH = '<?= $this->security->get_csrf_hash(); ?>';

  var table;

  $(document).ready(function() {

    // --- 1. INISIALISASI DATATABLE ---
    table = $('#table_dokumen').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": BASE_URL + "pagination", // Gunakan variabel BASE_URL
        "type": "POST",
        "data": function(data) {
          data[CSRF_NAME] = CSRF_HASH; // Kirim CSRF Token
        },
        "error": function(xhr, error, code) {
          console.log("Datatable Error:", xhr.responseText);
          $('#msg-error-text').text('Gagal memuat data tabel.');
          $('#modal-error').modal('show');
        }
      },
      "columnDefs": [{
        "targets": [0, 3, 4],
        "orderable": false,
        "className": "text-center"
      }],
      "language": {
        "emptyTable": "Tidak ada data tersedia",
        "processing": "Sedang memuat data..."
      }
    });

    // --- 2. TOMBOL TAMBAH (TOGGLE) ---
    $('#btn-tambah-toggle').click(function() {
      if ($('#collapseFormArea').hasClass('show')) {
        $('#collapseFormArea').collapse('hide');
        resetForm();
      } else {
        resetForm();
        $('#method').val('add');
        $('#form-title').html('<i class="fa fa-plus-circle"></i> Tambah Dokumen Baru');
        $('#btn-simpan').text('Simpan Data');
        $('#collapseFormArea').collapse('show');
        setTimeout(function() {
          $('#nama').focus();
        }, 300);
      }
    });

    // --- 3. TOMBOL BATAL ---
    $('#btn-batal').click(function() {
      $('#collapseFormArea').collapse('hide');
      setTimeout(function() {
        resetForm();
      }, 300);
    });

    // --- 4. SUBMIT FORM (AJAX) ---
    $('#form-dokumen').submit(function(e) {
      e.preventDefault();
      var method = $('#method').val();
      var url = (method == 'add') ? BASE_URL + "add" : BASE_URL + "update";

      $('#btn-simpan').text('Menyimpan...').attr('disabled', true);

      // Ambil data form
      var formData = $(this).serialize();
      // Tambahkan CSRF token manual ke serialize string jika belum ada
      if (formData.indexOf(CSRF_NAME) === -1) {
        formData += '&' + CSRF_NAME + '=' + CSRF_HASH;
      }

      $.ajax({
        url: url,
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(data) {
          if (data.status) {
            $('#collapseFormArea').collapse('hide');
            resetForm();
            table.ajax.reload(null, false);
            $('#msg-success-text').text(data.msg);
            $('#modal-success').modal('show');
            setTimeout(function() {
              $('#modal-success').modal('hide');
            }, 2000);
          } else {
            $('#msg-error-text').html(data.msg);
            $('#modal-error').modal('show');
          }
          $('#btn-simpan').text('Simpan Data').attr('disabled', false);
        },
        error: function(xhr) {
          console.log("Submit Error:", xhr.responseText);
          $('#msg-error-text').text('Terjadi kesalahan server (Error 500/403).');
          $('#modal-error').modal('show');
          $('#btn-simpan').text('Simpan Data').attr('disabled', false);
        }
      });
    });

    function resetForm() {
      $('#form-dokumen')[0].reset();
      $('[name="id_dokumen"]').val('');
      $('#method').val('add');
      $('#form-title').html('<i class="fa fa-list"></i> Data Dokumen');
      $('#btn-simpan').text('Simpan Data');
    }
  });

  // --- 5. FUNGSI EDIT & DELETE (GLOBAL SCOPE) ---

  window.edit_data = function(id) {
    resetForm(); // Panggil fungsi reset lokal
    $('#method').val('update');

    $.ajax({
      url: BASE_URL + "edit/" + id, // Gunakan BASE_URL
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        if (data) {
          $('[name="id_dokumen"]').val(data.id);
          $('[name="nama"]').val(data.nama_dokumen);
          $('[name="kategori"]').val(data.id_kategori);
          $('[name="file"]').val(data.file);

          $('#form-title').html('<i class="fa fa-pencil-alt"></i> Edit Dokumen: ' + data.nama_dokumen);
          $('#btn-simpan').text('Update Data');

          if (!$('#collapseFormArea').hasClass('show')) {
            $('#collapseFormArea').collapse('show');
          }
          $('html, body').animate({
            scrollTop: $("#card-form-container").offset().top - 80
          }, 500);
        }
      },
      error: function(xhr) {
        console.log("Edit Error:", xhr.responseText);
        Swal.fire('Gagal', 'Tidak dapat mengambil data edit.', 'error');
      }
    });
  }

  // Definisikan ulang fungsi resetForm agar bisa dipanggil window.edit_data
  function resetForm() {
    $('#form-dokumen')[0].reset();
    $('[name="id_dokumen"]').val('');
    $('#method').val('add');
    $('#form-title').html('<i class="fa fa-list"></i> Data Dokumen');
    $('#btn-simpan').text('Simpan Data');
  }

  window.delete_data = function(id) {
    console.log("ID Hapus:", id); // Cek apakah ini muncul di console setelah perbaikan Controller

    if (!id) {
      Swal.fire('Error!', 'ID Data tidak valid.', 'error');
      return;
    }

    Swal.fire({
      title: 'Hapus Permanen?',
      text: "Data yang dihapus tidak bisa dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ff5757',
      cancelButtonColor: '#8898aa',
      confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
      if (result.isConfirmed) {

        // Buat objek data CSRF
        var dataPost = {};
        dataPost[CSRF_NAME] = CSRF_HASH;

        $.ajax({
          url: BASE_URL + "delete/" + id,
          type: "POST",
          dataType: "JSON",
          data: dataPost, // Kirim Token
          success: function(data) {
            if (data.status) {
              Swal.fire({
                title: 'Terhapus!',
                text: data.msg,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
              });
              table.ajax.reload(null, false);
            } else {
              Swal.fire('Gagal!', data.msg, 'error');
            }
          },
          error: function(xhr) {
            console.log("Delete Error:", xhr.responseText);
            if (xhr.status == 404) {
              Swal.fire('Error 404', 'URL Controller Salah/Tidak Ditemukan.', 'error');
            } else {
              Swal.fire('Gagal', 'Terjadi kesalahan sistem.', 'error');
            }
          }
        });
      }
    });
  }
</script>