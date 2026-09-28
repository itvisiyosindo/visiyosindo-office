<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class md_surat_list extends CI_Model
{
	function countApprovalModal($idapproval, $modal)
	{
		$this->db->select('*')
			->where('id_approval', $idapproval)
			->where('modal', $modal);
		return $this->db->get('surat_approval_detail');
	}

	function getmasternotifikasi($idsurat)
	{
		$this->db->select('masterjenisnotifikasi.*,v1.nama as namav1,v1.jabatan as jabatanv1,v2.nama as namav2,v2.jabatan as jabatanv2,d1.nama as namad1,d1.jabatan as jabatand1,d2.nama as namad2,d2.jabatan as jabatand2')
			->from('masterjenisnotifikasi')
			->join('pengguna v1', 'v1.pengguna_id=masterjenisnotifikasi.verifikasi1', 'left')
			->join('pengguna v2', 'v2.pengguna_id=masterjenisnotifikasi.verifikasi2', 'left')
			->join('pengguna d1', 'd1.pengguna_id=masterjenisnotifikasi.disetujui1', 'left')
			->join('pengguna d2', 'd2.pengguna_id=masterjenisnotifikasi.disetujui2', 'left')
			->where('id', $idsurat);
		return $this->db->get()->result();
	}

	function getBywhere($where)
	{
		$this->db->select('
							s.status as stat_persetujuan,
                            sPB.id_surat as idSurat,
							sPB.kode_pb as kodePB,
							sPB.id_pengguna as idPengaju,
							sPB.kota,
							sPB.keperluan as perihal,
							sPB.tgl_laporan as tgl_laporan,
							sPB.tgl_pengajuan as tgl_pengajuan
                        ')
			->from('surat_list s')
			->join('surat_biaya_dinas sPB', 's.id_srt=sPB.id_pb')
			->where($where)
			->order_by('s.id_list_surat', 'DESC');
		return $this->db->get()->result();
	}

	//hapus semua semua tanda tangan jika ditolak
	public function update_laporan_ttd($id_pb)
	{
		$data = array(
			'pengajuan_ttd_1' => '0',
			'pengajuan_ttd_2' => '0',
			'pengajuan_ttd_3' => '0',
			'persetujuan_ga' => 0
		);
		$this->db->where('id_pb', $id_pb);
		$this->db->update('surat_biaya_dinas', $data);
	}
	function update_surat_list($idkat, $id, $stat)
	{
		$data['status'] = $stat;

		$this->db->where('id_srt', $id);
		$this->db->where('id_kat_surat', $idkat);
		$this->db->update('surat_list', $data);
	}

	function addSuratList($surat = "", $data)
	{
		$this->db->insert($tabel, $data);
	}
	function addNotifikasi($data)
	{
		$this->db->insert('notifikasipengguna', $data);
	}
	function NotifikasiNonAktif($pengguna_id, $jenis, $idsurat)
	{
		$where = array(
			'jenis' => $jenis,
			'idsurat' => $idsurat,
			'id_pengguna' => $pengguna_id
		);
		$this->db->where($where);
		$this->db->update('notifikasipengguna', array('status' => 1));
	}
	function NotifikasiNonAktifExpired()
	{
		$this->db->where('DATE_ADD(data_created, INTERVAL 2 DAY) < NOW()');
		$this->db->update('notifikasipengguna', array('status' => 1));
	}

	function addSurat($surat = "", $data)
	{
		if ($surat == "list") {
			$tabel = "surat_list";
		} else if ($surat == "pb") {
			$tabel = "surat_biaya_dinas";
		} else if ($surat == "dpb") {
			$tabel = "surat_detail_biaya_dinas";
		} else if ($surat == "spp") {
			$tabel = "surat_permintaan_pembayaran";
		} else if ($surat == "dspp") {
			$tabel = "surat_permintaan_pembayaran_detail";
		} else if ($surat == "gc") {
			$tabel = "surat_gojek_corp";
		} else if ($surat == "dgc") {
			$tabel = "surat_gojek_corp_detail";
		} else if ($surat == "pbok") {
			$tabel = "surat_pbok";
		} else if ($surat == "dpbok") {
			$tabel = "surat_pbok_detail";
		} else if ($surat == "pkk") {
			$tabel = "surat_pkk";
		} else if ($surat == "pkketoll") {
			$tabel = "surat_pkketoll";
		} else if ($surat == "dpkk") {
			$tabel = "surat_pkk_detail";
		} else if ($surat == "dpkketoll") {
			$tabel = "surat_pkketoll_detail";
		} else if ($surat == "kg") {
			$tabel = "surat_kunjungan_gudang";
		} else if ($surat == "ppa") {
			$tabel = "surat_ppa";
		} else if ($surat == "dppa") {
			$tabel = "surat_ppa_detail";
		} else if ($surat == "approval") {
			$tabel = "surat_approval";
		} else if ($surat == "dapproval") {
			$tabel = "surat_approval_detail";
		} else if ($surat == "pd") {
			$tabel = "surat_pd";
		} else if ($surat == "dpd") {
			$tabel = "surat_pd_detail";
		} else if ($surat == "sd") {
			$tabel = "surat_pd_dinas";
		} else if ($surat == "pd_teknisi") {
			$tabel = "surat_pdt";
		} else if ($surat == "sd_teknisi") {
			$tabel = "surat_pdt_dinas";
		} else if ($surat == "surat_pdt_detail") {
			$tabel = "surat_pdt_detail";
		} else if ($surat == "serah_terima_kerja") {
			$tabel = "surat_st_kerja";
		} else if ($surat == "surat_peringatan") {
			$tabel = "surat_peringatan";
		} else if ($surat == "st") {
			$tabel = "surat_tugas";
		} else if ($surat == "rekom") {
			$tabel = "surat_rekom";
		} else if ($surat == "keterangan") {
			$tabel = "surat_keterangan";
		} else if ($surat == "simp") {
			$tabel = "surat_izin_kerja";
		} else {
			$tabel = $surat;
		}

		$this->db->insert($tabel, $data);
	}

	// fungsi hapus
	function hapus($where, $tabel)
	{
		$this->db->where($where);
		$this->db->delete($tabel);
	}


	// fungsi reset urutan id pada tabel
	function reset_increment($tabel)
	{
		$this->db->query("ALTER TABLE " . $tabel . " AUTO_INCREMENT = 1");
	}

	function getLastCode()
	{
		return $this->db->select("*")->limit(1)->order_by('id_tiket', "DESC")->get('tiket')->row();
	}

	function getLastCodeDinas()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_pd_dinas')->row();
	}

	function getPDDKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_pd_dinas', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	//Surat Pembiayaan Dinas	
	function getAllSurat()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                sPB.id_pb as idPB,
				sPB.kode_pb as kodePB,
				sPB.id_pengguna as idPengaju,
				sPB.kota as kota,
				sPB.keperluan as perihal,
				sPB.tgl_laporan as tglLaporan,
				sPB.pengajuan_ttd_1 as aju_ttd1,
				sPB.pengajuan_ttd_2 as aju_ttd2,
				sPB.pengajuan_ttd_3 as aju_ttd3,
				sPB.laporan_ttd_1 as lap_ttd1,
				sPB.laporan_ttd_2 as lap_ttd2,
				sPB.laporan_ttd_3 as lap_ttd3,
				sPB.persetujuan_ga as persetujuan_ga,
				p.short_name as pengaju,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_biaya_dinas sPB', 's.id_srt=sPB.id_pb')
			->join('pengguna p', 'sPB.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sPB.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 1')
			->generate();
	}

	function getAllSuratByPengaju($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                sPB.id_pb as idPB,
				sPB.kode_pb as kodePB,
				sPB.id_pengguna as idPengaju,
				sPB.kota as kota,
				sPB.keperluan as perihal,
				sPB.tgl_laporan as tglLaporan,
				sPB.pengajuan_ttd_1 as aju_ttd1,
				sPB.pengajuan_ttd_2 as aju_ttd2,
				sPB.pengajuan_ttd_3 as aju_ttd3,
				sPB.laporan_ttd_1 as lap_ttd1,
				sPB.laporan_ttd_2 as lap_ttd2,
				sPB.laporan_ttd_3 as lap_ttd3,
				sPB.persetujuan_ga as persetujuan_ga,
				p.short_name as pengaju,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_biaya_dinas sPB', 's.id_srt=sPB.id_pb')
			->join('pengguna p', 'sPB.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sPB.id_kat_surat=sKat.id_kat_surat')
			->where('p.pengguna_id', $id_pengaju)
			->where('s.id_kat_surat = 1')
			->generate();
	}

	function getPBById($idPB)
	{
		$this->db->select('
                sPB.id_pb as idPB,
				sPB.kode_pb as kodePB,
				sPB.id_pengguna as idPengaju,
				sPB.kota as kota,
				sPB.kota_pengajuan as kota_pengajuan,
				sPB.keperluan as perihal,
				sPB.tgl_pengajuan as tglPengajuan,
				sPB.tgl_pergi as tglPergi,
				sPB.tgl_kembali as tglKembali,
				sPB.tgl_laporan as tglLaporan,
				sPB.pengajuan_ttd_1 as aju_ttd1,
				sPB.pengajuan_ttd_2 as aju_ttd2,
				sPB.pengajuan_ttd_3 as aju_ttd3,
				sPB.laporan_ttd_1 as lap_ttd1,
				sPB.laporan_ttd_2 as lap_ttd2,
				sPB.laporan_ttd_3 as lap_ttd3,
				sPB.lampiran_pengajuan as lampiran_1,
				sPB.lampiran_laporan as lampiran_2,
				sPB.catatan_finance as catatan_finance,
				sPB.persetujuan_ga as persetujuan_ga,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_biaya_dinas sPB', 's.id_srt=sPB.id_pb and year(`sPB`.`tgl_pengajuan`)=s.tahun')
			->join('pengguna p', 'sPB.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sPB.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 1')
			->where('sPB.id_pb', $idPB);
		return $this->db->get()->result();
	}

	function getByIdDetaiLapPB($id)
	{
		return $this->db->get_where('surat_detail_biaya_dinas p', array('p.id_detail_pb' => $id))->result();
	}

	function updateDetailLapPB($id, $data)
	{
		$this->db->where('id_detail_pb', $id);
		$this->db->update('surat_detail_biaya_dinas', $data);
	}

	function addDetailLapPB($data)
	{
		$this->db->insert('surat_detail_biaya_dinas', $data);
	}

	function getByIdDeleteDetailLapPB($id)
	{
		return $this->db->get_where('surat_detail_biaya_dinas p', array('p.id_detail_pb' => $id))->row();
	}

	function deleteDetailLapPB($id)
	{
		$this->db->where('id_detail_pb', $id);
		return $this->db->delete('surat_detail_biaya_dinas');
	}


	function getDetailPb($idPB)
	{
		$this->db->select('
                sPB.id_pb as idPB,
				dPB.tanggal as tgl,
				dPB.keterangan as ket,
				dPB.nominal as nominal
            ')
			->from('surat_detail_biaya_dinas dPB')
			->join('surat_biaya_dinas sPB', 'dPB.id_pb=sPB.id_pb')
			->where('dPB.id_pb', $idPB)
			->where('dPB.kode_laporan = 1');
		return $this->db->get()->result();
	}

	function getDetailLaporanPb($idPB)
	{
		$this->db->select('
                sPB.id_pb as idPB,
                dPB.id_detail_pb as idDetailPB,
				dPB.tanggal as tgl,
				dPB.keterangan as ket,
				dPB.nominal as nominal
            ')
			->from('surat_detail_biaya_dinas dPB')
			->join('surat_biaya_dinas sPB', 'dPB.id_pb=sPB.id_pb')
			->where('dPB.id_pb', $idPB)
			->where('dPB.kode_laporan = 2');
		return $this->db->get()->result();
	}

	function getDetailCatatanFinance($idPB)
	{
		$this->db->select('
                sPB.id_pb as idPB,
				dPB.keterangan as ket
            ')
			->from('surat_detail_biaya_dinas dPB')
			->join('surat_biaya_dinas sPB', 'dPB.id_pb=sPB.id_pb')
			->where('dPB.id_pb', $idPB)
			->where('dPB.kode_laporan = 3');
		return $this->db->get()->result();
	}

	function getPbKodeId()
	{
		return $this->db->select("COUNT(*) as id_pb")->limit(1)->order_by('id_pb', "DESC")->get_where('surat_biaya_dinas', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function getPbLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id_pb', "DESC")->get('surat_biaya_dinas')->row();
	}

	function getPbTiket($id_pengaju, $kode = "")
	{
		if ($kode == "pengajuan") {
			$status_list	= 1;
			$status_tiket 	= 0;
		} else if ($kode == "laporan") {
			$status_list	= 3;
			$status_tiket 	= 1;
		}
		$this->db->select('
                s.status as stat_persetujuan,
                sPB.id_pb as idPB,
				sPB.kode_pb as kodePB,
				sPB.id_pengguna as idPengaju,
				sPB.kota as kota,
				sPB.keperluan as perihal,
				sPB.tgl_laporan as tglLaporan,
				sPB.pengajuan_ttd_1 as aju_ttd1,
				sPB.pengajuan_ttd_2 as aju_ttd2,
				sPB.pengajuan_ttd_3 as aju_ttd3,
				sPB.laporan_ttd_1 as lap_ttd1,
				sPB.laporan_ttd_2 as lap_ttd2,
				sPB.laporan_ttd_3 as lap_ttd3,
				p.short_name as pengaju,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_biaya_dinas sPB', 's.id_srt=sPB.id_pb and year(`sPB`.`tgl_pengajuan`)=s.tahun')
			->join('pengguna p', 'sPB.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sPB.id_kat_surat=sKat.id_kat_surat')
			->where('p.pengguna_id', $id_pengaju)
			->where('s.id_kat_surat = 1')
			->where('sPB.status_tiket', $status_tiket)
			->where('s.status', $status_list)
			->order_by('s.id_list_surat', 'DESC');
		return $this->db->get()->result();
	}

	function update_pb($id, $data)
	{
		$this->db->where('id_pb', $id);
		$this->db->update('surat_biaya_dinas', $data);
	}

	function update_pbByWhere($data, $where)
	{
		$this->db->where($where);
		$this->db->update('surat_biaya_dinas', $data);
	}

	function update_pbaja()
	{
		$data = array(
			'pengajuan_ttd_1' => 1
		);
		$this->db->where('id_pb = 1');
		$this->db->update('surat_biaya_dinas', $data);
	}


	//Surat Permintaan Pembayaran (SPP)    
	function getAllSpp()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                spp.id as idSpp,
				spp.kode as kode,
				spp.id_pengaju as idPengaju,
				spp.rek_bank as bank,
				spp.rek_norek as norek,
				spp.rek_nama as namaRek,
				spp.file_lampiran as lampiran,
				spp.kota_pengajuan as kota_pengajuan,
				spp.tgl_pengajuan as tglPengajuan,
				spp.ttd1 as aju_ttd1,
				spp.ttd2 as aju_ttd2,
				spp.ttd3 as aju_ttd3,
				spp.catatan as catatan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_permintaan_pembayaran spp', 's.id_srt=spp.id and year(`spp`.`tgl_pengajuan`)=s.tahun')
			->join('pengguna p', 'spp.id_pengaju=p.pengguna_id')
			->join('surat_kategori sKat', 'spp.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 2')
			->generate();
	}

	function getAllMySpp($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
				s.status as stat_persetujuan,
                spp.id as idSpp,
				spp.kode as kode,
				spp.id_pengaju as idPengaju,
				spp.rek_bank as bank,
				spp.rek_norek as norek,
				spp.rek_nama as namaRek,
				spp.file_lampiran as lampiran,
				spp.kota_pengajuan as kota_pengajuan,
				spp.tgl_pengajuan as tglPengajuan,
				spp.ttd1 as aju_ttd1,
				spp.ttd2 as aju_ttd2,
				spp.ttd3 as aju_ttd3,
				spp.catatan as catatan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_permintaan_pembayaran spp', 's.id_srt=spp.id')
			->join('pengguna p', 'spp.id_pengaju=p.pengguna_id')
			->join('surat_kategori sKat', 'spp.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 2')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getSppById($id)
	{
		$this->db->select('
                spp.id as idSpp,
				spp.kode as kode,
				spp.id_pengaju as idPengaju,
				spp.terbilang as terbilang,
				spp.rek_bank as bank,
				spp.rek_norek as norek,
				spp.rek_nama as namaRek,
				spp.kode_mata_uang as kodeMataUang,
				spp.file_lampiran as lampiran,
				spp.kota_pengajuan as kota_pengajuan,
				spp.tgl_pengajuan as tglPengajuan,
				spp.ttd1 as aju_ttd1,
				spp.ttd2 as aju_ttd2,
				spp.ttd3 as aju_ttd3,
				spp.catatan as catatan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_permintaan_pembayaran spp')
			->join('pengguna p', 'spp.id_pengaju=p.pengguna_id')
			->join('surat_kategori sKat', 'spp.id_kat_surat=sKat.id_kat_surat')
			->where('spp.id', $id);
		return $this->db->get()->result();
	}

	function getDetailSpp($id)
	{
		$this->db->select('
				dspp.tgl as tgl,
				dspp.keterangan as ket,
				dspp.nominal as nominal,
				dspp.mata_uang as mata_uang
            ')
			->from('surat_permintaan_pembayaran_detail dspp')
			->where('dspp.id_srt', $id)
			->where('dspp.kode_detail = 1');
		return $this->db->get()->result();
	}

	function getSppLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_permintaan_pembayaran')->row();
	}

	function getSppKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_permintaan_pembayaran', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function update_spp($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_permintaan_pembayaran', $data);
	}

	function updateSuratSpp($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_permintaan_pembayaran', $data);
	}

	/*	
	-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	Surat limit gocorp (gc) 
	-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
	function getAllGc()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                gc.id as idGc,
				gc.kode as kode,
				gc.id_pengaju as idPengaju,
				gc.kota as kota,
				gc.kota_pengajuan as kota_pengajuan,
				gc.keperluan as perihal,
				gc.tgl_pengajuan as tglPengajuan,
				gc.tgl_pergi as tglPergi,
				gc.tgl_kembali as tglKembali,
				gc.pengajuan_ttd_1 as aju_ttd1,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_gojek_corp gc', 's.id_srt=gc.id')
			->join('pengguna p', 'gc.id_pengaju=p.pengguna_id')
			->join('surat_kategori sKat', 'gc.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 3')
			->generate();
	}

	function getAllMyGc($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
				s.status as stat_persetujuan,
                gc.id as idGc,
				gc.kode as kode,
				gc.id_pengaju as idPengaju,
				gc.kota as kota,
				gc.kota_pengajuan as kota_pengajuan,
				gc.keperluan as perihal,
				gc.tgl_pengajuan as tglPengajuan,
				gc.tgl_pergi as tglPergi,
				gc.tgl_kembali as tglKembali,
				gc.pengajuan_ttd_1 as aju_ttd1,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_gojek_corp gc', 's.id_srt=gc.id')
			->join('pengguna p', 'gc.id_pengaju=p.pengguna_id')
			->join('surat_kategori sKat', 'gc.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 3')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getGcById($id)
	{
		$this->db->select('
                gc.id as idGc,
				gc.kode as kode,
				gc.id_pengaju as idPengaju,
				gc.kota as kota,
				gc.kota_pengajuan as kota_pengajuan,
				gc.keperluan as perihal,
				gc.tgl_pengajuan as tglPengajuan,
				gc.tgl_pergi as tglPergi,
				gc.tgl_kembali as tglKembali,
				gc.pengajuan_ttd_1 as aju_ttd1,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_gojek_corp gc')
			->join('pengguna p', 'gc.id_pengaju=p.pengguna_id')
			->join('surat_kategori sKat', 'gc.id_kat_surat=sKat.id_kat_surat')
			->where('gc.id', $id);
		return $this->db->get()->result();
	}

	function getDetailGc($id)
	{
		$this->db->select('
				dgc.tanggal as tgl,
				dgc.keterangan as ket,
				dgc.nominal as nominal
            ')
			->from('surat_gojek_corp_detail dgc')
			->where('dgc.id_srt', $id)
			->where('dgc.kode_detail = 1');
		return $this->db->get()->result();
	}

	function getGcLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_gojek_corp')->row();
	}

	function getGcKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_gojek_corp', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function update_Gc($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_gojek_corp', $data);
	}


	// Surat PBOK--------------------------------------------------------------------------------------------------------------------------------------
	function updateSuratPbok($id, $data)
	{
		$this->db->where('id_pbok', $id);
		$this->db->update('surat_pbok', $data);
	}

	function update_pbok($id, $data)
	{
		$this->db->where('id_pbok', $id);
		$this->db->update('surat_pbok', $data);
	}

	function update_detailcatatanpbokByIdPbokId($idpbok, $id, $catatan)
	{
		$data = array(
			'catatan' => $catatan
		);
		$this->db->where('id_pbok', $idpbok);
		$this->db->where('id', $id);
		$this->db->update('surat_pbok_detail', $data);
	}

	function getPbokLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id_pbok', "DESC")->get('surat_pbok')->row();
	}

	function getPbokKodeId()
	{
		return $this->db->select("COUNT(*) as id_pbok")->limit(1)->order_by('id_pbok', "DESC")->get_where('surat_pbok', array('YEAR(`created_at`)' => date('Y')))->row();
	}

	function getDetailPbok($idPBOK)
	{
		$this->db->select('
            dPBOK.id as id,
            sPBOK.id_pbok as idPBOK,
            sPBOK.kode_pbok as kodePBOK,
			dPBOK.keterangan as keterangan,
			dPBOK.estimasi as est,
			dPBOK.qty as qty,
			dPBOK.total as ttl,
			dPBOK.catatan as catatan,
			dPBOK.mata_uang as mata_uang
        ')
			->from('surat_pbok_detail dPBOK')
			->join('surat_pbok sPBOK', 'dPBOK.id_pbok=sPBOK.id_pbok')
			->where('dPBOK.id_pbok', $idPBOK);
		return $this->db->get()->result();
	}

	function getPengajuPbokList()
	{
		$this->db->select('p.pengguna_id, p.nama, p.jabatan')
			->from('pengguna p')
			->where('p.nama IS NOT NULL')
			->where('p.nama !=', '')
			->order_by('p.nama', 'ASC');
		return $this->db->get()->result();
	}

	function getPbokByPengajuId($id_pengaju)
	{
		$this->db->select('
			s.status as stat_persetujuan,
			pbok.id_pbok as idPbok,
			pbok.kode_pbok as kode,
			pbok.tgl_pengajuan,
			pbok.bank,
			pbok.rekening,
			pbok.ats_nama,
			pbok.kota_aju,
			pbok.pengajuan_ttd_1 as aju_ttd1,
			pbok.ttd_2 as aju_ttd2,
			pbok.ttd_3 as aju_ttd3,
			pbok.trf_pajak,
			pbok.nml_pajak,
			pbok.pembayar_pajak,
			pbok.lampiran,
			p.nama as pengguna,
			p.jabatan as jabatan
		')
		->from('surat_list s')
		->join('surat_pbok pbok', 's.id_srt=pbok.id_pbok')
		->join('pengguna p', 'pbok.id_pengguna=p.pengguna_id')
		->where('s.id_kat_surat = 4')
		->where('pbok.id_pengguna', $id_pengaju)
		->order_by('pbok.tgl_pengajuan', 'DESC');
		return $this->db->get()->result();
	}

	function getAllPbok($id_pengaju = null)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		$this->datatables
			->select('
            	
				s.status as stat_persetujuan,
                pbok.id_pbok as idPbok,
				pbok.kode_pbok as kode,
				pbok.bank as bank,
				pbok.id_kat_surat as id_kat_surat,
				pbok.rekening as rekening,
				pbok.ats_nama as ats_nama,
				pbok.kota_aju as kota_aju,
				pbok.tgl_pengajuan as tgl_pengajuan,
				pbok.pengajuan_ttd_1 as aju_ttd1,
				pbok.ttd_2 as aju_ttd2,
				pbok.ttd_3 as aju_ttd3,
				pbok.trf_pajak as trf_pajak,
				pbok.nml_pajak as nml_pajak,
				pbok.pembayar_pajak as pembayar_pajak,
        		(SELECT pbokd.keterangan FROM surat_pbok_detail pbokd WHERE pbok.id_pbok = pbokd.id_pbok LIMIT 1) as keterangan,
				p.nama as pengguna,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')

			->from('surat_list s')
			->join('surat_pbok pbok', 's.id_srt=pbok.id_pbok')
			->join('pengguna p', 'pbok.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pbok.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 4');

		if (!empty($id_pengaju)) {
			$this->datatables->where('pbok.id_pengguna', $id_pengaju);
		}

		return $this->datatables->generate();
	}

	function getAllMyPbok($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
				s.status as stat_persetujuan,
                pbok.id_pbok as idPbok,
				pbok.kode_pbok as kode,
				pbok.bank as bank,
				pbok.id_kat_surat as id_kat_surat,
				pbok.rekening as rekening,
				pbok.ats_nama as ats_nama,
				pbok.kota_aju as kota_aju,
				pbok.pengajuan_ttd_1 as aju_ttd1,
				pbok.ttd_2 as aju_ttd2,
				pbok.ttd_3 as aju_ttd3,
				pbok.trf_pajak as trf_pajak,
				pbok.nml_pajak as nml_pajak,
				pbok.pembayar_pajak as pembayar_pajak,
        		(SELECT pbokd.keterangan FROM surat_pbok_detail pbokd WHERE pbok.id_pbok = pbokd.id_pbok LIMIT 1) as keterangan,
				p.nama as pengguna,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pbok pbok', 's.id_srt=pbok.id_pbok')
			->join('pengguna p', 'pbok.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pbok.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 4')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getPBOKById($id)
	{
		$this->db->select('
                pbok.id_pbok as idPbok,
				pbok.kode_pbok as kodePBOK,
				pbok.id_pengguna as idPengaju,
				pbok.bank as bank,
				pbok.tgl_pengajuan as tgl_pengajuan,
				pbok.alamat as alamat,
				pbokd.qty as qty,
				pbok.mata_uang as kodeMataUang,
				pbokd.total as total,
				pbok.id_kat_surat as id_kat_surat,
				pbok.rekening as rekening,
				pbok.ats_nama as ats_nama,
				pbok.kota_aju as kota_aju,
				pbok.pengajuan_ttd_1 as aju_ttd1,
				pbok.ttd_2 as aju_ttd2,
				pbok.ttd_3 as aju_ttd3,
				pbok.trf_pajak as trf_pajak,
				pbok.nml_pajak as nml_pajak,
				pbok.lampiran as lampiran,
				pbok.pembayar_pajak as pembayar_pajak,
				p.nama as nama,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pbok pbok', 's.id_srt=pbok.id_pbok')
			->join('surat_pbok_detail pbokd', 's.id_srt=pbokd.id_pbok')
			->join('pengguna p', 'pbok.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pbok.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 4')
			->where('pbok.id_pbok', $id);
		return $this->db->get()->result();
	}


	//FUNGSI Surat PKK------------------------------------------------------------------------------------------------------------------------------------
	function getAllPkk()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                pkk.id_pkk as idPKK,
				pkk.kode_pkk as kode,
				pkk.id_kat_surat as id_kat_surat,
				pkk.id_pengguna as idPengaju,
				pkk.type as type,
				pkk.nama_bank as nama_bank,
				pkk.keterangan_pengaju as keterangan_pengaju,
				pkk.no_rek as no_rek,
				pkk.kota_aju as kota_aju,
				pkk.tgl_pengajuan as tgl_pengajuan,
				pkk.ats_nama as ats_nama,
				pkk.pengajuan_ttd_1 as aju_ttd1,
				pkk.ttd_2 as aju_ttd2,
				pkk.ttd_3 as aju_ttd3,
				pkk.ttd_4 as aju_ttd4,
				pkk.lampiran as lampiran,
				pkk.lampiran_finance as lampiran_finance,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pkk pkk', 's.id_srt=pkk.id_pkk')
			->join('pengguna p', 'pkk.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pkk.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 5')
			->generate();
	}
	function getAllPkkEtoll()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                pkk.id_pkk as idPKK,
				pkk.kode_pkk as kode,
				pkk.id_kat_surat as id_kat_surat,
				pkk.id_pengguna as idPengaju,
				pkk.type as type,
				pkk.nama_bank as nama_bank,
				pkk.keterangan_pengaju as keterangan_pengaju,
				pkk.no_rek as no_rek,
				pkk.kota_aju as kota_aju,
				pkk.tgl_pengajuan as tgl_pengajuan,
				pkk.ats_nama as ats_nama,
				pkk.pengajuan_ttd_1 as aju_ttd1,
				pkk.ttd_2 as aju_ttd2,
				pkk.ttd_3 as aju_ttd3,
				pkk.ttd_4 as aju_ttd4,
				pkk.lampiran as lampiran,
				pkk.lampiran_finance as lampiran_finance,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pkketoll pkk', 's.id_srt=pkk.id_pkk')
			->join('pengguna p', 'pkk.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pkk.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 20')
			->generate();
	}

	function getAllMyPkk($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
				s.status as stat_persetujuan,
                pkk.id_pkk as idPKK,
				pkk.kode_pkk as kode,
				pkk.id_kat_surat as id_kat_surat,
				pkk.id_pengguna as idPengaju,
				pkk.type as type,
				pkk.nama_bank as nama_bank,
				pkk.keterangan_pengaju as keterangan_pengaju,
				pkk.no_rek as no_rek,
				pkk.kota_aju as kota_aju,
				pkk.tgl_pengajuan as tgl_pengajuan,
				pkk.ats_nama as ats_nama,
				pkk.pengajuan_ttd_1 as aju_ttd1,
				pkk.ttd_2 as aju_ttd2,
				pkk.ttd_3 as aju_ttd3,
				pkk.ttd_4 as aju_ttd4,
				pkk.lampiran as lampiran,
				pkk.lampiran_finance as lampiran_finance,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pkk pkk', 's.id_srt=pkk.id_pkk')
			->join('pengguna p', 'pkk.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pkk.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 5')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}
	function getAllMyPkkEtoll($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
				s.status as stat_persetujuan,
                pkk.id_pkk as idPKK,
				pkk.kode_pkk as kode,
				pkk.id_kat_surat as id_kat_surat,
				pkk.id_pengguna as idPengaju,
				pkk.type as type,
				pkk.nama_bank as nama_bank,
				pkk.keterangan_pengaju as keterangan_pengaju,
				pkk.no_rek as no_rek,
				pkk.kota_aju as kota_aju,
				pkk.tgl_pengajuan as tgl_pengajuan,
				pkk.ats_nama as ats_nama,
				pkk.pengajuan_ttd_1 as aju_ttd1,
				pkk.ttd_2 as aju_ttd2,
				pkk.ttd_3 as aju_ttd3,
				pkk.ttd_4 as aju_ttd4,
				pkk.lampiran as lampiran,
				pkk.lampiran_finance as lampiran_finance,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pkketoll pkk', 's.id_srt=pkk.id_pkk')
			->join('pengguna p', 'pkk.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pkk.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 20')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getPKKById($id)
	{
		$this->db->select('
                pkk.id_pkk as idPKK,
				pkk.kode_pkk as kodePKK,
				pkk.id_pengguna as idPengaju,
				pkk.id_kat_surat as id_kat_surat,
				pkk.type as type,
				pkk.nama_bank as nama_bank,
				pkk.kota_aju as kota_aju,
				pkk.tgl_pengajuan as tgl_pengajuan,
				pkk.no_rek as no_rek,
				pkk.ats_nama as ats_nama,
				pkk.keterangan_pengaju as keterangan_pengaju,
				pkk.pengajuan_ttd_1 as aju_ttd1,
				pkk.ttd_2 as aju_ttd2,
				pkk.ttd_3 as aju_ttd3,
				pkk.ttd_4 as aju_ttd4,
				pkk.lampiran as lampiran,
				pkk.lampiran_finance as lampiran_finance,
				pkkd.kode_detail as kode_detail,
				pkkd.tanggal as tanggal,
				pkkd.jenis_pengajuan as jenis_pengajuan,
				pkkd.nominal as nominal,
				p.nama as nama,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pkk pkk', 's.id_srt=pkk.id_pkk')
			->join('surat_pkk_detail pkkd', 's.id_srt=pkkd.id_pkk')
			->join('pengguna p', 'pkk.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pkk.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 5')
			->where('pkk.id_pkk', $id);
		return $this->db->get()->result();
	}
	function getPKKEtollById($id)
	{
		$this->db->select('
                pkk.id_pkk as idPKK,
				pkk.kode_pkk as kodePKK,
				pkk.id_pengguna as idPengaju,
				pkk.id_kat_surat as id_kat_surat,
				pkk.type as type,
				pkk.nama_bank as nama_bank,
				pkk.kota_aju as kota_aju,
				pkk.tgl_pengajuan as tgl_pengajuan,
				pkk.no_rek as no_rek,
				pkk.ats_nama as ats_nama,
				pkk.keterangan_pengaju as keterangan_pengaju,
				pkk.pengajuan_ttd_1 as aju_ttd1,
				pkk.ttd_2 as aju_ttd2,
				pkk.ttd_3 as aju_ttd3,
				pkk.ttd_4 as aju_ttd4,
				pkk.lampiran as lampiran,
				pkk.lampiran_finance as lampiran_finance,
				pkkd.kode_detail as kode_detail,
				pkkd.tanggal as tanggal,
				pkkd.jenis_pengajuan as jenis_pengajuan,
				pkkd.nominal as nominal,
				p.nama as nama,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pkketoll pkk', 's.id_srt=pkk.id_pkk')
			->join('surat_pkketoll_detail pkkd', 's.id_srt=pkkd.id_pkk')
			->join('pengguna p', 'pkk.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pkk.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 20')
			->where('pkk.id_pkk', $id);
		return $this->db->get()->result();
	}


	function getDetailPkk($idPKK)
	{
		$this->db->select('
                sPKK.id_pkk as idPKK,
                sPKK.kode_pkk as kodePKK,
				dPKK.ket_detail as ket_detail,
                dPKK.tanggal as tanggal,
                dPKK.jenis_pengajuan as jenis_pengajuan,
                dPKK.nominal as nominal,
                dPKK.mata_uang as mata_uang
            ')
			->from('surat_pkk_detail dPKK')
			->join('surat_pkk sPKK', 'dPKK.id_pkk=sPKK.id_pkk')
			->where('dPKK.id_pkk', $idPKK)
			->where('dPKK.jenis_pengajuan = 1');
		return $this->db->get()->result();
	}
	function getDetailPkkEtoll($idPKK)
	{
		$this->db->select('
                sPKK.id_pkk as idPKK,
                sPKK.kode_pkk as kodePKK,
                dPKK.nokartu as nokartu,
				dPKK.ket_detail as ket_detail,
                dPKK.tanggal as tanggal,
                dPKK.jenis_pengajuan as jenis_pengajuan,
                dPKK.nominal as nominal
            ')
			->from('surat_pkketoll_detail dPKK')
			->join('surat_pkketoll sPKK', 'dPKK.id_pkk=sPKK.id_pkk')
			->where('dPKK.id_pkk', $idPKK)
			->where('dPKK.jenis_pengajuan = 1');
		return $this->db->get()->result();
	}

	function getDetailPkk1($idPKK)
	{
		$this->db->select('
                sPKK.id_pkk as idPKK,
                sPKK.kode_pkk as kodePKK,
				dPKK.ket_detail as ket_detail,
                dPKK.tanggal as tanggal,
                dPKK.jenis_pengajuan as jenis_pengajuan,
                dPKK.nominal as nominal,
                dPKK.mata_uang as mata_uang
            ')
			->from('surat_pkk_detail dPKK')
			->join('surat_pkk sPKK', 'dPKK.id_pkk=sPKK.id_pkk')
			->where('dPKK.id_pkk', $idPKK)
			->where('dPKK.jenis_pengajuan = 2');
		return $this->db->get()->result();
	}
	function getDetailPkkEtoll1($idPKK)
	{
		$this->db->select('
                sPKK.id_pkk as idPKK,
                sPKK.kode_pkk as kodePKK,
                dPKK.nokartu as nokartu,
				dPKK.ket_detail as ket_detail,
                dPKK.tanggal as tanggal,
                dPKK.jenis_pengajuan as jenis_pengajuan,
                dPKK.nominal as nominal
            ')
			->from('surat_pkketoll_detail dPKK')
			->join('surat_pkketoll sPKK', 'dPKK.id_pkk=sPKK.id_pkk')
			->where('dPKK.id_pkk', $idPKK)
			->where('dPKK.jenis_pengajuan = 2');
		return $this->db->get()->result();
	}

	function getDetailPkk2($idPKK)
	{
		$this->db->select('
                sPKK.id_pkk as idPKK,
                sPKK.kode_pkk as kodePKK,
				dPKK.ket_detail as ket_detail,
                dPKK.tanggal as tanggal,
                dPKK.jenis_pengajuan as jenis_pengajuan,
                dPKK.nominal as nominal,
                dPKK.mata_uang as mata_uang
            ')
			->from('surat_pkk_detail dPKK')
			->join('surat_pkk sPKK', 'dPKK.id_pkk=sPKK.id_pkk')
			->where('dPKK.id_pkk', $idPKK)
			->where('dPKK.jenis_pengajuan = 3');
		return $this->db->get()->result();
	}
	function getDetailPkkEtoll2($idPKK)
	{
		$this->db->select('
                sPKK.id_pkk as idPKK,
                sPKK.kode_pkk as kodePKK,
                dPKK.nokartu as nokartu,
				dPKK.ket_detail as ket_detail,
                dPKK.tanggal as tanggal,
                dPKK.jenis_pengajuan as jenis_pengajuan,
                dPKK.nominal as nominal
            ')
			->from('surat_pkketoll_detail dPKK')
			->join('surat_pkketoll sPKK', 'dPKK.id_pkk=sPKK.id_pkk')
			->where('dPKK.id_pkk', $idPKK)
			->where('dPKK.jenis_pengajuan = 3');
		return $this->db->get()->result();
	}

	function getPkkLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id_pkk', "DESC")->get('surat_pkk')->row();
	}

	function getPkkKodeId()
	{
		return $this->db->select("COUNT(*) as id_pkk")->limit(1)->order_by('id_pkk', "DESC")->get_where('surat_pkk', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function getPkkEtollLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id_pkk', "DESC")->get('surat_pkketoll')->row();
	}

	function getPkkEtollKodeId()
	{
		return $this->db->select("COUNT(*) as id_pkk")->limit(1)->order_by('id_pkk', "DESC")->get_where('surat_pkketoll', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}


	function updateSuratPkk($id, $data)
	{
		$this->db->insert('surat_pkk_detail', $data);
	}

	function update_pkk($id, $data)
	{
		$this->db->where('id_pkk', $id);
		$this->db->update('surat_pkk', $data);
	}

	function update_pkketoll($id, $data)
	{
		$this->db->where('id_pkk', $id);
		$this->db->update('surat_pkketoll', $data);
	}

	function updateSuratLampiranPkk($id, $data)
	{
		$this->db->where('id_pkk', $id);
		$this->db->update('surat_pkk', $data);
	}


	// Surat Kunjungan Gudang (KG)----------------------------------------------------------------------------------------------------------------------------
	function getAllKg()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                kg.id as id,
				kg.kode as kode,
				kg.id_kat_surat as id_kat_surat,
				kg.id_pengguna as idPengaju,
				kg.perihal as perihal,
				kg.lampiran as lampiran,
				kg.laporan as laporan,
				kg.tgl_dinas as tgl_dinas,
				kg.tgl_pengajuan as tgl_pengajuan,
				kg.ttd_3 as aju_ttd3,
				kg.persetujuan_ga as persetujuan_ga,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_kunjungan_gudang kg', 's.id_srt=kg.id')
			->join('pengguna p', 'kg.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'kg.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 6')
			->generate();
	}

	function getAllMyKg($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                kg.id as id,
				kg.kode as kode,
				kg.id_kat_surat as id_kat_surat,
				kg.id_pengguna as idPengaju,
				kg.perihal as perihal,
				kg.lampiran as lampiran,
				kg.laporan as laporan,
				kg.tgl_dinas as tgl_dinas,
				kg.tgl_pengajuan as tgl_pengajuan,
				kg.ttd_3 as aju_ttd3,
				kg.persetujuan_ga as persetujuan_ga,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_kunjungan_gudang kg', 's.id_srt=kg.id')
			->join('pengguna p', 'kg.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'kg.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 6')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getKGById($id)
	{
		$this->db->select('
                kg.id as id,
				kg.kode as kode,
				kg.id_kat_surat as id_kat_surat,
				kg.id_pengguna as idPengaju,
				kg.perihal as perihal,
				kg.lampiran as lampiran,
				kg.laporan as laporan,
				kg.tgl_dinas as tgl_dinas,
				kg.tgl_pengajuan as tgl_pengajuan,
				kg.ttd_3 as aju_ttd3,
				kg.persetujuan_ga as persetujuan_ga,
				p.nama as nama,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori,
				s.status as stat_surat
            ')
			->from('surat_list s')
			->join('surat_kunjungan_gudang kg', 's.id_srt=kg.id')
			->join('pengguna p', 'kg.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'kg.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 6')
			->where('kg.id', $id);
		return $this->db->get()->result();
	}

	function getKgLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_kunjungan_gudang')->row();
	}

	function getKgKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_kunjungan_gudang', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}


	function getSimpLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_izin_kerja')->row();
	}

	function update_kg($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_kunjungan_gudang', $data);
	}


	//FUNGSI PENGAJUAN PEMBELIAN DAN PEMELIHARAAN ASET (PPA)
	function getPengajuPpaList()
	{
		$this->db->select('p.pengguna_id, p.nama, p.jabatan')
			->from('pengguna p')
			->where('p.nama IS NOT NULL')
			->where('p.nama !=', '')
			->order_by('p.nama', 'ASC');
		return $this->db->get()->result();
	}

	function getPpaByPengajuId($id_pengaju)
	{
		$this->db->select('
			s.status as stat_persetujuan,
			ppa.id_ppa as idppa,
			ppa.kode_ppa as kode,
			ppa.tgl_pengajuan,
			ppa.bank,
			ppa.rekening,
			ppa.ats_nama,
			ppa.kota_aju,
			ppa.pengajuan_ttd_1 as aju_ttd1,
			ppa.ttd_2 as aju_ttd2,
			ppa.ttd_3 as aju_ttd3,
			ppa.ttd_4 as aju_ttd4,
			ppa.trf_pajak,
			ppa.nml_pajak,
			ppa.pembayar_pajak,
			ppa.lampiran,
			p.nama as pengguna,
			p.jabatan as jabatan
		')
		->from('surat_list s')
		->join('surat_ppa ppa', 's.id_srt=ppa.id_ppa')
		->join('pengguna p', 'ppa.id_pengguna=p.pengguna_id')
		->where('s.id_kat_surat = 7')
		->where('ppa.id_pengguna', $id_pengaju)
		->order_by('ppa.tgl_pengajuan', 'DESC');
		return $this->db->get()->result();
	}

	function getAllPpa($id_pengaju = null)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		$this->datatables
			->select('
				s.status as stat_persetujuan,
                ppa.id_ppa as idppa,
				ppa.kode_ppa as kodePPA,
				ppa.bank as bank,
				ppa.id_kat_surat as id_kat_surat,
				ppa.rekening as rekening,
				ppa.ats_nama as ats_nama,
				ppa.kota_aju as kota_aju,
				ppa.tgl_pengajuan as tgl_pengajuan,
				ppa.pengajuan_ttd_1 as aju_ttd1,
				ppa.ttd_2 as aju_ttd2,
				ppa.ttd_3 as aju_ttd3,
				ppa.ttd_4 as aju_ttd4,
				ppa.ttd_5 as aju_ttd5,
				ppa.trf_pajak as trf_pajak,
				ppa.nml_pajak as nml_pajak,
				ppa.pembayar_pajak as pembayar_pajak,
        		(SELECT ppad.keterangan FROM surat_ppa_detail ppad WHERE ppa.id_ppa = ppad.id_ppa LIMIT 1) as keterangan,
				ppa.catatan as catatan,
				p.nama as pengguna,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_ppa ppa', 's.id_srt=ppa.id_ppa')
			->join('pengguna p', 'ppa.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'ppa.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 7');

		if (!empty($id_pengaju)) {
			$this->datatables->where('ppa.id_pengguna', $id_pengaju);
		}

		return $this->datatables->generate();
	}

	function getPpaById($id)
	{
		$this->db->select('
                ppa.id_ppa as idppa,
				ppa.kode_ppa as kodePPA,
				ppa.id_pengguna as idPengaju,
				ppa.bank as bank,
				ppa.tgl_pengajuan as tgl_pengajuan,
				ppa.alamat as alamat,
				ppa.id_kat_surat as id_kat_surat,
				ppa.rekening as rekening,
				ppa.ats_nama as ats_nama,
				ppa.kota_aju as kota_aju,
				ppa.pengajuan_ttd_1 as aju_ttd1,
				ppa.ttd_2 as aju_ttd2,
				ppa.ttd_3 as aju_ttd3,
				ppa.ttd_4 as aju_ttd4,
				ppa.trf_pajak as trf_pajak,
				ppa.nml_pajak as nml_pajak,
				ppa.lampiran as lampiran,
				ppa.pembayar_pajak as pembayar_pajak,
				ppa.catatan as catatan,
				ppad.qty as qty,
				ppad.total as total,
				ppad.keterangan as keterangan,
				ppad.estimasi as estimasi,
				p.nama as nama,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_ppa ppa', 's.id_srt=ppa.id_ppa')
			->join('surat_ppa_detail ppad', 's.id_srt=ppad.id_ppa')
			->join('pengguna p', 'ppa.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'ppa.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 7')
			->where('ppa.id_ppa', $id);
		return $this->db->get()->result();
	}

	function getDetailPpa($idppa)
	{
		$this->db->select('
                sPPA.id_ppa as idppa,
                sPPA.kode_ppa as kodePPA,
				dPPA.keterangan as keterangan,
				dPPA.estimasi as est,
				dPPA.qty as qty,
				dPPA.total as ttl
            ')
			->from('surat_ppa_detail dPPA')
			->join('surat_ppa sPPA', 'dPPA.id_ppa=sPPA.id_ppa')
			->where('dPPA.id_ppa', $idppa);
		return $this->db->get()->result();
	}

	function getPpaLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id_ppa', "DESC")->get('surat_ppa')->row();
	}


	function getPpaKodeId()
	{
		return $this->db->select("COUNT(*) as id_ppa")->limit(1)->order_by('id_ppa', "DESC")->get_where('surat_ppa', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function getAllMyPpa($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
            	
				s.status as stat_persetujuan,
                ppa.id_ppa as idppa,
				ppa.kode_ppa as kodePPA,
				ppa.bank as bank,
				ppa.id_kat_surat as id_kat_surat,
				ppa.rekening as rekening,
				ppa.ats_nama as ats_nama,
				ppa.kota_aju as kota_aju,
				ppa.pengajuan_ttd_1 as aju_ttd1,
				ppa.ttd_2 as aju_ttd2,
				ppa.ttd_3 as aju_ttd3,
				ppa.ttd_4 as aju_ttd4,
				ppa.trf_pajak as trf_pajak,
				ppa.nml_pajak as nml_pajak,
				ppa.pembayar_pajak as pembayar_pajak,
				ppa.catatan as catatan,
				p.nama as pengguna,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')

			->from('surat_list s')
			->join('surat_ppa ppa', 's.id_srt=ppa.id_ppa')
			->join('pengguna p', 'ppa.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'ppa.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 7')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	//FUNGSI APPROVAL HARGA
	function getAllApproval()
	{
		if ($this->input->post('filter_status'))
			$this->datatables->like('approval.ttd_gm', $this->input->post('filter_status'));


		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                approval.id_approval as id_approval,
				approval.kode as kode,
				approval.id_kat_surat as id_kat_surat,
				approval.id_pengguna as idPengaju,
				approval.tgl as tgl,
				approval.nama as nama,
				approval.nama_customer as nama_customer,
				approval.detail_order as detail_order,
				approval.ttd_gm as ttd_gm,
				approval.pengajuan_ttd_1 as aju_ttd1,
				approval.ttd_2 as aju_ttd2,
				approval.ttd_1 as aju_ttd3,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_approval approval', 's.id_srt=approval.id_approval')
			->join('pengguna p', 'approval.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'approval.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 8')
			->generate();
	}

	function getAllMyApproval($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                approval.id_approval as id_approval,
				approval.kode as kode,
				approval.id_kat_surat as id_kat_surat,
				approval.id_pengguna as idPengaju,
				approval.ttd_gm,
				approval.tgl as tgl,
				approval.nama as nama,
				approval.nama_customer as nama_customer,
				approval.detail_order as detail_order,
				approval.pengajuan_ttd_1 as aju_ttd1,
				approval.ttd_2 as aju_ttd2,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_approval approval', 's.id_srt=approval.id_approval')
			->join('pengguna p', 'approval.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'approval.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 8')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getApprovalById($id)
	{
		$this->db->select('
                approval.id_approval as id_approval,
				approval.kode as kode,
				approval.id_kat_surat as id_kat_surat,
				approval.id_pengguna as idPengaju,
				approval.tgl as tgl,
				approval.nama as nama,
				approval.nama_customer as nama_customer,
				approval.detail_order as detail_order,
				approval.sistem_pembayaran,
				approval.pajak,
				approval.ongkir,
				approval.cicilan,
				approval.ttd_gm,
				approval.pengajuan_ttd_1 as aju_ttd1,
				approval.ttd_2 as aju_ttd2,
				approval.ttd_1 as aju_ttd3,
				approvald.nama_barang as nama_barang,
				approvald.acuan_hrg as acuan_hrg,
				approvald.hrg_ditawarkan as hrg_ditawarkan,
				approvald.approvall as approvall,
				approvald.trf_komisi as trf_komisi,
				approvald.catatan as catatan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_approval approval', 's.id_srt=approval.id_approval')
			->join('surat_approval_detail approvald', 's.id_srt=approvald.id_approval')
			->join('pengguna p', 'approval.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'approval.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 8')
			->where('approval.id_approval', $id);
		return $this->db->get()->result();
	}

	function getDetailApprovalById($id)
	{
		return $this->db->get_where('surat_approval_detail p', array('p.id' => $id))->result();
	}

	function getApprovalByTgl($tglawal, $tglakhir)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->db
			->select('
                approval.id_approval as id_approval,
				approval.kode as kode,
				approval.id_kat_surat as id_kat_surat,
				approval.id_pengguna as idPengaju,
				approval.tgl as tgl,
				approval.nama as nama,
				approval.nama_customer as nama_customer,
				approval.detail_order as detail_order,
				approval.ttd_gm,
				approval.pengajuan_ttd_1 as aju_ttd1,
				approval.ttd_2 as aju_ttd2,
				approval.ttd_1 as aju_ttd3,
				approvald.nama_barang as nama_barang,
				approvald.acuan_hrg as acuan_hrg,
				approvald.hrg_ditawarkan as hrg_ditawarkan,
				approvald.approvall as approvall,
				approvald.trf_komisi as trf_komisi,
				approvald.catatan as catatan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_approval approval', 's.id_srt=approval.id_approval')
			->join('surat_approval_detail approvald', 's.id_srt=approvald.id_approval')
			->join('pengguna p', 'approval.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'approval.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 8')
			->where('approval.tgl >=', date('Y-m-d', strtotime($tglawal)))
			->where('approval.tgl <=', date('Y-m-d', strtotime($tglakhir)))
			->get()
			->result();
	}

	function getDetailApprovaltable($idapproval)
	{
		return $this->datatables->select('
				dAPPROVAL.id,
                sAPPROVAL.id_approval as idapproval,
                sAPPROVAL.kode as kode,
				dAPPROVAL.nama_barang as nama_barang,
				dAPPROVAL.acuan_hrg as acuan_hrg,
				dAPPROVAL.hrg_ditawarkan as hrg_ditawarkan,
				dAPPROVAL.approvall as approvall,
				dAPPROVAL.trf_komisi as trf_komisi,
				dAPPROVAL.catatan as catatan,
            ')
			->from('surat_approval_detail dAPPROVAL')
			->join('surat_approval sAPPROVAL', 'dAPPROVAL.id_approval=sAPPROVAL.id_approval')
			->where('dAPPROVAL.id_approval', $idapproval)
			->generate();
	}

	function getDetailApprovalModaltable($idapproval)
	{
		return $this->datatables->select('
						  	dAPPROVAL.id,
                sAPPROVAL.id_approval as idapproval,
                sAPPROVAL.kode as kode,
				dAPPROVAL.nama_barang as nama_barang,
				dAPPROVAL.acuan_hrg as acuan_hrg,
				dAPPROVAL.hrg_ditawarkan as hrg_ditawarkan,
				dAPPROVAL.modal as modal,
				dAPPROVAL.approvall as approvall,
				dAPPROVAL.trf_komisi as trf_komisi,
				dAPPROVAL.catatan as catatan,
            ')
			->from('surat_approval_detail dAPPROVAL')
			->join('surat_approval sAPPROVAL', 'dAPPROVAL.id_approval=sAPPROVAL.id_approval')
			->where('dAPPROVAL.id_approval', $idapproval)
			->generate();
	}

	function getDetailApprovalrevisicount($idapproval)
	{
		$query =  $this->db
			->select('iddetail, COUNT(iddetail) as total')
			->group_by('iddetail')
			->where('id_approval', $idapproval)
			->order_by('iddetail', 'asc')
			->get('logapproval');
		return $query->result();
	}
	function getDetailApprovalrevisi($iddetail)
	{
		$this->db->order_by('logapproval.id', 'desc');
		return $this->datatables
			->select('
				logapproval.id,
                logapproval.iddetail,
                logapproval.id_approval,
				logapproval.nama_barang,
				logapproval.acuan_hrg,
				logapproval.hrg_ditawarkan,
				logapproval.alasan,
				logapproval.pengguna_id,
				logapproval.created_at
            ')
			->from('logapproval')
			->where('logapproval.iddetail =', $iddetail)
			->generate();
	}
	function getDetailApproval($idapproval)
	{
		$this->db->select('
                dAPPROVAL.id as id,
                sAPPROVAL.id_approval as idapproval,
                sAPPROVAL.kode as kode,
				dAPPROVAL.nama_barang as nama_barang,
				dAPPROVAL.acuan_hrg as acuan_hrg,
				dAPPROVAL.hrg_ditawarkan as hrg_ditawarkan,
				masterjenisapproval.keterangan as modal,
				dAPPROVAL.approvall as approvall,
				dAPPROVAL.trf_komisi as trf_komisi,
				dAPPROVAL.catatan as catatan,
            ')
			->from('surat_approval_detail dAPPROVAL')
			->join('surat_approval sAPPROVAL', 'dAPPROVAL.id_approval=sAPPROVAL.id_approval')
			->join('masterjenisapproval', 'masterjenisapproval.id=dAPPROVAL.modal', 'left')
			->where('dAPPROVAL.id_approval', $idapproval);
		return $this->db->get()->result();
	}

	function getDetailApprovalModalApproval($idapproval)
	{
		$this->db->select('
							 dAPPROVAL.id as id,
                sAPPROVAL.id_approval as idapproval,
                sAPPROVAL.kode as kode,
				dAPPROVAL.nama_barang as nama_barang,
				dAPPROVAL.acuan_hrg as acuan_hrg,
				dAPPROVAL.hrg_ditawarkan as hrg_ditawarkan,
				dAPPROVAL.modal as modal,
				dAPPROVAL.approvall as approvall,
				dAPPROVAL.trf_komisi as trf_komisi,
				dAPPROVAL.catatan as catatan
            ')
			->from('surat_approval_detail dAPPROVAL')
			->join('surat_approval sAPPROVAL', 'dAPPROVAL.id_approval=sAPPROVAL.id_approval')
			->where('dAPPROVAL.id_approval', $idapproval)
			->where('dAPPROVAL.modal>0');
		return $this->db->get()->result();
	}

	function getApprovalLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id_approval', "DESC")->get('surat_approval')->row();
	}

	function getApprovalKodeId()
	{
		return $this->db->select("COUNT(*) as id_approval")->limit(1)->order_by('id_approval', "DESC")->get_where('surat_approval', array('YEAR(`tgl`)' => date('Y')))->row();
	}

	function addlogeditapproval($data)
	{
		$datalog = array(
			'id_approval' => $data['id_approval'],
			'iddetail' => $data['iddetail'],
			'nama_barang' => $data['namabarangsebelum'],
			'acuan_hrg' => $data['acuanhargasebelum'],
			'hrg_ditawarkan' => $data['hargasebelum'],
			'alasan' => $data['alasan'],
			'pengguna_id' => $data['pengguna_id']
		);
		$this->db->insert('logapproval', $datalog);
	}

	function update_approval($id, $data)
	{
		$this->db->where('id_approval', $id);
		$this->db->update('surat_approval', $data);
	}

	function updateSuratDetailApproval($id, $data)
	{
		$datadetail = array(
			'nama_barang' => $data['nama_barang'],
			'acuan_hrg' => $data['acuan_hrg'],
			'hrg_ditawarkan' => $data['hrg_ditawarkan']
		);

		$this->db->where('id', $id);
		$this->db->where('id_approval', $data['id_approval']);
		$this->db->update('surat_approval_detail', $datadetail);
	}

	function updateSuratApprovall($id, $data)
	{
		$this->db->where('id_approval', $id);
		$this->db->update('surat_approval_detail', $data);
	}

	function updateSuratApprovallDetail($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_approval_detail', $data);
	}

	function update_ppa($id, $data)
	{
		$this->db->where('id_ppa', $id);
		$this->db->update('surat_ppa', $data);
	}

	function updateSuratPpa($id, $data)
	{
		$this->db->where('id_ppa', $id);
		$this->db->update('surat_ppa', $data);
	}

	//Fungsi Permintaan Dinas
	function getAllPd()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                pd.id_pd as id_pd,
				pd.kode as kode,
				pd.id_kat_surat as id_kat_surat,
				pd.id_pengguna as idPengaju,
				pd.tgl_pengajuan as tgl_pengajuan,
				pd.kota_aju as kota_aju,
				pd.wilayah_dinas as wilayah_dinas,
				pd.lama_dinas as lama_dinas,
				pd.transportasi as transportasi,
				pd.jenis_kendaraan as jenis_kendaraan,
				pd.no_polisi as no_polisi,
				pd.lampiran as lampiran,
				pd.persetujuan_ga as persetujuan_ga,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pd pd', 's.id_srt=pd.id_pd')
			->join('pengguna p', 'pd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 9')
			->generate();
	}


	function getPdById($id)
	{
		$this->db->select('
                pd.id_pd as id_pd,
				pd.kode as kode,
				pd.id_kat_surat as id_kat_surat,
				pd.id_pengguna as idPengaju,
				pd.tgl_pengajuan as tgl_pengajuan,
				pd.kota_aju as kota_aju,
				pd.wilayah_dinas as wilayah_dinas,
				pd.lama_dinas as lama_dinas,
				pd.transportasi as transportasi,
				pd.jenis_kendaraan as jenis_kendaraan,
				pd.no_polisi as no_polisi,
				pd.lampiran as lampiran,
				pd.mulai as mulai,
				pd.akhir as akhir,
				pd.tujuan as tujuan,
				pd.id_surat_dinas as id_surat_dinas,
				pd.persetujuan_ga as persetujuan_ga,
				pdd.hari as hari,
				pdd.tgl as tgl,
				pdd.nama_customer as nama_customer,
				pdd.tujuan_dinas as tujuan_dinas,
				pdd.kota as kota,
				pdd.pic as pic,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pd pd', 's.id_srt=pd.id_pd')
			->join('surat_pd_detail pdd', 's.id_srt=pdd.id_pd')
			->join('pengguna p', 'pd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 9')
			->where('pd.id_pd', $id);
		return $this->db->get()->result();
	}

	function getDetailPd($idpd)
	{
		$this->db->select('
                spd.id_pd as id_pd,
                spd.kode as kode,
				dpd.hari as hari,
				dpd.tgl as tgl,
				dpd.nama_customer as nama_customer,
				dpd.tujuan_dinas as tujuan_dinas,
				dpd.kota as kota,
				dpd.pic as pic,
            ')
			->from('surat_pd_detail dpd')
			->join('surat_pd spd', 'dpd.id_pd=spd.id_pd')
			->where('dpd.id_pd', $idpd);
		return $this->db->get()->result();
	}

	function getAllMyPd($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                pd.id_pd as id_pd,
				pd.kode as kode,
				pd.id_kat_surat as id_kat_surat,
				pd.id_pengguna as idPengaju,
				pd.tgl_pengajuan as tgl_pengajuan,
				pd.kota_aju as kota_aju,
				pd.wilayah_dinas as wilayah_dinas,
				pd.lama_dinas as lama_dinas,
				pd.transportasi as transportasi,
				pd.jenis_kendaraan as jenis_kendaraan,
				pd.no_polisi as no_polisi,
				pd.lampiran as lampiran,
				pd.persetujuan_ga as persetujuan_ga,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pd pd', 's.id_srt=pd.id_pd')
			->join('pengguna p', 'pd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 9')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getPdLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id_pd', "DESC")->get('surat_pd')->row();
	}

	function getPdKodeId()
	{
		return $this->db->select("COUNT(*) as id_pd")->limit(1)->order_by('id_pd', "DESC")->get_where('surat_pd', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}


	function updateSuratPd($id, $data)
	{
		$this->db->where('id_pd', $id);
		$this->db->update('surat_pd', $data);
	}

	function update_pd($id, $data)
	{
		$this->db->where('id_pd', $id);
		$this->db->update('surat_pd', $data);
	}

	//Fungsi Permintaan Dinas Karyawan
	function getAllMyPdKaryawan($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                pd.id_pd as id_pd,
				pd.kode as kode,
				pd.id_kat_surat as id_kat_surat,
				pd.id_pengguna as idPengaju,
				pd.tgl_pengajuan as tgl_pengajuan,
				pd.kota_aju as kota_aju,
				pd.wilayah_dinas as wilayah_dinas,
				pd.lama_dinas as lama_dinas,
				pd.transportasi as transportasi,
				pd.jenis_kendaraan as jenis_kendaraan,
				pd.no_polisi as no_polisi,
				pd.lampiran as lampiran,
				pd.persetujuan_ga as persetujuan_ga,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pd pd', 's.id_srt=pd.id_pd')
			->join('pengguna p', 'pd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 12')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getPdKaryawanById($id)
	{
		$this->db->select('
                pd.id_pd as id_pd,
				pd.kode as kode,
				pd.id_kat_surat as id_kat_surat,
				pd.id_pengguna as idPengaju,
				pd.tgl_pengajuan as tgl_pengajuan,
				pd.kota_aju as kota_aju,
				pd.wilayah_dinas as wilayah_dinas,
				pd.lama_dinas as lama_dinas,
				pd.transportasi as transportasi,
				pd.jenis_kendaraan as jenis_kendaraan,
				pd.no_polisi as no_polisi,
				pd.lampiran as lampiran,
				pd.mulai as mulai,
				pd.akhir as akhir,
				pd.tujuan as tujuan,
				pd.id_surat_dinas as id_surat_dinas,
				pd.persetujuan_ga as persetujuan_ga,
				pdd.hari as hari,
				pdd.tgl as tgl,
				pdd.nama_customer as nama_customer,
				pdd.tujuan_dinas as tujuan_dinas,
				pdd.kota as kota,
				pdd.pic as pic,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pd pd', 's.id_srt=pd.id_pd')
			->join('surat_pd_detail pdd', 's.id_srt=pdd.id_pd')
			->join('pengguna p', 'pd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 12')
			->where('pd.id_pd', $id);
		return $this->db->get()->result();
	}

	function getDetailPdKaryawan($idpd)
	{
		$this->db->select('
                spd.id_pd as id_pd,
                spd.kode as kode,
				dpd.hari as hari,
				dpd.tgl as tgl,
				dpd.nama_customer as nama_customer,
				dpd.tujuan_dinas as tujuan_dinas,
				dpd.kota as kota,
				dpd.pic as pic,
            ')
			->from('surat_pd_detail dpd')
			->join('surat_pd spd', 'dpd.id_pd=spd.id_pd')
			->where('dpd.id_pd', $idpd);
		return $this->db->get()->result();
	}

	function getAllPdKaryawan()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                pd.id_pd as id_pd,
				pd.kode as kode,
				pd.id_kat_surat as id_kat_surat,
				pd.id_pengguna as idPengaju,
				pd.tgl_pengajuan as tgl_pengajuan,
				pd.kota_aju as kota_aju,
				pd.wilayah_dinas as wilayah_dinas,
				pd.lama_dinas as lama_dinas,
				pd.transportasi as transportasi,
				pd.jenis_kendaraan as jenis_kendaraan,
				pd.no_polisi as no_polisi,
				pd.lampiran as lampiran,
				pd.persetujuan_ga as persetujuan_ga,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pd pd', 's.id_srt=pd.id_pd')
			->join('pengguna p', 'pd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 12')
			->generate();
	}

	//Fungsi Permintaan Dinas Teknisi
	function getAllPdt()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                pd.id_pd as id_pd,
				pd.kode as kode,
				pd.id_kat_surat as id_kat_surat,
				pd.id_pengguna as idPengaju,
				pd.tgl_pengajuan as tgl_pengajuan,
				pd.kota_aju as kota_aju,
				pd.wilayah_dinas as wilayah_dinas,
				pd.lama_dinas as lama_dinas,
				pd.transportasi as transportasi,
				pd.jenis_kendaraan as jenis_kendaraan,
				pd.no_polisi as no_polisi,
				pd.lampiran as lampiran,
				pd.persetujuan_ga as persetujuan_ga,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pdt pd', 's.id_srt=pd.id_pd')
			->join('pengguna p', 'pd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 11')
			->generate();
	}
	// 		function update_pdt($id, $data)
	//     {
	//         $this->db->where('id_pd', $id);
	//         $this->db->update('surat_pdt', $data);
	//     }
	function getPdtById($id)
	{
		$this->db->select('
                pd.id_pd as id_pd,
				pd.kode as kode,
				pd.id_kat_surat as id_kat_surat,
				pd.id_pengguna as idPengaju,
				pd.tgl_pengajuan as tgl_pengajuan,
				pd.kota_aju as kota_aju,
				pd.wilayah_dinas as wilayah_dinas,
				pd.lama_dinas as lama_dinas,
				pd.transportasi as transportasi,
				pd.jenis_kendaraan as jenis_kendaraan,
				pd.no_polisi as no_polisi,
				pd.lampiran as lampiran,
				pd.mulai as mulai,
				pd.akhir as akhir,
				pd.id_surat_dinas as id_surat_dinas,
				pd.tujuan as tujuan,
				pd.persetujuan_ga as persetujuan_ga,
				pdd.hari as hari,
				pdd.tgl as tgl,
				pdd.nama_customer as nama_customer,
				pdd.tujuan_dinas as tujuan_dinas,
				pdd.kota as kota,
				pdd.pic as pic,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pdt pd', 's.id_srt=pd.id_pd')
			->join('surat_pdt_detail pdd', 's.id_srt=pdd.id_pd')
			->join('pengguna p', 'pd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 11')
			->where('pd.id_pd', $id);
		return $this->db->get()->result();
	}

	function getDetailPdt($idpd)
	{
		$this->db->select('
                spd.id_pd as id_pd,
                spd.kode as kode,
				dpd.hari as hari,
				dpd.tgl as tgl,
				dpd.nama_customer as nama_customer,
				dpd.tujuan_dinas as tujuan_dinas,
				dpd.kota as kota,
				dpd.pic as pic,
            ')
			->from('surat_pdt_detail dpd')
			->join('surat_pdt spd', 'dpd.id_pd=spd.id_pd')
			->where('dpd.id_pd', $idpd);
		return $this->db->get()->result();
	}

	function getAllMyPdt($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                pd.id_pd as id_pd,
				pd.kode as kode,
				pd.id_kat_surat as id_kat_surat,
				pd.id_pengguna as idPengaju,
				pd.tgl_pengajuan as tgl_pengajuan,
				pd.kota_aju as kota_aju,
				pd.wilayah_dinas as wilayah_dinas,
				pd.lama_dinas as lama_dinas,
				pd.transportasi as transportasi,
				pd.jenis_kendaraan as jenis_kendaraan,
				pd.no_polisi as no_polisi,
				pd.lampiran as lampiran,
				pd.persetujuan_ga as persetujuan_ga,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pdt pd', 's.id_srt=pd.id_pd')
			->join('pengguna p', 'pd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'pd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 11')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getPdtLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id_pd', "DESC")->get('surat_pdt')->row();
	}

	function getPdtKodeId()
	{
		return $this->db->select("COUNT(*) as id_pd")->limit(1)->order_by('id_pd', "DESC")->get_where('surat_pdt', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function updateSuratPdt($id, $data)
	{
		$this->db->where('id_pd', $id);
		$this->db->update('surat_pdt', $data);
	}

	// 	function update_pd($id, $data)
	//     {
	//         $this->db->where('id_pd', $id);
	//         $this->db->update('surat_pdt', $data);
	//     }
	// Surat Dinas (SD)
	function getAllSd()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                sd.id as id,
				sd.kode as kode,
				sd.id_kat_surat as id_kat_surat,
				sd.id_pengguna as idPengaju,
				sd.perihal as perihal,
				sd.lampiran as lampiran,
				sd.tgl_dinas as tgl_dinas,
				sd.tgl_dinas_akhir as tgl_dinas_akhir,
				sd.tgl_pengajuan as tgl_pengajuan,
				sd.ttd_3 as aju_ttd3,
				sd.persetujuan_ga as persetujuan_ga,
				sd.laporan as laporan,
				sd.lokasi_dinas as lokasi_dinas,
				sd.nama_karyawan as nama_karyawan,
				sd.jabatan_karyawan as jabatan_karyawan,
				sd.nik_karyawan as nik_karyawan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pd_dinas sd', 's.id_srt=sd.id')
			->join('pengguna p', 'sd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 10')
			->generate();
	}

	function getAllMySd($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                sd.id as id,
				sd.kode as kode,
				sd.id_kat_surat as id_kat_surat,
				sd.id_pengguna as idPengaju,
				sd.perihal as perihal,
				sd.lampiran as lampiran,
				sd.tgl_dinas as tgl_dinas,
				sd.tgl_dinas_akhir as tgl_dinas_akhir,
				sd.tgl_pengajuan as tgl_pengajuan,
				sd.ttd_3 as aju_ttd3,
				sd.persetujuan_ga as persetujuan_ga,
				sd.laporan as laporan,
				sd.lokasi_dinas as lokasi_dinas,
				sd.nama_karyawan as nama_karyawan,
				sd.jabatan_karyawan as jabatan_karyawan,
				sd.nik_karyawan as nik_karyawan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pd_dinas sd', 's.id_srt=sd.id')
			->join('pengguna p', 'sd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 10')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getSDById($id)
	{
		$this->db->select('
                sd.id as id,
				sd.kode as kode,
				sd.id_kat_surat as id_kat_surat,
				sd.id_pengguna as idPengaju,
				sd.perihal as perihal,
				sd.lampiran as lampiran,
				sd.lampiran_2 as lampiran_2,
				sd.tgl_dinas as tgl_dinas,
				sd.tgl_dinas_akhir as tgl_dinas_akhir,
				sd.tgl_pengajuan as tgl_pengajuan,
				sd.ttd_3 as aju_ttd3,
				sd.persetujuan_ga as persetujuan_ga,
				sd.laporan as laporan,
				sd.lokasi_dinas as lokasi_dinas,
				sd.nama_karyawan as nama_karyawan,
				sd.jabatan_karyawan as jabatan_karyawan,
				sd.nik_karyawan as nik_karyawan,
				p.nama as nama,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pd_dinas sd', 's.id_srt=sd.id')
			->join('pengguna p', 'sd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 10')
			->where('sd.id', $id);
		return $this->db->get()->result();
	}

	function getSdLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_pd_dinas')->row();
	}

	function getSdKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_pd_dinas', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function update_sd($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_pd_dinas', $data);
	}
	function updatesuratdinasteknisi($id, $data)
	{
		$this->db->where('id_pd', $id);
		$this->db->update('surat_pdt', $data);
	}

	//ini dia
	// Surat Dinas Teknisi (SDT)
	function getAllSdt()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                sd.id as id,
				sd.kode as kode,
				sd.id_kat_surat as id_kat_surat,
				sd.id_pengguna as idPengaju,
				sd.perihal as perihal,
				sd.lampiran as lampiran,
				sd.tgl_dinas as tgl_dinas,
				sd.tgl_pengajuan as tgl_pengajuan,
				sd.ttd_3 as aju_ttd3,
				sd.persetujuan_ga as persetujuan_ga,
				sd.laporan as laporan,
				sd.lokasi_dinas as lokasi_dinas,
				sd.nama_karyawan as nama_karyawan,
				sd.jabatan_karyawan as jabatan_karyawan,
				sd.nik_karyawan as nik_karyawan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pdt_dinas sd', 's.id_srt=sd.id')
			->join('pengguna p', 'sd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 11')
			->generate();
	}
	function getAllMySdt($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                sd.id as id,
				sd.kode as kode,
				sd.id_kat_surat as id_kat_surat,
				sd.id_pengguna as idPengaju,
				sd.perihal as perihal,
				sd.lampiran as lampiran,
				sd.tgl_dinas as tgl_dinas,
				sd.tgl_pengajuan as tgl_pengajuan,
				sd.ttd_3 as aju_ttd3,
				sd.persetujuan_ga as persetujuan_ga,
				sd.laporan as laporan,
				sd.lokasi_dinas as lokasi_dinas,
				sd.nama_karyawan as nama_karyawan,
				sd.jabatan_karyawan as jabatan_karyawan,
				sd.nik_karyawan as nik_karyawan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pdt_dinas sd', 's.id_srt=sd.id')
			->join('pengguna p', 'sd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 10')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getSdtById($id)
	{
		$this->db->select('
                sd.id as id,
				sd.kode as kode,
				sd.id_kat_surat as id_kat_surat,
				sd.id_pengguna as idPengaju,
				sd.perihal as perihal,
				sd.lampiran as lampiran,
				sd.tgl_dinas as tgl_dinas,
				sd.tgl_pengajuan as tgl_pengajuan,
				sd.ttd_3 as aju_ttd3,
				sd.persetujuan_ga as persetujuan_ga,
				sd.laporan as laporan,
				sd.lokasi_dinas as lokasi_dinas,
				sd.nama_karyawan as nama_karyawan,
				sd.jabatan_karyawan as jabatan_karyawan,
				sd.nik_karyawan as nik_karyawan,
				p.nama as nama,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_pdt_dinas sd', 's.id_srt=sd.id')
			->join('pengguna p', 'sd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 11')
			->where('sd.id', $id);
		return $this->db->get()->result();
	}

	function getSdtLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_pdt_dinas')->row();
	}

	function update_sdt($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_pdt_dinas', $data);
	}

	//Fungsi Surat Serah Terima Pekerjaan

	function getAllSpt()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                spt.idt as idt,
				spt.kode as kode,
				spt.id_kat_surat as id_kat_surat,
				spt.id_pengguna as idPengaju,
				spt.tgl_pengajuan as tgl_pengajuan,
				spt.hari as hari,
				spt.tempat as tempat,
				spt.nama as nama,
				spt.npp as npp,
				spt.jabatan as jabatan,
				spt.alamat as alamat,
				spt.nama2 as nama2,
				spt.npp2 as npp2,
				spt.jabatan2 as jabatan2,
				spt.alamat2 as alamat2,
				spt.lampiran as lampiran,
				spt.persetujuan_ga as persetujuan_ga,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_spt spt', 's.id_srt=spt.idt')
			->join('pengguna p', 'spt.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'spt.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 14')
			->generate();
	}

	function getSptById($id)
	{
		$this->db->select('
                spt.idt as idt,
				spt.kode as kode,
				spt.id_kat_surat as id_kat_surat,
				spt.id_pengguna as idPengaju,
				spt.tgl_pengajuan as tgl_pengajuan,
				spt.hari as hari,
				spt.tempat as tempat,
				spt.nama as nama,
				spt.npp as npp,
				spt.jabatan as jabatan,
				spt.alamat as alamat,
				spt.nama2 as nama2,
				spt.npp2 as npp2,
				spt.jabatan2 as jabatan2,
				spt.alamat2 as alamat2,
				spt.lampiran as lampiran,
				spt.persetujuan_ga as persetujuan_ga,
				sptd.isi as isi,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_spt spt', 's.id_srt=spt.idt')
			->join('surat_spt_detail sptd', 's.id_srt=sptd.idt')
			->join('pengguna p', 'spt.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'spt.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 14')
			->where('spt.idt', $id);
		return $this->db->get()->result();
	}

	function getDetailSpt($idspt)
	{
		$this->db->select('
                sspt.idt as idt,
                sspt.kode as kode,
				dspt.isi as isi,
            ')
			->from('surat_spt_detail dspt')
			->join('surat_spt sspt', 'dspt.idt=sspt.idt')
			->where('dspt.idt', $idspt);
		return $this->db->get()->result();
	}

	function getAllMySpt($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                spt.idt as idt,
				spt.kode as kode,
				spt.id_kat_surat as id_kat_surat,
				spt.id_pengguna as idPengaju,
				spt.tgl_pengajuan as tgl_pengajuan,
				spt.hari as hari,
				spt.tempat as tempat,
				spt.nama as nama,
				spt.npp as npp,
				spt.jabatan as jabatan,
				spt.alamat as alamat,
				spt.nama2 as nama2,
				spt.npp2 as npp2,
				spt.jabatan2 as jabatan2,
				spt.alamat2 as alamat2,
				spt.lampiran as lampiran,
				spt.persetujuan_ga as persetujuan_ga,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_spt spt', 's.id_srt=spt.idt')
			->join('pengguna p', 'spt.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'spt.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 14')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getSptLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('idt', "DESC")->get('surat_spt')->row();
	}

	function UpdateSuratSpt($id, $data)
	{
		$this->db->where('idt', $id);
		$this->db->usptate('surat_spt', $data);
	}

	function usptate_Spt($id, $data)
	{
		$this->db->where('idt', $id);
		$this->db->usptate('surat_spt', $data);
	}

	//surat peringatan
	function getAllSp()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                sp.id as id,
				sp.kode as kode,
				sp.perihal as perihal,
				sp.id_kat_surat as id_kat_surat,
				sp.id_pengguna as idPengaju,
				sp.nama as nama,
				sp.jabatan as jabatan1,
				sp.tgl_evaluasi as tgl_evaluasi,
				sp.tgl_salah as tgl_salah,
				sp.kesalahan as kesalahan,
				sp.jenis_sp as jenis_sp,
				sp.sanksi as sanksi,
				sp.masa as masa,
				sp.cara as cara,
				sp.tembusan as tembusan,
				sp.tgl_pengajuan as tgl_pengajuan,
				sp.ttd_3 as ttd_3,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_peringatan sp', 's.id_srt=sp.id')
			->join('pengguna p', 'sp.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sp.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 14')
			->generate();
	}

	function getAllMySp($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                sp.id as id,
				sp.kode as kode,
				sp.perihal as perihal,
				sp.id_kat_surat as id_kat_surat,
				sp.id_pengguna as idPengaju,
				sp.tgl_evaluasi as tgl_evalusi,
				sp.nama as nama,
				sp.jabatan as jabatan1,
				sp.tgl_salah as tgl_salah,
				sp.kesalahan as kesalahan,
				sp.jenis_sp as jenis_sp,
				sp.sanksi as sanksi,
				sp.masa as masa,
				sp.cara as cara,
				sp.tembusan as tembusan,
				sp.tgl_pengajuan as tgl_pengajuan,
				sp.ttd_3 as ttd_3,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_peringatan sp', 's.id_srt=sp.id')
			->join('pengguna p', 'sp.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sp.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 14')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getSPById($id)
	{
		$this->db->select('
                sp.id as id,
				sp.kode as kode,
				sp.perihal as perihal,
				sp.id_kat_surat as id_kat_surat,
				sp.id_pengguna as idPengaju,
				sp.tgl_evaluasi as tgl_evaluasi,
				sp.nama as nama,
				sp.jabatan as jabatan1,
				sp.tgl_salah as tgl_salah,
				sp.kesalahan as kesalahan,
				sp.jenis_sp as jenis_sp,
				sp.sanksi as sanksi,
				sp.masa as masa,
				sp.cara as cara,
				sp.tembusan as tembusan,
				sp.tgl_pengajuan as tgl_pengajuan,
				sp.ttd_3 as ttd_3,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori,
				p2.nama as namasp,
				p2.jabatan as jabatansp
            ')
			->from('surat_list s')
			->join('surat_peringatan sp', 's.id_srt=sp.id')
			->join('pengguna p', 'sp.id_pengguna=p.pengguna_id')
			->join('pengguna p2', 'sp.nama=p2.pengguna_id')
			->join('surat_kategori sKat', 'sp.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 14')
			->where('sp.id', $id);
		return $this->db->get()->result();
	}

	function getSpLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_peringatan')->row();
	}

	function getSpKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_peringatan', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function update_sp($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_peringatan', $data);
	}

	//end

	//SURAT TUGAS
	function getSTById($id)
	{
		$this->db->select('
                st.id as id,
				st.kode as kode,
				st.id_kat_surat as id_kat_surat,
				st.id_pengguna as idPengaju,
				st.perihal as perihal,
				st.lampiran as lampiran,
				st.ttd_3 as aju_ttd3,
				st.dasar as dasar,
				st.nama_kyw as nama_kyw,
				st.nip as nip,
				st.keterangan as keterangan,
				st.tgl_pengajuan as tgl_pengajuan,
				p.nama as nama,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_tugas st', 's.id_srt=st.id')
			->join('pengguna p', 'st.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'st.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 15')
			->where('st.id', $id);
		return $this->db->get()->result();
	}

	function getStLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_tugas')->row();
	}

	function getAllMySt($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                st.id as id,
				st.kode as kode,
				st.id_kat_surat as id_kat_surat,
				st.id_pengguna as idPengaju,
				st.perihal as perihal,
				st.lampiran as lampiran,
				st.ttd_3 as aju_ttd3,
				st.dasar as dasar,
				st.nama_kyw as nama_kyw,
				st.nip as nip,
				st.keterangan as keterangan,
				st.tgl_pengajuan as tgl_pengajuan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_tugas st', 's.id_srt=st.id')
			->join('pengguna p', 'st.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'st.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 15')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getAllSt()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                st.id as id,
				st.kode as kode,
				st.id_kat_surat as id_kat_surat,
				st.id_pengguna as idPengaju,
				st.perihal as perihal,
				st.lampiran as lampiran,
				st.ttd_3 as aju_ttd3,
				st.dasar as dasar,
				st.nama_kyw as nama_kyw,
				st.nip as nip,
				st.keterangan as keterangan,
				st.tgl_pengajuan as tgl_pengajuan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_tugas st', 's.id_srt=st.id')
			->join('pengguna p', 'st.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'st.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 15')
			->generate();
	}

	function update_st($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_tugas', $data);
	}

	//end SURAT TUGAS
	//SURAT Keputusan Direksi
	function getSkdById($id)
	{
		$this->db->select('
                skd.id as id,
				skd.kode as kode,
				skd.id_kat_surat as id_kat_surat,
				skd.id_pengguna as id_pengguna,
				skd.perihal as perihal,
				skd.isi1 as isi1,
				skd.isi2 as isi2,
					skd.tgl_pengajuan as tgl_pengajuan,
				skd.ttd_persetujuan as ttd_persetujuan,
				p.nama as nama,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_direksi skd', 's.id_srt=skd.id')
			->join('pengguna p', 'skd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'skd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 16')
			->where('skd.id', $id);
		return $this->db->get()->result();
	}

	function getSkdLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_direksi')->row();
	}

	function getAllMySkd($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
               	skd.id as id,
				skd.kode as kode,
				skd.id_kat_surat as id_kat_surat,
				skd.id_pengguna as id_pengguna,
				skd.perihal as perihal,
				skd.isi1 as isi1,
				skd.isi2 as isi2,
				skd.tgl_pengajuan as tgl_pengajuan,
				skd.ttd_persetujuan as ttd_persetujuan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_direksi skd', 's.id_srt=skd.id')
			->join('pengguna p', 'skd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'skd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 16')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getAllSkd()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                skd.id as id,
				skd.kode as kode,
				skd.id_kat_surat as id_kat_surat,
				skd.id_pengguna as id_pengguna,
				skd.perihal as perihal,
				skd.isi1 as isi1,
				skd.isi2 as isi2,
				skd.ttd_persetujuan as ttd_persetujuan,
				skd.tgl_pengajuan as tgl_pengajuan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_direksi skd', 's.id_srt=skd.id')
			->join('pengguna p', 'skd.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'skd.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 16')
			->generate();
	}

	function update_skd($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_direksi', $data);
	}

	//end SURAT TUGAS

	//SURAT REKOMENDASI
	function getREKOMByIdNew($id)
	{
		$this->db->select('
                rekom.id as id,
				rekom.id_kat_surat as id_kat_surat,
				rekom.kode as kode,
				rekom.perihal as perihal,
				rekom.id_pengguna as idPengaju,
				rekom.ttd_1 as aju_ttd1,
				rekom.ttd_2 as aju_ttd2,
				rekom.keterangan_1 as keterangan_1,
				rekom.keterangan_2 as keterangan_2,
				rekom.nama_kyw as nama_kyw,
				rekom.jabatan as jabatan1,
				rekom.dasar as dasar,
				rekom.tgl as tgl,
				rekom.perubahan as perubahan,
				rekom.tgl_pengajuan as tgl_pengajuan,
				p.nama as nama,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_rekom rekom', 's.id_srt=rekom.id')
			->join('pengguna p', 'rekom.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'rekom.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 17')
			->where('rekom.id', $id);
		return $this->db->get()->result();
	}

	function getREKOMById($id)
	{
		$this->db->select('
                rekom.id as id,
				rekom.id_kat_surat as id_kat_surat,
				rekom.kode as kode,
				rekom.perihal as perihal,
				rekom.id_pengguna as idPengaju,
				rekom.ttd_1 as aju_ttd1,
				rekom.ttd_2 as aju_ttd2,
				rekom.keterangan_1 as keterangan_1,
				rekom.keterangan_2 as keterangan_2,
				rekom.nama_kyw as nama_kyw,
				rekom.jabatan as jabatan1,
				rekom.dasar as dasar,
				rekom.tgl as tgl,
				rekom.jumlah as jumlahkar,
				rekom.idkar1 as idkar1,
				rekom.idkar2 as idkar2,
				rekom.idkar3 as idkar3,
				rekom.idkar4 as idkar4,
				rekom.idkar5 as idkar5,
				rekom.idkar6 as idkar6,
				rekom.idkar7 as idkar7,
				rekom.idkar8 as idkar8,
				rekom.idkar9 as idkar9,
				rekom.idkar10 as idkar10,
				rekom.lampiran as lampiran,
				rekom.perubahan as perubahan,
				rekom.tgl_pengajuan as tgl_pengajuan,
				p.nama as nama,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.no_pegawai as npp,
				
				p1.nama as nama1,
				p1.jabatan as jabatan11,
				p1.no_pegawai as npp1,
				p2.nama as nama2,
				p2.jabatan as jabatan2,
				p2.no_pegawai as npp2,
				p3.nama as nama3,
				p3.jabatan as jabatan3,
				p3.no_pegawai as npp3,
				p4.nama as nama4,
				p4.jabatan as jabatan4,
				p4.no_pegawai as npp4,
				p5.nama as nama5,
				p5.jabatan as jabatan5,
				p5.no_pegawai as npp5,
				p6.nama as nama6,
				p6.jabatan as jabatan6,
				p6.no_pegawai as npp6,
				p7.nama as nama7,
				p7.jabatan as jabatan7,
				p7.no_pegawai as npp7,
				p8.nama as nama8,
				p8.jabatan as jabatan8,
				p8.no_pegawai as npp8,
				p9.nama as nama9,
				p9.jabatan as jabatan9,
				p9.no_pegawai as npp9,
				p10.nama as nama10,
				p10.jabatan as jabatan10,
				p10.no_pegawai as npp10,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_rekom rekom', 's.id_srt=rekom.id')
			->join('pengguna p', 'rekom.id_pengguna=p.pengguna_id', 'left')
			->join('pengguna p1', 'rekom.idkar1=p1.pengguna_id', 'left')
			->join('pengguna p2', 'rekom.idkar2=p2.pengguna_id', 'left')
			->join('pengguna p3', 'rekom.idkar3=p3.pengguna_id', 'left')
			->join('pengguna p4', 'rekom.idkar4=p4.pengguna_id', 'left')
			->join('pengguna p5', 'rekom.idkar5=p5.pengguna_id', 'left')
			->join('pengguna p6', 'rekom.idkar6=p6.pengguna_id', 'left')
			->join('pengguna p7', 'rekom.idkar7=p7.pengguna_id', 'left')
			->join('pengguna p8', 'rekom.idkar8=p8.pengguna_id', 'left')
			->join('pengguna p9', 'rekom.idkar9=p9.pengguna_id', 'left')
			->join('pengguna p10', 'rekom.idkar10=p10.pengguna_id', 'left')
			->join('surat_kategori sKat', 'rekom.id_kat_surat=sKat.id_kat_surat', 'left')
			->where('s.id_kat_surat = 17')
			->where('rekom.id', $id);
		return $this->db->get()->result();
	}

	function getRekomLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_rekom')->row();
	}

	public function getBywhereActive()
	{
		$sql = 'SELECT * FROM pengguna p WHERE p.is_active = 1 ORDER BY p.nama ASC';
		$query = $this->db->query($sql);
		return $query->result(); // Mengembalikan hasil query dalam bentuk array objek
	}

	function getAllMyRekom($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
				s.status as stat_persetujuan,
				rekom.id as id,
				rekom.kode as kode,
				rekom.id_kat_surat as id_kat_surat,
				rekom.id_pengguna as idPengaju,
				rekom.perihal as perihal,
				rekom.keterangan_1 as keterangan_1,
				rekom.keterangan_2 as keterangan_2,
				rekom.nama_kyw as nama_kyw,
				rekom.jabatan as jabatan1,
				rekom.dasar as dasar,
				rekom.tgl as tgl,
				rekom.perubahan as perubahan,
				rekom.tgl_pengajuan as pengajuan,
				rekom.ttd_1 as aju_ttd1,
				rekom.ttd_2 as aju_ttd2,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_rekom rekom', 's.id_srt=rekom.id')
			->join('pengguna p', 'rekom.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'rekom.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 17')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getAllRekom()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
                s.status as stat_persetujuan,
                rekom.id as id,
				rekom.kode as kode,
				rekom.id_kat_surat as id_kat_surat,
				rekom.id_pengguna as idPengaju,
				rekom.perihal as perihal,
				rekom.keterangan_1 as keterangan_1,
				rekom.keterangan_2 as keterangan_2,
				rekom.nama_kyw as nama_kyw,
				rekom.jabatan as jabatan1,
				rekom.dasar as dasar,
				rekom.tgl as tgl,
				rekom.perubahan as perubahan,
				rekom.tgl_pengajuan as pengajuan,
				rekom.ttd_1 as aju_ttd1,
				rekom.ttd_2 as aju_ttd2,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.nik as nik,
				sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_rekom rekom', 's.id_srt=rekom.id')
			->join('pengguna p', 'rekom.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'rekom.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 17')
			->generate();
	}

	function update_rekom($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_rekom', $data);
	}

	//End Surat Rekomendasi

	//SURAT KETERANGAN
	function getKETERANGANById($id)
	{
		$this->db->select('
            ket.id as id,
            ket.id_kat_surat as id_kat_surat,
            ket.kode as kode,
            ket.perihal as perihal,
            ket.id_pengguna as idPengaju,
            ket.ttd_1 as aju_ttd1,
            ket.nama_kyw as nama_kyw,
            ket.jabatan as jabatan1,
            ket.tgl as tgl,
            ket.tgl_pengajuan as tgl_pengajuan,
            p.nama as nama,
            p.short_name as nama_ttd,
            p.jabatan as jabatan,
            p.nik as nik,
            sKat.kat_surat as kategori,
            p2.nama as nama_pegawai,
            p2.jabatan as jabatan_pegawai,
						p2.tgl_masuk
            ')
			->from('surat_list s')
			->join('surat_keterangan ket', 's.id_srt=ket.id')
			->join('pengguna p', 'ket.id_pengguna=p.pengguna_id')
			->join('pengguna p2', 'ket.nama_kyw=p2.pengguna_id')
			->join('surat_kategori sKat', 'ket.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 18')
			->where('ket.id', $id);
		return $this->db->get()->result();
	}

	function getAllMyKeterangan($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
			s.status as stat_persetujuan,
			ket.id as id,
            ket.id_kat_surat as id_kat_surat,
            ket.kode as kode,
            ket.perihal as perihal,
            ket.id_pengguna as idPengaju,
            ket.ttd_1 as aju_ttd1,
            ket.nama_kyw as nama_kyw,
            ket.jabatan as jabatan1,
            ket.tgl as tgl,
            ket.tgl_pengajuan as tgl_pengajuan,
            p.nama as nama,
            p.short_name as nama_ttd,
            p.jabatan as jabatan,
            p.nik as nik,
            sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_keterangan ket', 's.id_srt=ket.id')
			->join('pengguna p', 'ket.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'ket.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 18')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function getKeteranganLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('surat_keterangan')->row();
	}

	function getKeteranganKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_keterangan', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function getAllKeterangan()
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
            s.status as stat_persetujuan,
			ket.id as id,
            ket.id_kat_surat as id_kat_surat,
            ket.kode as kode,
            ket.perihal as perihal,
            ket.id_pengguna as idPengaju,
            ket.ttd_1 as aju_ttd1,
            ket.nama_kyw as nama_kyw,
            ket.jabatan as jabatan1,
            ket.tgl as tgl,
            ket.tgl_pengajuan as tgl_pengajuan,
            p.nama as nama,
            p.short_name as nama_ttd,
            p.jabatan as jabatan,
            p.nik as nik,
            sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_keterangan ket', 's.id_srt=ket.id')
			->join('pengguna p', 'ket.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'ket.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 18')
			->generate();
	}

	function update_keterangan($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_keterangan', $data);
	}


	//END SURAT KETERANGAN

	//SURAT PERINTAH ISTIRAHAT
	function getPERINTAHById($id)
	{
		$this->db->select('
               per.id as id,
               per.id_kat_surat as id_kat_surat,
               per.kode as kode,
               per.perihal as perihal,
               per.id_pengguna as idPengaju,
               per.ttd_1 as aju_ttd1,
               per.nama_kyw as nama_kyw,
               per.jabatan as jabatan1,
			   per.npp as npp,
			   per.hari_istirahat as hari_istirahat,
               per.tgl_istirahat as tgl_istirahat,
               per.tgl_pengajuan as tgl_pengajuan,
                p.nama as nama,
                p.short_name as nama_ttd,
                p.jabatan as jabatan,
                p.nik as nik,
                sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_perintah per', 's.id_srt=per.id')
			->join('pengguna p', 'per.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'per.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 19')
			->where('per.id', $id);
		return $this->db->get()->result();
	}

	function getAllMyPerintah($id_pengaju)
	{
		$this->db->order_by('s.id_list_surat', 'DESC');
		return $this->datatables
			->select('
			s.status as stat_persetujuan,
				per.id as id,
               per.id_kat_surat as id_kat_surat,
               per.kode as kode,
               per.perihal as perihal,
               per.id_pengguna as idPengaju,
               per.ttd_1 as aju_ttd1,
               per.nama_kyw as nama_kyw,
               per.jabatan as jabatan1,
			   per.npp as npp,
			   per.hari_istirahat as hari_istirahat,
               per.tgl_istirahat as tgl_istirahat,
               per.tgl_pengajuan as tgl_pengajuan,
                p.nama as nama,
                p.short_name as nama_ttd,
                p.jabatan as jabatan,
                p.nik as nik,
                sKat.kat_surat as kategori
            ')
			->from('surat_list s')
			->join('surat_perintah per', 's.id_srt=per.id')
			->join('pengguna p', 'per.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'per.id_kat_surat=sKat.id_kat_surat')
			->where('s.id_kat_surat = 19')
			->where('p.pengguna_id', $id_pengaju)
			->generate();
	}

	function updatemodalapproval($id, $modal)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_approval_detail', array('modal' => $modal));
	}
}
