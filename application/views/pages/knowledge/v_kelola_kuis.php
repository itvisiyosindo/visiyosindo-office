<header class="page-header">
    <h2><i class="fas fa-cog mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('knowledge/show/knowledge') ?>">Product Knowledge</a></li>
            <li><span>Kelola Soal & Rekap</span></li>
        </ol>
    </div>
</header>

<div class="mb-3 d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
    <div>
        <a href="<?= base_url('knowledge/show/knowledge') ?>" class="btn btn-sm btn-light border font-weight-bold">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Kuis
        </a>
    </div>
    <div>
        <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 13px;">
            <i class="fas fa-check-circle mr-1"></i> Status: <?= strtoupper($kuis->status) ?>
        </span>
    </div>
</div>

<!-- KUIS INFO CARD -->
<div class="card mb-4 shadow-sm" style="border-radius: 12px; border: 1px solid #e2e8f0;">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="text-muted small text-uppercase font-weight-bold mb-1">
                    <i class="far fa-calendar-alt text-primary mr-1"></i> Periode: Bulan <?= (int)$kuis->bulan ?> / Tahun <?= (int)$kuis->tahun ?> | Durasi: <?= (int)$kuis->durasi_menit ?> Menit
                </div>
                <h4 class="font-weight-bold text-dark mb-1"><?= htmlspecialchars($kuis->judul_kuis) ?></h4>
                <p class="text-muted mb-2 small"><?= !empty($kuis->deskripsi) ? htmlspecialchars($kuis->deskripsi) : 'Tidak ada petunjuk khusus.' ?></p>
                <?php if (!empty($kuis->waktu_buka) || !empty($kuis->waktu_tutup)): ?>
                    <div class="p-2 rounded bg-light border text-muted small d-inline-block" style="font-size: 11.5px;">
                        <i class="far fa-clock text-info mr-1"></i> <strong>Jadwal Pelaksanaan Kuis:</strong> 
                        <?= !empty($kuis->waktu_buka) ? date('d M Y H:i', strtotime($kuis->waktu_buka)) : 'Sekarang' ?> s/d 
                        <?= !empty($kuis->waktu_tutup) ? date('d M Y H:i', strtotime($kuis->waktu_tutup)) : 'Seterusnya' ?> WIB
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <button type="button" class="btn btn-success font-weight-bold shadow-sm" id="btnBukaModalSoal">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Soal Baru
                </button>
            </div>
        </div>
    </div>
</div>

<!-- TABS: BANK SOAL vs REKAP PESERTA -->
<div class="tabs">
    <ul class="nav nav-tabs" style="border-bottom: 2px solid #e2e8f0;">
        <li class="active">
            <a href="#tab_bank_soal" data-toggle="tab" class="font-weight-bold">
                <i class="fas fa-list-ol mr-1 text-primary"></i> Bank Soal Kuis (<?= count($soal_list) ?>)
            </a>
        </li>
        <li>
            <a href="#tab_rekap_peserta" data-toggle="tab" class="font-weight-bold">
                <i class="fas fa-users mr-1 text-success"></i> Rekap Peserta & Nilai (<?= count($peserta_list) ?>)
            </a>
        </li>
    </ul>

    <div class="tab-content pt-3" style="background:transparent; border:none; padding-left:0; padding-right:0;">
        <!-- TAB 1: DAFTAR SOAL -->
        <div id="tab_bank_soal" class="tab-pane active">
            <?php if (empty($soal_list)): ?>
                <div class="p-5 text-center bg-white rounded border" style="border-radius: 12px;">
                    <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                    <h5 class="font-weight-bold text-dark">Belum Ada Soal pada Kuis Ini</h5>
                    <p class="text-muted small">Klik tombol <strong>"Tambah Soal Baru"</strong> untuk menginput pertanyaan pilihan ganda beserta kunci jawabannya.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php $noSoal = 1; foreach ($soal_list as $soal): 
                        $kunci = strtoupper($soal->kunci_jawaban);
                    ?>
                        <div class="col-12 mb-3">
                            <div class="card shadow-sm" style="border-radius: 10px; border: 1px solid #e2e8f0;">
                                <div class="card-header d-flex justify-content-between align-items-center py-2 px-3" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                    <span class="font-weight-bold text-dark">
                                        <i class="fas fa-question-circle text-primary mr-1"></i> Nomor <?= $noSoal++ ?>
                                    </span>
                                    <div>
                                        <span class="badge badge-success px-2 py-1 mr-2" style="font-size: 11px;">
                                            Kunci: Pilihan <?= $kunci ?>
                                        </span>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-edit-soal"
                                            data-id="<?= $soal->id_soal ?>"
                                            data-urut="<?= $soal->nomor_urut ?>"
                                            data-pertanyaan="<?= htmlspecialchars($soal->pertanyaan, ENT_QUOTES) ?>"
                                            data-a="<?= htmlspecialchars($soal->pilihan_a, ENT_QUOTES) ?>"
                                            data-b="<?= htmlspecialchars($soal->pilihan_b, ENT_QUOTES) ?>"
                                            data-c="<?= htmlspecialchars($soal->pilihan_c, ENT_QUOTES) ?>"
                                            data-d="<?= htmlspecialchars($soal->pilihan_d, ENT_QUOTES) ?>"
                                            data-kunci="<?= $soal->kunci_jawaban ?>"
                                            data-pembahasan="<?= htmlspecialchars($soal->pembahasan ?? '', ENT_QUOTES) ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-xs btn-outline-danger btn-hapus-soal" data-id="<?= $soal->id_soal ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="font-weight-semibold text-dark mb-3" style="font-size: 14px; line-height: 1.5; white-space: pre-line;">
                                        <?= htmlspecialchars($soal->pertanyaan) ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="p-2 rounded border <?= $kunci === 'A' ? 'border-success bg-light-success font-weight-bold text-success' : 'bg-light' ?>" style="font-size: 13px;">
                                                <strong>A.</strong> <?= htmlspecialchars($soal->pilihan_a) ?>
                                                <?= $kunci === 'A' ? ' <i class="fas fa-check-circle text-success ml-1"></i>' : '' ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="p-2 rounded border <?= $kunci === 'B' ? 'border-success bg-light-success font-weight-bold text-success' : 'bg-light' ?>" style="font-size: 13px;">
                                                <strong>B.</strong> <?= htmlspecialchars($soal->pilihan_b) ?>
                                                <?= $kunci === 'B' ? ' <i class="fas fa-check-circle text-success ml-1"></i>' : '' ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="p-2 rounded border <?= $kunci === 'C' ? 'border-success bg-light-success font-weight-bold text-success' : 'bg-light' ?>" style="font-size: 13px;">
                                                <strong>C.</strong> <?= htmlspecialchars($soal->pilihan_c) ?>
                                                <?= $kunci === 'C' ? ' <i class="fas fa-check-circle text-success ml-1"></i>' : '' ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="p-2 rounded border <?= $kunci === 'D' ? 'border-success bg-light-success font-weight-bold text-success' : 'bg-light' ?>" style="font-size: 13px;">
                                                <strong>D.</strong> <?= htmlspecialchars($soal->pilihan_d) ?>
                                                <?= $kunci === 'D' ? ' <i class="fas fa-check-circle text-success ml-1"></i>' : '' ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!empty($soal->pembahasan)): ?>
                                        <div class="mt-2 p-2 rounded small text-muted" style="background:#f1f5f9; border-left: 3px solid #64748b;">
                                            <strong><i class="fas fa-info-circle mr-1"></i> Pembahasan:</strong> <?= htmlspecialchars($soal->pembahasan) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- TAB 2: REKAP PESERTA -->
        <div id="tab_rekap_peserta" class="tab-pane">
            <div class="card shadow-sm p-3" style="border-radius: 10px; border: 1px solid #e2e8f0;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold text-dark mb-0">Daftar Hasil Ujian Karyawan</h5>
                    <span class="text-muted small">Total Peserta Selesai: <strong><?= count($peserta_list) ?> Karyawan</strong></span>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="tablePesertaKuis">
                        <thead class="text-center" style="background:#f8fafc;">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="text-align:left;">Nama Karyawan</th>
                                <th>Divisi / Jabatan</th>
                                <th>Waktu Selesai</th>
                                <th>Durasi</th>
                                <th>Benar</th>
                                <th>Salah</th>
                                <th>Skor Akhir (0-100)</th>
                                <th>Grade</th>
                                <th style="width: 90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php if (empty($peserta_list)): ?>
                                <tr>
                                    <td colspan="10" class="text-muted py-4">Belum ada karyawan yang menyelesaikan kuis ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php $noP = 1; foreach ($peserta_list as $ps): 
                                    $skor = (float)$ps->skor_akhir;
                                    $grade = ($skor >= 85) ? 'A' : (($skor >= 75) ? 'B' : (($skor >= 65) ? 'C' : 'D'));
                                    $gradeBadge = ($skor >= 85) ? 'badge-success' : (($skor >= 75) ? 'badge-primary' : (($skor >= 65) ? 'badge-warning' : 'badge-danger'));
                                ?>
                                    <tr>
                                        <td><?= $noP++ ?></td>
                                        <td style="text-align:left;" class="font-weight-bold text-dark"><?= htmlspecialchars($ps->nama) ?></td>
                                        <td><?= htmlspecialchars($ps->nama_divisi ?: '-') ?> <small class="text-muted d-block"><?= htmlspecialchars($ps->jabatan ?: '') ?></small></td>
                                        <td><?= date('d-m-Y H:i', strtotime($ps->waktu_selesai)) ?></td>
                                        <td><?= round((int)$ps->durasi_detik / 60) ?> Menit</td>
                                        <td class="text-success font-weight-bold"><?= (int)$ps->total_benar ?></td>
                                        <td class="text-danger font-weight-bold"><?= (int)$ps->total_salah ?></td>
                                        <td style="font-size: 15px; font-weight: 800; color: #059669;"><?= $skor ?></td>
                                        <td><span class="badge <?= $gradeBadge ?> px-2 py-1 font-weight-bold">Grade <?= $grade ?></span></td>
                                        <td>
                                            <a href="<?= base_url('knowledge/show/hasil/' . $kuis->id_kuis . '/' . $ps->id_pengguna) ?>" target="_blank" class="btn btn-xs btn-primary font-weight-bold shadow-sm" title="Lihat Lembar Jawaban Peserta">
                                                <i class="fas fa-eye mr-1"></i> Review
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL TAMBAH / EDIT SOAL PILIHAN GANDA
     ========================================================================= -->
<div class="modal fade" id="modalSoal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <form id="formSoal">
                <input type="hidden" name="id_kuis" value="<?= $kuis->id_kuis ?>">
                <input type="hidden" name="id_soal" id="modal_id_soal" value="">
                
                <div class="modal-header d-flex align-items-center justify-content-between" style="background:#ffffff; border-bottom: 1px solid #e2e8f0; padding: 18px 24px;">
                    <div class="d-flex align-items-center">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #ecfdf5; border: 1.5px solid #a7f3d0; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #059669; margin-right: 12px;">
                            <i class="fas fa-question"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold mb-0" id="labelModalSoal" style="color: #0f172a;">Tambah Soal Pilihan Ganda</h5>
                            <small class="text-muted">Input pertanyaan, opsi A, B, C, D dan kunci jawaban benar</small>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" style="font-size: 24px; opacity: 0.6;">&times;</button>
                </div>

                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="font-weight-bold text-dark small text-uppercase">Pertanyaan Soal <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center" style="gap: 5px;">
                                <small class="text-muted font-weight-bold">Nomor Urut:</small>
                                <input type="number" name="nomor_urut" id="modal_nomor_urut" class="form-control form-control-sm text-center" style="width: 70px;" value="<?= count($soal_list) + 1 ?>">
                            </div>
                        </div>
                        <textarea name="pertanyaan" id="modal_pertanyaan" rows="3" class="form-control" required placeholder="Tuliskan pertanyaan ujian di sini..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark small text-uppercase"><span class="badge badge-primary mr-1">Opsi A</span> Pilihan Jawaban A <span class="text-danger">*</span></label>
                            <textarea name="pilihan_a" id="modal_pilihan_a" rows="2" class="form-control" required placeholder="Jawaban A..."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark small text-uppercase"><span class="badge badge-primary mr-1">Opsi B</span> Pilihan Jawaban B <span class="text-danger">*</span></label>
                            <textarea name="pilihan_b" id="modal_pilihan_b" rows="2" class="form-control" required placeholder="Jawaban B..."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark small text-uppercase"><span class="badge badge-primary mr-1">Opsi C</span> Pilihan Jawaban C <span class="text-danger">*</span></label>
                            <textarea name="pilihan_c" id="modal_pilihan_c" rows="2" class="form-control" required placeholder="Jawaban C..."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark small text-uppercase"><span class="badge badge-primary mr-1">Opsi D</span> Pilihan Jawaban D <span class="text-danger">*</span></label>
                            <textarea name="pilihan_d" id="modal_pilihan_d" rows="2" class="form-control" required placeholder="Jawaban D..."></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-success small text-uppercase"><i class="fas fa-key mr-1"></i> Kunci Jawaban Benar <span class="text-danger">*</span></label>
                            <select name="kunci_jawaban" id="modal_kunci_jawaban" class="form-control font-weight-bold border-success">
                                <option value="A">Pilihan A</option>
                                <option value="B">Pilihan B</option>
                                <option value="C">Pilihan C</option>
                                <option value="D">Pilihan D</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted small text-uppercase">Pembahasan Singkat (Opsional)</label>
                            <textarea name="pembahasan" id="modal_pembahasan" rows="1" class="form-control" placeholder="Penjelasan jawaban benar..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 d-flex justify-content-between align-items-center" style="background:#f8fafc; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-light px-4 py-2 font-weight-bold" data-dismiss="modal" style="border: 1px solid #cbd5e1;">Batal</button>
                    <button type="submit" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm" id="btnSubmitSoal">
                        <i class="fas fa-check mr-1"></i> Simpan Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var baseUrl = '<?= base_url() ?>';

    // Buka Modal Tambah Soal
    $('#btnBukaModalSoal').on('click', function() {
        $('#formSoal')[0].reset();
        $('#modal_id_soal').val('');
        $('#labelModalSoal').text('Tambah Soal Pilihan Ganda');
        $('#modalSoal').modal('show');
    });

    // Edit Soal
    $(document).on('click', '.btn-edit-soal', function() {
        var d = $(this).data();
        $('#modal_id_soal').val(d.id);
        $('#modal_nomor_urut').val(d.urut);
        $('#modal_pertanyaan').val(d.pertanyaan);
        $('#modal_pilihan_a').val(d.a);
        $('#modal_pilihan_b').val(d.b);
        $('#modal_pilihan_c').val(d.c);
        $('#modal_pilihan_d').val(d.d);
        $('#modal_kunci_jawaban').val(d.kunci);
        $('#modal_pembahasan').val(d.pembahasan);
        $('#labelModalSoal').text('Edit Soal Nomor ' + d.urut);
        $('#modalSoal').modal('show');
    });

    // Simpan Soal
    $('#formSoal').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btnSubmitSoal');
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

        $.ajax({
            url: baseUrl + 'knowledge/ajax_simpan_soal',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(origHtml);
                if (res && res.status === 'success') {
                    location.reload();
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal menyimpan soal.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(origHtml);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Hapus Soal
    $(document).on('click', '.btn-hapus-soal', function() {
        var id = $(this).data('id');
        if (!confirm('Hapus soal ini dari paket kuis?')) return;

        $.ajax({
            url: baseUrl + 'knowledge/ajax_hapus_soal',
            type: 'POST',
            data: { id_soal: id },
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    location.reload();
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal menghapus soal.');
                }
            }
        });
    });
});
</script>
