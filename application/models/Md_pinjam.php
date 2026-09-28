<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_pinjam extends CI_Model
{

		function reset_increment($tabel){
				$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
		}


		//Add
		function addPinjam($data)
			{
					$this->db->insert('pinjam', $data);
			}

			function addPinjamDetail($data)
			{
					$this->db->insert('pinjam_detail', $data);
			}
			
			function addPinjamStatus($data)
			{
					$this->db->insert('pinjam_status', $data);
			}

		//UPDATE 
		function updatePinjam($id, $data)
			{
				$this->db->where('id', $id);
				$this->db->update('pinjam', $data);
			}

			function updatePinjamDetail($id, $data)
			{
				$this->db->where('id', $id);
				$this->db->update('pinjam_detail', $data);
			}

		//GET
		function getPinjamKodeId(){
			return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('pinjam',array('YEAR(`created_at`)' => date('Y')))->row();
		}

		function getPinjamLastId(){
			return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('pinjam')->row();
		}

		function getDivisi($pengguna_id)
    {
        return $this->db->select('id_divisi')
                        ->from('pengguna')
                        ->where('pengguna_id', $pengguna_id)
                        ->get()
                        ->row(); 
    }


		function getAllPinjam()
		{
				$searchArray = $this->input->post('search', TRUE);
				$keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

				$this->db->select('
						p.id,
						p.kode,
						p.id_peminjam,
						pg.nama AS nama_peminjam,
						pg.jabatan AS jabatan_peminjam,
						p.jenis,
						p.keperluan,
						p.idttd_1,
						p.idttd_2,
						p.ttd_1,
						p.ttd_2,
						p.kota_aju,
						p.link,
						p.created_at,
						(
								SELECT ps.status 
								FROM pinjam_status ps
								WHERE ps.id_pinjam = p.id
								ORDER BY ps.created_at DESC 
								LIMIT 1
						) AS status
				');
				$this->db->from('pinjam p');
				$this->db->join('pengguna pg', 'pg.pengguna_id = p.id_peminjam', 'left');

				// 🔍 Search hanya di kode, nama, jabatan
				if (!empty($keyword)) {
						$this->db->group_start();
						$this->db->like('p.kode', $keyword);
						$this->db->or_like('pg.nama', $keyword);
						$this->db->or_like('pg.jabatan', $keyword);
						$this->db->group_end();
				}

				// 🔢 Ordering
				$columns = [
						'p.id', 'p.kode', 'pg.nama', 'pg.jabatan',
						'p.jenis', 'p.keperluan', 'p.kota_aju', 'p.created_at'
				];
				$orderCol = $columns[0];
				$orderDir = 'DESC';
				if ($this->input->post('order')) {
						$colIdx   = $this->input->post('order')[0]['column'];
						$orderCol = isset($columns[$colIdx]) ? $columns[$colIdx] : $columns[0];
						$orderDir = $this->input->post('order')[0]['dir'] === 'desc' ? 'DESC' : 'ASC';
				}
				$this->db->order_by($orderCol, $orderDir);

				// 📊 Pagination
				$length = $this->input->post('length');
				$start  = $this->input->post('start');
				if ($length !== null && $length != -1) {
						$this->db->limit(intval($length), intval($start ?? 0));
				}

				$data = $this->db->get()->result();

				// Total tanpa filter
				$this->db->reset_query();
				$this->db->select('COUNT(*) as total');
				$this->db->from('pinjam p');
				$recordsTotal = $this->db->get()->row()->total;

				// Total dengan filter
				$this->db->reset_query();
				$this->db->select('COUNT(DISTINCT p.id) as total');
				$this->db->from('pinjam p');
				$this->db->join('pengguna pg', 'pg.pengguna_id = p.id_peminjam', 'left');
				if (!empty($keyword)) {
						$this->db->group_start();
						$this->db->like('p.kode', $keyword);
						$this->db->or_like('pg.nama', $keyword);
						$this->db->or_like('pg.jabatan', $keyword);
						$this->db->group_end();
				}
				$recordsFiltered = $this->db->get()->row()->total;

				return [
						'draw' => intval($this->input->post('draw')),
						'recordsTotal' => $recordsTotal,
						'recordsFiltered' => $recordsFiltered,
						'data' => $data
				];
		}



		function getPinjamById($id)
		{
				$this->db->select('
						p.id,
						p.kode,
						p.id_peminjam,
						p.jenis,
						p.keperluan,
						p.idttd_1,
						p.idttd_2,
						p.ttd_1,
						p.ttd_2,
						p.kota_aju,
						p.link,
						p.created_at,
						u.nama as nama_peminjam,
						u.jabatan as jabatan_peminjam,
						u.no_pegawai as npp,
						u2.short_name as nama1,
						u2.jabatan as jabatan1,
						u3.short_name as nama2,
						u3.jabatan as jabatan2,
						(
								SELECT ps.status 
								FROM pinjam_status ps
								WHERE ps.id_pinjam = p.id
								ORDER BY ps.created_at DESC 
								LIMIT 1
						) as status
				')
				->from('pinjam p')
				->join('pengguna u', 'p.id_peminjam = u.pengguna_id', 'left')
				->join('pengguna u2', 'p.idttd_1 = u2.pengguna_id', 'left')
				->join('pengguna u3', 'p.idttd_2 = u3.pengguna_id', 'left')
				->where('p.id', $id);

				return $this->db->get()->row();
		}

		
		function getDetailPinjam($id)
		{
				$this->db->select('
						d.id,
						d.id_pinjam,
						d.nama,
						d.jumlah,
						d.keterangan,
						d.serial_number
				')
				->from('pinjam_detail d')
				->where('d.id_pinjam', $id);

				return $this->db->get()->result();
		}

		function getUpdateById($where)
    {
        $this->db->select('
														tu.id,
                            t.bukti_penerima,
                            t.nama_penerima,
                            t.tgl_penerima,
                            t.id_pinjam,
                            t.status,
                            t.keterangan_konfirmasi,
                            t.created_at,
                            p1.nama as nama_pembuat
                        ')
            ->from('pinjam_status t')
            ->join('pinjam tu', 't.id_pinjam=tu.id')
            ->join('pengguna p1', 't.id_pengguna=p1.pengguna_id')
            ->where($where)
            ->order_by('t.created_at', 'DESC');
        return $this->db->get()->result();
    }


		function getById($id)
    {
        return $this->db->get_where('pinjam e', array('e.id' => $id))->result();
    }






		function getDetailApprovalById($id)
    {
        return $this->db->get_where('surat_aprv_detail p', array('p.id' => $id))->result();
    }







}