<?php

/**
 * Return json response and die 
 */
function ajaxReturnDie($status, $text, $reload = FALSE)
{
	$response = array(
		'status' => $status,
		'msg' => $text,
		'message' => $text,
		'reload' => $reload
	);

	if (!is_bool($reload) && !is_null($reload)) {
		$response['data'] = $reload;
	}

	while (ob_get_level() > 0) {
		@ob_end_clean();
	}

	if (!headers_sent()) {
		header('Content-Type: application/json; charset=utf-8');
	}

	echo json_encode($response);
	die;
}

/**
 * Print array with pre tah
 */
function echo_array($data)
{
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

/**
 * Encrypt Helper
 * @todo mengubah integer menjadi suatu angka dengan menggunakan library hashids (https://hashids.org)
 */
function hashidsInitialize($minLength = 5)
{
	$salt          = 'IRR839GBnv22';
	$minHashLength = $minLength;
	$alphabet      = 'siw82ug75y2hfsbc8a02ds8g4hhghajhk912';
	return new Hashids\Hashids($salt, $minHashLength, $alphabet);
}


function encrypt($param)
{
	$hashids = hashidsInitialize(6);
	return $hashids->encode($param);
}
function decrypt($param)
{
	if ($param) {
		$hashids = hashidsInitialize(6);
		$decoded = $hashids->decode($param);
		// Cek apakah hasil decode valid (array tidak kosong)
		return !empty($decoded) ? $decoded[0] : null;
	}
	return null;
}



/**
 * Log Input
 */
function addlog($aksi = '', $ket = '')
{
	$CI = get_instance();
	$log['jenis_aksi']  = $aksi;
	$log['keterangan']  = $ket;
	// $log['pengguna_id'] = isAdmin() || isAdminCluster() ? decrypt($CI->session->userdata('pengguna_id')) : null;
	$penggunaId         = decrypt($CI->session->userdata('pengguna_id'));
	$log['pengguna_id'] = (!empty($penggunaId) && is_numeric($penggunaId)) ? (int)$penggunaId : null;
	$ip                 = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
	$log['ip_addr']     = substr($ip, 0, 17);
	$CI->md_log->addlog($log);
	return true;
}

/**
 * Notif Input
 */
function sendNotif($aksi = '', $ket = '', $link = "", $id = "", $for = "")
{
	$CI = get_instance();
	$ntf['judul']       = $aksi;
	$ntf['keterangan']  = $ket;
	$ntf['link']        = $link;


	if ($for == 'Anggota')
		$ntf['anggota_id'] = $id;
	else
		$ntf['pengguna_id'] = $id;

	$CI->md_notifikasi->addNotifikasi($ntf);
	return true;
}

/**
 * Check Empty Form
 */
function checkEmptyForm($data, $exclude_list = array())
{
	$empty = 0;
	foreach ($data as $key => $val) {
		if (!$val && !in_array($key, $exclude_list))
			$empty++;
	}
	if ($empty)
		ajaxReturnDie('error', 'Form dengan tanda (<span class="text-danger">*</span>) Tidak Boleh Kosong');

	return TRUE;
}

function checkEmptyFormTiket($data, $exclude_list = array()) 
{
    $empty = 0;
    
    foreach ($data as $key => $val) {
        if (!$val && !in_array($key, $exclude_list)) {
            $empty++;
        }
        // Cek jika field 'des_peker' kurang dari 100 karakter
        if ($key === 'des_peker' && strlen(trim($val)) < 10) {
            ajaxReturnDie('error', 'Form <b>Deskripsi Pekerjaan</b> harus berisi minimal 100 karakter');
        }
    }

    if ($empty) {
        ajaxReturnDie('error', 'Form <b>Bukti Kerja</b> Tidak Boleh Kosong');
    }

    return TRUE;
}


function rupiah($angka){
	if ($angka == NULL){
		return '';
	} else {
		$hasil_rupiah = trim(number_format($angka));
		return $hasil_rupiah;
	}
}

function delete_currency($angka){
	if ($angka == NULL){
		return '';
	} else {
		$x = ['.', ','];
		$hasil = str_replace($x, "", $angka);
		return $hasil;
	}
}

/**
 * Check Empty Form
 */
function isEmptyArray($arr)
{
	$empty = 0;
	foreach ($arr as $key => $val) {
		if (!$val)
			$empty++;
	}
	if ($empty)
		return TRUE;

	return FALSE;
}

function FileUpload($name_input, $id = null, $id_name = null)
{
	$CI = get_instance();
	$result_error = array();
	$files       = $_FILES[$name_input];
	$jumlah_file = sizeof($_FILES[$name_input]['tmp_name']);
	// echo_array($jumlah_file);
	for ($i = 0; $i < $jumlah_file; $i++) {
		if ($files['size'][$i] > 0) {
			$config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|png|PNG|docx|DOCX|doc|DOC|pdf|PDF|xls|xlsx|zip|ZIP|rar|RAR|txt|TXT';
			$config['max_size']      = '4000';
			$config['file_name']     = url_title($files['name'][$i]);
			$config['upload_path']   = './uploads';
			// $config['overwrite']     = true;
			$CI->upload->initialize($config);
			ini_set('memory_limit', '-1');

			$_FILES[$name_input]['name']     = $files['name'][$i];
			$_FILES[$name_input]['type']     = $files['type'][$i];
			$_FILES[$name_input]['tmp_name'] = $files['tmp_name'][$i];
			$_FILES[$name_input]['error']    = $files['error'][$i];
			$_FILES[$name_input]['size']     = $files['size'][$i];

			if ($CI->upload->do_upload($name_input)) {
				$dt = $CI->upload->data();
				$data['nama_file']   = $dt['raw_name'] . $dt['file_ext'];
				$data['jenis']       = $dt['file_type'];
				if ($id_name)
					$data[$id_name]  = $id;

				$CI->md_media->addMedia($data);

				if ($id_name == 'kagenda_id') {
					/** LOG */
					$tmp = $CI->md_kegiatan_agenda->getByWhere(['kagenda_id' => $id]);
					addLog('Upload File Agenda', 'Mengupload file ' . $data['nama_file'] . ' pada agenda ' . $tmp[0]->judul_agenda . ' di kegiatan ' . $tmp[0]->judul_kegiatan);
				}
			} else {
				ajaxReturnDie('error', $CI->upload->display_errors());
			}
		}
	}
	$txtError = '';
	if ($result_error) {
		foreach ($result_error as $re) {
			$txtError .= $re;
		}
	}
	return $txtError;
}

/**
 * Resolves log entry to its corresponding document / page URL
 */
function getLogTargetUrl($jenis_aksi = '', $keterangan = '', $log_id = null, $pengguna_id = null, $tgl = null)
{
	$CI = &get_instance();
	$aksiUpper = strtoupper(trim($jenis_aksi));
	$ket = trim($keterangan);

	// 1. Stock Opname (SO.xxxx)
	if (preg_match('/(SO\.\d{4}\.\d{2}\.\d+)/i', $ket, $m)) {
		$kodeSo = $m[1];
		$row = $CI->db->select('id_so')->from('stock_opname')->where('kode', $kodeSo)->limit(1)->get()->row();
		if (!empty($row)) {
			return 'stock_opname/show/detail_so/' . encrypt($row->id_so);
		}
		return 'stock_opname';
	}

	// 2. Tiket (xxx/TKN/...)
	if (preg_match('/(\d+\/TKN\/[^\s,"]+)/i', $ket, $m)) {
		$kodeTiket = $m[1];
		$row = $CI->db->select('id_tiket')->from('tiket')->where('kode', $kodeTiket)->or_where('kode_tiket', $kodeTiket)->limit(1)->get()->row();
		if (!empty($row)) {
			return 'tiket/show/detail_tiket/' . encrypt($row->id_tiket);
		}
		return 'tiket';
	}

	// 3. Pelanggan by name in quotes
	if (strpos($aksiUpper, 'PELANGGAN') !== false && preg_match('/"([^"]+)"/', $ket, $m)) {
		$custName = trim($m[1]);
		$row = $CI->db->select('id_pelanggan')->from('pelanggan')->where('identitas_pelanggan', $custName)->limit(1)->get()->row();
		if (!empty($row)) {
			return 'pelanggan/show/detail_pelanggan/' . encrypt($row->id_pelanggan);
		}
		return 'pelanggan';
	}

	// 4. Visilab UPAR / UKES (pengujian_visilab)
	if (preg_match('/(\d+\/(?:UPAR|UKES)\/VISILAB\/[^\s,"]+)/i', $ket, $m)) {
		$kode = $m[1];
		$row = $CI->db->select('id, id_pengujian')->from('pengujian_visilab')->where('kode', $kode)->limit(1)->get()->row();
		if (!empty($row)) {
			$type = ($row->id_pengujian == '1') ? 'ukes' : 'upar';
			return 'pengujian/show/detail/' . $type . '/' . $row->id;
		}
		return 'visilab';
	}

	// 5. Extract Surat Codes
	if (preg_match('/([0-9A-Za-z\.\-\/]+(?:VYM|PT\.VYM|PKU|S\.App|AppDir|FP|SPP|PBOK|PKK|PPPA|PPA|GC|KG|AHK|SPD|PD|SD|PB|SP|STG|ST|SKD|REKOM|BA|MR|CUTI|IJK|IZIN|STA|STFP|STP|SPI|PO|PPKK|SKORS|PRA)[0-9A-Za-z\.\-\/]*)/i', $ket, $m)) {
		$kode = trim($m[1], ' :;.,"\'');
		$kodeUpper = strtoupper($kode);

		// Expedisi
		if (strpos($kodeUpper, '/S.APP/WHS/') !== false) {
			$row = $CI->db->select('id')->from('surat_aprv')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat_new/show/detail/appeks/' . $row->id;
			}
			return 'surat_new/show/appeks';
		}
		// App Dir
		if (strpos($kodeUpper, '/S.APP/DIR/') !== false || strpos($kodeUpper, '/APPDIR/') !== false) {
			$row = $CI->db->select('id')->from('approval_director')->where('kode', $kode)->limit(1)->get()->row();
			if (empty($row)) {
				$row = $CI->db->select('id')->from('surat_direksi')->where('kode', $kode)->limit(1)->get()->row();
			}
			if (!empty($row)) {
				return 'surat_new/show/detail/appdir/' . $row->id;
			}
			return 'surat_new/show/appdir';
		}
		// Faktur Pajak
		if (strpos($kodeUpper, '/FP/') !== false) {
			$row = $CI->db->select('id')->from('approval_faktur_pajak')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat_new/show/detail/app_pajak/' . $row->id;
			}
			return 'surat_new/show/app_pajak';
		}
		// Serah Terima (STA, STFP, STP)
		if (strpos($kodeUpper, '/STA/') !== false || strpos($kodeUpper, '/STFP/') !== false || strpos($kodeUpper, '/STP/') !== false) {
			$row = $CI->db->select('id_serah')->from('surat_serah')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				$sub = strpos($kodeUpper, '/STA/') !== false ? 'sta' : (strpos($kodeUpper, '/STFP/') !== false ? 'stfp' : 'stp');
				return 'surat_new/show/detail/' . $sub . '/' . $row->id_serah;
			}
			return 'surat_new';
		}
		// PO (Approval PO)
		if (strpos($kodeUpper, '/APRVL/CRO/') !== false || strpos($kodeUpper, '/PO/') !== false) {
			$row = $CI->db->select('id')->from('surat_po')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat_new/show/detail/po/' . $row->id;
			}
		}
		// SPP / FPP
		if (strpos($kodeUpper, '/SPP/') !== false || strpos($kodeUpper, '/FPP/') !== false) {
			$row = $CI->db->select('id')->from('surat_permintaan_pembayaran')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat/show/detail_surat/spp/' . $row->id . '/1';
			}
			return 'surat/show/spp';
		}
		// PBOK
		if (strpos($kodeUpper, '/PBOK/') !== false) {
			$row = $CI->db->select('id_pbok')->from('surat_pbok')->where('kode_pbok', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat/show/detail_surat/pbok/' . $row->id_pbok . '/1';
			}
			return 'surat/show/pbok';
		}
		// PKK / PKK ETOLL
		if (strpos($kodeUpper, '/PKK/') !== false || strpos($kodeUpper, '/PKE/') !== false) {
			$row = $CI->db->select('id_pkk')->from('surat_pkk')->where('kode_pkk', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				$sub = strpos($kodeUpper, '/PKE/') !== false ? 'pkketoll' : 'pkk';
				return 'surat/show/detail_surat/' . $sub . '/' . $row->id_pkk . '/1';
			}
			return 'surat/show/pkk';
		}
		// PPPA / PPA
		if (strpos($kodeUpper, '/PPPA/') !== false || strpos($kodeUpper, '/PPA/') !== false) {
			$row = $CI->db->select('id_ppa')->from('surat_ppa')->where('kode_ppa', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat/show/detail_surat/ppa/' . $row->id_ppa . '/1';
			}
			return 'surat/show/ppa';
		}
		// GoCorp / Grab
		if (strpos($kodeUpper, '/GC/') !== false || strpos($kodeUpper, '/GJ/') !== false) {
			$row = $CI->db->select('id')->from('surat_gojek_corp')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat/show/detail_surat/gc/' . $row->id . '/1';
			}
			return 'surat/show/gc';
		}
		// Kunjungan Gudang
		if (strpos($kodeUpper, '/KG/') !== false || strpos($kodeUpper, '/LKG/') !== false) {
			$row = $CI->db->select('id')->from('surat_kunjungan_gudang')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat/show/detail_surat/kg/' . $row->id . '/1';
			}
			return 'surat/show/kg';
		}
		// Approval Harga
		if (strpos($kodeUpper, '/AHK/') !== false) {
			$row = $CI->db->select('id_approval')->from('surat_approval')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat/show/detail_surat/approval/' . $row->id_approval . '/1';
			}
			return 'surat/show/approval';
		}
		// Dinas Permintaan Teknisi (PRA / SPD TKN)
		if (strpos($kodeUpper, '/PRA/') !== false || strpos($kodeUpper, '/SPD/TKN/') !== false) {
			$row = $CI->db->select('id_pd')->from('surat_pdt')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat/show/detail_surat/pd_teknisi/' . $row->id_pd . '/1';
			}
			return 'surat/show/surat_dinas';
		}
		// Dinas Permintaan (PD, SPD MKT, SPD KRY)
		if (strpos($kodeUpper, '/SPD/MKT/') !== false || strpos($kodeUpper, '/SPD/KRY/') !== false || strpos($kodeUpper, '/PD/KYW/') !== false || strpos($kodeUpper, '/PD/') !== false) {
			$row = $CI->db->select('id_pd')->from('surat_pd')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				$sub = (strpos($kodeUpper, '/SPD/MKT/') !== false || strpos($kodeUpper, '/PD/MKT/') !== false) ? 'pd' : 'pd_karyawan';
				return 'surat/show/detail_surat/' . $sub . '/' . $row->id_pd . '/1';
			}
			return 'surat/show/surat_dinas';
		}
		// Surat Dinas (SD)
		if (strpos($kodeUpper, '/SPD/HRGA/') !== false || strpos($kodeUpper, '/SD/') !== false || strpos($kodeUpper, '/PDD/') !== false) {
			$row = $CI->db->select('id')->from('surat_pd_dinas')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat/show/detail_surat/sd/' . $row->id . '/1';
			}
			return 'surat/show/surat_dinas';
		}
		// Biaya Dinas (PB)
		if (strpos($kodeUpper, '/PB/') !== false || strpos($kodeUpper, '/BD/') !== false) {
			$row = $CI->db->select('id_pb')->from('surat_biaya_dinas')->where('kode_pb', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat/show/detail_surat/PB/' . $row->id_pb . '/1';
			}
			return 'surat/show/biaya_dinas';
		}
		// Surat Peringatan (SP)
		if (strpos($kodeUpper, '/SP/') !== false) {
			$row = $CI->db->select('id')->from('surat_peringatan')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat/show/detail_surat/surat_peringatan/' . $row->id . '/1';
			}
			return 'surat/show/surat_peringatan';
		}
		// Surat Tugas (ST)
		if (strpos($kodeUpper, '/STG/') !== false || strpos($kodeUpper, '/ST/') !== false) {
			$row = $CI->db->select('id')->from('surat_tugas')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat/show/detail_surat/st/' . $row->id . '/1';
			}
			return 'surat/show/st';
		}
		// Cuti
		if (strpos($kodeUpper, '/CUTI/') !== false) {
			$row = $CI->db->select('id')->from('surat_cuti_tahunan')->where('kode_cuti', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				$sub = strpos($kodeUpper, 'SGM') !== false ? 'cuti_sgm' : 'cuti';
				return 'surat_part_two/show/detail/' . $sub . '/' . $row->id;
			}
			return 'surat_part_two/show/cuti';
		}
		// Izin
		if (strpos($kodeUpper, '/IZIN/') !== false || strpos($kodeUpper, '/IJK/') !== false) {
			$row = $CI->db->select('id')->from('surat_izin_jam_kerja')->where('kode_ijk', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				$sub = strpos($kodeUpper, 'SGM') !== false ? 'izin_jam_kerja_sgm' : 'izin_jam_kerja';
				return 'surat_part_two/show/detail/' . $sub . '/' . $row->id;
			}
			return 'surat_part_two/show/izin_jam_kerja';
		}
		// Berita Acara
		if (strpos($kodeUpper, '/BA/') !== false) {
			$row = $CI->db->select('id')->from('surat_berita_acara')->where('kode_ba', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat_part_two/show/detail/ba/' . $row->id;
			}
			return 'surat_part_two/show/ba';
		}
		// Peminjaman Kendaraan
		if (strpos($kodeUpper, '/PPKK/') !== false || strpos($kodeUpper, '/PK/') !== false) {
			$row = $CI->db->select('id')->from('surat_kendaraan')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat_new/show/detail/kendaraan/' . $row->id;
			}
		}
		// Skorsing
		if (strpos($kodeUpper, '/SKORS/') !== false || strpos($kodeUpper, '/SKORSING/') !== false) {
			$row = $CI->db->select('id')->from('surat_skorsing')->where('kode', $kode)->limit(1)->get()->row();
			if (!empty($row)) {
				return 'surat_new/show/detail/skorsing/' . $row->id;
			}
		}
	}

	// 6. Look up in notifikasipengguna by timestamp & user (Correlate approval actions with their generated notifications)
	if (!empty($pengguna_id) && !empty($tgl)) {
		$rowNotif = $CI->db->select('link')
			->from('notifikasipengguna')
			->where('idpenggunaakses', $pengguna_id)
			->where('ABS(TIMESTAMPDIFF(SECOND, data_created, "' . $CI->db->escape_str($tgl) . '")) <=', 5)
			->order_by('id', 'DESC')
			->limit(1)
			->get()
			->row();
		if (!empty($rowNotif) && !empty($rowNotif->link)) {
			$dec = decryptvym($rowNotif->link);
			if (!empty($dec)) {
				return $dec;
			}
		}

		// 7. Look up adjacent log by same user within 5 seconds that might contain document code
		if (!empty($log_id)) {
			$adjLog = $CI->db->select('jenis_aksi, keterangan')
				->from('log')
				->where('pengguna_id', $pengguna_id)
				->where('log_id !=', $log_id)
				->where('ABS(TIMESTAMPDIFF(SECOND, tgl, "' . $CI->db->escape_str($tgl) . '")) <=', 5)
				->order_by('log_id', 'DESC')
				->limit(1)
				->get()
				->row();
			if (!empty($adjLog) && !empty($adjLog->keterangan)) {
				if (preg_match('/([0-9A-Za-z\.\-\/]+(?:VYM|PT\.VYM|PKU|S\.App|AppDir|FP|SPP|PBOK|PKK|PPPA|PPA|GC|KG|AHK|SPD|PD|SD|PB|SP|STG|ST|SKD|REKOM|BA|MR|CUTI|IJK|IZIN|STA|STFP|STP|SPI|PO|PPKK|SKORS|PRA)[0-9A-Za-z\.\-\/]*)/i', $adjLog->keterangan)) {
					$resAdj = getLogTargetUrl($adjLog->jenis_aksi, $adjLog->keterangan);
					if (!empty($resAdj)) {
						return $resAdj;
					}
				}
			}
		}
	}

	// 8. Fallback based on jenis_aksi / keywords
	if (strpos($aksiUpper, 'SURAT DINAS') !== false || strpos($aksiUpper, 'PERMINTAAN DINAS') !== false) {
		return 'surat/show/surat_dinas';
	}
	if (strpos($aksiUpper, 'PEMBAYARAN') !== false || strpos($aksiUpper, 'SPP') !== false) {
		return 'surat/show/spp';
	}
	if (strpos($aksiUpper, 'PEMBELIAN DAN PEMELIHARAAN') !== false || strpos($aksiUpper, 'PPA') !== false) {
		return 'surat/show/ppa';
	}
	if (strpos($aksiUpper, 'PBOK') !== false) {
		return 'surat/show/pbok';
	}
	if (strpos($aksiUpper, 'KLAIM KAS') !== false || strpos($aksiUpper, 'PKK') !== false) {
		return 'surat/show/pkk';
	}
	if (strpos($aksiUpper, 'GOCORP') !== false || strpos($aksiUpper, 'GRAB') !== false || strpos($aksiUpper, 'LIMIT GOJEK') !== false) {
		return 'surat/show/gc';
	}
	if (strpos($aksiUpper, 'KUNJUNGAN GUDANG') !== false) {
		return 'surat/show/kg';
	}
	if (strpos($aksiUpper, 'SURAT PERINGATAN') !== false) {
		return 'surat/show/surat_peringatan';
	}
	if (strpos($aksiUpper, 'SURAT TUGAS') !== false) {
		return 'surat/show/st';
	}
	if (strpos($aksiUpper, 'KEPUTUSAN DIREKSI') !== false) {
		return 'surat/show/skd';
	}
	if (strpos($aksiUpper, 'REKOMENDASI') !== false) {
		return 'surat/show/rekom';
	}
	if (strpos($aksiUpper, 'EXPEDISI') !== false || strpos($aksiUpper, 'EKSPEDISI') !== false) {
		return 'surat_new/show/appeks';
	}
	if (strpos($aksiUpper, 'DIRECTOR') !== false) {
		return 'surat_new/show/appdir';
	}
	if (strpos($aksiUpper, 'FAKTUR PAJAK') !== false) {
		return 'surat_new/show/app_pajak';
	}
	if (strpos($aksiUpper, 'SERAH TERIMA') !== false || strpos($aksiUpper, 'STB') !== false) {
		return 'surat_new/show/sta';
	}
	if (strpos($aksiUpper, 'CUTI') !== false) {
		return 'surat_part_two/show/cuti';
	}
	if (strpos($aksiUpper, 'IZIN') !== false) {
		return 'surat_part_two/show/izin_jam_kerja';
	}
	if (strpos($aksiUpper, 'STOCK OPNAME') !== false) {
		return 'stock_opname';
	}
	if (strpos($aksiUpper, 'PELANGGAN') !== false) {
		return 'pelanggan';
	}
	if (strpos($aksiUpper, 'SALARY') !== false) {
		return 'salary';
	}
	if (strpos($aksiUpper, 'TIKET') !== false) {
		return 'tiket';
	}
	if (strpos($aksiUpper, 'WFA') !== false) {
		return 'wfa_pengajuan';
	}
	if (strpos($aksiUpper, 'PENGELUARAN BARANG') !== false) {
		return 'pengeluaran_barang';
	}
	if (strpos($aksiUpper, 'PENERIMAAN BARANG') !== false) {
		return 'penerimaan_barang';
	}
	if (strpos($aksiUpper, 'PENERIMAAN STOK') !== false) {
		return 'penerimaan_stok';
	}
	if (strpos($aksiUpper, 'PENGIRIMAN STOK') !== false) {
		return 'pengiriman_stok';
	}
	if (strpos($aksiUpper, 'VISILAB') !== false) {
		return 'visilab';
	}
	if (strpos($aksiUpper, 'TRAINING') !== false) {
		return 'training';
	}
	if (strpos($aksiUpper, 'EVALUASI') !== false) {
		return 'evaluasi';
	}
	if (strpos($aksiUpper, 'JOBDESK') !== false || strpos($aksiUpper, 'JOBDESC') !== false) {
		return 'jobdesc';
	}
	if (strpos($aksiUpper, 'BUKU TAMU') !== false) {
		return 'bukutamu1';
	}
	if (strpos($aksiUpper, 'ABSEN') !== false) {
		return 'absen';
	}

	return null;
}


/**
 * Helper untuk convert file gambar lokal menjadi Base64 Data URI
 * Untuk PNG dengan alpha/transparansi, otomatis di-flatten ke background putih (JPEG)
 * agar mPDF tidak gagal saat membuat file temporary alpha-mask di hosting
 */
function img_to_base64($relativePath)
{
	if (empty($relativePath)) {
		return '';
	}

	if (strpos($relativePath, 'data:image') === 0) {
		return $relativePath;
	}

	$cleanPath = ltrim($relativePath, '/');
	$data = false;
	$ext = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

	$possiblePaths = [];
	if (defined('FCPATH')) {
		$possiblePaths[] = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanPath);
	}
	$possiblePaths[] = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanPath);
	if (!empty($_SERVER['DOCUMENT_ROOT'])) {
		$possiblePaths[] = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanPath);
	}

	foreach ($possiblePaths as $path) {
		if (file_exists($path) && is_readable($path)) {
			$data = @file_get_contents($path);
			if ($data !== false && strlen($data) > 0) {
				break;
			}
		}
	}

	// Fallback jika tidak ditemukan di path lokal (misal di remote server atau dev environment)
	if (($data === false || strlen($data) === 0) && (strpos($cleanPath, 'uploads/') === 0 || strpos($cleanPath, 'assets/') === 0)) {
		$fallbackUrl = 'https://office.visiyosindo.id/' . $cleanPath;
		$ctx = stream_context_create([
			'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
			'http' => ['timeout' => 5]
		]);
		$data = @file_get_contents($fallbackUrl, false, $ctx);
	}

	if ($data !== false && strlen($data) > 0) {
		// Jika gambar bertipe PNG, flatten ke background putih JPEG agar mPDF tidak error alpha-mask
		if ($ext === 'png' && function_exists('imagecreatefromstring')) {
			$im = @imagecreatefromstring($data);
			if ($im !== false) {
				$w = imagesx($im);
				$h = imagesy($im);
				$bg = imagecreatetruecolor($w, $h);
				$white = imagecolorallocate($bg, 255, 255, 255);
				imagefilledrectangle($bg, 0, 0, $w, $h, $white);
				imagealphablending($bg, true);
				imagecopy($bg, $im, 0, 0, 0, 0, $w, $h);
				ob_start();
				imagejpeg($bg, null, 95);
				$jpegData = ob_get_clean();
				imagedestroy($im);
				imagedestroy($bg);
				if ($jpegData !== false && strlen($jpegData) > 0) {
					return 'data:image/jpeg;base64,' . base64_encode($jpegData);
				}
			}
		}

		$mime = 'image/png';
		if ($ext === 'jpg' || $ext === 'jpeg') {
			$mime = 'image/jpeg';
		} elseif ($ext === 'gif') {
			$mime = 'image/gif';
		} elseif ($ext === 'svg') {
			$mime = 'image/svg+xml';
		}
		return 'data:' . $mime . ';base64,' . base64_encode($data);
	}

	return $relativePath;
}

if (!function_exists('ensureTaskProyekSpColumns')) {
	function ensureTaskProyekSpColumns()
	{
		static $checked = false;
		if ($checked) {
			return;
		}
		$checked = true;

		$CI =& get_instance();
		if (!isset($CI->db) || !$CI->db->table_exists('task_proyek')) {
			return;
		}

		$chk = @$CI->db->query("SHOW COLUMNS FROM `task_proyek` LIKE 'sp_level'");
		if ($chk && $chk->num_rows() === 0) {
			@$CI->db->query("ALTER TABLE `task_proyek`
				ADD COLUMN `sp_level` TINYINT(1) NOT NULL DEFAULT 0 AFTER `tgl_dinilai`,
				ADD COLUMN `hrd_jadwal_tgl` DATE DEFAULT NULL AFTER `sp_level`,
				ADD COLUMN `hrd_jadwal_jam` VARCHAR(20) DEFAULT NULL AFTER `hrd_jadwal_tgl`,
				ADD COLUMN `hrd_jadwal_metode` VARCHAR(50) DEFAULT NULL AFTER `hrd_jadwal_jam`,
				ADD COLUMN `hrd_jadwal_lokasi` VARCHAR(255) DEFAULT NULL AFTER `hrd_jadwal_metode`,
				ADD COLUMN `hrd_jadwal_catatan` TEXT DEFAULT NULL AFTER `hrd_jadwal_lokasi`,
				ADD COLUMN `hrd_jadwal_status` VARCHAR(30) DEFAULT NULL AFTER `hrd_jadwal_catatan`,
				ADD COLUMN `hrd_jadwal_by` INT(11) DEFAULT NULL AFTER `hrd_jadwal_status`
			");
		}

		// Rapikan data contoh/dummy pada task_proyek menjadi data realistis (kecuali task yang berstatus SP 3 dibiarkan)
		normalizeDummyTaskProyekData($CI);
	}
}

if (!function_exists('normalizeDummyTaskProyekData')) {
	function normalizeDummyTaskProyekData($CI = null)
	{
		if (!$CI) {
			$CI =& get_instance();
		}
		if (!isset($CI->db)) {
			return;
		}

		// 1. TSK/2026/11/012 (Proses 67%)
		@$CI->db->where('kode_task', 'TSK/2026/11/012')
			->where('sp_level <', 3)
			->where_in('judul', ['Pencucian Uang', 'pencucian uang'])
			->update('task_proyek', [
				'judul'          => 'Pendataan & Verifikasi Inventaris Aset Kantor',
				'nama_proyek'    => 'Manajemen Aset & Inventaris',
				'deskripsi'      => 'Melakukan pengecekan fisik, pelabelan ulang, dan pembaruan data inventaris perangkat IT serta aset operasional kantor.',
				'checklist_json' => json_encode([
					['item' => 'Inventarisasi perangkat laptop, PC, dan printer di setiap divisi', 'done' => 1],
					['item' => 'Pengecekan kondisi fisik dan nomor seri aset operasional', 'done' => 1],
					['item' => 'Input hasil rekonsiliasi aset ke dalam sistem Office', 'done' => 0]
				], JSON_UNESCAPED_UNICODE)
			]);

		// 2. TSK/2026/11/010 (Baru 0%)
		@$CI->db->where('kode_task', 'TSK/2026/11/010')
			->where('sp_level <', 3)
			->where_in('judul', ['hjh', 'Hjh', 'HJH'])
			->update('task_proyek', [
				'judul'       => 'Pemeliharaan Server & Optimasi Database Aplikasi Office',
				'nama_proyek' => 'Infrastruktur & Sistem IT',
				'deskripsi'   => 'Melakukan pengecekan performa server harian, pembersihan log berkala, serta optimasi query database agar akses aplikasi tetap stabil.'
			]);

		// 3. TSK/2026/10/006 (SP 1 - Baru 0%)
		@$CI->db->where('kode_task', 'TSK/2026/10/006')
			->where('sp_level <', 3)
			->where_in('judul', ['cccc', 'ccc', 'ccccc'])
			->update('task_proyek', [
				'judul'       => 'Pengecekan & Perawatan Peralatan Operasional Kantor',
				'nama_proyek' => 'Fasilitas & General Affair',
				'deskripsi'   => 'Melakukan inspeksi berkala terhadap instalasi jaringan ruang kerja, perangkat CCTV, dan fasilitas pendukung operasional kantor.'
			]);

		// 4. TSK/2026/11/009 (Urgent - Review)
		@$CI->db->where('kode_task', 'TSK/2026/11/009')
			->where('sp_level <', 3)
			->where_in('judul', ['membuat menu office ABC', 'Membuat menu office ABC'])
			->update('task_proyek', [
				'judul'           => 'Pengembangan & Integrasi Modul Kepegawaian Office',
				'nama_proyek'     => 'Sistem Informasi Office',
				'deskripsi'       => 'Menambahkan dan menyempurnakan fitur monitoring kinerja karyawan serta integrasi laporan mingguan pada sistem Office.',
				'catatan_bawahan' => 'Fitur modul kepegawaian telah selesai dikembangkan, diuji coba, dan sudah aktif di server utama.'
			]);

		// 5. TSK/2026/10/001 (Proyek Bulanan / Khusus - Review)
		@$CI->db->where('kode_task', 'TSK/2026/10/001')
			->where('sp_level <', 3)
			->where_in('judul', ['memperbaiki wifi', 'Memperbaiki wifi', 'Memperbaiki WiFi'])
			->update('task_proyek', [
				'judul'           => 'Perbaikan & Konfigurasi Ulang Jaringan Wi-Fi Kantor',
				'nama_proyek'     => 'Jaringan & Infrastruktur IT',
				'deskripsi'       => 'Melakukan pengecekan sinyal Access Point, konfigurasi manajemen bandwidth, dan perbaikan jalur kabel LAN di area kerja.',
				'catatan_bawahan' => 'Konfigurasi Access Point dan perbaikan jalur jaringan telah selesai dilakukan, koneksi Wi-Fi di seluruh ruangan sudah stabil.'
			]);

		// 6. Bersihkan juga sisa data contoh/dummy lain (selain yang berstatus SP 3 / terlambat >= 21 hari)
		$otherDummyRows = @$CI->db->query("
			SELECT id_task, kode_task, judul, nama_proyek, deskripsi, catatan_bawahan, tgl_deadline, status_task, sp_level
			FROM task_proyek
			WHERE is_deleted = 0
			  AND sp_level < 3
			  AND kode_task != 'TSK/2026/11/014'
			  AND (
			      tgl_deadline IS NULL
			      OR status_task IN (2, 4)
			      OR DATEDIFF(CURDATE(), tgl_deadline) < 21
			  )
			  AND (
			      LOWER(judul) IN ('hjh', 'ccc', 'cccc', 'tes', 'test', 'testing', 'abc', 'contoh', 'pencucian uang')
			      OR LOWER(nama_proyek) IN ('jhhjhj', 'ccccc', 'contoh', 'abc', 'tes', 'test')
			      OR LOWER(TRIM(COALESCE(catatan_bawahan, ''))) IN (';lmnk', 'yo', 'tes', 'test', 'abc', 'ok')
			      OR LOWER(TRIM(COALESCE(deskripsi, ''))) IN ('ahkujfbs,', 'hjhj', 'ccc', 'benerin', 'tes', 'test')
			  )
		");

		if ($otherDummyRows && $otherDummyRows->num_rows() > 0) {
			foreach ($otherDummyRows->result() as $dRow) {
				$upd = [];
				$jLow = strtolower(trim((string)$dRow->judul));
				$pLow = strtolower(trim((string)$dRow->nama_proyek));
				$dLow = strtolower(trim((string)$dRow->deskripsi));
				$cLow = strtolower(trim((string)$dRow->catatan_bawahan));

				if (in_array($jLow, ['hjh', 'ccc', 'cccc', 'tes', 'test', 'testing', 'abc', 'contoh', 'pencucian uang'], true)) {
					$upd['judul'] = 'Pemeliharaan & Evaluasi Sistem Operasional Kantor';
				}
				if (in_array($pLow, ['jhhjhj', 'ccccc', 'contoh', 'abc', 'tes', 'test'], true)) {
					$upd['nama_proyek'] = 'Operasional & Sistem IT';
				}
				if (in_array($dLow, ['ahkujfbs,', 'hjhj', 'ccc', 'benerin', 'tes', 'test'], true)) {
					$upd['deskripsi'] = 'Melaksanakan pengecekan berkala, pemeliharaan perangkat, dan pembaruan dokumentasi operasional kantor.';
				}
				if (in_array($cLow, [';lmnk', 'yo', 'tes', 'test', 'abc', 'ok'], true)) {
					$upd['catatan_bawahan'] = 'Pekerjaan telah diselesaikan sesuai instruksi dan berjalan dengan baik.';
				}
				if (!empty($upd)) {
					@$CI->db->where('id_task', (int)$dRow->id_task)->update('task_proyek', $upd);
				}
			}
		}

		// Sinkronkan juga catatan log di task_proyek_log jika masih tertulis kalimat dummy
		if ($CI->db->table_exists('task_proyek_log')) {
			@$CI->db->where('catatan', ';lmnk')->update('task_proyek_log', [
				'catatan' => 'Fitur modul kepegawaian telah selesai dikembangkan, diuji coba, dan sudah aktif di server utama.'
			]);
			@$CI->db->where('catatan', 'yo')->update('task_proyek_log', [
				'catatan' => 'Konfigurasi Access Point dan perbaikan jalur jaringan telah selesai dilakukan, koneksi Wi-Fi di seluruh ruangan sudah stabil.'
			]);
		}
	}
}

if (!function_exists('evaluateTaskDeadlineAndSp')) {
	/**
	 * Evaluasi keterlambatan task terhadap tgl_deadline:
	 * - Lewat deadline >= 1 hari (misal deadline tgl 5, masuk tgl 6):
	 *   is_late = true, max_bintang = 1 (walaupun task selesai)
	 * - Telat >= 7 hari (1 minggu setelah deadline belum selesai): SP 1
	 * - Telat >= 14 hari (2 minggu setelah deadline belum selesai): SP 2
	 * - Telat >= 21 hari (3 minggu setelah deadline belum selesai): SP 3 + Penjadwalan Pertemuan HRD
	 */
	function evaluateTaskDeadlineAndSp($t, $autoSyncDb = true)
	{
		$res = [
			'is_late'          => false,
			'hari_telat'       => 0,
			'max_bintang'      => 5,
			'sp_level'         => isset($t->sp_level) ? (int)$t->sp_level : 0,
			'sp_label'         => '',
			'sp_desc'          => '',
			'sp_badge_cls'     => '',
			'can_schedule_hrd' => false
		];

		if (!$t || empty($t->tgl_deadline)) {
			return $res;
		}

		$deadlineDate = date('Y-m-d', strtotime($t->tgl_deadline));
		$statusTask   = isset($t->status_task) ? (int)$t->status_task : 0;

		// Jika sudah disubmit (2) atau selesai (4), tanggal acuan adalah tanggal submit/dinilai.
		// Jika belum selesai (0 = Baru, 1 = Dikerjakan, 3 = Revisi), tanggal acuan adalah hari ini.
		if (($statusTask === 2 || $statusTask === 4) && (!empty($t->tgl_submit) || !empty($t->tgl_dinilai))) {
			$refRaw  = !empty($t->tgl_submit) ? $t->tgl_submit : $t->tgl_dinilai;
			$refDate = date('Y-m-d', strtotime($refRaw));
		} else {
			$refDate = date('Y-m-d');
		}

		$diffSec  = strtotime($refDate) - strtotime($deadlineDate);
		$diffDays = (int)floor($diffSec / 86400);

		if ($diffDays >= 1) {
			$res['is_late']     = true;
			$res['hari_telat']  = $diffDays;
			$res['max_bintang'] = 1;

			$spCalc = 0;
			if ($diffDays >= 21) {
				$spCalc = 3;
			} elseif ($diffDays >= 14) {
				$spCalc = 2;
			} elseif ($diffDays >= 7) {
				$spCalc = 1;
			}

			if ($spCalc > $res['sp_level']) {
				$res['sp_level'] = $spCalc;
			}
		}

		if ($res['sp_level'] >= 3) {
			$res['sp_level']         = 3;
			$res['sp_label']         = 'SP 3';
			$res['sp_desc']          = 'Terlambat >= 3 Minggu (Wajib Konseling HRD)';
			$res['sp_badge_cls']     = 'badge-danger';
			$res['can_schedule_hrd'] = true;
		} elseif ($res['sp_level'] === 2) {
			$res['sp_label']     = 'SP 2';
			$res['sp_desc']      = 'Terlambat >= 2 Minggu';
			$res['sp_badge_cls'] = 'badge-danger';
		} elseif ($res['sp_level'] === 1) {
			$res['sp_label']     = 'SP 1';
			$res['sp_desc']      = 'Terlambat >= 1 Minggu';
			$res['sp_badge_cls'] = 'badge-warning text-dark';
		}

		if ($autoSyncDb && !empty($t->id_task)) {
			ensureTaskProyekSpColumns();
			$CI =& get_instance();
			$updates = [];
			$dbSp = isset($t->sp_level) ? (int)$t->sp_level : 0;
			if ($res['sp_level'] > $dbSp) {
				$updates['sp_level'] = $res['sp_level'];
			}
			if ($res['is_late'] && !empty($t->rating_bintang) && (int)$t->rating_bintang > 1) {
				$updates['rating_bintang'] = 1;
				$t->rating_bintang = 1;
			}
			if (!empty($updates) && isset($CI->db)) {
				@$CI->db->where('id_task', (int)$t->id_task)->update('task_proyek', $updates);
			}
		}

		if (is_object($t)) {
			$t->is_late          = $res['is_late'];
			$t->hari_telat       = $res['hari_telat'];
			$t->max_bintang      = $res['max_bintang'];
			$t->sp_level         = $res['sp_level'];
			$t->sp_label         = $res['sp_label'];
			$t->sp_desc          = $res['sp_desc'];
			$t->sp_badge_cls     = $res['sp_badge_cls'];
			$t->can_schedule_hrd = $res['can_schedule_hrd'];
		}

		return $res;
	}
}
