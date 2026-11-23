<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Broadcast_pesan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_bukutamu');
        $this->load->helper('encrypt_helper');
    }

    function id_navbar()
    {
        return "marketing";
    }

    private function normalizeTanggalFilter($tanggal)
    {
        $tanggal = trim((string) $tanggal);
        if ($tanggal === '') {
            return null;
        }

        $dateObj = DateTime::createFromFormat('Y-m-d', $tanggal);
        if ($dateObj && $dateObj->format('Y-m-d') === $tanggal) {
            return $tanggal;
        }

        return null;
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'marketing/v_broadcast_pesan';
        $page_data['page_title'] = 'Broadcast Pesan Marketing';
        $page_data['page_desc'] = 'Kontak Buku Tamu & Aksi Kirim Pesan WhatsApp';

        $page_data['kegiatan_list'] = $this->md_bukutamu->getKegiatan();

        $this->load->view('index', $page_data);
    }

    public function pagination()
    {
        grantAccessFor('all');

        $kegiatanId = (int) $this->input->post('kegiatan_id');
        $tanggalMulai = $this->normalizeTanggalFilter($this->input->post('tanggal_mulai', true));
        $tanggalSelesai = $this->normalizeTanggalFilter($this->input->post('tanggal_selesai', true));

        if (!empty($tanggalMulai) && !empty($tanggalSelesai) && $tanggalMulai > $tanggalSelesai) {
            $tmp = $tanggalMulai;
            $tanggalMulai = $tanggalSelesai;
            $tanggalSelesai = $tmp;
        }

        $dt = $this->md_bukutamu->getAllBukuTamu($kegiatanId, $tanggalMulai, $tanggalSelesai);
        $data  = array();
        $index = ((int) $this->input->post('start')) + 1;

        foreach ($dt['data'] as $row) {
            $rawWa = (string)$row->nomorwa;
            $isImportDummy = (strpos($rawWa, 'IMPORT_') === 0);
            $cleanDigits = preg_replace('/\D+/', '', $rawWa);

            $waTarget = '';
            if (!$isImportDummy && !empty($cleanDigits)) {
                if (strpos($cleanDigits, '62') === 0) {
                    $waTarget = $cleanDigits;
                } elseif (strpos($cleanDigits, '0') === 0) {
                    $waTarget = '62' . substr($cleanDigits, 1);
                } elseif (strpos($cleanDigits, '8') === 0) {
                    $waTarget = '62' . $cleanDigits;
                } else {
                    $waTarget = $cleanDigits;
                }
            }

            $namaEsc = htmlspecialchars($row->nama, ENT_QUOTES, 'UTF-8');
            $noWaDisplay = $isImportDummy ? '-' : htmlspecialchars($rawWa, ENT_QUOTES, 'UTF-8');

            if (!empty($waTarget) && strlen($waTarget) >= 9) {
                $waUrl = 'https://wa.me/' . $waTarget;
                $btnAksi = '<a href="' . $waUrl . '" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success shadow-sm" title="Chat WhatsApp Manual">'
                         . '<i class="fab fa-whatsapp mr-1"></i> Hubungi WA'
                         . '</a>';
            } else {
                $btnAksi = '<span class="badge badge-secondary py-1 px-2"><i class="fas fa-times-circle mr-1"></i> No WA Tidak Valid</span>';
            }

            $th = array();
            $th[] = $index;
            $th[] = '<strong>' . $namaEsc . '</strong>';
            $th[] = htmlspecialchars($row->instansi ?: '-', ENT_QUOTES, 'UTF-8');
            $th[] = htmlspecialchars($row->jabatan ?: '-', ENT_QUOTES, 'UTF-8');
            $th[] = '<span class="badge badge-info">' . htmlspecialchars($row->nama_kegiatan ?: 'Umum', ENT_QUOTES, 'UTF-8') . '</span>';
            $th[] = '<span class="text-nowrap"><i class="fab fa-whatsapp text-success mr-1"></i> ' . $noWaDisplay . '</span>';
            $th[] = !empty($row->created_at) ? date('d-m-Y H:i', strtotime($row->created_at)) : '-';
            $th[] = $btnAksi;

            $data[] = $th;
            $index++;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
    }
}
