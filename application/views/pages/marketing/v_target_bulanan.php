<header class="page-header">
    <h2><i class="fas fa-sliders-h"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    .premium-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        margin-bottom: 25px;
    }
    .form-control-premium {
        border-radius: 8px;
        border: 1px solid #D1D5DB;
        padding: 8px 12px;
    }
    .custom-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .custom-table th {
        background-color: #F3F4F6;
        color: #374151;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        border-bottom: 2px solid #E5E7EB;
        padding: 10px 8px;
        text-align: center;
    }
    .custom-table td {
        padding: 10px 8px;
        vertical-align: middle;
        border-bottom: 1px solid #E5E7EB;
        color: #1F2937;
        font-size: 0.85rem;
    }
    .modal-content-premium {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    }
    .modal-header-premium {
        background: #1F2937;
        color: #ffffff;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
        padding: 16px 24px;
    }
    .modal-header-premium .close {
        color: #ffffff;
        opacity: 0.8;
    }
    .modal-footer-premium {
        border-top: 1px solid #F3F4F6;
        padding: 16px 24px;
    }
    .btn-premium {
        border-radius: 8px;
        font-weight: 600;
        padding: 8px 16px;
        transition: all 0.2s;
    }
    .btn-premium-success {
        background-color: #10B981;
        color: white;
        border: none;
    }
    .btn-premium-success:hover {
        background-color: #059669;
        color: white;
        transform: translateY(-1px);
    }
    .btn-premium-primary {
        background-color: #2563EB;
        color: white;
        border: none;
    }
    .btn-premium-primary:hover {
        background-color: #1D4ED8;
        color: white;
        transform: translateY(-1px);
    }
</style>

<div class="row">
    <div class="col-md-12">
        <!-- Year Filter Card -->
        <div class="card premium-card">
            <div class="card-body pb-2">
                <?= form_open('marketing_target/target_bulanan', ['method' => 'GET', 'class' => 'row align-items-center']); ?>
                <div class="col-md-4 mb-2">
                    <label class="font-weight-bold text-muted small text-uppercase">Pilih Tahun</label>
                    <select name="tahun" id="filter_tahun" class="form-control form-control-premium" onchange="this.form.submit()">
                        <?php
                        $current_year = date('Y');
                        for ($y = $current_year - 5; $y <= $current_year + 5; $y++) {
                            $selected = ($y == $tahun) ? 'selected' : '';
                            echo "<option value='{$y}' {$selected}>Tahun: {$y}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-8 mb-2 text-right pt-4">
                    <button type="submit" class="btn btn-premium btn-premium-primary"><i class="fas fa-search"></i> Tampilkan</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>

        <!-- Marketing Targets Table Card -->
        <div class="card premium-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table custom-table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 3%">No</th>
                                <th style="width: 15%">Nama Marketing</th>
                                <th style="width: 6%">Jan</th>
                                <th style="width: 6%">Feb</th>
                                <th style="width: 6%">Mar</th>
                                <th style="width: 6%">Apr</th>
                                <th style="width: 6%">Mei</th>
                                <th style="width: 6%">Jun</th>
                                <th style="width: 6%">Jul</th>
                                <th style="width: 6%">Ags</th>
                                <th style="width: 6%">Sep</th>
                                <th style="width: 6%">Okt</th>
                                <th style="width: 6%">Nov</th>
                                <th style="width: 6%">Des</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($marketing_list)): ?>
                                <?php $no = 1; foreach ($marketing_list as $m): ?>
                                    <?php $m_id = $m->pengguna_id; ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="font-weight-bold text-dark"><?= htmlspecialchars($m->nama) ?></td>
                                        <?php for ($b = 1; $b <= 12; $b++): ?>
                                            <?php $val = isset($targets[$m_id][$b]) ? $targets[$m_id][$b] : 0; ?>
                                            <td class="text-right">
                                                <?= $val > 0 ? rupiah($val) : '<span class="text-muted">-</span>' ?>
                                            </td>
                                        <?php endfor; ?>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary btn-set-target" 
                                                    data-marketing-id="<?= $m_id ?>" 
                                                    data-marketing-name="<?= htmlspecialchars($m->nama) ?>"
                                                    title="Set Target Bulanan">
                                                <i class="fas fa-cog"></i> Set Target
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="15" class="text-center py-4 text-muted">Tidak ada data marketing ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Target Bulanan -->
<div id="modal-set-target" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-premium">
            <div class="modal-header modal-header-premium">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-cog"></i> Konfigurasi Target Bulanan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('', ['id' => 'form-target-bulanan', 'autocomplete' => 'off']); ?>
            <input type="hidden" name="marketing_id" id="modal_marketing_id">
            <input type="hidden" name="tahun" value="<?= $tahun ?>">
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="font-weight-bold text-muted small text-uppercase">Tahun</label>
                        <p class="h4 font-weight-bold text-dark m-0"><?= $tahun ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="font-weight-bold text-muted small text-uppercase">Marketing</label>
                        <p class="h4 font-weight-bold text-primary m-0" id="modal_marketing_name">-</p>
                    </div>
                </div>

                <hr class="my-3">

                <div class="row">
                    <?php
                    $months = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    foreach ($months as $num => $name): ?>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small text-uppercase"><?= $name ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text py-0 px-2 font-weight-bold" style="font-size: 0.8rem; background-color: #F3F4F6;">Rp</span>
                                </div>
                                <input type="text" 
                                       name="target_nominal[<?= $num ?>]" 
                                       id="target_bulan_<?= $num ?>" 
                                       class="form-control form-control-premium format-rupiah" 
                                       placeholder="0">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer modal-footer-premium text-right">
                <button type="button" class="btn btn-secondary btn-premium" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-premium btn-premium-success">Simpan Target</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Rupiah formattings
        $(document).on('keyup', '.format-rupiah', function() {
            let val = $(this).val();
            let clean = val.replace(/\D/g, "");
            let formatted = clean.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(formatted);
        });

        // Set Target Button Click
        $('.btn-set-target').click(function() {
            let m_id = $(this).data('marketing-id');
            let m_name = $(this).data('marketing-name');
            let tahun = '<?= $tahun ?>';

            $('#modal_marketing_id').val(m_id);
            $('#modal_marketing_name').text(m_name);

            // Fetch current values via AJAX
            $.ajax({
                url: '<?= base_url('marketing_target/get_target_bulanan_json/') ?>' + m_id + '/' + tahun,
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    for (let b = 1; b <= 12; b++) {
                        let val = data[b] ? Math.round(data[b]) : 0;
                        if (val > 0) {
                            let formatted = String(val).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            $('#target_bulan_' + b).val(formatted);
                        } else {
                            $('#target_bulan_' + b).val('');
                        }
                    }
                    $('#modal-set-target').modal('show');
                }
            });
        });

        // Save Target Bulanan Form
        $('#form-target-bulanan').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '<?= base_url('marketing_target/save_all_target_bulanan') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'JSON',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#modal-set-target').modal('hide');
                        Swal.fire('Berhasil!', res.message, 'success').then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
                }
            });
        });
    });
</script>
