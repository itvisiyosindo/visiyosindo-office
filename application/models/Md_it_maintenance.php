<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_it_maintenance extends CI_Model
{
    // ==========================================
    // CONFIGURATION MANAGEMENT
    // ==========================================

    public function getConfig($key)
    {
        $res = $this->db->get_where('it_maintenance_config', ['key' => $key])->row();
        return $res ? $res->value : '';
    }

    public function updateConfig($key, $value)
    {
        $this->db->where('key', $key);
        $this->db->update('it_maintenance_config', [
            'value' => $value,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->db->affected_rows();
    }

    // ==========================================
    // TICKET MANAGEMENT
    // ==========================================

    public function addTicket($data)
    {
        $this->db->insert('it_ticket', $data);
        return $this->db->insert_id();
    }

    public function updateTicket($id, $data)
    {
        $this->db->where('id_ticket', $id);
        $this->db->update('it_ticket', $data);
        return $this->db->affected_rows();
    }

    public function deleteTicket($id)
    {
        return $this->updateTicket($id, ['status_data' => 0]);
    }

    public function getTicketById($id)
    {
        return $this->db->select('
            t.*,
            p.nama as nama_pembuat,
            p.no_hp as no_hp_pembuat,
            pr.nama as nama_penerima,
            pr.no_hp as no_hp_penerima,
            a.nama as nama_aset,
            a.kode as kode_aset,
            a.kategori as kategori_aset
        ')
            ->from('it_ticket t')
            ->join('pengguna p', 't.id_pembuat=p.pengguna_id', 'left')
            ->join('pengguna pr', 't.id_penerima=pr.pengguna_id', 'left')
            ->join('aset a', 't.id_aset=a.id', 'left')
            ->where('t.id_ticket', $id)
            ->get()
            ->row();
    }

    public function getTicketsByWhere($where)
    {
        return $this->db->select('
            t.*,
            p.nama as nama_pembuat,
            pr.nama as nama_penerima,
            a.nama as nama_aset,
            a.kode as kode_aset
        ')
            ->from('it_ticket t')
            ->join('pengguna p', 't.id_pembuat=p.pengguna_id', 'left')
            ->join('pengguna pr', 't.id_penerima=pr.pengguna_id', 'left')
            ->join('aset a', 't.id_aset=a.id', 'left')
            ->where($where)
            ->order_by('t.created_at', 'DESC')
            ->get()
            ->result();
    }

    public function getTicketsDatatable($where = [])
    {
        $searchArray = $this->input->post('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

        $limit = intval($this->input->post('length', TRUE));
        $start = intval($this->input->post('start', TRUE));
        $status_filter = $this->input->post('status_filter', TRUE);

        // 1. Get total count
        $this->db->from('it_ticket t');
        $this->db->where('t.status_data', 1);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $recordsTotal = $this->db->count_all_results();

        // 2. Build data query
        $this->db->select('
            t.id_ticket,
            t.kode_tiket,
            t.prioritas,
            t.subject,
            t.status_tiket,
            t.created_at,
            p.nama as nama_pembuat,
            pr.nama as nama_penerima,
            a.nama as nama_aset,
            a.kode as kode_aset
        ');
        $this->db->from('it_ticket t');
        $this->db->join('pengguna p', 't.id_pembuat=p.pengguna_id', 'left');
        $this->db->join('pengguna pr', 't.id_penerima=pr.pengguna_id', 'left');
        $this->db->join('aset a', 't.id_aset=a.id', 'left');
        $this->db->where('t.status_data', 1);

        if (!empty($where)) {
            $this->db->where($where);
        }

        if ($status_filter !== '' && $status_filter !== null) {
            if ($status_filter === 'solved') {
                $this->db->group_start();
                $this->db->where_in('t.status_tiket', [3, 4]);
                $this->db->group_end();
            } else {
                $this->db->where('t.status_tiket', intval($status_filter));
            }
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('t.kode_tiket', $keyword);
            $this->db->or_like('t.subject', $keyword);
            $this->db->or_like('p.nama', $keyword);
            $this->db->or_like('pr.nama', $keyword);
            $this->db->or_like('a.nama', $keyword);
            $this->db->or_like('a.kode', $keyword);
            $this->db->group_end();
        }

        // Apply sorting (default by created_at DESC)
        $this->db->order_by('t.created_at', 'DESC');

        // Apply pagination limit
        if ($limit != -1) {
            $this->db->limit($limit, $start);
        }

        $results = $this->db->get()->result();

        // 3. Count filtered records
        $this->db->from('it_ticket t');
        $this->db->join('pengguna p', 't.id_pembuat=p.pengguna_id', 'left');
        $this->db->join('pengguna pr', 't.id_penerima=pr.pengguna_id', 'left');
        $this->db->join('aset a', 't.id_aset=a.id', 'left');
        $this->db->where('t.status_data', 1);

        if (!empty($where)) {
            $this->db->where($where);
        }

        if ($status_filter !== '' && $status_filter !== null) {
            if ($status_filter === 'solved') {
                $this->db->group_start();
                $this->db->where_in('t.status_tiket', [3, 4]);
                $this->db->group_end();
            } else {
                $this->db->where('t.status_tiket', intval($status_filter));
            }
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('t.kode_tiket', $keyword);
            $this->db->or_like('t.subject', $keyword);
            $this->db->or_like('p.nama', $keyword);
            $this->db->or_like('pr.nama', $keyword);
            $this->db->or_like('a.nama', $keyword);
            $this->db->or_like('a.kode', $keyword);
            $this->db->group_end();
        }
        $recordsFiltered = $this->db->count_all_results();

        // 4. Format data to flat array matching JS expectations
        $data = [];
        foreach ($results as $row) {
            $data[] = [
                encrypt($row->id_ticket), // Index 0: id_ticket
                $row->kode_tiket,        // Index 1: kode_tiket
                $row->subject,           // Index 2: subject
                $row->status_tiket,      // Index 3: status_tiket
                $row->created_at,        // Index 4: created_at
                $row->nama_pembuat,      // Index 5: nama_pembuat (pelapor)
                $row->nama_penerima,     // Index 6: nama_penerima (teknisi)
                $row->nama_aset,         // Index 7: nama_aset
                $row->kode_aset,         // Index 8: kode_aset
                $row->prioritas          // Index 9: prioritas
            ];
        }

        return [
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];
    }

    public function getTicketStats()
    {
        return [
            'total' => $this->db->where('status_data', 1)->count_all_results('it_ticket'),
            'open' => $this->db->where(['status_data' => 1, 'status_tiket' => 1])->count_all_results('it_ticket'),
            'progress' => $this->db->where(['status_data' => 1, 'status_tiket' => 2])->count_all_results('it_ticket'),
            'solved' => $this->db->where(['status_data' => 1])->where_in('status_tiket', [3, 4])->count_all_results('it_ticket'),
            'rejected' => $this->db->where(['status_data' => 1, 'status_tiket' => 5])->count_all_results('it_ticket'),
        ];
    }

    public function generateKodeTiket()
    {
        $tahun = date('Y');
        $bulan = date('n');

        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        $bulanRomawi = isset($romawi[$bulan]) ? $romawi[$bulan] : '';

        $lastKode = $this->db->select("MAX(CAST(SUBSTRING_INDEX(kode_tiket, '/', 1) AS UNSIGNED)) AS nomor")
            ->where('YEAR(created_at)', $tahun)
            ->get('it_ticket')
            ->row();

        $nomor = ($lastKode && !empty($lastKode->nomor)) ? ((int) $lastKode->nomor + 1) : 1;
        $nomorFormatted = str_pad($nomor, 3, '0', STR_PAD_LEFT);

        return "{$nomorFormatted}/TKT-IT/VYM/{$bulanRomawi}/{$tahun}";
    }

    // ==========================================
    // TICKET UPDATES / HISTORY
    // ==========================================

    public function addTicketUpdate($data)
    {
        $this->db->insert('it_ticket_update', $data);
        return $this->db->insert_id();
    }

    public function getTicketUpdates($id_ticket)
    {
        return $this->db->select('
            tu.*,
            p.nama as nama_pembuat,
            p.level as level_pembuat
        ')
            ->from('it_ticket_update tu')
            ->join('pengguna p', 'tu.id_pembuat=p.pengguna_id', 'left')
            ->where('tu.id_ticket', $id_ticket)
            ->order_by('tu.created_at', 'DESC')
            ->get()
            ->result();
    }

    // ==========================================
    // PERIODIC CHECK MAINTENANCE
    // ==========================================

    public function addMaintenance($data)
    {
        $this->db->insert('it_maintenance_aset', $data);
        return $this->db->insert_id();
    }

    public function updateMaintenance($id, $data)
    {
        $this->db->where('id_maintenance', $id);
        $this->db->update('it_maintenance_aset', $data);
        return $this->db->affected_rows();
    }

    public function deleteMaintenance($id)
    {
        return $this->updateMaintenance($id, ['status_data' => 0]);
    }

    public function getMaintenanceById($id)
    {
        return $this->db->select('
            m.*,
            a.nama as nama_aset,
            a.kode as kode_aset,
            a.kategori as kategori_aset,
            p.nama as nama_pengguna,
            pit.nama as nama_it
        ')
            ->from('it_maintenance_aset m')
            ->join('aset a', 'm.id_aset=a.id', 'left')
            ->join('pengguna p', 'm.id_pengguna=p.pengguna_id', 'left')
            ->join('pengguna pit', 'm.id_it=pit.pengguna_id', 'left')
            ->where('m.id_maintenance', $id)
            ->get()
            ->row();
    }

    public function getMaintenancesDatatable($where = [])
    {
        $searchArray = $this->input->post_get('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';
        if (empty($keyword)) {
            $keyword = trim((string) $this->input->get('keyword', TRUE));
        }

        $limit = $this->input->post_get('length', TRUE);
        $limit = ($limit !== null) ? intval($limit) : -1;
        $start = intval($this->input->post_get('start', TRUE) ?: 0);

        $hardware_filter = $this->input->post_get('hardware_filter', TRUE);
        $rekomendasi_filter = $this->input->post_get('rekomendasi_filter', TRUE);
        $start_date = $this->input->post_get('start_date', TRUE);
        $end_date = $this->input->post_get('end_date', TRUE);

        if (!empty($hardware_filter)) {
            $where['m.status_hardware'] = $hardware_filter;
        }
        if (!empty($rekomendasi_filter)) {
            $where['m.rekomendasi'] = $rekomendasi_filter;
        }
        if (!empty($start_date)) {
            $where['m.tanggal_cek >='] = $start_date;
        }
        if (!empty($end_date)) {
            $where['m.tanggal_cek <='] = $end_date;
        }

        // 1. Get total count
        $this->db->from('it_maintenance_aset m');
        $this->db->where('m.status_data', 1);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $recordsTotal = $this->db->count_all_results();

        // 2. Build data query
        $this->db->select('
            m.id_maintenance,
            m.kode_maintenance,
            m.tanggal_cek,
            m.status_hardware,
            m.status_software,
            m.backup_gdrive,
            m.rekomendasi,
            a.nama as nama_aset,
            a.kode as kode_aset,
            p.nama as nama_pengguna
        ');
        $this->db->from('it_maintenance_aset m');
        $this->db->join('aset a', 'm.id_aset=a.id', 'left');
        $this->db->join('pengguna p', 'm.id_pengguna=p.pengguna_id', 'left');
        $this->db->where('m.status_data', 1);

        if (!empty($where)) {
            $this->db->where($where);
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('m.kode_maintenance', $keyword);
            $this->db->or_like('p.nama', $keyword);
            $this->db->or_like('a.nama', $keyword);
            $this->db->or_like('a.kode', $keyword);
            $this->db->group_end();
        }

        // Apply sorting (default by tanggal_cek DESC)
        $this->db->order_by('m.tanggal_cek', 'DESC');

        // Apply pagination limit
        if ($limit != -1) {
            $this->db->limit($limit, $start);
        }

        $results = $this->db->get()->result();

        // 3. Count filtered records
        $this->db->from('it_maintenance_aset m');
        $this->db->join('aset a', 'm.id_aset=a.id', 'left');
        $this->db->join('pengguna p', 'm.id_pengguna=p.pengguna_id', 'left');
        $this->db->where('m.status_data', 1);

        if (!empty($where)) {
            $this->db->where($where);
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('m.kode_maintenance', $keyword);
            $this->db->or_like('p.nama', $keyword);
            $this->db->or_like('a.nama', $keyword);
            $this->db->or_like('a.kode', $keyword);
            $this->db->group_end();
        }
        $recordsFiltered = $this->db->count_all_results();

        // 4. Format data to flat array matching JS expectations
        $data = [];
        foreach ($results as $row) {
            $data[] = [
                $row->id_maintenance,     // Index 0: id_maintenance
                $row->kode_maintenance,   // Index 1: kode_maintenance
                $row->tanggal_cek,        // Index 2: tanggal_cek
                $row->status_hardware,    // Index 3: status_hardware
                $row->status_software,    // Index 4: status_software
                $row->backup_gdrive,       // Index 5: backup_gdrive
                $row->rekomendasi,        // Index 6: rekomendasi
                $row->nama_aset,          // Index 7: nama_aset
                $row->kode_aset,          // Index 8: kode_aset
                $row->nama_pengguna       // Index 9: nama_pengguna
            ];
        }

        return [
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];
    }

    public function generateKodeMaintenance()
    {
        $tahun = date('Y');
        $bulan = date('n');

        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        $bulanRomawi = isset($romawi[$bulan]) ? $romawi[$bulan] : '';

        $lastKode = $this->db->select("MAX(CAST(SUBSTRING_INDEX(kode_maintenance, '/', 1) AS UNSIGNED)) AS nomor")
            ->where('YEAR(created_at)', $tahun)
            ->get('it_maintenance_aset')
            ->row();

        $nomor = ($lastKode && !empty($lastKode->nomor)) ? ((int) $lastKode->nomor + 1) : 1;
        $nomorFormatted = str_pad($nomor, 3, '0', STR_PAD_LEFT);

        return "{$nomorFormatted}/MNT-IT/VYM/{$bulanRomawi}/{$tahun}";
    }

    // ==========================================
    // ASSET RELATION & PIC INTEGRATION
    // ==========================================

    public function getAssetsByPengguna($pengguna_id)
    {
        $subQuery = '(SELECT id_aset, MAX(id) AS max_id FROM aset_log GROUP BY id_aset) latest_log';
        $this->db->select('a.id, a.nama, a.kode, a.kategori');
        $this->db->from('aset a');
        $this->db->join($subQuery, 'a.id = latest_log.id_aset', 'inner', false);
        $this->db->join('aset_log al', 'al.id = latest_log.max_id', 'inner');
        $this->db->where('al.id_pengguna', intval($pengguna_id));
        $this->db->where('a.dijual !=', 2); // Asset hasn't been sold (2 = Sudah Dijual)
        $this->db->order_by('a.nama', 'ASC');
        return $this->db->get()->result();
    }

    public function getStatusLabel($status)
    {
        $labels = [
            1 => 'Open',
            2 => 'In Progress',
            3 => 'Solved',
            4 => 'Closed',
            5 => 'Rejected'
        ];
        return isset($labels[$status]) ? $labels[$status] : 'Unknown';
    }

    public function getStatusBadge($status)
    {
        $badges = [
            1 => '<span class="badge badge-warning">Open</span>',
            2 => '<span class="badge badge-info">In Progress</span>',
            3 => '<span class="badge badge-success">Solved</span>',
            4 => '<span class="badge badge-secondary">Closed</span>',
            5 => '<span class="badge badge-danger">Rejected</span>'
        ];
        return isset($badges[$status]) ? $badges[$status] : '<span class="badge badge-dark">Unknown</span>';
    }

    public function getITTechnicians()
    {
        $this->db->select('pg.pengguna_id, pg.nama, pg.jabatan');
        $this->db->from('pengguna pg');
        $this->db->where('pg.is_active', 1);
        $this->db->group_start();
        $this->db->where("pg.jabatan LIKE", '%IT%');
        $this->db->or_where("pg.jabatan LIKE", '%Techn%');
        $this->db->or_where("pg.jabatan LIKE", '%Teknisi%');
        $this->db->or_where("pg.jabatan LIKE", '%Support%');
        $this->db->or_where("pg.jabatan LIKE", '%Develop%');
        $this->db->or_where("pg.id_divisi", 7);
        $this->db->or_where_in("pg.pengguna_id", [755, 769, 107, 751, 754, 72, 1]);
        $this->db->group_end();
        $this->db->order_by('pg.nama', 'ASC');
        $res = $this->db->get()->result();

        if (empty($res)) {
            $this->db->select('pg.pengguna_id, pg.nama, pg.jabatan');
            $this->db->from('pengguna pg');
            $this->db->where('pg.is_active', 1);
            $this->db->order_by('pg.nama', 'ASC');
            $res = $this->db->get()->result();
        }

        return $res;
    }

    public function getMaintenanceStats()
    {
        $total = $this->db->where('status_data', 1)->count_all_results('it_maintenance_aset');
        $baik = $this->db->where('status_data', 1)->where('status_hardware', 'Baik')->count_all_results('it_maintenance_aset');
        $perlu_perbaikan = $this->db->where('status_data', 1)->where('status_hardware', 'Perlu Perbaikan')->count_all_results('it_maintenance_aset');
        $ganti_unit = $this->db->where('status_data', 1)->where('rekomendasi', 'Ganti Unit')->count_all_results('it_maintenance_aset');

        return [
            'total' => $total,
            'baik' => $baik,
            'perlu_perbaikan' => $perlu_perbaikan,
            'ganti_unit' => $ganti_unit
        ];
    }

    public function getMaintenancesForExport($filters = [])
    {
        $this->db->select('
            m.id_maintenance,
            m.kode_maintenance,
            m.tanggal_cek,
            m.status_hardware,
            m.status_software,
            m.backup_gdrive,
            m.rekomendasi,
            m.test_keamanan,
            m.keterangan,
            a.nama as nama_aset,
            a.kode as kode_aset,
            p.nama as nama_pengguna,
            pit.nama as nama_it
        ');
        $this->db->from('it_maintenance_aset m');
        $this->db->join('aset a', 'm.id_aset=a.id', 'left');
        $this->db->join('pengguna p', 'm.id_pengguna=p.pengguna_id', 'left');
        $this->db->join('pengguna pit', 'm.id_it=pit.pengguna_id', 'left');
        $this->db->where('m.status_data', 1);

        if (!empty($filters['hardware_filter'])) {
            $this->db->where('m.status_hardware', $filters['hardware_filter']);
        }
        if (!empty($filters['rekomendasi_filter'])) {
            $this->db->where('m.rekomendasi', $filters['rekomendasi_filter']);
        }
        if (!empty($filters['start_date'])) {
            $this->db->where('m.tanggal_cek >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $this->db->where('m.tanggal_cek <=', $filters['end_date']);
        }
        if (!empty($filters['keyword'])) {
            $this->db->group_start();
            $this->db->like('m.kode_maintenance', $filters['keyword']);
            $this->db->or_like('p.nama', $filters['keyword']);
            $this->db->or_like('a.nama', $filters['keyword']);
            $this->db->or_like('a.kode', $filters['keyword']);
            $this->db->group_end();
        }

        $this->db->order_by('m.tanggal_cek', 'DESC');
        return $this->db->get()->result();
    }
}
