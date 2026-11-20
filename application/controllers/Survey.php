<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Survey extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_survey');
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        redirect('https://visiyosindo.id');
    }

    public function isi($token = '')
    {
        $token = trim((string)$token);
        if ($token === '') {
            show_404();
            return;
        }

        $survey = $this->md_survey->get_by_token($token);
        if (!$survey) {
            $data['status_view'] = 'not_found';
            $data['message']     = 'Link survey tidak valid atau tidak ditemukan.';
            $this->load->view('pages/survey/v_survey_status', $data);
            return;
        }

        if ($survey->status === 'Selesai') {
            $data['status_view'] = 'already_completed';
            $data['survey']      = $survey;
            $data['message']     = 'Survey ini sudah pernah diisi pada ' . date('d F Y, H:i', strtotime($survey->submitted_at)) . ' WIB. Terima kasih atas partisipasi Anda.';
            $this->load->view('pages/survey/v_survey_status', $data);
            return;
        }

        $data['survey'] = $survey;
        $this->load->view('pages/survey/v_survey_form', $data);
    }

    public function submit()
    {
        $token = $this->input->post('token', TRUE);
        if (empty($token)) {
            echo json_encode(['status' => 'error', 'message' => 'Token survey tidak valid.']);
            return;
        }

        $survey = $this->md_survey->get_by_token($token);
        if (!$survey || $survey->status === 'Selesai') {
            echo json_encode(['status' => 'error', 'message' => 'Survey sudah tidak dapat diisi atau tidak ditemukan.']);
            return;
        }

        $pelayanan       = (int)$this->input->post('bintang_pelayanan', TRUE);
        $kecepatan       = (int)$this->input->post('bintang_kecepatan_respons', TRUE);
        $keramahan       = (int)$this->input->post('bintang_keramahan', TRUE);
        $keluhan         = (int)$this->input->post('bintang_penanganan_keluhan', TRUE);
        $layanan_teknis  = (int)$this->input->post('bintang_layanan_teknis', TRUE);
        $tindak_lanjut   = (int)$this->input->post('bintang_kepuasan_tindaklanjut', TRUE);
        $saran           = trim((string)$this->input->post('saran_masukan', TRUE));

        // Validasi wajib bintang 1-5
        $ratings = [$pelayanan, $kecepatan, $keramahan, $keluhan, $layanan_teknis, $tindak_lanjut];
        foreach ($ratings as $r) {
            if ($r < 1 || $r > 5) {
                echo json_encode(['status' => 'error', 'message' => 'Mohon berikan rating bintang (1-5) untuk semua poin penilaian.']);
                return;
            }
        }

        $rata_rata = round(array_sum($ratings) / count($ratings), 2);

        $updateData = [
            'status'                       => 'Selesai',
            'bintang_pelayanan'            => $pelayanan,
            'bintang_kecepatan_respons'    => $kecepatan,
            'bintang_keramahan'            => $keramahan,
            'bintang_penanganan_keluhan'   => $keluhan,
            'bintang_layanan_teknis'       => $layanan_teknis,
            'bintang_kepuasan_tindaklanjut' => $tindak_lanjut,
            'rata_rata'                    => $rata_rata,
            'saran_masukan'                => $saran,
            'submitted_at'                 => date('Y-m-d H:i:s')
        ];

        $updated = $this->md_survey->submit_survey($token, $updateData);

        if ($updated) {
            echo json_encode([
                'status'  => 'success',
                'message' => 'Terima kasih, survey Anda telah berhasil dikirim!',
                'redirect' => base_url('survey/sukses')
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }

    public function sukses()
    {
        $data['status_view'] = 'success';
        $data['message']     = 'Terima kasih atas partisipasi dan masukan yang Anda berikan. Penilaian Anda sangat berharga bagi kami dalam terus meningkatkan kualitas pelayanan PT Visi Yosindo Medikal.';
        $this->load->view('pages/survey/v_survey_status', $data);
    }
}
