<header class="page-header">
  <h2><i class="fas fa-history"></i>&nbsp;<?= $page_title ?></h2>
  <div class="right-wrapper text-left">
    <ol class="breadcrumbs">
      <li><span>Daftar Pengajuan</span></li>
      <li><span>Tagihan Ekspedisi</span></li>
    </ol>
  </div>
</header>

<style>
  /* Styling Card & Typography (Disamakan dengan v_list_tagihan) */
  .card {
    border: none;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .font-weight-600 {
    font-weight: 600;
  }

  .text-small {
    font-size: 0.85rem;
  }

  /* Styling Tabel Khusus */
  .table thead th {
    border-top: none;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85rem;
    background-color: #f8f9fa;
    color: #555;
    vertical-align: middle;
  }

  .table td {
    vertical-align: middle !important;
    font-size: 0.9rem;
    padding: 1rem 0.75rem;
  }

  /* Badge Custom */
  .badge {
    padding: 0.6em 1em;
    font-size: 80%;
    font-weight: 600;
    border-radius: 20px;
  }

  .table-hover tbody tr:hover {
    background-color: #f1f5f9;
  }
</style>

<div class="row">
  <div class="col-md-12">

    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-user-clock mr-2"></i>Riwayat Pengajuan <?= isset($is_admin) && $is_admin ? '(Semua)' : 'Saya' ?>
          </h5>
          <a href="<?= base_url('tracking') ?>" class="btn btn-primary rounded shadow-sm">
            <i class="fas fa-plus mr-2"></i> Ajukan Baru
          </a>
        </div>
      </div>

      <div class="card-body">

        <div class="mb-3 d-flex justify-content-between align-items-center">
          <div class="btn-group" role="group" aria-label="Filter Data">

            <a href="<?= base_url('tagihan/my_submission') ?>"
              class="btn <?= (empty($filter_active)) ? 'btn-primary' : 'btn-outline-primary' ?>">
              <i class="fas fa-list mr-1"></i> Semua
            </a>

            <a href="<?= base_url('tagihan/my_submission?filter=waiting') ?>"
              class="btn <?= ($filter_active == 'waiting') ? 'btn-warning' : 'btn-outline-warning' ?> font-weight-bold text-dark">
              <i class="fas fa-clock mr-1"></i> Menunggu
            </a>

            <a href="<?= base_url('tagihan/my_submission?filter=revision') ?>"
              class="btn <?= ($filter_active == 'revision') ? 'btn-danger' : 'btn-outline-danger' ?>">
              <i class="fas fa-edit mr-1"></i> Direvisi
            </a>

            <a href="<?= base_url('tagihan/my_submission?filter=rejected') ?>"
              class="btn <?= ($filter_active == 'rejected') ? 'btn-dark' : 'btn-outline-dark' ?>">
              <i class="fas fa-times mr-1"></i> Ditolak
            </a>

            <a href="<?= base_url('tagihan/my_submission?filter=approved') ?>"
              class="btn <?= ($filter_active == 'approved') ? 'btn-success' : 'btn-outline-success' ?>">
              <i class="fas fa-check-double mr-1"></i> Disetujui
            </a>

            <a href="<?= base_url('tagihan/my_submission?filter=paid') ?>"
              class="btn <?= ($filter_active == 'paid') ? 'btn-info' : 'btn-outline-info' ?>">
              <i class="fas fa-money-check-alt mr-1"></i> Sudah Dibayar
            </a>

          </div>

          <span class="text-muted font-italic small">
            Menampilkan <?= count($list_tagihan) ?> data
          </span>
        </div>

        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="table-my-submission">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th width="25%">Informasi Pengiriman</th>
                <th width="20%">Informasi Penting</th>
                <th width="20%">Status Approval</th>
                <th width="15%" class="text-center">Dokumen</th>
                <th width="15%" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              if (!empty($list_tagihan)):
                foreach ($list_tagihan as $row):
                  // Tentukan tipe tagihan
                  $is_kirim_dokumen = (!empty($row->id_kirim) && empty($row->id_tracking)) || ($row->tagihan_type ?? '') == 'kirim_dokumen';

                  // Ambil data yang sesuai berdasarkan tipe
                  if ($is_kirim_dokumen) {
                    $kode_display = $row->kode_kirim ?? '-';
                    $ekspedisi_display = $row->nama_ekspedisi_kirim ?? $row->ekspedisi_kirim ?? '-';
                    $no_resi_display = $row->no_resi_kirim ?? '-';
                    $tgl_sampai_display = $row->tgl_sampai_kirim ?? null;
                    $source_url = base_url('kirim/show/detail/' . encrypt($row->id_kirim));
                    $type_badge = '<span class="badge badge-info"><i class="fas fa-envelope mr-1"></i>Kirim Dokumen</span>';
                  } else {
                    $kode_display = $row->no_sj ?? '-';
                    $ekspedisi_display = $row->nama_ekspedisi ?? '-';
                    $no_resi_display = $row->no_resi ?? '-';
                    $tgl_sampai_display = $row->tgl_sampai ?? null;
                    $source_url = base_url('tracking/detail/' . encrypt($row->id_tracking));
                    $type_badge = '<span class="badge badge-primary"><i class="fas fa-box mr-1"></i>Tracking Barang</span>';
                  }
              ?>
                  <tr>
                    <td class="text-center font-weight-bold"><?= $no++ ?></td>

                    <td>
                      <?= $type_badge ?>
                      <div class="font-weight-bold text-dark mb-1 mt-2" style="font-size:1rem;">
                        <a href="<?= $source_url ?>" target="_blank" class="text-dark">
                          <?= $kode_display ?><i class="fas fa-external-link-alt ml-2 text-muted small"></i>
                        </a>
                      </div>
                      <div class="d-flex align-items-center justify-content-start" style="gap: 4px;">
                        <span class="badge badge-light border text-primary mb-1">
                          <i class="fas fa-truck mr-1"></i><?= $ekspedisi_display ?>
                        </span>
                        <span class="badge badge-light border text-primary mb-1">
                          <i class="fas fa-receipt mr-1"></i><?= $no_resi_display ?>
                        </span>
                      </div>
                      <?php if ($tgl_sampai_display): ?>
                        <div class="text-muted text-small mt-1 d-flex flex-column" style="gap: 2px;">
                          <span class="badge badge-info font-weight-normal">
                            Sampai: <?= date('d M Y', strtotime($tgl_sampai_display)) ?>
                          </span>
                          <?php
                          $tgl_terima_show = ($row->tgl_penerima) ? date('d M Y', strtotime($row->tgl_penerima)) : '-';
                          ?>
                          <span class="badge badge-success font-weight-normal">
                            Diterima: <?= $tgl_terima_show ?>
                          </span>
                        </div>
                      <?php endif; ?>
                    </td>

                    <td style="vertical-align: top;">
                      <div class="mb-2">
                        <span class="text-label d-block text-muted" style="font-size:0.7rem; margin-bottom:0;">Nilai Tagihan</span>
                        <span class="font-weight-bold text-dark" style="font-size:0.95rem;">
                          Rp <?= number_format($row->nilai_tagihan, 0, ',', '.') ?>
                        </span>
                      </div>

                      <div class="mb-2">
                        <span class="text-label d-block text-muted" style="font-size:0.7rem; margin-bottom:0;">Biaya Asuransi</span>
                        <?php if (!empty($row->biaya_asuransi) && $row->biaya_asuransi > 0): ?>
                          <span class="font-weight-bold text-info" style="font-size:0.9rem;">
                            Rp <?= number_format($row->biaya_asuransi, 0, ',', '.') ?>
                          </span>
                        <?php else: ?>
                          <span class="text-muted" style="font-size:0.85rem;">Rp 0</span>
                          <small class="d-block text-muted font-italic" style="font-size:0.7rem;"><i class="fas fa-info-circle mr-1"></i>Tidak ada biaya asuransi</small>
                        <?php endif; ?>
                      </div>

                      <div class="mb-2 p-2 rounded" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
                        <span class="text-label d-block text-success" style="font-size:0.7rem; margin-bottom:0;"><i class="fas fa-calculator mr-1"></i>Total Tagihan</span>
                        <span class="font-weight-bold text-success" style="font-size:1rem;">
                          Rp <?= get_display_total_tagihan($row) ?>
                        </span>
                      </div>

                      <div class="mb-2">
                        <span class="text-label d-block text-muted" style="font-size:0.7rem; margin-bottom:0;">Tgl Invoice</span>
                        <span class="text-dark font-weight-600">
                          <i class="far fa-calendar-alt mr-1 text-info"></i>
                          <?= date('d M Y', strtotime($row->tanggal_invoice)) ?>
                        </span>
                      </div>

                      <div class="border-top my-2"></div>

                      <div>
                        <span class="text-label d-block text-muted" style="font-size:0.65rem; margin-bottom:2px;">Diajukan Oleh:</span>
                        <div class="d-flex align-items-center">
                          <div class="icon-shape bg-soft-primary text-primary rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width:24px; height:24px;">
                            <i class="fas fa-user" style="font-size:0.7rem"></i>
                          </div>
                          <span class="font-weight-bold text-dark" style="font-size:0.85rem;">
                            <?= $row->nama_pengaju ?>
                          </span>
                        </div>
                      </div>
                    </td>

                    <td>
                      <?php
                      // LOGIC STATUS BADGE DINAMIS
                      $statusLabel = 'Unknown';
                      $badgeColor  = 'badge-secondary';

                      if ($row->status_approval == 0) {
                        $statusLabel = 'PERLU REVISI';
                        $badgeColor = 'badge-danger';
                      } elseif ($row->status_approval == 5) {
                        $statusLabel = 'SELESAI (APPROVED)';
                        $badgeColor = 'badge-success';
                      } elseif ($row->status_approval == 99) {
                        $statusLabel = 'DITOLAK';
                        $badgeColor = 'badge-dark';
                      } else {
                        // Cari Label berdasarkan status di Config
                        $statusLabel = 'Menunggu Approval';
                        $badgeColor  = 'badge-warning';

                        if (isset($approval_configs) && !empty($approval_configs)) {
                          foreach ($approval_configs as $cfg) {
                            if ($cfg->status_code == $row->status_approval) {
                              $statusLabel = 'Menunggu: ' . $cfg->role_label;
                              break;
                            }
                          }
                        }
                      }
                      ?>
                      <span class="badge <?= $badgeColor ?> w-100 py-2">
                        <?= $statusLabel ?>
                      </span>

                      <?php if ($row->status_approval == 0): ?>
                        <div class="alert alert-danger p-2 mt-2 mb-0 text-small" style="line-height:1.2; font-size:0.75rem;">
                          <strong><i class="fas fa-exclamation-circle"></i> Revisi:</strong><br>
                          "<?= $row->catatan_revisi ?>"
                        </div>
                      <?php endif; ?>
                    </td>

                    <td class="text-center">
                      <div class="btn-group-vertical btn-group-sm w-100">
                        <a href="<?= $row->link_invoice ?>" target="_blank" class="btn btn-outline-primary mb-1 text-left" title="Lihat Invoice">
                          <i class="fas fa-file-invoice mr-2"></i> Invoice
                        </a>

                        <?php if ($row->link_bukti_bayar): ?>
                          <a href="<?= $row->link_bukti_bayar ?>" target="_blank" class="btn btn-outline-success mb-1 text-left" title="Lihat Bukti Bayar">
                            <i class="fas fa-receipt mr-2"></i> Bukti Bayar
                          </a>
                        <?php endif; ?>

                        <?php if ($row->link_bukti_potong): ?>
                          <a href="<?= $row->link_bukti_potong ?>" target="_blank" class="btn btn-outline-info mb-1 text-left" title="Bukti Potong">
                            <i class="fas fa-cut mr-2"></i> Bukti Potong
                          </a>
                        <?php endif; ?>

                        <?php if ($row->link_dokumen_lain): ?>
                          <a href="<?= $row->link_dokumen_lain ?>" target="_blank" class="btn btn-outline-secondary text-left" title="Dokumen Lain">
                            <i class="fas fa-folder-open mr-2"></i> Lainnya
                          </a>
                        <?php endif; ?>
                      </div>
                    </td>

                    <td class="text-center">
                      <?php if ($row->status_approval == 0): ?>
                        <div class="alert alert-warning p-1 mb-2 text-center small font-weight-bold">
                          Perbaiki Data!
                        </div>
                        <?php
                        // Tentukan URL edit berdasarkan tipe tagihan
                        if ($is_kirim_dokumen) {
                          $edit_url = base_url('tagihan/ajukan_kirim/' . encrypt($row->id_kirim));
                        } else {
                          $edit_url = base_url('tagihan/ajukan/' . encrypt($row->id_tracking));
                        }
                        ?>
                        <a href="<?= $edit_url ?>"
                          class="btn btn-primary btn-sm btn-block shadow-sm">
                          <i class="fas fa-edit mr-1"></i> EDIT
                        </a>

                      <?php elseif ($row->status_approval == 99): ?>
                        <?php
                        // Tentukan URL reset berdasarkan tipe tagihan
                        if ($is_kirim_dokumen) {
                          $reset_url = base_url('tagihan/ajukan_kirim/' . encrypt($row->id_kirim));
                        } else {
                          $reset_url = base_url('tagihan/ajukan/' . encrypt($row->id_tracking));
                        }
                        ?>
                        <a href="<?= $reset_url ?>"
                          class="btn btn-warning btn-sm btn-block shadow-sm">
                          <i class="fas fa-redo mr-1"></i> Ajukan Ulang
                        </a>

                      <?php else: ?>
                        <?php
                        // Tentukan URL detail berdasarkan tipe tagihan
                        if ($is_kirim_dokumen) {
                          $detail_url = base_url('tagihan/detail_kirim/' . encrypt($row->id_tagihan));
                        } else {
                          $detail_url = base_url('tagihan/detail/' . encrypt($row->id_tagihan));
                        }
                        ?>
                        <a href="<?= $detail_url ?>"
                          class="btn btn-info btn-sm btn-block shadow-sm">
                          <i class="fas fa-eye mr-1"></i> Detail
                        </a>
                      <?php endif; ?>

                      <?php if (isAdmin()): ?>
                        <!-- Tombol Edit Lengkap (Admin Only) -->
                        <a href="<?= base_url('tagihan/edit/' . encrypt($row->id_tagihan)) ?>"
                          class="btn btn-warning btn-sm btn-block mb-2 shadow-sm" title="Edit Data Tagihan">
                          <i class="fas fa-edit mr-1"></i> Edit Data
                        </a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach;
              else: ?>
                <tr>
                  <td colspan="6" class="text-center py-5 text-muted">
                    <img src="https://img.icons8.com/clouds/100/000000/folder-invoices.png" style="opacity:0.6"><br>
                    <span class="mt-2 d-block">Belum ada riwayat pengajuan tagihan.</span>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>

  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function() {
    // 1. Inisialisasi DataTable
    $('#table-my-submission').DataTable({
      "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
        '<"row"<"col-sm-12"tr>>' +
        '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
      "pageLength": 10,
      "ordering": false, // Matikan sorting default agar sesuai urutan Controller (Terbaru)
      "autoWidth": false,
      "language": {
        "search": "Cari:",
        "searchPlaceholder": "No SJ / Ekspedisi...",
        "emptyTable": "Belum ada riwayat pengajuan.",
        "zeroRecords": "Data tidak ditemukan.",
        "lengthMenu": "Show _MENU_",
        "info": "Hal _PAGE_ dari _PAGES_",
        "paginate": {
          "first": "<<",
          "last": ">>",
          "next": ">",
          "previous": "<"
        }
      }
    });

    // 2. SweetAlert untuk Konfirmasi Ajukan Ulang
    $('body').on('click', '.btn-resubmit', function(e) {
      e.preventDefault();
      let redirectUrl = $(this).data('url');

      Swal.fire({
        title: 'Ajukan Ulang?',
        text: "Data lama akan di-reset dan Anda akan diarahkan ke form pengajuan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ajukan Ulang',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = redirectUrl;
        }
      });
    });

  });
</script>