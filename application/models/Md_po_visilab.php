<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_po_visilab extends CI_Model
{


	// Add
	function addPO($data)
	{
			$this->db->insert('po_visilab', $data);
	}

	function addPOdetail($data)
	{
			$this->db->insert('po_visilab_detail', $data);
	}

	

	function addNotifikasi($data){
			$this->db->insert('notifikasipengguna', $data);
	}


	//GET
	function getKodeId(){
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('po_visilab',array('YEAR(`created_at`)' => date('Y')))->row();
	}

	function getLastId(){
		return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('po_visilab')->row();
	}

	function getAllPo()
	{
			$this->db->order_by('f.id', 'DESC');

			// Filter berdasarkan status PO
			if ($this->input->post('filter_status')) {
					$this->datatables->where('f.status', $this->input->post('filter_status', TRUE));
			}

			// Filter berdasarkan supplier
			if ($this->input->post('filter_supplier')) {
					$this->datatables->where("(
							SELECT ff.supplier 
							FROM po_visilab_detail ff 
							WHERE ff.id_po = f.id 
							LIMIT 1
					) = '" . $this->input->post('filter_supplier', TRUE) . "'");
			}

			return $this->datatables
					->select('
							f.id as id_po,
							f.kode_po,
							f.tanggal,
							f.lampiran,
							f.no_po,
							f.lampiran_adm,
							f.lampiran_finance,
							f.status,
							f.bulan_1,
							f.bulan_2,
							f.bulan_3,
							f.id_pengaju as idPengaju,
							f.kota_pengajuan as kota_pengajuan,
							f.tgl_pengajuan as tgl_Pengajuan,
							f.status,
							f.ttd_gm,
							f.ttd_1,
							f.ttd_2,
							f.ttd_3,
							f.ttd_4,
							(SELECT ff.supplier FROM po_visilab_detail ff WHERE f.id = ff.id_po LIMIT 1) as supplier,
							p.nama as pengaju,
							p.short_name as nama_ttd,
							p.jabatan as jabatan,
							p.jabatan_visilab as jabatan_visilab
					')
					->from('po_visilab f')
					->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
					->where('f.status != 5') // tidak tampilkan yang dihapus
					->generate();
	}

	function getPOById($id)
			{
					$this->db->select('
						f.id as id_po,
						f.kode_po,
						f.tanggal,
						f.lampiran,
						f.no_po,
						f.lampiran_adm,
						f.lampiran_finance,
						f.status,
						f.bulan_1,
						f.bulan_2,
						f.bulan_3,
						f.id_pengaju as idPengaju,
						f.kota_pengajuan as kota_pengajuan,
						f.tgl_pengajuan as tgl_Pengajuan,
						f.status,
						f.ttd_gm,
						f.ttd_1,
						f.ttd_2,
						f.ttd_3,
						f.ttd_4,
						p.nama as pengaju,
						p.short_name as nama_ttd,
						p.jabatan as jabatan,
						p.jabatan_visilab as jabatan_visilab,
						p.no_pegawai
					')
					->from('po_visilab f')
					->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
					->where('f.id', $id);
					return $this->db->get()->result();
			}

			function getDetailPOById($id)
			{
					$this->db->select('
						f.id_pod as id_pod,
						f.id_po as id_po,
						f.nama_barang,
						f.kode_barang,
						f.isibulan_1,
						f.isibulan_2,
						f.isibulan_3,
						f.stok_gudang,
						f.stok_po,
						f.kebutuhan_po,
						f.rencana_po,
						f.supplier,
						f.keterangan,
						f.detail,
						f.keterangan2
					')
					->from('po_visilab_detail f')
					->where('f.id_po', $id);
					return $this->db->get()->result();
			}

			
			function updatePO($id, $data)
			{
				$this->db->where('id', $id);
				$this->db->update('po_visilab', $data);
			}

			function getNotifPoId($id)
			{
					$this->db->select('
						f.id as id_po,
						f.kode_po,
						f.tanggal,
						f.lampiran,
						f.no_po,
						f.lampiran_adm,
						f.lampiran_finance,
						f.status,
						f.bulan_1,
						f.bulan_2,
						f.bulan_3,
						f.id_pengaju as idPengaju,
						f.kota_pengajuan as kota_pengajuan,
						f.tgl_pengajuan as tgl_Pengajuan,
						f.status,
						f.ttd_gm,
						f.ttd_1,
						f.ttd_2,
						f.ttd_3,
						f.ttd_4,
						ff.supplier,
						ff.keterangan,
						p.nama as pengaju,
						p.short_name as nama_ttd,
						p.jabatan as jabatan,
						p.jabatan_visilab as jabatan_visilab
					')
					->from('po_visilab f')
					->join('po_visilab_detail ff', 'f.id=ff.id_po')
					->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
					->where('f.id', $id);
					return $this->db->get()->result();
			}


			function getDetailPOByIdEdit($id)
			{
					$where = array('po_visilab_detail.id_pod' => $id);    
        	return $this->db
                ->select('
								po_visilab_detail.id_pod as id_pod,
								po_visilab_detail.id_po as id_po,
								po_visilab_detail.nama_barang as namabarang,
								po_visilab_detail.stok_gudang,
								po_visilab_detail.rencana_po,
								po_visilab_detail.supplier,
								po_visilab_detail.detail,
								po_visilab_detail.keterangan2
							')
						->get_where('po_visilab_detail', $where)
            ->result();
			}


			function updateKeterangan($id, $data)
			{
					$this->db->where('id_pod', $id);
					$this->db->update('po_visilab_detail', $data);
			}

			// fungsi reset urutan id pada tabel
			function reset_increment($tabel){
				$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
			}



			function getSupplier()
			{
					$this->db->order_by('k.nama_pemasok', 'ASC');
					return $this->db->get('pemasok_utama k')->result();
			}


















	


	

		
		function getAllPoOld()
			{
					$this->db->order_by('f.id', 'DESC');
					return $this->datatables
							->select('
									f.id as id_po,
									f.kode_po,
									f.tanggal,
									f.lampiran,
									f.no_po,
									f.lampiran_adm,
									f.lampiran_finance,
									f.status,
									f.bulan_1,
									f.bulan_2,
									f.bulan_3,
									f.id_pengaju as idPengaju,
									f.kota_pengajuan as kota_pengajuan,
									f.tgl_pengajuan as tgl_Pengajuan,
									f.status,
									f.ttd_gm,
									f.ttd_1,
									f.ttd_2,
									f.ttd_3,
									f.ttd_4,
									(SELECT ff.supplier FROM purchase_order_detail ff WHERE f.id = ff.id_po LIMIT 1) as supplier,
									p.nama as pengaju,
									p.short_name as nama_ttd,
									p.jabatan as jabatan,
									p.jabatan_visilab as jabatan_visilab
									')
				->from('purchase_order f')
				->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
				->where('f.status != 5') //dihapus
				->generate();
			}

			

		

			function getAllPObyID($id)
			{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
				->select('
						f.id as id_po,
						f.kode_po,
						f.tanggal,
						f.lampiran,
						f.no_po,
						f.lampiran_adm,
						f.lampiran_finance,
						f.status,
						f.bulan_1,
						f.bulan_2,
						f.bulan_3,
						f.id_pengaju as idPengaju,
						f.kota_pengajuan as kota_pengajuan,
						f.tgl_pengajuan as tgl_Pengajuan,
						f.status,
						f.ttd_gm,
						f.ttd_1,
						f.ttd_2,
						f.ttd_3,
						f.ttd_4,,
        		(SELECT ff.supplier FROM purchase_order_detail ff WHERE f.id = ff.id_po LIMIT 1) as supplier,
						p.nama as pengaju,
						p.short_name as nama_ttd,
						p.jabatan as jabatan,
						p.jabatan_visilab as jabatan_visilab
						')
						->from('purchase_order f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->where('f.status != 5')
						->where('f.id_pengaju', $id)
						->generate();
			}

			

			
			


			

		

			

			


			


			

	

}