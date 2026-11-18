<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Md_fpp extends CI_Model
{


	//==================================
	//==================================
	//============  FPP  ===============
	//==================================
	//==================================

	// Add
	function addFpp($data)
	{
		$this->db->insert('fpp', $data);
	}

	function addDetailFpp($data)
	{
		$this->db->insert('fpp_detail', $data);
	}

	function addNotifikasi($data)
	{
		$this->db->insert('notifikasipengguna', $data);
	}

	//GET
	function getBarang()
	{
		$this->db->where('b.status', 1);
		$this->db->order_by('b.nama_barang', 'ASC');
		return $this->db->get('barang b')->result();
	}

	function getFppLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('fpp')->row();
	}

	function getFppKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('fpp', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}



	function getAllFPP($bulan = NULL, $tahun = NULL) // <-- Tambahkan parameter

	{

		$this->db->order_by('f.id', 'DESC');

		$datatables = $this->datatables

			->select('

                        f.id as idGc,

        f.csname as csName,

        f.kode_fpp,

        f.alamat,

        f.id_pengaju as idPengaju,

        f.tgl as tanggal,

        f.status,

        f.no_sph,

        f.link_sph as sph,

        f.link_approval as aproval,

        p.nama as pengaju,

        p.jabatan as jabatan

                  ')

			->from('fpp f')

			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')

			->where('f.status != 5');



		// Logika Filter Bulan dan Tahun

		if (!empty($tahun)) {

			$datatables->where('YEAR(f.tgl)', $tahun);
		}

		if (!empty($bulan)) {

			$datatables->where('MONTH(f.tgl)', $bulan);
		}



		return $datatables->generate();
	}

	function getAllFPPbyID($id, $bulan = NULL, $tahun = NULL) // <-- Tambahkan parameter

	{

		$this->db->order_by('f.id', 'DESC');

		$datatables = $this->datatables

			->select('

                        f.id as idGc,

        f.csname as csName,

        f.kode_fpp,

        f.alamat,

        f.id_pengaju as idPengaju,

        f.tgl as tanggal,

        f.status,

        f.no_sph,

        f.link_sph as sph,

        f.link_approval as aproval,

        p.nama as pengaju,

        p.jabatan as jabatan

                  ')

			->from('fpp f')

			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')

			->where('f.status != 5')

			->where('f.id_pengaju', $id);



		// Logika Filter Bulan dan Tahun

		if (!empty($tahun)) {

			$datatables->where('YEAR(f.tgl)', $tahun);
		}

		if (!empty($bulan)) {

			$datatables->where('MONTH(f.tgl)', $bulan);
		}



		return $datatables->generate();
	}


	function getFppById($id)
	{
		$this->db->select('
        f.id as idFpp,
        f.csname as csName,
        f.kode_fpp,
        f.alamat,
        f.id_pengaju as idPengaju,
        f.tgl as tanggal,
        f.cpname as cpName,
        f.nocp as noCp,
        f.payment as paYment,
        f.cicilan,
        f.ongkir,
        f.pajak,
        f.notes as noTes,
        f.notifikasi,
        f.kota_pengajuan as kota_pengajuan,
        f.tgl_pengajuan as tgl_Pengajuan,
        f.status,
        f.approval_1, 
        f.approval_2, 
        f.approval_3, 
        f.approval_4, 
        f.approval_5, 
        f.approval_6, 
        f.approval_7, 
        f.approval_8,
        f.no_sph,
        f.link_sph as sph,
        f.link_approval as approval,
        p.nama as pengaju,
        p.short_name as nama_ttd,
        p.jabatan as jabatan,
        
        f.jenis_pembelian_tld,
        f.jumlah_pekerja_radiasi,
        f.include_zero_check,
        f.sudah_memiliki_tld_kontrol,
        f.membutuhkan_tld_kontrol_baru,
        f.tld_include_tld_kontrol,
        f.user_terdaftar_lab_dosimetri,
        f.setuju_estimasi_zero_check
    ')
			->from('fpp f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.id', $id);
		return $this->db->get()->result();
	}

	function getFppDetailById($id)
	{
		$this->db->select('
				df.id as if_Dfpp,
				df.deskripsi as des,
				df.qty as Qty,
				df.price as pri,
				df.diskon as dis,
				df.komisi as kom,
				df.namauser as usr,
				df.komisiketiga as komtiga,
				df.namaketiga as nmtiga
            ')
			->from('fpp_detail df')
			->where('df.id_fpp', $id);
		return $this->db->get()->result();
	}

	function getById($id)
	{
		return $this->db->get_where('fpp pg', array('pg.id' => $id))->result();
	}

	// fungsi reset urutan id pada tabel
	function reset_increment($tabel)
	{
		$this->db->query("ALTER TABLE " . $tabel . " AUTO_INCREMENT = 1");
	}


	//UPDATE
	function updateFpp($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('fpp', $data);
	}

	function updateDetailFpp($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('fpp_detail', $data);
	}



	function getAllLaporanFPPByPenggunaID121($pengguna_id, $tglawal, $tglakhir)
	{
		$this->db->order_by('fpp.tgl', 'asc');
		return $this->db
			->select('
				fpp.id,
				fpp.id_pengaju,
				fpp.csname,
				fpp.kode_fpp,
				fpp.alamat,
				fpp.cpname,
				fpp.nocp,
				fpp.payment,
				fpp.notes,
				fpp.no_sph,
				fpp.link_sph,
				fpp.link_approval,
                fpp.tgl
            ')
			->from('fpp')
			->where('fpp.id_pengaju', $pengguna_id)
			->where('CONVERT(fpp.tgl, DATE) BETWEEN "' . date('Y-m-d', strtotime($tglawal)) . '" and "' . date('Y-m-d', strtotime($tglakhir)) . '"')
			->where('fpp.status != 5')
			->get()
			->result();
	}

	function getAllLaporanFPPByPenggunaID($pengguna_id = NULL, $tglawal, $tglakhir)
	{
		$this->db->order_by('fpp.tgl', 'ASC');

		$query = $this->db
			->select('
								fpp.id,
								fpp.id_pengaju,
								fpp.csname,
								fpp.kode_fpp,
								fpp.alamat,
								fpp.cpname,
								fpp.nocp,
								fpp.payment,
								fpp.notes,
								fpp.no_sph,
								fpp.link_sph,
								fpp.link_approval,
								fpp.tgl,
                p.nama as namamarketing
						')
			->from('fpp')
			->join('pengguna p', 'fpp.id_pengaju=p.pengguna_id')
			->where('fpp.tgl >=', date('Y-m-d', strtotime($tglawal)))
			->where('fpp.tgl <=', date('Y-m-d', strtotime($tglakhir)))
			->where('fpp.status !=', 5);

		// Tambahkan filter jika $pengguna_id tidak null
		if ($pengguna_id != NULL) {
			$query->where('fpp.id_pengaju', $pengguna_id);
		}

		return $query->get()->result();
	}



	//==================================
	//==================================
	//========  Presentase  ============
	//==================================
	//==================================



	function addPresentase($data)
	{
		$this->db->insert('presentase', $data);
	}

	function addDetailPresentase($data)
	{
		$this->db->insert('presentase_detail', $data);
	}


	function getPresentaseLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('presentase')->row();
	}

	function getPresentaseKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('presentase', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	//UPDATE
	function updatePresentase($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('presentase', $data);
	}



	function getAllPresentase()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
              f.id as idGc,
							f.csname as csName,
							f.kode,
							f.alamat,
							f.invoice,
							f.teknisi,
							f.link,
							f.id_pengaju as idPengaju,
							f.status,
							p.nama as pengaju,
							p.jabatan as jabatan
                  ')
			->from('presentase f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->generate();
	}


	function getAllPresentaseById($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
              f.id as idGc,
							f.csname as csName,
							f.kode,
							f.alamat,
							f.invoice,
							f.teknisi,
							f.link,
							f.id_pengaju as idPengaju,
							f.status,
							p.nama as pengaju,
							p.jabatan as jabatan
                  ')
			->from('presentase f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.id_pengaju', $id)
			->generate();
	}


	function getPresentaseById($id)
	{
		$this->db->select('
        f.id as idFpp,
				f.csname as csName,
				f.kode,
				f.alamat,
				f.id_pengaju as idPengaju,
				f.invoice as invoice,
				f.cpname as cpName,
				f.nocp as noCp,
				f.teknisi as teknisi,
				f.notes as noTes,
				f.link,
				f.kota_pengajuan as kota_pengajuan,
				f.tgl_pengajuan as tgl_Pengajuan,
				f.status,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan
            ')
			->from('presentase f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.id', $id);
		return $this->db->get()->result();
	}

	function getPresentaseDetailById($id)
	{
		$this->db->select('
				df.id as if_Dfpp,
				df.deskripsi as des,
				df.req_detail as req
            ')
			->from('presentase_detail df')
			->where('df.id_presentase', $id);
		return $this->db->get()->result();
	}



	//==================================
	//==================================
	//==========  Trouble  =============
	//==================================
	//==================================



	function addTrouble($data)
	{
		$this->db->insert('trouble', $data);
	}

	function addDetailTrouble($data)
	{
		$this->db->insert('trouble_detail', $data);
	}


	function getTroubleLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('trouble')->row();
	}

	function getTroubleKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('trouble', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	//UPDATE
	function updateTrouble($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('trouble', $data);
	}



	function getAllTrouble()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
              f.id as idGc,
							f.csname as csName,
							f.kode,
							f.alamat,
							f.invoice,
							f.id_pengaju as idPengaju,
							f.status,
							p.nama as pengaju,
							p.jabatan as jabatan
                  ')
			->from('trouble f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->generate();
	}


	function getAllTroubleById($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
              f.id as idGc,
							f.csname as csName,
							f.kode,
							f.alamat,
							f.invoice,
							f.id_pengaju as idPengaju,
							f.status,
							p.nama as pengaju,
							p.jabatan as jabatan
                  ')
			->from('trouble f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.id_pengaju', $id)
			->generate();
	}


	function getTroubleById($id)
	{
		$this->db->select('
        f.id as idFpp,
				f.csname as csName,
				f.kode,
				f.alamat,
				f.id_pengaju as idPengaju,
				f.invoice as invoice,
				f.cpname as cpName,
				f.nocp as noCp,
				f.notes as noTes,
				f.kota_pengajuan as kota_pengajuan,
				f.tgl_pengajuan as tgl_Pengajuan,
				f.status,
				f.item1,
				f.item2,
				f.item3,
				f.item4,
				f.item5,
				f.item6,
				f.item7,
				f.item8,
				f.item9,
				f.item10,
				f.item11,
				f.namaitem11,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan
            ')
			->from('trouble f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.id', $id);
		return $this->db->get()->result();
	}

	function getTroubleDetailById($id)
	{
		$this->db->select('
				df.id as if_Dfpp,
				df.deskripsi as des,
				df.req_detail as req
            ')
			->from('trouble_detail df')
			->where('df.id_trouble', $id);
		return $this->db->get()->result();
	}
}
