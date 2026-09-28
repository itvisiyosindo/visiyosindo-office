<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Menentukan Host Wa -----------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------	
function hostWa($param)
{
	$devId = '';
	if ($param == '1') {
		$devId = '7388d9d2431fbc65c0ce49a4fac7550b'; //device ID IT di WhaCenter
	} else if ($param == '2') {
		$devId = 'bbe8ce03e61d0c490e850bce5f3df56c'; //device ID Notifikasi IT di WhaCenter
	} else if ($param == '3') {
		$devId = 'bbe8ce03e61d0c490e850bce5f3df56c'; //device ID Databank (Connected) di WhaCenter
	}
	return $devId;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// notif tiketing -----------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------		
function waTiketOpen_noEnter($data)
{
	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   'Dear ' . $data['namaPenerima'] . ', Anda mendapatkan Tiket: ' . $data['kodeTiket'] . ' dengan Subjek ' . $data['subject'] .
		' Segera periksa detail tiket anda di https://helpdesk.visiyosindo.id Terima Kasih';
	sendWa($dataWa);
	return true;
}

function waTiketOpen($data)
{
	$dataWa['devId']    = hostWa('2');
	$dataWa['penerima'] = $data['noPenerima'];

	// [MODIFIKASI PESAN]
	$dataWa['pesan']    =   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0AAnda mendapatkan Tiket :' .
		'%0AKode   : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A_Nama Pelanggan : *' . $data['namaPelanggan'] . '*_' . // <--- TAMBAHAN
		'%0A%0ASegera periksa detail tiket anda di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waTiketOpenGroup($data)
{
	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['groupPenerima'];

	$namaCp = isset($data['nama_cp']) ? urldecode($data['nama_cp']) : '-';
	$nomerCp = isset($data['nomer_cp']) ? urldecode($data['nomer_cp']) : '-';
	$deskripsi = isset($data['deskripsi']) ? urldecode($data['deskripsi']) : '-';
	$namaPelanggan = isset($data['namaPelanggan']) ? urldecode($data['namaPelanggan']) : '-';
	$subject = isset($data['subject']) ? urldecode($data['subject']) : '-';
	$teknisi = isset($data['namaPenerima']) ? $data['namaPenerima'] : '-';

	$dataWa['pesan']	=   '*Notifikasi Job Ticketing (Dispatch)*' .
		'%0A%0ATiket Baru telah di-Dispatch ke Teknisi :' .
		'%0A%0AKode Tiket : ' . $data['kodeTiket'] .
		'%0ASubjek     : ' . $subject .
		'%0APelanggan  : *' . $namaPelanggan . '*' .
		'%0ANama CP    : ' . $namaCp .
		'%0ANomor CP   : ' . $nomerCp .
		'%0ATeknisi    : *' . $teknisi . '*' .
		'%0A%0ADeskripsi   : ' . $deskripsi .
		'%0A%0ASegera periksa detail tiket di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

function waTiketGroup($data)
{
	$dataWa['devId']	= hostWa('2');
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
	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['groupPenerima'];

	// Tambahkan response time jika tersedia
	$infoResponseTime = '';
	if (isset($data['responseTime']) && !empty($data['responseTime'])) {
		$infoResponseTime = '%0AResponse Time: ' . $data['responseTime'];
	}
	if (isset($data['duration']) && !empty($data['duration'])) {
		$infoResponseTime .= '%0ADurasi Tiket: ' . $data['duration'];
	}

	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear Customer Relation Officer' .
		',%0A%0A' . $data['namaPengaju'] . ' ' . $data['melakukan'] . '  untuk Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0AKeterangan: ' . $data['ketPeker'] .
		'%0ALink: ' . $data['linkBukti'] .
		$infoResponseTime .
		'%0A%0ASegera periksa detail tiket ' . $data['detailTiket'] . ' Tiket di https://office.visiyosindo.id/tiket/show/dashboard' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

function waTiketAjuVisit($data)
{
	$dataWa['devId']	= hostWa('2');
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
	$dataWa['devId']    = hostWa('2');
	$dataWa['penerima'] = $data['noPenerima'];

	// [MODIFIKASI PESAN]
	$dataWa['pesan']    =   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0AAnda mendapatkan Tiket untuk di Dispath ke teknisi yang bersangkutan :' .
		'%0AKode   : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A_Nama Pelanggan : *' . $data['namaPelanggan'] . '*_' .
		'%0A%0ASegera Dispath tiket anda di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

//Uji Coba Notif Update
function waTiketLogUpdate($data)
{
	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= '081261457547';

	// Tambahkan response time jika tersedia
	$infoResponseTime = '';
	if (isset($data['responseTime']) && !empty($data['responseTime'])) {
		$infoResponseTime = '%0AResponse Time: ' . $data['responseTime'];
	}
	if (isset($data['duration']) && !empty($data['duration'])) {
		$infoResponseTime .= '%0ADurasi Tiket: ' . $data['duration'];
	}

	$dataWa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear Customer Relation Officer' .
		',%0A%0A' . $data['namaPengaju'] . ' melakukan update untuk Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		$infoResponseTime .
		'%0A%0ADetail Log Tiket cek di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waTiketTerbitSudin($data)
{
	$dataWa['devId']	= hostWa('2');
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
	$dataWa['devId']	= hostWa('2');
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
	$dataWa['devId']	= hostWa('2');
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
	$dataGa['devId']	= hostWa('2');
	$dataGa['penerima']	= '081261457547';
	$dataGa['pesan']	=   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear Customer Relation Officer' .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan laporan akhir untuk Tiket :' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ASegera periksa detail tiket dan lakukan penutupan terhadap tiket terkait di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';

	if ($data['cek'] == 1) {
		$dataFi['devId']	= hostWa('2');
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
	$data1['devId']	    = hostWa('2');
	$data1['penerima']	= $data['noPenerima1'];
	$data1['pesan']	    =   '*Notifikasi Job Ticketing*' .
		'%0A%0ADear ' . $data['nama1'] .
		',%0A%0ATiket anda dengan detail:' .
		'%0AKode  : ' . $data['kodeTiket'] .
		'%0ASubjek: ' . $data['subject'] .
		'%0A%0ADipindahkan kepada ' . $data['nama2'] .
		'%0A%0ASegera periksa detail tiket terkait di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';

	$data2['devId']	    = hostWa('2');
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
	$dataKetua['devId']	    = hostWa('2');
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
	$dataSupport['devId']	= hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId'] = hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$linkUrl = "https://office.visiyosindo.id";
	if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl .= '/' . $data['link'];
	}

	$dataWa['devId']	= hostWa('2');
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
		'%0A%0ASegera periksa detail surat dan lakukan persetujuan pada ' . $linkUrl .
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

	$dataWa['devId']	= hostWa('2');
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

	$linkUrl = "https://office.visiyosindo.id";
	if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl .= '/' . $data['link'];
	}

	$dataWa['devId']	= hostWa('2');
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
		'%0A%0ASegera periksa detail surat pada ' . $linkUrl .
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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
	$dataWa['devId']	= hostWa('2');
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

	$linkUrl = "https://office.visiyosindo.id";
	if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl .= '/' . $data['link'];
	}

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Penolakan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A ' . $data['namaSurat'] . ' yang anda ajukan:' .
		'%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATidak disetujui oleh: ' . $data['namaPenolak'] .
		'%0A%0ASegera hubungi ' . $data['namaPenolak'] . ' pada nomor *' . $data['noPenolak'] . '*' .
		'%0A%0ASegera periksa detail surat pada ' . $linkUrl .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSlipGaji($data, $link)
{
	$data['perihal']    = cekPerihal($data['perihal']);

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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

	$dataWa['devId']	= hostWa('2');
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
	// Daftar Penerima Hardcode
	$recipients = [
		'081275061896'  => '_Dian Melati Amelia_',
		'0895410953087' => '_HR & Legal Visi Yosindo Medikal_',
	];

	$dataWa['devId'] = hostWa('2'); // Menggunakan Device ID AdminIT

	// Loop ke setiap nomor hardcode
	foreach ($recipients as $noHp => $namaPenerima) {

		$dataWa['penerima'] = $noHp;

		// Pesan WA
		$dataWa['pesan']    =  '*Notifikasi Upload Sertifikat Training*' .
			'%0A%0ADear ' . $namaPenerima .
			',%0A%0AUser *' . $data['namaPengaju'] . '* telah mengupload sertifikat/bukti kehadiran.' .
			'%0A%0ADetail Training:' .
			'%0AKode : ' . $data['kodeSurat'] .
			'%0ANama Training : ' . $data['namaTraining'] .
			'%0A%0ASilahkan cek dokumen tersebut di sistem.' .
			'%0A%0ATerima Kasih';

		sendWa($dataWa);
		// Beri jeda 1 detik agar tidak dianggap spam oleh server WA (opsional)
		sleep(1);
	}
	return true;
}

function waReminderCustomTemplate($data)
{
	$dataWa['devId']    = hostWa('2');
	$dataWa['penerima'] = $data['target_phone'];

	// Header
	$pesan = '*NOTIFIKASI REMINDER ULANG TAHUN DAN ANNIVERSARY KARYAWAN VISI YSODINO MEDIKAL*';
	$pesan .= '%0A%0A_Dear, *Athala Aqsha*_';
	$pesan .= '%0A%0ABerikut Data Karyawan yang akan Ulang Tahun atau Work Anniversary :%0A%0A';

	// List Karyawan (Data ini disusun di Controller)
	$pesan .= $data['list_karyawan'];

	// Footer
	$pesan .= '%0A%0A_*Pesan Ini Dikirim secara otomatis oleh sistem_';

	$dataWa['pesan'] = $pesan;
	sendWa($dataWa);
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

function waTagihanApprovalSafe($data)
{
	// 1. VALIDASI WAJIB
	if (empty($data['target_phone']) || empty($data['no_sj']) || empty($data['nama_pengaju'])) {
		return [
			'success' => false,
			'message' => 'Data wajib tidak lengkap'
		];
	}

	// 2. FORMAT NOMOR HP STANDAR
	$phone = preg_replace('/[^0-9]/', '', $data['target_phone']);
	if (substr($phone, 0, 2) == '08') {
		$phone = '62' . substr($phone, 1);
	}

	if (strlen($phone) < 10) {
		return [
			'success' => false,
			'message' => 'Nomor HP tidak valid'
		];
	}

	// 3. BUAT PESAN STANDAR & AMAN
	$pesan = createStandardMessage($data);

	// 4.  KIRIM DENGAN 3 METODE FALLBACK
	return sendWaWithTripleFallback($phone, $pesan);
}

function createStandardMessage($data)
{
	// Template standar yang aman dan mudah dibaca
	$message = "*Notifikasi Pengajuan Tagihan Ekspedisi*\n\n";
	$message .= "_Dear *" . clean_text($data['target_role'] ?? 'Tim Approval') . "*_,\n\n";
	$message .= clean_text($data['nama_pengaju']) .  " *" . clean_text($data['action_status']) . "* tagihan ekspedisi:\n\n";

	$message .= "_No SJ : *" .  $data['no_sj'] . "*_\n";
	$message .= "_Ekspedisi: *" . clean_text($data['ekspedisi'] ?? '-') . "*_\n";

	// --- BAGIAN INI DIUBAH (Ganti Biaya jadi History) ---
	// Mengambil data 'history_info' jika dikirim dari Controller, 
	// jika tidak ada maka pakai default static sesuai request.
	$history_text = isset($data['history_info']) ? $data['history_info'] : " - Head of Warehouse & PJT (*Menunggu*)";

	$message .= "History Approval :\n" . $history_text . "\n";
	// ----------------------------------------------------

	if (! empty($data['catatan'])) {
		$message .= "\nCatatan: _" . clean_text($data['catatan']) . "_\n";
	}

	$message .= "\nMohon agar dapat diperiksa pada Office melalui Link: " . ($data['link_url'] ?? base_url('tagihan')) . "\n\n";
	$message .= "_*Terima Kasih*_";

	return $message;
}

function clean_text($text)
{
	// Bersihkan teks dari karakter berbahaya
	$text = strip_tags($text);
	$text = str_replace(['*', '_', '`', '~'], '', $text);
	return trim($text);
}

function sendWaWithTripleFallback($phone, $message)
{
	$devId = hostWa('2');
	$encoded_message = urlencode($message);

	// METODE 1: file_get_contents dengan timeout singkat
	$result1 = sendWaMethod1($devId, $phone, $encoded_message);
	if ($result1['success']) return $result1;

	// METODE 2: cURL dengan setting optimal
	$result2 = sendWaMethod2($devId, $phone, $encoded_message);
	if ($result2['success']) return $result2;

	// METODE 3: Pesan sederhana sebagai backup
	$result3 = sendWaMethod3($devId, $phone, $message);
	if ($result3['success']) return $result3;

	// Semua gagal
	return [
		'success' => false,
		'message' => 'Semua metode pengiriman gagal',
		'attempts' => 3
	];
}

function sendWaMethod1($devId, $phone, $message)
{
	$url = "https://app.whacenter.com/api/send?device_id={$devId}&number={$phone}&message={$message}";

	$context = stream_context_create([
		'http' => [
			'timeout' => 20, // 20 detik saja
			'method' => 'GET',
			'header' => 'User-Agent: VYM-System/1.0'
		]
	]);

	$response = @file_get_contents($url, false, $context);

	if ($response !== FALSE) {
		$json = json_decode($response, true);
		if (isset($json['status']) && $json['status'] == true) {
			return [
				'success' => true,
				'message' => 'Terkirim via Method 1',
				'method' => 'file_get_contents'
			];
		}
	}

	return ['success' => false, 'message' => 'Method 1 gagal'];
}

function sendWaMethod2($devId, $phone, $message)
{
	if (!function_exists('curl_init')) {
		return ['success' => false, 'message' => 'cURL tidak tersedia'];
	}

	$url = "https://app.whacenter. com/api/send?device_id={$devId}&number={$phone}&message={$message}";

	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 25,
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_USERAGENT => 'VYM-System-cURL/1.0'
	]);

	$response = curl_exec($curl);
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	if ($response && $httpCode == 200) {
		$json = json_decode($response, true);
		if (isset($json['status']) && $json['status'] == true) {
			return [
				'success' => true,
				'message' => 'Terkirim via Method 2 (cURL)',
				'method' => 'curl'
			];
		}
	}

	return ['success' => false, 'message' => 'Method 2 gagal'];
}

function sendWaMethod3($devId, $phone, $originalMessage)
{
	// Pesan backup yang super sederhana
	$simpleMessage = "NOTIFIKASI TAGIHAN EKSPEDISI - Silakan cek sistem untuk detail.  Link: " . base_url('tagihan');
	$encoded = urlencode($simpleMessage);

	$url = "https://app.whacenter. com/api/send?device_id={$devId}&number={$phone}&message={$encoded}";

	$response = @file_get_contents($url);

	if ($response !== FALSE) {
		$json = json_decode($response, true);
		if (isset($json['status']) && $json['status'] == true) {
			return [
				'success' => true,
				'message' => 'Terkirim via Method 3 (Backup)',
				'method' => 'backup_simple'
			];
		}
	}

	return ['success' => false, 'message' => 'Method 3 gagal'];
}

// FUNGSI LAMA TETAP ADA UNTUK COMPATIBILITY
function waTagihanApproval($data)
{
	return waTagihanApprovalSafe($data);
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// fungsi kirim WA (DIPERBAIKI DENGAN TIMEOUT)
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// INTEGRASI CONVIA WHATSAPP API (app.convia.id) & WHACENTER FALLBACK
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Kirim pesan WhatsApp personal via Convia API (https://app.convia.id)
 * 
 * @param array $dataSend ['penerima' => '0812...', 'pesan' => '...', 'devId' => '...']
 * @return bool
 */
function sendWaConvia($dataSend)
{
	if (empty($dataSend['penerima']) || empty($dataSend['pesan'])) {
		return false;
	}

	$apiKey = defined('CONVIA_API_KEY') ? CONVIA_API_KEY : (getenv('CONVIA_API_KEY') ?: '');
	if (empty($apiKey) && function_exists('get_instance')) {
		$CI = &get_instance();
		if ($CI && isset($CI->config)) {
			$apiKey = $CI->config->item('convia_api_key');
		}
	}

	if (empty($apiKey)) {
		return false;
	}

	// Format nomor HP ke standar 62xxx
	$penerima = preg_replace('/[^0-9]/', '', $dataSend['penerima']);
	if (substr($penerima, 0, 2) == '08') {
		$penerima = '62' . substr($penerima, 1);
	}

	$pesan = urldecode($dataSend['pesan']);
	$apiUrl = defined('CONVIA_API_URL') ? CONVIA_API_URL : 'https://app.convia.id/api/v1/send-message';

	// Payload serbaguna (mendukung header & body api_key Convia)
	$payload = [
		'api_key' => $apiKey,
		'token'   => $apiKey,
		'target'  => $penerima,
		'number'  => $penerima,
		'phone'   => $penerima,
		'to'      => $penerima,
		'message' => $pesan
	];

	if (isset($dataSend['file']) && !empty($dataSend['file'])) {
		$payload['url'] = $dataSend['file'];
	}

	if (function_exists('curl_init')) {
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL            => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => json_encode($payload),
			CURLOPT_HTTPHEADER     => [
				'Content-Type: application/json',
				'Authorization: Bearer ' . $apiKey,
				'x-api-key: ' . $apiKey,
				'api-key: ' . $apiKey
			],
			CURLOPT_TIMEOUT        => 10,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false
		]);

				$response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		// TEMPORARY DEBUG: Tampilkan respon asli dari server Convia di layar
		ajaxReturnDie('error', 'Respon Server Convia (HTTP ' . $httpCode . '): ' . $response, FALSE);
	}

	return false;
}
/**
 * Kirim pesan WhatsApp group via Convia API (https://app.convia.id)
 */
function sendWaConviaGroup($dataSend)
{
	if (empty($dataSend['penerima']) || empty($dataSend['pesan'])) {
		return false;
	}

	$apiKey = defined('CONVIA_API_KEY') ? CONVIA_API_KEY : (getenv('CONVIA_API_KEY') ?: '');
	if (empty($apiKey) && function_exists('get_instance')) {
		$CI = &get_instance();
		if ($CI && isset($CI->config)) {
			$apiKey = $CI->config->item('convia_api_key');
		}
	}

	if (empty($apiKey)) {
		return false;
	}

	$pesan = urldecode($dataSend['pesan']);
	$apiUrl = defined('CONVIA_API_URL') ? CONVIA_API_URL : 'https://app.convia.id/api/v1/send-message';

	$payload = [
		'target'  => $dataSend['penerima'],
		'group'   => $dataSend['penerima'],
		'message' => $pesan,
		'is_group'=> true
	];

	if (function_exists('curl_init')) {
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL            => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => json_encode($payload),
			CURLOPT_HTTPHEADER     => [
				'Content-Type: application/json',
				'Authorization: Bearer ' . $apiKey,
				'x-api-key: ' . $apiKey
			],
			CURLOPT_TIMEOUT        => 8,
			CURLOPT_CONNECTTIMEOUT => 4,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false
		]);

		$response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if ($response && ($httpCode == 200 || $httpCode == 201)) {
			$json = json_decode($response, true);
			if (isset($json['status']) && ($json['status'] == true || $json['status'] == 'success')) {
				return true;
			}
		}
	}

	return false;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// INTEGRASI FONNTE WHATSAPP API (md.fonnte.com / fonnte.com)
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Kirim pesan WhatsApp personal/group via Fonnte API (https://fonnte.com)
 * 
 * @param array $dataSend ['penerima' => '0812...', 'pesan' => '...', 'file' => '...']
 * @return bool
 */
function sendWaFonnte($dataSend)
{
	if (empty($dataSend['penerima']) || empty($dataSend['pesan'])) {
		return false;
	}

	$token = defined('FONNTE_TOKEN') ? FONNTE_TOKEN : (getenv('FONNTE_TOKEN') ?: '');
	if (empty($token) && function_exists('get_instance')) {
		$CI = &get_instance();
		if ($CI && isset($CI->config)) {
			$token = $CI->config->item('fonnte_token');
		}
	}

	if (empty($token)) {
		return false; // Jika Token belum diisi, langsung fallback
	}

	$penerima = preg_replace('/[^0-9]/', '', $dataSend['penerima']);
	if (substr($penerima, 0, 2) == '08') {
		$penerima = '0' . substr($penerima, 1);
	}
	if (empty($penerima)) {
		$penerima = $dataSend['penerima'];
	}

	$pesan = urldecode($dataSend['pesan']);
	$apiUrl = defined('FONNTE_API_URL') ? FONNTE_API_URL : 'https://api.fonnte.com/send';

	$payload = [
		'target'      => $penerima,
		'message'     => $pesan,
		'countryCode' => '62'
	];

	if (isset($dataSend['file']) && !empty($dataSend['file'])) {
		$payload['url'] = $dataSend['file'];
	}

	if (function_exists('curl_init')) {
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL            => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $payload,
			CURLOPT_HTTPHEADER     => [
				'Authorization: ' . $token
			],
			CURLOPT_TIMEOUT        => 8,
			CURLOPT_CONNECTTIMEOUT => 4,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false
		]);

		$response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if ($response && ($httpCode == 200 || $httpCode == 201)) {
			$json = json_decode($response, true);
			if (isset($json['status']) && $json['status'] == true) {
				return true;
			}
		}
	}

	return false;
}

function sendWaFonnteGroup($dataSend)
{
	return sendWaFonnte($dataSend);
}

/**
 * Fungsi Utama Kirim WA (Fonnte API -> Convia API -> Automatic Fallback ke Whacenter)
 */
function sendWa($dataSend)
{
	// 1. Coba kirim via Convia API lebih dulu
	if (sendWaConvia($dataSend)) {
		return true;
	}

	// 2. Fallback ke Fonnte jika Convia belum dikonfigurasi / gagal
	if (sendWaFonnte($dataSend)) {
		return true;
	}

	// 3. Fallback ke Whacenter
	return sendWaWhacenter($dataSend);
}
/**
 * Kirim pesan via Whacenter Gateway
 */
function sendWaWhacenter($dataSend)
{
	$penerima = preg_replace('/[^0-9]/', '', $dataSend['penerima']);
	if (substr($penerima, 0, 2) == '08') {
		$penerima = '62' . substr($penerima, 1);
	}

	if (empty($penerima)) {
		return false;
	}

	$pesan = urlencode(urldecode($dataSend['pesan']));
	$url = "https://app.whacenter.com/api/send?device_id=" . urlencode($dataSend['devId']) . "&number=" . urlencode($penerima) . "&message=" . $pesan;

	if (function_exists('curl_init')) {
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 5,
			CURLOPT_CONNECTTIMEOUT => 3,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_FOLLOWLOCATION => true
		]);
		$response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if ($response && $httpCode == 200) {
			return true;
		}
	}

	$ctx = stream_context_create([
		'http' => [
			'timeout' => 5,
			'header'  => "User-Agent: VYM-System/1.0\r\n"
		],
		'ssl' => [
			'verify_peer'      => false,
			'verify_peer_name' => false
		]
	]);
	@file_get_contents($url, false, $ctx);

	return true;
}

/**
 * Fungsi Utama Kirim WA Group (Fonnte API -> Fallback ke Whacenter)
 */
function sendWaGroup($dataSend)
{
	if (sendWaFonnteGroup($dataSend)) {
		return true;
	}

	return sendWaWhacenterGroup($dataSend);
}

/**
 * Kirim pesan group via Whacenter Gateway
 */
function sendWaWhacenterGroup($dataSend)
{
	if (empty($dataSend['penerima'])) {
		return false;
	}

	$pesan = urlencode(urldecode($dataSend['pesan']));
	$url = "https://app.whacenter.com/api/sendGroup?device_id=" . urlencode($dataSend['devId']) . "&group=" . urlencode(urldecode($dataSend['penerima'])) . "&message=" . $pesan;

	if (function_exists('curl_init')) {
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 5,
			CURLOPT_CONNECTTIMEOUT => 3,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_FOLLOWLOCATION => true
		]);
		$response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if ($response && $httpCode == 200) {
			return true;
		}
	}

	$ctx = stream_context_create([
		'http' => [
			'timeout' => 5,
			'header'  => "User-Agent: VYM-System/1.0\r\n"
		],
		'ssl' => [
			'verify_peer'      => false,
			'verify_peer_name' => false
		]
	]);
	@file_get_contents($url, false, $ctx);

	return true;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Notifikasi Training Teknisi
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
function waTrainingTeknisiOpen($data)
{
	$dataWa['devId']    = hostWa('2');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan']    = '*Notifikasi Training Teknisi*' .
		'%0A%0ADear ' . $data['namaPenerima'] . ',' .
		'%0A%0AAnda telah ditugaskan untuk mengikuti Training Teknisi dengan detail sebagai berikut:' .
		'%0AKode Training : ' . $data['kodeTraining'] .
		'%0ARekanan       : ' . $data['rekanan'] .
		'%0ACP Customer   : ' . $data['contactPerson'] .
		'%0ASubjek        : ' . $data['subject'] .
		'%0AKategori      : ' . $data['kategori'] .
		'%0APrioritas     : ' . $data['prioritas'] .
		'%0AWaktu         : ' . $data['waktu'] .
		'%0A%0ASegera periksa detail training Anda di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waTrainingTeknisiUpdate($data)
{
	$dataWa['devId']    = hostWa('2');
	$dataWa['penerima'] = $data['noPenerima'];
	$dataWa['pesan']    = '*Notifikasi Update Training Teknisi*' .
		'%0A%0ADear Karyawan / Teknisi,' .
		'%0A%0ATerdapat update baru untuk Training Teknisi Anda:' .
		'%0AKode Training : ' . $data['kodeTraining'] .
		'%0ASubjek        : ' . $data['subject'] .
		'%0AUpdate Baru    : ' . $data['update'] .
		'%0A%0ASegera periksa detail update training Anda di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waTrainingTeknisiOpenGroup($data)
{
	$dataWa['devId']    = hostWa('2');
	$dataWa['penerima'] = $data['groupPenerima'];
	$dataWa['pesan']    = '*Notifikasi Training Teknisi (Group)*' .
		'%0A%0ATelah dibuat penugasan Training Teknisi baru dengan detail sebagai berikut:' .
		'%0AKode Training : ' . $data['kodeTraining'] .
		'%0ATeknisi       : ' . $data['namaTeknisi'] .
		'%0ARekanan       : ' . $data['rekanan'] .
		'%0ACP Customer   : ' . $data['contactPerson'] .
		'%0ASubjek        : ' . $data['subject'] .
		'%0AKategori      : ' . $data['kategori'] .
		'%0APrioritas     : ' . $data['prioritas'] .
		'%0AWaktu         : ' . $data['waktu'] .
		'%0A%0ASegera periksa detail training di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

function waTrainingTeknisiUpdateGroup($data)
{
	$dataWa['devId']    = hostWa('2');
	$dataWa['penerima'] = $data['groupPenerima'];
	$dataWa['pesan']    = '*Notifikasi Update Training Teknisi (Group)*' .
		'%0A%0ATerdapat update progres baru untuk Training Teknisi:' .
		'%0AKode Training : ' . $data['kodeTraining'] .
		'%0ASubjek        : ' . $data['subject'] .
		'%0AUpdate Oleh    : ' . $data['namaPengaju'] .
		'%0AUpdate Baru    : ' . $data['update'] .
		'%0A%0ASegera periksa detail update training di https://office.visiyosindo.id' .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Notifikasi Pengajuan (Fonnte API Compatible) ------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
if (!function_exists('waPengajuanBaru')) {
	function waPengajuanBaru($data)
	{
		$dataWa['penerima'] = $data['noPenerima'];
		$dataWa['pesan']    = "*NOTIFIKASI PENGAJUAN BARU* 📑" .
			"%0A%0AHalo *" . $data['namaApprover'] . "*, ada pengajuan baru yang membutuhkan persetujuan Anda:" .
			"%0A%0A• *Jenis Pengajuan* : " . $data['jenisPengajuan'] .
			"%0A• *Nama Pemohon* : " . $data['namaPemohon'] .
			"%0A• *Tanggal Pengajuan* : " . $data['tanggalPengajuan'] .
			"%0A• *Keterangan* : " . $data['keterangan'] .
			"%0A%0ASilakan periksa dan berikan persetujuan melalui link berikut:" .
			"%0A🔗 " . $data['linkDetail'] .
			"%0A%0ATerima Kasih." .
			"%0A_Sistem Office Visiyosindo_";

		return sendWa($dataWa);
	}
}

if (!function_exists('waPengajuanApproved')) {
	function waPengajuanApproved($data)
	{
		$catatan = !empty($data['catatan']) ? $data['catatan'] : '-';

		$dataWa['penerima'] = $data['noPenerima'];
		$dataWa['pesan']    = "*STATUS PENGAJUAN: DISETUJUI* ✅" .
			"%0A%0AHalo *" . $data['namaPemohon'] . "*, pengajuan Anda telah *DISETUJUI*." .
			"%0A%0A• *Jenis Pengajuan* : " . $data['jenisPengajuan'] .
			"%0A• *Disetujui Oleh* : " . $data['namaApprover'] .
			"%0A• *Catatan* : " . $catatan .
			"%0A%0ADetail pengajuan dapat dilihat di:" .
			"%0A🔗 " . $data['linkDetail'] .
			"%0A%0ATerima Kasih." .
			"%0A_Sistem Office Visiyosindo_";

		return sendWa($dataWa);
	}
}

if (!function_exists('waPengajuanRejected')) {
	function waPengajuanRejected($data)
	{
		$alasan = !empty($data['alasan']) ? $data['alasan'] : '-';

		$dataWa['penerima'] = $data['noPenerima'];
		$dataWa['pesan']    = "*STATUS PENGAJUAN: DITOLAK* ❌" .
			"%0A%0AHalo *" . $data['namaPemohon'] . "*, mohon maaf pengajuan Anda *DITOLAK*." .
			"%0A%0A• *Jenis Pengajuan* : " . $data['jenisPengajuan'] .
			"%0A• *Ditolak Oleh* : " . $data['namaApprover'] .
			"%0A• *Alasan Penolakan* : " . $alasan .
			"%0A%0ADetail pengajuan dapat dilihat di:" .
			"%0A🔗 " . $data['linkDetail'] .
			"%0A%0ATerima Kasih." .
			"%0A_Sistem Office Visiyosindo_";

		return sendWa($dataWa);
	}
}

