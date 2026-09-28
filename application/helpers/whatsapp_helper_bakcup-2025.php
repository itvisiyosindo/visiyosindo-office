<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Menentukan Host Wa -----------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function hostWa($param)
{
	if ($param == '1') {
		$devId = '7388d9d2431fbc65c0ce49a4fac7550b'; //device ID AdminIT di WhaCenter
	} else if ($param == '2') {
		$devId = 'fc9006c3cb197a0d0fd951813348e102'; //device ID CRO di WhaCenter
	}
	return $devId;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// notif tiketing -----------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------		
function waTiketOpen_noEnter($data)
{
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   'Dear ' . $data['namaPenerima'] . ', Anda mendapatkan Tiket: ' . $data['kodeTiket'] . ' dengan Subjek ' . $data['subject'] .
		' Segera periksa detail tiket anda di https://helpdesk.visiyosindo.id Terima Kasih';
	sendWa($dataWa);
	return true;
}

function waTiketOpen($data)
{
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0AAnda mendapatkan Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ASegera periksa detail tiket anda di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waTiketOpenGroup($data)
{
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['groupPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0AAnda mendapatkan Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ASegera periksa detail tiket anda dan *WAJIB* segera Update Tiket di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

function waTiketGroup($data)
{
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['groupPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear Customer Relation Officer' .
		',%0A%0A' . $data['namaPengaju'] . ' ' . $data['melakukan'] . '  untuk Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ASegera periksa detail tiket ' . $data['detailTiket'] . ' Tiket di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

function waTiketGroupNew($data)
{
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['groupPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear Customer Relation Officer' .
		',%0A%0A' . $data['namaPengaju'] . ' ' . $data['melakukan'] . '  untuk Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0AKeterangan: ' . $data['ketPeker'] .
		'%0ALink: ' . $data['linkBukti'] .
		'%0A%0ASegera periksa detail tiket ' . $data['detailTiket'] . ' Tiket di https://office.visiyosindo.id/tiket/show/dashboard' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

function waTiketAjuVisit($data)
{
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= '0895410953259';
	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear General Affair' .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan visit untuk Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ASegera periksa detail tiket dan terbitkan surat dinas di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waTiketPertama($data)
{
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0AAnda mendapatkan Tiket untuk di Dispath ke teknisi yang bersangkutan :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ASegera Dispath tiket anda di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

//Uji Coba Notif Update
function waTiketLogUpdate($data)
{
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= '081261457547';
	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear Customer Relation Officer' .
		',%0A%0A' . $data['namaPengaju'] . ' melakukan update untuk Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ADetail Log Tiket cek di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waTiketTerbitSudin($data)
{
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0ASurat Dinas anda untuk Tiket :' .
		'%0AKode   : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0ASudah diterbitkan.' .
		'%0A%0ASegera periksa surat dinas dan detail tiket anda di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waTiketAjuBiaya($data)
{
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear Finance Staff' .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan biaya untuk Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ASegera periksa detail tiket dan setujui pembiayaan dinas di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waTiketSetujuBiaya($data)
{
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0APengajuan biaya anda untuk Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0ASudah disetujui.' .
		'%0A%0ASegera periksa detail tiket di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waTiketAjuLaporan($data)
{
	$dataGa['devId']	= hostWa('1');
	$dataGa['penerima']	= '081261457547';
	$dataGa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear Customer Relation Officer' .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan laporan akhir untuk Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ASegera periksa detail tiket dan lakukan penutupan terhadap tiket terkait di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';

	if ($data['cek'] == 1) {
		$dataFi['devId']	= hostWa('1');
		$dataFi['penerima']	= $data['noPenerima2'];
		$dataFi['pesan']	=   '*Notifikasi Job Ticketing*' .
			'%0A%0ADear Finance Staff' .
			',%0A%0A' . $data['namaPengaju'] . ' mengajukan laporkan biaya untuk Tiket :' .
			'%0AKode  : ' . $data['kodeTiket'] .
			'%0ASubjek: ' . $data['subject'] .
			'%0A%0ASegera periksa detail tiket dan setujui laporan biaya di https://office.visiyosindo.id' .
			'%0A%0ATerima Kasih';
		sendWa($dataFi);
	}

	sendWa($dataGa);
	return true;
}

function waTiketGantiPIC($data)
{
	$data1['devId']	    = hostWa('1');
	$data1['penerima']	= $data['noPenerima1'];
	$data1['pesan']	    =   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['nama1'] .
		',%0A%0ATiket anda dengan detail:' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ADipindahkan kepada ' . $data['nama2'] .
		'%0A%0ASegera periksa detail tiket terkait di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';

	$data2['devId']	    = hostWa('1');
	$data2['penerima']	= $data['noPenerima2'];
	$data2['pesan']	    =   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['nama2'] .
		',%0A%0ATiket dengan detail:' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ADipindahkan dari ' . $data['nama1'] . ' kepada anda' .
		'%0A%0ASegera periksa detail tiket terkait di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($data1);
	sendWa($data2);
	return true;
}

function waTiketMultiPicKetua($data)
{
	$dataKetua['devId']	    = hostWa('1');
	$dataKetua['penerima']	= $data['noPenerima'];
	$dataKetua['pesan']	    =   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['namaKetua'] .
		',%0A%0AAnda mendapatkan Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ADengan PIC Support:' .
		$data['pic_support'] .
		'%0A%0ASegera periksa detail tiket anda di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataKetua);
	return true;
}

function waTiketMultiPicSupport($data)
{
	$dataSupport['devId']	= hostWa('1');
	$dataSupport['penerima'] = $data['noPenerima'];
	$dataSupport['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['namaSupport'] .
		',%0A%0AAnda mendapatkan Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0AYang diketuai oleh:' .
		'%0A- ' . $data['namaKetua'] .
		'%0A%0ASegera hubungi ' . $data['namaKetua'] . ' untuk informasi lebih lengkap' .
		'%0A%0ATerima Kasih';
	sendWa($dataSupport);
	return true;
}

//WA CP Customer
function waTiketOpenToCust($data)
{
	$datapesan['devId']	    = hostWa('2');
	$datapesan['penerima']  = $data['noCpCustomer'];
	$datapesan['pesan']	    =   '*' . 'Halo ' . $data['cpCustomer'] . '*' .
		'%0A%0AAduan anda terkait ' . $data['mesin'] . ' di ' . $data['customer'] .
		'%0ATelah kami proses dengan detail:' .
		'%0ATiket  : ' . $data['kodeTiket'] .
		'%0ATeknisi: ' . $data['pic'] .
		'%0A%0AKami meminta kesediaan anda untuk menunggu' .
		'%0ATeknisi kami akan menghubungi dengan segera' .
		'%0A%0A' . '*Jam Pelayanan*' .
		'%0A' . '*Senin-Jumat 08:30 - 17:00*' .
		'%0A' . '*Sabtu 08:30 - 13:00*' .
		'%0A%0A' . '*Salam Hangat*' .
		'%0A*PT. VISI YOSINDO MEDIKAL*';
	sendWa($datapesan);
	return true;
}

function waTiketGantiPicToCust($data)
{
	$datapesan['devId']	    = hostWa('2');
	$datapesan['penerima']  = $data['noCpCustomer'];
	$datapesan['pesan']	    =   '*' . 'Halo ' . $data['cpCustomer'] . '*' .
		'%0A%0AAduan anda terkait ' . $data['mesin'] . ' di ' . $data['customer'] .
		'%0ADengan detail:' .
		'%0ATiket  : ' . $data['kodeTiket'] .

		'%0ATeknisi: ' . $data['pic'] .
		'%0A%0AKami meminta kesediaan anda untuk menunggu' .
		'%0ATeknisi kami akan menghubungi dengan segera' .
		'%0A%0A' . '*Jam Pelayanan*' .
		'%0A' . '*Senin-Jumat 08:30 - 17:00*' .
		'%0A' . '*Sabtu 08:30 - 13:00*' .
		'%0A%0A' . '*Salam Hangat*' .
		'%0A*PT. VISI YOSINDO MEDIKAL*';
	sendWa($datapesan);
	return true;
}

function waTiketCloseToCust($data)
{
	$datapesan['devId']	    = hostWa('2');
	$datapesan['penerima']  = $data['noCpCustomer'];
	$datapesan['pesan']	    =   '*' . 'Halo ' . $data['cpCustomer'] . '*' .
		'%0A%0AAduan anda terkait ' . $data['mesin'] . ' di ' . $data['customer'] .
		'%0ATelah kami proses dengan detail:' .
		'%0ATiket  : ' . $data['kodeTiket'] .

		'%0ATeknisi: ' . $data['pic'] .
		'%0A%0AKami meminta kesediaan anda untuk menunggu' .
		'%0ATeknisi kami akan menghubungi dengan segera' .
		'%0A%0A' . '*Jam Pelayanan*' .
		'%0A' . '*Senin-Jumat 08:30 - 17:00*' .
		'%0A' . '*Sabtu 08:30 - 13:00*' .
		'%0A%0A' . '*Salam Hangat*' .
		'%0A*PT. VISI YOSINDO MEDIKAL*';
	sendWa($datapesan);
	return true;
}
function waSuratAprovAllToKaryawan($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan'] = '*Anda mendapatkan Surat Peringatan*' .
		'%0A%0ADetail Surat :' .
		'%0A%0ASurat Peringatan : ' . $data['namaSurat'] .
		'%0AKode Surat: ' . $data['kodeSurat'] .
		'%0APerihal: ' . $data['perihal'] .
		'%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0AMohon segera periksa detail surat pada https://office.visiyosindo.id dan hubungi GA jika diperlukan.' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSuratAprovAllSpKar($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan'] = '*Anda mendapatkan Surat Peringatan*' .
		'%0A%0ADetail Surat :' .
		'%0A%0ASurat Peringatan : ' . $data['namaSurat'] .
		'%0AKode Surat: ' . $data['kodeSurat'] .
		'%0APerihal: ' . $data['perihal'] .
		'%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0AMohon segera periksa detail surat pada https://office.visiyosindo.id/surat/print_page/sp/' . $data['link'] . ' dan hubungi GA jika diperlukan.' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waApprovSpKardonal($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan'] = '*Notifikasi Surat Peringatan*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaSP'] . ' mendapatkan *Surat Peringatan*' .
		'%0AKode Surat: ' . $data['kodeSurat'] .
		$data['perihal'] .
		'%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0AMohon segera periksa detail surat pada https://office.visiyosindo.id/surat/print_page/sp/' . $data['link'] . ' dan hubungi GA jika diperlukan.' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSuratAprovAllKeterangan($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan'] = '*Anda mendapatkan Surat Keterangan Bekerja*' .
		'%0A%0ADetail Surat :' .
		'%0A%0ASurat : ' . $data['namaSurat'] .
		'%0AKode Surat: ' . $data['kodeSurat'] .
		'%0APerihal: ' . $data['perihal'] .
		'%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0AMohon segera periksa detail surat pada https://office.visiyosindo.id/surat/print_page/keterangan/' . $data['link'] . ' dan hubungi GA jika diperlukan.' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSuratAprovAllSkors($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan'] = '*Anda mendapatkan Surat Skorsing*' .
		'%0A%0ADetail Surat :' .
		'%0AKode Surat: ' . $data['kodeSurat'] .
		'%0APerihal: ' . $data['perihal'] .
		'%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0AMohon segera periksa detail surat pada https://office.visiyosindo.id/surat_new/print_page/spi/' . $data['link'] . ' dan hubungi GA jika diperlukan.' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}


function waEvaluasi($data)
{

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' meminta anda untuk mengisi ' . $data['namaSurat'] .
		' :%0ANama Pegawai    : ' . $data['namaPegawai'] .
		'%0APeriode : Semester ' . $data['smt'] . '  Tahun ' . $data['tahun'] .

		'%0A%0AMohon segera periksa detail ' . $data['namaSurat'] . ' pada https://office.visiyosindo.id/evaluasi/show/detail/penilaian/' . $data['link'] .
		'%0A%0ATerima Kasih';

	sendWa($dataWa);
	return true;
}

function waEvaluasiSimpan($data)
{

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' selesai mengisi nilai ' . $data['namaSurat'] .
		' :%0ANama Pegawai    : ' . $data['namaPegawai'] .
		'%0APeriode : Semester ' . $data['smt'] . '  Tahun ' . $data['tahun'] .

		'%0A%0AMohon segera periksa detail ' . $data['namaSurat'] . ' pada https://office.visiyosindo.id/evaluasi/show/detail/evaluasi/' . $data['link'] .
		'%0A%0ATerima Kasih';

	sendWa($dataWa);
	return true;
}


function waLaporan($data)
{

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' meminta anda untuk mengisi Nilai ' . $data['namaSurat'] .
		' :%0ANama Pegawai    : ' . $data['namaPegawai'] .
		'%0APeriode : ' . $data['startDate'] . ' sampai ' . $data['endDate'] .

		'%0A%0AMohon segera periksa detail ' . $data['namaSurat'] . ' pada https://office.visiyosindo.id/laporan/show/detail/my_detail_nilai/' . $data['link'] .
		'%0A%0ATerima Kasih';

	sendWa($dataWa);
	return true;
}

function waLaporanRev2($data)
{

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' meminta anda untuk mengisi Nilai ' . $data['namaSurat'] .
		' :%0ANama Pegawai    : ' . $data['namaPegawai'] .
		'%0APeriode : ' . $data['startDate'] . ' sampai ' . $data['endDate'] .

		'%0A%0AMohon segera periksa detail ' . $data['namaSurat'] . ' pada https://office.visiyosindo.id/laporan/show/detail/my_detail_nilai_rev2/' . $data['link'] .
		'%0A%0ATerima Kasih';

	sendWa($dataWa);
	return true;
}

function waLaporanSelesai($data)
{

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' selesai mengisi nilai ' . $data['namaSurat'] .
		' :%0ANama Pegawai    : ' . $data['namaPegawai'] .
		'%0APeriode : ' . $data['startDate'] . ' sampai ' . $data['endDate'] .

		'%0A%0AMohon segera periksa detail ' . $data['namaSurat'] . ' pada https://office.visiyosindo.id/laporan/show/detail/laporan_detail/' . $data['link'] .
		'%0A%0ATerima Kasih';

	sendWa($dataWa);
	return true;
}

function waLaporanSelesaiRev2($data)
{

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' selesai mengisi nilai ' . $data['namaSurat'] .
		' :%0ANama Pegawai    : ' . $data['namaPegawai'] .
		'%0APeriode : ' . $data['startDate'] . ' sampai ' . $data['endDate'] .

		'%0A%0AMohon segera periksa detail ' . $data['namaSurat'] . ' pada https://office.visiyosindo.id/laporan/show/detail/laporan_detail_rev2/' . $data['link'] .
		'%0A%0ATerima Kasih';

	sendWa($dataWa);
	return true;
}


function waSuratAprovAllToKaryawanTugas($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan'] = '*Anda mendapatkan Surat Tugas*' .
		'%0A%0ADetail Surat:' .
		'%0A%0AJenis Surat : ' . $data['namaSurat'] .
		'%0ANama Penerima: ' . $data['namaPenerima'] .
		'%0ANama Pengaju: ' . $data['namaPengaju'] .
		'%0AKode Surat: ' . $data['kodeSurat'] .
		'%0APerihal: ' . $data['perihal'] .
		'%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0AMohon segera periksa detail surat pada https://office.visiyosindo.id dan hubungi Legal/HR jika diperlukan.' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSuratAprovAllToKaryawanEngineer($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId'] = hostWa('1');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan'] = '*Notifikasi ' . $data['namaSurat'] . '*' .
		'%0A%0ADear *Kardonal*, bawahan Anda mendapatkan :' .
		'%0A%0AJenis Surat : ' . $data['namaSurat'] .
		'%0ANama Penerima: ' . $data['namaPenerima'] .
		'%0ANama Pengaju: ' . $data['namaPengaju'] .
		'%0AKode Surat: ' . $data['kodeSurat'] .
		'%0APerihal: ' . $data['perihal'] .
		'%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0AMohon segera periksa detail surat pada https://office.visiyosindo.id/' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// notif surat -----------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------		
function cekPerihal($param)
{
	if ($param != "") {
		$param = '%0A_Perihal : ' . $param . '_';
	} else {
		$param = "";
	}
	return $param;
}

function cekLink($param)
{
	if ($param != "") {
		$param = '%0A_Link : ' . $param . '_';
	} else {
		$param = "";
	}
	return $param;
}

function cekTtd($param)
{
	if ($param != "") {
		$param = '%0A-' . $param;
	} else {
		$param = "";
	}
	return $param;
}

function waSuratOpen($data)
{
	$data['perihal']    = cekPerihal($data['perihal']);

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ASegera periksa detail surat dan lakukan persetujuan pada https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}


function waSuratOpenLink($data)
{
	$data['perihal']    = cekPerihal($data['perihal']);

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ASegera periksa detail surat dan lakukan persetujuan pada ' . $data['urlNotif'] .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSuratOpenApproval($data)
{
	$data['perihal']    = cekPerihal($data['perihal']);

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] . ' produk *UKES, UPAR atau TLD* ' .
		':%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ASegera periksa detail surat pada https://office.visiyosindo.id/surat/show/detail_surat/approval/' . $data['link'] .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSuratOpenGroup($data)
{
	$data['perihal']    = cekPerihal($data['perihal']);

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ASegera periksa detail surat dan lakukan persetujuan pada ' . $data['url'] .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}


function waDokumenGroup($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *' . $data['status'] . '* ' . $data['namaSurat'] .
		':%0A_Nama Dokumen    : ' . $data['csname'] . '_' .
		'%0A_Kategori         : ' . $data['kategori'] . '_' .
		'%0A%0ASegera periksa detail  *' . $data['namaSurat'] . '* di ' . $data['url'] . '' . $data['idTracking'] .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

function waKirimDoc($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['statusSurat'] . ' ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *' . $data['status'] . '* ' . $data['namaSurat'] .
		':%0A_Customer Name    : ' . $data['csname'] . '_' .
		'%0A_Kode    : ' . $data['kode'] . '_' .
		'%0A_Asal    : ' . $data['gudangAsal'] . '_' .
		'%0A_Alamat Tujuan   : ' . $data['alamatTujuan'] . '_' .
		'%0A_Status    : ' . $data['statusTracking'] . '_' .
		'%0A_Keterangan    : ' . $data['keTerangan'] . '_' .
		'%0A%0ASegera periksa detail surat dan lakukan persetujuan pada ' . $data['urlNotif'] .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}


//======================================================
//========   Send To Group Gudang ======================
//======================================================

//=== Tracking Barang ====


function waAppGroupGudang($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['statusSurat'] . ' ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *' . $data['status'] . '* ' . $data['namaSurat'] .
		':%0A_Customer Name    : ' . $data['csname'] . '_' .
		'%0A_Nomor SJ    : ' . $data['nosj'] . '_' .
		'%0A_Gudang Asal    : ' . $data['gudangAsal'] . '_' .
		'%0A_Alamat Tujuan   : ' . $data['alamatTujuan'] . '_' .
		'%0A_Status    : ' . $data['statusTracking'] . '_' .
		'%0A_Keterangan    : ' . $data['keTerangan'] . '_' .
		'%0A%0ASegera periksa detail  *' . $data['namaSurat'] . '* di ' . $data['url'] . '' . $data['idTracking'] .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

function waAppGroupSJ($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['statusSurat'] . ' ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *' . $data['status'] . '* ' . $data['namaSurat'] .
		':%0A_Customer : ' . $data['csname'] . '_' .
		'%0A_Nomor SJ  : ' . $data['nosj'] . '_' .
		'%0A_Gudang Asal : ' . $data['namaGudang'] . '_' .
		'%0A_Ekspedisi  : ' . $data['namaEks'] . '_' .
		'%0A_Barang    : ' . $data['statusTracking'] . '_' .
		'%0A%0ASegera periksa detail  *' . $data['namaSurat'] . '* di ' . $data['url'] . '' . $data['idTracking'] .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}


function waAppGroupGudangPo($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['statusSurat'] . ' ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *' . $data['status'] . '* ' . $data['namaSurat'] .
		':%0A_Nama Customer    : ' . $data['csname'] . '_' .
		'%0A_Nama Marketing    : ' . $data['marketing'] . '_' .
		'%0A_Status    : ' . $data['statusTracking'] . '_' .
		'%0A%0ASegera periksa detail  *' . $data['namaSurat'] . '* di ' . $data['url'] . '' . $data['idTracking'] .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

//========  Stock opname =======

function waGroupStockOpname($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['statusSurat'] . ' ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *' . $data['status'] . '* ' . $data['namaSurat'] .
		':%0A_Gudang    : ' . $data['gudang'] . '_' .
		'%0A_Nomor      : ' . $data['kode'] . '_' .
		'%0A_Pelaksana  : ' . $data['pelaksana'] . '_' .
		'%0A%0ASegera periksa detail  *' . $data['namaSurat'] . '* di https://office.visiyosindo.id/stock_opname/show/detail_so/' . $data['idSo'] .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}




function waNotifStockOpname($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['statusSurat'] . ' ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *' . $data['status'] . '* ' . $data['namaSurat'] .
		':%0A_Gudang    : ' . $data['gudang'] . '_' .
		'%0A_Nomor      : ' . $data['kode'] . '_' .
		'%0A_Pelaksana  : ' . $data['pelaksana'] . '_' .
		'%0A%0ASegera periksa detail  *' . $data['namaSurat'] . '* di https://office.visiyosindo.id/stock_opname/show/detail_so/' . $data['idSo'] .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

//======================================================
//=====   Send To Group Marketing ======================
//======================================================

//=== Presentase ====


function waAppGroupMarketing($data)
{
	$data['link']    = cekLink($data['link']);

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['statusSurat'] . ' ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *' . $data['status'] . '* ' . $data['namaSurat'] .
		':%0A_Customer Name    : ' . $data['csname'] . '_' .
		'%0A_Nomor    : ' . $data['kode'] . '_' .
		$data['link'] .
		'%0A%0ASegera periksa detail  *' . $data['namaSurat'] . '* di https://office.visiyosindo.id/dashboard_marketing' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}


//==== FPP ========

function waPermintaanPenawaranGroup($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Customer Name    : ' . $data['csname'] . '_' .
		'%0A_FPP Nomor    : ' . $data['kodeFPP'] . '_' .
		'%0A%0ASegera periksa detail Permintaan Penawaran pada menu FPP di https://office.visiyosindo.id/dashboard_marketing' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}


function waPermintaanPenawaranGroupHaridho($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A%48' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Customer Name    : ' . $data['csname'] . '_' .
		'%0A_FPP Nomor    : ' . $data['kodeFPP'] . '_' .
		'%0A%0ASegera periksa detail Permintaan Penawaran pada menu FPP di https://office.visiyosindo.id/dashboard_marketing' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}


function waAppPermintaanPenawaranGroup($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *menyetujui* ' . $data['namaSurat'] .
		':%0A_Customer Name    : ' . $data['csname'] . '_' .
		'%0A_FPP Nomor    : ' . $data['kodeFPP'] . '_' .
		'%0A%0ADengan detail *' . $data['jenis'] . '* sebagai berikut' .
		':%0A_' . $data['jenis'] . ' Nomor    : ' . $data['noSph'] . '_' .
		'%0A_' . $data['jenis'] . ' Link    : ' . $data['linkSph'] . '_' .
		'%0A%0ASegera periksa detail Permintaan Penawaran dan *' . $data['jenis'] . '* pada menu FPP di https://office.visiyosindo.id/dashboard_marketing' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

function waAppPermintaanPenawaranGroupHaridho($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear %48' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *menyetujui* ' . $data['namaSurat'] .
		':%0A_Customer Name    : ' . $data['csname'] . '_' .
		'%0A_FPP Nomor    : ' . $data['kodeFPP'] . '_' .
		'%0A%0ADengan detail *' . $data['jenis'] . '* sebagai berikut' .
		':%0A_' . $data['jenis'] . ' Nomor    : ' . $data['noSph'] . '_' .
		'%0A_' . $data['jenis'] . ' Link    : ' . $data['linkSph'] . '_' .
		'%0A%0ASegera periksa detail Permintaan Penawaran dan *' . $data['jenis'] . '* pada menu FPP di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}


function waTolakPermintaanPenawaranGroup($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Penolakan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *menolak* ' . $data['namaSurat'] .
		':%0A_Customer Name    : ' . $data['csname'] . '_' .
		'%0A_FPP Nomor    : ' . $data['kodeFPP'] . '_' .
		'%0A%0ASegera periksa detail Permintaan Penawaran pada menu FPP di https://office.visiyosindo.id/dashboard_marketing' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

function waTolakPermintaanPenawaranGroupHaridho($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Penolakan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear %48' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *menolak* ' . $data['namaSurat'] .
		':%0A_Customer Name    : ' . $data['csname'] . '_' .
		'%0A_FPP Nomor    : ' . $data['kodeFPP'] . '_' .
		'%0A%0ASegera periksa detail Permintaan Penawaran pada menu FPP di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}



function waAnnouncementGroup($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' membuat ' . $data['namaSurat'] .
		':%0A_Tanggal    : ' . $data['applieddate'] . '_' .
		'%0A_Isi    : ' . $data['message'] . '_' .
		'%0A_Lampiran    : ' . $data['lampiran'] . '_' .
		'%0A%0ASegera periksa detail Announcement di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

//======================================================
//=====   Notif Training & Development =================
//======================================================


function waTrainingAdd($data)
{
	$data['perihal']    = cekPerihal($data['perihal']);

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ASegera periksa detail pengajuan dan lakukan persetujuan pada menu Training dan Development di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}



function waTrainingAprovOnProg($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail pengajuan dan lakukan persetujuan pada menu Training dan Development di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}



//======================================================
//===========   VISILAB Group              =============
//======================================================

function waPermintaanVisilabGroup($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Nama Pelanggan    : ' . $data['perihal'] . '_' .
		'%0A_Nomor    : ' . $data['kode'] . '_' .
		'%0A%0ASegera periksa detail ' . $data['namaSurat'] . ' di https://office.visiyosindo.id/dashboard_visilab' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}



function waVisilabAprovAllGroup($data)
{

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Nama Pelanggan    : ' . $data['perihal'] . '_' .
		'%0A_Nomor    : ' . $data['kode'] . '_' .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail ' . $data['namaSurat'] . ' di https://office.visiyosindo.id/dashboard_visilab' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return TRUE;
}


function waAddGroupVisilab($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Nama Customer    : ' . $data['perihal'] . '_' .
		'%0A_Nomor    : ' . $data['kode'] . '_' .
		'%0A%0ASegera periksa detail ' . $data['namaSurat'] . ' di ' . $data['urlNotif'] .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}



function waAllGroupVisilab($data)
{

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Nama Customer    : ' . $data['perihal'] . '_' .
		'%0A_Nomor    : ' . $data['kode'] . '_' .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail ' . $data['namaSurat'] . ' di ' . $data['urlNotif'] .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return TRUE;
}


//======================================================
//===========   PO       VISILAB           =============
//======================================================


function waPoVisilabOpen($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier Visilab di https://office.visiyosindo.id/po_visilab/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}


function waPermintaanPoVisilabGroup($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier Visilab di https://office.visiyosindo.id/po_visilab/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}


function waPoVisilabAprovOnProg($data)
{

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		$data['ttd_sebelum5'] .
		'%0A%0ASegera ' . $data['proses'] . ' Permintaan PO Supplier Visilab di https://office.visiyosindo.id/po_visilab/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waPoVisilabAprovAll($data)
{

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		$data['ttd_sebelum5'] .
		'%0A%0ASegera periksa detail Permintaan PO Supplier Visilab di https://office.visiyosindo.id/po_visilab/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return TRUE;
}

function waPoVisilabAprovAllGroup($data)
{

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A_PO Supplier Nomor    : ' . $data['noPo'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier Visilab di https://office.visiyosindo.id/po_visilab/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return TRUE;
}

function waPoVisilabAprovAllGroupAdm($data)
{

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A_Status Penerimaan    : ' . $data['noPo'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier Visilab di https://office.visiyosindo.id/po_visilab/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return TRUE;
}



//======================================================
//===========   PO                         =============
//======================================================


function waPoOpen($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier di https://office.visiyosindo.id/purchase_order/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waPermintaanPoGroup($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier di https://office.visiyosindo.id/purchase_order/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}


function waPoAprovOnProg($data)
{

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera ' . $data['proses'] . ' Permintaan PO Supplier di https://office.visiyosindo.id/purchase_order/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waPoAprovAll($data)
{

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail Permintaan PO Supplier di https://office.visiyosindo.id/purchase_order/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return TRUE;
}


function waPoAprovAllGroup($data)
{

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A_PO Supplier Nomor    : ' . $data['noPo'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier di https://office.visiyosindo.id/purchase_order/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return TRUE;
}




function waForecastGroup($data)
{

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear Warehouse Team' .
		',%0A%0A' . $data['namaSurat'] . ' yang anda diajukan oleh' . $data['namaPengaju'] .
		':%0A_Kode    : ' . $data['kodePO'] . '_' .
		'%0A_Perihal    : ' . $data['suplier'] . '_' .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail Permintaan Forecast di https://office.visiyosindo.id/forecast/show/list' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return TRUE;
}


function waPoAprovAllGroupAdm($data)
{

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Penerimaan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A_Status Penerimaan    : ' . $data['noPo'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier di https://office.visiyosindo.id/purchase_order/show/permintaan/purchase_order' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return TRUE;
}





//======================================================
//===========   END PO                     =============
//======================================================


function waSuratAprovOnProgVisilab($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail surat dan lakukan persetujuan pada ' . $data['urlNotif'] .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSuratAprovAllVisilab($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan:' .
		'%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail surat pada ' . $data['urlNotif'] .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return TRUE;
}




function waSuratAprovOnProg($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail surat dan lakukan persetujuan pada https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}


function waSuratAprovOnProgDir($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail surat dan lakukan persetujuan pada https://office.visiyosindo.id/surat_part_two/show/detail/ba/' . $data['idBA'] .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}



function waSuratAprovAll($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan:' .
		'%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail surat pada https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return TRUE;
}


function waSuratAprovGABA($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan:' .
		'%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail surat pada https://office.visiyosindo.id lalu General Affair mengarsipkannya' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return TRUE;
}


function waSijkEngineer($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaSurat'] . ' yang diajukan oleh: ' . $data['namaPengaju'] .
		'%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A_Pada Tanggal : ' . $data['tanggal'] . ' pukul ' . $data['mulai'] . ' sampai ' . $data['akhir'] . '_' .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail surat pada https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return TRUE;
}

function waIzinCutiEngineer($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaSurat'] . ' yang diajukan oleh: ' . $data['namaPengaju'] .
		'%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A_Total : ' . $data['total'] . ' hari, mulai tanggal ' . $data['tglmulai'] . ' sampai ' . $data['tglakhir'] . '_' .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail surat pada https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return TRUE;
}


function waSuratAprovAllToFinance($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaSurat'] . ' yang diajukan oleh ' . $data['namaPengaju'] . ' dengan detail: ' .
		'%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail surat pada https://office.visiyosindo.id dan lakukan transfer(jika diperlukan)' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSuratAprovAllToSecurity($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear our Security' .
		',%0A%0A' . $data['namaSurat'] . ' yang diajukan oleh ' . $data['namaPengaju'] . ' dengan detail: ' .
		'%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ALakukan pemeriksaan terhadap surat izin yang dibawa oleh ' . $data['namaPengaju'] .
		'%0ABerikan akses untuk masuk ke gudang jika kode yang ada pada surat sesuai dengan kode yang ada di notifikasi ini.' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSuratAprovAllToDirector($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}
	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear Director PT. Visi Yosindo Medikal' .
		',%0A%0A' . $data['namaSurat'] . ' yang diajukan oleh General Affair:' .
		'%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATelah disetujui oleh:' .
		$data['ttd_sebelum1'] .
		$data['ttd_sebelum2'] .
		$data['ttd_sebelum3'] .
		$data['ttd_sebelum4'] .
		'%0A%0ASegera periksa detail surat pada %0A%0A https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSuratReject($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Penolakan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A ' . $data['namaSurat'] . ' yang anda ajukan:' .
		'%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATidak disetujui oleh: ' . $data['namaPenolak'] .
		'%0A%0ASegera hubungi ' . $data['namaPenolak'] . ' pada nomor *' . $data['noPenolak'] . '*' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSlipGaji($data, $link)
{
	$data['perihal']    = cekPerihal($data['perihal']);

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Slip Gaji' . '*' .
		'%0A%0AYth ' . $data['penerima'] .
		'%0A%0AKami menginformasikan ' . $data['namaSurat'] .
		'%0A%0AMohon Segera periksa Slip Gaji Anda dengan tautan link berikut ini: ' . $link .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSendBrosurdanSurat($data)
{
	//$data['perihal']    = cekPerihal($data['perihal']);

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		'%0A%0AKami mengirimkan *' . $data['perihal'] . '*' .
		'%0A%0ABerikut Link tautannya : ' . $data['link_brosur'] .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

// Tambahkan ini di whatsapp_helper.php
function waAppPersonalSJ($data)
{

	$dataWa['devId']	= hostWa('1');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi ' . $data['statusSurat'] . ' ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *' . $data['status'] . '* ' . $data['namaSurat'] .
		':%0A_Customer : ' . $data['csname'] . '_' .
		'%0A_Nomor SJ  : ' . $data['nosj'] . '_' .
		'%0A_Gudang Asal : ' . $data['namaGudang'] . '_' .
		'%0A_Ekspedisi  : ' . $data['namaEks'] . '_' .
		'%0A_Barang    : ' . $data['statusTracking'] . '_' .
		'%0A%0ASegera periksa detail  *' . $data['namaSurat'] . '* di ' . $data['url'] . '' . $data['idTracking'] .
		'%0A%0ATerima Kasih';

	// Perbedannya disini: Menggunakan sendWa (Personal), bukan sendWaGroup
	sendWa($dataWa);
	return true;
}

function waTrainingUploadSertifikat($data)
    {
        // 1. DAFTAR PENERIMA (HARDCODE)
        // Format: 'NOMOR_HP' => 'NAMA_PANGGILAN'
        // Pastikan nomor diawali '08...' atau '628...' sesuai settingan gateway Anda (biasanya 08 aman)
        $recipients = [
            '081275061896'  => 'Ibu Dian Melati Amelia',
            '0895410953087' => 'Tim HR & Legal',
        ];

        // 2. SETTING DEVICE ID
        // Menggunakan Device ID AdminIT (sesuai request Anda)
        $dataWa['devId'] = hostWa('1'); 

        // 3. LOOPING PENGIRIMAN
        foreach ($recipients as $noHp => $namaPenerima) {

            $dataWa['penerima'] = $noHp;

            // Format Pesan (Menggunakan %0A untuk baris baru)
            $message  = "*Notifikasi Upload Sertifikat Training*";
            $message .= "%0A%0ADear " . $namaPenerima . ",";
            $message .= "%0A%0AUser *" . $data['namaPengaju'] . "* telah berhasil mengupload dokumen sertifikat/bukti kehadiran.";
            $message .= "%0A%0A*Detail Training:*";
            $message .= "%0AKode : " . $data['kodeSurat'];
            $message .= "%0ANama : " . $data['namaTraining'];
            $message .= "%0A%0AMohon untuk mengecek kelengkapan dokumen tersebut di sistem Office.";
            $message .= "%0A%0ATerima Kasih.";

            $dataWa['pesan'] = $message;

            // Kirim Pesan
            sendWa($dataWa);

            // Jeda 1 detik agar aman dari spam detection
            sleep(1);
        }

        return true;
    }
    
    //======================================================
    //===========   CUTI TAHUNAN (GROUP)       =============
    //======================================================
    
    function waAppGroupCuti($data)
    {
        // Menggunakan Device ID 1 (AdminIT) sesuai standar helper ini
        $dataWa['devId']    = hostWa('1'); 
        
        // Target Group diambil dari parameter controller
        $dataWa['penerima'] = $data['targetGroup']; 
    
        // Menyusun Pesan
        // Menggunakan %0A sebagai pengganti \n (Enter) agar kompatibel dengan API WhaCenter via URL
        $dataWa['pesan']    = '*' . $data['header'] . '*' .
            '%0A%0A_Nama : ' . $data['nama'] . '_' .
            '%0A_Tanggal Cuti : ' . $data['tanggal'] . '_' .
            '%0A_Keterangan : ' . $data['keterangan'] . '_' .
            '%0A%0ATerima Kasih';
    
        sendWaGroup($dataWa);
        return true;
    }

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// fungsi kirim WA -----------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// function sendWa($dataSend)
// {
// 	$result = file_get_contents("https://app.whacenter.com/api/send?device_id=" . $dataSend['devId'] . "&number=" . $dataSend['penerima'] . "&message=" . $dataSend['pesan']);
// 	error_reporting(E_ALL & ~E_NOTICE);
// 	ini_set('display_errors', 0);
// 	return true;
// }


// function sendWaGroup($dataSend)
// {
// 	$result = file_get_contents("https://app.whacenter.com/api/sendGroup?device_id=" . $dataSend['devId'] . "&group=" . $dataSend['penerima'] . "&message=" . $dataSend['pesan']);
// 	error_reporting(E_ALL & ~E_NOTICE);
// 	ini_set('display_errors', 0);
// 	return true;
// }

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// fungsi kirim WA (DIPERBAIKI DENGAN TIMEOUT)
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
function sendWa($dataSend)
{
	// Setup Timeout 10 detik. Jika lebih dari 10 detik, skip.
	$ctx = stream_context_create(array(
		'http' => array(
			'timeout' => 10
		)
	));

	// Gunakan @ untuk suppress error warning jika koneksi gagal
	$result = @file_get_contents("https://app.whacenter.com/api/send?device_id=" . $dataSend['devId'] . "&number=" . $dataSend['penerima'] . "&message=" . $dataSend['pesan'], false, $ctx);

	return true;
}


function sendWaGroup($dataSend)
{
	// Setup Timeout 10 detik
	$ctx = stream_context_create(array(
		'http' => array(
			'timeout' => 10
		)
	));

	$result = @file_get_contents("https://app.whacenter.com/api/sendGroup?device_id=" . $dataSend['devId'] . "&group=" . $dataSend['penerima'] . "&message=" . $dataSend['pesan'], false, $ctx);

	return true;
}
