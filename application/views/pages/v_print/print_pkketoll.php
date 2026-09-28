<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Pengajuan Klaim Kas | <?= $this->config->item('apps_name') ?></title>
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
            background: white !important;
            //color: red;
            //color: rgb(0, 0, 0);
            color: #d41c0f;
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
            padding: 0px 5px 5px 5px;
        }

        @media only screen and (min-width: 768px) {
            html.modern .header:not(.header-nav-menu) .logo {
                line-height: 0px;
                padding: 5px 20px 0 15px;
                font-size: 18px
            }
        }

        @media (max-width: 767px) {
            html.modern .header .logo-container .logo {
                margin-top: 0px;
                line-height: 0px;
                font-size: 18px
            }
        }

        hr {
            display: block;
            margin-top: 0em;
            margin-bottom: 0em;
            margin-left: auto;
            margin-right: auto;
            border-top: 2px solid black;
        }

        .mod-u {
            text-decoration-line: underline;
            text-underline-position: under;
            text-decoration-style: solid;
            text-decoration-color: black;
            text-decoration-thickness: 5px;
        }

        .mylabel {
            border: 1px solid blue;
            display: table-cell;
            width: 100%;
        }

        .mylabel2 {
            border: 1px solid blue;
            display: table-cell;
            width: 20%;
        }
    </style>
    <!-- end includes_css.php -->


    <!-- Head Libs -->
    <script src="<?= base_url('assets/') ?>vendor/modernizr/modernizr.js"></script>

</head>

<body style="background-color:white; font-color:black;">
    <section class="body" style="padding-top:0px;">
        <img src="assets/img/kop_baru.jpg" alt="Logo" style="width: 100%;" />
        <hr>
        </hr>
        <hr>
        </hr>
        <div class="inner-wrapper" style="padding-top: 0px">
            <section role="main" class="content-body content-body-modern" style="padding-top:13px; padding-bottom:0px;">
                <!-- start: page -->
                <table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
                    <tr>
                        <th width="32%"></th>
                        <th colspan="" style="height:27px; text-align:center; vertical-align:top;">
                            <font color='#000000'> PENGAJUAN KLAIM KAS
                                <hr>
                                </hr>
                                <hr>
                                </hr>
                        </th>
                        <th width="32%"></th>
                    </tr>
                    <tr>
                        <th colspan="3" style="text-align:center">
                            <font color='#000000'> No : <?= $data_pkk[0]->kodePKK ?>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <font color="white">i </font>
                        </td>
                    </tr>
                </table>

                <table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
                    <tr>
                        <td colspan="3" style="text-align:left">
                            <font color='#000000'> Dengan ini saya mengajukan biaya sebagai berikut :
                        </td>
                    </tr>
                    <tr>
                        <!-- <td rowspan="8" width="7%"></td> -->
                    <tr>
                        <!-- <td width="5%"></td> -->
                        <td width="22%" colspan="2">
                            <font color='#000000'> Nama
                        </td>
                        <td>
                            <font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->nama ?>
                        </td>
                    </tr>
                    <tr>
                        <!-- <td width="5%"></td> -->
                        <td colspan="2">
                            <font color='#000000'> Jabatan
                        </td>
                        <td>
                            <font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->jabatan ?>
                        </td>
                    </tr>
                    <tr>
                        <!-- <td width="5%"></td> -->
                        <td colspan="2">
                            <font color='#000000'> Kas
                        </td>
                        <td>
                            <font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->type ?>
                        </td>
                    </tr>
                    <tr>
                        <!-- <td width="5%"></td> -->
                        <td colspan="2">
                            <font color='#000000'> Keterangan
                        </td>
                        <td>
                            <font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->keterangan_pengaju ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" color='#000000'>Nomor Rekening Pembayaran </td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td>
                            <font color='#000000'> Nama Bank
                        </td>
                        <td>
                            <font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->nama_bank ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td>
                            <font color='#000000'> Nomor Rekening
                        </td>
                        <td>
                            <font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->no_rek ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td>
                            <font color='#000000'> Atas Nama
                        </td>
                        <td>
                            <font color='#000000'> :&nbsp;&nbsp; <?= $data_pkk[0]->ats_nama ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <font color="white">i </font>
                        </td>
                    </tr>
                    </tr>
                </table>
            </section>
            <!-- TABEL BIAYA OPERASIONAL -->
            <section role="main" class="body" style="padding-top:0px;">
                <div class="table-responsive">
                    <table id="kt_table_1" style="font-family:Times New Roman; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th colspan="4" bgcolor="#b7d5ac" colspan="4" style="text-align:center">
                                    <font color='#000000'>Biaya Operasional
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="5%">
                                    <font color='#000000'>Tanggal
                                </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="5%">
                                    <font color='#000000'>No.Kartu
                                </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="60%">
                                    <font color='#000000'> Keterangan
                                </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="25%">
                                    <font color='#000000'> Nominal
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $x = 1;
                            $nom = 0;
                            foreach ($detail as $row) {
                                $x = $x + 1;
                                $nom = (float) $row->nominal + $nom;
                            ?>

                                <tr>
                                    <!-- <td class="tgl" style="text-align:center;"><?= $row->tgl ?></td> -->
                                    <td class="tanggal" style="text-align:center;">
                                        <font color='#000000'>
                                            <!-- <?= $row->tanggal ?> -->
                                            <?php
                                            if ($row->nominal == 0) {
                                                echo "";
                                            } else {
                                                echo $row->tanggal;
                                            }
                                            ?>
                                    </td>
                                    <td class="nokartu">
                                        <font color='#000000'>
                                            &nbsp;<?= $row->nokartu ?>&nbsp;
                                    </td>
                                    <td class="ket_detail">
                                        <font color='#000000'>
                                            &nbsp;<?= $row->ket_detail ?>&nbsp;
                                    </td>
                                    <td class="nominal" style="text-align:right">
                                        <font color='#000000'>
                                            <?php
                                            if ($row->nominal == 0) {
                                                echo "";
                                            } else { ?>
                                                <label class="mylabel" style="text-align:left">&nbsp;Rp.
                                                </label>
                                                <label class="mylabel" style="text-align:right"><?= number_format($row->nominal, 0, ",", ".") ?>,-&nbsp;
                                                </label>
                                            <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php for ($kosong = $x; $kosong <= 12; $kosong++) { ?>
                                <tr>
                                    <td>
                                        <font color="white">i </font>
                                    </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th bgcolor="#b7d5ac" style="text-align:center">
                                    <font color='#000000'>TOTAL
                                </th>
                                <td bgcolor="#b7d5ac"></td>
                                <td bgcolor="#b7d5ac"></td>
                                <td bgcolor="#b7d5ac" style="text-align:right"><label class="mylabel" style="text-align:left">
                                        <font color='#000000'>&nbsp;Rp.
                                    </label>
                                    <label class="mylabel" style="text-align:right">
                                        <font color='#000000'><?= number_format($nom, 0, ",", ".") ?>,-&nbsp;
                                    </label>
                                    <input type="hidden" id="jml_nom" value="<?= $nom ?>">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- PENUTUP TABEL BIAYA OPERASIONAL -->
                    <br><br>

                    <!-- TABEL BIAYA DINAS -->
                    <?php if ($detail1) { ?>
                        <table id="kt_table_1" style="font-family:Times New Roman; font-size:15px" border="1" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="4" bgcolor="#b7d5ac" colspan="4" style="text-align:center">
                                        <font color='#000000'>Biaya Dinas
                                    </th>
                                </tr>
                                <tr>
                                    <th style="text-align:center" bgcolor="#b7d5ac" width="5%">
                                        <font color='#000000'>Tanggal
                                    </th>
                                    <th style="text-align:center" bgcolor="#b7d5ac" width="5%">
                                        <font color='#000000'>No.Kartu
                                    </th>
                                    <th style="text-align:center" bgcolor="#b7d5ac" width="60%">
                                        <font color='#000000'> Keterangan
                                    </th>
                                    <th style="text-align:center" bgcolor="#b7d5ac" width="25%">
                                        <font color='#000000'> Nominal
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $x = 1;
                                $nom2 = 0;
                                foreach ($detail1 as $row) {
                                    $x = $x + 1;
                                    $nom2 = (float) $row->nominal + $nom2;
                                ?>

                                    <tr>
                                        <!-- <td class="tgl" style="text-align:center;"><?= $row->tgl ?></td> -->
                                        <td class="tanggal" style="text-align:center;">
                                            <font color='#000000'>
                                                <!-- <?= $row->tanggal ?> -->
                                                <?php
                                                if ($row->nominal == 0) {
                                                    echo "";
                                                } else {
                                                    echo $row->tanggal;
                                                }
                                                ?>
                                        </td>
                                        <td class="nokartu">
                                            <font color='#000000'>
                                                &nbsp;<?= $row->nokartu ?>&nbsp;
                                        </td>
                                        <td class="ket_detail">
                                            <font color='#000000'>
                                                &nbsp;<?= $row->ket_detail ?>&nbsp;
                                        </td>
                                        <td class="nominal" style="text-align:right">
                                            <font color='#000000'>
                                                <?php
                                                if ($row->nominal == 0) {
                                                    echo "";
                                                } else { ?>
                                                    <label class="mylabel" style="text-align:left">
                                                        <font color='#000000'>&nbsp;Rp.
                                                    </label>
                                                    <label class="mylabel" style="text-align:right">
                                                        <font color='#000000'><?= number_format($row->nominal, 0, ",", ".") ?>,-&nbsp;
                                                    </label>
                                                <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php for ($kosong = $x; $kosong <= 12; $kosong++) { ?>
                                    <tr>
                                        <td>
                                            <font color="white">i </font>
                                        </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th bgcolor="#b7d5ac" style="text-align:center">
                                        <font color='#000000'>TOTAL
                                    </th>
                                    <td bgcolor="#b7d5ac"></td>
                                    <td bgcolor="#b7d5ac"></td>
                                    <td bgcolor="#b7d5ac" style="text-align:right"><label class="mylabel" style="text-align:left">
                                            <font color='#000000'>&nbsp;Rp.
                                        </label>
                                        <label class="mylabel" style="text-align:right">
                                            <font color='#000000'><?= number_format($nom2, 0, ",", ".") ?>,-&nbsp;
                                        </label>
                                        <input type="hidden" id="jml_nom" value="<?= $nom ?>">
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    <?php } ?>
                    <!-- PENUTUP TABEL BIAYA DINAS -->
                    <br><br>
                    <!-- TABEL FINANCE -->
                    <table id="kt_table_1" style="font-family:Times New Roman; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th colspan="4" bgcolor="#b7d5ac" colspan="4" style="text-align:center">
                                    <font color='#000000'>Catatan Finance
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="5%">
                                    <font color='#000000'>Tanggal
                                </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="5%">
                                    <font color='#000000'>No.Kartu
                                </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="60%">
                                    <font color='#000000'> Keterangan
                                </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="25%">
                                    <font color='#000000'> Nominal
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $x = 1;
                            $nom3 = 0;
                            foreach ($detail2 as $row) {
                                $x = $x + 1;
                                $nom3 = (float) $row->nominal + $nom3;
                            ?>

                                <tr>
                                    <!-- <td class="tgl" style="text-align:center;"><?= $row->tgl ?></td> -->
                                    <td class="tanggal" style="text-align:center;">
                                        <font color='#000000'>
                                            <!-- <?= $row->tanggal ?> -->
                                            <?php
                                            if ($row->nominal == 0) {
                                                echo "";
                                            } else {
                                                echo $row->tanggal;
                                            }
                                            ?>
                                    </td>
                                    <td class="nokartu">
                                        <font color='#000000'>
                                            &nbsp;<?= $row->nokartu ?>&nbsp;
                                    </td>
                                    <td class="ket_detail">
                                        <font color='#000000'>
                                            &nbsp;<?= $row->ket_detail ?>&nbsp;
                                    </td>
                                    <td class="nominal" style="text-align:right">
                                        <font color='#000000'>
                                            <?php
                                            if ($row->nominal == 0) {
                                                echo "";
                                            } else {
                                            ?>
                                                <label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
                                                <label class="mylabel" style="text-align:right"><?= number_format($row->nominal, 0, ",", ".") ?>,-&nbsp;</label>
                                            <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php for ($kosong = $x; $kosong <= 5; $kosong++) { ?>
                                <tr>
                                    <td>
                                        <font color="white">i </font>
                                    </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th bgcolor="#b7d5ac" style="text-align:center">
                                    <font color='#000000'>TOTAL
                                </th>
                                <td bgcolor="#b7d5ac"></td>
                                <td bgcolor="#b7d5ac"></td>
                                <td bgcolor="#b7d5ac" style="text-align:right"><label class="mylabel" style="text-align:left">
                                        <font color='#000000'>&nbsp;Rp.
                                    </label>
                                    <label class="mylabel" style="text-align:right"><?= number_format($nom3, 0, ",", ".") ?>,-&nbsp;</label>
                                    <input type="hidden" id="jml_nom" value="<?= $nom ?>">
                                </td>
                            </tr>
                        </tfoot>

                    </table>
                    <!-- PENUTUP TABEL FINANCE -->

                    <!-- TABEL PERHITUNGAN TOTAL KESELURUHAN -->
                    <?php
                    $nom4 = 0;
                    if ($detail1 != null) {
                        $nom2 = $nom2;
                    } else {
                        $nom2 = 0;
                    }
                    $nom4 = $nom + $nom2 + $nom3;
                    ?>
                    <br><br>
                    <table id="kt_table_1" style="font-family:Times New Roman; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="4" style="text-align:center">
                                    <font color='#000000'>TOTAL BIAYA OPERASIONAL
                                </th>
                                <th bgcolor="#b7d5ac" width="25%" style="text-align:right">
                                    <label class="mylabel">
                                        <font color='#000000'>&nbsp;Rp.
                                    </label>
                                    <label class="mylabel">
                                        <font color='#000000'><?= number_format($nom, 0, ",", ".") ?>,-&nbsp;
                                    </label>
                                </th>
                            </tr>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="4" style="text-align:center">
                                    <font color='#000000'>TOTAL BIAYA DINAS
                                </th>
                                <th bgcolor="#b7d5ac" width="25%" style="text-align:right">
                                    <label class="mylabel" style="text-align:left">
                                        <font color='#000000'>&nbsp;Rp.
                                    </label>
                                    <label class="mylabel" style="text-align:right">
                                        <font color='#000000'><?= number_format($nom2, 0, ",", ".") ?>,-&nbsp;
                                    </label>
                                </th>
                            </tr>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="4" style="text-align:center">
                                    <font color='#000000'>TOTAL CATATAN FINANCE
                                </th>
                                <th bgcolor="#b7d5ac" width="25%" style="text-align:right">
                                    <label class="mylabel" style="text-align:left">
                                        <font color='#000000'>&nbsp;Rp.
                                    </label>
                                    <label class="mylabel" style="text-align:right">
                                        <font color='#000000'><?= number_format($nom3, 0, ",", ".") ?>,-&nbsp;
                                    </label>
                                </th>
                            </tr>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="4" style="text-align:center">
                                    <font color='#000000'>GRAND TOTAL BIAYA PENGAJUAN KLAIM KAS
                                </th>
                                <th bgcolor="#b7d5ac" width="25%" style="text-align:right">
                                    <label class="mylabel" style="text-align:left">
                                        <font color='#000000'>&nbsp;Rp.
                                    </label>
                                    <label class="mylabel" style="text-align:right">
                                        <font color='#000000'><?= number_format($nom4, 0, ",", ".") ?>,-&nbsp;
                                    </label>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <!-- PENUTUP TABEL PERHITUNGAN TOTAL KESELURUHAN -->
                    <br>
                    <!-- TABEL KETERANGAN -->
                    <table id="tbl_2" border="0">
                        <tr>
                            <td width="7%">
                            <td style="text-align:justify; text-justify:inter-word;">
                                <font color='#000000' style="font-family:Times New Roman; font-size:15px;">
                                    Saya membuat laporan penggunaan kas dengan melampirkan Struk/Bon biaya terkait dan akan diberikan kepada bagian keuangan.
                                </font>
                            </td>
                            <td width="7%">
                        </tr>
                    </table>
                    <!-- PENUTUP TABEL KETERANGAN -->
                </div>
            </section>

            <section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
                <table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
                    <tbody>
                        <tr style="height: 35px;">
                            <td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
                                <font color='#000000'> <?= $data_pkk[0]->kota_aju ?>, <?= date('d-m-Y', strtotime($data_pkk[0]->tgl_pengajuan)); ?>
                            </td>
                        </tr>
                        <tr style="height: 18px;">
                            <td style="text-align:center; width:25.5%;" colspan="2">
                                <font color='#000000'> Diajukan Oleh,
                            </td>
                            <td style="text-align:center; width:49%;" colspan="3">
                                <font color='#000000'> Diverifikasi Oleh,
                            </td>
                            <td style="text-align:center; width:25.5%;" colspan="2">
                                <font color='#000000'> Disetujui Oleh,
                            </td>
                        </tr>
                        <tr style="height:60px;">
                            <?php
                            $img_path   = "uploads/file_karyawan/ttd/";
                            $ttdaju     = $img_path . "ttd_" . $data_pkk[0]->idPengaju . ".png";
                            $ttd1       = $img_path . "ttd_notyet2.png";
                            $ttd2       = $img_path . "ttd_notyet2.png";
                            $ttd3       = $img_path . "ttd_notyet2.png";

                            if ($data_pkk[0]->aju_ttd1 == '1') {
                                $ttd1 = $img_path . "ttd_107.png";
                            } else if ($data_pkk[0]->aju_ttd1 == '2') {
                                $ttd1 = $img_path . "ttd_not.png";
                            }
                            if ($data_pkk[0]->aju_ttd2 == '1') {
                                $ttd2 = $img_path . "ttd_33.png";
                            } else if ($data_pkk[0]->aju_ttd2 == '2') {
                                $ttd2 = $img_path . "ttd_not.png";
                            }
                            if ($data_pkk[0]->aju_ttd3 == '1') {
                                $ttd3 = $img_path . "ttd_23.png";
                            } else if ($data_pkk[0]->aju_ttd3 == '2') {
                                $ttd3 = $img_path . "ttd_not.png";
                            }
                            ?>
                            <td style="text-align:center; width:23%; height:60px;"> <?php echo '<img src="' . $ttdaju . '" height="75">'; ?> </td>
                            <td style="text-align:center; width:2.5%;"></td>
                            <td style="text-align:center; width:23%;"><?php echo '<img src="' . $ttd1 . '" height="75">'; ?></td>
                            <td style="text-align:center; width:2%;"></td>
                            <td style="text-align:center; width:24%;"><?php echo '<img src="' . $ttd2 . '" height="75">'; ?></td>
                            <td style="text-align:center; width:2.5%;"></td>
                            <td style="text-align:center; width:23%;"><?php echo '<img src="' . $ttd3 . '" height="75">'; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align:center; ">
                                <font color='#000000'> <?= $data_pkk[0]->nama_ttd ?>
                                    <hr>
                                    </hr>
                            </td>
                            <td style="text-align:center; "></td>
                            <td style="text-align:center; ">
                                <font color='#000000'> Dirangga Madali
                                    <hr>
                                    </hr>
                            </td>
                            <td style="text-align:center; "></td>
                            <td style="text-align:center; ">
                                <font color='#000000'> Yolanda Pratiwi
                                    <hr>
                                    </hr>
                            </td>
                            <td style="text-align:center; "></td>
                            <td style="text-align:center; ">
                                <font color='#000000'> Meilina Safitri
                                    <hr>
                                    </hr>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:center; vertical-align:top;">
                                <font color='#000000'> <i><?= $data_pkk[0]->jabatan ?></i>
                            </td>
                            <td style="text-align:center; "></td>
                            <td style="text-align:center; vertical-align:top;">
                                <font color='#000000'> <i>Head of Accounting and Tax</i>
                            </td>
                            <td style="text-align:center; "></td>
                            <td style="text-align:center; vertical-align:top;">
                                <font color='#000000'> <i>General Manager</i>
                            </td>
                            <td style="text-align:center; "></td>
                            <td style="text-align:center; vertical-align:top;">
                                <font color='#000000'> <i>Director of Corp Planning & Bussinees Management</i>
                            </td>
                        </tr>
                    </tbody>
                </table>

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