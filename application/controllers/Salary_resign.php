<?php

use Mpdf\Mpdf;

defined('BASEPATH') or exit('No direct script access allowed');

class Salary_resign extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_salary_resign');
    }

    function id_navbar()
    {
        $id_navbar = "kepegawaian";
        return $id_navbar;
    }

    /**
     * Index - Main page to display list of resign salary data
     */
    public function index()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $page_data['switch'] = $this->id_navbar();
        $page_data['pengguna_resign'] = $this->md_salary_resign->getPenggunaNonAktif();
        $page_data['page_name'] = 'salary/v_salary_resign';
        $page_data['page_title'] = 'Data Salary Karyawan Resign';
        $page_data['page_desc'] = 'Management Data Tunjangan Karyawan Resign/Out';
        $this->load->view('index', $page_data);
    }

    /**
     * Get pengguna detail for autofill via AJAX
     */
    public function getPenggunaDetail($pengguna_id)
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $pengguna = $this->md_salary_resign->getPenggunaById($pengguna_id);
        
        if ($pengguna) {
            $masa_kerja = $this->md_salary_resign->calculateMasaKerja($pengguna);
            
            $response = [
                'status' => 'success',
                'data' => [
                    'pengguna_id' => $pengguna->pengguna_id,
                    'nama' => $pengguna->nama,
                    'no_pegawai' => $pengguna->no_pegawai,
                    'status_karyawan' => $pengguna->status_karyawan,
                    'masa_kerja' => $masa_kerja,
                    'no_rek' => $pengguna->no_rek
                ]
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Data pengguna tidak ditemukan'
            ];
        }
        
        echo json_encode($response);
        die;
    }

    /**
     * Add - Insert new resign salary data
     */
    public function add()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $data = [
            'pengguna_id' => $this->input->post('pengguna_id', TRUE),
            'nama_karyawan' => $this->input->post('nama_karyawan', TRUE),
            'no_pegawai' => $this->input->post('no_pegawai', TRUE),
            'status_karyawan' => $this->input->post('status_karyawan', TRUE),
            'masa_kerja' => $this->input->post('masa_kerja', TRUE),
            'hari_kehadiran' => $this->input->post('hari_kehadiran', TRUE) ?: 0,
            'tunjangan_jabatan' => unmask_rupiah($this->input->post('tunjangan_jabatan', TRUE)),
            'tunjangan_kinerja' => unmask_rupiah($this->input->post('tunjangan_kinerja', TRUE)),
            'tunjangan_konsumsi' => unmask_rupiah($this->input->post('tunjangan_konsumsi', TRUE)),
            'tunjangan_komunikasi' => unmask_rupiah($this->input->post('tunjangan_komunikasi', TRUE)),
            'tunjangan_transportasi' => unmask_rupiah($this->input->post('tunjangan_transportasi', TRUE)),
            'tunjangan_bbm' => unmask_rupiah($this->input->post('tunjangan_bbm', TRUE)),
            'tunjangan_lainnya' => unmask_rupiah($this->input->post('tunjangan_lainnya', TRUE)),
            'potongan' => unmask_rupiah($this->input->post('potongan', TRUE)),
            'no_rekening' => $this->input->post('no_rekening', TRUE),
            'periode' => $this->input->post('periode', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE),
            'created_by' => sessPenggunaId()
        ];

        $this->md_salary_resign->add($data);

        addLog('Salary Resign', 'Menambah data salary resign: ' . $data['nama_karyawan']);
        ajaxReturnDie('success', 'Data berhasil disimpan!', 'reload_table');
    }

    /**
     * Edit - Get data for editing
     */
    public function edit($id)
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $decrypted_id = decrypt($id);
        $data = $this->md_salary_resign->getById($decrypted_id);
        
        echo json_encode($data);
        die;
    }

    /**
     * Update - Update existing resign salary data
     */
    public function update()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $id = decrypt($this->input->post('id'));

        $data = [
            'pengguna_id' => $this->input->post('pengguna_id', TRUE),
            'nama_karyawan' => $this->input->post('nama_karyawan', TRUE),
            'no_pegawai' => $this->input->post('no_pegawai', TRUE),
            'status_karyawan' => $this->input->post('status_karyawan', TRUE),
            'masa_kerja' => $this->input->post('masa_kerja', TRUE),
            'hari_kehadiran' => $this->input->post('hari_kehadiran', TRUE) ?: 0,
            'tunjangan_jabatan' => unmask_rupiah($this->input->post('tunjangan_jabatan', TRUE)),
            'tunjangan_kinerja' => unmask_rupiah($this->input->post('tunjangan_kinerja', TRUE)),
            'tunjangan_konsumsi' => unmask_rupiah($this->input->post('tunjangan_konsumsi', TRUE)),
            'tunjangan_komunikasi' => unmask_rupiah($this->input->post('tunjangan_komunikasi', TRUE)),
            'tunjangan_transportasi' => unmask_rupiah($this->input->post('tunjangan_transportasi', TRUE)),
            'tunjangan_bbm' => unmask_rupiah($this->input->post('tunjangan_bbm', TRUE)),
            'tunjangan_lainnya' => unmask_rupiah($this->input->post('tunjangan_lainnya', TRUE)),
            'potongan' => unmask_rupiah($this->input->post('potongan', TRUE)),
            'no_rekening' => $this->input->post('no_rekening', TRUE),
            'periode' => $this->input->post('periode', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE)
        ];

        $this->md_salary_resign->update($id, $data);

        addLog('Salary Resign', 'Mengubah data salary resign: ' . $data['nama_karyawan']);
        ajaxReturnDie('success', 'Data berhasil diupdate!', 'reload_table');
    }

    /**
     * Delete - Remove resign salary data
     */
    public function delete($id)
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $decrypted_id = decrypt($id);
        $data = $this->md_salary_resign->getById($decrypted_id);
        
        if ($data) {
            $this->md_salary_resign->delete($decrypted_id);
            addLog('Salary Resign', 'Menghapus data salary resign: ' . $data->nama_karyawan);
            ajaxReturnDie('success', 'Data berhasil dihapus!', 'reload_table');
        } else {
            ajaxReturnDie('error', 'Data tidak ditemukan!', FALSE);
        }
    }

    /**
     * Pagination - DataTables server-side processing
     */
    public function pagination()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $dt = $this->md_salary_resign->getAllForDatatables();
        $start = $this->input->post('start');
        $data = array();

        foreach ($dt['data'] as $row) {
            $id = encrypt($row->id);

            // Fetch pengguna data for no_rekening and masa_kerja
            if (!empty($row->pengguna_id)) {
                $pengguna = $this->md_salary_resign->getPenggunaById($row->pengguna_id);
                if ($pengguna) {
                    $row->no_rekening = $pengguna->no_rek ?: $row->no_rekening;
                    $row->masa_kerja = $this->md_salary_resign->calculateMasaKerja($pengguna);
                }
            }
            
            // Calculate total
            $total = $row->tunjangan_jabatan 
                   + $row->tunjangan_kinerja 
                   + $row->tunjangan_konsumsi 
                   + $row->tunjangan_komunikasi 
                   + $row->tunjangan_transportasi 
                   + $row->tunjangan_bbm 
                   + $row->tunjangan_lainnya 
                   - $row->potongan;

            // Action buttons
            $li_btn = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-info btn-print-single" data-id="' . $id . '" title="Print"><i class="fa fa-print"></i></button>
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '" title="Edit"><i class="fa fa-edit"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="' . $id . '" title="Hapus"><i class="fa fa-trash"></i></button>
                </div>';

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_karyawan;
            $th[] = $row->no_pegawai ?: '-';
            $th[] = ucwords(strtolower($row->status_karyawan));
            $th[] = $row->masa_kerja ?: '-';
            $th[] = $row->hari_kehadiran . ' hari';
            $th[] = $row->tunjangan_jabatan > 0 ? 'Rp. ' . rupiah($row->tunjangan_jabatan) : 'Rp. -';
            $th[] = $row->tunjangan_kinerja > 0 ? 'Rp. ' . rupiah($row->tunjangan_kinerja) : 'Rp. -';
            $th[] = $row->tunjangan_konsumsi > 0 ? 'Rp. ' . rupiah($row->tunjangan_konsumsi) : 'Rp. -';
            $th[] = $row->tunjangan_komunikasi > 0 ? 'Rp. ' . rupiah($row->tunjangan_komunikasi) : 'Rp. -';
            $th[] = $row->tunjangan_transportasi > 0 ? 'Rp. ' . rupiah($row->tunjangan_transportasi) : 'Rp. -';
            $th[] = $row->tunjangan_bbm > 0 ? 'Rp. ' . rupiah($row->tunjangan_bbm) : 'Rp. -';
            $th[] = $row->tunjangan_lainnya > 0 ? 'Rp. ' . rupiah($row->tunjangan_lainnya) : 'Rp. -';
            $th[] = $row->potongan > 0 ? 'Rp. ' . rupiah($row->potongan) : 'Rp. -';
            $th[] = '<strong>Rp. ' . rupiah($total) . '</strong>';
            $th[] = $row->no_rekening ?: '-';
            $th[] = $li_btn;
            
            $data[] = $th;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    /**
     * Print Single - Generate PDF for one resign salary record
     */
    public function print_single($id)
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $this->load->helper('terbilang');

        $decrypted_id = decrypt($id);
        $row = $this->md_salary_resign->getById($decrypted_id);

        if (!$row) {
            show_404();
            return;
        }

        // Fetch pengguna data for no_rekening and masa_kerja calculation
        if (!empty($row->pengguna_id)) {
            $pengguna = $this->md_salary_resign->getPenggunaById($row->pengguna_id);
            if ($pengguna) {
                $row->no_rekening = $pengguna->no_rek ?: $row->no_rekening;
                $row->masa_kerja = $this->md_salary_resign->calculateMasaKerja($pengguna);
            }
        }

        // Calculate total
        $total = $row->tunjangan_jabatan 
               + $row->tunjangan_kinerja 
               + $row->tunjangan_konsumsi 
               + $row->tunjangan_komunikasi 
               + $row->tunjangan_transportasi 
               + $row->tunjangan_bbm 
               + $row->tunjangan_lainnya 
               - $row->potongan;

        $periode_label = '';
        if (!empty($row->periode)) {
            $periode_label = getMonthName(date('m', strtotime($row->periode))) . ' ' . date('Y', strtotime($row->periode));
        }

        $mpdf = new Mpdf(['format' => 'A4']);
        $mpdf->AddPage('P');

        $data = [
            'row' => $row,
            'title_pdf' => 'Tunjangan Karyawan Out',
            'periode' => $periode_label
        ];

        $file_pdf = 'Tunjangan Karyawan Resign-Out - ' . $row->nama_karyawan;
        $html = $this->load->view('pages/v_print/print_salary_resign_single', $data, true);

        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }

    /**
     * Print - Generate PDF for resign salary data
     */
    public function print($periode = "")
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $mpdf = new Mpdf(['format' => 'Legal']);
        $mpdf->AddPage('L');

        $month = $periode ? $periode : date("Y-m", strtotime("first day of last month"));

        $dt = $this->md_salary_resign->getByPeriode($month);
        
        // Enrich each row with pengguna data
        foreach ($dt as &$row) {
            if (!empty($row->pengguna_id)) {
                $pengguna = $this->md_salary_resign->getPenggunaById($row->pengguna_id);
                if ($pengguna) {
                    $row->no_rekening = $pengguna->no_rek ?: $row->no_rekening;
                    $row->masa_kerja = $this->md_salary_resign->calculateMasaKerja($pengguna);
                }
            }
        }
        unset($row);

        $data = [
            'dt' => $dt,
            'totals' => $this->md_salary_resign->getTotalsByPeriode($month),
            'title_pdf' => 'Tunjangan Karyawan Out',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month
        ];

        $file_pdf = 'Tunjangan Karyawan Resign-Out ' . $data['periode'];
        $html = $this->load->view('pages/v_print/print_salary_resign', $data, true);
        
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }
}

/**
 * Helper function to unmask rupiah format
 */
if (!function_exists('unmask_rupiah')) {
    function unmask_rupiah($value)
    {
        if (empty($value)) return 0;
        // Remove dots and convert to number
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        return floatval($value);
    }
}
