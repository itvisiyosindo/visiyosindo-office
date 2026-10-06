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

	$linkUrl = getSuratDetailUrl($data);

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
		'%0A%0AMohon segera periksa detail surat pada ' . $linkUrl . ' dan hubungi GA jika diperlukan.' .
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

	$linkUrl = getSuratDetailUrl($data);

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
		'%0A%0AMohon segera periksa detail surat pada ' . $linkUrl . ' dan hubungi Legal/HR jika diperlukan.' .
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

function getSuratDetailUrl($data)
{
	if (isset($data['link']) && !empty($data['link'])) {
		if (strpos($data['link'], 'http://') === 0 || strpos($data['link'], 'https://') === 0) {
			return $data['link'];
		}
		if (strpos($data['link'], '/') !== false) {
			return 'https://office.visiyosindo.id/' . ltrim($data['link'], '/');
		}
	}

	if (isset($data['urlNotif']) && !empty($data['urlNotif'])) {
		if (strpos($data['urlNotif'], 'http://') === 0 || strpos($data['urlNotif'], 'https://') === 0) {
			return $data['urlNotif'];
		}
		if (strpos($data['urlNotif'], '/') !== false) {
			return 'https://office.visiyosindo.id/' . ltrim($data['urlNotif'], '/');
		}
	}

	if (isset($data['url']) && !empty($data['url'])) {
		if (strpos($data['url'], 'http://') === 0 || strpos($data['url'], 'https://') === 0) {
			return $data['url'];
		}
		if (strpos($data['url'], '/') !== false) {
			return 'https://office.visiyosindo.id/' . ltrim($data['url'], '/');
		}
	}

	if (isset($data['idBA']) && !empty($data['idBA'])) {
		return 'https://office.visiyosindo.id/surat_part_two/show/detail/ba/' . $data['idBA'];
	}

	$id = null;
	if (isset($data['id']) && !empty($data['id'])) {
		$id = $data['id'];
	} else if (isset($data['idSurat']) && !empty($data['idSurat'])) {
		$id = $data['idSurat'];
	} else if (isset($data['id_srt']) && !empty($data['id_srt'])) {
		$id = $data['id_srt'];
	} else if (isset($data['link']) && is_numeric($data['link'])) {
		$id = $data['link'];
	}

	$kode = isset($data['kodeSurat']) ? trim(urldecode($data['kodeSurat'])) : (isset($data['kode']) ? trim(urldecode($data['kode'])) : '');
	$nama = isset($data['namaSurat']) ? strtolower(trim(urldecode($data['namaSurat']))) : '';
	$kodeUpper = strtoupper($kode);

	// Jika $id belum ada tapi ada kode, query database untuk mendapatkan ID surat
	if (empty($id) && !empty($kode) && function_exists('get_instance')) {
		$ci = &get_instance();
		if (isset($ci->db)) {
			if (strpos($kodeUpper, '/S.APP/WHS/') !== false || strpos($nama, 'expedisi') !== false || strpos($nama, 'ekspedisi') !== false) {
				$row = $ci->db->select('id')->from('surat_aprv')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/S.APP/DIR/') !== false || strpos($kodeUpper, '/APPDIR/') !== false || strpos($nama, 'approval director') !== false) {
				$row = $ci->db->select('id')->from('approval_director')->where('kode', $kode)->limit(1)->get()->row();
				if (empty($row)) {
					$row = $ci->db->select('id')->from('surat_direksi')->where('kode', $kode)->limit(1)->get()->row();
				}
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/STA/') !== false || strpos($kodeUpper, '/STFP/') !== false || strpos($kodeUpper, '/STP/') !== false) {
				$row = $ci->db->select('id_serah')->from('surat_serah')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id_serah; }
			} else if (strpos($kodeUpper, '/AHK/') !== false) {
				$row = $ci->db->select('id_approval')->from('surat_approval')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id_approval; }
			} else if (strpos($kodeUpper, '/SPP/') !== false || strpos($kodeUpper, '/FPP/') !== false) {
				$row = $ci->db->select('id')->from('surat_permintaan_pembayaran')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/PBOK/') !== false) {
				$row = $ci->db->select('id_pbok')->from('surat_pbok')->where('kode_pbok', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id_pbok; }
			} else if (strpos($kodeUpper, '/PB/') !== false || strpos($kodeUpper, '/BD/') !== false) {
				$row = $ci->db->select('id_pb')->from('surat_biaya_dinas')->where('kode_pb', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id_pb; }
			} else if (strpos($kodeUpper, '/PKK/') !== false || strpos($kodeUpper, '/PKE/') !== false) {
				$row = $ci->db->select('id_pkk')->from('surat_pkk')->where('kode_pkk', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id_pkk; }
			} else if (strpos($kodeUpper, '/PPPA/') !== false || strpos($kodeUpper, '/PPA/') !== false) {
				$row = $ci->db->select('id_ppa')->from('surat_ppa')->where('kode_ppa', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id_ppa; }
			} else if (strpos($kodeUpper, '/GC/') !== false || strpos($kodeUpper, '/GJ/') !== false) {
				$row = $ci->db->select('id')->from('surat_gojek_corp')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/SPD/MKT/') !== false || strpos($kodeUpper, '/SPD/TKN/') !== false || strpos($kodeUpper, '/SPD/KRY/') !== false || strpos($kodeUpper, '/PD/KYW/') !== false || strpos($kodeUpper, '/PD/') !== false) {
				$row = $ci->db->select('id_pd')->from('surat_pd')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id_pd; }
			} else if (strpos($kodeUpper, '/SPD/HRGA/') !== false || strpos($kodeUpper, '/SD/') !== false || strpos($kodeUpper, '/PDD/') !== false) {
				$row = $ci->db->select('id')->from('surat_pd_dinas')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/APRVL/CRO/') !== false || strpos($kodeUpper, '/PO/') !== false) {
				$row = $ci->db->select('id')->from('surat_po')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/PPKK/') !== false || strpos($kodeUpper, '/PK/') !== false) {
				$row = $ci->db->select('id')->from('surat_kendaraan')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/FP/') !== false) {
				$row = $ci->db->select('id')->from('approval_faktur_pajak')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/SKORSING/') !== false || strpos($kodeUpper, '/SKORS/') !== false) {
				$row = $ci->db->select('id')->from('surat_skorsing')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/BA/') !== false) {
				$row = $ci->db->select('id')->from('surat_berita_acara')->where('kode_ba', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/CUTI/') !== false) {
				$row = $ci->db->select('id')->from('surat_cuti_tahunan')->where('kode_cuti', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/IZIN/') !== false || strpos($kodeUpper, '/IJK/') !== false) {
				$row = $ci->db->select('id')->from('surat_izin_jam_kerja')->where('kode_ijk', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/MR/') !== false) {
				$row = $ci->db->select('id')->from('meeting_room')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			} else if (strpos($kodeUpper, '/KG/') !== false || strpos($kodeUpper, '/LKG/') !== false) {
				$row = $ci->db->select('id')->from('surat_kunjungan_gudang')->where('kode', $kode)->limit(1)->get()->row();
				if (!empty($row)) { $id = $row->id; }
			}
		}
	}

	if (!empty($id)) {
		// 1. Surat Modul Baru (Surat_new.php)
		if (strpos($kodeUpper, '/S.APP/WHS/') !== false || strpos($nama, 'expedisi') !== false || strpos($nama, 'ekspedisi') !== false) {
			return 'https://office.visiyosindo.id/surat_new/show/detail/appeks/' . $id;
		}
		if (strpos($kodeUpper, '/S.APP/DIR/') !== false || strpos($kodeUpper, '/APPDIR/') !== false || strpos($nama, 'approval director') !== false) {
			return 'https://office.visiyosindo.id/surat_new/show/detail/appdir/' . $id;
		}
		if (strpos($kodeUpper, '/STA/') !== false || strpos($nama, 'serah terima aset') !== false) {
			return 'https://office.visiyosindo.id/surat_new/show/detail/sta/' . $id;
		}
		if (strpos($kodeUpper, '/STFP/') !== false || strpos($nama, 'fasilitas perusahaan') !== false || strpos($nama, 'fisik perlengkapan') !== false) {
			return 'https://office.visiyosindo.id/surat_new/show/detail/stfp/' . $id;
		}
		if (strpos($kodeUpper, '/STP/') !== false || strpos($nama, 'serah terima pekerjaan') !== false || strpos($nama, 'serah terima peralatan') !== false) {
			return 'https://office.visiyosindo.id/surat_new/show/detail/stp/' . $id;
		}
		if (strpos($kodeUpper, '/SPI/') !== false || strpos($nama, 'perintah instalasi') !== false) {
			return 'https://office.visiyosindo.id/surat_new/show/detail/spi/' . $id;
		}
		if (strpos($kodeUpper, '/APRVL/CRO/') !== false || strpos($kodeUpper, '/PO/') !== false || strpos($nama, 'approval po') !== false || strpos($nama, 'purchase order') !== false) {
			return 'https://office.visiyosindo.id/surat_new/show/detail/po/' . $id;
		}
		if (strpos($kodeUpper, '/PPKK/') !== false || strpos($kodeUpper, '/PK/') !== false || strpos($nama, 'peminjaman kendaraan') !== false || strpos($nama, 'penggunaan kendaraan') !== false || strpos($nama, 'kendaraan') !== false) {
			return 'https://office.visiyosindo.id/surat_new/show/detail/kendaraan/' . $id;
		}
		if (strpos($kodeUpper, '/FP/') !== false || strpos($nama, 'faktur pajak') !== false || strpos($nama, 'approval pajak') !== false) {
			return 'https://office.visiyosindo.id/surat_new/show/detail/app_pajak/' . $id;
		}
		if (strpos($kodeUpper, '/SKORSING/') !== false || strpos($kodeUpper, '/SKORS/') !== false || strpos($nama, 'skorsing') !== false) {
			return 'https://office.visiyosindo.id/surat_new/show/detail/skorsing/' . $id;
		}

		// 2. Surat Modul Part Two (Surat_part_two.php)
		if (strpos($kodeUpper, '/BA/') !== false || strpos($nama, 'berita acara') !== false) {
			return 'https://office.visiyosindo.id/surat_part_two/show/detail/ba/' . $id;
		}
		if (strpos($kodeUpper, '/MR/') !== false || strpos($nama, 'meeting') !== false) {
			return 'https://office.visiyosindo.id/surat_part_two/show/detail/meetingroom/' . $id;
		}
		if (strpos($kodeUpper, '/CUTI/') !== false || strpos($nama, 'cuti') !== false) {
			if (strpos($kodeUpper, 'SGM') !== false || strpos($nama, 'sgm') !== false) {
				return 'https://office.visiyosindo.id/surat_part_two/show/detail/cuti_sgm/' . $id;
			}
			return 'https://office.visiyosindo.id/surat_part_two/show/detail/cuti/' . $id;
		}
		if (strpos($kodeUpper, '/IZIN/') !== false || strpos($kodeUpper, '/IJK/') !== false || strpos($nama, 'jam kerja') !== false || strpos($nama, 'meninggalkan') !== false || strpos($nama, 'izin') !== false) {
			if (strpos($kodeUpper, 'SGM') !== false || strpos($nama, 'sgm') !== false) {
				if (strpos($nama, 'meninggalkan') !== false) {
					return 'https://office.visiyosindo.id/surat_part_two/show/detail/izin_meninggalkan_sgm/' . $id;
				}
				return 'https://office.visiyosindo.id/surat_part_two/show/detail/izin_jam_kerja_sgm/' . $id;
			}
			if (strpos($nama, 'meninggalkan') !== false) {
				return 'https://office.visiyosindo.id/surat_part_two/show/detail/izin_meninggalkan/' . $id;
			}
			return 'https://office.visiyosindo.id/surat_part_two/show/detail/izin_jam_kerja/' . $id;
		}

		// 3. Surat Modul Utama (Surat.php)
		if (strpos($kodeUpper, '/SPP/') !== false || strpos($kodeUpper, '/FPP/') !== false || strpos($nama, 'permintaan pembayaran') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/spp/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/PBOK/') !== false || strpos($nama, 'operasional kantor') !== false || strpos($nama, 'operasional kas') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/pbok/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/PKE/') !== false || strpos($kodeUpper, 'ETOLL') !== false || (strpos($nama, 'klaim kas') !== false && strpos($nama, 'toll') !== false)) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/pkketoll/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/PKK/') !== false || strpos($nama, 'klaim kas') !== false || strpos($nama, 'kasbon kurir') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/pkk/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/PPPA/') !== false || strpos($kodeUpper, '/PPA/') !== false || strpos($nama, 'pemeliharaan aset') !== false || strpos($nama, 'pembelian dan pemeliharaan') !== false || strpos($nama, 'pembayaran awal') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/ppa/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/GC/') !== false || strpos($kodeUpper, '/GJ/') !== false || strpos($nama, 'gojek') !== false || strpos($nama, 'grab') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/gc/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/KG/') !== false || strpos($kodeUpper, '/LKG/') !== false || strpos($nama, 'kunjungan gudang') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/kg/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/AHK/') !== false || strpos($nama, 'approval harga') !== false || strpos($nama, 'surat approval') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/approval/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/SPD/MKT/') !== false || (strpos($kodeUpper, '/PD/') !== false && strpos($nama, 'marketing') !== false) || strpos($nama, 'dinas marketing') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/pd/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/SPD/TKN/') !== false || strpos($kodeUpper, '/PDT/') !== false || strpos($nama, 'dinas teknisi') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/pd_teknisi/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/SPD/KRY/') !== false || strpos($kodeUpper, '/PD/KYW/') !== false || strpos($kodeUpper, '/PDK/') !== false || strpos($nama, 'dinas karyawan') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/pd_karyawan/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/SPD/HRGA/') !== false || strpos($kodeUpper, '/SD/') !== false || strpos($kodeUpper, '/PDD/') !== false || strpos($nama, 'pertanggungjawaban dinas') !== false || strpos($nama, 'surat dinas') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/sd/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/PB/') !== false || strpos($kodeUpper, '/BD/') !== false || strpos($nama, 'perjalanan dinas') !== false || strpos($nama, 'biaya dinas') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/PB/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/SP/') !== false || strpos($nama, 'peringatan') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/surat_peringatan/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/STG/') !== false || strpos($kodeUpper, '/ST/') !== false || strpos($nama, 'surat tugas') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/st/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/SK.DIR/') !== false || strpos($kodeUpper, '/SKD/') !== false || strpos($nama, 'keputusan direksi') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/skd/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/S.RKM/') !== false || strpos($kodeUpper, '/REKOM/') !== false || strpos($nama, 'rekomendasi') !== false) {
			return 'https://office.visiyosindo.id/surat/show/detail_surat/rekom/' . $id . '/1';
		}
		if (strpos($kodeUpper, '/S.KET/') !== false || strpos($kodeUpper, '/KET/') !== false || strpos($nama, 'keterangan') !== false) {
			if (strpos($kodeUpper, 'PKU') !== false || strpos($nama, 'paklaring') !== false) {
				return 'https://office.visiyosindo.id/surat_part_two/show/detail/paklaring/' . $id;
			}
			return 'https://office.visiyosindo.id/surat/show/detail_surat/keterangan/' . $id . '/1';
		}
	}

	return "https://office.visiyosindo.id";
}

function waSuratOpen($data)
{
	$data['perihal']    = cekPerihal($data['perihal']);
	$linkUrl            = getSuratDetailUrl($data);

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ASegera periksa detail surat dan lakukan persetujuan pada ' . $linkUrl .
		'%0A%0ATerima Kasih%0A_Sistem Office PT Visi Yosindo Medikal_';
	sendWa($dataWa);
	return true;
}


function waSuratOpenLink($data)
{
	$data['perihal']    = cekPerihal($data['perihal']);
	$linkUrl            = getSuratDetailUrl($data);

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' mengajukan ' . $data['namaSurat'] .
		':%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ASegera periksa detail surat dan lakukan persetujuan pada ' . $linkUrl .
		'%0A%0ATerima Kasih%0A_Sistem Office PT Visi Yosindo Medikal_';
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
	$linkUrl = "https://office.visiyosindo.id/po_visilab/show/permintaan/purchase_order";
	if (isset($data['id']) && !empty($data['id'])) {
		$linkUrl = "https://office.visiyosindo.id/po_visilab/show/detail/purchase_order/" . $data['id'];
	} else if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl = (strpos($data['link'], 'http') === 0) ? $data['link'] : 'https://office.visiyosindo.id/' . ltrim($data['link'], '/');
	}

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier Visilab di ' . $linkUrl .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}


function waPermintaanPoVisilabGroup($data)
{
	$linkUrl = "https://office.visiyosindo.id/po_visilab/show/permintaan/purchase_order";
	if (isset($data['id']) && !empty($data['id'])) {
		$linkUrl = "https://office.visiyosindo.id/po_visilab/show/detail/purchase_order/" . $data['id'];
	} else if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl = (strpos($data['link'], 'http') === 0) ? $data['link'] : 'https://office.visiyosindo.id/' . ltrim($data['link'], '/');
	}

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier Visilab di ' . $linkUrl .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}


function waPoVisilabAprovOnProg($data)
{
	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$linkUrl = "https://office.visiyosindo.id/po_visilab/show/permintaan/purchase_order";
	if (isset($data['id']) && !empty($data['id'])) {
		$linkUrl = "https://office.visiyosindo.id/po_visilab/show/detail/purchase_order/" . $data['id'];
	} else if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl = (strpos($data['link'], 'http') === 0) ? $data['link'] : 'https://office.visiyosindo.id/' . ltrim($data['link'], '/');
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
		'%0A%0ASegera ' . $data['proses'] . ' Permintaan PO Supplier Visilab di ' . $linkUrl .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waPoVisilabAprovAll($data)
{
	// Dinonaktifkan untuk menghemat kuota chat jika di-ACC semua
	return TRUE;
}

function waPoVisilabAprovAllGroup($data)
{
	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$linkUrl = "https://office.visiyosindo.id/po_visilab/show/permintaan/purchase_order";
	if (isset($data['id']) && !empty($data['id'])) {
		$linkUrl = "https://office.visiyosindo.id/po_visilab/show/detail/purchase_order/" . $data['id'];
	} else if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl = (strpos($data['link'], 'http') === 0) ? $data['link'] : 'https://office.visiyosindo.id/' . ltrim($data['link'], '/');
	}

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A_PO Supplier Nomor    : ' . $data['noPo'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier Visilab di ' . $linkUrl .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return TRUE;
}

function waPoVisilabAprovAllGroupAdm($data)
{
	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$linkUrl = "https://office.visiyosindo.id/po_visilab/show/permintaan/purchase_order";
	if (isset($data['id']) && !empty($data['id'])) {
		$linkUrl = "https://office.visiyosindo.id/po_visilab/show/detail/purchase_order/" . $data['id'];
	} else if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl = (strpos($data['link'], 'http') === 0) ? $data['link'] : 'https://office.visiyosindo.id/' . ltrim($data['link'], '/');
	}

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A_Status Penerimaan    : ' . $data['noPo'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier Visilab di ' . $linkUrl .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return TRUE;
}



//======================================================
//===========   PO                         =============
//======================================================


function waPoOpen($data)
{
	$linkUrl = "https://office.visiyosindo.id/purchase_order/show/permintaan/purchase_order";
	if (isset($data['id']) && !empty($data['id'])) {
		$linkUrl = "https://office.visiyosindo.id/purchase_order/show/detail/purchase_order/" . $data['id'];
	} else if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl = (strpos($data['link'], 'http') === 0) ? $data['link'] : 'https://office.visiyosindo.id/' . ltrim($data['link'], '/');
	}

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier di ' . $linkUrl .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waPermintaanPoGroup($data)
{
	$linkUrl = "https://office.visiyosindo.id/purchase_order/show/permintaan/purchase_order";
	if (isset($data['id']) && !empty($data['id'])) {
		$linkUrl = "https://office.visiyosindo.id/purchase_order/show/detail/purchase_order/" . $data['id'];
	} else if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl = (strpos($data['link'], 'http') === 0) ? $data['link'] : 'https://office.visiyosindo.id/' . ltrim($data['link'], '/');
	}

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Pengajuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPenerima'] .
		',%0A%0A' . $data['namaPengaju'] . ' *mengajukan* ' . $data['namaSurat'] .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier di ' . $linkUrl .
		'%0A%0ATerima Kasih';
	sendWaGroup($dataWa);
	return true;
}


function waPoAprovOnProg($data)
{
	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$linkUrl = "https://office.visiyosindo.id/purchase_order/show/permintaan/purchase_order";
	if (isset($data['id']) && !empty($data['id'])) {
		$linkUrl = "https://office.visiyosindo.id/purchase_order/show/detail/purchase_order/" . $data['id'];
	} else if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl = (strpos($data['link'], 'http') === 0) ? $data['link'] : 'https://office.visiyosindo.id/' . ltrim($data['link'], '/');
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
		'%0A%0ASegera ' . $data['proses'] . ' Permintaan PO Supplier di ' . $linkUrl .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waPoAprovAll($data)
{
	// Dinonaktifkan untuk menghemat kuota chat jika di-ACC semua
	return TRUE;
}


function waPoAprovAllGroup($data)
{
	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$linkUrl = "https://office.visiyosindo.id/purchase_order/show/permintaan/purchase_order";
	if (isset($data['id']) && !empty($data['id'])) {
		$linkUrl = "https://office.visiyosindo.id/purchase_order/show/detail/purchase_order/" . $data['id'];
	} else if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl = (strpos($data['link'], 'http') === 0) ? $data['link'] : 'https://office.visiyosindo.id/' . ltrim($data['link'], '/');
	}

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Persetujuan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A_PO Supplier Nomor    : ' . $data['noPo'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier di ' . $linkUrl .
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

	$linkUrl = "https://office.visiyosindo.id/purchase_order/show/permintaan/purchase_order";
	if (isset($data['id']) && !empty($data['id'])) {
		$linkUrl = "https://office.visiyosindo.id/purchase_order/show/detail/purchase_order/" . $data['id'];
	} else if (isset($data['link']) && !empty($data['link'])) {
		$linkUrl = (strpos($data['link'], 'http') === 0) ? $data['link'] : 'https://office.visiyosindo.id/' . ltrim($data['link'], '/');
	}

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Penerimaan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan' .
		':%0A_Supplier Name    : ' . $data['suplier'] . '_' .
		'%0A_PPS Nomor    : ' . $data['kodePO'] . '_' .
		'%0A_Status Penerimaan    : ' . $data['noPo'] . '_' .
		'%0A%0ASegera periksa detail Permintaan PO Supplier di ' . $linkUrl .
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
	// Dinonaktifkan untuk menghemat kuota chat jika di-ACC semua
	return TRUE;
}




function waSuratAprovOnProg($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$linkUrl = getSuratDetailUrl($data);

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

	$linkUrl = getSuratDetailUrl($data);

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



function waSuratAprovAll($data)
{
	// Dinonaktifkan untuk menghemat kuota chat jika di-ACC semua
	return TRUE;
}


function waSuratAprovGABA($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);

	for ($i = 1; $i < 5; $i++) {
		$data['ttd_sebelum' . $i] = cekTtd($data['ttd_sebelum' . $i]);
	}

	$linkUrl = getSuratDetailUrl($data);

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
		'%0A%0ASegera periksa detail surat pada ' . $linkUrl . ' lalu General Affair mengarsipkannya' .
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

	$linkUrl = getSuratDetailUrl($data);

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
		'%0A%0ASegera periksa detail surat pada ' . $linkUrl .
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

	$linkUrl = getSuratDetailUrl($data);

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
		'%0A%0ASegera periksa detail surat pada ' . $linkUrl .
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

	$linkUrl = getSuratDetailUrl($data);

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
		'%0A%0ASegera periksa detail surat pada ' . $linkUrl . ' dan lakukan transfer(jika diperlukan)' .
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

	$linkUrl = getSuratDetailUrl($data);

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
		'%0A%0ASegera periksa detail surat pada ' . $linkUrl .
		'%0A%0ATerima Kasih';
	sendWa($dataWa);
	return true;
}

function waSuratReject($data)
{
	$data['perihal'] = cekPerihal($data['perihal']);
	$linkUrl = getSuratDetailUrl($data);

	$dataWa['devId']	= hostWa('2');
	$dataWa['penerima']	= $data['noPenerima'];
	$dataWa['pesan']	=   '*Notifikasi Penolakan ' . $data['namaSurat'] . '*' .
		'%0A%0ADear ' . $data['namaPengaju'] .
		',%0A%0A' . $data['namaSurat'] . ' yang anda ajukan:' .
		'%0A_Kode    : ' . $data['kodeSurat'] . '_' .
		$data['perihal'] .
		'%0A%0ATidak disetujui oleh: ' . $data['namaPenolak'] .
		'%0A%0ASegera hubungi ' . $data['namaPenolak'] . ' pada nomor *' . $data['noPenolak'] . '*' .
		'%0A%0ASegera periksa detail surat pada ' . $linkUrl .
		'%0A%0ATerima Kasih%0A_Sistem Office PT Visi Yosindo Medikal_';
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
		'%0A%0ATerima Kasih%0A_Sistem Office PT Visi Yosindo Medikal_';
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
		'%0A%0ATerima Kasih%0A_Sistem Office PT Visi Yosindo Medikal_';
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

	$fallbackKey = base64_decode('c2tfbGl2ZV8zQWtxTTd0S1JhZGVsR1I2d0JJbjZnU05FR3JJa0pLMGZCN09hSFRMaXpF');
	$apiKey = defined('CONVIA_API_KEY') ? CONVIA_API_KEY : (getenv('CONVIA_API_KEY') ?: (getenv('CONVIA_SECRET_KEY') ?: (isset($_ENV['CONVIA_API_KEY']) ? $_ENV['CONVIA_API_KEY'] : (isset($_ENV['CONVIA_SECRET_KEY']) ? $_ENV['CONVIA_SECRET_KEY'] : (isset($_SERVER['CONVIA_API_KEY']) ? $_SERVER['CONVIA_API_KEY'] : (isset($_SERVER['CONVIA_SECRET_KEY']) ? $_SERVER['CONVIA_SECRET_KEY'] : $fallbackKey))))));
	if (empty($apiKey) && function_exists('get_instance')) {
		$CI = &get_instance();
		if ($CI && isset($CI->config)) {
			$apiKey = $CI->config->item('convia_api_key') ?: ($CI->config->item('CONVIA_API_KEY') ?: $fallbackKey);
		}
	}

	if (empty($apiKey)) {
		$apiKey = $fallbackKey;
	}

	// Format nomor HP ke standar E.164 (62xxx)
	$penerima = preg_replace('/[^0-9]/', '', $dataSend['penerima']);
	if (substr($penerima, 0, 1) === '0') {
		$penerima = '62' . substr($penerima, 1);
	}

	$pesan = urldecode($dataSend['pesan']);
	if (!preg_match('/_Sistem Office PT Visi Yosindo Medikal_/i', $pesan)) {
		$pesan = rtrim($pesan) . "\n_Sistem Office PT Visi Yosindo Medikal_";
	}
	$apiUrl = defined('CONVIA_API_URL') ? CONVIA_API_URL : (getenv('CONVIA_API_URL') ?: 'https://api.convia.id/api/v1/public/messages/send');

	// Format Payload resmi Convia
	$payload = [
		'phone_number' => $penerima,
		'channel'      => 'whatsapp',
		'message_type' => 'text',
		'content'      => $pesan
	];

	// Dukungan Meta Message Template via Convia (hanya jika USE_CONVIA_TEMPLATE=true di .env)
	$useTemplate = (getenv('USE_CONVIA_TEMPLATE') === 'true' || getenv('USE_CONVIA_TEMPLATE') === '1');
	if ($useTemplate && isset($dataSend['template_name']) && !empty($dataSend['template_name'])) {
		$payload['message_type']  = 'template';
		$payload['template_name'] = $dataSend['template_name'];
		$payload['language']      = isset($dataSend['language']) ? $dataSend['language'] : 'id';
		if (isset($dataSend['parameters']) && is_array($dataSend['parameters'])) {
			$payload['parameters'] = $dataSend['parameters'];
		}
		unset($payload['content']); // Template tidak memerlukan field content plain
	}
	// Media attachment (image / document)
	else if (isset($dataSend['file']) && !empty($dataSend['file'])) {
		$ext = strtolower(pathinfo($dataSend['file'], PATHINFO_EXTENSION));
		if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
			$payload['message_type'] = 'image';
		} else {
			$payload['message_type'] = 'document';
		}
		$payload['media_url'] = $dataSend['file'];
	}

	// Opsi sender business number jika dispesifikasikan
	$phoneId = getenv('CONVIA_PHONE_NUMBER_ID') ?: (function_exists('get_instance') && isset(get_instance()->config) ? get_instance()->config->item('convia_phone_number_id') : '');
	if (!empty($phoneId)) {
		$payload['whatsapp_phone_number_id'] = $phoneId;
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
				'Authorization: Bearer ' . $apiKey
			],
			CURLOPT_TIMEOUT        => 10,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false
		]);

		$response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		// Jika penerima belum terdaftar di Convia (HTTP 404), otomatis daftarkan customer lalu kirim ulang
		if ($httpCode == 404) {
			$custCh = curl_init('https://api.convia.id/api/v1/public/customers');
			curl_setopt_array($custCh, [
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST           => true,
				CURLOPT_POSTFIELDS     => json_encode(['phone_number' => $penerima]),
				CURLOPT_HTTPHEADER     => [
					'Content-Type: application/json',
					'Authorization: Bearer ' . $apiKey
				],
				CURLOPT_TIMEOUT        => 5
			]);
			curl_exec($custCh);
			curl_close($custCh);

			// Kirim ulang pesan
			$curl2 = curl_init();
			curl_setopt_array($curl2, [
				CURLOPT_URL            => $apiUrl,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST           => true,
				CURLOPT_POSTFIELDS     => json_encode($payload),
				CURLOPT_HTTPHEADER     => [
					'Content-Type: application/json',
					'Authorization: Bearer ' . $apiKey
				],
				CURLOPT_TIMEOUT        => 10
			]);
			$response = curl_exec($curl2);
			$httpCode = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
			curl_close($curl2);
		}

		if ($response && ($httpCode >= 200 && $httpCode < 300)) {
			$json = json_decode($response, true);
			if (isset($json['success']) && $json['success'] === true) {
				return true;
			}
			if (isset($json['status']) && ($json['status'] == true || $json['status'] == 'success' || $json['status'] == 'sent')) {
				return true;
			}
		}

		if (function_exists('log_message')) {
			log_message('error', 'Convia Send WA Error (HTTP ' . $httpCode . '): ' . $response);
		}
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

	$fallbackKey = base64_decode('c2tfbGl2ZV8zQWtxTTd0S1JhZGVsR1I2d0JJbjZnU05FR3JJa0pLMGZCN09hSFRMaXpF');
	$apiKey = defined('CONVIA_API_KEY') ? CONVIA_API_KEY : (getenv('CONVIA_API_KEY') ?: (getenv('CONVIA_SECRET_KEY') ?: (isset($_ENV['CONVIA_API_KEY']) ? $_ENV['CONVIA_API_KEY'] : (isset($_ENV['CONVIA_SECRET_KEY']) ? $_ENV['CONVIA_SECRET_KEY'] : (isset($_SERVER['CONVIA_API_KEY']) ? $_SERVER['CONVIA_API_KEY'] : (isset($_SERVER['CONVIA_SECRET_KEY']) ? $_SERVER['CONVIA_SECRET_KEY'] : $fallbackKey))))));
	if (empty($apiKey) && function_exists('get_instance')) {
		$CI = &get_instance();
		if ($CI && isset($CI->config)) {
			$apiKey = $CI->config->item('convia_api_key') ?: ($CI->config->item('CONVIA_API_KEY') ?: $fallbackKey);
		}
	}

	if (empty($apiKey)) {
		$apiKey = $fallbackKey;
	}

	$pesan = urldecode($dataSend['pesan']);
	if (!preg_match('/_Sistem Office PT Visi Yosindo Medikal_/i', $pesan)) {
		$pesan = rtrim($pesan) . "\n_Sistem Office PT Visi Yosindo Medikal_";
	}
	$apiUrl = defined('CONVIA_API_URL') ? CONVIA_API_URL : (getenv('CONVIA_API_URL') ?: 'https://api.convia.id/api/v1/public/messages/send');

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
	if (!preg_match('/_Sistem Office PT Visi Yosindo Medikal_/i', $pesan)) {
		$pesan = rtrim($pesan) . "\n_Sistem Office PT Visi Yosindo Medikal_";
	}
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
	// 0. Hanya alihkan pengiriman ke nomor kantor (no_hp_kantor) jika penerima adalah Mulia
	// Untuk karyawan lainnya, notifikasi selalu dikirim ke nomor HP pribadi mereka
	if (!empty($dataSend['penerima'])) {
		$cleanPenerima = preg_replace('/[^0-9]/', '', (string)$dataSend['penerima']);
		if (substr($cleanPenerima, 0, 2) === '62') {
			$localPenerima = '0' . substr($cleanPenerima, 2);
		} else {
			$localPenerima = $cleanPenerima;
		}

		if (function_exists('get_instance')) {
			$ci = &get_instance();
			if (isset($ci->db)) {
				$checkKantor = $ci->db->select('no_hp_kantor')
					->from('pengguna')
					->group_start()
						->where('no_hp', $localPenerima)
						->or_where('no_hp', $cleanPenerima)
					->group_end()
					->group_start()
						->where('pengguna_id', 777)
						->or_like('nama', 'Mulia', 'both')
					->group_end()
					->where('no_hp_kantor IS NOT NULL')
					->where('no_hp_kantor !=', '')
					->where('status', 1)
					->limit(1)
					->get()
					->row();

				if (!empty($checkKantor) && !empty($checkKantor->no_hp_kantor)) {
					$dataSend['penerima'] = $checkKantor->no_hp_kantor;
				}
			}
		}
	}

	// Pastikan link dalam pesan selalu mengarah ke domain publik yang bisa dibuka dari HP
	if (isset($dataSend['pesan'])) {
		$dataSend['pesan'] = preg_replace(
			'#https?://(?:127\.0\.0\.1|localhost)(?::\d+)?/#i',
			'https://office.visiyosindo.id/',
			$dataSend['pesan']
		);

		// Pastikan footer resmi selalu ada di setiap pesan
		$pesanDecoded = urldecode($dataSend['pesan']);
		if (!preg_match('/_Sistem Office PT Visi Yosindo Medikal_/i', $pesanDecoded)) {
			if (strpos($dataSend['pesan'], '%0A') !== false || strpos($dataSend['pesan'], '%0a') !== false) {
				$dataSend['pesan'] .= '%0A_Sistem Office PT Visi Yosindo Medikal_';
			} else {
				$dataSend['pesan'] .= "\n_Sistem Office PT Visi Yosindo Medikal_";
			}
		}
	}

	// Deduplikasi pengiriman pesan yang identik dalam satu request ke nomor yang sama
	static $sent_messages = [];
	$penerimaClean = preg_replace('/[^0-9]/', '', (string)$dataSend['penerima']);
	$msgHash = md5($penerimaClean . '_' . trim(strip_tags(urldecode($dataSend['pesan'] ?? ''))));
	if (isset($sent_messages[$msgHash])) {
		return true; // Lewati karena pesan yang sama persis sudah dikirim ke nomor ini dalam satu proses
	}
	$sent_messages[$msgHash] = true;

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

	$pesanText = urldecode($dataSend['pesan']);
	if (!preg_match('/_Sistem Office PT Visi Yosindo Medikal_/i', $pesanText)) {
		$pesanText = rtrim($pesanText) . "\n_Sistem Office PT Visi Yosindo Medikal_";
	}
	$pesan = urlencode($pesanText);
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
	if (isset($dataSend['pesan'])) {
		$dataSend['pesan'] = preg_replace(
			'#https?://(?:127\.0\.0\.1|localhost)(?::\d+)?/#i',
			'https://office.visiyosindo.id/',
			$dataSend['pesan']
		);

		// Pastikan footer resmi selalu ada di setiap pesan group
		$pesanDecoded = urldecode($dataSend['pesan']);
		if (!preg_match('/_Sistem Office PT Visi Yosindo Medikal_/i', $pesanDecoded)) {
			if (strpos($dataSend['pesan'], '%0A') !== false || strpos($dataSend['pesan'], '%0a') !== false) {
				$dataSend['pesan'] .= '%0A_Sistem Office PT Visi Yosindo Medikal_';
			} else {
				$dataSend['pesan'] .= "\n_Sistem Office PT Visi Yosindo Medikal_";
			}
		}
	}

	// 1. Coba Convia API Group
	if (sendWaConviaGroup($dataSend)) {
		return true;
	}

	// 2. Coba Fonnte API Group
	if (sendWaFonnteGroup($dataSend)) {
		return true;
	}

	// 3. Fallback Whacenter Group
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
		$kodeSurat = !empty($data['kodeSurat']) ? $data['kodeSurat'] : (!empty($data['kode']) ? $data['kode'] : '-');
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_pengajuan';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaApprover'],
			$data['jenisPengajuan'],
			$data['namaPemohon'],
			$kodeSurat,
			$data['linkDetail']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaApprover'] . "*! ✨ Ada pengajuan *" . $data['jenisPengajuan'] . "* baru dari *" . $data['namaPemohon'] . "* (Kode: " . $kodeSurat . ") nih." .
			"%0A%0AYuk bantu periksa dan berikan persetujuanmu melalui tautan berikut:" .
			"%0A🔗 " . $data['linkDetail'] .
			"%0A%0ASemangat beraktivitas dan semoga harimu menyenangkan! 🚀" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";

		return sendWa($dataWa);
	}
}

if (!function_exists('waPengajuanApproved')) {
	function waPengajuanApproved($data)
	{
		// Dinonaktifkan untuk menghemat kuota chat jika di-ACC semua
		return TRUE;
	}
}

if (!function_exists('waPengajuanRejected')) {
	function waPengajuanRejected($data)
	{
		$kodeSurat = !empty($data['kodeSurat']) ? $data['kodeSurat'] : (!empty($data['kode']) ? $data['kode'] : '-');
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_hasil_approval_v1';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaPemohon'],
			$data['jenisPengajuan'],
			$kodeSurat,
			'DITOLAK',
			$data['namaApprover'],
			$data['linkDetail']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaPemohon'] . "*! Kabar terbaru nih, pengajuan *" . $data['jenisPengajuan'] . "* kamu (Kode: " . $kodeSurat . ") telah *DITOLAK* oleh *" . $data['namaApprover'] . "*." .
			"%0A%0AKamu bisa cek detail lengkapnya melalui tautan berikut ya:" .
			"%0A🔗 " . $data['linkDetail'] .
			"%0A%0ATerima kasih dan tetap semangat selalu! 😊" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";

		return sendWa($dataWa);
	}
}

// ==============================================================================
// TEMPLATE NOTIFIKASI WA - MODUL KEPEGAWAIAN & HRD (via Convia)
// Semua fungsi menggunakan sendWa() → otomatis kirim via Convia API
// ==============================================================================

// ------------------------------------------------------------------------------
// 1. SLIP GAJI
// Trigger  : Tutup buku bulanan / kirim satuan oleh HRD
// Penerima : Karyawan (personal)
// ------------------------------------------------------------------------------
if (!function_exists('waSlipGajiBaru')) {
	function waSlipGajiBaru($data)
	{
		// $data: noPenerima, namaKaryawan, periodeBulan, linkSlip
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_slip_gaji';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaKaryawan'],
			$data['periodeBulan'],
			$data['linkSlip']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaKaryawan'] . "*! 🥳 Payday is here! Slip gaji kamu untuk periode *" . $data['periodeBulan'] . "* sudah siap diunduh nih." .
			"%0A%0AYuk cek dan unduh slip gaji kamu melalui tautan berikut:" .
			"%0A🔗 " . $data['linkSlip'] .
			"%0A%0ATerima kasih banyak atas kerja keras dan dedikasimu yang luar biasa! Tetap semangat! 💪✨" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

// ------------------------------------------------------------------------------
// 2. CUTI TAHUNAN
// ------------------------------------------------------------------------------
if (!function_exists('waCutiPengajuan')) {
	function waCutiPengajuan($data)
	{
		// $data: noPenerima, namaApprover, namaPengaju, kodeSurat, tglMulai, tglAkhir, totalHari, alasan, linkApproval
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_pengajuan';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaApprover'],
			'Cuti Tahunan',
			$data['namaPengaju'],
			$data['kodeSurat'],
			$data['linkApproval']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaApprover'] . "*! ✨ Ada pengajuan *Cuti Tahunan* baru dari *" . $data['namaPengaju'] . "* (Kode: " . $data['kodeSurat'] . ") nih." .
			"%0A%0AYuk bantu periksa dan berikan persetujuanmu melalui tautan berikut:" .
			"%0A🔗 " . $data['linkApproval'] .
			"%0A%0ASemangat beraktivitas dan semoga harimu menyenangkan! 🚀" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

if (!function_exists('waCutiDisetujui')) {
	function waCutiDisetujui($data)
	{
		// Dinonaktifkan untuk menghemat kuota chat jika di-ACC semua
		return TRUE;
	}
}

if (!function_exists('waCutiDitolak')) {
	function waCutiDitolak($data)
	{
		// $data: noPenerima, namaKaryawan, kodeSurat, tglMulai, tglAkhir, namaApprover, alasan, linkDetail
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_hasil_approval_v1';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaKaryawan'],
			'Cuti Tahunan',
			$data['kodeSurat'],
			'DITOLAK',
			$data['namaApprover'],
			$data['linkDetail']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaKaryawan'] . "*! Kabar terbaru nih, pengajuan *Cuti Tahunan* kamu (Kode: " . $data['kodeSurat'] . ") telah *DITOLAK* oleh *" . $data['namaApprover'] . "*." .
			"%0A%0AKamu bisa cek detail lengkapnya melalui tautan berikut ya:" .
			"%0A🔗 " . $data['linkDetail'] .
			"%0A%0ATerima kasih dan tetap semangat selalu! 😊" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

// ------------------------------------------------------------------------------
// 3. IZIN PADA JAM KERJA (SIJK)
// ------------------------------------------------------------------------------
if (!function_exists('waIzinJamKerjaPengajuan')) {
	function waIzinJamKerjaPengajuan($data)
	{
		// $data: noPenerima, namaApprover, namaPengaju, kodeSurat, tglIzin, jamMulai, jamSelesai, keperluan, linkApproval
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_pengajuan';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaApprover'],
			'Izin Pada Jam Kerja',
			$data['namaPengaju'],
			$data['kodeSurat'],
			$data['linkApproval']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaApprover'] . "*! ✨ Ada pengajuan *Izin Pada Jam Kerja* baru dari *" . $data['namaPengaju'] . "* (Kode: " . $data['kodeSurat'] . ") nih." .
			"%0A%0AYuk bantu periksa dan berikan persetujuanmu melalui tautan berikut:" .
			"%0A🔗 " . $data['linkApproval'] .
			"%0A%0ASemangat beraktivitas dan semoga harimu menyenangkan! 🚀" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

if (!function_exists('waIzinJamKerjaHasil')) {
	function waIzinJamKerjaHasil($data)
	{
		// Dinonaktifkan jika disetujui demi menghemat kuota chat
		if (isset($data['status']) && $data['status'] === 'DISETUJUI') {
			return TRUE;
		}

		// $data: noPenerima, namaKaryawan, kodeSurat, status (DISETUJUI/DITOLAK), namaApprover, alasan, linkDetail
		$statusText = ($data['status'] === 'DISETUJUI') ? 'DISETUJUI' : 'DITOLAK';
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_hasil_approval_v1';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaKaryawan'],
			'Izin Pada Jam Kerja',
			$data['kodeSurat'],
			$statusText,
			$data['namaApprover'],
			$data['linkDetail']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaKaryawan'] . "*! 🎉 Kabar terbaru nih, pengajuan *Izin Pada Jam Kerja* kamu (Kode: " . $data['kodeSurat'] . ") telah *" . $statusText . "* oleh *" . $data['namaApprover'] . "*." .
			"%0A%0AKamu bisa cek detail lengkapnya melalui tautan berikut ya:" .
			"%0A🔗 " . $data['linkDetail'] .
			"%0A%0ATerima kasih dan tetap semangat selalu! 😊" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

// ------------------------------------------------------------------------------
// 4. IZIN MENINGGALKAN PEKERJAAN (SIMP)
// ------------------------------------------------------------------------------
if (!function_exists('waIzinMeninggalkanPengajuan')) {
	function waIzinMeninggalkanPengajuan($data)
	{
		// $data: noPenerima, namaApprover, namaPengaju, kodeSurat, tglMulai, tglAkhir, totalHari, alasan, linkApproval
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_pengajuan';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaApprover'],
			'Izin Meninggalkan Pekerjaan',
			$data['namaPengaju'],
			$data['kodeSurat'],
			$data['linkApproval']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaApprover'] . "*! ✨ Ada pengajuan *Izin Meninggalkan Pekerjaan* baru dari *" . $data['namaPengaju'] . "* (Kode: " . $data['kodeSurat'] . ") nih." .
			"%0A%0AYuk bantu periksa dan berikan persetujuanmu melalui tautan berikut:" .
			"%0A🔗 " . $data['linkApproval'] .
			"%0A%0ASemangat beraktivitas dan semoga harimu menyenangkan! 🚀" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

if (!function_exists('waIzinMeninggalkanHasil')) {
	function waIzinMeninggalkanHasil($data)
	{
		// Dinonaktifkan jika disetujui demi menghemat kuota chat
		if (isset($data['status']) && $data['status'] === 'DISETUJUI') {
			return TRUE;
		}

		// $data: noPenerima, namaKaryawan, kodeSurat, status, namaApprover, alasan, linkDetail
		$statusText = ($data['status'] === 'DISETUJUI') ? 'DISETUJUI' : 'DITOLAK';
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_hasil_approval_v1';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaKaryawan'],
			'Izin Meninggalkan Pekerjaan',
			$data['kodeSurat'],
			$statusText,
			$data['namaApprover'],
			$data['linkDetail']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaKaryawan'] . "*! 🎉 Kabar terbaru nih, pengajuan *Izin Meninggalkan Pekerjaan* kamu (Kode: " . $data['kodeSurat'] . ") telah *" . $statusText . "* oleh *" . $data['namaApprover'] . "*." .
			"%0A%0AKamu bisa cek detail lengkapnya melalui tautan berikut ya:" .
			"%0A🔗 " . $data['linkDetail'] .
			"%0A%0ATerima kasih dan tetap semangat selalu! 😊" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

// ------------------------------------------------------------------------------
// 5. WFA (WORK FROM ANYWHERE)
// ------------------------------------------------------------------------------
if (!function_exists('waWfaPengajuan')) {
	function waWfaPengajuan($data)
	{
		// $data: noPenerima, namaApprover, namaPengaju, tglWfa, lokasiWfa, alasan, linkApproval
		$kodeSurat = !empty($data['kodeSurat']) ? $data['kodeSurat'] : 'WFA';
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_pengajuan';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaApprover'],
			'Work From Anywhere (WFA)',
			$data['namaPengaju'],
			$kodeSurat,
			$data['linkApproval']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaApprover'] . "*! ✨ Ada pengajuan *Work From Anywhere (WFA)* baru dari *" . $data['namaPengaju'] . "* (Kode: " . $kodeSurat . ") nih." .
			"%0A%0AYuk bantu periksa dan berikan persetujuanmu melalui tautan berikut:" .
			"%0A🔗 " . $data['linkApproval'] .
			"%0A%0ASemangat beraktivitas dan semoga harimu menyenangkan! 🚀" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

if (!function_exists('waWfaHasil')) {
	function waWfaHasil($data)
	{
		// Dinonaktifkan jika disetujui demi menghemat kuota chat
		if (isset($data['status']) && $data['status'] === 'DISETUJUI') {
			return TRUE;
		}

		// $data: noPenerima, namaKaryawan, tglWfa, status, namaApprover, alasan, linkDetail
		$statusText = ($data['status'] === 'DISETUJUI') ? 'DISETUJUI' : 'DITOLAK';
		$kodeSurat = !empty($data['kodeSurat']) ? $data['kodeSurat'] : 'WFA';
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_hasil_approval_v1';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaKaryawan'],
			'Work From Anywhere (WFA)',
			$kodeSurat,
			$statusText,
			$data['namaApprover'],
			$data['linkDetail']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaKaryawan'] . "*! 🎉 Kabar terbaru nih, pengajuan *Work From Anywhere (WFA)* kamu (Kode: " . $kodeSurat . ") telah *" . $statusText . "* oleh *" . $data['namaApprover'] . "*." .
			"%0A%0AKamu bisa cek detail lengkapnya melalui tautan berikut ya:" .
			"%0A🔗 " . $data['linkDetail'] .
			"%0A%0ATerima kasih dan tetap semangat selalu! 😊" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

// ------------------------------------------------------------------------------
// 6. SURAT PERINGATAN (SP)
// Trigger  : SP diterbitkan & ditandatangani
// Penerima : Karyawan bersangkutan (personal)
// ------------------------------------------------------------------------------
if (!function_exists('waSuratPeringatan')) {
	function waSuratPeringatan($data)
	{
		// $data: noPenerima, namaKaryawan, jenisSP, kodeSurat, perihal, linkDetail
		// jenisSP: 'SP 1' / 'SP 2' / 'SP 3' / 'Skorsing'
		$dataWa['penerima'] = $data['noPenerima'];
		$dataWa['pesan']    =
			"*⚠️ Notifikasi " . $data['jenisSP'] . "*" .
			"%0A%0AYth. *" . $data['namaKaryawan'] . "*," .
			"%0A%0AKami menginformasikan bahwa Anda menerima *" . $data['jenisSP'] . "* dari perusahaan." .
			"%0A%0A• *Kode Surat* : " . $data['kodeSurat'] .
			"%0A• *Perihal*       : " . $data['perihal'] .
			"%0A%0AMohon segera periksa dan tindak lanjuti:" .
			"%0A🔗 " . $data['linkDetail'] .
			"%0A%0AJika ada pertanyaan, hubungi HRD/GA." .
			"%0A%0ATerima Kasih.%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

// ------------------------------------------------------------------------------
// 7. SURAT TUGAS
// Trigger  : Surat tugas diterbitkan/disetujui
// Penerima : Karyawan yang ditugaskan
// ------------------------------------------------------------------------------
if (!function_exists('waSuratTugas')) {
	function waSuratTugas($data)
	{
		// $data: noPenerima, namaKaryawan, kodeSurat, perihal, tglTugas, lokasi, linkDetail
		$dataWa['penerima'] = $data['noPenerima'];
		$dataWa['pesan']    =
			"*📌 Notifikasi Surat Tugas*" .
			"%0A%0AYth. *" . $data['namaKaryawan'] . "*," .
			"%0A%0AAnda mendapatkan Surat Tugas dari perusahaan." .
			"%0A%0A• *Kode Surat* : " . $data['kodeSurat'] .
			"%0A• *Perihal*       : " . $data['perihal'] .
			"%0A• *Tanggal*       : " . $data['tglTugas'] .
			"%0A• *Lokasi*         : " . $data['lokasi'] .
			"%0A%0ADetail surat tugas:%0A🔗 " . $data['linkDetail'] .
			"%0A%0ATerima Kasih.%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

// ------------------------------------------------------------------------------
// 8. PAKLARING (Surat Keterangan Pengalaman Kerja)
// Trigger  : Paklaring selesai diterbitkan
// Penerima : Karyawan bersangkutan
// ------------------------------------------------------------------------------
if (!function_exists('waPaklaring')) {
	function waPaklaring($data)
	{
		// $data: noPenerima, namaKaryawan, kodeSurat, linkDownload
		$dataWa['penerima'] = $data['noPenerima'];
		$dataWa['pesan']    =
			"*📄 Surat Keterangan Kerja (Paklaring) Siap*" .
			"%0A%0AYth. *" . $data['namaKaryawan'] . "*," .
			"%0A%0ASurat Keterangan Pengalaman Kerja Anda telah selesai diterbitkan." .
			"%0A%0A• *Kode Surat* : " . $data['kodeSurat'] .
			"%0A%0ASilakan unduh melalui:%0A🔗 " . $data['linkDownload'] .
			"%0A%0AJika ada pertanyaan, hubungi HRD." .
			"%0A%0ATerima Kasih.%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

// ------------------------------------------------------------------------------
// 9. EVALUASI KINERJA
// 9a. Reminder ke Atasan/Penilai untuk mengisi evaluasi
// 9b. Notifikasi hasil ke Karyawan
// ------------------------------------------------------------------------------
if (!function_exists('waEvaluasiReminder')) {
	function waEvaluasiReminder($data)
	{
		// $data: noPenerima, namaApprover, namaPegawai, periode, linkEvaluasi
		$dataWa['penerima'] = $data['noPenerima'];
		$dataWa['pesan']    =
			"*📊 Reminder: Evaluasi Kinerja Karyawan*" .
			"%0A%0AYth. *" . $data['namaApprover'] . "*," .
			"%0A%0AMohon segera lakukan penilaian kinerja untuk:" .
			"%0A%0A• *Karyawan* : " . $data['namaPegawai'] .
			"%0A• *Periode*    : " . $data['periode'] .
			"%0A%0ASilakan isi penilaian:%0A🔗 " . $data['linkEvaluasi'] .
			"%0A%0ATerima Kasih.%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

if (!function_exists('waEvaluasiSelesai')) {
	function waEvaluasiSelesai($data)
	{
		// $data: noPenerima, namaKaryawan, periode, linkHasil
		$dataWa['penerima'] = $data['noPenerima'];
		$dataWa['pesan']    =
			"*📊 Hasil Evaluasi Kinerja Telah Tersedia*" .
			"%0A%0AYth. *" . $data['namaKaryawan'] . "*," .
			"%0A%0AHasil evaluasi kinerja Anda periode *" . $data['periode'] . "* telah selesai dinilai." .
			"%0A%0ASilakan lihat hasilnya:%0A🔗 " . $data['linkHasil'] .
			"%0A%0AJika ada pertanyaan, hubungi HRD." .
			"%0A%0ATerima Kasih.%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

// ------------------------------------------------------------------------------
// 10. TRAINING & PELATIHAN KARYAWAN
// 10a. Undangan/penugasan training ke peserta
// 10b. Reminder upload sertifikat
// ------------------------------------------------------------------------------
if (!function_exists('waTrainingUndangan')) {
	function waTrainingUndangan($data)
	{
		// $data: noPenerima, namaKaryawan, namaTraining, tanggal, lokasi, penyelenggara, linkDetail
		$dataWa['penerima'] = $data['noPenerima'];
		$dataWa['pesan']    =
			"*🎓 Undangan Training / Pelatihan*" .
			"%0A%0AYth. *" . $data['namaKaryawan'] . "*," .
			"%0A%0AAnda ditugaskan mengikuti training:" .
			"%0A%0A• *Nama Training*    : " . $data['namaTraining'] .
			"%0A• *Tanggal*               : " . $data['tanggal'] .
			"%0A• *Lokasi*                 : " . $data['lokasi'] .
			"%0A• *Penyelenggara*    : " . $data['penyelenggara'] .
			"%0A%0ADetail training:%0A🔗 " . $data['linkDetail'] .
			"%0A%0AMohon hadir tepat waktu." .
			"%0A%0ATerima Kasih.%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

if (!function_exists('waTrainingReminderSertifikat')) {
	function waTrainingReminderSertifikat($data)
	{
		// $data: noPenerima, namaKaryawan, namaTraining, deadline, linkUpload
		$dataWa['penerima'] = $data['noPenerima'];
		$dataWa['pesan']    =
			"*📎 Reminder: Upload Sertifikat Training*" .
			"%0A%0AYth. *" . $data['namaKaryawan'] . "*," .
			"%0A%0AMohon segera upload sertifikat untuk training:" .
			"%0A%0A• *Nama Training* : " . $data['namaTraining'] .
			"%0A• *Deadline*           : " . $data['deadline'] .
			"%0A%0ASilakan upload:%0A🔗 " . $data['linkUpload'] .
			"%0A%0ATerima Kasih.%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

// ------------------------------------------------------------------------------
// 11. BERITA ACARA
// 11a. Notifikasi pengajuan ke approver
// 11b. Notifikasi hasil ke pengaju
// ------------------------------------------------------------------------------
if (!function_exists('waBeritaAcaraPengajuan')) {
	function waBeritaAcaraPengajuan($data)
	{
		// $data: noPenerima, namaApprover, namaPengaju, kodeSurat, perihal, tanggal, linkApproval
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_pengajuan';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaApprover'],
			'Berita Acara',
			$data['namaPengaju'],
			$data['kodeSurat'],
			$data['linkApproval']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaApprover'] . "*! ✨ Ada pengajuan *Berita Acara* baru dari *" . $data['namaPengaju'] . "* (Kode: " . $data['kodeSurat'] . ") nih." .
			"%0A%0AYuk bantu periksa dan berikan persetujuanmu melalui tautan berikut:" .
			"%0A🔗 " . $data['linkApproval'] .
			"%0A%0ASemangat beraktivitas dan semoga harimu menyenangkan! 🚀" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

if (!function_exists('waBeritaAcaraHasil')) {
	function waBeritaAcaraHasil($data)
	{
		// Dinonaktifkan jika disetujui demi menghemat kuota chat
		if (isset($data['status']) && $data['status'] === 'DISETUJUI') {
			return TRUE;
		}

		// $data: noPenerima, namaPengaju, kodeSurat, perihal, status, namaApprover, alasan, linkDetail
		$statusText = ($data['status'] === 'DISETUJUI') ? 'DISETUJUI' : 'DITOLAK';
		$dataWa['penerima']      = $data['noPenerima'];
		$dataWa['template_name'] = 'notifikasi_hasil_approval_v1';
		$dataWa['language']      = 'id';
		$dataWa['parameters']    = [
			$data['namaPengaju'],
			'Berita Acara',
			$data['kodeSurat'],
			$statusText,
			$data['namaApprover'],
			$data['linkDetail']
		];
		$dataWa['pesan']         =
			"Halo *" . $data['namaPengaju'] . "*! 🎉 Kabar terbaru nih, pengajuan *Berita Acara* kamu (Kode: " . $data['kodeSurat'] . ") telah *" . $statusText . "* oleh *" . $data['namaApprover'] . "*." .
			"%0A%0AKamu bisa cek detail lengkapnya melalui tautan berikut ya:" .
			"%0A🔗 " . $data['linkDetail'] .
			"%0A%0ATerima kasih dan tetap semangat selalu! 😊" .
			"%0A_Sistem Office PT Visi Yosindo Medikal_";
		return sendWa($dataWa);
	}
}

// ------------------------------------------------------------------------------
// 12. UCAPAN OTOMATIS (JUMAT SORE & SENIN PAGI)
// ------------------------------------------------------------------------------
if (!function_exists('waSalamWeekend')) {
	function waSalamWeekend($data)
	{
		// $data: noPenerima, namaKaryawan
		$dataWa['penerima'] = $data['noPenerima'];
		
		// Jika template_name dikirimkan (dari Convia Template)
		if (!empty($data['template_name'])) {
			$dataWa['template_name'] = $data['template_name'];
			$dataWa['language']      = 'id';
			$dataWa['parameters']    = [$data['namaKaryawan']];
		} else {
			$dataWa['pesan'] =
				"Halo *" . $data['namaKaryawan'] . "*! 🎉 Selamat berakhir pekan! " .
				"%0A%0ATerima kasih banyak atas kerja keras dan semangatmu sepanjang minggu ini. Waktunya istirahat, recharge energi, dan nikmati waktu bersama keluarga tercinta ya." .
				"%0A%0AHappy Weekend! Sampai jumpa di hari Senin! 🏖️✨" .
				"%0A_Sistem Office PT Visi Yosindo Medikal_";
		}
		return sendWa($dataWa);
	}
}

if (!function_exists('waSalamSenin')) {
	function waSalamSenin($data)
	{
		// $data: noPenerima, namaKaryawan
		$dataWa['penerima'] = $data['noPenerima'];
		
		if (!empty($data['template_name'])) {
			$dataWa['template_name'] = $data['template_name'];
			$dataWa['language']      = 'id';
			$dataWa['parameters']    = [$data['namaKaryawan']];
		} else {
			$dataWa['pesan'] =
				"Selamat pagi *" . $data['namaKaryawan'] . "*! ☀️ Semangat hari Senin!" .
				"%0A%0ASemoga akhir pekan kemarin menyenangkan dan energimu sudah terisi penuh kembali. Yuk kita mulai minggu ini dengan senyuman dan optimisme baru!" .
				"%0A%0AHave a productive and wonderful week ahead! 🚀💪" .
				"%0A_Sistem Office PT Visi Yosindo Medikal_";
		}
		return sendWa($dataWa);
	}
}

// ==============================================================================
// END OF KEPEGAWAIAN & HRD TEMPLATES
// ==============================================================================
