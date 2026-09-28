<header class="page-header">
  <h2><i class="icons fas fa-database"></i>&nbsp;<?= $page_title ?></h2>
  <div class="right-wrapper text-left">
    <ol class="breadcrumbs">
      <li><span><?= $page_desc ?></span></li>
    </ol>
  </div>
</header>

<style>
  /* --- MODERN DASHBOARD VARIABLES --- */
  :root {
    --primary-soft: #e3f2fd;
    --primary-dark: #1565c0;
    --success-soft: #e8f5e9;
    --text-label: #8898aa;
    --text-dark: #32325d;
    --card-radius: 10px;
  }

  /* Utility Classes */
  .text-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--text-label);
    font-weight: 700;
    display: block;
    margin-bottom: 4px;
  }

  .text-value {
    font-size: 0.9rem;
    color: var(--text-dark);
    font-weight: 600;
    line-height: 1.4;
  }

  .text-value-lg {
    font-size: 1.35rem;
    color: var(--primary-dark);
    font-weight: 800;
  }

  /* Card Styling */
  .card-modern {
    border: 0;
    border-radius: var(--card-radius);
    box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
    background: #fff;
    margin-bottom: 24px;
    transition: transform 0.2s;
    overflow: hidden;
  }

  .card-header-modern {
    background: #fff;
    border-bottom: 1px solid #f6f9fc;
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .card-header-modern h6 {
    margin: 0;
    font-weight: 700;
    color: #32325d;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* Sticky Form Sidebar */
  .sticky-sidebar {
    position: sticky;
    top: 20px;
    z-index: 99;
  }

  /* Section Box inside Card */
  .info-box {
    background-color: #f6f9fc;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #e9ecef;
  }

  /* Approval Timeline */
  .approval-track {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 25px 0 10px;
  }

  .approval-track::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e9ecef;
    z-index: 0;
  }

  .approval-step {
    position: relative;
    z-index: 1;
    text-align: center;
    background: #fff;
    padding: 0 10px;
  }

  .approval-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #e9ecef;
    color: #adb5bd;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    transition: all 0.3s;
    font-size: 0.8rem;
  }

  .approval-step.active .approval-icon {
    border-color: #3498db;
    background: #3498db;
    color: #fff;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
  }

  .approval-step.completed .approval-icon {
    border-color: #2ecc71;
    background: #2ecc71;
    color: #fff;
  }

  .approval-label {
    font-size: 0.65rem;
    font-weight: bold;
    color: #8898aa;
    text-transform: uppercase;
  }

  .approval-step.active .approval-label {
    color: #3498db;
  }

  .approval-step.completed .approval-label {
    color: #2ecc71;
  }

  /* Vertical Timeline */
  .vertical-timeline {
    list-style: none;
    padding: 0;
    position: relative;
    margin: 0;
  }

  .vertical-timeline::before {
    content: '';
    position: absolute;
    top: 5px;
    bottom: 0;
    left: 10px;
    width: 2px;
    background: #e9ecef;
  }

  .vertical-timeline li {
    position: relative;
    padding-left: 35px;
    margin-bottom: 25px;
  }

  .timeline-dot {
    position: absolute;
    left: 4px;
    top: 2px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #dee2e6;
    z-index: 1;
  }

  .vertical-timeline li:first-child .timeline-dot {
    border-color: #2ecc71;
    background: #2ecc71;
    box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.3);
  }

  /* Inputs */
  .form-control {
    border: 1px solid #dee2e6;
    height: calc(1.5em + 1rem + 2px);
    padding: 0.5rem 0.75rem;
    font-size: 0.9rem;
  }

  .form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
  }
</style>

<div class="row">

  <div class="col-lg-4 col-md-12">
    <div class="sticky-sidebar">
      <div class="card card-modern">
        <div class="card-header-modern bg-primary">
          <h6 class="text-white mb-0"><i class="fas fa-file-signature mr-2"></i>Form Penagihan</h6>
        </div>
        <div class="card-body">

          <div class="alert alert-soft-warning d-flex align-items-start mb-4 p-3" style="background: #fff8e1; border: 1px solid #ffe57f; border-radius: 6px;">
            <i class="fas fa-lightbulb text-warning mt-1 mr-2"></i>
            <div style="font-size: 0.8rem; color: #664d03;">
              <strong>Penting:</strong>
              <ul class="pl-3 mb-0 mt-1">
                <li>Link G-Drive wajib <b>Public</b>.</li>
                <li>Data tidak bisa diedit setelah kirim.</li>
              </ul>
            </div>
          </div>

          <form id="form-pengajuan">
            <input type="hidden" name="id_tracking" value="<?= $tracking->id_tracking ?>">
            <input type="hidden" name="id_ekspedisi" value="<?= $tracking->id_ekspedisi ?>">
            <input type="hidden" name="id_tagihan" value="<?= isset($data_lama->id_tagihan) ? $data_lama->id_tagihan : '' ?>">
            <input type="hidden" name="is_revisi" value="<?= $is_revisi ? 'true' : 'false' ?>">

            <?php if ($is_revisi && !empty($data_lama->catatan_revisi)): ?>
              <div class="alert alert-danger p-3 mb-4 rounded shadow-sm">
                <h6 class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Revisi Diperlukan!</h6>
                <small><?= nl2br($data_lama->catatan_revisi) ?></small>
              </div>
            <?php endif; ?>

            <div class="form-group mb-3">
              <label class="text-label">Nomor Invoice Ekspedisi <span class="text-danger">*</span></label>
              <div class="input-group shadow-sm">
                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-file-invoice text-info"></i></span></div>
                <input type="text" class="form-control border-left-0 required-field" name="no_invoice" placeholder="INV-001/EKS/XII/2025" value="<?= $data_lama->no_invoice ?? '' ?>" required>
              </div>
              <small class="text-muted" style="font-style: italic;"><i class="fas fa-info-circle mr-1"></i>Nomor invoice akan menjadi grup untuk pengajuan SPP</small>
            </div>

            <div class="form-group mb-3">
              <label class="text-label">Link Invoice Ekspedisi <span class="text-danger">*</span></label>
              <div class="input-group shadow-sm">
                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-link text-primary"></i></span></div>
                <input type="url" class="form-control border-left-0 required-field" name="link_invoice" placeholder="https://drive.google.com/..." value="<?= $data_lama->link_invoice ?? '' ?>" required>
              </div>
            </div>

            <div class="form-group mb-3">
              <label class="text-label">Nilai Tagihan (Rp) <span class="text-danger">*</span></label>
              <div class="input-group shadow-sm">
                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0 font-weight-bold text-success">Rp</span></div>
                <input type="number" class="form-control border-left-0 required-field" name="nilai_tagihan" id="nilai_tagihan" placeholder="0" value="<?= isset($data_lama->nilai_tagihan) ? (int)$data_lama->nilai_tagihan : '' ?>" required>
              </div>
              <small class="text-danger" style="font-style: italic;">Jangan masukkan titik,koma dan karakter apapun. Hanya Angka</small>
            </div>

            <div class="form-group mb-3">
              <label class="text-label">Biaya Asuransi (Rp) <span class="text-muted font-weight-normal">(Opsional)</span></label>
              <div class="input-group shadow-sm">
                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0 font-weight-bold text-info">Rp</span></div>
                <input type="number" class="form-control border-left-0" name="biaya_asuransi" id="biaya_asuransi" placeholder="0" value="<?= isset($data_lama->biaya_asuransi) ? (int)$data_lama->biaya_asuransi : '' ?>">
              </div>
              <small class="text-muted" style="font-style: italic;"><i class="fas fa-info-circle mr-1"></i>Isi biaya asuransi jika ada dalam invoice. Lihat kolom "Asuransi" pada invoice ekspedisi.</small>
            </div>

            <!-- Total Tagihan (Read-only, Auto Calculate) -->
            <div class="form-group mb-3">
              <label class="text-label">Total Tagihan (Rp)</label>
              <div class="input-group shadow-sm">
                <div class="input-group-prepend"><span class="input-group-text bg-primary text-white font-weight-bold">Rp</span></div>
                <input type="text" class="form-control border-left-0 bg-light font-weight-bold text-dark" id="total_tagihan_display" readonly placeholder="0" style="font-size: 1.1rem;">
              </div>
              <small class="text-success" style="font-style: italic;"><i class="fas fa-calculator mr-1"></i>Total = Nilai Tagihan + Biaya Asuransi (otomatis terhitung)</small>
            </div>

            <div class="form-group mb-3">
              <label class="text-label">Tanggal Invoice <span class="text-danger">*</span></label>
              <div class="input-group shadow-sm">
                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="far fa-calendar-alt text-info"></i></span></div>
                <input type="date" class="form-control border-left-0 required-field" name="tanggal_invoice" value="<?= $data_lama->tanggal_invoice ?? '' ?>" required>
              </div>
            </div>

            <div class="form-group mb-3">
              <label class="text-label">Link Faktur Pajak (Opsional)</label>
              <div class="input-group shadow-sm">
                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-file-invoice-dollar text-warning"></i></span></div>
                <input type="url" class="form-control border-left-0" name="link_faktur_pajak" placeholder="https://drive.google.com/..." value="<?= $data_lama->link_faktur_pajak ?? '' ?>" required>
              </div>
            </div>

            <div class="form-group mb-4">
              <label class="text-label">Link Dokumen Lain (Opsional)</label>
              <div class="input-group shadow-sm">
                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-folder text-secondary"></i></span></div>
                <input type="url" class="form-control border-left-0" name="link_dokumen_lain" placeholder="https://drive.google.com/..." value="<?= $data_lama->link_dokumen_lain ?? '' ?>">
              </div>
            </div>

            <button type="button" class="btn btn-secondary btn-block font-weight-bold py-3 shadow rounded-pill" id="btn-submit" disabled style="cursor: not-allowed; transition: all 0.3s; letter-spacing: 0.5px;">
              <i class="fas fa-lock mr-2"></i> LENGKAPI DATA
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>


  <div class="col-lg-8 col-md-12">

    <div class="card card-modern">
      <div class="card-body pt-4 pb-2">
        <h6 class="text-center text-label mb-2" style="font-size:0.8rem">STATUS PROGRESS</h6>

        <?php $cur_stat = isset($data_lama->status_approval) ? $data_lama->status_approval : -1; ?>
        <div class="approval-track px-3">
          <?php
          $steps = [
            1 => ['icon' => 'fa-warehouse', 'label' => 'Warehouse'],
            2 => ['icon' => 'fa-calculator', 'label' => 'Accounting'],
            3 => ['icon' => 'fa-percent', 'label' => 'Tax'],
            4 => ['icon' => 'fa-money-check-alt', 'label' => 'Finance'],
            5 => ['icon' => 'fa-check-double', 'label' => 'Selesai']
          ];
          foreach ($steps as $key => $val):
            $activeClass = ($cur_stat >= $key && $cur_stat != 99) ? 'completed' : ($cur_stat == $key ? 'active' : '');
            if ($cur_stat == 5 && $key == 5) $activeClass = 'completed';
          ?>
            <div class="approval-step <?= $activeClass ?>">
              <div class="approval-icon"><i class="fas <?= $val['icon'] ?>"></i></div>
              <div class="approval-label"><?= $val['label'] ?></div>
            </div>
          <?php endforeach; ?>
        </div>

        <?php if ($cur_stat == 99): ?>
          <div class="alert alert-danger mx-4 mt-3 text-center py-2 shadow-sm border-0"><i class="fas fa-times-circle mr-1"></i> Pengajuan Ditolak</div>
        <?php elseif ($cur_stat == 0): ?>
          <div class="alert alert-warning mx-4 mt-3 text-center py-2 shadow-sm border-0"><i class="fas fa-exclamation-circle mr-1"></i> Sedang dalam Revisi</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="card card-modern">
      <div class="card-header-modern">
        <h6><i class="fas fa-box-open text-warning mr-2"></i>Detail Pengiriman</h6>
        <?php
        // Tampilkan badge tipe tracking
        $tracking_type = isset($tracking_type) ? $tracking_type : 'pengeluaran_barang';
        if ($tracking_type == 'pengiriman_stok'): ?>
          <span class="badge badge-warning px-3 py-1">Transfer Stok Antar Gudang</span>
        <?php elseif ($tracking_type == 'serah_terima_barang'): ?>
          <span class="badge badge-info px-3 py-1">Serah Terima Barang (STTB)</span>
        <?php else: ?>
          <span class="badge badge-primary px-3 py-1">Pengeluaran ke Customer</span>
        <?php endif; ?>
      </div>
      <div class="card-body p-0">
        <div class="row no-gutters">
          <div class="col-md-7 p-4 border-right">
            <?php if ($tracking_type == 'pengiriman_stok'): ?>
              <!-- PENGIRIMAN STOK: Tampilkan Gudang Asal -> Gudang Tujuan -->
              <div class="mb-4">
                <span class="text-label text-warning">Gudang Asal</span>
                <h5 class="font-weight-bold text-dark mt-1"><?= $tracking->nama_gudang_asal ?? $tracking->nama_gudang ?></h5>
                <p class="text-muted small mb-0"><i class="fas fa-map-pin mr-1"></i> <?= $tracking->alamat_gudang_asal ?? '-' ?></p>
              </div>
              <div class="mb-4">
                <span class="text-label text-success">Gudang Tujuan</span>
                <h5 class="font-weight-bold text-dark mt-1"><?= $tracking->nama_gudang_tujuan ?? '-' ?></h5>
                <p class="text-success small mb-0"><i class="fas fa-map-pin mr-1"></i> <?= $tracking->alamat_gudang_tujuan ?? $tracking->alamat_penerima ?></p>
              </div>
              <div class="mb-3">
                <span class="text-label">No. Pemindahan</span>
                <span class="text-value font-weight-bold"><?= $tracking->no_pemindahan ?? $tracking->no_sj ?></span>
              </div>
            <?php elseif ($tracking_type == 'serah_terima_barang'): ?>
              <!-- SERAH TERIMA BARANG: Tampilkan Pihak 1 -> Pihak 2 -->
              <div class="mb-4">
                <span class="text-label text-info">Pihak Pertama (Pengirim)</span>
                <h5 class="font-weight-bold text-dark mt-1"><?= $tracking->nama_pihak1 ?? '-' ?></h5>
                <p class="text-muted small mb-0"><i class="fas fa-map-pin mr-1"></i> <?= $tracking->alamat_pihak1 ?? '-' ?></p>
              </div>
              <div class="mb-4">
                <span class="text-label text-success">Pihak Kedua (Penerima)</span>
                <h5 class="font-weight-bold text-dark mt-1"><?= $tracking->nama_pihak2 ?? $tracking->nama_customer ?></h5>
                <p class="text-success small mb-0"><i class="fas fa-map-pin mr-1"></i> <?= $tracking->alamat_pihak2 ?? $tracking->alamat_penerima ?></p>
              </div>
              <div class="mb-3">
                <span class="text-label">Kode STTB</span>
                <span class="text-value font-weight-bold"><?= $tracking->kode_stb ?? $tracking->no_sj ?></span>
              </div>
              <div class="mb-3">
                <span class="text-label">Kota Pengajuan</span>
                <span class="text-value"><?= $tracking->kota_stb ?? '-' ?></span>
              </div>
            <?php else: ?>
              <!-- PENGELUARAN BARANG: Tampilkan Customer (Default) -->
              <div class="mb-4">
                <span class="text-label text-primary">Nama Customer</span>
                <h5 class="font-weight-bold text-dark mt-1"><?= $tracking->nama_customer ?></h5>
                <p class="text-success small mb-0"><i class="fas fa-map-pin mr-1"></i> <?= $tracking->alamat_penerima ?></p>
              </div>
              <div class="mb-3">
                <span class="text-label">PIC Penerima</span>
                <span class="text-value"><?= $tracking->pic_penerima ?></span>
              </div>
            <?php endif; ?>

            <div class="row">
              <div class="col-12 mb-3">
                <div class="mt-3">
                  <span class="text-label mb-2 d-block">Rincian Item Barang</span>

                  <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0" style="font-size: 0.8rem;">
                      <thead style="background: #f6f9fc;">
                        <tr>
                          <th width="5%" class="text-center">#</th>
                          <th>Nama Barang</th>
                          <?php if ($tracking_type == 'serah_terima_barang'): ?>
                            <th>Merk</th>
                            <th class="text-center">Qty</th>
                            <th>Satuan</th>
                            <th>Batch</th>
                          <?php else: ?>
                            <th>NIE</th>
                            <th class="text-center">Qty</th>
                            <th>Batch</th>
                            <th>Exp Date</th>
                          <?php endif; ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        // KONDISI 1: Ada Data Detail Array
                        if (!empty($detail_barang_keluar)):
                          $no = 1;
                          foreach ($detail_barang_keluar as $row):
                            if ($tracking_type == 'serah_terima_barang'):
                        ?>
                              <tr>
                                <td class="text-center align-middle"><?= $no++ ?></td>
                                <td class="align-middle font-weight-bold"><?= $row->nama_barang ?></td>
                                <td class="align-middle"><?= $row->merk ?? '-' ?></td>
                                <td class="text-center align-middle text-dark font-weight-bold"><?= $row->qty ?></td>
                                <td class="align-middle"><?= $row->satuan ?? '-' ?></td>
                                <td class="align-middle"><?= $row->no_batch ?? '-' ?></td>
                              </tr>
                            <?php else:
                              $exp = (!empty($row->exp_date) && date('Y', strtotime($row->exp_date)) > 2000)
                                ? date('d M Y', strtotime($row->exp_date)) : '-';
                            ?>
                              <tr>
                                <td class="text-center align-middle"><?= $no++ ?></td>
                                <td class="align-middle font-weight-bold"><?= $row->nama_barang ?></td>
                                <td class="align-middle"><?= $row->nie ?? '-' ?></td>
                                <td class="text-center align-middle text-dark font-weight-bold"><?= $row->qty ?></td>
                                <td class="align-middle"><?= $row->no_batch ?? '-' ?></td>
                                <td class="align-middle text-danger"><?= $exp ?></td>
                              </tr>
                            <?php endif;
                          endforeach;

                        // KONDISI 2: Fallback (Cuma ada String Nama Barang)
                        elseif (!empty($tracking->nama_barang) && $tracking->nama_barang != '-'):
                          $list_barang = explode(',', $tracking->nama_barang);
                          $no = 1;
                          foreach ($list_barang as $brg):
                            ?>
                            <tr>
                              <td class="text-center"><?= $no++ ?></td>
                              <td colspan="5"><strong><?= trim($brg) ?></strong> <span class="text-muted font-italic ml-2">(Detail qty/batch tidak tersedia)</span></td>
                            </tr>
                          <?php endforeach; ?>

                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center">- Tidak ada data barang -</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-5 bg-light p-4">
            <div class="mb-4">
              <span class="text-label">Nomor Resi / AWB</span>
              <div class="text-value-lg text-dark mt-1"><?= $tracking->no_resi ?></div>
              <a href="<?= base_url('tracking/detail/' . encrypt($tracking->id_tracking)) ?>" target="_blank">
                <span class="text-primary font-monospace"><i class="fas fa-link mr-2"></i><?= $tracking->no_sj ?></small>
              </a>
            </div>

            <?php if (!empty($tracking->biaya)) { ?>
              <div class="mb-4">
                <span class="text-label">Biaya Real (Ekspedisi)</span>
                <div class="text-value-lg text-danger mt-1">Rp <?= number_format($tracking->biaya, 0, ',', '.') ?></div>
              </div>
            <?php }; ?>

            <!-- Input Link Resi untuk Edit -->
            <div class="mb-4">
              <label class="text-label" for="link_resi_form">Link Resi/Tracking (Opsional)</label>
              <input type="url" class="form-control" id="link_resi_form" name="link_resi"
                placeholder="https://tracking.tiki.id/..."
                value="<?= !empty($tracking->link_resi) && $tracking->link_resi != '-' ? $tracking->link_resi : '' ?>">
              <small class="text-muted d-block mt-1">
                <i class="fas fa-info-circle mr-1"></i>Masukkan link resi/tracking untuk ditampilkan sebagai tombol
              </small>
            </div>

            <?php if (!empty($tracking->link_resi) && $tracking->link_resi != '-'): ?>
              <a href="<?= $tracking->link_resi ?>" target="_blank" class="btn btn-primary btn-block rounded-pill shadow-sm"><i class="fas fa-external-link-alt mr-2"></i> Cek Resi Online</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="row d-flex align-items-stretch">

      <div class="col-md-6 mb-4 d-flex">
        <div class="card card-modern w-100 h-100">
          <div class="card-header-modern">
            <h6><i class="fas fa-truck text-primary mr-2"></i>Ekspedisi</h6>
            <?php if ($tracking->kerjasama == 'Kontrak'): ?>
              <span class="badge badge-success px-3 py-1 rounded-pill">CONTRACT</span>
            <?php else: ?>
              <span class="badge badge-secondary px-3 py-1 rounded-pill">NON-CONTRACT</span>
            <?php endif; ?>
          </div>
          <div class="card-body d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
              <div class="icon-shape bg-primary text-white rounded-circle p-3 mr-3 shadow-sm">
                <i class="fas fa-shipping-fast fa-lg"></i>
              </div>
              <div>
                <h5 class="mb-0 text-dark font-weight-bold" style="font-size:1.1rem"><?= $tracking->nama_ekspedisi ?></h5>
                <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> <?= $tracking->alamat_ekspedisi ?></small>
              </div>
            </div>

            <hr class="my-2 w-100 border-light">

            <div class="row mt-2">
              <div class="col-6 mb-3">
                <span class="text-label">PIC Vendor</span>
                <span class="text-value"><?= $tracking->pic_ekspedisi ?? '-' ?></span>
              </div>
              <div class="col-6 mb-3">
                <span class="text-label">Kontak</span>
                <span class="text-value"><?= $tracking->kontak_ekspedisi ?? '-' ?></span>
              </div>
              <div class="col-6 mb-3">
                <span class="text-label">Payment Term</span>
                <span class="text-value text-danger font-weight-bold"><?= $tracking->payment_term ?? '-' ?></span>
              </div>
              <div class="col-6 mb-3">
                <span class="text-label">PPh 23</span>
                <span class="text-value"><?= $tracking->pph23 ?? '-' ?></span>
              </div>
            </div>

            <div class="mt-auto pt-3 border-top">
              <span class="text-label mb-2">Legalitas</span>
              <div class="d-flex">
                <?php if (!empty($tracking->link_mou)): ?>
                  <a href="<?= $tracking->link_mou ?>" target="_blank" class="btn btn-sm btn-outline-primary mr-2 flex-fill"><i class="fas fa-file-contract"></i> MOU</a>
                <?php endif; ?>
                <?php if (!empty($tracking->link_legalitas)): ?>
                  <a href="<?= $tracking->link_legalitas ?>" target="_blank" class="btn btn-sm btn-outline-info flex-fill"><i class="fas fa-balance-scale"></i> Legal</a>
                <?php endif; ?>
                <?php if (empty($tracking->link_mou) && empty($tracking->link_legalitas)): ?>
                  <span class="text-muted small font-italic">Tidak ada dokumen.</span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4 d-flex">
        <div class="card card-modern w-100 h-100">
          <div class="card-header-modern">
            <?php if ($tracking_type == 'pengiriman_stok'): ?>
              <h6><i class="fas fa-exchange-alt text-warning mr-2"></i>Info Transfer Stok</h6>
            <?php elseif ($tracking_type == 'serah_terima_barang'): ?>
              <h6><i class="fas fa-handshake text-info mr-2"></i>Info Serah Terima</h6>
            <?php else: ?>
              <h6><i class="fas fa-warehouse text-info mr-2"></i>Asal Barang</h6>
            <?php endif; ?>
          </div>
          <div class="card-body d-flex flex-column">
            <div class="info-box mb-3 flex-fill">
              <div class="row">
                <?php if ($tracking_type == 'pengiriman_stok'): ?>
                  <div class="col-12 pl-3 mb-2">
                    <span class="text-label">No. Pemindahan</span>
                    <div class="text-value mt-1">
                      <a href="<?= base_url('tracking/detail/' . encrypt($tracking->id_tracking)) ?>" target="_blank">
                        <?= $tracking->no_pemindahan ?? $tracking->no_sj ?><i class="fas fa-external-link-alt ml-2"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-12 border-right">
                    <span class="text-label">Keterangan</span>
                    <div class="text-value font-weight-bold mt-1 text-primary"><?= $tracking->ket_pengiriman_stok ?? $tracking->keterangan ?? '-' ?></div>
                  </div>
                <?php elseif ($tracking_type == 'serah_terima_barang'): ?>
                  <div class="col-12 pl-3 mb-2">
                    <span class="text-label">Kode STTB</span>
                    <div class="text-value mt-1">
                      <a href="<?= base_url('tracking/detail/' . encrypt($tracking->id_tracking)) ?>" target="_blank">
                        <?= $tracking->kode_stb ?? $tracking->no_sj ?><i class="fas fa-external-link-alt ml-2"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-12 border-right">
                    <span class="text-label">Tanggal Pengajuan</span>
                    <div class="text-value font-weight-bold mt-1 text-primary"><?= isset($tracking->tgl_pengajuan_stb) ? date('d F Y', strtotime($tracking->tgl_pengajuan_stb)) : '-' ?></div>
                  </div>
                <?php else: ?>
                  <div class="col-12 pl-3 mb-2">
                    <span class="text-label">No. Pengiriman / Surat Jalan</span>
                    <div class="text-value mt-1">
                      <a href="<?= base_url('tracking/detail/' . encrypt($tracking->id_tracking)) ?>" target="_blank">
                        <?= $tracking->no_pengiriman ?? $tracking->no_sj ?><i class="fas fa-external-link-alt ml-2"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-12 border-right">
                    <span class="text-label">No. PO</span>
                    <div class="text-value font-weight-bold mt-1 text-primary"><?= $tracking->no_po ?? '-' ?></div>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <div class="px-2">
              <?php if ($tracking_type == 'pengiriman_stok'): ?>
                <div class="mb-3">
                  <span class="text-label">Gudang Asal</span>
                  <span class="text-value"><i class="fas fa-building mr-2 text-muted"></i> <?= $tracking->nama_gudang_asal ?? $tracking->nama_gudang ?></span>
                </div>
                <div class="mb-3">
                  <span class="text-label">Gudang Tujuan</span>
                  <span class="text-value"><i class="fas fa-building mr-2 text-success"></i> <?= $tracking->nama_gudang_tujuan ?? '-' ?></span>
                </div>
              <?php elseif ($tracking_type == 'serah_terima_barang'): ?>
                <div class="mb-3">
                  <span class="text-label">Pengaju STTB</span>
                  <span class="text-value"><i class="fas fa-user mr-2 text-muted"></i> <?= $tracking->nama_pengaju_stb ?? '-' ?></span>
                </div>
                <div class="mb-3">
                  <span class="text-label">Kota</span>
                  <span class="text-value"><i class="fas fa-map-marker-alt mr-2 text-muted"></i> <?= $tracking->kota_stb ?? '-' ?></span>
                </div>
              <?php else: ?>
                <div class="mb-3">
                  <span class="text-label">Gudang Pengiriman</span>
                  <span class="text-value"><i class="fas fa-building mr-2 text-muted"></i> <?= $tracking->nama_gudang ?></span>
                </div>
              <?php endif; ?>
              <div class="d-flex align-items-center justify-content-between">
                <div class="mb-3">
                  <span class="text-label"><?= $tracking_type == 'serah_terima_barang' ? 'Tanggal Pengajuan' : 'Tanggal Keluar' ?></span>
                  <span class="text-value"><i class="far fa-clock mr-2 text-muted"></i>
                    <?php
                    if ($tracking_type == 'serah_terima_barang') {
                      echo isset($tracking->tgl_pengajuan_stb) ? date('d F Y', strtotime($tracking->tgl_pengajuan_stb)) : '-';
                    } elseif ($tracking_type == 'pengiriman_stok') {
                      echo isset($tracking->tgl_pengiriman_stok) ? date('d F Y', strtotime($tracking->tgl_pengiriman_stok)) : (isset($tracking->tgl_pengiriman) ? date('d F Y', strtotime($tracking->tgl_pengiriman)) : '-');
                    } else {
                      echo isset($tracking->tgl_keluar_gudang) ? date('d F Y', strtotime($tracking->tgl_keluar_gudang)) : '-';
                    }
                    ?>
                  </span>
                </div>
                <div class="mb-3">
                  <span class="text-label">Estimasi Sampai</span>
                  <span class="text-value"><i class="far fa-clock mr-2 text-muted"></i> <?= isset($tracking->tgl_sampai) ? date('d F Y', strtotime($tracking->tgl_sampai)) : '-' ?></span>
                </div>
              </div>
            </div>

            <?php if (!empty($tracking->link_file_po) && $tracking_type == 'pengeluaran_barang'): ?>
              <div class="mt-auto pt-3 border-top">
                <a href="<?= $tracking->link_file_po ?>" target="_blank" class="btn btn-info btn-block shadow-sm"><i class="fas fa-cloud-download-alt mr-2"></i> Unduh PO / DO</a>
              </div>
            <?php else: ?>
              <div class="mt-auto pt-3 border-top text-center">
                <span class="text-muted small"><?= $tracking_type == 'pengeluaran_barang' ? 'Tidak ada lampiran PO/DO' : 'Tidak ada lampiran' ?></span>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-modern">
      <div class="card-header-modern">
        <h6><i class="fas fa-history text-secondary mr-2"></i>Riwayat Perjalanan</h6>
        <span class="badge badge-light text-primary border"><?= count($tracking_history_log) ?> Update</span>
      </div>
      <div class="card-body" style="max-height: 400px; overflow-y: auto; scrollbar-width: thin;">
        <?php if (!empty($tracking_history_log)): ?>
          <ul class="vertical-timeline mt-2">
            <?php foreach ($tracking_history_log as $log): ?>
              <?php
              $status_name = 'Unknown';
              if ($log->id_status == 1) $status_name = 'Proses Kirim';
              elseif ($log->id_status == 2) $status_name = 'Manifest Berangkat';
              elseif ($log->id_status == 3) $status_name = 'Proses Sortir / Transit';
              elseif ($log->id_status == 4) $status_name = 'Pengantaran Kurir';
              elseif ($log->id_status == 5) $status_name = 'Barang Diterima';
              elseif ($log->id_status == 6) $status_name = 'Menunggu Konfirmasi';

              $is_finish = ($log->id_status == 5);
              ?>
              <li>
                <span class="timeline-dot"></span>
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="font-weight-bold <?= $is_finish ? 'text-success' : 'text-dark' ?>" style="font-size:0.9rem"><?= $status_name ?></span>
                  <small class="text-muted text-right font-weight-bold d-flex flex-column" style="font-size:0.75rem;">
                    <span class="small text-muted mb-0">Data Diupdate pada</span>
                    <span><?= date('d M Y', strtotime($log->created_at)) ?></span>
                    <span class="text-primary"><?= date('H:i', strtotime($log->created_at)) ?></span>
                  </small>
                </div>

                <div class="bg-light p-3 rounded border-left border-3 <?= $is_finish ? 'border-success' : 'border-primary' ?>">

                  <div class="pt-2">
                    <ul class="list-unstyled mb-0 small text-muted">
                      <?php if ($log->id_status == 5): ?>
                        <li class="mb-1 text-success">
                          <i class="fas fa-calendar-check mr-2"></i>Tanggal Diterima:
                          <strong><?= !empty($log->tgl_penerima) ? date('d M Y', strtotime($log->tgl_penerima)) : '-' ?></strong>
                        </li>
                        <?php if (!empty($log->nama_penerima)): ?>
                          <li class="text-success border-bottom mb-0">
                            <i class="fas fa-user-check mr-2"></i>Diterima Oleh:
                            <strong><?= $log->nama_penerima ?></strong>
                          </li>
                        <?php endif; ?>
                        <li class="mb-2">
                          <i class="fas fa-user-edit mr-2 text-secondary"></i>Update Oleh:
                          <strong><?= isset($log->nama_penerima) ? $log->nama_penerima : 'System/Admin' ?></strong>
                        </li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </div>

                <?php if ($is_finish && !empty($log->bukti_penerima)): ?>
                  <div class="mt-2">
                    <a href="<?= $log->bukti_penerima ?>" target="_blank" class="btn btn-sm btn-success shadow-sm rounded-pill px-3">
                      <i class="fas fa-image mr-1"></i> Bukti Foto
                    </a>
                  </div>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="text-center py-5">
            <img src="https://img.icons8.com/clouds/100/000000/shipped.png" alt="No Data" style="opacity:0.5">
            <p class="text-muted mt-2">Belum ada riwayat perjalanan.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function() {

    // =========================================================================
    // KALKULASI TOTAL TAGIHAN OTOMATIS
    // =========================================================================
    function calculateTotal() {
      let nilai = parseFloat($('#nilai_tagihan').val()) || 0;
      let asuransi = parseFloat($('#biaya_asuransi').val()) || 0;
      let total = nilai + asuransi;

      // Format dengan thousand separator
      $('#total_tagihan_display').val(new Intl.NumberFormat('id-ID').format(total));
    }

    // Trigger calculation on input change
    $('#nilai_tagihan, #biaya_asuransi').on('input change keyup', function() {
      calculateTotal();
    });

    // Initial calculation on page load
    calculateTotal();
    // =========================================================================

    // VALIDASI REALTIME
    $('.required-field').on('input change keyup', function() {
      checkFormValidity();
    });
    checkFormValidity();

    function checkFormValidity() {
      let allFilled = true;
      $('.required-field').each(function() {
        if ($(this).val().trim() === '') allFilled = false;
      });

      const btn = $('#btn-submit');
      if (allFilled) {
        btn.prop('disabled', false).css('cursor', 'pointer').removeClass('btn-secondary').addClass('btn-success')
          .html('<i class="fas fa-paper-plane mr-2"></i> KIRIM PENGAJUAN');
      } else {
        btn.prop('disabled', true).css('cursor', 'not-allowed').removeClass('btn-success').addClass('btn-secondary')
          .html('<i class="fas fa-lock mr-2"></i> LENGKAPI DATA');
      }
    }

    // SUBMIT VIA AJAX
    $('#btn-submit').click(function() {
      let btn = $(this);
      let originalText = btn.html();

      Swal.fire({
        title: 'Kirim Data Tagihan?',
        text: "Pastikan nominal dan link invoice sudah benar.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2dce89',
        cancelButtonColor: '#f5365c',
        confirmButtonText: 'Ya, Kirim!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

          $.ajax({
            url: '<?= base_url("tagihan/submit_pengajuan") ?>',
            type: 'POST',
            data: $('#form-pengajuan').serialize(),
            dataType: 'json',
            success: function(res) {
              if (res.status == 'success') {
                Swal.fire('Berhasil!', res.message, 'success').then(() => {
                  window.location.href = '<?= base_url("tracking") ?>';
                });
              } else {
                Swal.fire('Gagal!', res.message, 'error');
                btn.prop('disabled', false).html(originalText);
              }
            },
            error: function(xhr, status, error) {
              console.error(xhr.responseText);
              Swal.fire('Error System', 'Terjadi kesalahan server.', 'error');
              btn.prop('disabled', false).html(originalText);
            }
          });
        }
      });
    });
  });
</script>