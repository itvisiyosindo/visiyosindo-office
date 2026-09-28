<header class="page-header">
    <script src="<?php echo base_url('assets/ckeditor/ckeditor.js'); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/ckeditor/contents.css'); ?>">
    <h2><i class="icons fas fa-user"></i>&nbsp;
        <?= $page_title ?>
    </h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>
                    <?= $page_desc ?>
                </span></li>
        </ol>
    </div>

    <style>
        hr {
            display: block;
            margin-top: 0em;
            margin-bottom: 0em;
            margin-left: auto;
            margin-right: auto;
            border-top: 1px solid black;
        }

        input {
            width: 97%;
            height: auto;
            border: 0px dotted #f30;
            border-radius: 4px;
            -moz-border-radius: 8px;
            margin-right: 0px;
            //font-family:Garamond;
            //background:#363;
        }

        .myinput {
            width: 100%;
            height: 200px;
            border: 0px solid #000;
            border-radius: 0px;
            -moz-border-radius: 8px;
            margin-left: 0px;
            text-align: center;
            height: 100px;
            line-height: 100px;
            font-weight: bold;
        }

        .myselect {
            width: 97%;
            height: auto;
            border: 0px solid #000;
            border-radius: 4px;
            -moz-border-radius: 8px;
            margin: 0px;
            //background:#b7d5ac;
        }

        .mydiv br {
            display: none;
        }

        .mydiv p {
            padding: 0;
            margin: 0;
        }

        *::placeholder {
            /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: red;
            opacity: 1;
            /* Firefox */
        }
    </style>

</header>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFFFFF; padding:5%;">
        <div class="table-responsive">
            <font color='#000000'>
                <div class="form-group">
                    <?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
                </div>

                <!-- TABEL SURAT PENGAJUAN -->
                <table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0"
                    width="100%">
                    <tbody>
                        <tr>
                            <td colspan="4" style="text-align:center; font-size:20px; font-weight:bold;">
                                SURAT KEPUTUSAN DIREKSI
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:center; font-size:12px; font-weight:bold;">
                </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <font color="white">i</font>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                Perihal
                            </td>
                            <td>&nbsp;<input type="text" name="perihal" id="perihal" placeholder=" &nbsp;Ketik Perihal"
                                    required></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                             Berdasarkan <input type="text" name="isi1" id="isi1" placeholder=" &nbsp;Ketik Disini"
                                    required></td>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <font color="black">maka dengan ini diputuskan bahwa :</font>
                            </td>
                        </tr>
                         <tr>
                            <td colspan="4"><textarea class="texteditor" rows="2" id="isi2" name="isi2"
                                    placeholder="Ketik Disini" id="editor"></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <font color="white">i </font>
                            </td>
                        </tr>
                       
                        <tr>
                            <td colspan="4">Demikian surat keputusan ini dibuat, apabila kemudian hari terdapat kekeliruan dan/atau kesalahan dengan penerbitan surat keputusan ini, maka perusahaan akan melakukan penyesuaian ulang sebagaimana mestinya.</td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <font color="white">i </font>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" width="70%"></td>
                            <td style="text-align:left; ">Ditetapkan di : Pekanbaru<br>Pada Tanggal :<input type="text" data-plugin-datepicker
                                    data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}'
                                    id="tgl_pengajuan" Style="width:40%; text-align:Left" placeholder="Isi Tanggal">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align:left; ">PT. Visi Yosindo Medkal</td>
                        </tr>
                        <tr style="height:60px;">
                            <?php
                            $img_path = "uploads/file_karyawan/ttd/";
                            $ttd3 = $img_path . "ttd_blank.png";
                            ?>
                            <td colspan="3"></td>
                            <td style="text-align:center; ">
                                <?php echo '<img src="' . $ttd3 . '" height="70">'; ?>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align:LEFT; "><b><u>Bob Ariyos</u></b></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align:LEFT; vertical-align:top;"><i>Director</i></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <font color="white">i </font>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"><i>Tembusan :</i></td>
                        </tr>
                        <tr>
                            <td style="text-align:right; "><i>1. </i></td>
                            <!-- <td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Direksi</i></td> -->
                        </tr>
                        <tr>
                            <td style="text-align:right; "><i>2. </i></td>
                            <!-- <td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Pimpinan Umum</i></td> -->
                        </tr>
                        <tr>
                            <td style="text-align:right; "><i>3. </i></td>
                            <!-- <td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Finance Staff</i></td> -->
                        </tr>
                        <tr>
                            <td colspan="4">
                                <font color="white">i </font>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <!-- PENUTUP SURAT PENGANTAR DINAS -->
                <br><br>
                </table>
        </div>
        <?= form_close(); ?>
        <br><br>
        <div role="document">
            <button type="button" class="btn btn-success btn-save float-right btn-ajukan" style="margin-left:12px;"> <i
                    class="fas fa-check"></i> Ajukan </button>
            <button type="button" onclick="goBack()"
                class="btn btn-secondary btn-clear-form float-left">Kembali</button>
        </div>
        <br><br>
    </div>

</div>
</div>
<!-- panggil jquery -->
<script type="text/javascript" src="assets/jquery/jquery-3.1.1.min.js"></script>
<!-- panggil ckeditor.js -->
<script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
<!-- panggil adapter jquery ckeditor -->
<script type="text/javascript" src="assets/ckeditor/adapters/jquery.js"></script>
<!-- setup selector -->
<script type="text/javascript">
    $('textarea.texteditor').ckeditor();
</script>
<script>

    // PENUTP FUNGSI TAMBAH BUTTON
    document.addEventListener('DOMContentLoaded', function () {

        $(document).on('click', '.btn-ajukan', function () {
            var perihal = $('#perihal').val();
            var isi1 = $('#isi1').val();
            var isi2 = CKEDITOR.instances['isi2'].getData();
            var tgl_pengajuan = $('#tgl_pengajuan').val(); tgl_pengajuan

            Swal.fire({
                //title: approval + ' absensi?',
                title: 'Ajukan Surat Keputusan Direksi?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/skd',
                        dataType: 'JSON',
                        data: {
                            perihal: perihal,
                            isi1: isi1,
                            isi2: isi2,
                            tgl_pengajuan: tgl_pengajuan

                        },
                        success: function (resp) {
                            handleResponse(resp)
                        }
                    })
                }
            })
        })

        $(document).on('click', '.btn-denial', function () {
            const id = $(this).attr("id-pd")
            Swal.fire({
                title: 'Tolak Pengantar Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/kg/2/ttd_1',
                        dataType: 'JSON',
                        data: {
                            id: id,
                            csrf_token: token
                        },
                        success: function (resp) {
                            handleResponse(resp)
                        }
                    })
                }
            })
        })
    })

    function goBack() {
        window.history.back();
    }
</script>