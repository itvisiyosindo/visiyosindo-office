<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_surat_new extends CI_Model
{


		//=======================================
		//=========   Start Kendaraan     =======
		//=======================================
		
	//Add
	function addKendaraan($data)
		{
				$this->db->insert('surat_kendaraan', $data);
		}

		//UPDATE 
			function updateKendaraan($id, $data)
			{
				$this->db->where('id', $id);
				$this->db->update('surat_kendaraan', $data);
			}


	//GET
	function getKendaraanKodeId(){
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('surat_kendaraan',array('YEAR(`created_at`)' => date('Y')))->row();
	}

	function getKendaraanByID($id)
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
					->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.id_pengaju as idPengaju,
						f.keperluan,
						f.mobil,
						f.nopol,
						f.lama,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
						->from('surat_kendaraan f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						//->where('f.status != 5')
						->where('f.id_pengaju', $id)
						->generate();
		}

		function getAllKendaraan()
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
					->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.id_pengaju as idPengaju,
						f.keperluan,
						f.mobil,
						f.nopol,
						f.lama,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
						->from('surat_kendaraan f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->generate();
		}

		function getDetailKendaraanById($id)
		{
				$this->db->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.id_pengaju as idPengaju,
						f.keperluan,
						f.mobil,
						f.nopol,
						f.lama,
						f.status,
						f.ttd_1,
						f.ttd_2,
						f.lampiran,
						f.created_at,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p.no_pegawai
				')
				->from('surat_kendaraan f')
				->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
				->where('f.id', $id);
				return $this->db->get()->result();
		}


		//=======================================
		//=========   END Kendaraan       =======
		//=======================================

		// fungsi reset urutan id pada tabel
		function reset_increment($tabel){
				$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
		}

		//=======================================
		//====   Start Purchase Order (PO)  =====
		//=======================================
		
		//Add
		function addPo($data)
			{
					$this->db->insert('surat_po', $data);
			}

		//UPDATE 
		function updatePo($id, $data)
			{
				$this->db->where('id', $id);
				$this->db->update('surat_po', $data);
			}

		//GET
		function getPoKodeId(){
			return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('surat_po',array('YEAR(`created_at`)' => date('Y')))->row();
		}


		function getPoByID($id)
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
					->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.id_pengaju as idPengaju,
						f.no_po,
						f.id_pelanggan,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						pl.id_pelanggan,
						pl.identitas_pelanggan
						')
						->from('surat_po f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pelanggan pl', 'f.id_pelanggan=pl.id_pelanggan')
						->where('f.id_pengaju', $id)
						->generate();
		}

		function getAllPo()
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
					->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.id_pengaju as idPengaju,
						f.no_po,
						f.id_pelanggan,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						pl.id_pelanggan,
						pl.identitas_pelanggan
						')
						->from('surat_po f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pelanggan pl', 'f.id_pelanggan=pl.id_pelanggan')
						->generate();
		}

		function getDetailPoById($id)
		{
				$this->db->select('
					
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.id_pengaju as idPengaju,
						f.no_po,
						f.item1,
						f.item2,
						f.item3,
						f.item4,
						f.item5,
						f.alasan,
						f.catatan,
						f.catatan_hr,
						f.catatan_gm,
						f.id_pelanggan,
						f.status,
						f.lampiran,
						f.ttd_1,
						f.ttd_2,
						f.ttd_3,
						f.jenis,
						f.created_at,
						p.nama as pengaju,
						p.jabatan as jabatan,
						pl.id_pelanggan,
						pl.identitas_pelanggan
				')
				->from('surat_po f')
				->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
				->join('pelanggan pl', 'f.id_pelanggan=pl.id_pelanggan')
				->where('f.id', $id);
				return $this->db->get()->result();
		}

		//=======================================
		//======   End Purchase Order (PO)  =====
		//=======================================





		//=======================================
		//===== Start Approval Ekpedisi   =======
		//=======================================

		//Add
		function addAppeks($data)
			{
					$this->db->insert('surat_aprv', $data);
			}

			function addAppeksDetail($data)
			{
					$this->db->insert('surat_aprv_detail', $data);
			}

		//UPDATE 
		function updateAppeks($id, $data)
			{
				$this->db->where('id', $id);
				$this->db->update('surat_aprv', $data);
			}

			function updateAppeksDetail($id, $data)
			{
				$this->db->where('id', $id);
				$this->db->update('surat_aprv_detail', $data);
			}

		//GET
		function getAppeksKodeId(){
			return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('surat_aprv',array('YEAR(`created_at`)' => date('Y')))->row();
		}

		function getAppeksLastId(){
			return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('surat_aprv')->row();
		}


		function getAppeksByID($id)
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
					->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.nama_customer,
						f.tujuan,
						f.nama_barang,
						f.id_pengaju as idPengaju,
						f.no_sj,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
						->from('surat_aprv f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->where('f.id_pengaju', $id)
						->generate();
		}

		function getAllAppeks()
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
					->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.nama_customer,
						f.tujuan,
						f.nama_barang,
						f.id_pengaju as idPengaju,
						f.no_sj,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
						->from('surat_aprv f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->generate();
		}


		function getAppeksDetailById($id)
		{
				$this->db->select('
					
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.nama_customer,
						f.tujuan,
						f.nama_barang,
						f.id_pengaju as idPengaju,
						f.no_sj,
						f.status,
						f.lampiran,
						f.kota_aju,
						f.gudang_asal,
						f.rencana_expedisi,
						f.harga_expedisi,
						f.po_customer,
						f.sph_customer,
						f.catatan_finance,
						f.ttd_1,
						f.ttd_2,
						f.kota_aju,
						f.created_at,
						p.nama as pengaju,
						p.jabatan as jabatan
				')
				->from('surat_aprv f')
				->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
				->where('f.id', $id);
				return $this->db->get()->result();
		}


		function getDetailAppById($id)
    {
        $this->db->select('
						df.id,
						df.namaekspedisi,
						df.berat,
						df.fasilitas,
						df.harga,
						df.acuanharga,
						df.kekurangan,
						df.approval
            ')
        ->from('surat_aprv_detail df')
				->where('df.id_aprv', $id);
				return $this->db->get()->result();
    }

		function getDetailApprovalById($id)
    {
        return $this->db->get_where('surat_aprv_detail p', array('p.id' => $id))->result();
    }


		//=======================================
		//======= END Approval Ekpedisi   =======
		//=======================================




		//=======================================
		//===== Start Serah Terima ====   =======
		//=======================================

		//Add
		function addSerah($data)
			{
					$this->db->insert('surat_serah', $data);
			}

			function addSerahDetail($data)
			{
					$this->db->insert('surat_serah_detail', $data);
			}

		//UPDATE 
		function updateSerah($id, $data)
			{
				$this->db->where('id_serah', $id);
				$this->db->update('surat_serah', $data);
			}

			function updateSerahDetail($id, $data)
			{
				$this->db->where('id', $id);
				$this->db->update('surat_serah_detail', $data);
			}


			function getDetailSerahById($id)
			{
					$this->db->select('
							df.id,
							df.nama,
							df.sn,
							df.jumlah,
							df.link,
							df.ket
							')
					->from('surat_serah_detail df')
					->where('df.id_serah', $id);
					return $this->db->get()->result();
			}

			function getDetailSeraahById($id)
			{
					return $this->db->get_where('surat_serah_detail p', array('p.id' => $id))->result();
			}


		//Start Serah Terima Aset ============================================================
		function getStaKodeId(){
			$this->db->select("COUNT(*) as id_serah")
					->from('surat_serah')
					->where(array(
							'YEAR(created_at)' => date('Y'),
							'jenis' => '1',  // jenis 1 = STA
					))
					->order_by('id_serah', 'DESC')
					->limit(1);

			$result = $this->db->get()->row();
			return $result;
	}

		function getStaLastId(){
			return $this->db->select("*")->limit(1)->order_by('id_serah',"DESC")->get('surat_serah')->row();
		}


		function getStaByID($id)
		{
			$this->db->order_by('f.id_serah', 'DESC');
				return $this->datatables
					->select('
						f.id_serah as idGc,
						f.kode,
						f.tanggal,
						f.keterangan,
						f.id_terima,
						f.id_pengaju as idPengaju,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as penerima,
						p2.jabatan as jabatan2
						')
						->from('surat_serah f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_terima=p2.pengguna_id')
						->where('f.id_pengaju', $id)
						->where('f.jenis = 1')
						->generate();
		}

		function getAllSta()
		{
			$this->db->order_by('f.id_serah', 'DESC');
				return $this->datatables
					->select('
						f.id_serah as idGc,
						f.kode,
						f.tanggal,
						f.keterangan,
						f.id_terima,
						f.id_pengaju as idPengaju,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as penerima,
						p2.jabatan as jabatan2
						')
						->from('surat_serah f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_terima=p2.pengguna_id')
						->where('f.jenis = 1')
						->generate();
		}

		function getSta2ByID($id)
		{
			$this->db->order_by('f.id_serah', 'DESC');
				return $this->datatables
					->select('
						f.id_serah as idGc,
						f.kode,
						f.tanggal,
						f.keterangan,
						f.id_terima,
						f.id_pengaju as idPengaju,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as penerima,
						p2.jabatan as jabatan2
						')
						->from('surat_serah f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_terima=p2.pengguna_id')
						->where('f.id_terima', $id)
						->where('f.jenis = 1')
						->generate();
		}


		

		//END Serah Terima Aset ==============================

		function getStaDetailById($id)
		{
				$this->db->select('
						f.id_serah as idGc,
						f.kode,
						f.tanggal,
						f.keterangan,
						f.id_terima,
						f.id_diketahui,
						f.id_pengaju as idPengaju,
						f.status,
						f.lampiran,
						f.kota_aju,
						f.ttd,
						f.ttd_1,
						p.short_name as pengaju,
						p.jabatan as jabatan,
						p.no_pegawai as nppaju,
						p2.short_name as penerima,
						p2.no_pegawai as nppterima,
						p2.jabatan as jabatan2,
						p3.short_name as mengetahui,
						p3.no_pegawai as nppketahui,
						p3.jabatan as jabatan3
				')
				->from('surat_serah f')
				->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
				->join('pengguna p2', 'f.id_terima=p2.pengguna_id')
				->join('pengguna p3', 'f.id_diketahui=p3.pengguna_id', 'left')
				->where('f.id_serah', $id);
				return $this->db->get()->result();
		}


		//Start Serah Terima Fisik Perlengkapan ==============================================
		function getStfpKodeId(){
			$this->db->select("COUNT(*) as id_serah")
					->from('surat_serah')
					->where(array(
							'YEAR(created_at)' => date('Y'),
							'jenis' => '2',  // jenis 2 = STFP
					))
					->order_by('id_serah', 'DESC')
					->limit(1);

			$result = $this->db->get()->row();
			return $result;
	}

		


		function getStfpByID($id)
		{
			$this->db->order_by('f.id_serah', 'DESC');
				return $this->datatables
					->select('
						f.id_serah as idGc,
						f.kode,
						f.tanggal,
						f.keterangan,
						f.id_terima,
						f.id_pengaju as idPengaju,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as penerima,
						p2.jabatan as jabatan2
						')
						->from('surat_serah f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_terima=p2.pengguna_id')
						->where('f.id_pengaju', $id)
						->where('f.jenis = 2')
						->generate();
		}

		function getAllStfp()
		{
			$this->db->order_by('f.id_serah', 'DESC');
				return $this->datatables
					->select('
						f.id_serah as idGc,
						f.kode,
						f.tanggal,
						f.keterangan,
						f.id_terima,
						f.id_pengaju as idPengaju,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as penerima,
						p2.jabatan as jabatan2
						')
						->from('surat_serah f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_terima=p2.pengguna_id')
						->where('f.jenis = 2')
						->generate();
		}

		function getStfp2ByID($id)
		{
			$this->db->order_by('f.id_serah', 'DESC');
				return $this->datatables
					->select('
						f.id_serah as idGc,
						f.kode,
						f.tanggal,
						f.keterangan,
						f.id_terima,
						f.id_pengaju as idPengaju,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as penerima,
						p2.jabatan as jabatan2
						')
						->from('surat_serah f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_terima=p2.pengguna_id')
						->where('f.id_terima', $id)
						->where('f.jenis = 2')
						->generate();
		}



		//END Serah Terima Fisik Perlengkapan ============================



		//Start Serah Terima Pekerjaan ===================================
		function getStpKodeId(){
			$this->db->select("COUNT(*) as id_serah")
					->from('surat_serah')
					->where(array(
							'YEAR(created_at)' => date('Y'),
							'jenis' => '3',  // jenis 3 = STP
					))
					->order_by('id_serah', 'DESC')
					->limit(1);

			$result = $this->db->get()->row();
			return $result;
	}

		


		function getStpByID($id)
		{
			$this->db->order_by('f.id_serah', 'DESC');
				return $this->datatables
					->select('
						f.id_serah as idGc,
						f.kode,
						f.tanggal,
						f.keterangan,
						f.id_terima,
						f.id_diketahui,
						f.id_pengaju as idPengaju,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as penerima,
						p2.jabatan as jabatan2,
						p3.nama as mengetahui
						')
						->from('surat_serah f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_terima=p2.pengguna_id')
						->join('pengguna p3', 'f.id_diketahui=p3.pengguna_id')
						->where('f.id_pengaju', $id)
						->where('f.jenis = 3')
						->generate();
		}

		function getAllStp()
		{
			$this->db->order_by('f.id_serah', 'DESC');
				return $this->datatables
					->select('
						f.id_serah as idGc,
						f.kode,
						f.tanggal,
						f.keterangan,
						f.id_terima,
						f.id_diketahui,
						f.id_pengaju as idPengaju,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as penerima,
						p2.jabatan as jabatan2,
						p3.nama as mengetahui
						')
						->from('surat_serah f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_terima=p2.pengguna_id')
						->join('pengguna p3', 'f.id_diketahui=p3.pengguna_id')
						->where('f.jenis = 3')
						->generate();
		}

		function getStp2ByID($id)
		{
			$this->db->order_by('f.id_serah', 'DESC');
				return $this->datatables
					->select('
						f.id_serah as idGc,
						f.kode,
						f.tanggal,
						f.keterangan,
						f.id_terima,
						f.id_diketahui,
						f.id_pengaju as idPengaju,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as penerima,
						p2.jabatan as jabatan2,
						p3.nama as mengetahui
						')
						->from('surat_serah f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_terima=p2.pengguna_id')
						->join('pengguna p3', 'f.id_diketahui=p3.pengguna_id')
						->where('(f.id_terima = '.$id.' OR f.id_diketahui = '.$id.')')
						//->where('f.id_terima', $id)
						->where('f.jenis = 3')
						->generate();
		}



		//END Serah Terima Pekerjaan =====================================

		



		//=======================================
		//===== Start Surat Perintah Istirahat ==
		//=======================================

		//Add
		function addIstirahat($data)
		{
			$this->db->insert('surat_skorsing', $data);
		}


		//UPDATE 
		function updateIstirahat($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('surat_skorsing', $data);
		}

		//GET
		function getIstirahatKodeId(){
			return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('surat_skorsing',array('YEAR(`created_at`)' => date('Y')))->row();
		}


		function getAllIstirahat()
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
					->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.masa,
						f.id_hr,
						f.idpengguna,
						f.idpengaju as idPengaju,
						f.status,
						p.short_name as pengaju,
						p.jabatan as jabatan,
						p2.short_name as penerima,
						p2.jabatan as jabatan2
						')
						->from('surat_skorsing f')
						->join('pengguna p', 'f.idpengaju=p.pengguna_id')
						->join('pengguna p2', 'f.idpengguna=p2.pengguna_id')
						->generate();
		}



		function getIstirahatById($id)
		{
				$this->db->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.masa,
						f.id_hr,
						f.idpengguna,
						f.idpengaju as idPengaju,
						f.status,
						f.ttd,
						f.created_at,
						p.short_name as pengaju,
						p.jabatan as jabatan,
						p.no_pegawai as npp,
						p2.short_name as penerima,
						p2.no_pegawai as npp2,
						p2.jabatan as jabatan2,
						p3.nama as pengaju2
				')
				->from('surat_skorsing f')
				->join('pengguna p', 'f.id_hr=p.pengguna_id')
				->join('pengguna p2', 'f.idpengguna=p2.pengguna_id')
				->join('pengguna p3', 'f.idpengaju=p3.pengguna_id')
				->where('f.id', $id);
				return $this->db->get()->result();
		}




		//=======================================
		//======= END Surat Perintah Istirahat ==
		//=======================================

		//=======================================
		//===== Start Approval Director =========
		//=======================================

		//Add
		function addAppdir($data)
		{
			$this->db->insert('approval_director', $data);
		}


		//UPDATE 
		function updateAppdir($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('approval_director', $data);
		}

		//GET
		function getAppdirKodeId(){
			return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('approval_director',array('YEAR(`created_at`)' => date('Y')))->row();
		}

		function getAllAppdir()
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
					->select('
						f.id as idGc,
						f.kode,
						f.created_at,
						f.nama_dokumen,
						f.id_hr,
						f.id_pengaju as idPengaju,
						f.status,
						f.link,
						f.ket,
						f.jenis,
						p.short_name as pengaju,
						p.jabatan as jabatan,
						p2.short_name as penerima,
						p2.jabatan as jabatan2
						')
						->from('approval_director f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_hr=p2.pengguna_id')
						->generate();
		}



		function getAppdirById($id)
		{
				$this->db->select('
						f.id as idGc,
						f.kode,
						f.created_at,
						f.nama_dokumen,
						f.id_hr,
						f.id_pengaju as idPengaju,
						f.status,
						f.link,
						f.ket,
						f.jenis,
						f.ttd,
						p.short_name as pengaju,
						p.no_pegawai as npp,
						p.jabatan as jabatan,
						p2.short_name as penerima,
						p2.jabatan as jabatan2
						')
						->from('approval_director f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_hr=p2.pengguna_id')
						->where('f.id', $id);
				return $this->db->get()->result();
		}


		function getAllAppdirById($id)
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
					->select('
						f.id as idGc,
						f.kode,
						f.created_at,
						f.nama_dokumen,
						f.id_hr,
						f.id_pengaju as idPengaju,
						f.status,
						f.link,
						f.ket,
						f.jenis,
						p.short_name as pengaju,
						p.jabatan as jabatan,
						p2.short_name as penerima,
						p2.jabatan as jabatan2
						')
						->from('approval_director f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pengguna p2', 'f.id_hr=p2.pengguna_id')
						->where('f.id_pengaju', $id)
						->generate();
		}




		//=======================================
		//======= END Approval Director =========
		//=======================================


		
		//========================================
		//=======  Approval Faktur Pajak =========
		//========================================

		//Add
		function addAppPajak($data)
		{
			$this->db->insert('approval_faktur_pajak', $data);
		}


		//UPDATE 
		function updateAppPajak($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('approval_faktur_pajak', $data);
		}

		function getAllCustomer()
    {
        $this->db->where('status', 1);
        $this->db->order_by('k.nama_customer', 'ASC');
        return $this->db->get('customer k')->result();
    }

		
		function getAppPajakKodeId(){
			return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('approval_faktur_pajak',array('YEAR(`created_at`)' => date('Y')))->row();
		}


		function getAppPajakById11($id)
		{
				$this->db->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.created_at,
						f.id_pengaju as idPengaju,
						f.id_marketing,
						f.id_customer,
						f.no_po,
						f.dpp,
						f.ppn,
						f.pembayaran,
						f.alasan,
						f.link_lampiran,
						f.ttd_1,
						f.ttd_2,
						f.ttd_3,
						f.ttd_4,
						f.status,
						p1.nama as nama_pengaju,
						p1.no_pegawai as npp_pengaju,
						p1.jabatan as jabatan_pengaju,
						p2.nama as nama_marketing,
						c.nama_customer
				')
				->from('approval_faktur_pajak f')
				->join('pengguna p1', 'f.id_pengaju = p1.pengguna_id', 'left')
				->join('pengguna p2', 'f.id_marketing = p2.pengguna_id', 'left')
						->join('customer c', 'f.id_customer = c.id_customer', 'left')
				->where('f.id', $id);
				
				return $this->db->get()->result();
		}

		function getAppPajakById($id)
		{
				$this->db->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.created_at,
						f.id_pengaju as idPengaju,
						f.id_marketing,
						f.id_customer,
						f.no_po,
						f.dpp,
						f.ppn,
						f.pembayaran,
						f.alasan,
						f.link_lampiran,
						f.ttd_1,
						f.ttd_2,
						f.ttd_3,
						f.ttd_4,
						f.status,
						p1.short_name as nama_pengaju,
						p1.no_pegawai as npp_pengaju,
						p1.jabatan as jabatan_pengaju,
						c.identitas_pelanggan as nama_customer
				');

				// Tambahkan CASE untuk nama_marketing
				$this->db->select("
						CASE 
								WHEN f.id_marketing = 1 THEN 'Office / Kantor Pusat' 
								ELSE p2.nama 
						END AS nama_marketing
				", FALSE);

				$this->db->from('approval_faktur_pajak f')
								->join('pengguna p1', 'f.id_pengaju = p1.pengguna_id', 'left')
								->join('pengguna p2', 'f.id_marketing = p2.pengguna_id', 'left')
								//->join('customer c', 'f.id_customer = c.id_customer', 'left')
								->join('pelanggan c', 'f.id_customer = c.id_pelanggan', 'left')
								->where('f.id', $id);

				return $this->db->get()->result();
		}




		function getAllAppPajakById($id)
		{
				$this->db->order_by('f.id', 'DESC');
				return $this->datatables
						->select('
								f.id as idFp,
								f.kode,
								f.created_at,
								f.tanggal,
								f.no_po,
								f.dpp,
								f.ppn,
								f.pembayaran,
								f.status,
								f.alasan,
								f.link_lampiran,
								p1.nama as pengaju,
								p2.nama as marketing,
								c.identitas_pelanggan as nama_customer
						')
						->from('approval_faktur_pajak f')
						->join('pengguna p1', 'f.id_pengaju = p1.pengguna_id', 'left')
						->join('pengguna p2', 'f.id_marketing = p2.pengguna_id', 'left')
						//->join('customer c', 'f.id_customer = c.id_customer', 'left')
						->join('pelanggan c', 'f.id_customer = c.id_pelanggan', 'left')
						->where('f.id_pengaju', $id)
						->generate();
		}

		function getAllAppPajak()
		{
				$this->db->order_by('f.id', 'DESC');
				return $this->datatables
						->select('
								f.id as idFp,
								f.kode,
								f.created_at,
								f.tanggal,
								f.no_po,
								f.dpp,
								f.ppn,
								f.pembayaran,
								f.status,
								f.alasan,
								f.link_lampiran,
								p1.nama as pengaju,
								p2.nama as marketing,
								c.identitas_pelanggan as nama_customer
						')
						->from('approval_faktur_pajak f')
						->join('pengguna p1', 'f.id_pengaju = p1.pengguna_id', 'left')
						->join('pengguna p2', 'f.id_marketing = p2.pengguna_id', 'left')
						//->join('customer c', 'f.id_customer = c.id_customer', 'left')
						->join('pelanggan c', 'f.id_customer = c.id_pelanggan', 'left')
						->generate();
		}



		
		//============================================
		//=======  END Approval Faktur Pajak =========
		//============================================






	

	



}