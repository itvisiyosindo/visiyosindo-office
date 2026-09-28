<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_purchase_order extends CI_Model
{


	// Add
	function addPO($data)
	{
			$this->db->insert('purchase_order', $data);
	}

	function addPOdetail($data)
	{
			$this->db->insert('purchase_order_detail', $data);
	}

	

	function addNotifikasi($data){
			$this->db->insert('notifikasipengguna', $data);
	}


	//GET
	function getKodeId(){
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('purchase_order',array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function getLastId(){
		return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('purchase_order')->row();
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
							FROM purchase_order_detail ff 
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
							(SELECT ff.supplier FROM purchase_order_detail ff WHERE f.id = ff.id_po LIMIT 1) as supplier,
							p.nama as pengaju,
							p.short_name as nama_ttd,
							p.jabatan as jabatan
					')
					->from('purchase_order f')
					->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
					->where('f.status != 5') // tidak tampilkan yang dihapus
					->generate();
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
									p.jabatan as jabatan
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
						p.jabatan as jabatan
						')
						->from('purchase_order f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->where('f.status != 5')
						->where('f.id_pengaju', $id)
						->generate();
			}

			

			//Surat Izin Jam Kerja
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
						p.jabatan as jabatan
					')
					->from('purchase_order f')
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
					->from('purchase_order_detail f')
					->where('f.id_po', $id);
					return $this->db->get()->result();
			}

			function getDetailPOByIdEdit($id)
			{
					$where = array('purchase_order_detail.id_pod' => $id);    
        	return $this->db
                ->select('
								purchase_order_detail.id_pod as id_pod,
								purchase_order_detail.id_po as id_po,
								purchase_order_detail.nama_barang as namabarang,
								purchase_order_detail.kode_barang as kodebarang,
								purchase_order_detail.isibulan_1,
								purchase_order_detail.isibulan_2,
								purchase_order_detail.isibulan_3,
								purchase_order_detail.stok_gudang,
								purchase_order_detail.stok_po,
								purchase_order_detail.kebutuhan_po,
								purchase_order_detail.rencana_po,
								purchase_order_detail.supplier,
								purchase_order_detail.detail,
								purchase_order_detail.keterangan2
							')
						->get_where('purchase_order_detail', $where)
            ->result();
			}

			function updateKeterangan($id, $data)
    {
        $this->db->where('id_pod', $id);
        $this->db->update('purchase_order_detail', $data);
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
						p.jabatan as jabatan
					')
					->from('purchase_order f')
					->join('purchase_order_detail ff', 'f.id=ff.id_po')
					->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
					->where('f.id', $id);
					return $this->db->get()->result();
			}

			


			// fungsi reset urutan id pada tabel
			function reset_increment($tabel){
				$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
			}


			function updatePO($id, $data)
			{
				$this->db->where('id', $id);
				$this->db->update('purchase_order', $data);
			}


			function getSupplier()
			{
					$this->db->order_by('k.nama_pemasok', 'ASC');
					return $this->db->get('pemasok_utama k')->result();
			}


			

	

}