<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Mpdf\Mpdf;

class Salary_resign_pokok extends CI_Controller {
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_salary_resign_pokok');
        $this->load->model('md_salary_resign'); 
    }

    public function index() {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $page_data['switch'] = "kepegawaian";
        $page_data['pengguna_resign'] = $this->md_salary_resign->getPenggunaNonAktif();
        $page_data['page_name'] = 'salary/v_salary_resign_pokok';
        $page_data['page_title'] = 'Data Gaji Pokok Resign';
        $page_data['page_desc'] = 'Manajemen Gaji Pokok Karyawan Resign (Tanggal 1)';
        $this->load->view('index', $page_data);
    }

    public function add() {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        
        // Unmask Rupiah Inputs
        $pokok         = unmask_rupiah($this->input->post('gaji_pokok'));
        $p_lain        = unmask_rupiah($this->input->post('pendapatan_lain'));
        $bpjs_kes      = unmask_rupiah($this->input->post('bpjs_kes'));
        $bpjs_tk       = unmask_rupiah($this->input->post('bpjs_tk'));
        $potongan_lain = unmask_rupiah($this->input->post('potongan_lain'));
        $pph21         = unmask_rupiah($this->input->post('pph21'));

        // Hitung Total Diterima
        $total_terima = ($pokok + $p_lain) - ($bpjs_kes + $bpjs_tk + $potongan_lain + $pph21);
        
        $data = [
            'pengguna_id'     => $this->input->post('pengguna_id', TRUE),
            'nama_karyawan'   => $this->input->post('nama_karyawan', TRUE),
            'no_pegawai'      => $this->input->post('no_pegawai', TRUE),
            'status_karyawan' => $this->input->post('status_karyawan', TRUE),
            'masa_kerja'      => $this->input->post('masa_kerja', TRUE),
            'hari_kehadiran'  => $this->input->post('hari_kehadiran', TRUE) ?: 0,
            'gaji_pokok'      => $pokok,
            'pendapatan_lain' => $p_lain,
            'bpjs_kes'        => $bpjs_kes,
            'bpjs_tk'         => $bpjs_tk,
            'potongan_lain'   => $potongan_lain,
            'pph21'           => $pph21,
            'total_diterima'  => $total_terima,
            'no_rekening'     => $this->input->post('no_rekening', TRUE),
            'periode'         => $this->input->post('periode', TRUE),
            'keterangan'      => $this->input->post('keterangan', TRUE),
            'created_by'      => sessPenggunaId()
        ];
        
        $this->md_salary_resign_pokok->add($data);
        addLog('Gaji Pokok Resign', 'Menambah data gaji pokok resign: ' . $data['nama_karyawan']);
        ajaxReturnDie('success', 'Gaji Pokok berhasil disimpan!', 'reload_table');
    }

    public function edit($id) {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $decrypted_id = decrypt($id);
        $data = $this->md_salary_resign_pokok->getById($decrypted_id);
        echo json_encode($data);
        die;
    }

    public function update() {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $id = decrypt($this->input->post('id'));

        $pokok         = unmask_rupiah($this->input->post('gaji_pokok'));
        $p_lain        = unmask_rupiah($this->input->post('pendapatan_lain'));
        $bpjs_kes      = unmask_rupiah($this->input->post('bpjs_kes'));
        $bpjs_tk       = unmask_rupiah($this->input->post('bpjs_tk'));
        $potongan_lain = unmask_rupiah($this->input->post('potongan_lain'));
        $pph21         = unmask_rupiah($this->input->post('pph21'));

        $total_terima = ($pokok + $p_lain) - ($bpjs_kes + $bpjs_tk + $potongan_lain + $pph21);

        $data = [
            'pengguna_id'     => $this->input->post('pengguna_id', TRUE),
            'nama_karyawan'   => $this->input->post('nama_karyawan', TRUE),
            'no_pegawai'      => $this->input->post('no_pegawai', TRUE),
            'status_karyawan' => $this->input->post('status_karyawan', TRUE),
            'masa_kerja'      => $this->input->post('masa_kerja', TRUE),
            'hari_kehadiran'  => $this->input->post('hari_kehadiran', TRUE) ?: 0,
            'gaji_pokok'      => $pokok,
            'pendapatan_lain' => $p_lain,
            'bpjs_kes'        => $bpjs_kes,
            'bpjs_tk'         => $bpjs_tk,
            'potongan_lain'   => $potongan_lain,
            'pph21'           => $pph21,
            'total_diterima'  => $total_terima,
            'no_rekening'     => $this->input->post('no_rekening', TRUE),
            'periode'         => $this->input->post('periode', TRUE),
            'keterangan'      => $this->input->post('keterangan', TRUE)
        ];

        $this->md_salary_resign_pokok->update($id, $data);
        addLog('Gaji Pokok Resign', 'Mengubah data gaji pokok resign: ' . $data['nama_karyawan']);
        ajaxReturnDie('success', 'Data berhasil diupdate!', 'reload_table');
    }

    public function delete($id) {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $decrypted_id = decrypt($id);
        $data = $this->md_salary_resign_pokok->getById($decrypted_id);
        
        if ($data) {
            $this->md_salary_resign_pokok->delete($decrypted_id);
            addLog('Gaji Pokok Resign', 'Menghapus data gaji pokok resign: ' . $data->nama_karyawan);
            ajaxReturnDie('success', 'Data berhasil dihapus!', 'reload_table');
        } else {
            ajaxReturnDie('error', 'Data tidak ditemukan!', FALSE);
        }
    }

    public function pagination() {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $dt = $this->md_salary_resign_pokok->getAllForDatatables();
        $start = $this->input->post('start');
        $data = array();
        
        foreach ($dt['data'] as $row) {
            $id = encrypt($row->id);
            $li_btn = '<div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-info btn-print-single" data-id="'.$id.'" title="Print"><i class="fa fa-print"></i></button>
                <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="'.$id.'" title="Edit"><i class="fa fa-edit"></i></button>
                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="'.$id.'" title="Hapus"><i class="fa fa-trash"></i></button>
            </div>';

            $th = [
                ++$start . '.',
                $row->nama_karyawan,
                $row->no_pegawai ?: '-',
                ucwords(strtolower($row->status_karyawan)),
                $row->masa_kerja ?: '-',
                $row->hari_kehadiran . ' hari',
                $row->gaji_pokok > 0 ? 'Rp. ' . rupiah($row->gaji_pokok) : 'Rp. -',
                'Rp. ' . rupiah($row->bpjs_kes + $row->bpjs_tk + $row->potongan_lain + $row->pph21),
                '<strong>Rp. ' . rupiah($row->total_diterima) . '</strong>',
                $row->no_rekening ?: '-',
                $li_btn
            ];
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
    
    public function print_single($id) {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $decrypted_id = decrypt($id);
        $row = $this->md_salary_resign_pokok->getById($decrypted_id);

        if (!$row) show_404();

        $mpdf = new Mpdf(['format' => 'A4']);
        $mpdf->AddPage('P');

        $data = [
            'row' => $row,
            'title_pdf' => 'Gaji Pokok Karyawan Resign/Out',
            'periode' => getMonthName(date('m', strtotime($row->periode . "-01"))) . ' ' . date('Y', strtotime($row->periode . "-01"))
        ];

        $html = $this->load->view('pages/v_print/print_salary_resign_pokok_single', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output('Slip_Gaji_Pokok_' . $row->nama_karyawan . '.pdf', 'I');
    }

    public function print($periode) {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
        $dt = $this->md_salary_resign_pokok->getByPeriode($periode);
        $totals = $this->md_salary_resign_pokok->getTotalsByPeriode($periode);
    
        $mpdf = new Mpdf(['format' => 'Legal']);
        $mpdf->AddPage('L');
    
        $tanggal_palsu = $periode . "-01"; 
    
        $data = [
            'dt' => $dt,
            'totals' => $totals,
            'title_pdf' => 'Gaji Pokok Karyawan Resign',
            'periode' => getMonthName(date('m', strtotime($tanggal_palsu))) . ' ' . date('Y', strtotime($tanggal_palsu))
        ];
    
        $html = $this->load->view('pages/v_print/print_salary_resign_pokok', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output('Gaji_Pokok_Resign_'.$periode.'.pdf', 'I');
    }
}

if (!function_exists('unmask_rupiah')) {
    function unmask_rupiah($value)
    {
        if (empty($value)) return 0;
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        return floatval($value);
    }
}