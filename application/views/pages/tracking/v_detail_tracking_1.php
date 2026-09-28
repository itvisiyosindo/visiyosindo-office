
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
            <h2>Data Tracking Barang</h2>
        </div>
        <?= form_open('tracking/update', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <!-- value preview -->
        <div id="value_preview">
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="pelanggan" name="pelanggan" value="<?= $data_tracking[0]->nama_gudang ?>" readonly>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Nama Customer <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $data_tracking[0]->nama_customer ?>" readonly>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Nama Barang <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $data_tracking[0]->nama_barang ?>" readonly>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">PIC Penerima <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $data_tracking[0]->pic_penerima ?>" readonly>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class="col-form-label">Alamat Penerima <span class="text-danger">*</span></label>
                <textarea class="form-control" id="subject" name="subject" readonly><?= htmlspecialchars($data_tracking[0]->alamat_penerima) ?></textarea>
            </div>

            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Ekpedisi <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $data_tracking[0]->nama_ekspedisi ?>" readonly>
            </div>

            <div class="form-group mb-2 pt-1 d-flex">
                <div class="me-2" style="flex: 1;">
                    <label class="col-form-label">Tanggal Pengiriman <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="nama_barang" name="nama_barang" value="<?= date('d-M-Y',strtotime($data_tracking[0]->tgl_pengiriman)); ?>" readonly>
                </div>
                <div style="flex: 1;">
                    <label class="col-form-label">Estimasi Sampai <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="pic_penerima" name="pic_penerima" value="<?= date('d-M-Y',strtotime($data_tracking[0]->tgl_sampai)); ?>" readonly>
                </div>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Biaya <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $data_tracking[0]->biaya ?>" readonly>
            </div>

            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">No Surat Jalan <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $data_tracking[0]->no_sj ?>" readonly>
            </div>

            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">No Resi <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $data_tracking[0]->no_resi ?>" readonly>
            </div>
            <div class="form-group">
                <label for="invoice" class="form-control-label">Link Resi : </label>
                <?php if($data_tracking[0]->link_resi != ""){ ?>
                    <a href="<?= $data_tracking[0]->link_resi ?>">
                        Klik untuk cek
                    </a>
                <?php } ?>
                
            </div> 
            <div class="form-group mb-2 pt-1">
                <label class="col-form-label">Keterangan Lainnya <span class="text-danger">*</span></label>
                <textarea class="form-control" id="subject" name="subject" readonly><?= htmlspecialchars($data_tracking[0]->keterangan) ?></textarea>
            </div>

            <?php if($data_status[0]->id_status==1){
                    $status = "Proses Kirim";
                }else if($data_status[0]->id_status==2){
                    $status = "Manifest Berangkat";
                }else if($data_status[0]->id_status==3){
                    $status = "Proses Sortir";
                }else if($data_status[0]->id_status==4){
                    $status = "Pengantaran Kurir";
                }else if($data_status[0]->id_status==5){
                    $status = "Diterima";
                }else if($data_status[0]->id_status==6){
                    $status = "Menunggu Konfirmasi";
                }

            ?>

            <div class="form-group mb-2 pt-1">
                <label class="col-form-label">Status Barang <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $status ?>" readonly>
            </div>

            
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


            <!--<div class="form-group">
                <label for="invoice" class="form-control-label">File invoice : </label>
                <?php if($data_tracking[0]->nama_barang != ""){ ?>
                    <a href="<?= $data_tracking[0]->nama_barang ?>">
                        Klik untuk cek
                    </a>
                <?php } ?>
                <input type="text" class="form-control respon" id="invoice" name="invoice" value="<?= $data_tracking[0]->nama_barang ?>">
            </div> -->
        </div>
        <!-- end value preview -->
        <br>
        <div class="row-action-buttons">
            <!--<button type="button" id="btn-show-add-form" class="btn btn-primary btn-clear-form" data-id="<?//= encrypt($data_tracking[0]->id_tiket) ?>">Report Note</button>-->
            <input type="hidden" name="id_tracking" id="id_tracking" value="<?= encrypt($data_tracking[0]->id_tracking) ?>">
            <?php if (sessPenggunaId()==1 || sessPenggunaId()==15 || sessPenggunaId()==85 || sessPenggunaId()==33 || sessPenggunaId()==7 || sessPenggunaId()==73 || sessPenggunaId()==749) { ?>
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
                <h4 style="margin-left: 1.2rem;"><b>Status Tracking Barang</b></h4>
                <ul class="timeline-3">
                 <?php
                foreach($data_status as $each){

                $dari = date_create($each->created_at); 
                $sampai = date_create();
                $diff  = date_diff($dari, $sampai); //untuk menghitung hari
                // echo $diff->d . ' Hari, ';                         <span><i class="fa fa-clock-o mr-1"></i>21 March, 2019</span>

                if($each->id_status==1){
                    $status = "Proses Kirim";
                }else if($each->id_status==2){
                    $status = "Manifest Berangkat";
                }else if($each->id_status==6){
                    $status = "Menunggu Konfirmasi";
                }else if($each->id_status==3){
                    $status = "Proses Sortir";
                }else if($each->id_status==4){
                    $status = "Pengantaran Kurir";
                }else if($each->id_status==5){
                    $status = "Diterima";
                }
                        
            ?>
                
                    <li>
                    <a><b><?php echo  $status?></b></a>
                    <a class="float-right"><?php echo date('d-M-Y | H:i:s',strtotime($each->created_at))?></a>
                    <p class="mt-2"><?php echo "Update oleh : " . $each->nama_pembuat; ?></p>

                    <?php if($each->keterangan_konfirmasi != "") { ?>
                       <a><b><?php echo  "Keterangan : " . $each->keterangan_konfirmasi; ?></b></a>
                    <?php } ?> 
                    
                    <?php if($each->nama_penerima != "") { ?>
                       <a><b><?php echo  "Nama Penerima : " . $each->nama_penerima; ?></b></a>
                    <?php } ?> 
                    
                <br>
                    <?php if($each->tgl_penerima != "") { ?>
                        <a class="float-left"><?php echo "Tanggal Penerimaan : " . date('d-M-Y',strtotime($each->tgl_penerima)); ?></a>
                        
                    <?php } ?>
                    <br><br>    
                    <?php if($each->bukti_penerima != "") { ?>
			            <a href="<?=$each->bukti_penerima?>" target="blank" class="btn btn-primary float-left" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Bukti Penerimaan </a>
			        <br><br>
                    <?php } ?> 
                    <!--<font color='#22c0e8'><?php echo  $status?></font>-->
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
        } else if (status === "6") {
            formDiantarkan.style.display = "none";
            formDikirim.style.display = "none";
            formBukti.style.display = "none";
            formKet.style.display = "block";
        } else {
            formDiantarkan.style.display = "none";
            formDikirim.style.display = "none";
            formBukti.style.display = "none";
            formKet.style.display = "none";
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
		
		Array.prototype.forEach.call(document.getElementsById('cek_login'),
		function (elem) {
			elem.addEventListener('change', function() {
				let text 	= this.value;
				
				if (text != "1"){
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
