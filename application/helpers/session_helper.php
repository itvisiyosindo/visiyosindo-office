<?php
function grantAccessForPerusahaan()
{
	$CI = get_instance();
	$perusahaan = 0;
	return $perusahaan;
}
/**
 * Grant Access Menu By Kelas Anggota
 */
function grantAccessForKelas($list)
{
	$CI = get_instance();
	if (!in_array($CI->session->userdata('kelas'), $list) && $CI->session->userdata('login_type') == 'Anggota')
		redirect(base_url());
}

/**
 * Grant Acces & Login Type Check
 */
function grantAccessFor($list)
{
	$CI = get_instance();
	if ($list == 'all') {
		$list = array('Administrator', 'Hrd', 'Karyawan', 'Ga');
	}
	$pass = 0;
	foreach ($list as $row) {
		if ($CI->session->userdata('login_type') == $row) {
			$pass++;
		}
	}
	if (!$pass) {
		if (!is_cli()) {
			redirect(base_url());
		}
	}
}

//function isPenggunaIdBy($penggunaid){
//  $CI = get_instance();
//return $CI->session->userdata('pengguna_id')=='29' ? TRUE : FALSE;
//}

function isAdmin()
{
	$CI = get_instance();
	return $CI->session->userdata('login_type') == 'Administrator' ? TRUE : FALSE;
}

function isHrd()
{
	$CI = get_instance();
	return $CI->session->userdata('login_type') == 'Hrd' ? TRUE : FALSE;
}

function isGa()
{
	$CI = get_instance();
	return $CI->session->userdata('login_type') == 'Ga' ? TRUE : FALSE;
	//return $CI->session->userdata('id_divisi') == '8' ? TRUE : FALSE;
}

function isGudangUser()
{
	$CI = get_instance();
	if ($CI->session->userdata('login_type') == 'Administrator') {
		return TRUE;
	}
	$pengguna_id = sessPenggunaId();
	if (empty($pengguna_id)) {
		return FALSE;
	}
	
	static $is_gudang = null;
	if ($is_gudang !== null) {
		return $is_gudang;
	}
	
	$user = $CI->db->select('jabatan')->where('pengguna_id', $pengguna_id)->get('pengguna')->row();
	if ($user) {
		$jabatan = strtolower($user->jabatan);
		if (strpos($jabatan, 'warehouse') !== false || 
			strpos($jabatan, 'gudang') !== false || 
			strpos($jabatan, 'logistik') !== false || 
			strpos($jabatan, 'pjt') !== false) {
			$is_gudang = TRUE;
			return TRUE;
		}
	}
	
	$is_gudang = FALSE;
	return FALSE;
}

function isKaryawan()
{
	$CI = get_instance();
	return $CI->session->userdata('login_type') == 'Karyawan' ? TRUE : FALSE;
}

function isAccountingUser()
{
	$CI = get_instance();
	if ($CI->session->userdata('login_type') == 'Administrator') {
		return TRUE;
	}
	$allowed_ids = [54, 110, 79, 106, 107, 714, 764, 777, 87, 23, 64];
	return in_array(sessPenggunaId(), $allowed_ids) ? TRUE : FALSE;
}



function sessPenggunaId()
{
	$CI = get_instance();
	return decrypt($CI->session->userdata('pengguna_id'));
}

function sessNama()
{
	$CI = get_instance();
	return $CI->session->userdata('nama');
}

function sessTelepon()
{
	$CI = get_instance();
	return $CI->session->userdata('no_hp');
}

function sessEmail()
{
	$CI = get_instance();
	return $CI->session->userdata('email');
}

function sessAnggotaId()
{
	$CI = get_instance();
	return decrypt($CI->session->userdata('anggota_id'));
}

function isEksetkutif()
{
	$CI = get_instance();
	return $CI->session->userdata('hirarki') == '1' ? TRUE : FALSE;
}

// Correctly spelled alias for executive-level check
function isEksekutif()
{
	$CI = get_instance();
	return $CI->session->userdata('hirarki') == '1' ? TRUE : FALSE;
}

function isKepalaDivisi()
{
	$CI = get_instance();
	return $CI->session->userdata('hirarki') == '2' ? TRUE : FALSE;
}

function isAdminDivisi()
{
	$CI = get_instance();
	return $CI->session->userdata('hirarki') == '3' ? TRUE : FALSE;
}

function isPic()
{
	$CI = get_instance();
	return $CI->session->userdata('hirarki') == '4' ? TRUE : FALSE;
}

function isAdminInventory()
{
	$CI = get_instance();
	return $CI->session->userdata('login_type_inventory') == 'Administrator' ? TRUE : FALSE;
}


function isMarketing()
{
	$CI = get_instance();
	return $CI->session->userdata('login_type_inventory') == 'Marketing' ? TRUE : FALSE;
}

function isTeamMarketing()
{
	$CI = get_instance();
	return $CI->session->userdata('id_divisi') == 3 ? TRUE : FALSE;
}

function isCRO()
{
	$CI = get_instance();
	return $CI->session->userdata('id_divisi') == 9 ? TRUE : FALSE;
}

function isLegalOfficer()
{
	$CI = get_instance();
	return $CI->session->userdata('id_divisi') == 8 ? TRUE : FALSE;
}

function isEngineerTeam()
{
	$CI = get_instance();
	return $CI->session->userdata('id_divisi') == 7 ? TRUE : FALSE;
}

function isStafAdmin()
{
	$CI = get_instance();
	return $CI->session->userdata('login_type_inventory') == 'StaffAdmin' ? TRUE : FALSE;
}

function isUmum()
{
	$CI = get_instance();
	return $CI->session->userdata('login_type_inventory') == 'Umum' ? TRUE : FALSE;
}



function totalnotifikasimasuk()
{
	$CI = get_instance();
	$pengguna_id = decrypt($CI->session->userdata('pengguna_id'));
	if ($pengguna_id == 1) {
		return $CI->md_notifikasi->countMasukAdmin();
	} else {
		return $CI->md_notifikasi->countMasuk($pengguna_id);
	}
}

function notifikasimasuk()
{
	$CI = get_instance();
	$pengguna_id = decrypt($CI->session->userdata('pengguna_id'));
	if ($pengguna_id == 1) {
		return $CI->md_notifikasi->notifikasimasukadmin();
	} else {
		return $CI->md_notifikasi->notifikasimasuk($pengguna_id);
	}
}

function dokumenproduct()
{
	$CI = get_instance();
	return $CI->md_dokumen->getKategoriProductAktif();
}

function dokumenvisilab()
{
	$CI = get_instance();
	return $CI->md_dokumen->getKategoriVisilabAktif();
}

/**
 * Get current company/perusahaan ID
 * Default: 1 (PT. Visi Yosindo Medikal)
 */
function perusahaan()
{
	return 1;
}
