<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_pengujian extends CI_Model {

    function getUkesKodeId()
		{
			$this->db->select("COUNT(*) as id")
					->from('pengujian_visilab')
					->where(array(
							'YEAR(created_at)' => date('Y'),
							'id_pengujian' => '1',  // Kalau 1 = Ukes
					))
					->order_by('id', 'DESC')
					->limit(1);

			$result = $this->db->get()->row();
			return $result;
		}

    function getUparKodeId()
		{
			$this->db->select("COUNT(*) as id")
					->from('pengujian_visilab')
					->where(array(
							'YEAR(created_at)' => date('Y'),
							'id_pengujian' => '2',  // Kalau 1 = Upar
					))
					->order_by('id', 'DESC')
					->limit(1);

			$result = $this->db->get()->row();
			return $result;
		}

    function reset_increment($tabel){
			$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
		}

    function add($data)
    {
        $this->db->insert('pengujian_visilab', $data);
    }

    function getAllUkes()
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
				->select('
					f.id as idGc,
					f.jenis_uji,
					f.jenis_alat,
					f.nama_instansi,
					f.kode,
					f.id_pengaju as idPengaju,
					f.nama_alat,
					f.serial_number,
					f.id_pengujian,
					f.id_pelanggan,
					f.created_at,
					f.status,
					p.nama as pengaju,
					p.jabatan as jabatan,
					p.jabatan_visilab as jabatan_visilab,
					pl.identitas_pelanggan
					')
				->from('pengujian_visilab f')
				->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
				->join('pelanggan pl', 'f.id_pelanggan=pl.id_pelanggan')
				->where('f.status != 0')
				->where('f.id_pengujian = 1')
				->generate();
		}

    function getAllUpar()
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
				->select('
					f.id as idGc,
					f.jenis_uji,
					f.jenis_alat,
					f.nama_instansi,
					f.kode,
					f.id_pengaju as idPengaju,
					f.nama_alat,
					f.serial_number,
					f.id_pengujian,
					f.id_pelanggan,
					f.created_at,
					f.status,
					p.nama as pengaju,
					p.jabatan as jabatan,
					p.jabatan_visilab as jabatan_visilab,
					pl.identitas_pelanggan
					')
				->from('pengujian_visilab f')
				->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
				->join('pelanggan pl', 'f.id_pelanggan=pl.id_pelanggan')
				->where('f.status != 0')
				->where('f.id_pengujian = 2')
				->generate();
		}

    function getById($id)
    {
        return $this->db->get_where('pengujian_visilab e', array('e.id' => $id))->result();
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('pengujian_visilab', $data);
    }

    function getBywhereID($where)
    {
				$this->db->select('
						f.id as idGc,
						f.jenis_uji,
						f.jenis_alat,
						f.kode,
						f.id_pengaju as idPengaju,
						f.nama_alat,
						f.nama_instansi,
						f.serial_number,
						f.id_pengujian,
						f.id_pelanggan,
						f.created_at,
						f.status,
						f.biaya,
						f.jadwal,
						f.jadwal_end,
						f.form_ceklis,
						f.link_instalasi,
						f.link_sph,
						f.link_po,
						f.link_inv,
						f.link_lhu,
						f.link_sertifikat,
						f.ttd_1,
						f.ttd_2,
						f.ttd_3,
						f.ttd_4,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p.jabatan_visilab as jabatan_visilab,
						pl.identitas_pelanggan
								')
					->from('pengujian_visilab f')
					->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
					->join('pelanggan pl', 'f.id_pelanggan=pl.id_pelanggan')
					->where($where)
					->order_by('f.created_at', 'DESC');
        return $this->db->get()->result();
    }










    


}