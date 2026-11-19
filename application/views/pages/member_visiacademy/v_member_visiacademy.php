<section class="panel">
    <header class="panel-heading">
        <div class="panel-actions">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-member">Tambah Member</button>
            <button class="btn btn-success" data-toggle="modal" data-target="#modal-deal">Input Deal</button>
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

<!-- Modal Tambah Member -->
<div id="modal-member" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Member</h5>
            </div>
            <?= form_open('Member_visiacademy/add', ['id' => 'form-member']); ?>
            <div class="modal-body">
                <div class="form-group"><label>Kode</label><input type="text" name="kode_member" class="form-control" required></div>
                <div class="form-group"><label>Nama</label><input type="text" name="nama_member" class="form-control" required></div>
                <div class="form-group"><label>No Telp</label><input type="text" name="no_telp" class="form-control"></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control"></div>
                <div class="form-group"><label>Institusi</label><input type="text" name="institusi_asal" class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Input Deal -->
<div id="modal-deal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Deal</h5>
            </div>
            <?= form_open('Member_visiacademy/addDeal', ['id' => 'form-deal']); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>Member</label>
                    <select name="id_member" class="form-control" required>
                        <?php foreach($members as $m): ?>
                            <option value="<?= $m['id_member'] ?>"><?= $m['nama_member'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Produk</label><input type="text" name="nama_produk" class="form-control" required></div>
                <div class="form-group"><label>Estimasi Nilai</label><input type="number" name="estimasi_nilai" class="form-control" required></div>
                <div class="form-group"><label>Persen Komisi (%)</label><input type="number" name="persen_komisi" class="form-control" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
</script>
