<header class="page-header">
    <h2><i class="icons fas fa-building"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>


<div class="col-xl-8 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:10%">


        <div class="text-center mt-0">
            <h3><b>Detail Data Aset Perusahaan</b></h3>
        </div><br>

        <?php function rowItem($label, $value)
        { ?>
            <div style="display: flex; gap: 8px;" class="mb-2">
                <div style="min-width: 170px;"><strong style="color: #000;"><?= $label ?></strong></div>
                <div style="color: #000;">: <?= $value ?></div>
            </div>
        <?php } ?>

        <?php

        if ($data_aset->nilai !== null && $data_aset->nilai !== '') {
            $nilai_real = 'Rp ' . number_format($data_aset->nilai, 0, ',', '.');
            $has_permission = (sessPenggunaId() == 58 || sessPenggunaId() == 29 || sessPenggunaId() == 107 || sessPenggunaId() == 54);
            $state = $has_permission ? 'real' : 'masked';
            $display_text = $has_permission ? $nilai_real : 'Rp ******';
            $icon = $has_permission ? 'fa-eye-slash' : 'fa-eye';

            $nilai = '<span class="asset-value-container" data-real="' . $nilai_real . '" data-masked="Rp ******" data-state="' . $state . '">
                        <span class="val-text">' . $display_text . '</span> 
                        <a href="javascript:;" class="toggle-value text-primary ml-1" title="Tampilkan/Sembunyikan"><i class="fas ' . $icon . '"></i></a>
                      </span>';
        } else {
            $nilai = '-';
        }

        rowItem('Kategori', $data_aset->kategori ?? '-');
        rowItem('Kode', $data_aset->kode ?? '-');
        rowItem('Nama', $data_aset->nama ?? '-');
        rowItem('Nilai', $nilai);
        rowItem('Tanggal Pembelian', !empty($data_aset->tgl_pembelian) ? date('d-m-Y', strtotime($data_aset->tgl_pembelian)) : '-');
        rowItem('Link Invoice', !empty($data_aset->link_pembelian) ? '<a href="' . $data_aset->link_pembelian . '" target="_blank"><i class="fas fa-link me-1"></i> Lihat</a>' : '-');
        rowItem('Link Foto', !empty($data_aset->link_foto) ? '<a href="' . $data_aset->link_foto . '" target="_blank"><i class="fas fa-link me-1"></i> Lihat</a>' : '-');
        rowItem('Keterangan', $data_aset->keterangan ?? '-');
        rowItem('Posisi', $data_aset->posisi ?? '-');
        rowItem('Dijual', $data_aset->dijual == 2 ? 'Sudah' : 'Belum');
        rowItem('Tanggal Dijual', !empty($data_aset->tgl_dijual) ? date('d-m-Y', strtotime($data_aset->tgl_dijual)) : '-');
        ?>








        <!-- end value preview -->
        <br>
        <div class="row-action-buttons">
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" data-dismiss="modal">Kembali</button>
        </div>

        <br>
        <br>

        <div class="row mb-3">
            <div class="col-md-12 offset-md-0">
                <div class="d-flex justify-content-between align-items-center" style="margin-left: 1.2rem; margin-right: 1.2rem;">
                    <h4><b>Histrory Aset Ini</b></h4>
                    <div class="btn-group">
                        <button type="button" id="btn-show-riwayat-it" class="btn btn-sm btn-primary">
                            <i class="fas fa-tools"></i>&nbsp;Riwayat Tiket IT
                        </button>
                        <?php if (sessPenggunaId() == 1 || sessPenggunaId() == 58 || sessPenggunaId() == 29) { ?>
                            <button type="button" id="btn-add-history" class="btn btn-sm btn-success ml-2">
                                <i class="icons icon-plus"></i>&nbsp;Tambah History
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 offset-md-0">
                <ul class="timeline-3">
                    <?php
                    foreach ($data_update as $each) {
                    ?>
                        <li>
                            <div class="timeline-item-content border p-3 rounded" style="background-color: #f8f9fa;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1 text-dark" style="font-size: 1.1rem;"><strong>Nama : <?= htmlspecialchars($each->nama_pembuat) ?></strong></h5>
                                        <span class="badge badge-info mb-2">Jabatan : <?= htmlspecialchars($each->jabatan) ?></span>
                                    </div>
                                    <div class="text-right">
                                        <small class="text-muted d-block mb-2">
                                            <i class="far fa-calendar-alt"></i> Tanggal : <?= date('d-M-Y | H:i:s', strtotime($each->tanggal)) ?>
                                        </small>
                                        <?php if (sessPenggunaId() == 1 || sessPenggunaId() == 58 || sessPenggunaId() == 29) { ?>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-primary btn-edit-history" data-id="<?= encrypt($each->id) ?>" title="Edit History">
                                                    <i class="bx bx-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="<?= encrypt($each->id) ?>" data-object="aset/deleteAsetLog" title="Hapus History">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <?php if ($each->id_sta != "") { ?>
                                    <div class="mt-2">
                                        <a href="surat_new/show/detail/sta/<?= $each->id_sta ?>" target="_blank" class="btn btn-xs btn-outline-primary" style="margin-left:0px; padding: 2px 8px; font-size: 0.8rem;">
                                            <i class="fas fa-info-circle"></i>&nbsp;&nbsp;Serah Terima Aset
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <link rel="stylesheet" href="assets/css/timeline.css">
    </div>
</div>

<!-- Modal History -->
<div id="modal-history" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-history-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form History Aset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-history-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group text-left">
                        <label for="id_pengguna" class="form-control-label">PIC / Pengguna <span class="text-danger">*</span> :</label>
                        <select data-plugin-selectTwo class="form-control select2-local" id="id_pengguna" name="id_pengguna" required>
                            <option value="">— Pilih Pengguna —</option>
                            <?php foreach ($list_pengguna as $p) { ?>
                                <option value="<?= $p->pengguna_id ?>"><?= htmlspecialchars($p->nama) ?> (<?= htmlspecialchars($p->jabatan) ?>)</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group text-left">
                        <label for="tanggal" class="form-control-label">Tanggal <span class="text-danger">*</span> :</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="text" class="form-control" id="tanggal" name="tanggal" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy", "todayBtn": "linked", "todayHighlight": true, "autoclose": true}' required>
                        </div>
                    </div>
                    <div class="form-group text-left">
                        <label for="id_sta" class="form-control-label">Serah Terima Aset (STA) (Optional) :</label>
                        <select data-plugin-selectTwo class="form-control select2-local" id="id_sta" name="id_sta">
                            <option value="">— Pilih Serah Terima Aset (STA) —</option>
                            <?php foreach ($list_sta as $sta) { ?>
                                <option value="<?= $sta->id_serah ?>"><?= htmlspecialchars($sta->kode) ?> (<?= date('d-m-Y', strtotime($sta->tanggal)) ?>)</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="id_history" name="id_history">
                <input type="hidden" id="id_aset" name="id_aset" value="<?= encrypt($data_aset->id) ?>">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Riwayat Tiket IT -->
<div id="modal-riwayat-it" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius:12px; border-top-right-radius:12px;">
                <h5 class="modal-title font-weight-bold text-white"><i class="fas fa-tools"></i> Riwayat Perbaikan & Tiket IT Perangkat</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div id="riwayat-tiket-modal-body">
                    <div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Memuat riwayat perbaikan...</div>
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-bottom-left-radius:12px; border-bottom-right-radius:12px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show IT Ticket History Modal
        $('#btn-show-riwayat-it').click(function() {
            $('#modal-riwayat-it').modal('show');
            $('#riwayat-tiket-modal-body').html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Memuat riwayat perbaikan...</div>');
            $.ajax({
                url: '<?= base_url("it_maintenance/get_asset_ticket_history") ?>',
                type: 'POST',
                data: {
                    id_aset: '<?= $data_aset->id ?>'
                },
                success: function(html) {
                    $('#riwayat-tiket-modal-body').html(html);
                },
                error: function() {
                    $('#riwayat-tiket-modal-body').html('<div class="alert alert-danger" style="font-size:13px;">Gagal memuat riwayat perbaikan.</div>');
                }
            });
        });
        // Initialize datepicker and select2 for elements inside modal
        if ($.isFunction($.fn['themePluginSelect2'])) {
            $('[data-plugin-selectTwo]').themePluginSelect2({
                width: '100%',
                dropdownParent: $('#modal-history')
            });
        }

        // Add history click handler
        $('#btn-add-history').click(function() {
            $('#modal-history-form').attr('action', 'aset/addAsetLog');
            $('#id_history').val('');
            $('#id_pengguna').val('').trigger('change');
            $('#tanggal').val('');
            $('#id_sta').val('');
            $('#modal-history').modal('show');
        });

        // Edit history click handler
        $(document).on('click', '.btn-edit-history', function() {
            var id = $(this).data('id');
            $('#modal-history-form').attr('action', 'aset/updateAsetLog');

            fetch('aset/editAsetLog/' + id)
                .then(function(resp) {
                    return resp.json();
                })
                .then(function(data) {
                    if (data && data.length > 0) {
                        $('#id_history').val(data[0].id);
                        $('#id_aset').val(data[0].id_aset);
                        $('#id_pengguna').val(data[0].id_pengguna).trigger('change');
                        $('#tanggal').val(data[0].tanggal);
                        $('#id_sta').val(data[0].id_sta);
                        $('#modal-history').modal('show');
                    }
                })
                .catch(function(err) {
                    console.error('Error fetching history:', err);
                });
        });
    });

    function goBack() {
        window.history.back();
    }
</script>