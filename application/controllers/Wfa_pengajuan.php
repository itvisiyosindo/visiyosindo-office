<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property Md_wfa_pengajuan $md_wfa_pengajuan
 * @property Md_pengguna $md_pengguna
 */
class Wfa_pengajuan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('md_wfa_pengajuan');
        $this->load->model('md_pengguna');
        $this->load->helper('tanggal_helper');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
    }

    private function id_navbar()
    {
        return 'kepegawaian';
    }

    private function memverifikasiUserId()
    {
        return 29;
    }

    private function memverifikasiOfficeUserId()
    {
        return 58;
    }

    private function memverifikasiAllowedUserIds()
    {
        return array_values(array_unique([
            (int) $this->memverifikasiUserId(),
            (int) $this->memverifikasiOfficeUserId(),
        ]));
    }

    private function menyetujuiUserId()
    {
        return 744;
    }

    private function menyetujuiOfficeUserId()
    {
        return 69;
    }

    private function menyetujuiAllowedUserIds()
    {
        return array_values(array_unique([
            (int) $this->menyetujuiUserId(),
            (int) $this->menyetujuiOfficeUserId(),
        ]));
    }

    private function getPenggunaRowById($id)
    {
        $rows = $this->md_pengguna->getById((int) $id);
        if (empty($rows) || !isset($rows[0])) {
            return null;
        }

        return $rows[0];
    }

    public function show($param = '', $param2 = '')
    {
        grantAccessFor('all');

        $param = empty($param) ? 'pengajuan' : $param;
        $pengguna_id = sessPenggunaId();

        if ($param === 'pengajuan') {
            $page_data['switch'] = $this->id_navbar();
            $page_data['page_name'] = 'wfa/v_pengajuan_wfa';
            $page_data['page_title'] = 'Pengajuan WFA';
            $page_data['page_desc'] = 'Form Pengajuan Work From Anywhere';
            $page_data['list_approver'] = $this->md_wfa_pengajuan->getApproverCandidates($pengguna_id);
            $page_data['my_submissions'] = $this->md_wfa_pengajuan->getMyPengajuan($pengguna_id, 100);
            $page_data['memverifikasi_user'] = $this->getPenggunaRowById($this->memverifikasiUserId());
            $page_data['memverifikasi_office_user'] = $this->getPenggunaRowById($this->memverifikasiOfficeUserId());
            $page_data['menyetujui_user'] = $this->getPenggunaRowById($this->menyetujuiUserId());
            $page_data['menyetujui_office_user'] = $this->getPenggunaRowById($this->menyetujuiOfficeUserId());
            $page_data['next_friday'] = $this->nextFridayDate();

            $this->load->view('index', $page_data);
            return;
        }

        if ($param === 'persetujuan') {
            $page_data['switch'] = $this->id_navbar();
            $page_data['page_name'] = 'wfa/v_persetujuan_wfa';
            $page_data['page_title'] = 'Persetujuan WFA';
            $page_data['page_desc'] = 'Approval Mengetahui, Memverifikasi, dan Menyetujui Pengajuan WFA';
            $page_data['approval_inbox'] = $this->md_wfa_pengajuan->getApprovalInbox($pengguna_id);
            $page_data['approval_history'] = $this->md_wfa_pengajuan->getApprovalHistory($pengguna_id, 120);

            $this->load->view('index', $page_data);
            return;
        }

        if ($param === 'detail') {
            $id = (int) $param2;
            $detail = $this->md_wfa_pengajuan->getPengajuanById($id);
            $memverifikasi_allowed_ids = $this->memverifikasiAllowedUserIds();
            $menyetujui_allowed_ids = $this->menyetujuiAllowedUserIds();

            if (!$detail) {
                show_error('Data pengajuan WFA tidak ditemukan', 404);
                return;
            }

            $can_view = (isAdmin() || isHrd())
                || ((int) $detail->pengguna_id === (int) $pengguna_id)
                || ((int) $detail->id_mengetahui === (int) $pengguna_id)
                || in_array((int) $pengguna_id, $memverifikasi_allowed_ids, true)
                || in_array((int) $pengguna_id, $menyetujui_allowed_ids, true);

            if (!$can_view) {
                show_error('Anda tidak memiliki akses ke data ini', 403);
                return;
            }

            $page_data['switch'] = $this->id_navbar();
            $page_data['page_name'] = 'wfa/v_detail_wfa';
            $page_data['page_title'] = 'Detail Pengajuan WFA';
            $page_data['page_desc'] = 'Informasi lengkap pengajuan WFA';
            $page_data['detail'] = $detail;
            $page_data['can_approve_mengetahui'] = ((int) $detail->id_mengetahui === (int) $pengguna_id)
                && ((int) $detail->status_mengetahui === 0)
                && ((int) $detail->status === 0);
            $page_data['can_approve_memverifikasi'] = in_array((int) $pengguna_id, $memverifikasi_allowed_ids, true)
                && ((int) $detail->status_mengetahui === 1)
                && ((int) $detail->status_menyetujui === 0)
                && ((int) $detail->status === 1);
            $page_data['can_approve_menyetujui'] = in_array((int) $pengguna_id, $menyetujui_allowed_ids, true)
                && ((int) $detail->status_mengetahui === 1)
                && ((int) $detail->status_menyetujui === 1)
                && ((int) $detail->status === 2);

            $this->load->view('index', $page_data);
            return;
        }

        if ($param === 'admin_jumat') {
            grantAccessFor(['Administrator', 'Hrd', sessPenggunaId() == 58]);

            $tanggal_input = $this->input->get('tanggal_wfa', true);
            $tanggal_wfa = $this->normalizeDateInput($tanggal_input);

            if (empty($tanggal_wfa)) {
                $tanggal_wfa = $this->nextFridayDate();
            }

            $page_data['switch'] = $this->id_navbar();
            $page_data['page_name'] = 'wfa/v_admin_jumat_wfa';
            $page_data['page_title'] = 'Dashboard WFA Jumat';
            $page_data['page_desc'] = 'Monitoring karyawan WFA pada hari Jumat';
            $page_data['selected_date'] = $tanggal_wfa;
            $page_data['selected_is_friday'] = $this->isFridayDate($tanggal_wfa);
            $page_data['summary'] = $this->md_wfa_pengajuan->getAdminFridaySummary($tanggal_wfa);
            $page_data['list_pengajuan'] = $this->md_wfa_pengajuan->getAdminFridayList($tanggal_wfa);
            $page_data['next_friday'] = $this->nextFridayDate();

            $this->load->view('index', $page_data);
            return;
        }

        show_error('Halaman tidak ditemukan', 404);
    }

    public function add()
    {
        grantAccessFor('all');

        $pengguna_id = (int) sessPenggunaId();
        $tanggal_wfa = $this->normalizeDateInput($this->input->post('tanggal_wfa', true));
        $id_mengetahui = (int) $this->input->post('id_mengetahui', true);
        $tanpa_kepala_divisi = ((int) $this->input->post('tanpa_kepala_divisi', true) === 1);
        $id_memverifikasi = $this->memverifikasiUserId();
        $id_menyetujui = $this->menyetujuiUserId();
        $memverifikasi_allowed_ids = $this->memverifikasiAllowedUserIds();
        $menyetujui_allowed_ids = $this->menyetujuiAllowedUserIds();

        $rencana_pekerjaan = trim((string) $this->input->post('rencana_pekerjaan', true));
        $alasan = 'Pengajuan WFA';
        $lokasi_kerja = 'Rumah';

        if (empty($tanggal_wfa)) {
            ajaxReturnDie('error', 'Tanggal WFA wajib diisi');
        }

        if (!$this->isFridayDate($tanggal_wfa)) {
            ajaxReturnDie('error', 'Pengajuan WFA hanya boleh untuk hari Jumat');
        }

        if ($tanggal_wfa < date('Y-m-d')) {
            ajaxReturnDie('error', 'Tanggal WFA tidak boleh kurang dari hari ini');
        }

        if (!$tanpa_kepala_divisi && $id_mengetahui <= 0) {
            ajaxReturnDie('error', 'Mengetahui wajib dipilih');
        }

        if ($tanpa_kepala_divisi) {
            $id_mengetahui = 0;
        }

        if ($id_mengetahui > 0 && (in_array($id_mengetahui, $memverifikasi_allowed_ids, true) || in_array($id_mengetahui, $menyetujui_allowed_ids, true))) {
            ajaxReturnDie('error', 'Mengetahui tidak boleh sama dengan Memverifikasi/Menyetujui');
        }

        if ($id_mengetahui > 0 && $id_mengetahui === $pengguna_id) {
            ajaxReturnDie('error', 'Pengaju tidak boleh menjadi mengetahui');
        }

        if ($this->md_wfa_pengajuan->hasPendingPengajuanOnDate($pengguna_id, $tanggal_wfa)) {
            ajaxReturnDie('error', 'Anda sudah memiliki pengajuan WFA pending pada tanggal tersebut');
        }

        $pengaju = $this->getPenggunaRowById($pengguna_id);
        $mengetahui = $this->getPenggunaRowById($id_mengetahui);
        $memverifikasi = $this->getPenggunaRowById($id_memverifikasi);
        $menyetujui = $this->getPenggunaRowById($id_menyetujui);

        if (!$pengaju || !$memverifikasi || !$menyetujui || (!$tanpa_kepala_divisi && !$mengetahui)) {
            ajaxReturnDie('error', 'Data pengguna approval tidak valid');
        }

        $now = date('Y-m-d H:i:s');
        $kode_pengajuan = $this->md_wfa_pengajuan->generateKodePengajuan();

        $data = [
            'kode_pengajuan' => $kode_pengajuan,
            'pengguna_id' => $pengguna_id,
            'tanggal_wfa' => $tanggal_wfa,
            'alasan' => $alasan,
            'rencana_pekerjaan' => $rencana_pekerjaan,
            'lokasi_kerja' => $lokasi_kerja,
            'id_mengetahui' => $id_mengetahui,
            'id_menyetujui' => $id_menyetujui,
            'status_mengetahui' => $tanpa_kepala_divisi ? 1 : 0,
            'status_menyetujui' => 0,
            'status' => $tanpa_kepala_divisi ? 1 : 0,
            'catatan_mengetahui' => $tanpa_kepala_divisi ? 'Auto-approved: pengaju tidak memiliki kepala divisi.' : '',
            'perusahaan' => grantAccessForPerusahaan(),
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $id_pengajuan = $this->md_wfa_pengajuan->addPengajuan($data);
        $url_detail = base_url('wfa_pengajuan/show/detail/' . $id_pengajuan);

        $namaPengaju = !empty($pengaju->nama) ? $pengaju->nama : '-';

        if ($tanpa_kepala_divisi) {
            $this->notifyUsersByIds(
                $memverifikasi_allowed_ids,
                '*Notifikasi Approval WFA*'
                . '%0A%0ADear *Bapak/Ibu*, '
                . '%0APengajuan WFA berikut tidak memiliki Kepala Divisi, sehingga tahap Mengetahui otomatis disetujui dan menunggu verifikasi Anda.'
                . '%0A%0AKode Pengajuan : *' . $kode_pengajuan . '*'
                . '%0APengaju       : *' . $namaPengaju . '*'
                . '%0ATanggal WFA   : *' . date('d-m-Y', strtotime($tanggal_wfa)) . '*'
                . '%0ALokasi Kerja  : *Rumah*'
                . '%0AStatus        : Menunggu verifikasi'
                . '%0A%0ASilakan cek detail pengajuan di:'
                . '%0A' . $url_detail
                . '%0A%0ATerima kasih.'
            );

            addLog('Pengajuan WFA', 'Pengajuan WFA ' . $kode_pengajuan . ' diajukan oleh ' . $pengaju->nama . ' (tanpa kepala divisi, auto-approved mengetahui)');
            ajaxReturnDie('success', 'Pengajuan WFA berhasil diajukan dan langsung diteruskan ke Memverifikasi', true);
        }

        $namaMengetahui = !empty($mengetahui->nama) ? $mengetahui->nama : 'Bapak/Ibu';

        $this->notifyUserByPhone(
            $mengetahui->no_hp ?? '',
            '*Notifikasi Pengajuan WFA*'
            . '%0A%0ADear *' . $namaMengetahui . '*, '
            . '%0AAnda menerima pengajuan WFA untuk ditinjau.'
            . '%0A%0AKode Pengajuan : *' . $kode_pengajuan . '*'
            . '%0APengaju       : *' . $namaPengaju . '*'
            . '%0ATanggal WFA   : *' . date('d-m-Y', strtotime($tanggal_wfa)) . '*'
            . '%0ALokasi Kerja  : *Rumah*'
            . '%0AStatus        : Menunggu persetujuan Mengetahui'
            . '%0A%0ASilakan cek detail pengajuan di:'
            . '%0A' . $url_detail
            . '%0A%0ATerima kasih.'
        );

        addLog('Pengajuan WFA', 'Pengajuan WFA ' . $kode_pengajuan . ' diajukan oleh ' . $pengaju->nama);

        ajaxReturnDie('success', 'Pengajuan WFA berhasil diajukan', true);
    }

    public function proses($aksi = '')
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id', true));
        $id = (int) $id;

        if ($id <= 0) {
            ajaxReturnDie('error', 'ID pengajuan tidak valid');
        }

        $detail = $this->md_wfa_pengajuan->getPengajuanById($id);
        if (!$detail) {
            ajaxReturnDie('error', 'Data pengajuan tidak ditemukan');
        }

        $pengguna_id = (int) sessPenggunaId();
        $catatan = trim((string) $this->input->post('catatan', true));
        $alasan_tolak = trim((string) $this->input->post('alasan_tolak', true));
        $now = date('Y-m-d H:i:s');
        $url_detail = base_url('wfa_pengajuan/show/detail/' . $id);
        $memverifikasi_allowed_ids = $this->memverifikasiAllowedUserIds();
        $menyetujui_allowed_ids = $this->menyetujuiAllowedUserIds();

        if ($aksi === 'setujui_mengetahui') {
            if ((int) $detail->id_mengetahui !== $pengguna_id || (int) $detail->status_mengetahui !== 0 || (int) $detail->status !== 0) {
                ajaxReturnDie('error', 'Anda tidak memiliki hak mengetahui untuk pengajuan ini');
            }

            $this->md_wfa_pengajuan->updatePengajuan($id, [
                'status_mengetahui' => 1,
                'status' => 1,
                'catatan_mengetahui' => $catatan,
                'updated_at' => $now,
            ]);

            $namaPengaju = !empty($detail->nama_pengaju) ? $detail->nama_pengaju : '-';
            $namaMengetahui = !empty($detail->nama_mengetahui) ? $detail->nama_mengetahui : '-';

            $this->notifyUsersByIds(
                $memverifikasi_allowed_ids,
                '*Notifikasi Approval WFA*'
                    . '%0A%0ADear *Bapak/Ibu*, '
                    . '%0APengajuan WFA berikut sudah disetujui oleh Mengetahui dan menunggu verifikasi Anda.'
                    . '%0A%0AKode Pengajuan : *' . $detail->kode_pengajuan . '*'
                    . '%0APengaju       : *' . $namaPengaju . '*'
                    . '%0ATanggal WFA   : *' . date('d-m-Y', strtotime($detail->tanggal_wfa)) . '*'
                    . '%0ALokasi Kerja  : *Rumah*'
                    . '%0AMengetahui    : *' . $namaMengetahui . '*'
                    . '%0AStatus        : Menunggu verifikasi'
                    . '%0A%0ASilakan cek detail pengajuan di:'
                    . '%0A' . $url_detail
                    . '%0A%0ATerima kasih.'
            );

            addLog('Pengajuan WFA', 'Mengetahui menyetujui pengajuan WFA ' . $detail->kode_pengajuan);
            ajaxReturnDie('success', 'Pengajuan disetujui oleh Mengetahui dan diteruskan ke Memverifikasi', true);
        }

        if ($aksi === 'tolak_mengetahui') {
            if ((int) $detail->id_mengetahui !== $pengguna_id || (int) $detail->status_mengetahui !== 0 || (int) $detail->status !== 0) {
                ajaxReturnDie('error', 'Anda tidak memiliki hak mengetahui untuk pengajuan ini');
            }

            if (empty($alasan_tolak)) {
                ajaxReturnDie('error', 'Alasan penolakan wajib diisi');
            }

            $this->md_wfa_pengajuan->updatePengajuan($id, [
                'status_mengetahui' => 2,
                'status' => 99,
                'rejected_by' => $pengguna_id,
                'rejected_role' => 'mengetahui',
                'rejected_reason' => $alasan_tolak,
                'catatan_mengetahui' => $catatan,
                'updated_at' => $now,
            ]);

            $namaPengaju = !empty($detail->nama_pengaju) ? $detail->nama_pengaju : 'Bapak/Ibu';
            $namaPenolak = !empty($detail->nama_mengetahui) ? $detail->nama_mengetahui : 'Mengetahui';

            $this->notifyUserByPhone(
                $detail->hp_pengaju,
                '*Notifikasi Pengajuan WFA*'
                    . '%0A%0ADear *' . $namaPengaju . '*, '
                    . '%0APengajuan WFA Anda belum dapat diproses lebih lanjut.'
                    . '%0A%0AKode Pengajuan : *' . $detail->kode_pengajuan . '*'
                    . '%0ATahap         : Mengetahui'
                    . '%0AStatus        : *Ditolak*'
                    . '%0ADitolak Oleh   : *' . $namaPenolak . '*'
                    . '%0AAlasan        : ' . $alasan_tolak
                    . '%0A%0ASilakan cek detail pengajuan di:'
                    . '%0A' . $url_detail
                    . '%0A%0ATerima kasih.'
            );

            addLog('Pengajuan WFA', 'Mengetahui menolak pengajuan WFA ' . $detail->kode_pengajuan);
            ajaxReturnDie('success', 'Pengajuan ditolak oleh Mengetahui', true);
        }

        if ($aksi === 'setujui_memverifikasi') {
            if (!in_array($pengguna_id, $memverifikasi_allowed_ids, true) || !in_array((int) $detail->id_menyetujui, $menyetujui_allowed_ids, true) || (int) $detail->status_mengetahui !== 1 || (int) $detail->status_menyetujui !== 0 || (int) $detail->status !== 1) {
                ajaxReturnDie('error', 'Anda tidak memiliki hak memverifikasi untuk pengajuan ini');
            }

            $this->md_wfa_pengajuan->updatePengajuan($id, [
                'status_menyetujui' => 1,
                'status' => 2,
                'catatan_menyetujui' => $catatan,
                'updated_at' => $now,
            ]);

            $namaPengaju = !empty($detail->nama_pengaju) ? $detail->nama_pengaju : 'Bapak/Ibu';
            $namaMengetahui = !empty($detail->nama_mengetahui) ? $detail->nama_mengetahui : 'Mengetahui';
            $namaMemverifikasi = !empty($detail->nama_memverifikasi) ? $detail->nama_memverifikasi : 'Memverifikasi';

            $this->notifyUsersByIds(
                $menyetujui_allowed_ids,
                '*Notifikasi Approval WFA*'
                    . '%0A%0ADear *Bapak/Ibu*, '
                    . '%0APengajuan WFA berikut sudah diverifikasi dan menunggu persetujuan Anda.'
                    . '%0A%0AKode Pengajuan : *' . $detail->kode_pengajuan . '*'
                    . '%0APengaju       : *' . $namaPengaju . '*'
                    . '%0ATanggal WFA   : *' . date('d-m-Y', strtotime($detail->tanggal_wfa)) . '*'
                    . '%0ALokasi Kerja  : *Rumah*'
                    . '%0AMengetahui    : *' . $namaMengetahui . '*'
                    . '%0AMemverifikasi : *' . $namaMemverifikasi . '*'
                    . '%0AStatus        : Menunggu persetujuan Menyetujui'
                    . '%0A%0ASilakan cek detail pengajuan di:'
                    . '%0A' . $url_detail
                    . '%0A%0ATerima kasih.'
            );

            addLog('Pengajuan WFA', 'Memverifikasi menyetujui pengajuan WFA ' . $detail->kode_pengajuan);
            ajaxReturnDie('success', 'Pengajuan diverifikasi dan diteruskan ke Menyetujui', true);
        }

        if ($aksi === 'tolak_memverifikasi') {
            if (!in_array($pengguna_id, $memverifikasi_allowed_ids, true) || !in_array((int) $detail->id_menyetujui, $menyetujui_allowed_ids, true) || (int) $detail->status_mengetahui !== 1 || (int) $detail->status_menyetujui !== 0 || (int) $detail->status !== 1) {
                ajaxReturnDie('error', 'Anda tidak memiliki hak memverifikasi untuk pengajuan ini');
            }

            if (empty($alasan_tolak)) {
                ajaxReturnDie('error', 'Alasan penolakan wajib diisi');
            }

            $this->md_wfa_pengajuan->updatePengajuan($id, [
                'status_menyetujui' => 2,
                'status' => 99,
                'rejected_by' => $pengguna_id,
                'rejected_role' => 'memverifikasi',
                'rejected_reason' => $alasan_tolak,
                'catatan_menyetujui' => $catatan,
                'updated_at' => $now,
            ]);

            $namaPengaju = !empty($detail->nama_pengaju) ? $detail->nama_pengaju : 'Bapak/Ibu';
            $namaPenolak = !empty($detail->nama_memverifikasi) ? $detail->nama_memverifikasi : 'Memverifikasi';

            $this->notifyUserByPhone(
                $detail->hp_pengaju,
                '*Notifikasi Pengajuan WFA*'
                    . '%0A%0ADear *' . $namaPengaju . '*, '
                    . '%0APengajuan WFA Anda belum dapat diverifikasi.'
                    . '%0A%0AKode Pengajuan : *' . $detail->kode_pengajuan . '*'
                    . '%0ATahap         : Memverifikasi'
                    . '%0AStatus        : *Ditolak*'
                    . '%0ADitolak Oleh   : *' . $namaPenolak . '*'
                    . '%0AAlasan        : ' . $alasan_tolak
                    . '%0A%0ASilakan cek detail pengajuan di:'
                    . '%0A' . $url_detail
                    . '%0A%0ATerima kasih.'
            );

            addLog('Pengajuan WFA', 'Memverifikasi menolak pengajuan WFA ' . $detail->kode_pengajuan);
            ajaxReturnDie('success', 'Pengajuan ditolak oleh Memverifikasi', true);
        }

        if ($aksi === 'setujui_menyetujui') {
            if (!in_array($pengguna_id, $menyetujui_allowed_ids, true) || !in_array((int) $detail->id_menyetujui, $menyetujui_allowed_ids, true) || (int) $detail->status_mengetahui !== 1 || (int) $detail->status_menyetujui !== 1 || (int) $detail->status !== 2) {
                ajaxReturnDie('error', 'Anda tidak memiliki hak menyetujui untuk pengajuan ini');
            }

            $this->md_wfa_pengajuan->updatePengajuan($id, [
                'status' => 5,
                'approved_at' => $now,
                'updated_at' => $now,
            ]);

            $namaPengaju = !empty($detail->nama_pengaju) ? $detail->nama_pengaju : 'Bapak/Ibu';
            $namaMengetahui = !empty($detail->nama_mengetahui) ? $detail->nama_mengetahui : 'Mengetahui';
            $namaMemverifikasi = !empty($detail->nama_memverifikasi) ? $detail->nama_memverifikasi : 'Memverifikasi';
            $namaMenyetujui = !empty($detail->nama_menyetujui) ? $detail->nama_menyetujui : 'Menyetujui';

            $this->notifyUserByPhone(
                $detail->hp_pengaju,
                '*Notifikasi Pengajuan WFA*'
                    . '%0A%0ADear *' . $namaPengaju . '*, '
                    . '%0APengajuan WFA Anda telah selesai diproses.'
                    . '%0A%0AKode Pengajuan : *' . $detail->kode_pengajuan . '*'
                    . '%0ATanggal WFA   : *' . date('d-m-Y', strtotime($detail->tanggal_wfa)) . '*'
                    . '%0AStatus        : *Disetujui*'
                        . '%0ADisetujui Oleh :'
                        . '%0A1. *' . $namaMengetahui . '* (Mengetahui)'
                        . '%0A2. *' . $namaMemverifikasi . '* (Memverifikasi)'
                        . '%0A3. *' . $namaMenyetujui . '* (Menyetujui)'
                    . '%0A%0ASilakan cek detail pengajuan di:'
                    . '%0A' . $url_detail
                    . '%0A%0ATerima kasih.'
            );

            addLog('Pengajuan WFA', 'Menyetujui menyetujui pengajuan WFA ' . $detail->kode_pengajuan);
            ajaxReturnDie('success', 'Pengajuan disetujui', true);
        }

        if ($aksi === 'tolak_menyetujui') {
            if (!in_array($pengguna_id, $menyetujui_allowed_ids, true) || !in_array((int) $detail->id_menyetujui, $menyetujui_allowed_ids, true) || (int) $detail->status_mengetahui !== 1 || (int) $detail->status_menyetujui !== 1 || (int) $detail->status !== 2) {
                ajaxReturnDie('error', 'Anda tidak memiliki hak menyetujui untuk pengajuan ini');
            }

            if (empty($alasan_tolak)) {
                ajaxReturnDie('error', 'Alasan penolakan wajib diisi');
            }

            $this->md_wfa_pengajuan->updatePengajuan($id, [
                'status' => 99,
                'rejected_by' => $pengguna_id,
                'rejected_role' => 'menyetujui',
                'rejected_reason' => $alasan_tolak,
                'updated_at' => $now,
            ]);

            $namaPengaju = !empty($detail->nama_pengaju) ? $detail->nama_pengaju : 'Bapak/Ibu';
            $namaPenolak = !empty($detail->nama_menyetujui) ? $detail->nama_menyetujui : 'Menyetujui';

            $this->notifyUserByPhone(
                $detail->hp_pengaju,
                '*Notifikasi Pengajuan WFA*'
                    . '%0A%0ADear *' . $namaPengaju . '*, '
                    . '%0APengajuan WFA Anda belum dapat disetujui.'
                    . '%0A%0AKode Pengajuan : *' . $detail->kode_pengajuan . '*'
                    . '%0ATahap         : Menyetujui'
                    . '%0AStatus        : *Ditolak*'
                    . '%0ADitolak Oleh   : *' . $namaPenolak . '*'
                    . '%0AAlasan        : ' . $alasan_tolak
                    . '%0A%0ASilakan cek detail pengajuan di:'
                    . '%0A' . $url_detail
                    . '%0A%0ATerima kasih.'
            );

            addLog('Pengajuan WFA', 'Menyetujui menolak pengajuan WFA ' . $detail->kode_pengajuan);
            ajaxReturnDie('success', 'Pengajuan ditolak oleh Menyetujui', true);
        }

        ajaxReturnDie('error', 'Aksi tidak dikenali');
    }

    private function normalizeDateInput($input)
    {
        $input = trim((string) $input);

        if (empty($input)) {
            return null;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $input)) {
            return $input;
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $input)) {
            return date_db_format($input);
        }

        return null;
    }

    private function isFridayDate($tanggal)
    {
        return date('N', strtotime($tanggal)) == 5;
    }

    private function nextFridayDate($fromDate = null)
    {
        $timestamp = empty($fromDate) ? time() : strtotime($fromDate);
        $dayNumber = (int) date('N', $timestamp);
        $delta = 5 - $dayNumber;

        if ($delta < 0) {
            $delta += 7;
        }

        return date('Y-m-d', strtotime('+' . $delta . ' day', $timestamp));
    }

    private function notifyUsersByIds($userIds, $message)
    {
        $sent_phones = [];

        foreach ((array) $userIds as $user_id) {
            $row = $this->getPenggunaRowById((int) $user_id);
            if (!$row || empty($row->no_hp)) {
                continue;
            }

            $normalized = $this->normalizePhone($row->no_hp);
            if (empty($normalized) || isset($sent_phones[$normalized])) {
                continue;
            }

            $this->notifyUserByPhone($normalized, $message);
            $sent_phones[$normalized] = true;
        }

        return !empty($sent_phones);
    }

    private function notifyUserByPhone($phone, $message)
    {
        $phone = $this->normalizePhone($phone);

        if (empty($phone)) {
            return false;
        }

        $dataSend = [
            'devId' => hostWa('1'),
            'penerima' => $phone,
            'pesan' => $message,
        ];

        return sendWa($dataSend);
    }

    private function normalizePhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', (string) $phone);

        if (empty($phone)) {
            return '';
        }

        if (strpos($phone, '0') === 0) {
            return '62' . substr($phone, 1);
        }

        if (strpos($phone, '8') === 0) {
            return '62' . $phone;
        }

        return $phone;
    }
}
