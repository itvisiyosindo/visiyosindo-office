<header class="page-header">
	<h2><i class="fas fa-clipboard-list"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<br>
		<div class="card-body">
			<div class="table-responsive">

				<div>
						<h4>Nilai Rata-Rata Laporan Mingguan dari Tanggal <strong> <?= $startDate ?> </strong> sampai <strong> <?= $endDate ?> </strong></h4>
						<style>
								tbody tr:nth-child(odd) {
										background-color: #f2f2f2; /* Warna abu-abu muda */
								}
						</style>
					<div style="overflow-x: auto; white-space: nowrap; max-width: 100%;">
										<table border="1" style="width: 100%; table-layout: fixed;">

                            <thead>
                                <tr>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> No </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="25%"><font color='#000000'> Nama</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> NPP</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="20%"><font color='#000000'> Jabatan </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="7%"><font color='#000000'> Nilai Rata-Rata </th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php 
                                $no = 1;

                                foreach ($data_detail as $row) {

                                    
                                    
                                ?>
                                    <tr>
                                        <td style="text-align:center"><font color='#000000'><?= $no++ ?></td>
                                        <td><font color='#000000'><?= $row->nama ?></td>
																				<td><font color='#000000'><?= $row->no_pegawai ?></td>
                                        <td><font color='#000000'><?= $row->jabatan ?></td>
                                        <td><font color='#000000'><?= number_format($row->rataNilai, 2) ?></td>

                                        
                                    </tr>

                                <?php } ?>

                                
                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
								<a href="<?= base_url('laporan/show/list/nilai/' . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/list/nilai/' . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>
						</div>
				</div>
				

			</div>
		</div>
	</div>
</div>





<script>
	

		
	function updateDatatable() {
		table.ajax.reload(null, false)
	}



		
</script>