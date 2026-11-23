<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Broadcast_pesan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_bukutamu');
        $this->load->model('Md_broadcast_pesan');
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
        $kegiatanId = (int) $this->input->post('kegiatan_id');
        $tanggalMulai = $this->normalizeTanggalFilter($this->input->post('tanggal_mulai', true));
        $tanggalSelesai = $this->normalizeTanggalFilter($this->input->post('tanggal_selesai', true));
        $statusHubungi = trim((string)$this->input->post('status_hubungi', true));

        if (!empty($tanggalMulai) && !empty($tanggalSelesai) && $tanggalMulai > $tanggalSelesai) {
            $tmp = $tanggalMulai;
            $tanggalMulai = $tanggalSelesai;
            $tanggalSelesai = $tmp;
        }

        $dt = $this->Md_broadcast_pesan->getAllBroadcastData($kegiatanId, $tanggalMulai, $tanggalSelesai, $statusHubungi);
        $data  = array();
        $index = ((int) $this->input->post('start')) + 1;

        if (!empty($dt['data'])) {
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
                $instansiEsc = htmlspecialchars($row->instansi ?: '-', ENT_QUOTES, 'UTF-8');
                $noWaDisplay = $isImportDummy ? '-' : htmlspecialchars($rawWa, ENT_QUOTES, 'UTF-8');

                // Tracking Info
                $hasTracked = !empty($row->total_klik) && (int)$row->total_klik > 0;
                if ($hasTracked) {
                    $lastDate = !empty($row->last_click_at) ? date('d-M-Y H:i', strtotime($row->last_click_at)) : '-';
                    $userHubungi = htmlspecialchars($row->nama_pengguna ?: 'Marketing', ENT_QUOTES, 'UTF-8');
                    $statusBadge = '<span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Sudah Dihubungi</span>'
                                 . '<div class="text-muted small mt-1" style="font-size: 0.78rem;">'
                                 . '<i class="fas fa-user-edit mr-1"></i> ' . $userHubungi . '<br>'
                                 . '<i class="far fa-clock mr-1"></i> ' . $lastDate . '<br>'
                                 . '<span class="badge badge-light border mt-1">' . (int)$row->total_klik . 'x dihubungi</span>'
                                 . '</div>';
                } else {
                    $statusBadge = '<span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-clock mr-1"></i> Belum Dihubungi</span>';
                }

                $checkbox = '';
                $btnAksi = '';
                if (!empty($waTarget) && strlen($waTarget) >= 9) {
                    $waUrl = 'https://wa.me/' . $waTarget;
                    
                    $checkbox = '<input type="checkbox" class="chk-kontak" '
                              . 'data-id="' . (int)$row->id_bukutamu . '" '
                              . 'data-kegiatan="' . (int)$row->kegiatan . '" '
                              . 'data-nama="' . $namaEsc . '" '
                              . 'data-instansi="' . $instansiEsc . '" '
                              . 'data-wa="' . htmlspecialchars($waTarget, ENT_QUOTES, 'UTF-8') . '" '
                              . 'data-url="' . $waUrl . '" '
                              . 'data-status="' . ($hasTracked ? 'sudah' : 'belum') . '">';

                    $btnAksi = '<div class="d-inline-flex align-items-center" style="gap: 8px;">'
                             . $checkbox
                             . '<button type="button" class="btn btn-xs btn-success text-nowrap btn-hubungi-wa" '
                             . 'data-id="' . (int)$row->id_bukutamu . '" '
                             . 'data-kegiatan="' . (int)$row->kegiatan . '" '
                             . 'data-nama="' . $namaEsc . '" '
                             . 'data-wa="' . htmlspecialchars($waTarget, ENT_QUOTES, 'UTF-8') . '" '
                             . 'data-url="' . $waUrl . '" '
                             . 'title="Hubungi WA & Catat Tracking">'
                             . '<i class="fab fa-whatsapp mr-1"></i> ' . ($hasTracked ? 'Hubungi Lagi' : 'Hubungi WA')
                             . '</button>'
                             . '</div>';
                } else {
                    $btnAksi = '<span class="badge badge-secondary py-1 px-2 text-nowrap"><i class="fas fa-times-circle mr-1"></i> Tidak Valid</span>';
                }

                $th = array();
                $th[] = $index;
                $th[] = '<strong>' . $namaEsc . '</strong>';
                $th[] = $instansiEsc;
                $th[] = htmlspecialchars($row->jabatan ?: '-', ENT_QUOTES, 'UTF-8');
                $th[] = '<span class="badge badge-info">' . htmlspecialchars($row->nama_kegiatan ?: 'Umum', ENT_QUOTES, 'UTF-8') . '</span>';
                $th[] = '<span class="text-nowrap"><i class="fab fa-whatsapp text-success mr-1"></i> ' . $noWaDisplay . '</span>';
                $th[] = !empty($row->created_at) ? date('d-M-Y | H:i', strtotime($row->created_at)) : '-';
                $th[] = $statusBadge;
                $th[] = $btnAksi;

                $data[] = $th;
                $index++;
            }
        }

        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function track_click()
    {
        $id_bukutamu = (int)$this->input->post('id_bukutamu');
        $nomorwa = trim((string)$this->input->post('nomorwa', true));
        $nama = trim((string)$this->input->post('nama', true));
        $kegiatan_id = (int)$this->input->post('kegiatan_id');

        if ($id_bukutamu > 0) {
            $result = $this->Md_broadcast_pesan->logClick($id_bukutamu, $nomorwa, $nama, $kegiatan_id);
            echo json_encode([
                'status'  => 'success',
                'message' => 'Tracking berhasil dicatat.',
                'data'    => $result
            ]);
        } else {
            echo json_encode([
                'status'  => 'error',
                'message' => 'ID Buku Tamu tidak valid.'
            ]);
        }
        die;
    }
}
