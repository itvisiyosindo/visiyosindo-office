<header class="page-header">
  <h2><i class="icons icon-doc"></i>&nbsp;<?= $page_title ?></h2>
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

        <div class="alert alert-info">
          <i class="fa fa-info-circle"></i> Silahkan upload Sertifikat atau Bukti Kehadiran setelah training selesai.
        </div>

        <?php if (sessPenggunaId() == 1 || sessPenggunaId() == 69 || sessPenggunaId() == 33 || sessPenggunaId() == 23 || sessPenggunaId() == 54 || sessPenggunaId() == 58 || isAdmin() || isHrd()) { ?>
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="font-weight-bold">Filter Status Upload:</label>
              <select id="filter_status_upload" class="form-control">
                <option value="">- Tampilkan Semua -</option>
                <option value="belum">Belum Diupload</option>
                <option value="sudah">Sudah Diupload</option>
              </select>
            </div>
          </div>
          <hr>
        <?php } ?>
        <hr>
        <div class="table-responsive">
          <table class="table table-striped table-sm table-bordered table-hover" id="table_upload_training">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Kode & Nama</th>
                <th>Nama Training</th>
                <th>Penyelenggara</th>
                <th>Tanggal Mulai</th>
                <th>Status Upload</th>
                <th width="15%">Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</div>

<div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold" id="modalLabel">Upload Sertifikat Training</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-upload-dokumen" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="id_training_modal" id="id_training_modal">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

          <div class="form-group">
            <label>Kode Training</label>
            <input type="text" class="form-control" id="kode_training_view" readonly>
          </div>

          <div class="form-group">
            <label>File Sertifikat (PDF/JPG)</label>
            <input type="file" class="form-control" name="file_sertifikat" accept=".pdf, .jpg, .jpeg, .png" required>
            <small class="text-muted">Maksimal 5MB. Format: PDF, JPG, PNG.</small>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan Sertifikat</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    var $ = jQuery;
    var table;

    table = $('#table_upload_training').DataTable({
      responsive: false,
      processing: true,
      serverSide: true,
      order: [
        [0, 'desc']
      ],
      ajax: {
        url: '<?= base_url("training/pagination/training_user_upload") ?>',
        type: 'POST',
        data: function(e) {
          e.csrf_token = '<?= $this->security->get_csrf_hash(); ?>';
          e.filter_status = $('#filter_status_upload').val();
        }
      },
      columnDefs: [{
        targets: [0, 4, 5, 6],
        className: 'text-center'
      }]
    });

    $('#filter_status_upload').change(function() {
      table.ajax.reload();
    });

    // Handle Submit Form Upload dengan SweetAlert
    $('#form-upload-dokumen').on('submit', function(e) {
      e.preventDefault();
      var formData = new FormData(this);

      // Tampilkan Loading Swal sebelum request
      Swal.fire({
        title: 'Sedang Mengupload...',
        html: 'Mohon tunggu sebentar.',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading()
        }
      });

      $.ajax({
        url: '<?= base_url("training/process_upload_sertifikat") ?>',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'JSON',
        success: function(response) {
          // Tutup loading
          Swal.close();

          if (response.status == 'success') {
            $('#modalUpload').modal('hide');
            table.ajax.reload(null, false);

            // SWAL SUKSES
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: response.message || response.msg, // Handle msg/message key
              timer: 2000,
              showConfirmButton: false
            });
          } else {
            // SWAL ERROR DARI CONTROLLER (Validasi/Upload Gagal)
            Swal.fire({
              icon: 'error',
              title: 'Gagal!',
              html: response.message || response.msg // Pakai html karena error upload mengandung tag <p>
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.close();
          // SWAL ERROR SERVER (500/404)
          console.log(xhr.responseText);
          Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Gagal menghubungi server. Silahkan coba lagi.'
          });
        }
      });
    });
  });

  function bukaModalUpload(id, kode) {
    var $ = jQuery;
    $('#form-upload-dokumen')[0].reset();
    $('#id_training_modal').val(id);
    $('#kode_training_view').val(kode);
    $('#modalUpload').modal('show');
  }
</script>