<header class="page-header">
   <h2><i class="fas fa-file-invoice-dollar"></i>&nbsp;<?= $page_title ?></h2>
   <div class="right-wrapper text-left">
      <ol class="breadcrumbs">
         <li><span><?= isset($page_desc) ? $page_desc : 'Daftar SPP' ?></span></li>
      </ol>
   </div>
</header>

<style>
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
      vertical-align: top !important;
      /* Ubah ke top agar rapi saat list panjang */
      font-size: 0.9rem;
      padding: 1rem 0.75rem;
   }

   .badge {
      padding: 0.6em 1em;
      font-size: 80%;
      font-weight: 600;
      border-radius: 20px;
   }

   .table-hover tbody tr:hover {
      background-color: #f1f5f9;
   }

   .text-label {
      font-size: 0.65rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #8898aa;
      font-weight: 700;
      margin-bottom: 2px;
   }

   .stat-card {
      border-radius: 10px;
      padding: 20px;
      color: white;
      margin-bottom: 20px;
   }

   .stat-card.pending {
      background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
   }

   .stat-card.approved {
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
   }

   .stat-card.total {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
   }

   /* Style untuk garis pemisah halus */
   .divider-dashed {
      border-top: 1px dashed #e0e0e0;
      margin: 5px 0;
   }
</style>

<div class="row mb-4">
   <div class="col-md-3">
      <div class="stat-card total">
         <div class="d-flex justify-content-between align-items-center">
            <div>
               <h6 class="mb-1 text-white-50">Total SPP</h6>
               <h3 class="mb-0 font-weight-bold"><?= count($all_spp ?? $list_spp) ?></h3>
            </div>
            <i class="fas fa-file-invoice fa-2x opacity-50"></i>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="stat-card pending">
         <div class="d-flex justify-content-between align-items-center">
            <div>
               <h6 class="mb-1 text-white-50">Menunggu Approval</h6>
               <h3 class="mb-0 font-weight-bold">
                  <?php
                  $pending = 0;
                  $data_source = $all_spp ?? $list_spp;
                  foreach ($data_source as $s) {
                     if ($s->status_approval > 0 && $s->status_approval < 5) $pending++;
                  }
                  echo $pending;
                  ?>
               </h3>
            </div>
            <i class="fas fa-clock fa-2x opacity-50"></i>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="stat-card approved">
         <div class="d-flex justify-content-between align-items-center">
            <div>
               <h6 class="mb-1 text-white-50">Sudah Disetujui</h6>
               <h3 class="mb-0 font-weight-bold">
                  <?php
                  $approved = 0;
                  foreach ($data_source as $s) {
                     if ($s->status_approval == 5) $approved++;
                  }
                  echo $approved;
                  ?>
               </h3>
            </div>
            <i class="fas fa-check-circle fa-2x opacity-50"></i>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="stat-card" style="background: linear-gradient(135deg, #0575e6 0%, #021b79 100%);">
         <div class="d-flex justify-content-between align-items-center">
            <div>
               <h6 class="mb-1 text-white-50">Sudah Dibayar</h6>
               <h3 class="mb-0 font-weight-bold">
                  <?php
                  $paid = 0;
                  foreach ($data_source as $s) {
                     if (isset($s->status_bayar) && $s->status_bayar == 'sudah_dibayar') $paid++;
                  }
                  echo $paid;
                  ?>
               </h3>
            </div>
            <i class="fas fa-money-check-alt fa-2x opacity-50"></i>
         </div>
      </div>
   </div>
</div>

<div class="row">
   <div class="col-md-12">

      <div class="card shadow-sm mb-4">
         <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
               <h5 class="m-0 font-weight-bold"><i class="fas fa-list-ul mr-2"></i>Daftar Surat Permintaan Pembayaran</h5>
               <div>
                  <a href="<?= base_url('spp/print_rekap') ?>" target="_blank" class="btn btn-light btn-sm mr-1">
                     <i class="fas fa-print mr-1"></i> Cetak Rekap
                  </a>
                  <a href="<?= base_url('spp/export_excel') ?>" class="btn btn-success btn-sm mr-1">
                     <i class="fas fa-file-excel mr-1"></i> Export Excel
                  </a>
                  <a href="<?= base_url('spp/create') ?>" class="btn btn-warning btn-sm font-weight-bold">
                     <i class="fas fa-plus mr-1"></i> Ajukan SPP Baru
                  </a>
               </div>
            </div>
         </div>

         <div class="card-body">

            <div class="mb-3 d-flex justify-content-between align-items-center">
               <div class="btn-group" role="group" aria-label="Filter Data">

                  <a href="<?= base_url('spp') ?>"
                     class="btn <?= (empty($filter_active)) ? 'btn-primary' : 'btn-outline-primary' ?>">
                     <i class="fas fa-list mr-1"></i> Semua
                  </a>

                  <a href="<?= base_url('spp?filter=waiting') ?>"
                     class="btn <?= ($filter_active == 'waiting') ? 'btn-warning' : 'btn-outline-warning' ?> font-weight-bold text-dark">
                     <i class="fas fa-clock mr-1"></i> Menunggu Approval
                  </a>

                  <a href="<?= base_url('spp?filter=approved') ?>"
                     class="btn <?= ($filter_active == 'approved') ? 'btn-success' : 'btn-outline-success' ?>">
                     <i class="fas fa-check-double mr-1"></i> Sudah Disetujui
                  </a>

                  <a href="<?= base_url('spp?filter=paid') ?>"
                     class="btn <?= ($filter_active == 'paid') ? 'btn-info' : 'btn-outline-info' ?>">
                     <i class="fas fa-money-check-alt mr-1"></i> Sudah Dibayar
                  </a>

               </div>

               <span class="text-muted font-italic small">
                  Menampilkan <?= count($list_spp) ?> data
               </span>
            </div>

            <div class="table-responsive">
               <table class="table table-hover table-bordered" id="table-spp">
                  <thead>
                     <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="15%">No. SPP</th>
                        <th width="15%">Informasi Invoice</th>
                        <th width="15%">Tujuan Transfer</th>
                        <th width="20%">Rincian Nilai</th>
                        <th width="15%">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $no = 1;
                     if (!empty($list_spp)):
                        foreach ($list_spp as $row):
                     ?>
                           <tr>
                              <td class="text-center font-weight-bold"><?= $no++ ?></td>

                              <td>
                                 <div class="font-weight-bold text-primary mb-1" style="font-size:1rem;">
                                    <?= $row->no_spp ?>
                                 </div>
                                 <div class="text-muted text-small">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    <?= date('d M Y H:i', strtotime($row->created_at)) ?>
                                 </div>
                                 <div class="text-muted text-small mt-1">
                                    <i class="fas fa-user mr-1"></i>
                                    <?= $row->nama_pengaju ?>
                                 </div>
                              </td>

                              <td>
                                 <div class="mb-1">
                                    <span class="text-label d-block">No. Invoice</span>
                                    <span class="font-weight-bold text-dark"><?= $row->no_invoice ?></span>
                                 </div>
                                 <div>
                                    <span class="text-label d-block">Jumlah Tagihan</span>
                                    <span class="badge badge-info"><?= $row->jumlah_tagihan ?> item</span>
                                 </div>
                              </td>

                              <td>
                                 <?php if (!empty($row->nama_bank)): ?>
                                    <div class="mb-1">
                                       <span class="text-label d-block">Bank</span>
                                       <span class="font-weight-bold text-dark"><?= strtoupper($row->nama_bank) ?></span>
                                    </div>
                                    <div class="mb-1">
                                       <span class="text-label d-block">No. Rekening</span>
                                       <span class="font-weight-bold text-primary copy-text" style="cursor:pointer" title="Klik untuk copy">
                                          <?= $row->no_rekening ?>
                                       </span>
                                    </div>
                                    <div>
                                       <span class="text-label d-block">A.N</span>
                                       <span class="text-dark small"><?= strtoupper($row->atas_nama) ?></span>
                                    </div>
                                 <?php else: ?>
                                    <span class="text-muted font-italic small">- Data Belum Ada -</span>
                                 <?php endif; ?>
                              </td>

                              <td>
                                 <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-label mb-0">Total Tagihan</span>
                                    <span class="font-weight-bold text-dark">
                                       Rp <?= number_format($row->total_nilai, 0, ',', '.') ?>
                                    </span>
                                 </div>

                                 <?php if (!empty($row->ppn) && $row->ppn > 0): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                       <span class="text-label mb-0">PPN</span>
                                       <span class="text-dark small">
                                          + Rp <?= number_format($row->ppn, 0, ',', '.') ?>
                                       </span>
                                    </div>
                                 <?php endif; ?>

                                 <?php if (!empty($row->biaya_lainnya) && $row->biaya_lainnya > 0): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                       <span class="text-label mb-0">Biaya Lain</span>
                                       <span class="text-info small">
                                          + Rp <?= number_format($row->biaya_lainnya, 0, ',', '.') ?>
                                       </span>
                                    </div>
                                 <?php endif; ?>

                                 <?php if (!empty($row->diskon) && $row->diskon > 0): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                       <span class="text-label mb-0">Diskon</span>
                                       <span class="text-warning small">
                                          - Rp <?= number_format($row->diskon, 0, ',', '.') ?>
                                       </span>
                                    </div>
                                 <?php endif; ?>

                                 <?php if ((!empty($row->ppn) && $row->ppn > 0) || (!empty($row->diskon) && $row->diskon > 0)): ?>
                                    <div class="divider-dashed"></div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                       <span class="text-label mb-0">Grand Total</span>
                                       <span class="font-weight-bold text-success">
                                          Rp <?= number_format($row->grand_total, 0, ',', '.') ?>
                                       </span>
                                    </div>
                                 <?php endif; ?>

                                 <?php if (!empty($row->nilai_bukti_potong) && $row->nilai_bukti_potong > 0): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                       <span class="text-label mb-0" style="color:#d32f2f;">Bukti Potong</span>
                                       <span class="text-danger small">
                                          - Rp <?= number_format($row->nilai_bukti_potong, 0, ',', '.') ?>
                                       </span>
                                    </div>
                                 <?php endif; ?>

                                 <div class="divider-dashed"></div>
                                 <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-label mb-0 font-weight-bold text-primary">NILAI PEMBAYARAN</span>
                                    <span class="font-weight-bold text-primary" style="font-size:1.1rem;">
                                       Rp <?= number_format($row->nilai_pembayaran, 0, ',', '.') ?>
                                    </span>
                                 </div>
                              </td>

                              <td>
                                 <?php
                                 $statusLabel = 'Unknown';
                                 $badgeColor = 'badge-secondary';

                                 if ($row->status_approval == 0) {
                                    $statusLabel = 'PERLU REVISI';
                                    $badgeColor = 'badge-danger';
                                 } elseif ($row->status_approval == 5) {
                                    $statusLabel = 'SELESAI';
                                    $badgeColor = 'badge-success';
                                 } elseif ($row->status_approval == 99) {
                                    $statusLabel = 'DITOLAK';
                                    $badgeColor = 'badge-dark';
                                 } else {
                                    if (isset($approval_configs)) {
                                       foreach ($approval_configs as $cfg) {
                                          if ($cfg->status_code == $row->status_approval) {
                                             $statusLabel = 'Menunggu: ' . $cfg->role_label;
                                             $badgeColor = 'badge-warning';
                                             break;
                                          }
                                       }
                                    }
                                 }
                                 ?>
                                 <span class="badge <?= $badgeColor ?> w-100 py-2 mb-2" style="white-space: normal;">
                                    <?= $statusLabel ?>
                                 </span>

                                 <?php if ($row->status_approval == 5): ?>
                                    <!-- Tampilkan Alert Jatuh Tempo untuk SPP yang sudah SELESAI (Approved) -->
                                    <?php
                                    $status_jatuh_tempo = $row->status_jatuh_tempo ?? 'Tidak Ada Info Payment';
                                    $status_bayar_spp = $row->latest_status_bayar ?? 'belum_dibayar';

                                    // Get alert styling
                                    $alert_class = get_alert_jatuh_tempo_class($status_jatuh_tempo);
                                    $alert_icon = get_alert_jatuh_tempo_icon($status_jatuh_tempo);
                                    $alert_message = get_alert_jatuh_tempo_message($row);
                                    ?>

                                    <?php if ($status_bayar_spp == 'sudah_dibayar'): ?>
                                       <!-- Sudah Dibayar -->
                                       <div class="alert alert-success mb-0 py-2 px-2" style="font-size:0.75rem;">
                                          <div class="d-flex justify-content-between align-items-center">
                                             <div>
                                                <i class="fas fa-check-circle mr-1"></i>
                                                <strong>Sudah Dibayar</strong>
                                             </div>
                                             <?php if (!empty($row->link_bukti_bayar)): ?>
                                                <a href="<?= $row->link_bukti_bayar ?>" target="_blank" class="btn btn-sm btn-outline-success py-0 px-2" title="Lihat Bukti Bayar">
                                                   <i class="fas fa-receipt"></i> Bukti
                                                </a>
                                             <?php endif; ?>
                                          </div>
                                       </div>
                                    <?php else: ?>
                                       <!-- Alert Jatuh Tempo -->
                                       <div class="alert <?= $alert_class ?> mb-0 py-1 px-2" style="font-size:0.75rem;">
                                          <div class="d-flex align-items-center">
                                             <i class="<?= $alert_icon ?> mr-1"></i>
                                             <div class="flex-grow-1">
                                                <strong class="d-block"><?= $status_jatuh_tempo ?></strong>
                                                <small style="font-size:0.7rem;"><?= $alert_message ?></small>
                                             </div>
                                          </div>
                                       </div>
                                    <?php endif; ?>
                                 <?php endif; ?>
                              </td>

                              <td class="text-center">
                                 <?php
                                 $target_recipient = '';
                                 if ($row->status_approval == 0 || $row->status_approval == 99) {
                                    $target_recipient = $row->nama_pengaju;
                                 } elseif ($row->status_approval == 5) {
                                    $target_recipient = $row->nama_pengaju . ' & Accounting/Tax';
                                 } else {
                                    if (isset($approval_configs)) {
                                       foreach ($approval_configs as $cfg) {
                                          if ($cfg->status_code == $row->status_approval) {
                                             $target_recipient = $cfg->role_label;
                                             break;
                                          }
                                       }
                                    }
                                    if (empty($target_recipient)) {
                                       $target_recipient = 'Approver';
                                    }
                                 }
                                 ?>
                                 <div class="d-flex flex-wrap justify-content-center align-items-center" style="gap: 5px;">
                                    <a href="<?= base_url('spp/detail/' . encrypt($row->id_spp)) ?>" class="btn btn-info btn-sm" title="Lihat Detail" style="white-space: nowrap;">
                                       <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="#"
                                       class="btn btn-success btn-sm btn-resend-wa"
                                       title="Resend WA"
                                       style="white-space: nowrap;"
                                       data-recipient="<?= htmlspecialchars($target_recipient) ?>"
                                       data-url="<?= base_url('spp/resend_wa_spp/' . encrypt($row->id_spp)) ?>">
                                       <i class="fab fa-whatsapp"></i> Resend WA
                                    </a>
                                    <?php
                                    $can_edit = false;
                                    // Logic Edit: Status Revisi(0), Ditolak(99) atau Admin
                                    if ($row->status_approval == 0 || $row->status_approval == 99) {
                                       if (sessPenggunaId() == $row->created_by || isset($is_admin) && $is_admin) {
                                          $can_edit = true;
                                       }
                                    }
                                    // Admin bisa edit kapan saja (tergantung kebijakan, biasanya via menu edit_admin)
                                    if (isset($is_admin) && $is_admin) $can_edit = true;

                                    // Tombol khusus Revisi Pengaju
                                    if (($row->status_approval == 0 || $row->status_approval == 99) && sessPenggunaId() == $row->created_by):
                                    ?>
                                       <a href="<?= base_url('spp/edit/' . encrypt($row->id_spp)) ?>" class="btn btn-warning btn-sm" title="Revisi" style="white-space: nowrap;">
                                          <i class="fas fa-edit"></i> Revisi
                                       </a>
                                    <?php endif; ?>
                                 </div>
                              </td>
                           </tr>
                        <?php endforeach;
                     else: ?>
                        <tr>
                           <td colspan="7" class="text-center py-5 text-muted">
                              <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                              Belum ada data SPP
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
      $('#table-spp').DataTable({
         "ordering": false,
         "pageLength": 10,
         "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Hal _PAGE_ dari _PAGES_",
            "emptyTable": "Tidak ada data",
            "zeroRecords": "Data tidak ditemukan",
            "paginate": {
               "previous": "<",
               "next": ">"
            }
         }
      });

      // Handle click on Resend WA button with confirmation
      $(document).on('click', '.btn-resend-wa', function(e) {
         e.preventDefault();
         var url = $(this).data('url');
         var recipient = $(this).data('recipient');

         Swal.fire({
            title: 'Konfirmasi Kirim Notifikasi',
            html: 'Apakah Anda ingin mengirim notifikasi kembali ke <strong>' + recipient + '</strong>?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
         }).then((result) => {
            if (result.isConfirmed) {
               Swal.fire({
                  title: 'Mengirim...',
                  text: 'Mohon tunggu sebentar.',
                  allowOutsideClick: false,
                  didOpen: () => {
                     Swal.showLoading();
                  }
               });
               window.location.href = url;
            }
         });
      });

      // Show SweetAlert for session flash messages
      <?php if ($this->session->flashdata('success')): ?>
         Swal.fire({
            title: 'Berhasil!',
            text: '<?= htmlspecialchars($this->session->flashdata('success')) ?>',
            icon: 'success',
            confirmButtonColor: '#28a745'
         });
      <?php endif; ?>

      <?php if ($this->session->flashdata('error')): ?>
         Swal.fire({
            title: 'Gagal!',
            text: '<?= htmlspecialchars($this->session->flashdata('error')) ?>',
            icon: 'error',
            confirmButtonColor: '#dc3545'
         });
      <?php endif; ?>
   });
</script>