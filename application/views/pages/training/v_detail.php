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
            <?php if ($data_training[0]->status==0){ ?>
                <h2>Data Pengajuan Training & Development</h2>
            <?php } else {  ?>
                <h2>Data Training & Development</h2>
            <?php } ?>
        </div>
        <?= form_open('tiket/update/edit_on_detail', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <!-- value preview -->
        <div id="value_preview">
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Nama  <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="pelanggan" name="pelanggan" value="<?= $data_training[0]->namaPengaju ?>" readonly>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Jabatan <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="subject" name="subject" value="<?= $data_training[0]->jabatan ?>" readonly>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Nama Training <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="nama_training" name="nama_training" value="<?= $data_training[0]->nama_training ?>" readonly>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Penyelenggara <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="penyelenggara" name="penyelenggara" value="<?= $data_training[0]->penyelenggara ?>" readonly>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Tanggal <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="tanggal" name="tanggal" value="<?= date('d-M-Y',strtotime($data_training[0]->tanggal_mulai)) ?>" readonly>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Alasan </label>
                <textarea class="form-control" name="alasan" id="alasan" readonly><?= $data_training[0]->alasan ?? NULL ?></textarea>
            </div>
            <div class="form-group mb-2 pt-1">
    <label class=" col-form-label">Biaya <span class="text-danger">*</span></label>
    <?php 
        // 1. Ambil data mentah dari database (Varchar)
        $raw_biaya = isset($data_training[0]->biaya) ? $data_training[0]->biaya : 0;

        // 2. Bersihkan data (Hanya ambil angka). 
        // Ini menjaga jika user pernah input manual seperti "Rp 500.000" ke dalam database varchar.
        // Fungsi ini membuang huruf dan simbol, menyisakan angka saja.
        $clean_biaya = preg_replace('/[^0-9]/', '', (string)$raw_biaya);

        // 3. Jika hasil bersihnya kosong, set jadi 0
        if(empty($clean_biaya)) { $clean_biaya = 0; }

        // 4. Format angka yang sudah aman
        $biaya = 'Rp '. number_format((float)$clean_biaya, 0, ",", "."). ',-'; 
    ?>
    <input class="form-control" type="text" id="biaya" name="biaya" value="<?= $biaya ?>" readonly>
</div>
            <?php if($data_training[0]->link_pelatihan != ""){ ?>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">Link Pelatihan : </label>
                    <a href="<?= $data_training[0]->link_pelatihan ?>">
                        Klik untuk cek
                    </a>
            </div>
            <?php } ?>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">File Sertifikat : </label>
                <?php if($data_training[0]->file_sertifikat != ""){ ?>
                    <a href="<?= $data_training[0]->file_sertifikat ?>">
                        Klik untuk cek
                    </a>
                    <input type="hidden" name="file_sertifikat" id="file_sertifikat" value="<?= $data_training[0]->file_sertifikat ?>">
                    <?php } else {?>
                    <input class="form-control" type="text" id="file_sertifikat" name="file_sertifikat" required>
                    <?php } ?>
            </div>
            <div class="form-group mb-2 pt-1">
                <label class=" col-form-label">File Kehadiran : </label>
                <?php if($data_training[0]->file_kehadiran != ""){ ?>
                    <a href="<?= $data_training[0]->file_kehadiran ?>">
                        Klik untuk cek
                    </a> 
                    <input type="hidden" name="file_kehadiran" id="file_kehadiran" value="<?= $data_training[0]->file_kehadiran ?>">
                    <?php } else { ?>
                    <input class="form-control" type="text" id="file_kehadiran" name="file_kehadiran" required>
                    <?php } ?>
            </div>
            
        </div>
        <!-- end value preview -->
        <br>
        <div class="row-action-buttons">
            <input type="hidden" name="id" id="id" value="<?= $data_training[0]->id ?>">
			<input type="hidden" id="status" name="status" value="2">
            <?php if (sessPenggunaId()==$data_training[0]->id_pengaju || sessPenggunaId()==1) { ?>
                <?php if ($data_training[0]->file_sertifikat=="" || $data_training[0]->file_kehadiran=="") { ?>
                <button type="button" class="btn btn-success btn-save float-right" style="margin-left: 12px;">Simpan</button>
                <?php } ?>
            <?php } ?>

            <?php if (sessPenggunaId()==33 || sessPenggunaId()==1) { ?>
                <?php if ($data_training[0]->status==0) { ?>
                    <button type="button" class="btn btn-success btn-setujui float-right" style="margin-left: 12px;"><i class="fas fa-check"></i> Setujui</button>
                    <button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left: 12px;"><i class="fas fa-times"></i> Tolak</button>
                <?php } ?>
            <?php } ?>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" data-dismiss="modal">Kembali</button>
        </div>
		
		<br>
        <br>      
       
		        
		 
        <?= form_close(); ?>
    </div>
</div>



<script>


    document.addEventListener('DOMContentLoaded', function() {

        $(document).on('click', '.btn-save', function() {
        	var file_sertifikat = $('#file_sertifikat').val();
        	var file_kehadiran = $('#file_kehadiran').val();
        	var id = $('#id').val();

        	// var level_ttd = $('#level_ttd').val();
        	// console.log(approvall);
        	Swal.fire({
        		title: 'Setujui Pengajuan Training & Development?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
        	}).then(function(result) {
        		if (result.value) {	
        			// console.log('' + trf_komisi);
                    $.ajax({
                        method: 'POST',
                        url: 'training/updateLink/'+id,
                        dataType: 'JSON',
                        data: {
							file_sertifikat: file_sertifikat,
							file_kehadiran: file_kehadiran,
							csrf_token	: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }
        	})
        })
        
        
        $(document).on('click', '.btn-setujui', function() {
        	var status = $('#status').val();
        	var id = $('#id').val();

        	// var level_ttd = $('#level_ttd').val();
        	// console.log(approvall);
        	Swal.fire({
        		title: 'Setujui Pengajuan Training & Development?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
        	}).then(function(result) {
        		if (result.value) {	
        			// console.log('' + trf_komisi);
                    $.ajax({
                        method: 'POST',
                        url: 'training/updateSetuju/'+id,
                        dataType: 'JSON',
                        data: {
							status: status,
							csrf_token	: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }
        	})
        })


        $(document).on('click', '.btn-denial', function() {
        	var status = $('#status').val();
        	var id = $('#id').val();

        	// var level_ttd = $('#level_ttd').val();
        	// console.log(approvall);
        	Swal.fire({
        		title: 'Tolak Pengajuan Training & Development?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
        	}).then(function(result) {
        		if (result.value) {	
        			// console.log('' + trf_komisi);
                    $.ajax({
                        method: 'POST',
                        url: 'training/updateTolak/'+id,
                        dataType: 'JSON',
                        data: {
							status: status,
							csrf_token	: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }
        	})
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