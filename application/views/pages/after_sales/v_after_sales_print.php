<!-- View: v_after_sales_print.php - Format Berita Acara & Laporan Servis After Sales PDF -->
<style>
    body {
        color: #111111;
        font-family: 'Times New Roman', serif;
        font-size: 11pt;
        line-height: 1.4;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .bold { font-weight: bold; }
    .uppercase { text-transform: uppercase; }

    .judul-surat {
        text-align: center;
        font-size: 14pt;
        font-weight: bold;
        text-decoration: underline;
        margin-top: 5px;
        margin-bottom: 2px;
    }
    .nomor-surat {
        text-align: center;
        font-size: 11pt;
        margin-bottom: 20px;
    }

    .table-info {
        width: 100%;
        margin-bottom: 15px;
        border-collapse: collapse;
    }
    .table-info td {
        vertical-align: top;
        padding: 3px 2px;
        font-size: 11pt;
    }

    .section-title {
        font-weight: bold;
        font-size: 11pt;
        margin-top: 15px;
        margin-bottom: 6px;
        border-bottom: 1px solid #333;
        padding-bottom: 2px;
    }

    .table-data {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
        margin-bottom: 15px;
    }
    .table-data th, .table-data td {
        border: 1px solid #444;
        padding: 5px 6px;
        font-size: 10pt;
    }
    .table-data th {
        background-color: #f2f2f2;
        text-align: center;
        font-weight: bold;
    }

    .box-catatan {
        border: 1px solid #666;
        padding: 8px;
        min-height: 45px;
        font-size: 10pt;
        margin-bottom: 15px;
        background-color: #fafafa;
    }

    .table-ttd {
        width: 100%;
        margin-top: 25px;
        border-collapse: collapse;
    }
    .table-ttd td {
        text-align: center;
        vertical-align: top;
        font-size: 11pt;
    }
</style>

<div class="judul-surat">LEMBAR KERJA & BERITA ACARA SERVIS</div>
<div class="nomor-surat">Nomor: <?= htmlspecialchars($ticket->nomor_tiket) ?></div>

<table class="table-info">
    <tr>
        <td width="22%" class="bold">Nama Pelanggan / RS</td>
        <td width="3%">:</td>
        <td width="45%" class="bold"><?= htmlspecialchars($ticket->nama_pelanggan) ?></td>
        <td width="15%" class="bold">Tgl. Masuk</td>
        <td width="3%">:</td>
        <td width="12%"><?= date('d/m/Y', strtotime($ticket->tgl_masuk)) ?></td>
    </tr>
    <tr>
        <td class="bold">Alamat Lokasi</td>
        <td>:</td>
        <td><?= htmlspecialchars($ticket->alamat_pelanggan ?: '-') ?></td>
        <td class="bold">Jenis Layanan</td>
        <td>:</td>
        <td><?= htmlspecialchars($ticket->jenis_layanan) ?></td>
    </tr>
    <tr>
        <td class="bold">PIC / Kontak</td>
        <td>:</td>
        <td><?= htmlspecialchars($ticket->pic_nama ?: '-') ?> <?= !empty($ticket->pic_kontak) ? '(' . htmlspecialchars($ticket->pic_kontak) . ')' : '' ?></td>
        <td class="bold">Status Tiket</td>
        <td>:</td>
        <td class="uppercase bold"><?= htmlspecialchars(str_replace('_', ' ', $ticket->status)) ?></td>
    </tr>
</table>

<div class="section-title">A. IDENTITAS ALAT KESEHATAN</div>
<table class="table-info" style="margin-bottom: 8px;">
    <tr>
        <td width="22%" class="bold">Nama Alat</td>
        <td width="3%">:</td>
        <td width="45%" class="bold"><?= htmlspecialchars($ticket->nama_alat) ?></td>
        <td width="15%" class="bold">Teknisi</td>
        <td width="3%">:</td>
        <td width="12%"><?= htmlspecialchars($ticket->teknisi_nama_lengkap ?: $ticket->nama_teknisi ?: '-') ?></td>
    </tr>
    <tr>
        <td class="bold">Merk / Tipe</td>
        <td>:</td>
        <td><?= htmlspecialchars($ticket->merk_tipe ?: '-') ?></td>
        <td class="bold">Skema Biaya</td>
        <td>:</td>
        <td><?= strtoupper($ticket->status_pembayaran) ?></td>
    </tr>
    <tr>
        <td class="bold">Serial Number</td>
        <td>:</td>
        <td colspan="4" class="bold"><?= htmlspecialchars($ticket->nomor_seri ?: '-') ?></td>
    </tr>
</table>

<div class="section-title">B. KELUHAN & GEJALA KERUSAKAN</div>
<div class="box-catatan">
    <?= nl2br(htmlspecialchars($ticket->keluhan ?: 'Tidak ada keterangan keluhan tertulis.')) ?>
</div>

<div class="section-title">C. TINDAKAN PERBAIKAN / PENYELESAIAN TEKNISI</div>
<div class="box-catatan">
    <?= nl2br(htmlspecialchars($ticket->tindakan_perbaikan ?: 'Pengecekan teknis, uji fungsi, dan verifikasi operasional unit alat.')) ?>
</div>

<div class="section-title">D. RINCIAN SPAREPART / JASA PERBAIKAN</div>
<table class="table-data">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">Kategori</th>
            <th width="45%">Deskripsi</th>
            <th width="8%">Qty</th>
            <th width="12%">Satuan</th>
            <th width="15%">Biaya</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($ticket->items)) { ?>
            <tr>
                <td colspan="6" class="text-center" style="font-style: italic; padding: 10px;">Tidak ada penggantian sparepart berbayar / Dalam masa garansi.</td>
            </tr>
        <?php } else {
            $n = 1;
            foreach ($ticket->items as $it) { ?>
                <tr>
                    <td class="text-center"><?= $n++ ?></td>
                    <td class="text-center"><?= ucfirst($it->jenis) ?></td>
                    <td><?= htmlspecialchars($it->deskripsi) ?></td>
                    <td class="text-center"><?= $it->qty ?></td>
                    <td class="text-center"><?= htmlspecialchars($it->satuan) ?></td>
                    <td class="text-right">Rp <?= number_format($it->subtotal, 0, ',', '.') ?></td>
                </tr>
        <?php }
        } ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" class="text-right bold">TOTAL ESTIMASI BIAYA:</th>
            <th class="text-right bold">Rp <?= number_format($ticket->biaya_total, 0, ',', '.') ?></th>
        </tr>
    </tfoot>
</table>

<p style="font-size: 10pt; margin-top: 15px; line-height: 1.4;">
    Demikian lembar kerja dan berita acara after sales service ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
</p>

<table class="table-ttd">
    <tr>
        <td width="33%">
            Pelanggan / Pihak Rumah Sakit,<br><br><br><br><br>
            ( <strong><?= htmlspecialchars($ticket->pic_nama ?: '..............................') ?></strong> )<br>
            <span style="font-size: 9pt;">Nama & Tanda Tangan / Cap</span>
        </td>
        <td width="34%"></td>
        <td width="33%">
            Teknisi / PT. Visi Yosindo Medikal,<br><br><br><br><br>
            ( <strong><?= htmlspecialchars($ticket->teknisi_nama_lengkap ?: $ticket->nama_teknisi ?: 'Teknisi PT. VYM') ?></strong> )<br>
            <span style="font-size: 9pt;">Teknisi Penanggung Jawab</span>
        </td>
    </tr>
</table>
