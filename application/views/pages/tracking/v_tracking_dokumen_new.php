<!-- HEADER -->
<header class="page-header">
   <h2><i class="icons fas fa-paper-plane"></i>&nbsp;<?= $page_title ?></h2>
   <div class="right-wrapper text-left">
      <ol class="breadcrumbs">
         <li><span><?= $page_desc ?></span></li>
      </ol>
   </div>
</header>

<?php if ($this->session->flashdata('error_message')): ?>
   <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-triangle mr-2"></i>
      <strong>Error!</strong> <?= $this->session->flashdata('error_message') ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
      </button>
   </div>
<?php endif; ?>

<?php if ($this->session->flashdata('success_message')): ?>
   <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle mr-2"></i>
      <strong>Sukses!</strong> <?= $this->session->flashdata('success_message') ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
      </button>
   </div>
<?php endif; ?>

<div class="row">
   <div class="col">
      <div class="mb-3 d-print-none">
         <?php if (in_array(sessPenggunaId(), [1, 7, 15, 33, 73])) { ?>
            <a href="<?= base_url('kirim') ?>" class="btn btn-primary">
               <i class="fas fa-arrow-left"></i> Ke Halaman Kirim Dokumen
            </a>
         <?php } ?>
      </div>

      <section class="card">
         <div class="card-body">
            <div class="alert alert-info">
               <i class="fas fa-info-circle"></i>
               <strong>Informasi:</strong> Data tracking dokumen dikelola melalui halaman <strong>Kirim Dokumen</strong>.
               Halaman ini hanya menampilkan status tracking terkini dari setiap dokumen yang telah dikirim.
            </div>

            <div class="table-responsive">
               <table class="table table-striped table-condensed table-hover mb-0" id="dtTrackingDokumen">
                  <thead>
                     <tr>
                        <th class="text-center">#</th>
                        <th class="text-center" width="12%">TGL UPDATE</th>
                        <th class="text-left">NO. DOKUMEN</th>
                        <th class="text-left">CUSTOMER</th>
                        <th class="text-left">EKSPEDISI</th>
                        <th class="text-left">NO. RESI</th>
                        <th class="text-center" width="10%">STATUS</th>
                        <th class="text-center" width="8%">AKSI</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $no = 1;
                     if (!empty($list_tracking_dokumen)) {
                        foreach ($list_tracking_dokumen as $row) {
                           // Status badge color
                           $status_class = 'secondary';
                           if ($row->id_status == 5) $status_class = 'success';
                           elseif ($row->id_status == 2 || $row->id_status == 3) $status_class = 'info';
                           elseif ($row->id_status == 4) $status_class = 'warning';
                           elseif ($row->id_status == 0) $status_class = 'secondary';
                     ?>
                           <tr>
                              <td class="text-center"><?= $no++ ?></td>
                              <td class="text-center"><?= indo_date($row->tgl_update) ?></td>
                              <td>
                                 <strong><?= $row->kode ?></strong><br>
                                 <small class="text-muted">Kirim: <?= indo_date($row->tgl_kirim) ?></small>
                              </td>
                              <td>
                                 <?= $row->nama_customer ?><br>
                                 <small class="text-muted"><?= $row->pic ?></small>
                              </td>
                              <td><?= $row->ekspedisi ?: '-' ?></td>
                              <td><?= $row->no_resi ?: '-' ?></td>
                              <td class="text-center">
                                 <span class="badge badge-<?= $status_class ?>"><?= $row->status_label ?></span>
                              </td>
                              <td class="text-center">
                                 <a href="<?= base_url('kirim/show/detail/' . encrypt($row->id_kirim)) ?>"
                                    class="btn btn-sm btn-info"
                                    title="Lihat Detail & Timeline">
                                    <i class="fas fa-eye"></i>
                                 </a>
                              </td>
                           </tr>
                        <?php
                        }
                     } else {
                        ?>
                        <tr>
                           <td colspan="8" class="text-center text-muted py-4">
                              <i class="fas fa-inbox fa-3x mb-3"></i><br>
                              Data tracking dokumen tidak ditemukan
                           </td>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>
         </div>
      </section>
   </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
      // Simple DataTable for display only (no server-side processing needed)
      $('#dtTrackingDokumen').DataTable({
         responsive: true,
         order: [
            [1, 'desc']
         ], // Sort by tanggal update descending
         pageLength: 25,
         language: {
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan halaman _PAGE_ dari _PAGES_",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(difilter dari _MAX_ total data)",
            search: "Cari:",
            paginate: {
               first: "Pertama",
               last: "Terakhir",
               next: "Selanjutnya",
               previous: "Sebelumnya"
            }
         }
      });
   });
</script>