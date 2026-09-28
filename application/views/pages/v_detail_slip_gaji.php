<header class="page-header">
    <h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div class="col-xl-8 mb-8 mb-xl-0;" style="margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:5%">
        <div class="text-center">
            <h2>Slip Gaji <?= $pengguna[0]->nama ?></h2>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- -- -->
                <div class="row">
                    <div class="col-md-2">Nama</div>
                    <div class="col-md-6 text-left">: <?= $pengguna[0]->nama ?></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Jabatan</div>
                    <div class="col-md-6 text-left">: <?= $pengguna[0]->jabatan ?></div>
                </div>
                <div class="row">
                    <div class="col-md-2">Status</div>
                    <div class="col-md-6 text-left">: <?= $pengguna[0]->status_karyawan ?></div>
                </div>
                <!-- -- -->
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-6">
            <strong>Salary Bulan ini : </strong>
                <!-- -- -->
                <div class="row">
                    <div class="col-md-6">Gaji Pokok</div>
                    <div class="col-md-6 text-left">: Rp <?= rupiah($current_salary[0]->total_gaji_pokok) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6">Tunjangan Jabatan</div>
                    <div class="col-md-6 text-left">: Rp <?= rupiah($current_salary[0]->total_tunjangan_jabatan) ?></div>
                </div>
                <!-- -- -->
            </div>
            <div class="col-md-6">
            <strong>Total Potongan Bulan ini : </strong>
                <!-- -- -->
                <div class="row">
                    <div class="col-md-6">Gaji Pokok</div>
                    <div class="col-md-6 text-left">: Rp <?= isset($riwayat_potongan[0]->potongan_gaji_pokok) ? rupiah($riwayat_potongan[0]->potongan_gaji_pokok) : 0 ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6">Tunjangan Jabatan</div>
                    <div class="col-md-6 text-left">: Rp <?= isset($riwayat_potongan[0]->potongan_tunjangan_jabatan) ? rupiah($riwayat_potongan[0]->potongan_tunjangan_jabatan) : 0 ?></div>
                </div>
                <!-- -- -->
            </div>
        </div><br>
        <!-- -- -->
        <strong>Detail Potongan Bulan ini : </strong>
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                <thead>
                    <tr>
                        <th> # </th>
                        <th> Total Potongan Gapok</th>
                        <th> Total Potongan T.Jabatan</th>
                        <th> Total Potongan T.Kinerja</th>
                        <th> Total Potongan T.Konsumsi</th>
                        <th> Per Tanggal</th>
                    </tr>
                    <?php $no = 1 ?>
                    <?php foreach ($riwayat_potongan as $row) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>- Rp <?= rupiah($row->potongan_gaji_pokok) ?></td>
                            <td>- Rp <?= rupiah($row->potongan_tunjangan_jabatan) ?></td>
                            <td><?= date_view_format($row->data_created) ?></td>
                        </tr>
                    <?php } ?>
                </thead>
            </table>
        </div>
        <!-- -- -->
    </div>
</div>