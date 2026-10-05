<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_kalkulator extends CI_Model
{

    function add($data)
    {
        $this->db->insert('kalkulator_pricelist', $data);
    }


//======================================
//======================================
//===========    SWASTA    =============
//======================================
//======================================

    

    function getFilterMerkSwasta($param = "")
    {
        $this->db->distinct();
        $this->db->select('kb.merk');
        $this->db->where('kb.jenis', 1);
        $this->db->order_by('kb.merk', 'ASC');
        return $this->db->get('kalkulator_pricelist kb')->result();
    }

    function getFilterNamaSwasta($param = "")
    {
        $this->db->distinct();
        $this->db->select('kb.nama');
        $this->db->where('kb.jenis', 1);
        $this->db->order_by('kb.nama', 'ASC');
        return $this->db->get('kalkulator_pricelist kb')->result();
    }


    public function getAll()
    {
        $searchArray = $this->input->post('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

        $this->db->select('b.id, b.nama, b.merk, b.harga, b.created_at, b.jenis');
        $this->db->from('kalkulator_pricelist b');

        // Pencarian di kolom merk dan nama saja
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('b.merk', $keyword);
            $this->db->or_like('b.nama', $keyword);
            $this->db->group_end();
        }

        // Filter dropdown
        if ($this->input->post('filter_merk')) {
            $this->db->where('b.merk', $this->input->post('filter_merk', TRUE));
        }

        if ($this->input->post('filter_nama')) {
            $this->db->where('b.nama', $this->input->post('filter_nama', TRUE));
        }

        // Hanya ambil data dengan b.jenis = 1
        $this->db->where('b.jenis', 1);

        // Urutan default
        $orderColumn = 'b.id';
        $orderDir = 'ASC';

        // Urutan dari datatables
        if (isset($_POST['order']) && !empty($_POST['order'])) {
            $columnIndex = $_POST['order'][0]['column'];
            $dir = $_POST['order'][0]['dir'];
            $columns = ['b.id', 'b.merk', 'b.nama', 'b.harga', 'b.created_at']; // Sesuaikan index DataTables
            if (isset($columns[$columnIndex])) {
                $orderColumn = $columns[$columnIndex];
                $orderDir = (strtolower($dir) === 'desc') ? 'DESC' : 'ASC';
            }
        }

        $this->db->order_by($orderColumn, $orderDir);

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $data = $this->db->get()->result();

        // Hitung total semua
        $recordsTotal = $this->db->count_all('kalkulator_pricelist');

        // Hitung setelah filter
        $this->db->select('COUNT(*) as count');
        $this->db->from('kalkulator_pricelist b');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('b.merk', $keyword);
            $this->db->or_like('b.nama', $keyword);
            $this->db->group_end();
        }

        if ($this->input->post('filter_merk')) {
            $this->db->where('b.merk', $this->input->post('filter_merk', TRUE));
        }

        if ($this->input->post('filter_nama')) {
            $this->db->where('b.nama', $this->input->post('filter_nama', TRUE));
        }

        // Filter jenis juga untuk hitung total setelah filter
        $this->db->where('b.jenis', 1);

        $recordsFiltered = $this->db->get()->row()->count;

        return [
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];
    }



    function getAll11()
    {
        if ($this->input->post('filter_merk'))
            $this->datatables->where('b.merk', $this->input->post('filter_merk', TRUE));

        if ($this->input->post('filter_nama')) {
            $this->datatables->where('b.nama', $this->input->post('filter_nama', TRUE));
        }


        $this->db->order_by('b.id', 'ASC');
        
        return $this->datatables
            ->select('  
            b.id,  
            b.nama,  
            b.merk,  
            b.harga,  
            b.created_at
            ')
            ->from('kalkulator_pricelist b')
            ->generate();
    }


//======================================
//======================================
//===========    GOVERNMENT    =========
//======================================
//======================================


    function getFilterMerkGov($param = "")
    {
        $this->db->distinct();
        $this->db->select('kb.merk');
        $this->db->where('kb.jenis', 2);
        $this->db->order_by('kb.merk', 'ASC');
        return $this->db->get('kalkulator_pricelist kb')->result();
    }

    function getFilterNamaGov($param = "")
    {
        $this->db->distinct();
        $this->db->select('kb.nama');
        $this->db->where('kb.jenis', 2);
        $this->db->order_by('kb.nama', 'ASC');
        return $this->db->get('kalkulator_pricelist kb')->result();
    }


    public function getAllGov()
    {
        $searchArray = $this->input->post('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

        $this->db->select('b.id, b.nama, b.merk, b.harga, b.created_at, b.jenis');
        $this->db->from('kalkulator_pricelist b');

        // Pencarian di kolom merk dan nama saja
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('b.merk', $keyword);
            $this->db->or_like('b.nama', $keyword);
            $this->db->group_end();
        }

        // Filter dropdown
        if ($this->input->post('filter_merk')) {
            $this->db->where('b.merk', $this->input->post('filter_merk', TRUE));
        }

        if ($this->input->post('filter_nama')) {
            $this->db->where('b.nama', $this->input->post('filter_nama', TRUE));
        }

        // Hanya ambil data dengan b.jenis = 2
        $this->db->where('b.jenis', 2);

        // Urutan default
        $orderColumn = 'b.id';
        $orderDir = 'ASC';

        // Urutan dari datatables
        if (isset($_POST['order']) && !empty($_POST['order'])) {
            $columnIndex = $_POST['order'][0]['column'];
            $dir = $_POST['order'][0]['dir'];
            $columns = ['b.id', 'b.merk', 'b.nama', 'b.harga', 'b.created_at']; // Sesuaikan index DataTables
            if (isset($columns[$columnIndex])) {
                $orderColumn = $columns[$columnIndex];
                $orderDir = (strtolower($dir) === 'desc') ? 'DESC' : 'ASC';
            }
        }

        $this->db->order_by($orderColumn, $orderDir);

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $data = $this->db->get()->result();

        // Hitung total semua
        $recordsTotal = $this->db->count_all('kalkulator_pricelist');

        // Hitung setelah filter
        $this->db->select('COUNT(*) as count');
        $this->db->from('kalkulator_pricelist b');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('b.merk', $keyword);
            $this->db->or_like('b.nama', $keyword);
            $this->db->group_end();
        }

        if ($this->input->post('filter_merk')) {
            $this->db->where('b.merk', $this->input->post('filter_merk', TRUE));
        }

        if ($this->input->post('filter_nama')) {
            $this->db->where('b.nama', $this->input->post('filter_nama', TRUE));
        }

        // Filter jenis juga untuk hitung total setelah filter
        $this->db->where('b.jenis', 2);

        $recordsFiltered = $this->db->get()->row()->count;

        return [
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];
    }

    public function getDataSwasta($filter_merk = '', $filter_nama = '', $keyword = '')
    {
        $this->db->select('b.id, b.nama, b.merk, b.harga, b.created_at, b.jenis');
        $this->db->from('kalkulator_pricelist b');
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('b.merk', $keyword);
            $this->db->or_like('b.nama', $keyword);
            $this->db->group_end();
        }
        if (!empty($filter_merk)) {
            $this->db->where('b.merk', $filter_merk);
        }
        if (!empty($filter_nama)) {
            $this->db->where('b.nama', $filter_nama);
        }
        $this->db->where('b.jenis', 1);
        $this->db->order_by('b.merk', 'ASC');
        $this->db->order_by('b.nama', 'ASC');
        return $this->db->get()->result();
    }

    public function getDataGov($filter_merk = '', $filter_nama = '', $keyword = '')
    {
        $this->db->select('b.id, b.nama, b.merk, b.harga, b.created_at, b.jenis');
        $this->db->from('kalkulator_pricelist b');
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('b.merk', $keyword);
            $this->db->or_like('b.nama', $keyword);
            $this->db->group_end();
        }
        if (!empty($filter_merk)) {
            $this->db->where('b.merk', $filter_merk);
        }
        if (!empty($filter_nama)) {
            $this->db->where('b.nama', $filter_nama);
        }
        $this->db->where('b.jenis', 2);
        $this->db->order_by('b.merk', 'ASC');
        $this->db->order_by('b.nama', 'ASC');
        return $this->db->get()->result();
    }

}

