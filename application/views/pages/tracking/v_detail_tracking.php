<?php
// Determine tracking type
$tracking_type = isset($data_tracking[0]->tracking_type) ? $data_tracking[0]->tracking_type : 'pengeluaran_barang';
$is_pengiriman_stok = ($tracking_type == 'pengiriman_stok');
$is_serah_terima_barang = ($tracking_type == 'serah_terima_barang');
$is_kirim_dokumen = ($tracking_type == 'kirim_dokumen');

// Check if admin
$is_admin = in_array(sessPenggunaId(), [1, 15, 33, 7]);
$id_tracking_enc = encrypt($data_tracking[0]->id_tracking);
?>

<header class="page-header">
    <h2><i class="icons fas fa-truck"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<!-- Tombol Kembali di atas konten -->
<div class="mb-3">
    <a href="<?= base_url('tracking') ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<div class="col-xl-8 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:5%">
        <div class="text-center mt-0">
            <?php if ($is_pengiriman_stok): ?>
                <span class="badge badge-warning mb-2">Tracking Pengiriman Stok (Antar Gudang)</span>
                <h3><strong>Data Tracking Pemindahan Stok</strong></h3>
                <h3>No Pemindahan: <?= $data_tracking[0]->no_sj ?></h3>
            <?php elseif ($is_serah_terima_barang): ?>
                <span class="badge badge-info mb-2">Tracking Serah Terima Barang (STTB)</span>
                <h3><strong>Data Tracking Serah Terima Barang</strong></h3>
                <h3>Kode STTB: <?= isset($data_tracking[0]->kode_stb) ? $data_tracking[0]->kode_stb : $data_tracking[0]->no_sj ?></h3>
            <?php elseif ($is_kirim_dokumen): ?>
                <span class="badge badge-dark mb-2">Tracking Kirim Dokumen</span>
                <h3><strong>Data Tracking Kirim Dokumen</strong></h3>
                <h3>Kode: <?= isset($data_tracking[0]->kode_kirim_dokumen) ? $data_tracking[0]->kode_kirim_dokumen : $data_tracking[0]->no_sj ?></h3>
                <?php /* COMMENTED OUT: penerimaan_stok - diganti dengan serah_terima_barang
            elseif ($is_penerimaan_stok): ?>
                <span class="badge badge-info mb-2">Tracking Penerimaan Stok (Antar Gudang)</span>
                <h3><strong>Data Tracking Penerimaan Stok</strong></h3>
                <h3>No Penerimaan: <?= $data_tracking[0]->no_sj ?></h3>
            <?php */ ?>
            <?php else: ?>
                <span class="badge badge-primary mb-2">Tracking Pengeluaran Barang</span>
                <h3><strong>Data Tracking Barang</strong></h3>
                <h3>No Surat Jalan: <?= $data_tracking[0]->no_sj ?></h3>
            <?php endif; ?>

            <?php if ($is_admin || sessPenggunaId() == 7 || sessPenggunaId() == 749 || sessPenggunaId() == 763 || sessPenggunaId() == 769): ?>
                <div class="mt-3">
                    <a href="<?= base_url('tracking/edit_full/' . $id_tracking_enc) ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> Edit Full (Admin)
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <br><br>

        <div class="row mb-3">
            <div class="col-md-6">
                <?php if ($is_serah_terima_barang): ?>
                    <strong>Pihak Pertama (Pemberi):</strong><br>
                    <?= isset($data_tracking[0]->nama_pihak1_stb) ? $data_tracking[0]->nama_pihak1_stb : '-' ?>
                <?php elseif ($is_kirim_dokumen): ?>
                    <strong>Marketing:</strong><br>
                    <?= isset($data_tracking[0]->marketing_kirim) ? $data_tracking[0]->marketing_kirim : '-' ?>
                    <?php /* COMMENTED OUT: penerimaan_stok - diganti dengan serah_terima_barang
                elseif ($is_penerimaan_stok): ?>
                    <strong>Gudang Asal (Pengirim):</strong><br>
                    <?= isset($data_tracking[0]->gudang_asal_penerimaan) ? $data_tracking[0]->gudang_asal_penerimaan : '-' ?>
                <?php */ ?>
                <?php else: ?>
                    <strong>Gudang Pengirim:</strong><br>
                    <?= $data_tracking[0]->nama_gudang ?>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <?php if ($is_pengiriman_stok): ?>
                    <strong>Gudang Tujuan:</strong><br>
                    <?= isset($data_tracking[0]->gudang_tujuan) ? $data_tracking[0]->gudang_tujuan : '-' ?>
                <?php elseif ($is_serah_terima_barang): ?>
                    <strong>Pihak Kedua (Penerima):</strong><br>
                    <?= isset($data_tracking[0]->nama_pihak2_stb) ? $data_tracking[0]->nama_pihak2_stb : '-' ?>
                <?php elseif ($is_kirim_dokumen): ?>
                    <strong>Nama Customer:</strong><br>
                    <?= isset($data_tracking[0]->nama_customer_kirim) ? $data_tracking[0]->nama_customer_kirim : '-' ?>
                    <?php /* COMMENTED OUT: penerimaan_stok - diganti dengan serah_terima_barang
                elseif ($is_penerimaan_stok): ?>
                    <strong>Gudang Tujuan (Penerima):</strong><br>
                    <?= isset($data_tracking[0]->gudang_tujuan_penerimaan) ? $data_tracking[0]->gudang_tujuan_penerimaan : '-' ?>
                <?php */ ?>
                <?php else: ?>
                    <strong>Nama Customer:</strong><br>
                    <?= $data_tracking[0]->nama_customer ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-3">
            <strong>PIC Penerima:</strong><br>
            <?= $data_tracking[0]->pic_penerima ?>
        </div>

        <div class="mb-3">
            <strong>Alamat Penerima:</strong><br>
            <?= nl2br(htmlspecialchars($data_tracking[0]->alamat_penerima)) ?>
        </div>

        <div class="mb-3">
            <strong>Ekspedisi:</strong><br>
            <?= $data_tracking[0]->nama_ekspedisi ?>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Tanggal Pengiriman:</strong><br>
                <?= date('d-M-Y', strtotime($data_tracking[0]->tgl_pengiriman)); ?>
            </div>
            <div class="col-md-6">
                <strong>Tanggal Estimasi Sampai:</strong><br>
                <?= date('d-M-Y', strtotime($data_tracking[0]->tgl_sampai)); ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>No Resi:</strong> <?= $data_tracking[0]->no_resi ?>
            </div>
            <div class="col-md-6">
                <strong>Link Resi:</strong>
                <?php if (!empty($data_tracking[0]->link_resi)) : ?>
                    <a href="<?= $data_tracking[0]->link_resi ?>"
                        target="_blank"
                        class="btn btn-sm btn-info ms-2">
                        🔗 Cek Resi
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-3">
            <strong>Keterangan Lainnya:</strong><br>
            <?= nl2br(htmlspecialchars($data_tracking[0]->keterangan)) ?>
        </div>

        <?php if ($data_status[0]->id_status == 1) {
            $status = "Proses Kirim";
        } else if ($data_status[0]->id_status == 2) {
            $status = "Manifest Berangkat";
        } else if ($data_status[0]->id_status == 3) {
            $status = "Proses Sortir";
        } else if ($data_status[0]->id_status == 4) {
            $status = "Pengantaran Kurir";
        } else if ($data_status[0]->id_status == 5) {
            $status = "Diterima";
        } else if ($data_status[0]->id_status == 6) {
            $status = "Menunggu Konfirmasi";
        }

        ?>

        <div class="mb-3">
            <strong>Status Tracking Barang:</strong><br>
            <div class="alert alert-info mt-2 mb-0 py-2 px-3" role="alert">
                <i class="bi bi-truck me-2"></i> <?= $status ?>
            </div>
            <span>Cek History Tracking Barang Dibawah</span>
        </div>


        <?php
        // Check if there's detail barang to display
        $has_detail_barang = !empty($detail_barang_keluar);
        $has_manual_barang = !empty($data_tracking[0]->nama_barang);

        if (!$has_detail_barang && $has_manual_barang): ?>
            <div class="mb-3">
                <strong>Detail Barang:</strong><br>
                <?= nl2br(htmlspecialchars($data_tracking[0]->nama_barang)) ?>
            </div>

        <?php elseif ($has_detail_barang): ?>

            <div class="card-body">
                <div class="table-responsive">
                    <strong> Detail Barang :</strong>
                    <?php if ($is_serah_terima_barang): ?>
                        <!-- Table untuk Serah Terima Barang (STTB) -->
                        <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th> Nama Barang</th>
                                    <th> Merk</th>
                                    <th> No Batch </th>
                                    <th> Kuantitas </th>
                                    <th> Satuan </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1 ?>
                                <?php foreach ($detail_barang_keluar as $row) { ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= isset($row->nama_barang) ? $row->nama_barang : '-' ?></td>
                                        <td><?= isset($row->merk) ? $row->merk : '-' ?></td>
                                        <td><?= isset($row->no_batch) ? $row->no_batch : '-' ?></td>
                                        <td><?= isset($row->qty) ? $row->qty : '-' ?></td>
                                        <td><?= isset($row->satuan) ? $row->satuan : '-' ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <!-- Table untuk Pengeluaran Barang / Pengiriman Stok -->
                        <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th> Nama Barang</th>
                                    <th> No AKL</th>
                                    <th> Kuantitasi </th>
                                    <th> No Batch </th>
                                    <th> Exp Date </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1 ?>
                                <?php foreach ($detail_barang_keluar as $row) {
                                    // Handle different field names for pengiriman_stok vs pengeluaran_barang
                                    $id_field = isset($row->id_detail_barang_keluar) ? $row->id_detail_barang_keluar : (isset($row->id_detail_barang_pengiriman_stok) ? $row->id_detail_barang_pengiriman_stok : null);
                                    $nie_value = isset($row->nie) ? $row->nie : '-';
                                    $exp_date_value = isset($row->exp_date) ? $row->exp_date : null;
                                ?>
                                    <tr>
                                        <td> <input type="hidden" value="<?= $id_field ?>"><?= $i++ ?></td>
                                        <td> <input type="hidden" value="<?= $row->id_barang ?>"><?= $row->nama_barang ?> </td>
                                        <td> <input type="hidden" value="<?= $nie_value ?>"><?= $nie_value ?> </td>
                                        <td> <input type="hidden" value="<?= $row->qty ?>"> <?= $row->qty ?></td>
                                        <td> <input type="hidden" value="<?= $row->no_batch ?>"> <?= $row->no_batch ?></td>
                                        <td> <input type="hidden" value="<?= $exp_date_value ?? NULL ?>"> <?= $exp_date_value && date('Y', strtotime($exp_date_value)) >= 2000 ? date('d-m-Y', strtotime($exp_date_value)) : '-' ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>





        <?= form_open('tracking/update', array('id' => 'main-form', 'autocomplete' => 'off')); ?>

        <?php if (sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 85 || sessPenggunaId() == 7 || sessPenggunaId() == 73 || sessPenggunaId() == 749 || sessPenggunaId() == 763 || sessPenggunaId() == 769) { ?>

            <!-- value preview -->
            <div id="value_preview">
                <br><br>
                <div class="form-group">
                    <label for="status" class="form-control-label">Update Status <span class="text-danger">*</span> :</label>
                    <select class="form-control" id="status" name="status" required onchange="toggleFormStatus()">
                        <option value="">- Pilih Status -</option>
                        <option value="1">Proses Kirim</option>
                        <option value="2">Manifest Berangkat</option>
                        <option value="3">Proses Sortir</option>
                        <option value="6">Menunggu Konfirmasi</option>
                        <option value="4">Pengantaran Kurir</option>
                        <option value="5">Diterima</option>
                    </select>
                    <div id="danger-alert">Di Update oleh Warehouse</div>
                </div>

                <div class="form-group" id="form_nama_penerima" style="display:none;">
                    <label for="nama_penerima" class="form-control-label">Nama Penerima <span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" id="nama_penerima" name="nama_penerima" required>
                </div>
                <div class="form-group" id="form_tgl_penerima" style="display:none;">
                    <label for="tgl_penerima" class="form-control-label">Tanggal Penerimaan <span class="text-danger">*</span> :</label>
                    <div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
                        <span class="input-group-text">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <input type="text" class="form-control" id="tgl_penerima" name="tgl_penerima" required>
                    </div>
                </div>
                <div class="form-group" id="form_bukti_penerima" style="display:none;">
                    <label for="bukti_penerima" class="form-control-label">Bukti Penerimaan <span class="text-danger">*</span> :</label>
                    <textarea type="text" class="form-control" id="bukti_penerima" name="bukti_penerima" required></textarea>
                </div>


                <div class="form-group" id="form_keterangan_konfirmasi" style="display:none;">
                    <label for="keterangan_konfirmasi" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
                    <textarea type="text" class="form-control" id="keterangan_konfirmasi" name="keterangan_konfirmasi" required></textarea>
                </div>


            </div>
        <?php } ?>
        <!-- end value preview -->
        <br>
        <div class="row-action-buttons">
            <!--<button type="button" id="btn-show-add-form" class="btn btn-primary btn-clear-form" data-id="<? //= encrypt($data_tracking[0]->id_tiket) 
                                                                                                                ?>">Report Note</button>-->
            <input type="hidden" name="id_tracking" id="id_tracking" value="<?= encrypt($data_tracking[0]->id_tracking) ?>">
            <?php if (sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 85  || sessPenggunaId() == 7 || sessPenggunaId() == 73 || sessPenggunaId() == 749 || sessPenggunaId() == 763 || sessPenggunaId() == 769) { ?>
                <button type="button" class="btn btn-success btn-save float-right" style="margin-left: 12px;">Simpan</button>
                <label type="hidden" id="cek_login" value="1"></label>
            <?php } ?>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" data-dismiss="modal">Kembali</button>
        </div>

        <br>
        <br>






        <!-- Section: Timeline  
            <div class="container my-5">-->
        <div class="row">
            <div class="col-md-12 offset-md-0">
                <h4 style="margin-left: 1.2rem;"><b>History Tracking</b></h4>
                <ul class="timeline-3">
                    <?php
                    foreach ($data_status as $each) {

                        $dari = date_create($each->created_at);
                        $sampai = date_create();
                        $diff  = date_diff($dari, $sampai); //untuk menghitung hari
                        // echo $diff->d . ' Hari, ';                         <span><i class="fa fa-clock-o mr-1"></i>21 March, 2019</span>

                        if ($each->id_status == 1) {
                            $status = "Proses Kirim";
                        } else if ($each->id_status == 2) {
                            $status = "Manifest Berangkat";
                        } else if ($each->id_status == 6) {
                            $status = "Menunggu Konfirmasi";
                        } else if ($each->id_status == 3) {
                            $status = "Proses Sortir";
                        } else if ($each->id_status == 4) {
                            $status = "Pengantaran Kurir";
                        } else if ($each->id_status == 5) {
                            $status = "Diterima";
                        }

                    ?>

                        <li>
                            <a><b><?php echo  $status ?></b></a>
                            <a class="float-right"><?php echo date('d-M-Y | H:i:s', strtotime($each->created_at)) ?></a>
                            <p class="mt-2"><?php echo "Update oleh : " . $each->nama_pembuat; ?></p>

                            <?php if ($each->keterangan_konfirmasi != "") { ?>
                                <a><b><?php echo  "Keterangan : " . $each->keterangan_konfirmasi; ?></b></a>
                            <?php } ?>

                            <?php if ($each->nama_penerima != "") { ?>
                                <a><b><?php echo  "Nama Penerima : " . $each->nama_penerima; ?></b></a>
                            <?php } ?>

                            <br>
                            <?php if ($each->tgl_penerima != "") { ?>
                                <a class="float-left"><?php echo "Tanggal Penerimaan : " . date('d-M-Y', strtotime($each->tgl_penerima)); ?></a>

                            <?php } ?>
                            <br><br>
                            <?php if ($each->bukti_penerima != "") { ?>
                                <a href="<?= $each->bukti_penerima ?>" target="blank" class="btn btn-primary float-left" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Bukti Penerimaan </a>
                                <br><br>
                            <?php } ?>
                            <!--<font color='#22c0e8'><?php echo  $status ?></font>-->
                            <!--<br><br>-->

                        </li>

                    <?php } ?>
                </ul>
            </div>
        </div>
        <!-- </div>
            Section: Timeline 
            <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">  -->
        <link rel="stylesheet" href="assets/css/timeline.css">









        <?= form_close(); ?>
    </div>
</div>



<script>
    function toggleFormStatus() {
        var status = document.getElementById("status").value;
        var formDiantarkan = document.getElementById("form_nama_penerima");
        var formDikirim = document.getElementById("form_tgl_penerima");
        var formBukti = document.getElementById("form_bukti_penerima");
        var formKet = document.getElementById("form_keterangan_konfirmasi");

        if (status === "5") {
            formDiantarkan.style.display = "block";
            formDikirim.style.display = "block";
            formBukti.style.display = "block";
            formKet.style.display = "none";
        } else {
            formDiantarkan.style.display = "none";
            formDikirim.style.display = "none";
            formBukti.style.display = "none";
            formKet.style.display = "block";
        }
    }


    document.addEventListener('DOMContentLoaded', function() {


        var prioritas = $('#id_prioritas').val();
        $('#prioritas option[value="' + prioritas + '"]').prop("selected", true).trigger('change')

        var kategori = $('#id_topik').val();
        $('#kategori option[value="' + kategori + '"]').prop("selected", true).trigger('change')

        var penerima = $('#id_penerima').val();
        $('#agent option[value="' + penerima + '"]').prop("selected", true).trigger('change')

        var status_tiket = $('#id_status_tiket').val();
        $('#status_tiket option[value="' + status_tiket + '"]').prop("selected", true).trigger('change')

        Array.prototype.forEach.call(document.getElementById('cek_login') ? [document.getElementById('cek_login')] : [],
            function(elem) {
                elem.addEventListener('change', function() {
                    let text = this.value;

                    if (text != "1") {
                        $("#pelanggan").attr("readonly", true);
                        $("#subject").attr("readonly", true);
                        $("#kategori").attr("readonly", true);
                        $("#agent").attr("readonly", true);
                        $("#deskripsi").attr("readonly", true);
                        $("#file_pendukung").attr("readonly", true);
                        $("#start").attr("readonly", true);
                        $("#end").attr("readonly", true);
                    }
                });
            });



        //show modal add respon
        $('#btn-show-add-form').click(function() {
            $('.respon').val(null)
            $('.btn-isactive').remove()
            var id = $(this).data('id');
            $("#main-modal #id").val(id);
            var object = 'tiket'
            $('#main-modal #modal-form').attr('action', 'tiket/add/respon')
            $('#main-modal').modal()
        })

        $.ajax({
            url: "tiket/pagination/respon",
            type: "POST",
            cache: false,
            data: {
                id_tiket: $('#id_tiket').val(),
                csrf_token: token
            },
            success: function(data) {
                //alert(data);
                $('#respon').html(data);
            }
        })
    })

    var textAreas = document.getElementsByTagName('textarea');

    Array.prototype.forEach.call(textAreas, function(elem) {
        elem.placeholder = elem.placeholder.replace(/\\n/g, '\n');
        elem.value = elem.value.replace(/\\n/g, '\n');
    });

    function goBack() {
        window.history.back();
    }
</script>