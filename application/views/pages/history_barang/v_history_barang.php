<header class="page-header">
	<h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="card-body">
            <a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp; Kartu Stock</a>
			
		</div>
		<br>
		
		<div class="card-body">
    <?php if(!empty($list_data)) { ?>
			<b>Nama Barang &nbsp;: &nbsp;  <?= $data_barang[0]->nama_barang ?></b><br>
			<b>Kode Barang &nbsp;&nbsp;&nbsp;: &nbsp;  <?= $data_barang[0]->kode_barang ?></b><br>
			<b>Priode	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp; <?= date('d-m-Y',strtotime($tglawal));?> &nbsp; s/d &nbsp; <?= date('d-m-Y',strtotime($tglakhir));?> </b><br><br><br>
			<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
							<thead>			
								<tr>
									<th style="text-align:center"> Tanggal</th>
									<th style="text-align:center"> Nomor </th>
									<th style="text-align:center" width="17%"> Customer / Pemasok </th>
									<th style="text-align:center"> Tipe Transaksi </th>
									<th style="text-align:center"> No Batch </th>
									<th style="text-align:center"> Exp Date </th>
									<th style="text-align:center"> Gudang </th>
									<th style="text-align:center"> Kts Masuk </th>
									<th style="text-align:center"> Kts Keluar </th>
									<th style="text-align:center"> Kts Akhir (masih error) </th>
								</tr>
							</thead>
							<tbody>

												<?php
													$tanggal_awal = '2017-01-01';
													$tanggal_akhir = date('Y-m-d', strtotime($tglawal . ' -1 day')); 
													$stok_awal = $this->md_history_barang->getStokByBarangIdDate($data_barang[0]->id_barang,$tanggal_awal,$tanggal_akhir);

													//$stok_total = $this->md_history_barang->getStokByBarangId($data_barang[0]->id_barang);  //Stok keseluruhan barang itu
													
													$sehariSebelum = date('d-m-Y', strtotime($tglawal . ' -1 day')); 
												?>


															<tr>
																	<td class="tgl_keluar" style="text-align:center"><?= $sehariSebelum ?></td>          <!-- Tanggal keluar -->
																	<td class="no_pengiriman"></td>    <!-- No pengiriman -->
																	<td class="no_pengiriman"></td>    <!-- customer -->
																	<td class="kategori">Saldo Awal &nbsp; <?= $sehariSebelum ?></td>        <!-- Kategori Pengeluaran Barang -->
																	<td class="no_batch_exit"></td>      <!-- No batch -->
																	<td class="exp_date_exit" style="text-align:center"></td>      <!-- Exp date -->
																	<td class="gudang_exit"></td>     <!-- Gudang tujuan -->
																	<td class="qty_exit" style="text-align:center"><?= !empty($stok_awal) ? $stok_awal : 0 ?></td>
																	<td class="qty_exit" style="text-align:center">0</td>                <!-- Kuantitas -->
																	<td class="qty_exit" style="text-align:center"><?= !empty($stok_awal) ? $stok_awal : 0 ?></td>

															</tr>

										<?php

												$total_masuk = 0;
												$total_keluar = 0;
												$total_stok = 0;


											foreach ($list_data as $row) {
													// Tentukan kategori untuk Penerimaan Stok
													if (!is_null($row->penerimaan_barang_id)) {
															$penerimaan_stok_kategori = 'Penerimaan Stok';
													} elseif (!is_null($row->id_pemasok)) {
															$penerimaan_stok_kategori = 'Penerimaan Barang';
													} else {
															$penerimaan_stok_kategori = null;
													}

													// Tentukan kategori untuk Pengiriman Stok
													if (!is_null($row->pengiriman_stok_id)) {
															$pengiriman_stok_kategori = 'Pengiriman Stok';
													} else {
															$pengiriman_stok_kategori = null;
													}

													// Tentukan kategori untuk Pengeluaran Barang
													if (!is_null($row->detail_barang_exit)) { // Jika detail_barang_exit tidak null
															$pengeluaran_kategori = 'Pengeluaran Barang';
													} else {
															$pengeluaran_kategori = null;
													}

													$stok_total = $this->md_history_barang->getStokByBarangIdDate($row->id_barang,$tanggal_awal,$tglakhir);


													$tanggal_end = date('Y-m-d', strtotime($row->tgl_masuk . ' +1 day')); 
												  $stok_awal2 = $this->md_history_barang->getStokByBarangIdDate($row->id_barang,$tanggal_awal,$tanggal_end);

													

													// Jika ada data untuk Pengiriman Stok atau Penerimaan Stok
													if ($penerimaan_stok_kategori == 'Penerimaan Stok' && $pengiriman_stok_kategori == 'Pengiriman Stok') {
															// Baris pertama: Pengiriman Stok
															$no_terima2 = '<a href="pengiriman_stok/edit/' . encrypt($row->id_pengiriman_stok) . '">' . $row->no_terima . '</a>';

															$stok_kirim = $row->qty;
															$total_kirim = $stok_awal-$stok_kirim;

															
        											$total_keluar += $stok_kirim;
															?>
															<tr>
																	<td class="tgl_masuk" style="text-align:center"><?= date('d-m-Y', strtotime($row->tgl_pengiriman)); ?></td>
																	<td class="no_terima"><?= $no_terima2 ?></td>
																	<td class="no_terima"></td>
																	<td class="kategori"><?= $pengiriman_stok_kategori ?></td>
																	<td class="no_batch"><?= $row->no_batch ?></td>
																	<td class="exp_date" style="text-align:center"><?= $row->exp_date ?></td>
																	<td class="gudang_asal"><?= $row->gudang_asal ?></td>
																	<td class="qty" style="text-align:center">0</td>
																	<td class="qty" style="text-align:center"><?= $row->qty ?></td>
																	<td class="qty" style="text-align:center"></td>
															</tr>
															<?php
															// Baris kedua: Penerimaan Stok
															$no_terima = '<a href="penerimaan_stok/edit/' . encrypt($row->id_penerimaan_stok) . '">' . $row->no_terima . '</a>';

															$stok_terima = $row->qty;
															$total_terima = $stok_awal+$stok_terima;

															// Update total penerimaan
        											$total_masuk += $stok_terima;
        											//$total_masuk += $stok_terima;

															?>
															<tr>
																	<td class="tgl_masuk" style="text-align:center"><?= date('d-m-Y', strtotime($row->tgl_penerimaan_stok)); ?></td>
																	<td class="no_terima"><?= $no_terima ?></td>
																	<td class="no_terima"></td>
																	<td class="kategori"><?= $penerimaan_stok_kategori ?></td>
																	<td class="no_batch"><?= $row->no_batch ?></td>
																	<td class="exp_date" style="text-align:center"><?= $row->exp_date ?></td>
																	<td class="gudang"><?= $row->nama_gudang ?></td>
																	<td class="qty" style="text-align:center"><?= $row->qty ?></td>
																	<td class="qty" style="text-align:center">0</td>
																	<td class="qty" style="text-align:center"></td>
															</tr>
															<?php
													}
 													elseif ($penerimaan_stok_kategori || $pengiriman_stok_kategori) {
															// Jika hanya ada salah satu kategori
															 $no_terima3	= '<a href="penerimaan_barang/edit/'.encrypt($row->id_penerimaan_barang).'">'.$row->no_terima.'</a>'; 

															 $stok_terima2 = $row->qty;
															 $total_terima2 = $stok_awal2+$stok_terima2;

															 // Update total penerimaan
        											 $total_masuk += $stok_terima2;
															
															?>
															<tr>
																	<td class="tgl_masuk" style="text-align:center"><?= date('d-m-Y',strtotime($row->tgl_masuk)); ?></td>
																	<td class="no_terima"><?= $no_terima3 ?></td>
																	<td class="no_terima"><?= $row->nama_pemasok ?></td>
																	<td class="kategori"><?= $penerimaan_stok_kategori ?: $pengiriman_stok_kategori ?></td>
																	<td class="no_batch"><?= $row->no_batch ?></td>
																	<td class="exp_date" style="text-align:center"><?= $row->exp_date ?></td>
																	<td class="nama_gudang"><?= $row->nama_gudang ?></td>
																	<td class="qty" style="text-align:center"><?= $row->qty ?></td>
																	<td class="qty" style="text-align:center">0</td>
																	<td class="qty" style="text-align:center"></td>
															</tr>
															<?php
													}

													// Jika ada data untuk Pengeluaran Barang
													if (!is_null($pengeluaran_kategori)) {
															$no_pengiriman	= '<a href="pengeluaran_barang/edit/'.encrypt($row->id_pengeluaran_barang).'">'.$row->no_pengiriman.'</a>';

															$stok_exit = $row->qty_exit;
															$total_exit = $stok_awal-$stok_exit;

															
        											$total_keluar += $stok_exit;
															?>
															<tr>
																	<td class="tgl_keluar" style="text-align:center"><?= date('d-m-Y',strtotime($row->tgl_keluar));?></td>          <!-- Tanggal keluar -->
																	<td class="no_pengiriman"><?= $no_pengiriman ?></td>    <!-- No pengiriman -->
																	<td class="no_pengiriman"><?= $row->nama_customer ?></td>    <!-- customer -->
																	<td class="kategori"><?= $pengeluaran_kategori ?></td>        <!-- Kategori Pengeluaran Barang -->
																	<td class="no_batch_exit"><?= $row->no_batch ?></td>      <!-- No batch -->
																	<td class="exp_date_exit" style="text-align:center"><?= $row->exp_date_exit ?></td>      <!-- Exp date -->
																	<td class="gudang_exit"><?= $row->nama_gudang_exit ?></td>     <!-- Gudang tujuan -->
																	<td class="qty_exit" style="text-align:center">0</td>                <!-- Kuantitas -->
																	<td class="qty_exit" style="text-align:center"><?= $row->qty_exit ?></td>                <!-- Kuantitas -->
																	<td class="qty_exit" style="text-align:center"></td>                <!-- Kuantitas -->
															</tr>
															<?php
													}


											}
											?>




														<tr>
																<td colspan="7" style="text-align:right"><strong>Total :</strong></td>
																<td style="text-align:center"><?= $total_masuk+$stok_awal ?></td>
																<td style="text-align:center"><?= $total_keluar ?></td>
																<td style="text-align:center"><?= $total_stok += $stok_total ?></td>
														</tr>
													
                        </tbody>
					</table>	
				</div>
				
				<?php } else { ?>
         <p>Belum ada data untuk ditampilkan.</p>
  			<?php } ?>
		</div>
	</div>
</div>



<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Cetak Kartu Stock  </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?> 
			<div class="modal-body">
				<div class="dt-marketing-form">
						<label>Pilih Tanggal</label>
				    <div class="form-group" style="display: flex;">
				        <div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
						</div>
						<div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
						</div>
					</div>
					<div class="form-group mb-2 pt-1">
            <div class="col-form-label">
            <label>Pilih Barang</label>
                <select data-plugin-selectTwo="search_barang" class="form-control search_barang_diform filter-grup" name="id_barang[]" id="id_barang"></select>
            </div>
          </div>	
					<br><label>Harap isi semua form</label>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form" >Export Excel</button>
				<button type="button" id="btn-tampilkan" class="btn btn-success btn-clear-form" >Tampilkan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		$(".search_barang_diform").themePluginSelect2({
            placeholder: "--- Ketik Nama Barang ---",
            allowClear: true,
            minimumInputLength: 1,
            width: '100%',
            ajax: {
                method: 'POST',
                url: "barang/get/by_search",
                dataType: 'json',
                delay: 250,
                data:

                    function(params) {
                        return {
                            q: params.term, // search term
                            csrf_token: token
                        };
                    },
                processResults: function(data, params) {
                    return {

                        results: $.map(data.items, function(obj) {
                            return {
                                id: obj.id_barang,
                                text: `${obj.nama_barang}`
                            };
                        })
                    }
                },
                cache: true
            },
        });


/** 

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			
			// order: [
			// 	[0, 'ASC']
			// ],
			ajax: {
				url: 'history_barang/pagination',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				// targets: [0, 1, 2],
				// className: 'text-center'
			}]
		})
		function updateDatatable() {
		table.ajax.reload(null, false)
	}
		
		*/


		$('#btn-cetaklaporan-form').click(function() {
		     $('#main-modal-marketing').modal()	
		     
    			
		})

		$("#btn-export").click(function(){
		      
                tglawal = $("#tglawal").val();
                tglakhir = $("#tglakhir").val();
                id_barang = $("#id_barang").val();
                //namamarketing = $("#namamarketing option:selected").text();
                 window.open("<?php echo base_url(); ?>history_barang/exportlaporan/search?tglawal="+encodeURIComponent(tglawal)+"&tglakhir="+encodeURIComponent(tglakhir)+"&id_barang="+encodeURIComponent(id_barang),"_blank");
                $('#main-modal-marketing').modal('hide')
			
        });


		$("#btn-tampilkan").click(function(){
			tglawal = $("#tglawal").val();
			tglakhir = $("#tglakhir").val();
			id_barang = $("#id_barang").val();
			
			// Ganti window.open dengan window.location.href untuk reload halaman yang sama
			window.location.href = "<?php echo base_url(); ?>history_barang/index/search?tglawal=" + encodeURIComponent(tglawal) + "&tglakhir=" + encodeURIComponent(tglakhir) + "&id_barang=" + encodeURIComponent(id_barang);
			
			// Menyembunyikan modal setelah klik
			$('#main-modal-marketing').modal('hide');
	});


	

		
	})

	
</script>