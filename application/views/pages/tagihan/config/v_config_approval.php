<header class="page-header">
  <h2><i class="icons fas fa-database"></i>&nbsp;<?= $page_title ?></h2>
  <div class="right-wrapper text-left">
    <ol class="breadcrumbs">
      <li><span><?= $page_desc ?></span></li>
    </ol>
  </div>
</header>

<style>
  .card {
    border: none;
    border-radius: 8px;
    margin-bottom: 20px;
  }

  .font-weight-600 {
    font-weight: 600;
  }

  /* Style untuk tombol disabled agar terlihat jelas mati */
  .btn-disabled {
    background-color: #d6d8db !important;
    border-color: #d6d8db !important;
    color: #6c757d !important;
    cursor: not-allowed;
  }
</style>

<div class="row">

  <div class="col-md-12 mb-4">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fa fa-cogs mr-1"></i>Pengaturan Alur Approval</h5>
      </div>
      <div class="card-body">
        <div class="alert alert-info">
          Halaman ini hanya dapat diakses oleh <b>Administrator</b>.<br>
          <small>* Tombol <b>Save</b> hanya akan menyala jika Anda melakukan perubahan data.</small>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover" id="table-config">
            <thead class="thead-dark">
              <tr>
                <th width="5%" class="text-center">Level</th>
                <th width="5%" class="text-center">ID</th>
                <th>Role Label</th>
                <th>Role Slug (System)</th>
                <th>Nomor WA / Group ID</th>
                <th width="10%" class="text-center">Edit?</th>
                <th width="10%" class="text-center">Status</th>
                <th width="5%" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($configs as $cfg): ?>
                <tr data-id="<?= $cfg->id_config ?>">
                  <td class="text-center font-weight-bold"><?= $cfg->level_order ?></td>
                  <td class="text-center"><?= $cfg->status_code ?></td>

                  <td>
                    <input type="text" class="form-control form-control-sm inp-monitor inp-label" value="<?= $cfg->role_label ?>">
                  </td>

                  <td>
                    <input type="text" class="form-control form-control-sm bg-light text-muted" value="<?= $cfg->role_slug ?>" readonly title="Slug Sistem (Tidak bisa diubah)">
                  </td>

                  <td>
                    <input type="text" class="form-control form-control-sm inp-monitor inp-wa" value="<?= $cfg->notif_number ?>">
                  </td>

                  <td class="text-center">
                    <select class="form-control form-control-sm inp-monitor inp-edit">
                      <option value="0" <?= $cfg->can_edit == 0 ? 'selected' : '' ?>>Tidak</option>
                      <option value="1" <?= $cfg->can_edit == 1 ? 'selected' : '' ?>>Ya</option>
                    </select>
                  </td>

                  <td class="text-center">
                    <select class="form-control form-control-sm inp-monitor inp-active font-weight-bold <?= $cfg->is_active == 1 ? 'text-success' : 'text-danger' ?>" onchange="updateColor(this)">
                      <option value="1" class="text-success" <?= $cfg->is_active == 1 ? 'selected' : '' ?>>Aktif</option>
                      <option value="0" class="text-danger" <?= $cfg->is_active == 0 ? 'selected' : '' ?>>Non-Aktif</option>
                    </select>
                  </td>

                  <td class="text-center">
                    <button type="button" class="btn btn-sm btn-secondary btn-save btn-disabled" title="Tidak ada perubahan" disabled>
                      <i class="fas fa-save"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">📜 Log Notifikasi WhatsApp</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover table-sm table-bordered" id="table-logs">
            <thead>
              <tr>
                <th>Waktu Kirim</th>
                <th>No SJ</th>
                <th>Target</th>
                <th>Nomor Tujuan</th>
                <th>Pesan</th>
                <th class="text-center">Resend</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($logs as $log): ?>
                <tr>
                  <td><?= $log->sent_at ?></td>
                  <td><?= $log->no_sj ?? '-' ?></td>
                  <td><?= $log->target_name ?></td>
                  <td><?= $log->target_number ?></td>
                  <td>
                    <button type="button" class="btn btn-xs btn-info" onclick="viewMessage('<?= base64_encode($log->message) ?>')"><i class="fas fa-eye"></i> Lihat</button>
                  </td>
                  <td class="text-center"><?= $log->resent_count ?>x</td>
                  <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-xs btn-warning btn-resend-tagihan" data-url="<?= base_url('tagihan_config/resend_wa/' . $log->id_log) ?>" data-number="<?= $log->target_number ?>" data-name="<?= $log->target_name ?>">
                      <i class="fas fa-redo"></i> Resend
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function updateColor(select) {
    if (select.value == '1') {
      select.classList.remove('text-danger');
      select.classList.add('text-success');
    } else {
      select.classList.remove('text-success');
      select.classList.add('text-danger');
    }
  }

  function viewMessage(encodedMsg) {
    let msg = atob(encodedMsg);
    Swal.fire({
      title: 'Isi Pesan WA',
      text: msg,
      icon: 'info'
    });
  }

  // Handle Resend Button dengan SweetAlert
  $(document).on('click', '.btn-resend-tagihan', function(e) {
    e.preventDefault();
    let url = $(this).data('url');
    let number = $(this).data('number');
    let name = $(this).data('name');

    Swal.fire({
      title: 'Kirim Ulang Notifikasi?',
      html: 'Notifikasi akan dikirim ulang ke:<br><strong>' + name + '</strong><br>(' + number + ')',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#ffc107',
      cancelButtonColor: '#6c757d',
      confirmButtonText: '<i class="fas fa-redo"></i> Ya, Resend',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Mengirim...',
          html: 'Mohon tunggu, sedang mengirim notifikasi WhatsApp',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        window.location.href = url;
      }
    });
  });

  document.addEventListener("DOMContentLoaded", function() {

    $('#table-config').DataTable({
      "paging": false,
      "ordering": false,
      "info": false,
      "searching": false
    });

    $('#table-logs').DataTable({
      "order": [
        [0, "desc"]
      ],
      "pageLength": 10
    });

    // ------------------------------------------------------------
    // 1. SIMPAN NILAI AWAL SAAT LOAD
    // ------------------------------------------------------------
    $('.inp-monitor').each(function() {
      // Simpan nilai asli ke attribute data-original
      $(this).data('original', $(this).val());
    });

    // ------------------------------------------------------------
    // 2. DETEKSI PERUBAHAN INPUT
    // ------------------------------------------------------------
    $(document).on('keyup change input', '.inp-monitor', function() {
      let row = $(this).closest('tr');
      let btn = row.find('.btn-save');
      let isChanged = false;

      // Cek setiap input di baris ini
      row.find('.inp-monitor').each(function() {
        let currentVal = $(this).val();
        let originalVal = $(this).data('original');

        // Jika ada satu saja yang beda, tandai changed
        if (currentVal != originalVal) {
          isChanged = true;
          return false; // break loop
        }
      });

      // Toggle Tombol Save
      if (isChanged) {
        btn.prop('disabled', false)
          .removeClass('btn-secondary btn-disabled')
          .addClass('btn-success')
          .attr('title', 'Simpan Perubahan');
      } else {
        btn.prop('disabled', true)
          .removeClass('btn-success')
          .addClass('btn-secondary btn-disabled')
          .attr('title', 'Tidak ada perubahan');
      }
    });

    // ------------------------------------------------------------
    // 3. LOGIC SAVE DATA
    // ------------------------------------------------------------
    $(document).on('click', '.btn-save', function() {
      let btn = $(this);
      let row = btn.closest('tr');
      let originalIcon = btn.html();

      if (btn.prop('disabled')) return; // Double check

      let payload = {
        id_config: row.data('id'),
        role_label: row.find('.inp-label').val(),
        notif_number: row.find('.inp-wa').val(),
        can_edit: row.find('.inp-edit').val(),
        is_active: row.find('.inp-active').val()
      };

      // Loading state
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

      $.ajax({
        url: '<?= base_url("tagihan_config/update_setting") ?>',
        type: 'POST',
        data: payload,
        dataType: 'json',
        success: function(response) {
          if (response.status == 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Tersimpan!',
              text: 'Konfigurasi berhasil diperbarui.',
              timer: 1500,
              showConfirmButton: false
            });

            // PENTING: Update data-original dengan nilai baru
            row.find('.inp-monitor').each(function() {
              $(this).data('original', $(this).val());
            });

            // Disable tombol kembali karena data sudah "sync"
            btn.removeClass('btn-success')
              .addClass('btn-secondary btn-disabled')
              .attr('title', 'Tidak ada perubahan');

          } else {
            Swal.fire('Gagal', 'Gagal menyimpan data', 'error');
            btn.prop('disabled', false); // Aktifkan lagi jika gagal
          }
        },
        error: function(xhr) {
          Swal.fire('Error', 'Terjadi kesalahan server', 'error');
          btn.prop('disabled', false);
        },
        complete: function() {
          // Kembalikan icon save (tetap disabled jika sukses)
          btn.html(originalIcon);
        }
      });
    });

  });
</script>