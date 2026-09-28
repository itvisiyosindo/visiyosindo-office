<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_acc_pemasok extends CI_Model
{
    // ==========================================
    // PEMASOK MAIN CRUD
    // ==========================================

    public function getPemasokById($id)
    {
        $this->db->select('p.*, s.nama as status_nama, t.nama as tipe_nama');
        $this->db->from('acc_pemasok p');
        $this->db->join('acc_master_status_pemasok s', 'p.id_status = s.id', 'left');
        $this->db->join('acc_master_tipe_pemasok t', 'p.id_tipe = t.id', 'left');
        $this->db->where('p.id', $id);
        $this->db->where('p.status_data', 1);
        $pemasok = $this->db->get()->row();

        if ($pemasok) {
            // Get products
            $this->db->select('pr.*');
            $this->db->from('acc_pemasok_product pp');
            $this->db->join('acc_master_product pr', 'pp.id_product = pr.id');
            $this->db->where('pp.id_pemasok', $id);
            $pemasok->products = $this->db->get()->result();

            // Get hospital expo
            $this->db->select('he.*');
            $this->db->from('acc_pemasok_hospital_expo phe');
            $this->db->join('acc_master_hospital_expo he', 'phe.id_hospital_expo = he.id');
            $this->db->where('phe.id_pemasok', $id);
            $pemasok->hospital_expos = $this->db->get()->result();

            // Get sertifikasi
            $pemasok->sertifikasi = $this->db->get_where('acc_pemasok_sertifikasi', ['id_pemasok' => $id])->result();

            // Get agreement
            $pemasok->agreement = $this->db->get_where('acc_pemasok_agreement', ['id_pemasok' => $id])->result();

            // Get brochure
            $pemasok->brochure = $this->db->get_where('acc_pemasok_brochure', ['id_pemasok' => $id])->result();

            // Get harga
            $pemasok->harga = $this->db->get_where('acc_pemasok_harga', ['id_pemasok' => $id])->result();
        }

        return $pemasok;
    }

    public function addPemasok($data)
    {
        $this->db->insert('acc_pemasok', $data);
        return $this->db->insert_id();
    }

    public function updatePemasok($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('acc_pemasok', $data);
    }

    public function deletePemasok($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('acc_pemasok', ['status_data' => 2]); // soft delete
    }

    // ==========================================
    // STATS & DASHBOARD
    // ==========================================

    public function getPemasokStats()
    {
        $current_year = date('Y');

        $this->db->select("
            COUNT(*) as total,
            SUM(CASE WHEN id_status = 1 THEN 1 ELSE 0 END) as aktif,
            SUM(CASE WHEN id_status = 2 THEN 1 ELSE 0 END) as tidak_aktif,
            SUM(CASE WHEN id_status = 3 THEN 1 ELSE 0 END) as referensi,
            SUM(CASE WHEN YEAR(tanggal_loa_awal) = {$current_year} AND id_status = 1 THEN 1 ELSE 0 END) as baru
        ");
        $this->db->from('acc_pemasok');
        $this->db->where('status_data', 1);
        return $this->db->get()->row();
    }

    // ==========================================
    // SERVER SIDE DATATABLE QUERY
    // ==========================================

    public function getPemasokDatatable($filters = [])
    {
        $searchArray = $this->input->post_get('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';
        if (empty($keyword)) {
            $keyword = isset($filters['keyword']) ? trim($filters['keyword']) : '';
        }

        $limit = $this->input->post_get('length', TRUE);
        $limit = ($limit !== null) ? intval($limit) : -1;
        $start = intval($this->input->post_get('start', TRUE) ?: 0);

        // 1. Get total records count
        $this->db->from('acc_pemasok p');
        $this->db->where('p.status_data', 1);
        $recordsTotal = $this->db->count_all_results();

        // 2. Build filtered query
        $this->db->select('p.*, s.nama as status_nama, t.nama as tipe_nama');
        $this->db->from('acc_pemasok p');
        $this->db->join('acc_master_status_pemasok s', 'p.id_status = s.id', 'left');
        $this->db->join('acc_master_tipe_pemasok t', 'p.id_tipe = t.id', 'left');
        $this->db->where('p.status_data', 1);

        // Apply filters
        if (!empty($filters['nama_pemasok'])) {
            $this->db->like('p.nama_pemasok', $filters['nama_pemasok']);
        }
        if (!empty($filters['id_status'])) {
            $this->db->where('p.id_status', intval($filters['id_status']));
        }
        if (!empty($filters['id_tipe'])) {
            $this->db->where('p.id_tipe', intval($filters['id_tipe']));
        }
        if (!empty($filters['negara'])) {
            $this->db->where('p.negara', $filters['negara']);
        }
        if (!empty($filters['id_product'])) {
            $this->db->join('acc_pemasok_product pp', 'p.id = pp.id_pemasok');
            $this->db->where('pp.id_product', intval($filters['id_product']));
        }
        if (!empty($filters['id_hospital_expo'])) {
            // Join only if not already joined above, but alias can prevent conflicts
            $this->db->join('acc_pemasok_hospital_expo phe', 'p.id = phe.id_pemasok');
            $this->db->where('phe.id_hospital_expo', intval($filters['id_hospital_expo']));
        }
        if (!empty($filters['is_baru'])) {
            $this->db->where('YEAR(p.tanggal_loa_awal)', date('Y'));
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('p.nama_pemasok', $keyword);
            $this->db->or_like('p.negara', $keyword);
            $this->db->or_like('p.alamat', $keyword);
            $this->db->or_like('p.kontak', $keyword);
            $this->db->group_end();
        }

        // Clone query for counting filtered
        $tempdb = clone $this->db;
        $recordsFiltered = $tempdb->count_all_results();

        // 3. Sorting & Limits
        $order = $this->input->post_get('order', TRUE);
        if ($order && isset($order[0])) {
            $col_idx = intval($order[0]['column']);
            $dir = $order[0]['dir'] === 'asc' ? 'asc' : 'desc';
            
            $columns_map = [
                0 => 'p.id',
                1 => 'p.nama_pemasok',
                2 => 's.nama',
                3 => 't.nama',
                4 => 'p.negara',
                5 => 'p.id' // product is complex, sort by ID
            ];

            if (isset($columns_map[$col_idx])) {
                $this->db->order_by($columns_map[$col_idx], $dir);
            }
        } else {
            $this->db->order_by('p.nama_pemasok', 'ASC');
        }

        if ($limit !== -1) {
            $this->db->limit($limit, $start);
        }

        $list = $this->db->get()->result();

        // Enrich list with products
        foreach ($list as $row) {
            $this->db->select('pr.nama');
            $this->db->from('acc_pemasok_product pp');
            $this->db->join('acc_master_product pr', 'pp.id_product = pr.id');
            $this->db->where('pp.id_pemasok', $row->id);
            $products = $this->db->get()->result();
            
            $p_names = [];
            foreach ($products as $pr) {
                $p_names[] = $pr->nama;
            }
            $row->product_list = !empty($p_names) ? implode(', ', $p_names) : '-';
        }

        return [
            'draw' => intval($this->input->post_get('draw', TRUE) ?: 0),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $list
        ];
    }

    public function getPemasokForExport($filters = [])
    {
        $this->db->select('p.*, s.nama as status_nama, t.nama as tipe_nama');
        $this->db->from('acc_pemasok p');
        $this->db->join('acc_master_status_pemasok s', 'p.id_status = s.id', 'left');
        $this->db->join('acc_master_tipe_pemasok t', 'p.id_tipe = t.id', 'left');
        $this->db->where('p.status_data', 1);

        if (!empty($filters['nama_pemasok'])) {
            $this->db->like('p.nama_pemasok', $filters['nama_pemasok']);
        }
        if (!empty($filters['id_status'])) {
            $this->db->where('p.id_status', intval($filters['id_status']));
        }
        if (!empty($filters['id_tipe'])) {
            $this->db->where('p.id_tipe', intval($filters['id_tipe']));
        }
        if (!empty($filters['negara'])) {
            $this->db->where('p.negara', $filters['negara']);
        }
        if (!empty($filters['id_product'])) {
            $this->db->join('acc_pemasok_product pp', 'p.id = pp.id_pemasok');
            $this->db->where('pp.id_product', intval($filters['id_product']));
        }
        if (!empty($filters['id_hospital_expo'])) {
            $this->db->join('acc_pemasok_hospital_expo phe', 'p.id = phe.id_pemasok');
            $this->db->where('phe.id_hospital_expo', intval($filters['id_hospital_expo']));
        }
        if (!empty($filters['is_baru'])) {
            $this->db->where('YEAR(p.tanggal_loa_awal)', date('Y'));
        }

        $this->db->order_by('p.nama_pemasok', 'ASC');
        $list = $this->db->get()->result();

        foreach ($list as $row) {
            $this->db->select('pr.nama');
            $this->db->from('acc_pemasok_product pp');
            $this->db->join('acc_master_product pr', 'pp.id_product = pr.id');
            $this->db->where('pp.id_pemasok', $row->id);
            $products = $this->db->get()->result();
            
            $p_names = [];
            foreach ($products as $pr) {
                $p_names[] = $pr->nama;
            }
            $row->product_list = !empty($p_names) ? implode(', ', $p_names) : '-';
        }

        return $list;
    }

    // ==========================================
    // MANY-TO-MANY RELATION SYNCS
    // ==========================================

    public function syncProducts($id_pemasok, $product_ids)
    {
        $this->db->delete('acc_pemasok_product', ['id_pemasok' => $id_pemasok]);
        if (!empty($product_ids)) {
            $data = [];
            foreach ($product_ids as $pid) {
                if (empty($pid)) continue;
                $data[] = [
                    'id_pemasok' => $id_pemasok,
                    'id_product' => intval($pid)
                ];
            }
            if (!empty($data)) {
                $this->db->insert_batch('acc_pemasok_product', $data);
            }
        }
    }

    public function syncHospitalExpo($id_pemasok, $expo_ids)
    {
        $this->db->delete('acc_pemasok_hospital_expo', ['id_pemasok' => $id_pemasok]);
        if (!empty($expo_ids)) {
            $data = [];
            foreach ($expo_ids as $eid) {
                if (empty($eid)) continue;
                $data[] = [
                    'id_pemasok' => $id_pemasok,
                    'id_hospital_expo' => intval($eid)
                ];
            }
            if (!empty($data)) {
                $this->db->insert_batch('acc_pemasok_hospital_expo', $data);
            }
        }
    }

    // ==========================================
    // SUB-TABLE DETAILS OPERATIONS
    // ==========================================

    // Sertifikasi
    public function saveSertifikasi($id_pemasok, $sertifikasi_rows)
    {
        $this->db->delete('acc_pemasok_sertifikasi', ['id_pemasok' => $id_pemasok]);
        if (!empty($sertifikasi_rows)) {
            foreach ($sertifikasi_rows as $row) {
                if (empty($row['nama_dokumen'])) continue;
                $this->db->insert('acc_pemasok_sertifikasi', [
                    'id_pemasok' => $id_pemasok,
                    'nama_dokumen' => $row['nama_dokumen'],
                    'tanggal_dokumen' => !empty($row['tanggal_dokumen']) ? date('Y-m-d', strtotime($row['tanggal_dokumen'])) : NULL,
                    'masa_berlaku' => !empty($row['masa_berlaku']) ? date('Y-m-d', strtotime($row['masa_berlaku'])) : NULL,
                    'link_dokumen' => $row['link_dokumen'],
                    'kolom_lainnya' => isset($row['kolom_lainnya']) ? json_encode($row['kolom_lainnya']) : NULL,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    // Agreement / LOA
    public function saveAgreement($id_pemasok, $agreement_rows)
    {
        $this->db->delete('acc_pemasok_agreement', ['id_pemasok' => $id_pemasok]);
        if (!empty($agreement_rows)) {
            foreach ($agreement_rows as $row) {
                if (empty($row['nama_dokumen'])) continue;
                $this->db->insert('acc_pemasok_agreement', [
                    'id_pemasok' => $id_pemasok,
                    'nama_dokumen' => $row['nama_dokumen'],
                    'tanggal_dokumen' => !empty($row['tanggal_dokumen']) ? date('Y-m-d', strtotime($row['tanggal_dokumen'])) : NULL,
                    'masa_berlaku' => !empty($row['masa_berlaku']) ? date('Y-m-d', strtotime($row['masa_berlaku'])) : NULL,
                    'link_dokumen' => $row['link_dokumen'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    // Brochure
    public function saveBrochure($id_pemasok, $brochure_rows)
    {
        $this->db->delete('acc_pemasok_brochure', ['id_pemasok' => $id_pemasok]);
        if (!empty($brochure_rows)) {
            foreach ($brochure_rows as $row) {
                if (empty($row['nama_dokumen'])) continue;
                $this->db->insert('acc_pemasok_brochure', [
                    'id_pemasok' => $id_pemasok,
                    'nama_dokumen' => $row['nama_dokumen'],
                    'tanggal_dokumen' => !empty($row['tanggal_dokumen']) ? date('Y-m-d', strtotime($row['tanggal_dokumen'])) : NULL,
                    'link_dokumen' => $row['link_dokumen'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    // Harga
    public function saveHarga($id_pemasok, $harga_rows)
    {
        $this->db->delete('acc_pemasok_harga', ['id_pemasok' => $id_pemasok]);
        if (!empty($harga_rows)) {
            foreach ($harga_rows as $row) {
                if (empty($row['nama_dokumen'])) continue;
                $this->db->insert('acc_pemasok_harga', [
                    'id_pemasok' => $id_pemasok,
                    'nama_dokumen' => $row['nama_dokumen'],
                    'tanggal_dokumen' => !empty($row['tanggal_dokumen']) ? date('Y-m-d', strtotime($row['tanggal_dokumen'])) : NULL,
                    'masa_berlaku' => !empty($row['masa_berlaku']) ? date('Y-m-d', strtotime($row['masa_berlaku'])) : NULL,
                    'link_dokumen' => $row['link_dokumen'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    // ==========================================
    // MASTER DATA GETTERS & SETTERS
    // ==========================================

    // Status
    public function getStatusList()
    {
        return $this->db->order_by('urutan', 'ASC')->get('acc_master_status_pemasok')->result();
    }
    public function addStatus($data)
    {
        return $this->db->insert('acc_master_status_pemasok', $data);
    }
    public function deleteStatus($id)
    {
        return $this->db->delete('acc_master_status_pemasok', ['id' => $id]);
    }

    // Tipe
    public function getTipeList()
    {
        return $this->db->order_by('nama', 'ASC')->get('acc_master_tipe_pemasok')->result();
    }
    public function addTipe($data)
    {
        return $this->db->insert('acc_master_tipe_pemasok', $data);
    }
    public function deleteTipe($id)
    {
        return $this->db->delete('acc_master_tipe_pemasok', ['id' => $id]);
    }

    // Product
    public function getProductList()
    {
        return $this->db->order_by('nama', 'ASC')->get('acc_master_product')->result();
    }
    public function addProduct($data)
    {
        return $this->db->insert('acc_master_product', $data);
    }
    public function deleteProduct($id)
    {
        return $this->db->delete('acc_master_product', ['id' => $id]);
    }

    // Hospital Expo
    public function getHospitalExpoList()
    {
        return $this->db->order_by('tahun', 'DESC')->order_by('nama', 'ASC')->get('acc_master_hospital_expo')->result();
    }
    public function addHospitalExpo($data)
    {
        return $this->db->insert('acc_master_hospital_expo', $data);
    }
    public function deleteHospitalExpo($id)
    {
        return $this->db->delete('acc_master_hospital_expo', ['id' => $id]);
    }

    // Country lookup stats (get all countries currently stored in database for filter option)
    public function getCountriesFromPemasok()
    {
        $this->db->select('DISTINCT(negara) as negara');
        $this->db->from('acc_pemasok');
        $this->db->where('status_data', 1);
        $this->db->where('negara IS NOT NULL');
        $this->db->where('negara !=', '');
        $this->db->order_by('negara', 'ASC');
        return $this->db->get()->result();
    }
}
