<?php 


function status_perkawinan(){
	return $status =[
	    ['id' =>1,'jenis'=>'TK0','jumlah' =>54000000],
	    ['id' =>2,'jenis'=>'TK1','jumlah' =>58500000],
	    ['id' =>3,'jenis'=>'TK2','jumlah' =>63000000],
	    ['id' =>4,'jenis'=>'TK3','jumlah' =>67500000],
	    ['id' =>5,'jenis'=>'K0','jumlah' =>58500000],
	    ['id' =>6,'jenis'=>'K1','jumlah' =>63000000],
	    ['id' =>7,'jenis'=>'K2','jumlah' =>67500000],
	    ['id' =>8,'jenis'=>'K3','jumlah' =>72000000],
	    ['id' =>9,'jenis'=>'K/1/0','jumlah' =>112500000],
	    ['id' =>10,'jenis'=>'K/1/1','jumlah' =>117000000],
	    ['id' =>11,'jenis'=>'K/1/2','jumlah' =>121500000],
	    ['id' =>12,'jenis'=>'K/1/3','jumlah' =>126500000],
	    ];
}

function statusFindbyId($id){
    $cari = array_search($id, array_column(status_perkawinan(), 'id'));
 
		return 	status_perkawinan()[$cari];
}



function umk(){
	return $data =[
		['id' =>1,'kota'=>'pekanbaru','jumlah' =>3319023],
	    ['id' =>2,'kota'=>'medan','jumlah' =>3624117],
	    ['id' =>3,'kota'=>'yogyakarta','jumlah' =>3319023],
	    ['id' =>4,'kota'=>'bekasi','jumlah' =>4901798],
	    ['id' =>5,'kota'=>'jakarta','jumlah' =>4901798],
	];
}

function findUmkById($id){
    $cari = array_search($id, array_column(umk(), 'id'));
 
		return 	umk()[$cari];
}

