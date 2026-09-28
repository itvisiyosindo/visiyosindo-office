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
      <section class="card">
         <div class="card-body">
            <!-- Action Buttons -->
            <div class="mb-3">
               <a href="<?= base_url('tracking/import_dokumen_page') ?>" class="btn btn-primary">
                  <i class="fa fa-upload"></i> Import dari Excel
               </a>
               <a href="<?= base_url('tracking/download_template_dokumen') ?>" class="btn btn-info">
                  <i class="fa fa-download"></i> Download Template
               </a>
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
                        <th class="text-center" width="10%">AKSI</th>
                     </tr>
                  </thead>
               </table>
            </div>
         </div>
      </section>
   </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
      // DataTable
      var table = $('#dtTrackingDokumen').DataTable({
         responsive: true,
         serverSide: true,
         processing: true,
         ajax: {
            url: "<?= base_url('tracking/pagination_dokumen') ?>",
            type: "POST"
         },
         columns: [{
               data: 0,
               className: "text-center"
            },
            {
               data: 1,
               className: "text-center"
            },
            {
               data: 2,
               className: "text-left"
            },
            {
               data: 3,
               className: "text-left"
            },
            {
               data: 4,
               className: "text-left"
            },
            {
               data: 5,
               className: "text-left"
            },
            {
               data: 6,
               className: "text-center"
            },
            {
               data: 7,
               className: "text-center",
               orderable: false
            }
         ],
         order: [
            [1, 'desc']
         ],
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
            },
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
         }
      });
   });
</script>