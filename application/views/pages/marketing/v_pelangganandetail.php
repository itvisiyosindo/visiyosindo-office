<header class="page-header">
    <h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div id="main-modal-pic" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form PIC</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
		<?= form_open('#', array('id' => 'modal-form-pic', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-pic-form">
                     <div class="form-group" hidden>
                        <input type="text" class="form-control" id="idpic" name="idpic" value="<?= encrypt($datacaloncustomer->id) ?>"  autocomplete="off" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-9" for="namapic">Nama PIC:</label>
                        <input type="text" class="form-control" id="namapic" placeholder="Masukkkan Nama PIC" name="namapic" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-9" for="jabatanpic">Jabatan PIC:</label>
                        <input type="email" class="form-control" id="jabatanpic" placeholder="Masukkan Jabatan PIC" name="jabatanpic" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-6" for="teleponpic">Telepon PIC:</label>
                        <input type="number" class="form-control" id="teleponpic" placeholder="Masukkan Telepon PIC" name="teleponpic" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
				<button type="button" id="btnclose" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="add-pic" class="btn btn-success btn-save">Simpan</button>
			</div>
        <?= form_close(); ?>
        </div>
    </div>
</div>
<div class="col-xl-8 mb-8 mb-xl-0;" style="margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:5%">
        <div class="text-center">
            <h2>Detail Data Pelanggan </h2>
        </div>

        <?= form_open('tiket/update/edit_on_detail', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
       
        <div id="value_preview">
            <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Kode<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="kodecaloncustomer" name="kodecaloncustomer" value="<?= $datacaloncustomer->kodecustomer ?>" readonly> 
                     <label class="col-form-label">Nama<span class="text-danger">*</span></label>
                     <input class="form-control" type="text" id="namacaloncustomer" name="namacaloncustomer" value="<?= $datacaloncustomer->namacaloncustomer ?>">
                    <label class="col-form-label">Kelas<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="kelascustomer" name="kelascustomer" value="<?= $datacaloncustomer->kelascustomer ?>">
                    <label class="col-form-label">Type Customer <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="tipecustomer" name="tipecustomer" value="<?= $datacaloncustomer->tipecustomer ?>">
                    <label class="col-form-label">Nama Pihak Ketiga <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="namapihakketiga" name="namapihakketiga" value="<?= $datacaloncustomer->namapihakketiga ?>">
            </div>               
            <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Provinsi <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="provinsi" name="provinsi" value="<?= $datacaloncustomer->provinsi ?>">
                    <label class=" col-form-label">Kota <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="kota" name="kota" value="<?= $datacaloncustomer->tipe.' '.$datacaloncustomer->kota ?>">
                    <label for="alamat" class="col-form-label">Alamat <span class="text-danger">*</span> :</label>
				    <textarea name="alamatcaloncustomer" class="form-control" id="alamatcaloncustomer" cols="15" rows="1"><?= $datacaloncustomer->alamatcaloncustomer ?></textarea>
            </div>
            <div class="form-group mb-2 pt-1" >
                    <label class="col-form-label">Email <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="email" name="email" value="<?= $datacaloncustomer->email ?>">
                    <label class="col-form-label">Website <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="website" name="website" value="<?= $datacaloncustomer->website ?>">
            </div>
            <div class="form-group mb-2 pt-1 col-sm-12">
                <label for="pic" class="form-control-label">PIC <span class="text-danger">*</span> :</label>
				<select data-plugin-selectTwo class="form-control populate" name="pic[]" id="pic[]" data-placeholder="Data PIC" multiple disabled >
					<?php
						foreach ($pic as $row) {
						    echo '<option value="' . $row->id . '" selected="selected">' . $row->namapic . '</option>';
						}
					?>
				</select>
				<span class="input-group-append float-right">
			        <button type="button" name="btnaddpic" id="btnaddpic" class="btn btn-success btn-xs">Tambah PIC</button> 
				</span>
            </div>
             <div class="form-group mb-2 pt-1 col-sm-12">
                <label for="modality" class="form-control-label">Modality <span class="text-danger">*</span> :</label>
				<select data-plugin-selectTwo class="form-control populate" name="modality[]" id="modality[]" data-placeholder="Data Modality"  multiple disabled="disabled">
					<?php
						foreach ($modality as $row) {
						    echo '<option value="' . $row->id . '" selected="selected">' . $row->modality . '</option>';
						}
					?>
				</select>
            </div>
        </div>
        <div class="card-body" style="background-color:#FFF;padding:5%">
        <?= form_open('tiket/update/edit_on_detail', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <div id="value_preview">
            <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">NIK<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="nik" name="nik" value="<?= $datacaloncustomer->nik ?>" readonly> 
                     <label class="col-form-label">No NPWP<span class="text-danger">*</span></label>
                     <input class="form-control" type="text" id="nonpwp" name="nonpwp" value="<?= $datacaloncustomer->nonpwp ?>">
                        <label class="col-form-label">Nama NPWP<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="namanpwp" name="namanpwp" value="<?= $datacaloncustomer->namanpwp ?>">
            </div>    
            <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Jenis Faktur Pajak<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="jenisfakturpajak" name="jenisfakturpajak" value="<?= $datacaloncustomer->jenisfakturpajak ?>" readonly> 
                    <label class="col-form-label">Syarat Pembayaran<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="syaratpembayaran" name="syaratpembayaran" value="<?= $datacaloncustomer->syaratpembayaran ?>" readonly> 
                    <label class="col-form-label">Pengiriman Dokumen<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="pengirimandokumen" name="pengirimandokumen" value="<?= $datacaloncustomer->pengirimandokumen ?>" readonly> 
                    <label class="col-form-label">Status Piutang<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="statuspiutang" name="statuspiutang" value="<?= $datacaloncustomer->statuspiutang ?>" readonly> 
                    <label class="col-form-label">Limit Piutang<span class="text-danger">*</span></label>
                    <input class="form-control text-right" type="number" id="limitpiutang" name="limitpiutang" value="<?= $datacaloncustomer->limitpiutang ?>" readonly> 
            </div>    
            <div class="form-group mb-2 pt-1" >
                <label for="berkasdokumenpembayaran" class="col-form-label">Berkas Dokumen Pembayaran  <span class="text-danger">*</span> :</label>
                <?php
                    foreach ($berkasdokumenpembayaran as $row) {
                        echo '<div style="flex: 100%; padding: 10px;">';
                        echo '<input type="checkbox" id="chk'.$row->idberkas.'"  name="chkberkas[]" value="'.$row->idberkas.'" checked>'.$row->namaberkas;
						echo '<input type="text" class="form-control" id="link'.$row->idberkas.'" name="linkberkas[]" value="'.$row->linkberkas.'">';
                        echo '</div>';
                                //  echo '<option value="' . $row->id . '" selected>' . $row->namapic . '</option>';
                        }
                    ?>
            </div>   
            <div class="form-group mb-2 pt-1" >
                    <label for="alamatpengiriman" class="col-form-label">Alamat Pengiriman <span class="text-danger">*</span> :</label>
				    <textarea name="alamatpengiriman" class="form-control" id="alamatpengiriman" cols="15" rows="1"><?= $datacaloncustomer->alamatpengiriman ?></textarea>
                    <label for="alamatpenagihan" class="col-form-label">Alamat Penagihan <span class="text-danger">*</span> :</label>
				    <textarea name="alamatpenagihan" class="form-control" id="alamatpenagihan" cols="15" rows="1"><?= $datacaloncustomer->alamatpenagihan ?></textarea>
            </div>
            <div class="form-group mb-2 pt-1">
                    <label for="keterangan" class="col-form-label">Keterangan <span class="text-danger">*</span> :</label>
				    <textarea name="keterangan" class="form-control" id="keterangan" cols="15" rows="1"><?= $datacaloncustomer->keterangan ?></textarea>
            </div>  
            <div class="form-group mb-2 pt-1" >
                <label for="berkaslain" class="col-form-label">Link Scan Berkas Customer   <span class="text-danger">*</span> :</label>
                <?php
                    foreach ($berkaslain as $row) {
                        echo '<div style="flex: 100%; padding: 10px;">';
                        echo '<input type="checkbox" id="chk'.$row->idberkas.'"  name="chkberkas[]" value="'.$row->idberkas.'" checked>'.$row->namaberkas;
						echo '<input type="text" class="form-control" id="link'.$row->idberkas.'" name="linkberkas[]" value="'.$row->linkberkas.'">';
                        echo '</div>';
                                //  echo '<option value="' . $row->id . '" selected>' . $row->namapic . '</option>';
                        }
                    ?>
            </div>           
        </div>
        <div class="row-action-buttons">
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" data-dismiss="modal">Kembali</button>
        </div>
        <?= form_close(); ?>    
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $("#dpihakketiga").hide();
        $('#tipecustomer').focusout(function() {
           // alert(this.value)
			//var tipecustomer = this.value;
  			if (this.value == 'Pihak Ketiga'){
					$("#dpihakketiga").show();
                    $("#namapihakketiga").focus();
				}else{
					$("#dpihakketiga").hide();
				}	
		});
        $('#btnaddpic').click(function() {
           	$('#main-modal-pic #modal-form-pic').attr('action', 'pelangganan/addpic')
	 		$('#main-modal-pic').modal()
           
        });

         $('#btnclose').click(function() {
            window.location.reload()
           	
        });
    })		
    function goBack() {
      window.history.back();
    }
</script>