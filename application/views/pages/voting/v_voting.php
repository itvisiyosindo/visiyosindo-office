<header class="page-header">
	<h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>
<?php if (isAdmin() || sessPenggunaId() == '29') { ?>
    <div id="main-modal-hasilvotingdicipline" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content ">
    			<div class="modal-header bg-dark text-light">
    				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Hasil Voting</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
    			</div>
    			<?= form_open('#', array('id' => 'modal-form-hasilvotingdicipline', 'autocomplete' => 'off')); ?>
    				<div class="modal-footer">
    				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
    			</div>
    			<div class="row">
    				<div class="col">
    					<div class="card-body">
    						 <div class="table-responsive">
    							<table class="table table-striped table-bordered table-hover" id='kt_table_2'>
    								<thead>
    									<tr>
    									<th> # </th>
    									<th> Nama </th>
            							<th> Total Best Dicipline Employee</th>
    									</tr>
    								</thead>
    								
    							</table>
    						 </div>
    					</div>
    				</div>
    
    			</div>
    
    			<?= form_close(); ?>
    		</div>
    	</div>
    </div>
    <div id="main-modal-hasilvotingprofesional" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content ">
    			<div class="modal-header bg-dark text-light">
    				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Hasil Voting</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
    			</div>
    			<?= form_open('#', array('id' => 'modal-form-hasilvotingprofesional', 'autocomplete' => 'off')); ?>
    				<div class="modal-footer">
    				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
    			</div>
    			<div class="row">
    				<div class="col">
    					<div class="card-body">
    						 <div class="table-responsive">
    							<table class="table table-striped table-bordered table-hover" id='kt_table_3'>
    								<thead>
    									<tr>
    									<th> # </th>
    									<th> Nama </th>
            							<th> Total Best Profesional Employee</th>
    									</tr>
    								</thead>
    								
    							</table>
    						 </div>
    					</div>
    				</div>
    
    			</div>
    
    			<?= form_close(); ?>
    		</div>
    	</div>
    </div>
    <div id="main-modal-hasilvotingcreative" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content ">
    			<div class="modal-header bg-dark text-light">
    				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Hasil Voting</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
    			</div>
    			<?= form_open('#', array('id' => 'modal-form-hasilvotingcreative', 'autocomplete' => 'off')); ?>
    				<div class="modal-footer">
    				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
    			</div>
    			<div class="row">
    				<div class="col">
    					<div class="card-body">
    						 <div class="table-responsive">
    							<table class="table table-striped table-bordered table-hover" id='kt_table_4'>
    								<thead>
    									<tr>
    									<th> # </th>
    									<th> Nama </th>
            							<th> Total Best Creative Employee</th>
    									</tr>
    								</thead>
    								
    							</table>
    						 </div>
    					</div>
    				</div>
    
    			</div>
    
    			<?= form_close(); ?>
    		</div>
    	</div>
    </div>
    <div id="main-modal-hasilvotingsales" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content ">
    			<div class="modal-header bg-dark text-light">
    				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Hasil Voting</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
    			</div>
    			<?= form_open('#', array('id' => 'modal-form-hasilvotingsales', 'autocomplete' => 'off')); ?>
    				<div class="modal-footer">
    				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
    			</div>
    			<div class="row">
    				<div class="col">
    					<div class="card-body">
    						 <div class="table-responsive">
    							<table class="table table-striped table-bordered table-hover" id='kt_table_5'>
    								<thead>
    									<tr>
    									<th> # </th>
    									<th> Nama </th>
            							<th> Total Best Sales Achievement</th>
    									</tr>
    								</thead>
    								
    							</table>
    						 </div>
    					</div>
    				</div>
    
    			</div>
    
    			<?= form_close(); ?>
    		</div>
    	</div>
    </div>
    <div id="main-modal-hasilvotingoperational" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content ">
    			<div class="modal-header bg-dark text-light">
    				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Hasil Voting</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
    			</div>
    			<?= form_open('#', array('id' => 'modal-form-hasilvotingoperational', 'autocomplete' => 'off')); ?>
    				<div class="modal-footer">
    				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
    			</div>
    			<div class="row">
    				<div class="col">
    					<div class="card-body">
    						 <div class="table-responsive">
    							<table class="table table-striped table-bordered table-hover" id='kt_table_6'>
    								<thead>
    									<tr>
    									<th> # </th>
    									<th> Nama </th>
            							<th> Total Best Operational Support</th>
    									</tr>
    								</thead>
    								
    							</table>
    						 </div>
    					</div>
    				</div>
    
    			</div>
    
    			<?= form_close(); ?>
    		</div>
    	</div>
    </div>
    <div id="main-modal-hasilvotingbestofyear" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content ">
    			<div class="modal-header bg-dark text-light">
    				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Hasil Voting</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
    			</div>
    			<?= form_open('#', array('id' => 'modal-form-hasilvotingbestofyear', 'autocomplete' => 'off')); ?>
    				<div class="modal-footer">
    				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
    			</div>
    			<div class="row">
    				<div class="col">
    					<div class="card-body">
    						 <div class="table-responsive">
    							<table class="table table-striped table-bordered table-hover" id='kt_table_7'>
    								<thead>
    									<tr>
    									<th> # </th>
    									<th> Nama </th>
            							<th> Total Employee of The Year</th>
    									</tr>
    								</thead>
    								
    							</table>
    						 </div>
    					</div>
    				</div>
    
    			</div>
    
    			<?= form_close(); ?>
    		</div>
    	</div>
    </div>
    <div id="main-modal-hasilvotingsudahvoting" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content ">
    			<div class="modal-header bg-dark text-light">
    				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Hasil Voting</h5>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
    			</div>
    			<?= form_open('#', array('id' => 'modal-form-hasilvotingsudahvoting', 'autocomplete' => 'off')); ?>
    				<div class="modal-footer">
    				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
    			</div>
    			<div class="row">
    				<div class="col">
    					<div class="card-body">
    						 <div class="table-responsive">
    							<table class="table table-striped table-bordered table-hover" id='kt_table_8'>
    								<thead>
    									<tr>
    									<th> # </th>
    									<th> Nama </th>
    									<th> Best Professional Employee</th>
            							<th> Best Creative Employee </th>
            							<th> Best Operational Support </th>
            							<th> Best Employee of The Year </th>
    									</tr>
    								</thead>
    								
    							</table>
    						 </div>
    					</div>
    				</div>
    
    			</div>
    
    			<?= form_close(); ?>
    		</div>
    	</div>
    </div>
<?php } ?>
<div class="row">
	<div class="col">
	     <div class="ml-3">
            
            <button type="button" id="btn-totalvoting" class="btn btn-light btn-vote"><i class="icons fas fa-poll"></i> Total Voting : <?= $totalvoting ?> </button>
            <?php if (isAdmin() || sessPenggunaId() == '29') { ?>
             </br> </br>
                <button type="button" id="btn-totaldiciplined" class="btn btn-success btn-vote"><i class="icons fas fa-poll"></i> Best Diciplined </button>
                <button type="button" id="btn-totalprofesional" class="btn btn-success btn-vote"><i class="icons fas fa-poll"></i> Best Professional Employee </button>
                 <button type="button" id="btn-totalcreative" class="btn btn-success btn-vote"><i class="icons fas fa-poll"></i> Best Creative Employee </button>
                  <button type="button" id="btn-totalsales" class="btn btn-success btn-vote"><i class="icons fas fa-poll"></i>  Best Sales Achievement</button>
                   <button type="button" id="btn-totaloperational" class="btn btn-success btn-vote"><i class="icons fas fa-poll"></i> Best Operational Support </button>
                    <button type="button" id="btn-totalbestofyear" class="btn btn-success btn-vote"><i class="icons fas fa-poll"></i> Best Employee of The Year</button>
                    <button type="button" id="btn-daftarpengguna" class="btn btn-danger btn-vote"><i class="icons fas fa-poll"></i> Daftar Voting</button>
            <?php } ?>
        </div>
        </br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead style="text-align: center;">
						<tr>
							<th> # </th>
							<th> Best Disciplined Employee </th>
							<th> Best Professional Employee</th>
							<th> Best Creative Employee </th>
							<th> Best Sales Achievement </th>
							<th> Best Operational Support </th>
							<th> Best Employee of The Year </th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>



<script>
	document.addEventListener('DOMContentLoaded', function() {
	        table = $('#kt_table_1').DataTable({
    			responsive: false,
    			processing: true,
    			serverSide: true,
    			order: [
    				[0, 'desc']
    			],
    			ajax: {
    				url: 'voting/pagination/0',
    				type: 'POST',
    				data: function(e) {
    					e.tahun = $('#tahun').val()
    					e.csrf_token = token
    				}
    			},
    			iDisplayLength: 100,
    			searching : false,
    			columns: [
    			    { "width": "1%" },
    				{ "width": "16.5%" },
    				{ "width": "16.5%" },
    				{ "width": "16.5%" },
    				{ "width": "16.5%" },
    				{ "width": "16.5%" },
    				{ "width": "16.5%" },
    			],
    			columnDefs: [
    			    {
    				    targets: [0,1,2,3,4,5,6],
    				    className: 'text-center'
    			    },
    			    {
    				  'visible': false, 
    				  'targets': [1,4],
    				
			        }
    			]
	    	})
	    	
	    	 table2 = $('#kt_table_2').DataTable({
    			responsive: false,
    			processing: true,
    			serverSide: true,
    			order: [
    				[0, 'desc']
    			],
    			ajax: {
    				url: 'voting/paginationhasilvotingdicipline',
    				type: 'POST',
    				data: function(e) {
    					e.tahun = $('#tahun').val()
    					e.csrf_token = token
    				}
    			},
	    	})
	    	table3 = $('#kt_table_3').DataTable({
    			responsive: false,
    			processing: true,
    			serverSide: true,
    			order: [
    				[0, 'desc']
    			],
    			ajax: {
    				url: 'voting/paginationhasilvotingprofesional',
    				type: 'POST',
    				data: function(e) {
    					e.tahun = $('#tahun').val()
    					e.csrf_token = token
    				}
    			},
	    	})
	    	table4 = $('#kt_table_4').DataTable({
    			responsive: false,
    			processing: true,
    			serverSide: true,
    			order: [
    				[0, 'desc']
    			],
    			ajax: {
    				url: 'voting/paginationhasilvotingcreative',
    				type: 'POST',
    				data: function(e) {
    					e.tahun = $('#tahun').val()
    					e.csrf_token = token
    				}
    			},
	    	})
	    	table5 = $('#kt_table_5').DataTable({
    			responsive: false,
    			processing: true,
    			serverSide: true,
    			order: [
    				[0, 'desc']
    			],
    			ajax: {
    				url: 'voting/paginationhasilvotingsales',
    				type: 'POST',
    				data: function(e) {
    					e.tahun = $('#tahun').val()
    					e.csrf_token = token
    				}
    			},
	    	})
	    	table6 = $('#kt_table_6').DataTable({
    			responsive: false,
    			processing: true,
    			serverSide: true,
    			order: [
    				[0, 'desc']
    			],
    			ajax: {
    				url: 'voting/paginationhasilvotingoperational',
    				type: 'POST',
    				data: function(e) {
    					e.tahun = $('#tahun').val()
    					e.csrf_token = token
    				}
    			},
	    	})
	    	table7 = $('#kt_table_7').DataTable({
    			responsive: false,
    			processing: true,
    			serverSide: true,
    			order: [
    				[0, 'desc']
    			],
    			ajax: {
    				url: 'voting/paginationhasilvotingbestofyear',
    				type: 'POST',
    				data: function(e) {
    					e.tahun = $('#tahun').val()
    					e.csrf_token = token
    				}
    			},
	    	})
	    	table8 = $('#kt_table_8').DataTable({
    			responsive: false,
    			processing: true,
    			serverSide: true,
    			order: [
    				[0, 'desc']
    			],
    			ajax: {
    				url: 'voting/paginationhasilvotingsudahvoting',
    				type: 'POST',
    				data: function(e) {
    					e.tahun = $('#tahun').val()
    					e.csrf_token = token
    				}
    			},
    			iDisplayLength: 100,
	    	})
	    	
	    $("#btn-totaldiciplined").click(function(){
	          $('#main-modal-hasilvotingdicipline').modal()	
	    });
	     $("#btn-totalprofesional").click(function(){
	          $('#main-modal-hasilvotingprofesional').modal()	
	    });
	     $("#btn-totalcreative").click(function(){
	          $('#main-modal-hasilvotingcreative').modal()	
	    });
	    $("#btn-totalsales").click(function(){
	          $('#main-modal-hasilvotingsales').modal()	
	    });
	     $("#btn-totaloperational").click(function(){
	          $('#main-modal-hasilvotingoperational').modal()	
	    });
	     $("#btn-totalbestofyear").click(function(){
	          $('#main-modal-hasilvotingbestofyear').modal()	
	    });
	    $("#btn-daftarpengguna").click(function(){
	          $('#main-modal-hasilvotingsudahvoting').modal()	
	    });

		$('#btn-voting').click(function() {
		    $.ajax({
				method: 'POST',
				url: 'voting/add',
				dataType: 'json',
				data: {
					csrf_token: token
				},
				success: function(resp) {
					handleResponse(resp)
				}
			})
			table.ajax.url('voting/pagination/1').load();
			$('#kt_table').DataTable().ajax.reload();
		})
		
		$("#kt_table_1").on('click','.btn-edit',function(e){	
			e.preventDefault();
			var par = $(this).data("id"); 
			var url = ''
			if(par.indexOf("dicipline") > -1){
				url = 'voting/updatedicipline/'+par;
			}else if(par.indexOf("profesional") > -1){
				url = 'voting/updateprofesional/'+par;
			}else if(par.indexOf("creative") > -1){
				url = 'voting/updatecreative/'+par;
			}else if(par.indexOf("sales") > -1){
				url = 'voting/updatesales/'+par;
			}else if(par.indexOf("operational") > -1){
				url = 'voting/updateoperational/'+par;
			}else if(par.indexOf("bestofyear") > -1){
				url = 'voting/updatebestofyear/'+par;
			}
			 swal.fire({
					title: "Apakah Yakin memvotenya..?",
					text: "",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "VOTE",
					closeOnConfirm: false
				}).then((result) => {
			            	if (result.isConfirmed) {
				       
							$.ajax({
								type: "POST",
								url: url,
								async: false,
							});		
							
					}
				})
		})

		
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>