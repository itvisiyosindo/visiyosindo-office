<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_Serah_terima_barang extends CI_Model
{


	// Add
	function addSTB($data)
	{
			$this->db->insert('surat_stb', $data);
	}

	function addSTBdetail($data)
	{
			$this->db->insert('surat_stb_detail', $data);
	}

	

	function addNotifikasi($data){
			$this->db->insert('notifikasipengguna', $data);
	}


	//GET
	function getKodeId(){
		return $this->db->select("COUNT(*) as id_stb")->limit(1)->order_by('id_stb',"DESC")->get_where('surat_stb',array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function getLastId(){
		return $this->db->select("*")->limit(1)->order_by('id_stb',"DESC")->get('surat_stb')->row();
	}

	function getAllCustomer()
    {
        $this->db->order_by('k.nama_customer', 'ASC');
        return $this->db->get('customer k')->result();
    }

		function update($id, $data)
    {
        $this->db->where('id_stb', $id);
        $this->db->update('surat_stb', $data);
    }


		 function getById($id)
    {
        return $this->db->get_where('surat_stb p', array('p.id_stb' => $id))->result();
    }

		function updateDetail($id, $data)
    {
        $this->db->where('id_dstb', $id);
        $this->db->update('surat_stb_detail', $data);
    }


		 function getByIdDetail($id)
    {
        return $this->db->get_where('surat_stb_detail p', array('p.id_dstb' => $id))->result();
    }
	

	

		
		


			function getAllSTB()
			{
			$this->db->order_by('f.id_stb', 'DESC');
				return $this->datatables
					->select('
						f.id_stb,
						f.kode_stb,
						f.id_customer,
						f.id_pihak1,
						f.id_pengaju as idPengaju,
						f.kota_pengajuan as kota_pengajuan,
						f.tgl_pengajuan as tgl_Pengajuan,
						p.nama as pengaju,
						c.nama_customer,
						c1.nama_customer as nama_pihak1,
						')
						->from('surat_stb f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('customer c', 'f.id_customer=c.id_customer')
						->join('customer c1', 'f.id_pihak1=c1.id_customer')
						->generate();
			}

		

			function getAllSTBbyID($id)
			{
			$this->db->order_by('f.id_Stb', 'DESC');
				return $this->datatables
				->select('
						f.id_stb,
						f.kode_stb,
						f.id_customer,
						f.id_pihak1,
						f.id_pengaju as idPengaju,
						f.kota_pengajuan as kota_pengajuan,
						f.tgl_pengajuan as tgl_Pengajuan,
						p.nama as pengaju,
						c.nama_customer,
						c1.nama_customer as nama_pihak1,
						')
						->from('surat_stb f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('customer c', 'f.id_customer=c.id_customer')
						->join('customer c1', 'f.id_pihak1=c1.id_customer')
						->where('f.id_pengaju', $id)
						->generate();
			}

			


			function getSTBById($id)
			{
					$this->db->select('
						f.id_stb,
						f.kode_stb,
						f.id_customer,
						f.id_pihak1,
						f.id_pengaju as idPengaju,
						f.kota_pengajuan as kota_pengajuan,
						f.tgl_pengajuan as tgl_Pengajuan,
						p.nama as pengaju,
						p.jabatan,
						c.nama_customer,
						c.alamat_customer,
						c1.nama_customer as nama_pihak1,
						c1.alamat_customer as alamat_pihak1
						')
					->from('surat_stb f')
					->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
					->join('customer c', 'f.id_customer=c.id_customer')
					->join('customer c1', 'f.id_pihak1=c1.id_customer')
					->where('f.id_stb', $id);
					return $this->db->get()->result();
			}

			function getDetailSTBById($id)
			{
					$this->db->select('
						f.id_dstb,
						f.nomor,
						f.id_stb,
						f.nama_barang,
						f.merk,
						f.no_batch,
						f.qty,
						f.satuan,
						f.ket,
						f.status
					')
					->from('surat_stb_detail f')
					->where('f.id_stb', $id)
					->where('f.status', 1);
					return $this->db->get()->result();
			}

			// fungsi reset urutan id pada tabel
			function reset_increment($tabel){
				$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
			}


			
			



}