<section class="panel">
    <header class="panel-heading">
        <div class="panel-actions">
            <button class="btn btn-primary" onclick="showModal('Member Visi Academy/add')">Tambah Member</button>
            <button class="btn btn-success" onclick="showModal('Member Visi Academy/addDeal')">Input Deal</button>
        </div>
        <h2 class="panel-title">Data Member Visi Academy</h2>
    </header>
    <div class="panel-body">
        <table class="table table-bordered table-striped" id="datatable-default">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>No Telp</th>
                    <th>Institusi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $m) : ?>
                <tr>
                    <td><?= $m['kode_member'] ?></td>
                    <td><?= $m['nama_member'] ?></td>
                    <td><?= $m['no_telp'] ?></td>
                    <td><?= $m['institusi_asal'] ?></td>
                    <td><?= $m['status'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Data Deal Member</h2>
    </header>
    <div class="panel-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Produk</th>
                    <th>Nilai</th>
                    <th>Komisi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deals as $d) : ?>
                <tr>
                    <td><?= $d['nama_member'] ?></td>
                    <td><?= $d['nama_produk'] ?></td>
                    <td><?= number_format($d['estimasi_nilai']) ?></td>
                    <td><?= number_format($d['nominal_komisi']) ?></td>
                    <td><?= $d['status_deal'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
