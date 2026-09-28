<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_inventory_new extends CI_Model
{



		// fungsi reset urutan id pada tabel
		function reset_increment($tabel){
				$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
		}

		//=======================================
		//====   Start PO Pending  =====
		//=======================================
		
		//Add
		function addPo($data)
			{
					$this->db->insert('po_pending', $data);
			}

		function addPoStatus($data)
			{
					$this->db->insert('po_pending_status', $data);
			}

		function update($where, $data)
        {
            $this->db->where($where);
            $this->db->update('po_pending', $data);
        }


        function getById($id)
        {
            return $this->db->get_where('po_pending e', array('e.id' => $id))->result();
        }

        function getByWhere($param="") 
        {
            $this->db->where('e.status', 1);
            return $this->db->get('po_pending e')->result();
        }

        function getTrackLastId(){
                return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('po_pending')->row();
        }

        function getAllCustomer()
        {
            $this->db->where('status', 1);
            $this->db->order_by('k.nama_customer', 'ASC');
            return $this->db->get('customer k')->result();
        }

        


        function getAll() {
            $this->db->order_by('t.created_at', 'DESC');
            return $this->datatables
                ->select('  
                    t.id,
                    t.nama_customer,
                    t.nama_marketing,
                    t.sistem_pembayaran,
					t.item1,
					t.item2,
					t.item3,
					t.item4,
					t.item5,
					t.item6,
					t.item7,
                    t.tanggal_po,
                    t.status,
                    t.created_at,
                    (SELECT ts.id_status FROM po_pending_status ts WHERE ts.id_po = t.id ORDER BY ts.created_at DESC LIMIT 1) AS id_status,
                    (SELECT ts.remarks FROM po_pending_status ts WHERE ts.id_po = t.id ORDER BY ts.created_at DESC LIMIT 1) AS remarks
                ')
                ->from('po_pending t')
                ->where('t.status = 1')
                ->generate();
        }



        function getDetailPoById($id)
		{
				$this->db->select('
					
						f.id as idGc,
						f.tanggal_po,
						f.id_pengaju as idPengaju,
						f.item1,
						f.item2,
						f.item3,
						f.item4,
						f.item5,
						f.item6,
						f.item7,
						f.nama_customer,
						f.sistem_pembayaran,
						f.nama_marketing,
						f.created_at
				')
				->from('po_pending f')
				->where('f.id', $id);
				return $this->db->get()->result();
		}




    function getUpdateById($where)
    {
        $this->db->select('
							tu.id,
                            t.id,
                            t.id_po,
                            t.id_status,
                            t.remarks,
                            t.created_at,
                            p1.nama as nama_pembuat
                        ')
            ->from('po_pending_status t')
            ->join('po_pending tu', 't.id_po=tu.id')
            ->join('pengguna p1', 't.id_pengguna=p1.pengguna_id')
            ->where($where)
            ->order_by('t.created_at', 'DESC');
        return $this->db->get()->result();
    }



		//=======================================
		//======   End PO Pending  =====
		//=======================================






	

	



}