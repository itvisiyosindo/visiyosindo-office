<header class="page-header">
    <h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>


<div class="col-xl-8 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:10%">
        <div class="text-center mt-0">
            <h2>Data Tiket</h2>
        </div>
        <?= form_open('tiket/update/edit_on_detail', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <!-- value preview -->
        <div id="value_preview">
            <div class="form-group mb-2 pt-1">
                <label class="col-form-label">Pelanggan <span class="text-danger">*</span></label>
                <div class="custom-control custom-checkbox mb-2">
                    <input type="checkbox" class="custom-control-input" id="chk_pelanggan_manual" name="pelanggan_manual_flag" value="1" <?= empty($data_tiket[0]->id_pelanggan) ? 'checked' : '' ?>>
                    <label class="custom-control-label" for="chk_pelanggan_manual">
                        <small class="text-info"><i class="fas fa-user-edit"></i> Pelanggan tidak terdaftar (input manual)</small>
                    </label>
                </div>
                <div id="wrap_select_pelanggan" style="<?= empty($data_tiket[0]->id_pelanggan) ? 'display:none;' : '' ?>">
                    <select data-plugin-selectTwo class="form-control populate" id="list_pelanggan" name="list_pelanggan" <?= !empty($data_tiket[0]->id_pelanggan) ? 'required' : '' ?>>
                        <option value="">- Pilih Pelanggan -</option>
                        <?php
                        foreach ($pelanggan as $row) {
                            $selected = ($data_tiket[0]->id_pelanggan == $row->id_pelanggan) ? 'selected' : '';
                            echo '<option value="' . $row->id_pelanggan . '" ' . $selected . '>' . $row->identitas_pelanggan . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div id="wrap_input_pelanggan" style="<?= empty($data_tiket[0]->id_pelanggan) ? '' : 'display:none;' ?>">
                    <input class="form-control" type="text" placeholder="Ketik nama pelanggan..." id="pelanggan" name="pelanggan" value="<?= $data_tiket[0]->pelanggan ?>" <?= empty($data_tiket[0]->id_pelanggan) ? 'required' : '' ?>>
                </div>
                <div id="danger-alert">Untuk Tiketing Internal, Silahkan di kosongkan</div>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Subject <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $data_tiket[0]->subject ?>">
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Kategori <span class="text-danger"></span></label>
                <input type="hidden" name="id_topik" id="id_topik" value="<?= $data_tiket[0]->id_topik ?>">
                <select class="form-control" id="kategori" name="kategori" required>
                    <option value="">- Pilih Kategori -</option>
                    <?php
                    foreach ($kategori as $row) {
                        echo '<option value="' . $row->id_topik . '">' . $row->nama . '</option>';
                    }
                    ?>
                </select>
                <div id="danger-alert"></div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-2 pt-1">
                        <label class=" col-form-label">Status <span class="text-danger"></span></label>
                        <input type="hidden" name="id_status_tiket" id="id_status_tiket" value="<?= $data_tiket[0]->status_tiket ?>">
                        <select class="form-control col-md-6" id="status_tiket" name="status_tiket" required>
                            <option value="">- Pilih Prioritas -</option>
                            <option value="1">Baru</option>
                            <option value="2">Dalam Proses</option>
                            <option value="3">Revisi</option>
                            <option value="4">Selesai</option>
                            <option value="5">Tidak Selesai</option>
                        </select>
                        <div id="danger-alert"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2 pt-1">
                        <label class=" col-form-label">Prioritas <span class="text-danger"></span></label>
                        <input type="hidden" name="id_prioritas" id="id_prioritas" value="<?= $data_tiket[0]->prioritas ?>">
                        <select class="form-control" id="prioritas" name="prioritas" required>
                            <option value="">- Pilih Prioritas -</option>
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                            <option value="4">Urgent</option>
                        </select>
                        <div id="danger-alert"></div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Penerima Tiket</label>
                <input type="hidden" name="id_penerima" id="id_penerima" value="<?= encrypt($data_tiket[0]->id_penerima) ?>">
                <select data-plugin-selectTwo class="form-control populate" name="agent" id="agent">
                    <option value="">- Pilih Penerima Tiket/PIC -</option>
                    <?php
                    foreach ($pengguna as $row) {
                        echo '<option value="' . encrypt($row->pengguna_id) . '">' . $row->nama . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group mb-2 pt-1">
                <?php
                $cek = "";
                $isi = "Tidak ada PIC Support";
                foreach ($data_pic_support as $row) {
                    $cek .= "- " . $row->nama_pic . '\n';
                    $isi = $cek;
                }
                ?>
                <label class=" col-form-label">PIC Support</label>
                <textarea class="form-control" name="pic_support" id="pic_support" rows="3" required disabled><?= $isi ?></textarea>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" id="deskripsi" required><?= $data_tiket[0]->deskripsi ?? NULL ?></textarea>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">File Pendukung : </label>
                <?php if ($data_tiket[0]->file_pendukung != "") { ?>
                    <a href="<?= $data_tiket[0]->file_pendukung ?>">
                        Klik untuk cek
                    </a>
                <?php } ?>
                <input class="form-control" type="text" id="file_pendukung" name="file_pendukung" value="<?= $data_tiket[0]->file_pendukung ?>">
            </div>
            <div class="form-group">
                <label for="invoice" class="form-control-label">File invoice : </label>
                <?php if ($data_tiket[0]->invoice != "") { ?>
                    <a href="<?= $data_tiket[0]->invoice ?>">
                        Klik untuk cek
                    </a>
                <?php } ?>
                <input type="text" class="form-control respon" id="invoice" name="invoice" value="<?= $data_tiket[0]->invoice ?>">
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Waktu Pengerjaan</label>
                <div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
                    <span class="input-group-text">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    <input type="text" class="form-control" id="start" value="<?= $data_tiket[0]->waktu_mulai == '0000-00-00' ? '0000-00-00'  : date('d-m-Y', strtotime($data_tiket[0]->waktu_mulai)); ?>" name="start" required>
                    <span class="input-group-text border-start-0 border-end-0 rounded-0">
                        to
                    </span>
                    <input type="text" class="form-control" id="end" value="<?= $data_tiket[0]->waktu_selesai == '0000-00-00' ? '0000-00-00'  : date('d-m-Y', strtotime($data_tiket[0]->waktu_selesai)); ?>" name="end" required>
                </div>
            </div>
        </div>
        <!-- end value preview -->
        <br>
        <div class="row-action-buttons">
            <!--<button type="button" id="btn-show-add-form" class="btn btn-primary btn-clear-form" data-id="<? //= encrypt($data_tiket[0]->id_tiket) 
                                                                                                                ?>">Report Note</button>-->
            <input type="hidden" name="id_tiket" id="id_tiket" value="<?= encrypt($data_tiket[0]->id_tiket) ?>">
            <?php if (sessPenggunaId() != $data_tiket[0]->id_penerima && (sessPenggunaId() == 72 || sessPenggunaId() == 85 || sessPenggunaId() == 755)) { ?>
                <button type="button" class="btn btn-success btn-save float-right" style="margin-left: 12px;">Simpan</button>
                <label type="hidden" id="cek_login" value="1"></label>
            <?php } ?>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" data-dismiss="modal">Kembali</button>
        </div>

        <br>
        <br>
        <?php if ($data_tiket[0]->log_tiket != "") { ?>


            <?php  /*<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_update">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama</th>
							<th> Tindakan</th>
							<th> Keterangan</th>
							<th> Waktu</th>
						</tr>
                        <?php 
                        $x = 1;
                        foreach ($data_update as $dtt) { 
                            if($dtt->status==1){
                                $status = "OPEN TICKET";
                            }else if($dtt->status==2){
                                $status = "UPDATE TICKET";
                            }else if($dtt->status==3){
                                $status = "CLOSE TICKET";
                            }else if($dtt->status==4){
                                $status = "UPDATE TICKET (Pengajuan Visit)";
                            }else if($dtt->status==5){
                                $status = "UPDATE TICKET (Pengajuan Biaya)";
                            }else if($dtt->status==6){
                                $status = "UPDATE TICKET (Laporan Akhir)";
                            }
                ?>
                            <tr>
                                <td><?php echo $x++; ?></td>
                                <td><?php echo $dtt->nama_pembuat; ?></td>
                                <td><?php echo $dtt->update; ?></td>
                                <td><?php echo $status; ?></td>
                                <td><?php echo $dtt->waktu; ?></td>
                            </tr>
                        <?php } ?>
					</thead>
				</table>
			</div>
		</div>  */ ?>


            <!-- Section: Timeline  
            <div class="container my-5">-->
            <div class="row">
                <div class="col-md-12 offset-md-0">
                    <h4 style="margin-left: 1.2rem;"><b>Log Ticket</b></h4>
                    <ul class="timeline-3">
                        <?php
                        foreach ($data_update as $each) {

                            $dari = date_create($each->waktu);
                            $sampai = date_create();
                            $diff  = date_diff($dari, $sampai); //untuk menghitung hari
                            // echo $diff->d . ' Hari, ';                         <span><i class="fa fa-clock-o mr-1"></i>21 March, 2019</span>

                            if ($each->status == 1) {
                                $status = "OPEN TICKET";
                            } else if ($each->status == 2) {
                                $status = "UPDATE TICKET";
                            } else if ($each->status == 3) {
                                $status = "CLOSE TICKET";
                            } else if ($each->status == 4) {
                                $status = "UPDATE TICKET (Pengajuan Visit)";
                            } else if ($each->status == 5) {
                                $status = "UPDATE TICKET (Pengajuan Biaya)";
                            } else if ($each->status == 6) {
                                $status = "UPDATE TICKET (Laporan Akhir)";
                            } else if ($each->status == 7) {
                                $status = "UPDATE TICKET (Dispatch Teknisi)";
                            }

                        ?>

                            <li>
                                <a><b><?php echo  $each->nama_pembuat ?></b></a>
                                <a class="float-right"><?php echo date('d-M-Y | H:i:s', strtotime($each->waktu)) ?></a>
                                <p class="mt-2"><?php echo  $each->update ?></p>
                                <?php if ($each->file_update != "") { ?>
                                    <a href="<?= $each->file_update ?>" target="blank" class="btn btn-primary float-left" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran </a>
                                    <br><br>
                                <?php } ?>
                                <font color='#22c0e8'><?php echo  $status ?></font>
                                <br><br>

                            </li>

                        <?php } ?>
                    </ul>
                </div>
            </div>
            <!-- </div>
            Section: Timeline 
            <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">  -->
            <link rel="stylesheet" href="assets/css/timeline.css">



            <?php /*         <!-- For demo purpose -->
    <div class="row text-center text-white mb-2">
        <div class="col-lg-8 mx-auto">
            <p class="lead mb-0">Histori Pengerjaan Tiket </p>
            </div>
        </div><!-- End -->


        <div class="row">

            <?php
                foreach($data_update as $each){

                $dari = date_create($each->waktu); 
                $sampai = date_create();
                $diff  = date_diff($dari, $sampai); //untuk menghitung hari
                // echo $diff->d . ' Hari, ';                         <span><i class="fa fa-clock-o mr-1"></i>21 March, 2019</span>

                if($each->status==1){
                    $status = "OPEN TICKET";
                }else if($each->status==2){
                    $status = "UPDATE TICKET";
                }else if($each->status==3){
                    $status = "CLOSE TICKET";
                }else if($each->status==4){
                    $status = "UPDATE TICKET (Pengajuan Visit)";
                }else if($each->status==5){
                    $status = "UPDATE TICKET (Pengajuan Biaya)";
                }else if($each->status==6){
                    $status = "UPDATE TICKET (Laporan Akhir)";
                }
                        
            ?>
                
                <!-- Timeline -->
                <ul class="timeline">
                    <li class="timeline-item bg-white rounded ml-3 p-4 shadow">
                        <div class="timeline-arrow"></div>
                        <p class="h5 mb-0"><?php echo  $each->nama_pembuat?></p>
                        <p class="float-right"><?php echo  $each->waktu?></p>
                        <p class="text-small mt-2 font-weight-light"><?php echo  $each->update?></p>
                        <p class="text-small mt-2 font-weight-light"><?php echo  $status?></p>
                    </li><br>
                    
                </ul><!-- End -->

            <?php } ?>

            
        </div>
    </div> */ ?>


        <?php }  ?>


        <?= form_close(); ?>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Report Note </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="ecommerce-timeline mb-3">
                    <div class="ecommerce-timeline-items-wrapper" id="respon">
                    </div>
                </div>
                <hr>
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Tambah Respon </h5>
                <div class="dt-tiket-form">
                    <div class="form-group">
                        <label for="deskripsi" class="form-control-label">Deskripsi <span class="text-danger">*</span> :</label>
                        <textarea class="form-control respon" name="deskripsi" id="deskripsi" cols="10" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="attachment" class="form-control-label">File Pendukung :</label>
                        <input type="text" class="form-control respon" placeholder="Masukkan Link Goggle Drive untuk data pendukung" id="attachment" name="attachment">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="is_aktif"></div>
                <input type="hidden" id="id" class="form-control" name="id" value="">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        var prioritas = $('#id_prioritas').val();
        $('#prioritas option[value="' + prioritas + '"]').prop("selected", true).trigger('change')

        var kategori = $('#id_topik').val();
        $('#kategori option[value="' + kategori + '"]').prop("selected", true).trigger('change')

        var penerima = $('#id_penerima').val();
        $('#agent option[value="' + penerima + '"]').prop("selected", true).trigger('change')

        var status_tiket = $('#id_status_tiket').val();
        $('#status_tiket option[value="' + status_tiket + '"]').prop("selected", true).trigger('change')

        // Toggle pelanggan manual di form edit
        $('#chk_pelanggan_manual').change(function() {
            if ($(this).is(':checked')) {
                $('#wrap_select_pelanggan').hide();
                $('#list_pelanggan').prop('required', false).val('').trigger('change');
                $('#wrap_input_pelanggan').show();
                $('#pelanggan').prop('required', true);
            } else {
                $('#wrap_select_pelanggan').show();
                $('#list_pelanggan').prop('required', true).trigger('change');
                $('#wrap_input_pelanggan').hide();
                $('#pelanggan').prop('required', false).val('');
            }
        });

        var cekLoginVal = $('#cek_login').attr('value');
        if (cekLoginVal != "1") {
            $("#pelanggan").attr("readonly", true);
            $("#list_pelanggan").attr("disabled", true);
            $("#chk_pelanggan_manual").attr("disabled", true);
            $("#subject").attr("readonly", true);
            $("#kategori").attr("disabled", true);
            $("#agent").attr("disabled", true);
            $("#deskripsi").attr("readonly", true);
            $("#file_pendukung").attr("readonly", true);
            $("#start").attr("readonly", true);
            $("#end").attr("readonly", true);
        }



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