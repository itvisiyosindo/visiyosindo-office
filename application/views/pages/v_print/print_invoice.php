<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Print Invoice | <?= $this->config->item('apps_name') ?></title>
    <meta name="keywords" content="Sistem Informasi" />
    <meta name="description" content="<?= $this->config->item('apps_name') ?>">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- includes_css.php -->
    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,300,400,600,700,800,900" rel="stylesheet" type="text/css">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/animate/animate.compat.css">

    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/boxicons/css/boxicons.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />


    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.theme.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/select2/css/select2.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/dropzone/basic.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/dropzone/dropzone.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/pnotify/pnotify.custom.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/datatables/media/css/dataTables.bootstrap4.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/simple-line-icons/css/simple-line-icons.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>js/sweetalert2/sweetalert2.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>js/croppie/croppie.css" />

    <!--(remove-empty-lines-end)-->

    <!-- Theme CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>css/theme.css" />



    <!-- Theme Layout -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>css/layouts/modern.css" />
    <!--(remove-empty-lines-end)-->



    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>css/custom.css">
    <link rel="shortcut icon" href="<?= base_url('assets/') ?>img/favicon.png" />
    <!-- Head Libs -->

    <style>
        html.modern html,
        html.modern body {
            background: none !important;
        }

        .page-header {
            background: #1D2127 !important;
        }

        .header .logo-container {
            background-image: none;
            background-color: #1D2127;
            border-bottom-color: #161a1e;
            border-top-color: #1D2127;
            /* background-color: #1D2127; */

        }

        .modal-header {
            padding: 5px 5px 5px 20px;
        }

        @media only screen and (min-width: 768px) {
            html.modern .header:not(.header-nav-menu) .logo {
                line-height: 20px;
                padding: 5px 20px 0 15px;
                font-size: 18px
            }
        }

        @media (max-width: 767px) {
            html.modern .header .logo-container .logo {
                margin-top: 10px;
                line-height: 20px;
                font-size: 18px
            }
        }
    </style>
    <!-- end includes_css.php -->


    <!-- Head Libs -->
    <script src="<?= base_url('assets/') ?>vendor/modernizr/modernizr.js"></script>

</head>

<body>
    <section class="body" style="font-size: 14px;">
        <img src="assets/img/kop_surat.png" alt="Logo" style="width: 100%;" />
        <div class="inner-wrapper" style="padding-top: 0px">
            <section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
                <!-- start: page -->
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Kepada </strong> : <br><br>
                        <?= $invoice[0]->nama_customer ?> <br><br>
                        <?= $invoice[0]->alamat_customer ?><br>
                        <?= $invoice[0]->contact ?>
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col-md-5">
                        <strong style="font-size: 30px;">INVOICE</strong><br><br>
                        <table>
                            <tr>
                                <th>No Invoice &nbsp;&nbsp;&nbsp;</th>
                                <th>:&nbsp;&nbsp;&nbsp; <?= $invoice[0]->no_invoice ?></th>
                            </tr>
                            <tr>
                                <th>Tanggal &nbsp;&nbsp;&nbsp;</th>
                                <th>:&nbsp;&nbsp;&nbsp; <?= date_view_format($invoice[0]->tgl_invoice) ?></th>
                            </tr>
                            <tr>
                                <th>No PO &nbsp;&nbsp;&nbsp;</th>
                                <th>:&nbsp;&nbsp;&nbsp; <?= $invoice[0]->no_po ?></th>
                            </tr>
                            <tr>
                                <th>Syarat Pembayaran &nbsp;&nbsp;&nbsp;</th>
                                <th>:&nbsp;&nbsp;&nbsp; <?= $invoice[0]->nama_syarat_pembayaran ?></th>
                            </tr>
                            <tr>
                                <th>Salesperson &nbsp;&nbsp;&nbsp;</th>
                                <th>:&nbsp;&nbsp;&nbsp; <?= $invoice[0]->nama_markerting ?></th>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr style="border-top: 4px dashed">
                </hr>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                        <thead class="text-center">
                            <tr>
                                <th> No </th>
                                <th> Kode Barang</th>
                                <th> Nama Barang </th>
                                <?= $batch == 1 ? '<th> Batch </th>' : '' ?>
                                <?= $batch == 1 ? '<th> Exp date </th>' : '' ?>
                                <th> Qty </th>
                                <th> Satuan </th>
                                <th> Harga Satuan </th>
                                <th> Discount Satuan </th>
                                <th> Total Harga </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;  ?>
                            <?php foreach ($detail_barang_invoice as $row) { ?>
                                <?php $total_qty[] = $row->qty ?>
                                <tr>
                                    <td class="no"><?= $i++ ?></td>
                                    <td class="kode-barang"><?= $row->kode_barang ?></td>
                                    <td class="unit"><?= $row->nama_barang ?></td>
                                    <?= $batch == 1 ? "<td>". $row->no_batch ."</td>" : '' ?>
                                    <?= $batch == 1 ? "<td>". $row->exp_date ."</td>" : '' ?>
                                    <td class=""><?= $row->qty ?></td>
                                    <td class=""><?= $row->nama_satuan ?></td>
                                    <?php if ($pajak == 1) { ?>
                                        <td class=""><?= rupiah($row->harga) ?></td>
                                    <?php } else { ?>
                                        <td class=""><?= rupiah($row->harga * $invoice[0]->persentase / 100 + $row->harga) ?></td>
                                    <?php } ?>
                                    <td class=""><?= $row->discount ? rupiah($row->discount) : '' ?></td>
                                    <?php if ($pajak == 1) { ?>
                                        <td class=""><?= rupiah($row->harga_total) ?></td>
                                    <?php } else { ?>
                                        <td class=""><?= rupiah($row->harga_total * $invoice[0]->persentase / 100 + $row->harga_total) ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <!-- <?= isset($invoice[0]->nama_customer) ?><br>
                        <?= isset($no_va[0]->no_va) ?>
                        <?= isset($no_va[0]->nama_bank) ?> -->
                    </div>
                    <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                                <thead>
                                    <tr>
                                        <?php $total_harga_sum = [];
                                        foreach ($detail_barang_invoice as $row) {
                                            array_push($total_harga_sum, $pajak == 1 ? $row->harga_total : $row->harga_total * $invoice[0]->persentase / 100 + $row->harga_total);
                                        }  ?>
                                        <th> Sub Total </th>
                                        <th class="text-right"><?= rupiah(array_sum($total_harga_sum)) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($pajak == 1) { ?>
                                        <tr>
                                            <th> <?= $invoice[0]->nama_pajak . ' (' . $invoice[0]->persentase .  '%)' ?> </th>
                                            <th class="text-right"> <?= rupiah(array_sum($total_harga_sum) *  $invoice[0]->persentase / 100) ?></th>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th> Biaya Pengiriman </th>
                                        <th class="text-right"> <?= $invoice[0]->ongkir ? rupiah($invoice[0]->ongkir) : 0 ?></th>
                                    </tr>
                                    <tr>
                                        <th> Total </th>
                                        <th class="text-right"> <?= rupiah($invoice[0]->total_keseluruhan) ?></th>
                                    </tr>
                                </tbody>
                            </table>
                            <?php if ($pajak == 0) { ?>
                                <small>*Harga sudah termasuk pajak</small>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-4"><strong class="text-light">Pembayaran Mohon Transfer ke Rekening PT. Visi Yosindo Medikal : </strong><br>
                        <?php if ($no_va) { ?>
                            <div class="row" style="border: 1px solid black">
                                <?php foreach ($no_va as $row) { ?>
                                    <div class="col-md-4">
                                        <strong><?= $row->nama_bank ?></strong>
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <strong>: <?= $row->no_va ?></strong>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <br><br><br><br><br>
                        <div class="text-center">
                            <span class="text-light">Meilina Safitri</span>
                            <hr style="border-top: 1px solid;margin:auto"><br>
                        </div>
                    </div>
                </div><br>
                <?php if ($ttd_digital == 1) { ?>
                    <div class="text-left">
                        <span class="text-light">*Dokumen ini ditandatangani secara elektronik dengan menggunakan sertifikat elektronik yang di terbitkan oleh Badan Pengkajian dan Penerapan Teknologi sesuai dengan UU Nomor 11 Tahun 2008 tentang Informasi dan Transaksi Elektronika</span>
                    </div>
                <?php } ?>

                <script>
                    window.onload = function() {
                        window.print();
                    }
                </script>
                <!-- end: page -->
            </section>
            <!-- end includes_js.php -->
            <!-- Vendor -->
            <script src="<?= base_url('assets/') ?>vendor/jquery/jquery.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/jquery-cookie/jquery.cookie.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/popper/umd/popper.min.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/common/common.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/nanoscroller/nanoscroller.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/magnific-popup/jquery.magnific-popup.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/jquery-placeholder/jquery.placeholder.js"></script>

            <!-- Specific Page Vendor -->
            <script src="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/jquery-validation/jquery.validate.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/select2/js/select2.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/dropzone/dropzone.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/pnotify/pnotify.custom.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/datatables/media/js/jquery.dataTables.min.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>
            <script src="<?= base_url('assets/') ?>js/global.js"></script>
            <script src="<?= base_url('assets/') ?>js/jquery.mask.min.js"></script>
            <script src="<?= base_url('assets/') ?>js/table2excel.min.js"></script>
            <script src="<?= base_url('assets/') ?>/js/sweetalert2/sweetalert2.min.js"></script>
            <script src="<?= base_url('assets/') ?>/js/chart/chart.min.js"></script>
            <script src="<?= base_url('assets/') ?>/js/ckeditor/ckeditor.js"></script>
            <script src="<?= base_url('assets/') ?>/js/croppie/croppie.js"></script>

            <!--(remove-empty-lines-end)-->

            <!-- Theme Base, Components and Settings -->
            <script src="<?= base_url('assets/') ?>js/theme.js"></script>


            <!-- Theme Initialization Files -->
            <script src="<?= base_url('assets/') ?>js/theme.init.js"></script>

            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

            <script>
                let token = $('input[name=token]').val()
                // Maintain Scroll Position
                if (typeof localStorage !== 'undefined') {
                    if (localStorage.getItem('sidebar-left-position') !== null) {
                        var initialPosition = localStorage.getItem('sidebar-left-position'),
                            sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                        sidebarLeft.scrollTop = initialPosition;
                    }
                }
            </script>
        </div>
        <!-- includes_js.php -->
    </section>
</body>

</html>