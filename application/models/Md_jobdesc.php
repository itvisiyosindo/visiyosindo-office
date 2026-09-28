<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_jobdesc extends CI_Model
{


	// Add
	function addJob($data)
	{
			$this->db->insert('jobdesc', $data);
	}

	function addJobdetail($data)
    {
        $result = $this->db->insert('jobdesc_detail', $data);
    
        if (!$result) {
            log_message('error', 'Gagal insert jobdesc_detail: ' . $this->db->last_query());
            log_message('error', 'Database error: ' . print_r($this->db->error(), true));
        }
    
        return $result;
    }

	function updateDetail($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('jobdesc_detail', $data);
    }
	


	function getLastId(){
		return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('jobdesc')->row();
	}

	

		
				function getAllPo()
        		{
        			$this->db->order_by('p.nama', 'ASC');
        			return $this->datatables
        					->select('
        							f.id as id_po,
        							f.id_pengguna,
        							f.tgl_mulai,
        							f.tgl_selesai,
        							p.nama as pegawai,
        							p.no_pegawai,
        							p.jabatan as jabatan
        							')
        					->from('jobdesc f')
        					->join('pengguna p', 'f.id_pengguna=p.pengguna_id')
        					->generate();
        		}

			

			/*function getAllPObyID($id)
			{
				$this->db->order_by('f.id', 'DESC');
				return $this->datatables
						->select('
								f.id as id_po,
								f.id_pengguna,
								p.nama as pegawai,
								p.no_pegawai,
								p.jabatan as jabatan
								')
						->from('jobdesc f')
						->join('pengguna p', 'f.id_pengguna=p.pengguna_id')
						->where('f.id_pengguna', $id)
						->generate();
			}

			function getDetailPOByIdEdit($id)
			{
					$where = array('jobdesc_detail.id' => $id);    
        	return $this->db
                ->select('
								jobdesc_detail.id as id,
								jobdesc_detail.id_jobdesc as id_jobdesc,
								jobdesc_detail.deskripsi as deskripsi
							')
						->get_where('jobdesc_detail', $where)
            ->result();
			}


			*/

			function getAllDetailbyID($id)
			{
				$this->db->order_by('f.id', 'ASC');
				return $this->datatables
						->select('
								f.id as id_po,
								f.idPengguna,
								f.deskripsi,
								f.nilai,
								f.status
								')
						->from('jobdesc_detail f')
						->where('f.idPengguna', $id)
						->where('f.status = 1')
						->generate();
			}

			

			    function getJobById($id)
                {
                    $this->db->select('
                            f.id as id_po,
                            f.id_pengguna,
                            f.tgl_mulai,
                            f.tgl_selesai,
                            p.nama as pegawai,
                            p.no_pegawai,
                            p.jabatan as jabatan
                    ')
                    ->from('jobdesc f')
                    ->join('pengguna p', 'f.id_pengguna=p.pengguna_id')
                    ->where('f.id', $id);
                    return $this->db->get()->row(); 
                }


				function getDetailPOById($id_jobdesc, $idPengguna = null)
            	{
            		$this->db->order_by('f.point', 'ASC');
            		$this->db->order_by('f.id_urut', 'ASC');
            		$this->db->select('
            			f.id as id_pod,
            			f.id_jobdesc,
            			f.id_urut,
            			f.nilai,
            			f.point,
            			f.idPengguna,
            			f.deskripsi,
            			f.status
            		')
            		->from('jobdesc_detail f')
            		->where('f.status', 1);
            
            		if (!empty($idPengguna)) {
            			$this->db->group_start();
            				$this->db->where('f.id_jobdesc', $id_jobdesc);
            				$this->db->or_group_start();
            					$this->db->where('f.idPengguna', $idPengguna);
            					$this->db->group_start();
            						$this->db->where('f.id_jobdesc IS NULL');
            						$this->db->or_where('f.id_jobdesc', 0);
            					$this->db->group_end();
            				$this->db->group_end();
            			$this->db->group_end();
            		} else {
            			$this->db->where('f.id_jobdesc', $id_jobdesc);
            		}
            
            		return $this->db->get()->result();
            	}

// 			function getById($id)
// 			{
// 					return $this->db->get_where('jobdesc_detail p', array('p.id' => $id))->result();
// 			}

			
// 			// fungsi reset urutan id pada tabel
// 			function reset_increment($tabel){
// 				$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
// 			}

            function getById($id)
                {
                    return $this->db->get_where('jobdesc_detail p', array('p.id' => $id))->result();
                }
                
                
                function getNextUrutByPengguna($idPengguna)
                {
                    $row = $this->db
                        ->select_max('id_urut', 'max_urut')
                        ->where('idPengguna', $idPengguna)
                        ->get('jobdesc_detail')
                        ->row();
                
                    return ((int) $row->max_urut) + 1;
                }
                
                
                // fungsi reset urutan id pada tabel
                function reset_increment($tabel){
                    $this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
                }
                
                    public function updateJobdescTanggal($id_po, $data)
                {
                    $this->db->where('id', $id_po);
                    $this->db->update('jobdesc', $data);
                }

    public function deleteJobdescById($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('jobdesc');
    }

    public function deleteJobdescDetailByJobId($id_jobdesc)
    {
        $this->db->where('id_jobdesc', $id_jobdesc);
        $this->db->delete('jobdesc_detail');
    }


}