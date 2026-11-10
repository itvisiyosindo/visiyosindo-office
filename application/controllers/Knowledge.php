<?php

use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Knowledge extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_knowledge');
        $this->load->model('md_laporan');
        $this->load->model('md_pengguna');


        $this->load->model('md_pelanggan');
        $this->load->model('md_prov_kota');
        $this->load->model('md_kategori_tiket');
        $this->load->model('md_tiket');
    }
	
	function id_navbar(){
		$id_navbar = "kepegawaian";
		return $id_navbar;
	}

    private function _isAdminHrd()
    {
        $myId = (int)sessPenggunaId();
        return (isAdmin() || isHrd() || isEksekutif() || in_array($myId, [1, 23, 33, 54, 55, 56, 58, 69, 744], true));
    }

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');
        $myId = (int)sessPenggunaId();
        $isAdminHrd = $this->_isAdminHrd();

        if ($param == 'knowledge' || empty($param)) {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']     = 'knowledge/v_knowledge';
            $page_data['page_title']    = 'Kuis & Nilai Product Knowledge';
            $page_data['page_desc']     = 'Evaluasi Pemahaman Produk & Kinerja Karyawan';
            $page_data['list_nama']     = $this->md_laporan->getBywhereActive();
            $tahun = (int)($this->input->get('tahun') ?: date('Y'));
            $bulan = $this->input->get('bulan') ?: '';
            $semester = $this->input->get('semester') ?: 'all';
            $activeTab = $this->input->get('tab') ?: 'kuis';

            $page_data['tahun']         = $tahun;
            $page_data['bulan']         = $bulan;
            $page_data['semester']      = $semester;
            $page_data['active_tab']    = $activeTab;
            $page_data['is_admin_hrd']  = $isAdminHrd;
            $page_data['my_id']         = $myId;

            $me = $this->db->select('p.*, d.nama AS nama_divisi')
                ->from('pengguna p')
                ->join('divisi d', 'd.id_divisi = p.id_divisi', 'left')
                ->where('p.pengguna_id', $myId)
                ->get()
                ->row();
            $isExempt = $this->md_knowledge->isExemptFromPk($me ? $me->nama : '', $me ? $me->jabatan : '', $me ? $me->nama_divisi : '');
            $page_data['me']           = $me;
            $page_data['is_exempt_pk'] = $isExempt;

            // Data Rekap Nilai Bulanan
            $page_data['list_knowledge'] = $this->md_knowledge->getAllKnowledge($tahun);

            // Data Daftar Kuis Online
            $kuisList = $this->md_knowledge->getKuisList($tahun, $bulan);
            foreach ($kuisList as $k) {
                $statusPeserta = $this->md_knowledge->getPesertaKuis($k->id_kuis, $myId);
                $k->status_peserta = $statusPeserta ? $statusPeserta->status_pengerjaan : 'belum_mengerjakan';
                $k->skor_saya      = $statusPeserta ? $statusPeserta->skor_akhir : null;
                $k->waktu_selesai  = $statusPeserta ? $statusPeserta->waktu_selesai : null;
            }
            $page_data['list_kuis'] = $kuisList;

            $this->load->view('index', $page_data);

        } else if ($param == 'kelola_kuis') {
            // Khusus Admin / HRD: Kelola Soal & Lihat Peserta
            if (!$isAdminHrd) {
                show_error('Akses ditolak: Hanya HRD / Manajemen yang dapat mengelola kuis.', 403);
                return;
            }

            $id_kuis = (int)$param2;
            $kuis = $this->md_knowledge->getKuisById($id_kuis);
            if (!$kuis) {
                show_404();
                return;
            }

            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']     = 'knowledge/v_kelola_kuis';
            $page_data['page_title']    = 'Kelola Soal: ' . $kuis->judul_kuis;
            $page_data['page_desc']     = 'Bank Soal Pilihan Ganda & Rekap Peserta';
            $page_data['kuis']          = $kuis;
            $page_data['soal_list']     = $this->md_knowledge->getSoalByKuis($id_kuis);
            $page_data['peserta_list']  = $this->md_knowledge->getPesertaKuis($id_kuis);
            $this->load->view('index', $page_data);

        } else if ($param == 'kerjakan') {
            // Karyawan: Antarmuka Pengerjaan Kuis (CBT)
            $id_kuis = (int)$param2;
            $kuis = $this->md_knowledge->getKuisById($id_kuis);
            if (!$kuis || $kuis->status !== 'aktif') {
                show_error('Kuis tidak ditemukan atau saat ini tidak aktif.', 404);
                return;
            }

            // Validasi Jadwal Waktu Buka & Tutup
            $now = time();
            if (!empty($kuis->waktu_buka) && $now < strtotime($kuis->waktu_buka)) {
                $this->session->set_flashdata('error_msg', 'Kuis belum dibuka. Jadwal pengerjaan dimulai pada ' . date('d M Y H:i', strtotime($kuis->waktu_buka)) . ' WIB.');
                redirect(base_url('knowledge/show/knowledge'));
                return;
            }
            if (!empty($kuis->waktu_tutup) && $now > strtotime($kuis->waktu_tutup)) {
                $this->session->set_flashdata('error_msg', 'Kuis telah berakhir pada ' . date('d M Y H:i', strtotime($kuis->waktu_tutup)) . ' WIB.');
                redirect(base_url('knowledge/show/knowledge'));
                return;
            }

            $soalList = $this->md_knowledge->getSoalByKuis($id_kuis);
            if (empty($soalList)) {
                show_error('Kuis ini belum memiliki soal ujian. Harap hubungi HRD.', 400);
                return;
            }

            // Cek apakah sudah pernah menyelesaikan
            $existing = $this->md_knowledge->getPesertaKuis($id_kuis, $myId);
            if ($existing && $existing->status_pengerjaan === 'selesai') {
                redirect(base_url('knowledge/show/hasil/' . $id_kuis));
                return;
            }

            // Catat waktu mulai jika belum ada
            if (!$existing) {
                $this->db->insert('pk_peserta', [
                    'id_kuis'           => $id_kuis,
                    'id_pengguna'       => $myId,
                    'waktu_mulai'       => date('Y-m-d H:i:s'),
                    'status_pengerjaan' => 'sedang_mengerjakan'
                ]);
            }

            $page_data['switch']     = $this->id_navbar();
            $page_data['page_name']  = 'knowledge/v_kerjakan_kuis';
            $page_data['page_title'] = 'Ujian Kuis: ' . $kuis->judul_kuis;
            $page_data['page_desc']  = 'Tes Product Knowledge Online';
            $page_data['kuis']       = $kuis;
            $page_data['soal_list']  = $soalList;
            $page_data['peserta']    = $this->md_knowledge->getPesertaKuis($id_kuis, $myId);
            $this->load->view('index', $page_data);

        } else if ($param == 'hasil') {
            $id_kuis = (int)$param2;
            $targetUserId = $myId;
            if ($isAdminHrd && !empty($param3)) {
                $targetUserId = (int)$param3;
            }
            $kuis = $this->md_knowledge->getKuisById($id_kuis);
            $peserta = $this->md_knowledge->getPesertaKuis($id_kuis, $targetUserId);
            if (!$kuis || !$peserta) {
                show_error('Data hasil kuis tidak ditemukan.', 404);
                return;
            }

            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']     = 'knowledge/v_hasil_kuis';
            $page_data['page_title']    = 'Hasil Kuis: ' . $kuis->judul_kuis;
            $page_data['page_desc']     = 'Skor & Review Jawaban Soal';
            $page_data['kuis']          = $kuis;
            $page_data['peserta']       = $peserta;
            $page_data['is_admin_view'] = ($isAdminHrd && $targetUserId !== $myId);
            $page_data['soal_list']     = $this->md_knowledge->getSoalByKuis($id_kuis);
            $this->load->view('index', $page_data);

        } else if ($param == 'detail') {
            $page_data['switch']        = $this->id_navbar();
            $page_data['list_nama']     = $this->md_laporan->getBywhereActive();
            $page_data['pengguna']      = $this->md_pengguna->getById(decrypt($param2));
            $page_data['page_name']     = 'knowledge/v_knowledge_detail';
            $page_data['page_title']    = 'Test Product Knowledge';
            $page_data['page_desc']     = 'Management Data Kepegawaian';
            $this->load->view('index', $page_data);

        } else if ($param == 'my_detail') {
            redirect(base_url('knowledge/show/knowledge'));
            return;
        }
    }

    /* =========================================================================
       AJAX ACTIONS: KELOLA KUIS, SOAL, DAN SUBMIT JAWABAN PESERTA
       ========================================================================= */

    public function ajax_simpan_kuis()
    {
        grantAccessFor('all');
        if (!$this->_isAdminHrd()) {
            ajaxReturnDie('error', 'Hanya HRD / Admin yang berwenang membuat atau mengubah kuis.', FALSE);
        }

        $id_kuis      = (int)$this->input->post('id_kuis', TRUE);
        $judul_kuis   = trim((string)$this->input->post('judul_kuis', TRUE));
        $deskripsi    = trim((string)$this->input->post('deskripsi', FALSE));
        $bulan        = (int)$this->input->post('bulan', TRUE);
        $tahun        = (int)$this->input->post('tahun', TRUE);
        $durasi_menit = (int)$this->input->post('durasi_menit', TRUE);
        $waktu_buka   = trim((string)$this->input->post('waktu_buka', TRUE));
        $waktu_tutup  = trim((string)$this->input->post('waktu_tutup', TRUE));
        $status       = trim((string)$this->input->post('status', TRUE));

        if (empty($judul_kuis)) {
            ajaxReturnDie('error', 'Judul kuis wajib diisi.', FALSE);
        }
        if ($bulan < 1 || $bulan > 12) {
            $bulan = (int)date('n');
        }
        if ($tahun < 2020) {
            $tahun = (int)date('Y');
        }
        if ($durasi_menit <= 0) {
            $durasi_menit = 30;
        }
        if (!in_array($status, ['draft', 'aktif', 'selesai'], true)) {
            $status = 'aktif';
        }

        $waktu_buka_sql = !empty($waktu_buka) ? date('Y-m-d H:i:s', strtotime($waktu_buka)) : null;
        $waktu_tutup_sql = !empty($waktu_tutup) ? date('Y-m-d H:i:s', strtotime($waktu_tutup)) : null;

        if ($waktu_buka_sql && $waktu_tutup_sql && strtotime($waktu_tutup_sql) <= strtotime($waktu_buka_sql)) {
            ajaxReturnDie('error', 'Waktu tutup ujian harus lebih besar dari waktu buka.', FALSE);
        }

        $dataKuis = [
            'judul_kuis'   => $judul_kuis,
            'deskripsi'    => $deskripsi,
            'bulan'        => $bulan,
            'tahun'        => $tahun,
            'durasi_menit' => $durasi_menit,
            'waktu_buka'   => $waktu_buka_sql,
            'waktu_tutup'  => $waktu_tutup_sql,
            'status'       => $status
        ];

        if ($id_kuis > 0) {
            $this->db->where('id_kuis', $id_kuis)->update('pk_kuis', $dataKuis);
            addLog('Product Knowledge', 'Update Paket Kuis: ' . $judul_kuis);
            ajaxReturnDie('success', 'Paket kuis berhasil diperbarui.', ['id_kuis' => $id_kuis]);
        } else {
            $dataKuis['created_by'] = (int)sessPenggunaId();
            $dataKuis['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('pk_kuis', $dataKuis);
            $newId = $this->db->insert_id();
            addLog('Product Knowledge', 'Membuat Paket Kuis Baru: ' . $judul_kuis);
            ajaxReturnDie('success', 'Paket kuis berhasil dibuat. Silakan tambahkan soal.', ['id_kuis' => $newId]);
        }
    }

    public function ajax_hapus_kuis()
    {
        grantAccessFor('all');
        if (!$this->_isAdminHrd()) {
            ajaxReturnDie('error', 'Akses ditolak.', FALSE);
        }

        $id_kuis = (int)$this->input->post('id_kuis', TRUE);
        $this->db->where('id_kuis', $id_kuis)->update('pk_kuis', ['is_deleted' => 1]);
        addLog('Product Knowledge', 'Menghapus Paket Kuis ID: ' . $id_kuis);
        ajaxReturnDie('success', 'Paket kuis berhasil dihapus.', TRUE);
    }

    public function ajax_simpan_soal()
    {
        grantAccessFor('all');
        if (!$this->_isAdminHrd()) {
            ajaxReturnDie('error', 'Akses ditolak.', FALSE);
        }

        $id_soal       = (int)$this->input->post('id_soal', TRUE);
        $id_kuis       = (int)$this->input->post('id_kuis', TRUE);
        $nomor_urut    = (int)$this->input->post('nomor_urut', TRUE) ?: 1;
        $pertanyaan    = trim((string)$this->input->post('pertanyaan', FALSE));
        $pilihan_a     = trim((string)$this->input->post('pilihan_a', FALSE));
        $pilihan_b     = trim((string)$this->input->post('pilihan_b', FALSE));
        $pilihan_c     = trim((string)$this->input->post('pilihan_c', FALSE));
        $pilihan_d     = trim((string)$this->input->post('pilihan_d', FALSE));
        $kunci_jawaban = strtoupper(trim((string)$this->input->post('kunci_jawaban', TRUE)));
        $pembahasan    = trim((string)$this->input->post('pembahasan', FALSE));

        if ($id_kuis <= 0) {
            ajaxReturnDie('error', 'ID Kuis tidak valid.', FALSE);
        }
        if (empty($pertanyaan) || empty($pilihan_a) || empty($pilihan_b) || empty($pilihan_c) || empty($pilihan_d)) {
            ajaxReturnDie('error', 'Pertanyaan dan seluruh opsi A, B, C, D wajib diisi lengkap.', FALSE);
        }
        if (!in_array($kunci_jawaban, ['A', 'B', 'C', 'D'], true)) {
            ajaxReturnDie('error', 'Kunci jawaban harus berupa salah satu dari A, B, C, atau D.', FALSE);
        }

        $dataSoal = [
            'id_kuis'       => $id_kuis,
            'nomor_urut'    => $nomor_urut,
            'pertanyaan'    => $pertanyaan,
            'pilihan_a'     => $pilihan_a,
            'pilihan_b'     => $pilihan_b,
            'pilihan_c'     => $pilihan_c,
            'pilihan_d'     => $pilihan_d,
            'kunci_jawaban' => $kunci_jawaban,
            'pembahasan'    => $pembahasan
        ];

        if ($id_soal > 0) {
            $this->db->where('id_soal', $id_soal)->update('pk_soal', $dataSoal);
            ajaxReturnDie('success', 'Soal berhasil diperbarui.', TRUE);
        } else {
            $this->db->insert('pk_soal', $dataSoal);
            ajaxReturnDie('success', 'Soal berhasil ditambahkan.', TRUE);
        }
    }

    public function ajax_hapus_soal()
    {
        grantAccessFor('all');
        if (!$this->_isAdminHrd()) {
            ajaxReturnDie('error', 'Akses ditolak.', FALSE);
        }

        $id_soal = (int)$this->input->post('id_soal', TRUE);
        $this->db->where('id_soal', $id_soal)->delete('pk_soal');
        ajaxReturnDie('success', 'Soal berhasil dihapus.', TRUE);
    }

    public function ajax_submit_kuis()
    {
        grantAccessFor('all');
        $myId    = (int)sessPenggunaId();
        $id_kuis = (int)$this->input->post('id_kuis', TRUE);
        $jawaban = $this->input->post('jawaban', FALSE); // array id_soal => 'A'/'B'/'C'/'D'

        if (!is_array($jawaban)) {
            $jawaban = [];
        }

        $res = $this->md_knowledge->submitJawabanKuis($id_kuis, $myId, $jawaban);
        if ($res['status'] !== 'success') {
            ajaxReturnDie('error', $res['msg'], FALSE);
        }

        addLog('Product Knowledge', 'Menyelesaikan Kuis ID ' . $id_kuis . ' dengan Skor: ' . $res['skor_akhir']);
        ajaxReturnDie('success', 'Ujian kuis selesai! Skor Anda: ' . $res['skor_akhir'], $res);
    }

    public function add()
    {
        grantAccessFor('all');

        $idPengguna = $this->input->post('id_pengguna', TRUE);
        $nilai      = $this->input->post('nilai', TRUE);
        $bulan      = (int)($this->input->post('bulan', TRUE) ?: date('n'));
        $tahun      = (int)($this->input->post('tahun', TRUE) ?: date('Y'));

        if (!$idPengguna || $nilai === null || $nilai === '') {
            ajaxReturnDie('error', 'Karyawan dan nilai skor harus diisi.', FALSE);
        }

        $this->md_knowledge->saveOrUpdateNilai($idPengguna, $tahun, $bulan, $nilai);

        $ambilDataEv = $this->md_pengguna->getById($idPengguna);
        $namaPegawai = !empty($ambilDataEv) ? $ambilDataEv[0]->nama : 'Karyawan';

        $namaBulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulan = $namaBulanList[$bulan] ?? 'Bulan ' . $bulan;

        addLog('Product Knowledge', 'Input manual nilai ' . $namaPegawai . ' periode ' . $namaBulan . ' ' . $tahun . ' skor ' . $nilai);
        ajaxReturnDie('success', 'Nilai ' . $namaPegawai . ' (' . $namaBulan . ' ' . $tahun . ') berhasil disimpan: ' . $nilai, TRUE);
    }

    public function download_template()
    {
        if (!$this->_isAdminHrd()) {
            show_error('Akses ditolak: Hanya HRD / Manajemen yang dapat mengunduh template.', 403);
            return;
        }

        $tahun = (int)($this->input->get('tahun') ?: date('Y'));
        $list_knowledge = $this->md_knowledge->getAllKnowledge($tahun);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Nilai PK ' . $tahun);

        // Header Info
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT NILAI PRODUCT KNOWLEDGE (TAHUN ' . $tahun . ')');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0F172A'));

        $sheet->setCellValue('A2', 'Petunjuk: Isikan nilai angka (0 - 100) pada kolom bulan terkait. Kolom kosong tidak akan ditimpa/diubah. Kolom ID Pegawai jangan diubah.');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));

        // Header Kolom Baris 4
        $headers = [
            'A4' => 'No',
            'B4' => 'ID Pegawai',
            'C4' => 'NPP',
            'D4' => 'Nama Karyawan',
            'E4' => 'Jabatan',
            'F4' => 'Divisi',
            'G4' => 'Tahun',
            'H4' => 'Jan (S1)',
            'I4' => 'Feb (S1)',
            'J4' => 'Mar (S1)',
            'K4' => 'Apr (S1)',
            'L4' => 'Mei (S1)',
            'M4' => 'Jun (S1)',
            'N4' => 'Jul (S2)',
            'O4' => 'Agu (S2)',
            'P4' => 'Sep (S2)',
            'Q4' => 'Okt (S2)',
            'R4' => 'Nov (S2)',
            'S4' => 'Des (S2)'
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $sheet->getStyle('A4:G4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');
        $sheet->getStyle('H4:M4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDBEAFE'); // Biru muda S1
        $sheet->getStyle('N4:S4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF3C7'); // Kuning muda S2

        $sheet->getStyle('A4:S4')->getFont()->setBold(true);
        $sheet->getStyle('A4:S4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:S4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(26);

        // Isi Data Karyawan
        $rowIdx = 5;
        $no = 1;
        $colLettersS1 = ['H', 'I', 'J', 'K', 'L', 'M'];
        $colLettersS2 = ['N', 'O', 'P', 'Q', 'R', 'S'];

        foreach ($list_knowledge as $emp) {
            $sheet->setCellValue('A' . $rowIdx, $no++);
            $sheet->setCellValue('B' . $rowIdx, $emp['id']);
            $sheet->setCellValue('C' . $rowIdx, !empty($emp['npp']) ? $emp['npp'] : '-');
            $sheet->setCellValue('D' . $rowIdx, $emp['nama']);
            $sheet->setCellValue('E' . $rowIdx, !empty($emp['jabatan']) ? $emp['jabatan'] : '-');
            $sheet->setCellValue('F' . $rowIdx, !empty($emp['divisi']) ? $emp['divisi'] : '-');
            $sheet->setCellValue('G' . $rowIdx, $tahun);

            for ($m = 1; $m <= 6; $m++) {
                $val = isset($emp['nilai'][$m]) && $emp['nilai'][$m] !== null ? $emp['nilai'][$m] : '';
                $sheet->setCellValue($colLettersS1[$m - 1] . $rowIdx, $val);
            }

            for ($m = 7; $m <= 12; $m++) {
                $val = isset($emp['nilai'][$m]) && $emp['nilai'][$m] !== null ? $emp['nilai'][$m] : '';
                $sheet->setCellValue($colLettersS2[$m - 7] . $rowIdx, $val);
            }

            $sheet->getStyle('A' . $rowIdx)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $rowIdx)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $rowIdx)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $rowIdx)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H' . $rowIdx . ':S' . $rowIdx)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $rowIdx++;
        }

        $lastRow = max(5, $rowIdx - 1);
        $sheet->getStyle('A4:S' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFCBD5E1'));

        foreach (range('A', 'S') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Template_Nilai_Product_Knowledge_' . $tahun . '.xlsx';
        if (ob_get_length()) {
            ob_clean();
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function import_excel()
    {
        if (!$this->_isAdminHrd()) {
            show_error('Akses ditolak: Hanya HRD / Manajemen yang dapat mengimpor data nilai.', 403);
            return;
        }

        $tahun = (int)($this->input->post('tahun') ?: date('Y'));

        if (empty($_FILES['berkas_excel']['name'])) {
            $this->session->set_flashdata('error_msg', 'Harap pilih file Excel (.xlsx / .xls / .csv) yang ingin diimpor.');
            redirect(base_url('knowledge/show/knowledge?tahun=' . $tahun . '&tab=rekap'));
            return;
        }

        $fileName = $_FILES['berkas_excel']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['xlsx', 'xls', 'csv'])) {
            $this->session->set_flashdata('error_msg', 'Format file tidak didukung (' . htmlspecialchars($ext) . '). Harap upload file .xlsx, .xls, atau .csv.');
            redirect(base_url('knowledge/show/knowledge?tahun=' . $tahun . '&tab=rekap'));
            return;
        }

        try {
            if ($ext === 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['berkas_excel']['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            $sheetData = $sheet->toArray(null, true, true, false);

            $res = $this->md_knowledge->importExcelData($sheetData, $tahun);

            addLog('Product Knowledge', 'Import nilai Excel tahun ' . $tahun . ': ' . $res['total_saved'] . ' nilai tersimpan');

            $msg = 'Berhasil mengimpor nilai Product Knowledge! Sebanyak ' . $res['total_saved'] . ' nilai bulanan berhasil disinkronisasi ke database & Leaderboard KPI.';
            if (!empty($res['warnings'])) {
                $msg .= ' Terdapat ' . count($res['warnings']) . ' baris yang dilewati (karyawan tidak ditemukan).';
            }
            $this->session->set_flashdata('success_msg', $msg);

        } catch (\Exception $e) {
            $this->session->set_flashdata('error_msg', 'Gagal memproses file Excel: ' . $e->getMessage());
        }

        redirect(base_url('knowledge/show/knowledge?tahun=' . $tahun . '&tab=rekap'));
    }


    public function pagination($param = "")
    {
        grantAccessFor('all');

        $pengguna_id = decrypt($param);
        $dt    = $this->md_knowledge->getAllDetail($pengguna_id);
        $start = $this->input->post('start');
        $data  = array();


        foreach ($dt['data'] as $row) {
            $id       	= encrypt($row->id);
            $li_btn   	= '
                <div class="btn-group" role="group" aria-label="First group">
                <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                </div>';


            $tanggal = $row->bulan; // Misalnya: '2025-05-27'
            $timestamp = strtotime($tanggal);
            $bulan = date('n', $timestamp); // Mengambil angka bulan (1-12)
            $tahun = date('Y', $timestamp); // Mengambil tahun

            $nama_bulan = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];

            $nama_bulan = $nama_bulan[$bulan] . ' ' . $tahun;

                    
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $nama_bulan; // Menggunakan nama bulan dalam bahasa Indonesia
            $th[] = $row->nilai;
            $data[] = $th;
        }
        
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }


    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_knowledge->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }



}
