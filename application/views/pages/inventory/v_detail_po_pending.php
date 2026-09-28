
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
            <h2>Data PO Pending</h2>
        </div>
        <?= form_open('inventory_new/update', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <!-- value preview -->
        <div id="value_preview">
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Nama Customer <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="pelanggan" name="pelanggan" value="<?= $data_po[0]->nama_customer ?>" readonly>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Nama Marketing <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $data_po[0]->nama_marketing ?>" readonly>
            </div>

						<?php
						if($data_po[0]->item2 == ""){
								$item = '- '.$data_po[0]->item1;
						}else if($data_po[0]->item3 == ""){
								$item = '- '.$data_po[0]->item1."\n".'- '.$data_po[0]->item2;
						}else if($data_po[0]->item4 == ""){
								$item = '- '.$data_po[0]->item1."\n".'- '.$data_po[0]->item2."\n".'- '.$data_po[0]->item3;
						}else if($data_po[0]->item5 == ""){
								$item = '- '.$data_po[0]->item1."\n".'- '.$data_po[0]->item2."\n".'- '.$data_po[0]->item3."\n".'- '.$data_po[0]->item4;
						}else if($data_po[0]->item6 == ""){
								$item = '- '.$data_po[0]->item1."\n".'- '.$data_po[0]->item2."\n".'- '.$data_po[0]->item3."\n".'- '.$data_po[0]->item4."\n".'- '.$data_po[0]->item5;
						}else if($data_po[0]->item7 == ""){
								$item = '- '.$data_po[0]->item1."\n".'- '.$data_po[0]->item2."\n".'- '.$data_po[0]->item3."\n".'- '.$data_po[0]->item4."\n".'- '.$data_po[0]->item5."\n".'- '.$data_po[0]->item6;
						}else{
								$item = '- '.$data_po[0]->item1."\n".'- '.$data_po[0]->item2."\n".'- '.$data_po[0]->item3."\n".'- '.$data_po[0]->item4."\n".'- '.$data_po[0]->item5."\n".'- '.$data_po[0]->item6."\n".'- '.$data_po[0]->item7;
						}
						?>
						<div class="form-group mb-2 pt-1">
								<label class="col-form-label">Keterangan Lainnya <span class="text-danger">*</span></label>
								<textarea class="form-control" id="subject" name="subject" rows="9" readonly><?= htmlspecialchars($item) ?></textarea>

						</div>

						<div class="form-group mb-2 pt-1 d-flex">
                <div class="me-2" style="flex: 1;">
                    <label class="col-form-label">Tanggal PO <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="nama_barang" name="nama_barang" value="<?= date('d-M-Y',strtotime($data_po[0]->tanggal_po)); ?>" readonly>
                </div>
                <div style="flex: 1;">
								</div>
            </div>

            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Sistem Pembayaran <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $data_po[0]->sistem_pembayaran ?>" readonly>
            </div>

            
            
            <div class="form-group">
						<label for="status" class="form-control-label">Update Status <span class="text-danger">*</span> :</label>
						<select class="form-control" id="status" name="status" required onchange="toggleFormStatus()">
									<option value="">- Pilih Status -</option>
									<option value = "1">Pending</option>
									<option value = "2">Batal</option>
									<option value = "3">Barang Dalam Pemesanan</option>
									<option value = "4">Barang Ready</option>
									<option value = "5">Menunggu Konfirmasi Customer</option>
									<option value = "6">Dikirim</option>
						</select>
                <div id="danger-alert">Di Update oleh Warehouse</div>
				</div>
                
				<div class="form-group" id="form_keterangan_konfirmasi" style="display:none;">
						<label for="keterangan_konfirmasi" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="keterangan_konfirmasi" name="keterangan_konfirmasi" required></textarea>
				</div>


           
        <br>
        <div class="row-action-buttons">
            <!--<button type="button" id="btn-show-add-form" class="btn btn-primary btn-clear-form" data-id="<?//= encrypt($data_po[0]->id_tiket) ?>">Report Note</button>-->
            <input type="hidden" name="id_po" id="id_po" value="<?= $data_po[0]->idGc ?>">
            <?php if (sessPenggunaId()==1 || sessPenggunaId()==15 || sessPenggunaId()==85 || sessPenggunaId()==33 || sessPenggunaId()==7 || sessPenggunaId()==73) { ?>
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
                <h4 style="margin-left: 1.2rem;"><b>Status PO Pending</b></h4>
                <ul class="timeline-3">
                 <?php
                foreach($data_status as $each){

                $dari = date_create($each->created_at); 
                $sampai = date_create();
                $diff  = date_diff($dari, $sampai); //untuk menghitung hari
                // echo $diff->d . ' Hari, ';                         <span><i class="fa fa-clock-o mr-1"></i>21 March, 2019</span>

                if($each->id_status==1){
                    $status = "Pending";
                }else if($each->id_status==2){
                    $status = "Batal";
                }else if($each->id_status==3){
                    $status = "Barang Dalam Pemesanan";
                }else if($each->id_status==4){
                    $status = "Barang Ready";
                }else if($each->id_status==5){
                    $status = "Menunggu Konfirmasi Customer";
                }else if($each->id_status==6){
                    $status = "Dikirim";
                }
                        
            ?>
                
                    <li>
                    <a><b><?php echo  $status?></b></a>
                    <a class="float-right"><?php echo date('d-M-Y | H:i:s',strtotime($each->created_at))?></a>
                    <p class="mt-2"><?php echo "Update oleh : " . $each->nama_pembuat; ?></p>

                    <?php if($each->remarks != "") { ?>
                       <a><b><?php echo  "Keterangan : " . $each->remarks; ?></b></a>
                    <?php } ?> 
                    
                     <br><br>
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
        var formKet = document.getElementById("form_keterangan_konfirmasi");

        if (status === "") {
            formKet.style.display = "none";
        } else {
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
