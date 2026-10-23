<header class="page-header">
    <h2><i class="icons icon-calendar"></i>&nbsp;<?= $page_title ?></h2>
</header>

<div class="row">
    <div class="col-md-12 text-right mb-3">
        <?php if(isAdmin() || isHrd()): ?>
            <button type="button" class="btn btn-sm btn-primary mr-2" id="btn-ucapan-senin">
                <i class="fas fa-sun mr-1"></i> Kirim Semangat Senin (Senin 09:00)
            </button>
            <button type="button" class="btn btn-sm btn-info mr-2" id="btn-ucapan-weekend">
                <i class="fas fa-coffee mr-1"></i> Kirim Ucapan Weekend (Jumat 17:30)
            </button>
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

            // Handler Semangat Senin
            $("#btn-ucapan-senin").on("click", function() {
                Swal.fire({
                    title: "Kirim Semangat Senin?",
                    html: "Kirim pesan penyemangat awal pekan ke seluruh karyawan aktif sekarang?<br><small class='text-muted'>Pesan bertanda tangan: <b>_Sistem Office PT Visi Yosindo Medikal_</b></small>",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Kirim Sekarang",
                    cancelButtonText: "Batal",
                    confirmButtonColor: "#3085d6"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({title: "Sedang Mengirim Pesan...", allowOutsideClick: false, didOpen: () => { Swal.showLoading() }});
                        
                        $.get("<?= base_url('reminder/cron_monday_greeting/vysi_medikal_2026?force=1') ?>", function(res) {
                            if(res.status) {
                                Swal.fire("Selesai!", res.msg, "success");
                            } else {
                                Swal.fire("Info", res.msg || "Gagal mengirim pesan.", "warning");
                            }
                        }, "json").fail(function() {
                            Swal.fire("Error", "Terjadi kesalahan jaringan.", "error");
                        });
                    }
                });
            });

            // Handler Ucapan Weekend
            $("#btn-ucapan-weekend").on("click", function() {
                Swal.fire({
                    title: "Kirim Ucapan Weekend?",
                    html: "Kirim pesan ucapan selamat berakhir pekan ke seluruh karyawan aktif sekarang?<br><small class='text-muted'>Pesan bertanda tangan: <b>_Sistem Office PT Visi Yosindo Medikal_</b></small>",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Kirim Sekarang",
                    cancelButtonText: "Batal",
                    confirmButtonColor: "#0284c7"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({title: "Sedang Mengirim Ucapan...", allowOutsideClick: false, didOpen: () => { Swal.showLoading() }});
                        
                        $.get("<?= base_url('reminder/cron_weekend_greeting/vysi_medikal_2026?force=1') ?>", function(res) {
                            if(res.status) {
                                Swal.fire("Selesai!", res.msg, "success");
                            } else {
                                Swal.fire("Info", res.msg || "Gagal mengirim ucapan.", "warning");
                            }
                        }, "json").fail(function() {
                            Swal.fire("Error", "Terjadi kesalahan jaringan.", "error");
                        });
                    }
                });
            });

        })(jQuery);
    }
});
</script>