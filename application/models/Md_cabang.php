<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_cabang extends CI_Model {

    public function checkColumns()
    {
        if (!$this->db->field_exists('latitude', 'cabang')) {
            $this->load->dbforge();
            $fields = [
                'latitude' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                'longitude' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                'radius' => ['type' => 'INT', 'constraint' => 11, 'default' => 100]
            ];
            $this->dbforge->add_column('cabang', $fields);
        }
    }

    function add($data)
    {
        $this->checkColumns();
        $this->db->insert('cabang', $data);
    }

    function update($where, $data)
    {
        $this->checkColumns();
        $this->db->where($where);
        $this->db->update('cabang', $data);
    }

    function getById($id)
    {
        $this->checkColumns();
        return $this->db->get_where('cabang c', array('c.id_cabang' => $id))->result();
    }

    function getByWhere($param="") 
    {
        $this->checkColumns();
        $this->db->where('c.status', 1);
        return $this->db->get('cabang c')->result();
    }

    function getActiveBranches()
    {
        $this->checkColumns();
        return $this->db->where('status', 1)
            ->where('latitude IS NOT NULL', NULL, FALSE)
            ->where('longitude IS NOT NULL', NULL, FALSE)
            ->where("latitude != ''", NULL, FALSE)
            ->where("longitude != ''", NULL, FALSE)
            ->get('cabang')
            ->result();
    }

    function getAll(){
        $this->checkColumns();
        return $this->datatables
        ->select('  
            c.id_cabang,
            c.nama_cabang,
            c.penanggung_jawab,
            c.alamat_cabang,
            c.latitude,
            c.longitude,
            c.radius,
            c.status,
        ')
        ->from('cabang c')
        ->where('c.status = 1')
        ->generate();        
    }
}
