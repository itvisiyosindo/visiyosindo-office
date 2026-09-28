<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Permintaan Stock Opname | <?= $this->config->item('apps_name') ?></title>
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

        @media print {

            /* Hanya untuk tabel dengan id #kt_table_1 */
            #kt_table_1 {
                border-collapse: collapse;
                /* Menggabungkan border ganda */
                width: 100%;
                /* Memastikan tabel memenuhi lebar halaman cetak */
            }

            #kt_table_1 th,
            #kt_table_1 td {
                border: 2px solid black !important;
                /* Border hitam tebal untuk cetak */
                padding: 8px;
                /* Menambah ruang di dalam sel */
                /*text-align: center;  Menyesuaikan posisi teks */
            }

            #kt_table_1 th {
                background-color: #eaeaea !important;
                /* Latar belakang header */
                -webkit-print-color-adjust: exact;
                /* Memastikan warna terlihat */
                print-color-adjust: exact;
            }
        }
    </style>
    <!-- end includes_css.php -->


    <!-- Head Libs -->
    <script src="<?= base_url('assets/') ?>vendor/modernizr/modernizr.js"></script>

</head>

<body>
    <section class="body" style="font-size: 14px;">
        <img src="assets/img/kop_new.jpg" alt="Logo" style="width: 100%;" />
        <div class="inner-wrapper" style="padding-top: 0px">
            <section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
                <!-- start: page -->
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <strong style="font-size: 30px;">Perintah Stock Opname</strong>
                        <table>
                            <tr>
                                <th>Nomor SPK </th>
                                <th>:&nbsp;&nbsp;&nbsp; <?= $stock_opname[0]->kode   ?></th>
                            </tr>
                            <tr>
                                <th>Tanggal SPK </th>
                                <th>:&nbsp;&nbsp;&nbsp; <?= date_view_format($stock_opname[0]->created_at)  ?></th>
                            </tr>
                            <tr>
                                <th>Tanggal Mulai</th>
                                <th>:&nbsp;&nbsp;&nbsp; <?= date_view_format($stock_opname[0]->tanggal_mulai)  ?></th>
                            </tr>
                            <tr>
                                <th>Gudang </th>
                                <th>:&nbsp;&nbsp;&nbsp; <?= $stock_opname[0]->nama_gudang   ?></th>
                            </tr>
                            <tr>
                                <th>Penanggung Jawab </th>
                                <th>:&nbsp;&nbsp;&nbsp; <?= $stock_opname[0]->nama_pengaju   ?></th>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <strong style="font-size: 20px;">Keterangan</strong><br><br>
                        <div class="address"><?= $stock_opname[0]->keterangan ?></div>
                    </div>
                </div>
                <hr style="border-top: 4px dashed">
                </hr>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                        <thead class="text-center">
                            <tr>
                                <th style="text-align: center;"> No </th>
                                <th style="text-align: center;"> Nama Barang</th>
                                <th style="text-align: center;"> Kts (Hitung) </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;  ?>
                            <?php foreach ($stock_opname_detail as $row) { ?>
                                <tr>
                                    <td class="no" style="text-align: center;"><?= $i++ ?></td>
                                    <td class="nama_barang"><?= $row->nama_barang ?></td>
                                    <td class="unit" style="text-align: center;">....... Pcs</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>



                <br><br>
                <?php
                $img_path     = "uploads/file_karyawan/ttd/";
                $ttdPelaksana        = $img_path . "ttd_" . $stock_opname[0]->id_pelaksana . ".png";
                $ttd1         = $img_path . "ttd_notyet2.png";
                $ttd2         = $img_path . "ttd_notyet2.png";

                if ($stock_opname[0]->ttd_1 == '1') {
                    $ttd1 = $img_path . "ttd_" . $stock_opname[0]->id_pengaju . ".png";
                } else if ($stock_opname[0]->ttd_1 == '2') {
                    $ttd1 = $img_path . "ttd_not.png";
                }
                if ($stock_opname[0]->ttd_2 == '1') {
                    $ttd2 = $img_path . "ttd_769.png";
                } else if ($stock_opname[0]->ttd_2 == '2') {
                    $ttd2 = $img_path . "ttd_not.png";
                }
                ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mt-3">
                            Pelaksana,
                        </div>
                        <br><br><br><br><br>
                        <div class="text-center">
                            <span><?= $stock_opname[0]->nama_pelaksana ?></span>
                            <hr style="border-top: 1px solid;margin:auto">
                            <div class="text-center"> <?= $stock_opname[0]->jabatan_pelaksana ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center mt-3">
                            Penanggung Jawab,
                        </div>
                        <br><br><br><br><br>
                        <div class="text-center">
                            <span><?= $stock_opname[0]->nama_pengaju ?></span>
                            <hr style="border-top: 1px solid;margin:auto">
                            <div class="text-center"> <?= $stock_opname[0]->jabatan_pengaju ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center mt-3">
                            Penanggung Jawab Teknis,
                        </div>
                        <br><br><br><br><br>
                        <div class="text-center">
                            <span>Fitri Andriani</span>
                            <hr style="border-top: 1px solid;margin:auto">
                            <div class="text-center"> Penanggung Jawab Teknis</div>
                        </div>
                    </div>
                </div>
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
        <img src="assets/img/kop_surat_bawah.jpg" alt="Logo" style="width: 100%;" />
    </section>
</body>

</html>