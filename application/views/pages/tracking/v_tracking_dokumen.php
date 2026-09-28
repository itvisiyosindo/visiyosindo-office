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
      <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
         <?php if (in_array(sessPenggunaId(), [1, 15, 33, 7, 73, 763, 769])) { ?>
            <a href="javascript:;" id="btn-show-add-form-kd" class="btn btn-sm btn-primary mr-2">
               <i class="icons icon-plus"></i>&nbsp;Tambah Tracking Kirim Dokumen
            </a>
            <a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success">
               <i class="fas fa-print"></i>&nbsp;Print Rekapan
            </a>
         <?php } ?>
      </div>

      <section class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-striped table-condensed table-hover mb-0" id="dtTracking">
                  <thead>
                     <tr>
                        <th class="text-center">#</th>
                        <th class="text-center" width="13%">TANGGAL</th>
                        <th class="text-left">NO. RESI</th>
                        <th class="text-left">NO. DOKUMEN</th>
                        <th class="text-left">EKSPEDISI</th>
                        <th class="text-left">STATUS</th>
                        <th class="text-center" width="8%">AKSI</th>
                     </tr>
                  </thead>
               </table>
            </div>
         </div>
      </section>
   </div>
</div>

<!-- Modal Add Kirim Dokumen -->
<div id="modalAddKirimDokumen" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <form id="formAddKirimDokumen" method="post">
            <div class="modal-header bg-primary">
               <h4 class="modal-title text-white">
                  <i class="fa fa-plus"></i> Tambah Tracking Kirim Dokumen
               </h4>
               <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <input type="hidden" name="tracking_type" value="kirim_dokumen">
               <input type="hidden" name="id_pengguna" value="<?= sessPenggunaId() ?>">
               <input type="hidden" id="kode_kirim_dokumen" name="kode_kirim_dokumen">
               <input type="hidden" id="nama_ekspedisi_kd" name="nama_ekspedisi_kd">
               <input type="hidden" id="tgl_kirim_kd" name="tgl_kirim_kd">
               <input type="hidden" id="status" name="status" value="1">

               <div class="form-group">
                  <label for="id_kirim_dokumen_kd">Nomor Surat Jalan <span class="text-danger">*</span></label>
                  <select class="form-control" id="id_kirim_dokumen_kd" name="id_kirim_dokumen" required>
                     <option value="">-- Pilih No. Kirim Dokumen --</option>
                     <?php foreach ($list_kirim_dokumen as $row) { ?>
                        <option value="<?= $row->id_kirim_dokumen ?>"
                           data-kode="<?= $row->kode ?>"
                           data-customer="<?= $row->nama_customer ?>"
                           data-pic="<?= $row->pic ?>"
                           data-alamat="<?= $row->alamat ?>"
                           data-ekspedisi="<?= $row->ekspedisi ?>"
                           data-id-ekspedisi="<?= $row->id_ekspedisi ?>"
                           data-tgl-kirim="<?= $row->tgl_kirim ?>">
                           <?= $row->kode ?> - <?= $row->nama_customer ?>
                        </option>
                     <?php } ?>
                  </select>
               </div>

               <div class="form-group">
                  <label>Gudang Pengirim:</label>
                  <label class="form-control" style="background-color: #e9ecef;">Warehouse</label>
               </div>

               <div class="form-group">
                  <label>Nama Customer:</label>
                  <label id="nama_customer_display" class="form-control" style="background-color: #e9ecef;"></label>
               </div>

               <div class="form-group">
                  <label>PIC Penerima:</label>
                  <input type="text" id="pic_kirim_dokumen_display" name="pic_kirim_dokumen" class="form-control" readonly style="background-color: #e9ecef;">
               </div>

               <div class="form-group">
                  <label>Alamat Penerima:</label>
                  <input type="text" id="alamat_kirim_dokumen_display" name="alamat_kirim_dokumen" class="form-control" readonly style="background-color: #e9ecef;">
               </div>

               <div class="form-group">
                  <label>Ekspedisi:</label>
                  <label id="ekspedisi_display" class="form-control" style="background-color: #e9ecef;"></label>
               </div>

               <br><br>
               <strong>Diisi oleh Tim Warehouse</strong>

               <div class="form-group">
                  <label>Estimasi Sampai <span class="text-danger">*</span></label>
                  <div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format":"dd-mm-yyyy"}'>
                     <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                     <input type="text" id="tgl_sampai_kd" name="tgl_sampai" class="form-control" required>
                  </div>
               </div>

               <div class="form-group">
                  <label for="id_ekspedisi_kd">Nama Ekspedisi <span class="text-danger">*</span></label>
                  <select class="form-control" id="id_ekspedisi_kd" name="id_ekspedisi_kd" required>
                     <option value="">-- Pilih Ekspedisi --</option>
                     <?php foreach ($list_eks as $row) { ?>
                        <option value="<?= $row->id_ekspedisi ?>" data-nama="<?= $row->nama_ekspedisi ?>"><?= $row->nama_ekspedisi ?></option>
                     <?php } ?>
                  </select>
               </div>

               <div class="form-group">
                  <label for="no_resi_kd">No Resi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="no_resi_kd" name="no_resi" placeholder="Masukkan No Resi" required>
               </div>

               <div class="form-group">
                  <label for="link_resi_kd">Link Resi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="link_resi_kd" name="link_resi" placeholder="Google Drive link (atau isi -)" required>
               </div>

               <div class="form-group">
                  <label for="keterangan_kd">Keterangan Lainnya <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="keterangan_kd" name="keterangan" rows="3" required></textarea>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">
                  <i class="fa fa-times"></i> Batal
               </button>
               <button type="submit" class="btn btn-primary" id="btnSubmitKirimDokumen">
                  <i class="fa fa-save"></i> Simpan
               </button>
            </div>
         </form>
      </div>
   </div>
</div>

<!-- Modal Print Laporan -->
<div id="modalLaporan" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <form id="formLaporan" method="post" action="<?= base_url('report/tracking/print_laporan') ?>" target="_blank">
            <div class="modal-header bg-success">
               <h4 class="modal-title text-white">
                  <i class="fas fa-print"></i> Print Rekapan Tracking Dokumen
               </h4>
               <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <input type="hidden" name="tracking_type" value="kirim_dokumen">
               <div class="form-group">
                  <label for="dari_tanggal">Dari Tanggal <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="dari_tanggal" name="dari_tanggal" required>
               </div>
               <div class="form-group">
                  <label for="sampai_tanggal">Sampai Tanggal <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="sampai_tanggal" name="sampai_tanggal" required>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">
                  <i class="fa fa-times"></i> Batal
               </button>
               <button type="submit" class="btn btn-success">
                  <i class="fas fa-print"></i> Cetak
               </button>
            </div>
         </form>
      </div>
   </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
      var table = $('#dtTracking').DataTable({
         responsive: true,
         serverSide: true,
         processing: true,
         searching: true,
         ajax: {
            url: "<?php echo base_url('tracking/pagination') ?>",
            type: "POST",
            data: function(d) {
               d.tracking_mode = 'dokumen'; // Only kirim_dokumen
            }
         },
         columns: [{
               data: 0,
               className: "text-center"
            }, // Index # (nomor urut)
            {
               data: 7,
               className: "text-center"
            }, // Tanggal (tgl_sampai)
            {
               data: 15,
               className: "text-left"
            }, // No Resi
            {
               data: 1,
               className: "text-left"
            }, // No Dokumen (no_sj dengan badge)
            {
               data: 6,
               className: "text-left"
            }, // Ekspedisi (nama_ekspedisi)
            {
               data: 8,
               className: "text-left"
            }, // Status (stat badge)
            {
               data: 9,
               className: "text-center",
               orderable: false,
               searchable: false
            } // Aksi (li_btn)
         ],
         order: [
            [1, 'desc']
         ],
         pageLength: 10,
         lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "Semua"]
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

      // Show Add Form Kirim Dokumen
      $('#btn-show-add-form-kd').click(function() {
         $('#modalAddKirimDokumen').modal('show');
      });

      // Auto-fill data when kirim dokumen selected
      $('#id_kirim_dokumen_kd').change(function() {
         var selected = $(this).find('option:selected');
         var kode = selected.data('kode') || '';
         var customer = selected.data('customer') || '';
         var pic = selected.data('pic') || '';
         var alamat = selected.data('alamat') || '';
         var ekspedisi = selected.data('ekspedisi') || '';
         var idEkspedisi = selected.data('id-ekspedisi') || '';
         var tglKirim = selected.data('tgl-kirim') || '';

         // Set hidden fields
         $('#kode_kirim_dokumen').val(kode);
         $('#tgl_kirim_kd').val(tglKirim);

         // Set display fields
         $('#nama_customer_display').text(customer);
         $('#pic_kirim_dokumen_display').val(pic);
         $('#alamat_kirim_dokumen_display').val(alamat);
         $('#ekspedisi_display').text(ekspedisi);

         // Auto-select ekspedisi jika sudah ada
         if (idEkspedisi) {
            $('#id_ekspedisi_kd').val(idEkspedisi).trigger('change');
         }
      });

      // Set nama ekspedisi when selected
      $('#id_ekspedisi_kd').change(function() {
         var selected = $(this).find('option:selected');
         $('#nama_ekspedisi_kd').val(selected.data('nama') || '');
      });

      // Submit Add Form Kirim Dokumen
      $('#formAddKirimDokumen').submit(function(e) {
         e.preventDefault();
         $('#btnSubmitKirimDokumen').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

         $.ajax({
            url: "<?= base_url('tracking/add') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
               $('#btnSubmitKirimDokumen').prop('disabled', false).html('<i class="fa fa-save"></i> Simpan');

               if (response.status === 'success') {
                  $('#modalAddKirimDokumen').modal('hide');
                  $('#formAddKirimDokumen')[0].reset();
                  table.ajax.reload(null, false);

                  Swal.fire({
                     icon: 'success',
                     title: 'Berhasil!',
                     text: response.message,
                     showConfirmButton: false,
                     timer: 2000
                  });
               } else {
                  Swal.fire({
                     icon: 'error',
                     title: 'Gagal!',
                     text: response.message
                  });
               }
            },
            error: function() {
               $('#btnSubmitKirimDokumen').prop('disabled', false).html('<i class="fa fa-save"></i> Simpan');
               Swal.fire({
                  icon: 'error',
                  title: 'Error!',
                  text: 'Terjadi kesalahan sistem'
               });
            }
         });
      });

      // Delete Tracking
      $(document).on('click', '.btn-delete-tracking', function() {
         var id = $(this).data('id');
         var resi = $(this).data('resi');

         Swal.fire({
            title: 'Hapus Tracking?',
            html: 'Anda yakin ingin menghapus tracking dengan no resi <strong>' + resi + '</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
         }).then((result) => {
            if (result.isConfirmed) {
               $.ajax({
                  url: "<?= base_url('tracking/delete/') ?>" + id,
                  type: "POST",
                  dataType: "json",
                  success: function(response) {
                     if (response.status === 'success') {
                        table.ajax.reload(null, false);
                        Swal.fire({
                           icon: 'success',
                           title: 'Terhapus!',
                           text: response.message,
                           showConfirmButton: false,
                           timer: 2000
                        });
                     } else {
                        Swal.fire({
                           icon: 'error',
                           title: 'Gagal!',
                           text: response.message
                        });
                     }
                  },
                  error: function() {
                     Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan sistem'
                     });
                  }
               });
            }
         });
      });

      // Show Print Laporan Form
      $('#btn-laporan-form').click(function() {
         $('#modalLaporan').modal('show');
      });

      // Modal hidden reset form
      $('#modalAddKirimDokumen').on('hidden.bs.modal', function() {
         $('#formAddKirimDokumen')[0].reset();
      });
   });
</script>