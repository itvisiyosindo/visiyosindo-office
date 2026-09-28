<style>
    body {
        color: #000000;
        font-family: 'Times New Roman', serif;
        font-size: 12pt;
        margin: 0;
        padding: 0;
        line-height: 1.4;
    }

    /* ========== CONTENT WRAPPER ========== */
    .content-wrapper {
        padding: 0;
        padding-top: 5mm;
    }

    /* ========== INFO SURAT ========== */
    .surat-info {
        margin-bottom: 15px;
    }

    .surat-info-table {
        width: 100%;
        font-size: 12pt;
    }

    .surat-info-table td {
        vertical-align: top;
        padding: 2px 0;
    }

    .surat-info-left {
        width: 50%;
    }

    .surat-info-right {
        width: 50%;
        text-align: right;
    }

    /* ========== KEPADA ========== */
    .kepada {
        margin-bottom: 15px;
        line-height: 1.6;
    }

    .kepada p {
        margin: 0 0 2px 0;
    }

    .kepada .label {
        font-weight: normal;
    }

    .kepada .bold {
        font-weight: bold;
    }

    /* ========== ISI SURAT ========== */
    .isi-surat {
        margin-bottom: 15px;
        text-align: justify;
    }

    .isi-surat p {
        margin: 0 0 10px 0;
        text-indent: 40px;
    }

    .isi-surat p.no-indent {
        text-indent: 0;
    }

    /* ========== TABEL PRODUK ========== */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
        font-size: 11pt;
    }

    .data-table th {
        background-color: #B8CCE4;
        border: 1px solid #000;
        padding: 8px 5px;
        text-align: center;
        font-weight: bold;
        vertical-align: middle;
        word-wrap: break-word;
        word-break: break-all;
    }

    .data-table td {
        border: 1px solid #000;
        padding: 6px 8px;
        vertical-align: middle;
        word-wrap: break-word;
        word-break: break-all;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    .data-table tfoot td {
        font-weight: bold;
        background-color: #f5f5f5;
    }

    /* ========== SISTEM PEMBAYARAN ========== */
    .pembayaran-box {
        background-color: #FFFFCC;
        border: 1px solid #000;
        border-top: none;
        text-align: center;
        padding: 8px;
        margin-bottom: 20px;
    }

    .pembayaran-box .title {
        font-weight: bold;
        color: #1a237e;
        margin-bottom: 3px;
    }

    .pembayaran-box .content {
        font-size: 11pt;
    }

    /* ========== KETERANGAN ========== */
    .keterangan {
        margin-bottom: 15px;
    }

    .keterangan h4 {
        font-weight: bold;
        margin: 0 0 8px 0;
        font-size: 12pt;
    }

    .keterangan ol {
        padding-left: 25px;
        margin: 0;
    }

    .keterangan li {
        margin-bottom: 3px;
        text-align: justify;
    }

    /* ========== PENUTUP ========== */
    .penutup {
        margin-bottom: 20px;
        text-align: justify;
    }

    .penutup p {
        margin: 0 0 10px 0;
        text-indent: 40px;
    }

    /* ========== TTD SECTION ========== */
    .ttd-section {
        margin-top: 20px;
        width: 100%;
    }

    .ttd-section td {
        vertical-align: top;
        padding: 3px;
        font-size: 12pt;
    }

    .ttd-box {
        text-align: center;
        min-height: 120px;
    }

    .ttd-img {
        height: 70px;
    }
</style>

<div class="content-wrapper">
    <!-- Info Surat (No, Hal, Tanggal) -->
    <div class="surat-info">
        <table class="surat-info-table">
            <tr>
                <td class="surat-info-left">
                    <table>
                        <tr>
                            <td style="width: 35px;">No.</td>
                            <td style="width: 10px;">:</td>
                            <td><strong><?= $sph->nomor_surat ?></strong></td>
                        </tr>
                        <tr>
                            <td>Hal.</td>
                            <td>:</td>
                            <td><strong><?= $sph->hal ?></strong></td>
                        </tr>
                    </table>
                </td>
                <td class="surat-info-right">
                    <?php
                    $this->load->model('md_sph');
                    echo $this->md_sph->formatTanggalSurat($sph->tanggal_surat, $sph->kota);
                    ?>
                </td>
            </tr>
        </table>
    </div>

    <!-- Kepada -->
    <div class="kepada">
        <p class="bold">Kepada Yth.</p>
        <p class="bold"><?= $sph->sapaan ?></p>
        <p class="bold"><?= $sph->nama_penerima ?></p>
        <p class="bold">Di -</p>
        <p class="bold"><?= $sph->alamat_penerima ?></p>
    </div>

    <!-- Isi Surat -->
    <div class="isi-surat">
        <p class="no-indent" style="margin-bottom: 5px;">Dengan Hormat,</p>
        <p>Bersama ini kami PT. Visi Yosindo Medikal yang berkedudukan di <?= $sph->kota ?> bermaksud memberikan penawaran harga sebagai berikut:</p>
    </div>

    <!-- Tabel Produk -->
    <?php
    // Check if any item has discount to show (not hidden)
    $showDisc = false;
    $showHargaPricelist = false;
    $showHargaPenawaran = false;

    foreach ($sph->items as $item) {
        if (!empty($item->hide_on_print)) continue;

        $hiddenFields = [];
        if (!empty($item->hidden_fields)) {
            $hiddenFields = json_decode($item->hidden_fields, true) ?: [];
        }

        // Check harga_pricelist visibility
        if (!in_array('harga_pricelist', $hiddenFields)) {
            $showHargaPricelist = true;
        }

        // Check diskon visibility  
        if (!in_array('diskon', $hiddenFields) && $item->tampilkan_diskon && ($item->diskon_persen > 0 || $item->diskon_nominal > 0)) {
            $showDisc = true;
        }

        // Check harga_penawaran visibility
        if (!in_array('harga_penawaran', $hiddenFields)) {
            $showHargaPenawaran = true;
        }
    }

    // Hitung kolom dinamis yang visible
    $visibleDynCols = [];
    if (!empty($sph->dynamic_columns)) {
        foreach ($sph->dynamic_columns as $col) {
            $shouldHideColumn = true;
            if (!empty($sph->items)) {
                foreach ($sph->items as $checkItem) {
                    if (!empty($checkItem->dynamic_values)) {
                        foreach ($checkItem->dynamic_values as $dv) {
                            if ($dv->sph_column_id == $col->id && empty($dv->hide_on_print)) {
                                $shouldHideColumn = false;
                                break 2;
                            }
                        }
                    }
                }
            }
            if (!$shouldHideColumn) {
                $visibleDynCols[] = $col;
            }
        }
    }
    ?>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40%;">Description</th>
                <?php if (!in_array('qty', $hiddenFields)): ?>
                    <th style="width: 10%;">Qty</th>
                <?php endif; ?>
                <?php if ($showHargaPricelist): ?>
                    <th style="width: 18%;">Harga Pricelist</th>
                <?php endif; ?>
                <?php if ($showDisc): ?>
                    <th style="width: 10%;">Disc</th>
                <?php endif; ?>
                <?php if ($showHargaPenawaran): ?>
                    <th style="width: 18%;">Harga Penawaran</th>
                <?php endif; ?>
                <?php foreach ($visibleDynCols as $col): ?>
                    <th><?= $col->nama_kolom ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($sph->items as $item):
                if (!empty($item->hide_on_print)) continue;

                $hiddenFields = [];
                if (!empty($item->hidden_fields)) {
                    $hiddenFields = json_decode($item->hidden_fields, true) ?: [];
                }
            ?>
                <tr>
                    <td class="text-left"><?= $item->deskripsi ?></td>
                    <?php if (!in_array('qty', $hiddenFields)): ?>
                        <td class="text-center"><?= $item->qty ?></td>
                    <?php endif; ?>
                    <?php if ($showHargaPricelist): ?>
                        <td class="text-right">
                            <?php if (in_array('harga_pricelist', $hiddenFields)): ?>
                                -
                            <?php elseif ($item->jenis_harga == 'free'): ?>
                                Rp<?= number_format($item->harga_penawaran, 0, ',', '.') ?>
                            <?php else: ?>
                                Rp<?= number_format($item->harga_pricelist, 0, ',', '.') ?>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if ($showDisc): ?>
                        <td class="text-center">
                            <?php if (in_array('diskon', $hiddenFields)): ?>
                                -
                            <?php elseif ($item->tampilkan_diskon): ?>
                                <?php if ($item->tipe_diskon == 'persen' && $item->diskon_persen > 0): ?>
                                    <?= intval($item->diskon_persen) ?>%
                                <?php elseif ($item->tipe_diskon == 'nominal' && $item->diskon_nominal > 0): ?>
                                    Rp<?= number_format($item->diskon_nominal, 0, ',', '.') ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if ($showHargaPenawaran): ?>
                        <td class="text-right">
                            <?php if (in_array('harga_penawaran', $hiddenFields)): ?>
                                -
                            <?php else: ?>
                                Rp<?= number_format($item->harga_penawaran, 0, ',', '.') ?>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php foreach ($visibleDynCols as $col): ?>
                        <td>
                            <?php
                            $val = '';
                            $hideOnPrint = false;

                            // Cek dari hidden_fields (global config)
                            $dynFieldKey = 'dyn_existing_' . $col->id;
                            $dynFieldKeyAlt = 'dyn_' . $col->id;
                            if (in_array($dynFieldKey, $hiddenFields) || in_array($dynFieldKeyAlt, $hiddenFields)) {
                                $hideOnPrint = true;
                            }

                            if (!empty($item->dynamic_values)) {
                                foreach ($item->dynamic_values as $dv) {
                                    if ($dv->sph_column_id == $col->id) {
                                        $val = $dv->nilai;
                                        // Legacy: juga cek dari field hide_on_print di database
                                        if (!empty($dv->hide_on_print)) {
                                            $hideOnPrint = true;
                                        }
                                        break;
                                    }
                                }
                            }
                            if ($hideOnPrint) {
                                echo '-';
                            } else {
                                if (preg_match('/^https?:\/\/[^\s]+$/i', trim($val))) {
                                    echo '<a href="' . htmlspecialchars(trim($val)) . '" target="_blank" style="color: #1a237e; text-decoration: underline; font-weight: bold;">Link E-Catalog</a>';
                                } else {
                                    echo nl2br(htmlspecialchars(wordwrap($val, 25, "\n", true)));
                                }
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <?php if ($sph->tampilkan_total): ?>
            <?php
            // Hitung colspan untuk TOTAL
            $colspanTotal = 2; // Description
            if ($showHargaPricelist) $colspanTotal++;
            if ($showDisc) $colspanTotal++;
            // Harga Penawaran adalah kolom untuk nilai total, jadi tidak dihitung di colspan
            ?>
            <tfoot>
                <tr>
                    <td class="text-right" colspan="<?= $colspanTotal ?>"><strong>TOTAL :</strong></td>
                    <?php if ($showHargaPenawaran): ?>
                        <td class="text-right"><strong>Rp<?= number_format($sph->total_harga, 0, ',', '.') ?></strong></td>
                    <?php endif; ?>
                    <?php if (count($visibleDynCols) > 0): ?>
                        <td colspan="<?= count($visibleDynCols) ?>"></td>
                    <?php endif; ?>
                </tr>
            </tfoot>
        <?php endif; ?>
    </table>

    <!-- Sistem Pembayaran -->
    <div class="pembayaran-box">
        <div class="title">Sistem Pembayaran</div>
        <div class="content">
            <?php if ($sph->sistem_pembayaran == 'cash'): ?>
                Pembayaran <strong>Cash / Tunai</strong>
            <?php elseif ($sph->sistem_pembayaran == 'tempo'): ?>
                Pembayaran <strong>Tempo <?= $sph->tempo_hari ?> hari</strong>
            <?php elseif ($sph->sistem_pembayaran == 'cicilan'): ?>
                Sistem Pembayaran <strong>DP <?= intval($sph->dp_persen) ?>%</strong> Sisa Cicil <strong><?= $sph->cicilan_bulan ?>x</strong>
            <?php elseif ($sph->sistem_pembayaran == 'custom'): ?>
                <?= $sph->pembayaran_custom ?? 'Sesuai kesepakatan' ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Keterangan -->
    <?php
    $hasKeterangan = (!empty($sph->keterangan['selected']) || !empty($sph->keterangan['custom']));
    if ($hasKeterangan):
    ?>
        <div class="keterangan">
            <h4>Keterangan :</h4>
            <ol>
                <?php
                if (!empty($sph->keterangan['selected'])) {
                    foreach ($sph->keterangan['selected'] as $ket) {
                        echo "<li>{$ket->keterangan}</li>";
                    }
                }
                if (!empty($sph->keterangan['custom'])) {
                    foreach ($sph->keterangan['custom'] as $ket) {
                        echo "<li>{$ket->keterangan}</li>";
                    }
                }
                ?>
            </ol>
        </div>
    <?php endif; ?>

    <!-- Informasi Rekening -->
    <?php if (!empty($sph->norek_terpilih)): ?>
        <div class="keterangan" style="margin-top: 15px; padding: 10px; background-color: #f5f5f5; border-left: 4px solid #2196f3;">
            <h4 style="margin-top: 0;">Rekening Pembayaran :</h4>
            <p style="margin: 5px 0;">
                <strong><?= htmlspecialchars($sph->norek_terpilih) ?></strong>
            </p>
        </div>
    <?php endif; ?>

    <!-- Penutup -->
    <div class="penutup">
        <p style="text-indent: 0;">Demikian surat penawaran ini kami sampaikan, atas perhatiannya kami ucapkan terima kasih.</p>
    </div>

    <!-- TTD Section -->
    <?php
    $img_path = "uploads/file_karyawan/ttd/";
    $ttd_notyet = "uploads/ttd/ttd-notyet.png";

    // Gunakan TTD berbeda untuk Visilab
    if (!empty($sph->is_visilab)) {
        $ttd_signed = $img_path . "ttd_mega_cap.png";
        $stempel = $img_path . "ttd_mega_cap.png";
    } else {
        $ttd_signed = $img_path . "ttd_33.png";
        $stempel = $img_path . "ttd_stample_gm.png";
    }

    $is_signed = ($sph->status == 'signed');
    $ttd_notyet_exists = file_exists(FCPATH . $ttd_notyet);
    ?>

    <table class="ttd-section" style="width: 100%;">
        <tr>
            <td style="width: 55%;"></td>
            <td style="width: 45%; text-align: center;">
                Hormat kami,<br>
                <strong>PT. Visi Yosindo Medikal</strong>
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center; height: 100px;">
                <?php if ($is_signed): ?>
                    <!-- TTD dengan stempel sudah digabung -->
                    <img src="<?= FCPATH . $stempel ?>" alt="TTD" style="height: 100px;">
                <?php else: ?>
                    <div style="height: 80px; display: flex; align-items: center; justify-content: center;">
                        <span style="color: #999; font-style: italic; font-size: 11px;">(Belum ditandatangani)</span>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center;">
                <strong>(<?= $sph->nama_ttd ?>)</strong><br>
                <span><?= $sph->jabatan_ttd ?></span>
            </td>
        </tr>
    </table>

</div>