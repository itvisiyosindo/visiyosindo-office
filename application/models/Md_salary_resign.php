<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_salary_resign extends CI_Model
{
    protected $table = 'salary_resign';

    /**
     * Add new salary resign data
     */
    public function add($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update salary resign data by ID
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete salary resign data by ID
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Get salary resign by ID
     */
    public function getById($id)
    {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    /**
     * Get all salary resign data
     */
    public function getAll()
    {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Get salary resign data by periode
     */
    public function getByPeriode($periode)
    {
        $this->db->where('periode', $periode);
        $this->db->order_by('nama_karyawan', 'ASC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Get pengguna non-aktif (karyawan resign)
     */
    public function getPenggunaNonAktif()
    {
        $this->db->select('
            pengguna_id,
            nama,
            no_pegawai,
            status_karyawan,
            tgl_masuk,
            tgl_kontrak,
            tgl_keluar,
            no_rek
        ');
        $this->db->group_start();
        $this->db->where('is_active', 0);
        $this->db->or_where('pengguna_id', 29);
        $this->db->group_end();
        $this->db->where('status', 1);
        $this->db->order_by('nama', 'ASC');
        return $this->db->get('pengguna')->result();
    }

    /**
     * Get pengguna by ID for autofill
     */
    public function getPenggunaById($pengguna_id)
    {
        $this->db->select('
            pengguna_id,
            nama,
            no_pegawai,
            status_karyawan,
            tgl_masuk,
            tgl_kontrak,
            tgl_keluar,
            no_rek
        ');
        $this->db->where('pengguna_id', $pengguna_id);
        return $this->db->get('pengguna')->row();
    }

    /**
     * Calculate masa kerja based on status_karyawan
     * Training/Tetap = from tgl_masuk
     * Kontrak = from tgl_kontrak
     */
    public function calculateMasaKerja($pengguna)
    {
        $today = new DateTime();

        // If tgl_keluar exists, use it as end date
        if (!empty($pengguna->tgl_keluar)) {
            $end_date = new DateTime($pengguna->tgl_keluar);
        } else {
            $end_date = $today;
        }

        // Determine start date based on status
        $status = strtolower($pengguna->status_karyawan);

        if ($status == 'kontrak' && !empty($pengguna->tgl_kontrak)) {
            $start_date = new DateTime($pengguna->tgl_kontrak);
        } else if (!empty($pengguna->tgl_masuk)) {
            $start_date = new DateTime($pengguna->tgl_masuk);
        } else {
            return '-';
        }

        $interval = $start_date->diff($end_date);

        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;

        $result = '';
        if ($years > 0) {
            $result .= $years . ' Tahun ';
        }
        if ($months > 0) {
            $result .= str_pad($months, 2, '0', STR_PAD_LEFT) . ' Bulan ';
        }
        if ($days > 0) {
            $result .= $days . ' Hari';
        }

        return trim($result) ?: '0 Hari';
    }

    /**
     * Get salary resign for DataTables
     */
    public function getAllForDatatables()
    {
        // Filter by periode if provided
        if ($this->input->post('filter_month')) {
            $this->datatables->where('periode', $this->input->post('filter_month'));
        }

        return $this->datatables
            ->select('
                id,
                pengguna_id,
                nama_karyawan,
                no_pegawai,
                status_karyawan,
                masa_kerja,
                hari_kehadiran,
                tunjangan_jabatan,
                tunjangan_kinerja,
                tunjangan_konsumsi,
                tunjangan_komunikasi,
                tunjangan_transportasi,
                tunjangan_bbm,
                tunjangan_lainnya,
                potongan,
                no_rekening,
                periode,
                keterangan,
                created_at
            ')
            ->from($this->table)
            ->generate();
    }

    /**
     * Calculate total tunjangan for a row
     */
    public function calculateTotal($row)
    {
        $total = $row->tunjangan_jabatan
            + $row->tunjangan_kinerja
            + $row->tunjangan_konsumsi
            + $row->tunjangan_komunikasi
            + $row->tunjangan_transportasi
            + $row->tunjangan_bbm
            + $row->tunjangan_lainnya
            - $row->potongan;
        return $total;
    }

    /**
     * Get totals by periode for print summary
     */
    public function getTotalsByPeriode($periode)
    {
        $this->db->select('
            SUM(tunjangan_jabatan) as sum_jabatan,
            SUM(tunjangan_kinerja) as sum_kinerja,
            SUM(tunjangan_konsumsi) as sum_konsumsi,
            SUM(tunjangan_komunikasi) as sum_komunikasi,
            SUM(tunjangan_transportasi) as sum_transportasi,
            SUM(tunjangan_bbm) as sum_bbm,
            SUM(tunjangan_lainnya) as sum_lainnya,
            SUM(potongan) as sum_potongan,
            SUM(tunjangan_jabatan + tunjangan_kinerja + tunjangan_konsumsi + tunjangan_komunikasi + tunjangan_transportasi + tunjangan_bbm + tunjangan_lainnya - potongan) as sum_total
        ');
        $this->db->where('periode', $periode);
        return $this->db->get($this->table)->row();
    }

    /**
     * Check if data exists for periode
     */
    public function countByPeriode($periode)
    {
        $this->db->where('periode', $periode);
        return $this->db->count_all_results($this->table);
    }
}
