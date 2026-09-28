<header class="page-header">
	<h2><i class="fas fa-tachometer-alt"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>
<style>
    .big-icon {
        font-size: 45px; /* Sesuaikan ukuran sesuai kebutuhan */
    }
</style>


<div class="container-fluid mt-4">

    <!-- Tombol Cetak Rekapan di Kanan Atas -->
    <div class="d-flex justify-content-end mb-3">
        <a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-sm btn-primary">
            <i class="fas fa-download"></i>&nbsp;&nbsp;&nbsp;Generate Data
        </a>
    </div>

    <div class="row">
        <!-- Total Ticket -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">TOTAL TICKET</h6>
                        <h3 class="font-weight-bold"><?= $total_ticket ?></h3>
                    </div>
                    <i class="bx bx-receipt bx-lg text-primary big-icon"></i>
                </div>
            </div>
        </div>

        <!-- Ticket Open -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        	    <a href="javascript:;" id="btn-show-open">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">TICKET OPEN</h6>
                        <h3 class="font-weight-bold"><?= $ticket_open ?></h3>
                    </div>
                    <i class="bx bx-folder-open text-danger big-icon"></i>
                </div>
            </div>
                </a>
        </div>

        <!-- Ticket Submit Laporan Akhir -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        	    <a href="javascript:;" id="btn-show-submit">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">TICKET SUBMIT</h6>
                        <h3 class="font-weight-bold"><?= $ticket_submit ?></h3>
                    </div>
                    <i class="bx bx-upload bx-lg text-warning big-icon"></i>
                </div>
            </div>
                </a>
        </div>

        <!-- Ticket Closed -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">TICKET CLOSED</h6>
                        <h3 class="font-weight-bold"><?= $ticket_closed ?></h3>
                    </div>
                    <i class="bx bx-check-circle bx-lg text-success big-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row">
        <!-- Tickets by Teknisi -->
        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="text-muted">Tickets by Teknisi</h6>
                    <canvas id="ticketsByTeknisi"></canvas>
                </div>
            </div>
        </div>
        <!-- AVG Response Time -->
        <div class="col-lg-6 col-sm-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="text-muted">Avg Response Time</h6>
                    <canvas id="avgResponTime"></canvas>
                </div>
            </div>
        </div>
    </div>


    <!-- Chart Section -->
    <div class="row">
        
         <!-- Tickets by Kategori -->
        <div class="col-lg-5 col-sm-12">
            <div class="card shadow-sm border-0 h-100 mb-3">
                <div class="card-body">
                    <h6 class="text-muted">Tickets by Kategori</h6>
                    <div style="position: relative; width: 100%; height: auto;">
                        <canvas id="ticketsByCategory" style="max-height: 350px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Percentage Completed -->
		<div class="col-lg-4 col-md-6 col-sm-12 mb-3">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<h6 class="text-muted">PERCENTAGE COMPLETED</h6>
					<div class="d-flex justify-content-between align-items-center">
						<h3 class="font-weight-bold"><?= $percent_ticket ?>%</h3>
							<i class="bx bx-task bx-lg text-info big-icon"></i>
					</div>
					<div class="progress mt-2" style="height: 5px;">
						<div class="progress-bar bg-info" role="progressbar" style="width: <?= $percent_ticket ?>%;" 
							aria-valuenow="<?= $percent_ticket ?>" aria-valuemin="0" aria-valuemax="100">
                        </div>
					</div>
				</div>
			</div>
		</div>

    </div>

    <br>
    <!-- Earnings Overview -->
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="text-muted">Detail Ticket Instalasi & Trouble</h6>
                    <canvas id="avgCloseTicketChart" style="max-height: 400px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>




<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Cetak Tiket </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?> 
			<div class="modal-body">
				<div class="dt-marketing-form">
					 <?php
							$tanggal = date('d');
							$bulanArray = [
									1 => 'Januari', 
									2 => 'Februari', 
									3 => 'Maret', 
									4 => 'April', 
									5 => 'Mei', 
									6 => 'Juni', 
									7 => 'Juli', 
									8 => 'Agustus', 
									9 => 'September', 
									10 => 'Oktober', 
									11 => 'November', 
									12 => 'Desember'
							];
							$bulan = $bulanArray[date('n')];
							$tahun = date('Y');
						?>
							<div class="form-group" style="display: flex;">
									Cetak Data Tiket Tanggal : <?php echo $tanggal . ' ' . $bulan . ' ' . $tahun; ?>
							</div>
				   
					<div class="form-group">
						<label class="control-label">Nama Alat / Topik</label>
						 <select class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
								<?php if ($kategori != NULL): ?>
													<option value=''>Semua Alat</option>
													<?php foreach ($kategori as $value): ?>
													<option value="<?php echo $value->id_topik;?>"><?php echo $value->nama;?></option>
													<?php endforeach;?>
													<?php else:?>
													<option value=''>— Tidak ada data —</option>
													<?php endif;?>
													</select>
													<?php echo form_error('nama_marketing');?>
					</div>	

					<div class="form-group">
						<label class="control-label">Kategori</label>
						 <select class="select-transaction input-group-sm form-control" name="idkat" id="idkat">
													<option value=''>Semua Kategori</option>
													<option value='Instalasi'>Instalasi</option>
													<option value='Trouble'>Trouble</option>
													<option value='Uji Kesesuaian'>Uji Kesesuaian (UKES)</option>
													<option value='Uji Paparan'>Uji Paparan (UPAR)</option>
						 </select>
					</div>	
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-export" class="btn btn-primary btn-clear-form" ><i class="fas fa-file-excel"></i>  Export Excel</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<div id="main-modal-open" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Ticket Open</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Kode</th>
							<th>Pelanggan</th>
							<th>Teknisi</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_open as $row) { 

                            if ($row->status_tiket == 1) {
                                $stiket = '<span class="badge badge-ecommerce badge-primary">Baru</span>';
                            } else {
                                $stiket = '<span class="badge badge-ecommerce badge-info">Dalam Proses</span>';
                            }

                            $id     = encrypt($row->id_tiket);
					        $koTik  = '<a href="tiket/show/detail_tiket/' . $id . '")>' . $row->kode_tiket . '</a>';
                        
                        ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $koTik ?></td>
								<td><?= $row->pelanggan ?></td>
								<td><?= $row->nama_penerima ?></td>
								<td><?= $stiket ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>


<div id="main-modal-submit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Ticket Open</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable1" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Kode</th>
							<th>Pelanggan</th>
							<th>Teknisi</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_submit as $row) { 

                            if ($row->status_tiket == 1) {
                                $stiket = '<span class="badge badge-ecommerce badge-primary">Baru</span>';
                            } else {
                                $stiket = '<span class="badge badge-ecommerce badge-info">Dalam Proses</span>';
                            }

                            $id     = encrypt($row->id_tiket);
					        $koTik  = '<a href="tiket/show/detail_tiket/' . $id . '")>' . $row->kode_tiket . '</a>';
                        
                        ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $koTik ?></td>
								<td><?= $row->pelanggan ?></td>
								<td><?= $row->nama_penerima ?></td>
								<td><?= $stiket ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>



<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    //Tiket di teknisi
    var ctx = document.getElementById('ticketsByTeknisi').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($tickets_by_technician)) ?>,
            datasets: [{
                label: 'Tickets',
                data: <?= json_encode(array_values($tickets_by_technician)) ?>,
                backgroundColor: ['#ffcc00', '#ff9900', '#3366ff', '#33cc33', '#999894']
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });


    //AVG Respon Time
    var ctx4 = document.getElementById('avgResponTime').getContext('2d');
    new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($avg_respon_time)) ?>,
            datasets: [{
                label: 'Time (In Minutes)',
                data: <?= json_encode(array_values($avg_respon_time)) ?>,
                backgroundColor: ['#ffcc00', '#ff9900', '#3366ff', '#33cc33', '#999894']
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });



    //Detail Tiket Instalasi & Trouble

    var detailTicket = <?= json_encode($detail_ticket); ?>;

    var labels = detailTicket.map(item => item.alat);
    var dataKategori1 = detailTicket.map(item => item.instalasi);
    var dataKategori2 = detailTicket.map(item => item.trouble);

    var ctx2 = document.getElementById("avgCloseTicketChart").getContext("2d");

    var avgCloseTicketChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Instalasi",
                    data: dataKategori1,
                    backgroundColor: 'rgba(0, 123, 255, 1)',
                    borderRadius: 5
                },
                {
                    label: "Trouble",
                    data: dataKategori2,
                    backgroundColor: 'rgba(220, 53, 69, 1)',
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + " Ticket";
                        }
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            plugins: {
                legend: { display: true }
            }
        }
    });



    
    // Kategori Tiket
    var ctx3 = document.getElementById('ticketsByCategory').getContext('2d');
    var ticketByCategory = <?= json_encode($ticket_by_category) ?>;

    new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: Object.keys(ticketByCategory),
            datasets: [{
                data: Object.values(ticketByCategory),
                backgroundColor: ['#007bff', '#dc3545', '#28a745', '#6f42c1'],
                borderColor: '#ffffff', // Tambahkan border putih agar jelas
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Agar bisa menyesuaikan ukuran
            cutout: '50%', // Supaya bagian tengah tidak terlalu besar
            plugins: {
                legend: {
                    position: 'bottom' // Agar legend tidak menutupi chart
                }
            }
        }
    });






document.addEventListener('DOMContentLoaded', function () {

    	$('#myTable').DataTable();
		$('#myTable1').DataTable();

        $('#btn-show-open').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-open').modal()
		})

        $('#btn-show-submit').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-submit').modal()
		})






    $('#btn-cetaklaporan-form').click(function() {
		     $('#main-modal-marketing').modal()	
		     
    			
		})

		$("#btn-export").click(function(){
		      
                idmarketing = $("#namamarketing").val();
                namamarketing = $("#namamarketing option:selected").text();
								idkat = $("#idkat").val();
                 window.open("<?php echo base_url(); ?>tiket/exportlaporan/search?idmarketing="+encodeURIComponent(idmarketing)+"&namamarketing="+encodeURIComponent(namamarketing)+"&idkat="+encodeURIComponent(idkat),"_blank");
                $('#main-modal-marketing').modal('hide')
			
        });


	})
    
</script>


