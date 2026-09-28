<header class="page-header">
  <h2><i class="icons fas fa-database"></i>&nbsp;<?= $page_title ?></h2>
  <div class="right-wrapper text-left">
    <ol class="breadcrumbs">
      <li><span><?= isset($page_desc) ? $page_desc : 'Daftar Tagihan' ?></span></li>
    </ol>
  </div>
</header>

<style>
  /* Styling Card & Typography agar senada dengan Form */
  .card {
    border: none;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    /* Tambahan shadow halus */
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
    /* Badge bulat lebih modern */
  }

  /* Hover effect pada baris tabel */
  .table-hover tbody tr:hover {
    background-color: #f1f5f9;
  }
</style>

<div class="row">
  <div class="col-md-12">

    <!-- Quick Access to SPP -->
    <div class="alert alert-info d-flex justify-content-between align-items-center mb-4">
      <div>
        <i class="fas fa-info-circle mr-2"></i>
        <strong>Surat Permintaan Pembayaran (SPP)</strong> - Ajukan pembayaran untuk tagihan yang sudah disetujui
      </div>
      <a href="<?= base_url('spp') ?>" class="btn btn-info btn-sm font-weight-bold">
        <i class="fas fa-file-invoice-dollar mr-1"></i> Kelola SPP
      </a>
    </div>

    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="m-0 font-weight-bold"><i class="fas fa-list-ul mr-2"></i>Daftar Tagihan Ekspedisi</h5>
          <div>
            <a href="<?= base_url('tagihan/print_rekap') ?>" target="_blank" class="btn btn-light btn-sm mr-1">
              <i class="fas fa-print mr-1"></i> Cetak Rekap
            </a>
            <a href="<?= base_url('tagihan/export_excel') ?>" class="btn btn-success btn-sm">
              <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
          </div>
        </div>
      </div>

      <div class="card-body">

        <div class="mb-3 d-flex justify-content-between align-items-center">
          <div class="btn-group" role="group" aria-label="Filter Data">

            <a href="<?= base_url('tagihan') ?>"
              class="btn <?= (empty($filter_active)) ? 'btn-primary' : 'btn-outline-primary' ?>">
              <i class="fas fa-list mr-1"></i> Semua
            </a>

            <a href="<?= base_url('tagihan?filter=waiting') ?>"
              class="btn <?= ($filter_active == 'waiting') ? 'btn-warning' : 'btn-outline-warning' ?> font-weight-bold text-dark">
              <i class="fas fa-user-clock mr-1"></i> Menunggu Approval Saya
            </a>

            <a href="<?= base_url('tagihan?filter=approved') ?>"
              class="btn <?= ($filter_active == 'approved') ? 'btn-success' : 'btn-outline-success' ?>">
              <i class="fas fa-check-double mr-1"></i> Sudah Di Approved
            </a>

            <a href="<?= base_url('tagihan?filter=paid') ?>"
              class="btn <?= ($filter_active == 'paid') ? 'btn-info' : 'btn-outline-info' ?>">
              <i class="fas fa-money-check-alt mr-1"></i> Sudah Dibayar
            </a>

          </div>

          <span class="text-muted font-italic small">
            Menampilkan <?= count($list_tagihan) ?> data
          </span>
        </div>

        <div class="table-responsive">
          <table class="table table-hover table-bordered" id="table-approval">
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
                    $kode_display       = $row->kode_kirim ?? '-';
                    $ekspedisi_display   = $row->nama_ekspedisi_kirim ?? $row->ekspedisi_kirim ?? '-';
                    $no_resi_display     = $row->no_resi_kirim ?? '-';
                    $tgl_sampai_display  = $row->tgl_sampai_kirim ?? null; // Estimasi Dokumen
                    $tgl_penerima_display = $row->tgl_penerima_kirim ?? null; // Realitas Dokumen (Hasil subquery model)

                    $detail_url = base_url('tagihan/detail_kirim/' . encrypt($row->id_tagihan));
                    $source_url = base_url('kirim/show/detail/' . encrypt($row->id_kirim));
                    $type_badge = '<span class="badge badge-info"><i class="fas fa-envelope mr-1"></i>Kirim Dokumen</span>';
                  } else {
                    $kode_display       = $row->no_sj ?? '-';
                    $ekspedisi_display   = $row->nama_ekspedisi ?? '-';
                    $no_resi_display     = $row->no_resi ?? '-';
                    $tgl_sampai_display  = $row->tgl_sampai ?? null; // Estimasi Barang
                    $tgl_penerima_display = $row->tgl_penerima_barang ?? $row->tgl_penerima ?? null; // Realitas Barang

                    $detail_url = base_url('tagihan/detail/' . encrypt($row->id_tagihan));
                    $source_url = base_url('tracking/detail/' . encrypt($row->id_tracking));
                    $type_badge = '<span class="badge badge-primary"><i class="fas fa-box mr-1"></i>Tracking Barang</span>';
                  }
              ?>
                  <tr>
                    <td class="text-center font-weight-bold"><?= $no++ ?></td>

                    <td>
                      <?= $type_badge ?>
                      <div class="font-weight-bold text-dark mb-1 mt-2" style="font-size:1rem;">
                        <a href="<?= $source_url ?>" target="_blank">
                          <?= $kode_display ?><i class="fas fa-external-link-alt ml-2"></i>
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

                      <?php if ($tgl_sampai_display || $tgl_penerima_display): ?>
                        <div class="text-muted text-small mt-1 d-flex flex-column" style="gap: 2px;">

                          <?php if ($tgl_sampai_display): ?>
                            <span class="border border-info text-info text-center rounded-pill my-2">
                              Tgl Estimasi Diterima: <?= date('d M Y', strtotime($tgl_sampai_display)) ?>
                            </span>
                          <?php endif; ?>

                          <?php if (!empty($tgl_penerima_display)): ?>
                            <span class="border border-success text-success text-center rounded-pill">
                              Tanggal Diterima: <?= date('d M Y', strtotime($tgl_penerima_display)) ?>
                            </span>
                          <?php endif; ?>

                        </div>
                      <?php endif; ?>
                    </td>
                    <td style="vertical-align: top;">
                      <div class="mb-2">
                        <span class="text-label d-block text-muted" style="font-size:0.7rem; margin-bottom:0;">No. Invoice</span>
                        <span class="font-weight-bold text-primary" style="font-size:0.9rem;">
                          <i class="fas fa-file-invoice mr-1"></i><?= $row->no_invoice ?? '-' ?>
                        </span>
                      </div>

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
                        <span class="font-weight-bold text-success" style="font-size:1.1rem;">
                          Rp <?= get_display_total_tagihan($row) ?>
                        </span>
                        <?php if ($this->session->userdata('role') == 'Administrator'): ?>
                          <button type="button" class="btn btn-sm btn-outline-primary ml-2 btn-edit-asuransi"
                            data-id="<?= $row->id_tagihan ?>"
                            data-nilai="<?= $row->nilai_tagihan ?>"
                            data-asuransi="<?= $row->biaya_asuransi ?? 0 ?>"
                            data-nosj="<?= $is_kirim_dokumen ? $kode_display : $row->no_sj ?>"
                            title="Edit Biaya Asuransi">
                            <i class="fas fa-edit"></i>
                          </button>
                        <?php endif; ?>
                      </div>

                      <div class="mb-2">
                        <span class="text-label d-block text-muted" style="font-size:0.7rem; margin-bottom:0;">Tgl Invoice</span>
                        <span class="text-dark font-weight-600">
                          <i class="far fa-calendar-alt mr-1 text-info"></i>
                          <?= date('d M Y', strtotime($row->tanggal_invoice)) ?>
                        </span>
                      </div>

                      <!-- ========== ALERT JATUH TEMPO & STATUS PEMBAYARAN ========== -->
                      <?php
                      // Ambil informasi dari model (sudah di-query)
                      $status_jatuh_tempo = $row->status_jatuh_tempo ?? 'Tidak Ada Info Payment';
                      $tanggal_jatuh_tempo = $row->tanggal_jatuh_tempo ?? null;
                      $payment_term = $row->payment_term ?? null;
                      $status_bayar = $row->status_bayar ?? 'belum_dibayar';
                      $hari_tersisa = $row->hari_tersisa ?? null;

                      // Get alert styling
                      $alert_class = get_alert_jatuh_tempo_class($status_jatuh_tempo);
                      $alert_icon = get_alert_jatuh_tempo_icon($status_jatuh_tempo);
                      $alert_message = get_alert_jatuh_tempo_message($row);
                      ?>

                      <?php if ($row->status_approval == 5): ?>
                        <!-- Hanya tampilkan jika tagihan sudah approved -->
                        <div class="mb-2">
                          <?php if ($status_bayar == 'sudah_dibayar'): ?>
                            <!-- Sudah Dibayar -->
                            <div class="alert alert-success py-1 px-2 mb-1" style="font-size:0.8rem;">
                              <strong><i class="fas fa-check-circle mr-1"></i>Sudah Dibayar</strong>
                              <?php if (!empty($row->tgl_bayar)): ?>
                                <br><small>Tgl: <?= date('d/m/Y', strtotime($row->tgl_bayar)) ?></small>
                              <?php endif; ?>
                            </div>
                          <?php else: ?>
                            <!-- Alert Status Jatuh Tempo -->
                            <div class="alert <?= $alert_class ?> py-1 px-2 mb-1" style="font-size:0.8rem;">
                              <strong>
                                <i class="<?= $alert_icon ?> mr-1"></i>
                                <?= $status_jatuh_tempo ?>
                              </strong>
                              <br><small><?= $alert_message ?></small>
                            </div>
                            <!-- Sub Alert: Belum Dibayar -->
                            <div class="badge badge-warning w-100 py-1" style="font-size:0.75rem;">
                              📌 Belum Dibayar
                            </div>
                          <?php endif; ?>
                        </div>
                      <?php endif; ?>
                      <!-- ========== END ALERT JATUH TEMPO ========== -->

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
                      <?php if(false) { ?>
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
                      <?php } ?>
                      
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
                            $statusLabel = 'Menunggu Approval';
                            $badgeColor  = 'badge-warning';
                        
                            if (isset($approval_configs) && !empty($approval_configs)) {
                                foreach ($approval_configs as $cfg) {
                                    // TAMBAHKAN PENGECEKAN is_active DI SINI
                                    if ($cfg->status_code == $row->status_approval) {
                                        if ($cfg->is_active == 1) {
                                            $statusLabel = 'Menunggu: ' . $cfg->role_label;
                                        } else {
                                            // Jika status saat ini ternyata adalah role yang tidak aktif, 
                                            // berarti ada ketidaksinkronan data. Tampilkan label "Pending" saja.
                                            $statusLabel = 'Menunggu Approval (Proses)';
                                        }
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
                        <div class="alert alert-danger p-2 mt-2 mb-0 text-small" style="line-height:1.2">
                          <strong>Note:</strong> "<?= substr($row->catatan_revisi, 0, 50) ?>..."
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

                        <?php if ($row->link_dokumen_lain): ?>
                          <a href="<?= $row->link_dokumen_lain ?>" target="_blank" class="btn btn-outline-secondary text-left" title="Dokumen Lain">
                            <i class="fas fa-folder-open mr-2"></i> Lainnya
                          </a>
                        <?php endif; ?>

                        <?php if ($row->link_bukti_potong): ?>
                          <a href="<?= $row->link_bukti_potong ?>" target="_blank" class="btn btn-outline-info mb-1 text-left" title="Bukti Potong">
                            <i class="fas fa-cut mr-2"></i> Bukti Potong
                          </a>
                        <?php endif; ?>
                      </div>
                    </td>

                    <td class="text-center">
                      <?php
                      // Setup logika perizinan
                      $canApprove   = false;
                      $myJabatan    = isset($current_user_jabatan) ? trim($current_user_jabatan) : '';
                      $requiredRole = '';
                      $isMySubmission = ($row->created_by == $this->session->userdata('pengguna_id'));

                      // Cari Role yang dibutuhkan untuk status saat ini
                      if (isset($approval_configs)) {
                        foreach ($approval_configs as $cfg) {
                          if ($cfg->status_code == $row->status_approval) {
                            $requiredRole = $cfg->role_label;
                            break;
                          }
                        }
                      }

                      // Logic approval permission
                      if ($requiredRole != '' && strtolower($myJabatan) == strtolower($requiredRole)) {
                        $canApprove = true;
                      }

                      // Admin bypass
                      if ($this->session->userdata('role') == 'Administrator') {
                        $canApprove = true;
                      }

                      // Safety check
                      if ($row->status_approval == 5) {
                        $canApprove = false;
                      }
                      ?>

                      <!-- Tombol Detail (Selalu Ada) -->
                      <a href="<?= $detail_url ?>"
                        class="btn btn-info btn-sm btn-block mb-2 shadow-sm">
                        <i class="fas fa-eye mr-1"></i> Detail
                      </a>

                      <?php if (isAdmin() || sessPenggunaId() == '1' || sessPenggunaId() == '106'): ?>
                        <!-- Tombol Edit Lengkap (Admin Only) -->
                        <a href="<?= base_url('tagihan/edit/' . encrypt($row->id_tagihan)) ?>"
                          class="btn btn-warning btn-sm btn-block mb-2 shadow-sm" title="Edit Data Tagihan">
                          <i class="fas fa-edit mr-1"></i> Edit Data
                        </a>
                      <?php endif; ?>

                      <?php if ($canApprove && $row->status_approval != 99): ?>
                        <!-- Tombol Approval (Jika ada hak akses dan bukan ditolak) -->
                        <div class="d-flex flex-column">
                          <button class="btn btn-success btn-sm mb-2 btn-action shadow-sm font-weight-bold"
                            data-id="<?= $row->id_tagihan ?>"
                            data-status="<?= $row->status_approval ?>"
                            data-aksi="setujui" title="Setujui Pengajuan">
                            <i class="fas fa-check-circle mr-1"></i> SETUJUI
                          </button>

                          <div class="btn-group btn-group-sm">
                            <button class="btn btn-warning btn-action shadow-sm"
                              data-id="<?= $row->id_tagihan ?>"
                              data-status="<?= $row->status_approval ?>"
                              data-aksi="revisi" title="Minta Revisi">
                              <i class="fas fa-undo"></i>
                            </button>
                            <button class="btn btn-danger btn-action shadow-sm"
                              data-id="<?= $row->id_tagihan ?>"
                              data-status="<?= $row->status_approval ?>"
                              data-aksi="tolak" title="Tolak">
                              <i class="fas fa-times"></i>
                            </button>
                          </div>
                        </div>

                      <?php elseif ($row->status_approval == 0 && $isMySubmission): ?>
                        <!-- Tombol Perbaiki (Jika status revisi dan milik sendiri) -->
                        <div class="alert alert-warning p-2 mb-2">
                          <strong><i class="fas fa-exclamation-triangle"></i> Perlu Revisi!</strong>
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
                          class="btn btn-primary btn-sm shadow-sm btn-block">
                          <i class="fas fa-edit mr-1"></i> Perbaiki Data
                        </a>

                      <?php elseif ($row->status_approval == 99): ?>
                        <!-- Status Ditolak -->
                        <div class="alert alert-danger p-2 mb-2">
                          <strong><i class="fas fa-times-circle"></i> Pengajuan Ditolak</strong>
                        </div>

                        <?php if ($isMySubmission): ?>
                          <!-- Jika milik sendiri, bisa ajukan ulang -->
                          <?php
                          // Tentukan URL reset berdasarkan tipe tagihan
                          if ($is_kirim_dokumen) {
                            $reset_url = base_url('tagihan/ajukan_kirim/' . encrypt($row->id_kirim)) . '?reset=1';
                          } else {
                            $reset_url = base_url('tagihan/ajukan/' . encrypt($row->id_tracking)) . '?reset=1';
                          }
                          ?>
                          <a href="<?= $reset_url ?>"
                            class="btn btn-warning btn-sm shadow-sm btn-block"
                            onclick="return confirm('Ajukan ulang tagihan ini? Data lama akan diganti.')">
                            <i class="fas fa-redo mr-1"></i> Ajukan Ulang
                          </a>
                        <?php else: ?>
                          <span class="text-muted text-small">Ditolak Permanen</span>
                        <?php endif; ?>

                      <?php elseif ($row->status_approval == 5): ?>
                        <!-- Status Selesai -->
                        <div class="text-success font-weight-bold">
                          <i class="fas fa-check-double"></i> Approval Selesai
                        </div>

                      <?php else: ?>
                        <!-- Status Menunggu -->
                        <span class="text-muted text-small font-italic">
                          Menunggu: <?= $requiredRole ?: 'Approval' ?>
                        </span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach;
              else: ?>
                <tr>
                  <td colspan="6" class="text-center py-5 text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                    Belum ada data tagihan yang diajukan.
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

<!-- Modal Action (Approval) -->
<div class="modal fade" id="modalAction" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-clipboard-check mr-2"></i>Konfirmasi Approval</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-action">
          <input type="hidden" name="id_tagihan" id="act_id_tagihan">
          <input type="hidden" name="current_status" id="act_current_status">
          <input type="hidden" name="aksi" id="act_tipe">

          <div class="text-center mb-4">
            <div id="icon-confirm"></div>
            <h4 class="font-weight-bold text-dark" id="modal-title-confirm">Konfirmasi</h4>
            <p class="text-muted" id="modal-text-confirm">Apakah anda yakin?</p>
          </div>

          <div id="div-catatan" class="form-group" style="display:none;">
            <label class="font-weight-bold">Catatan Revisi/Penolakan <span class="text-danger">*</span></label>
            <textarea class="form-control" name="catatan" rows="3" placeholder="Contoh: Link Invoice tidak bisa dibuka..."></textarea>
          </div>

          <div id="div-doc-tax" class="form-group" style="display:none;">
            <label class="font-weight-bold">Upload Link Dokumen Tambahan</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-link"></i></span></div>
              <input type="text" class="form-control" name="link_dokumen_lain" placeholder="Link Google Drive Bukti Potong/Lainnya...">
            </div>
            <small class="text-muted"><i class="fas fa-info-circle"></i> Opsional: Jika ada dokumen yang perlu dilampirkan saat approval.</small>
          </div>

        </form>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary font-weight-bold px-4" id="btn-save-action">
          Ya, Proses
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Biaya Asuransi (Admin Only) -->
<div class="modal fade" id="modalEditAsuransi" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-shield-alt mr-2"></i>Edit Biaya Asuransi</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-edit-asuransi">
          <input type="hidden" name="id_tagihan" id="edit_id_tagihan">

          <div class="alert alert-info py-2 mb-3">
            <i class="fas fa-info-circle mr-1"></i>
            <strong>No. SJ/Kode:</strong> <span id="edit_nosj_display">-</span>
          </div>

          <div class="form-group mb-3">
            <label class="font-weight-bold">Nilai Tagihan (Rp) <span class="text-danger">*</span></label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text bg-success text-white font-weight-bold">Rp</span></div>
              <input type="text" class="form-control" name="nilai_tagihan" id="edit_nilai_tagihan" placeholder="0" required>
            </div>
          </div>

          <div class="form-group mb-3">
            <label class="font-weight-bold">Biaya Asuransi (Rp)</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text bg-info text-white font-weight-bold">Rp</span></div>
              <input type="text" class="form-control" name="biaya_asuransi" id="edit_biaya_asuransi" placeholder="0">
            </div>
            <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Masukkan biaya asuransi sesuai invoice ekspedisi. Isi 0 jika tidak ada.</small>
          </div>

          <div class="form-group mb-3">
            <label class="font-weight-bold text-success">Total Tagihan (Rp)</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text bg-primary text-white font-weight-bold">Rp</span></div>
              <input type="text" class="form-control bg-light font-weight-bold" id="edit_total_display" readonly style="font-size:1.1rem;">
            </div>
            <small class="text-success"><i class="fas fa-calculator mr-1"></i>Total = Nilai Tagihan + Biaya Asuransi</small>
          </div>

        </form>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-info font-weight-bold px-4" id="btn-save-asuransi">
          <i class="fas fa-save mr-1"></i> Simpan Perubahan
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function() {
    // Inisialisasi DataTable (Jika Anda pakai plugin dataTables)
    if ($.fn.DataTable) {
      $('#table-approval').DataTable({
        "ordering": false,
        "pageLength": 10,
        "language": {
          "emptyTable": "Tidak ada data tersedia",
          "search": "Cari No SJ / Pengaju:"
        }
      });
    }

    // Handle Klik Tombol Action
    $('.btn-action').click(function() {
      let id = $(this).data('id');
      let status = $(this).data('status');
      let aksi = $(this).data('aksi');

      $('#act_id_tagihan').val(id);
      $('#act_current_status').val(status);
      $('#act_tipe').val(aksi);

      // Reset Tampilan Modal
      $('#div-catatan').hide();
      $('#div-doc-tax').hide();
      $('#form-action')[0].reset();

      let iconHtml = '';
      let btnClass = '';
      let titleText = '';

      // Atur Tampilan Modal Berdasarkan Aksi
      if (aksi === 'revisi') {
        iconHtml = '<i class="fas fa-undo-alt fa-3x text-warning mb-3"></i>';
        titleText = 'Minta Revisi';
        $('#modal-text-confirm').text('Kembalikan ke pengaju untuk diperbaiki?');
        $('#div-catatan').slideDown();
        $('textarea[name="catatan"]').prop('required', true);
        btnClass = 'btn-warning';
      } else if (aksi === 'tolak') {
        iconHtml = '<i class="fas fa-times-circle fa-3x text-danger mb-3"></i>';
        titleText = 'Tolak Pengajuan';
        $('#modal-text-confirm').text('Yakin ingin menolak pengajuan ini secara permanen?');
        $('#div-catatan').slideDown();
        $('textarea[name="catatan"]').prop('required', true);
        btnClass = 'btn-danger';
      } else {
        iconHtml = '<i class="fas fa-check-circle fa-3x text-success mb-3"></i>';
        titleText = 'Setujui Pengajuan';
        $('#modal-text-confirm').text('Lanjut ke tahap approval berikutnya?');
        $('textarea[name="catatan"]').prop('required', false);
        btnClass = 'btn-success';

        // Contoh Logika Dinamis: Jika level 3 (Misal Tax), tampilkan input dokumen
        // Sesuaikan ID status TAX di database config Anda (misal status code 3)
        if (status == 3) {
          $('#div-doc-tax').slideDown();
        }
      }

      $('#icon-confirm').html(iconHtml);
      $('#modal-title-confirm').text(titleText);
      $('#btn-save-action')
        .removeClass('btn-primary btn-success btn-warning btn-danger')
        .addClass(btnClass);

      $('#modalAction').modal('show');
    });

    // Handle Klik Tombol Simpan di Modal
    $('#btn-save-action').click(function() {
      let btn = $(this);
      let originalText = btn.html();

      // Validasi Manual
      let aksi = $('#act_tipe').val();
      let catatan = $('textarea[name="catatan"]').val();
      if ((aksi == 'revisi' || aksi == 'tolak') && catatan.trim() == '') {
        Swal.fire('Peringatan', 'Wajib mengisi alasan revisi/penolakan!', 'warning');
        return;
      }

      // Loading State
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

      $.ajax({
        url: '<?= base_url("tagihan/process_approval") ?>',
        type: 'POST',
        data: $('#form-action').serialize(),
        dataType: 'json',
        success: function(res) {
          if (res.status == 'success') {
            $('#modalAction').modal('hide');
            Swal.fire({
              title: 'Berhasil!',
              text: res.message,
              icon: 'success',
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire('Gagal', res.message, 'error');
            btn.prop('disabled', false).html(originalText);
          }
        },
        error: function(xhr) {
          console.log(xhr.responseText);
          Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
          btn.prop('disabled', false).html(originalText);
        }
      });
    });

    // =========================================================================
    // HANDLER EDIT BIAYA ASURANSI (ADMIN ONLY)
    // =========================================================================

    // Format number with thousand separator
    function formatNumber(num) {
      return new Intl.NumberFormat('id-ID').format(num);
    }

    // Parse formatted number back to integer
    function parseFormattedNumber(str) {
      return parseInt(str.replace(/\./g, '')) || 0;
    }

    // Calculate and display total in edit modal
    function calculateEditTotal() {
      let nilai = parseFormattedNumber($('#edit_nilai_tagihan').val());
      let asuransi = parseFormattedNumber($('#edit_biaya_asuransi').val());
      let total = nilai + asuransi;
      $('#edit_total_display').val(formatNumber(total));
    }

    // Format input on keyup
    $('#edit_nilai_tagihan, #edit_biaya_asuransi').on('input', function() {
      let value = $(this).val().replace(/\D/g, '');
      $(this).val(formatNumber(value));
      calculateEditTotal();
    });

    // Handle klik tombol Edit Asuransi
    $('.btn-edit-asuransi').click(function() {
      let id = $(this).data('id');
      let nilai = $(this).data('nilai');
      let asuransi = $(this).data('asuransi');
      let nosj = $(this).data('nosj');

      $('#edit_id_tagihan').val(id);
      $('#edit_nosj_display').text(nosj);
      $('#edit_nilai_tagihan').val(formatNumber(nilai));
      $('#edit_biaya_asuransi').val(formatNumber(asuransi));
      calculateEditTotal();

      $('#modalEditAsuransi').modal('show');
    });

    // Handle klik tombol Simpan Asuransi
    $('#btn-save-asuransi').click(function() {
      let btn = $(this);
      let originalText = btn.html();

      // Loading State
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

      $.ajax({
        url: '<?= base_url("tagihan/update_asuransi") ?>',
        type: 'POST',
        data: $('#form-edit-asuransi').serialize(),
        dataType: 'json',
        success: function(res) {
          if (res.status == 'success') {
            $('#modalEditAsuransi').modal('hide');
            Swal.fire({
              title: 'Berhasil!',
              text: res.message,
              icon: 'success',
              timer: 2000,
              showConfirmButton: false
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire('Gagal', res.message, 'error');
            btn.prop('disabled', false).html(originalText);
          }
        },
        error: function(xhr) {
          console.log(xhr.responseText);
          Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
          btn.prop('disabled', false).html(originalText);
        }
      });
    });

  });
</script>