<header class="page-header">
    <h2><i class="icons icon-calendar"></i>&nbsp;<?= $page_title ?></h2>
</header>

<div class="row">
    <div class="col-md-12 text-right mb-3">
        <?php if(isAdmin()): ?>
            <button type="button" class="btn btn-sm btn-success" id="btn-cek-massal">
                <i class="fab fa-whatsapp"></i> Cek & Kirim Reminder H-3 (Massal)
            </button>
        <?php endif; ?>
    </div>

    <div class="col-md-12">
        <section class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0" id="dt-reminder">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Jenis Event</th>
                                <th>Informasi</th>
                                <th>Tanggal H-0</th>
                                <th class="text-center">Countdown</th>
                                <?php if(isAdmin()): ?>
                                    <th class="text-center">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data_reminder)): foreach($data_reminder as $no => $r): 
                                $badge = ($r->sisa_hari == 0) ? 'badge-danger' : (($r->sisa_hari <= 3) ? 'badge-warning' : 'badge-info');
                            ?>
                            <tr>
                                <td><?= $no+1 ?></td>
                                <td><?= $r->nama ?></td>
                                <td><?= $r->jabatan ?></td>
                                <td><?= $r->jenis ?></td>
                                <td><?= $r->info_tahun ?></td>
                                <td><?= date('d F', strtotime($r->tgl_event)) ?></td>
                                <td class="text-center"><span class="badge <?= $badge ?>">H-<?= $r->sisa_hari ?></span></td>
                                
                                <?php if(isAdmin()): ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-info btn-ingatkan-cc"
                                            data-nama="<?= $r->nama ?>" 
                                            data-jenis="<?= $r->jenis ?>"
                                            data-info="<?= $r->info_tahun ?>"
                                            data-tgl="<?= date('d M Y', strtotime($r->tgl_event)) ?>">
                                            Ingatkan CC
                                        </button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery !== 'undefined') {
        (function($) {
            
            $('#dt-reminder').DataTable({ "ordering": false });

            // AJAX Massal (Seluruh Data)
            $('#btn-cek-massal').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Kirim Semua Reminder?',
                    text: "Sistem akan mengirim seluruh daftar yang ada di tabel ke Athala Aqsha.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Ya, Kirim Semua'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Munculkan Loading
                        Swal.fire({
                            title: 'Sedang Memproses...',
                            text: 'Harap tunggu, sedang menyusun daftar dan mengirim WA.',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading() }
                        });

                        $.ajax({
                            url: "<?= base_url('reminder/kirim_notif_massal_ajax') ?>",
                            type: "POST",
                            data: { "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>" },
                            dataType: "JSON",
                            success: function(res) {
                                // Tutup loading dan tampilkan pesan sukses/gagal
                                if (res.status) {
                                    Swal.fire('Berhasil!', res.msg, 'success');
                                } else {
                                    Swal.fire('Info', res.msg, 'info');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', 'Terjadi kesalahan pada server atau koneksi internet.', 'error');
                                console.log(xhr.responseText);
                            }
                        });
                    }
                });
            });

            // AJAX Single (Per Baris)
            $(document).on('click', '.btn-ingatkan-cc', function(e) {
                e.preventDefault();
                var btn = $(this);
                var d = btn.data();
                
                Swal.fire({
                    title: 'Kirim Reminder?',
                    html: "Kirim data <b>" + d.nama + "</b> ke Athala Aqsha?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({title: 'Mengirim...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() }});
                        
                        $.post("<?= base_url('reminder/kirim_single_notif_cc') ?>", { 
                            nama: d.nama,
                            jenis: d.jenis,
                            tgl: d.tgl,
                            info: d.info, // Pastikan info_tahun dikirim
                            "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>" 
                        }, function(res) {
                            if(res.status) {
                                Swal.fire('Terkirim', 'Pesan berhasil dikirim.', 'success');
                            } else {
                                Swal.fire('Gagal', 'Pesan gagal dikirim.', 'error');
                            }
                        }, 'json');
                    }
                });
            });

        })(jQuery);
    }
});
</script>