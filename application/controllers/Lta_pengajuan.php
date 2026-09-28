<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property Md_helpdesk_lta_pengajuan $md_helpdesk_lta_pengajuan
 * @property Md_pengguna $md_pengguna
 * @property CI_DB_query_builder $db
 * @property CI_Loader $load
 */
class Lta_pengajuan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('md_helpdesk_lta_pengajuan');
        $this->load->model('md_pengguna');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
    }

    private function getPhoneFromRow($row)
    {
        if (!$row) return '';
        $candidates = ['no_hp', 'hp', 'phone', 'hp_pengguna', 'nowhatsapp', 'notelp', 'mobile'];
        foreach ($candidates as $k) {
            if (isset($row->{$k}) && !empty($row->{$k})) {
                return (string) $row->{$k};
            }
        }
        return '';
    }

    private function id_navbar()
    {
        return 'helpdesk';
    }

    private function approverUserId()
    {
        // Dirangga Madali (Head of Accounting and Tax)
        return 106;
    }

    private function approverBackupUserId()
    {
        // Akun jabatan Head of Accounting and Tax
        return 107;
    }

    private function approverAllowedUserIds()
    {
        return array_values(array_unique([
            (int) $this->approverUserId(),
            (int) $this->approverBackupUserId(),
        ]));
    }

    private function canAccessApprovalMenu($pengguna_id)
    {
        return isAdmin()
            || isHrd()
            || (function_exists('isEksekutif') && isEksekutif())
            || (function_exists('isEksetkutif') && isEksetkutif())
            || in_array((int) $pengguna_id, $this->approverAllowedUserIds(), true);
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
        $pengguna_id = (int) sessPenggunaId();
        $approver_allowed_ids = $this->approverAllowedUserIds();
        $is_admin = isAdmin();
        $_perf_start = $is_admin ? microtime(true) : 0;

        if ($param === 'pengajuan') {
            $t1 = $is_admin ? microtime(true) : 0;
            $approver = $this->getPenggunaRowById($this->approverUserId());
            $t2 = $is_admin ? microtime(true) : 0;
            $approver_backup = $this->getPenggunaRowById($this->approverBackupUserId());
            $t3 = $is_admin ? microtime(true) : 0;

            // If second param is 'list', render the list-only view for Pengajuan Saya
            if ((string) $param2 === 'list') {
                $page_data['switch'] = $this->id_navbar();
                $page_data['page_name'] = 'helpdesk_lta/v_pengajuan_lta_list';
                $page_data['page_title'] = 'Pengajuan Saya';
                $page_data['page_desc'] = 'Daftar pengajuan yang diajukan oleh Anda';
                $t4 = $is_admin ? microtime(true) : 0;
                $page_data['my_submissions'] = $this->md_helpdesk_lta_pengajuan->getMyPengajuan($pengguna_id, 200);
                $t5 = $is_admin ? microtime(true) : 0;
                // Accurate server-side statistics for this pengguna
                $page_data['stats'] = $this->md_helpdesk_lta_pengajuan->countByPenggunaPerStatus($pengguna_id);
                $t6 = $is_admin ? microtime(true) : 0;
                $page_data['role_nav_selected'] = 'pengaju';

                if ($is_admin) {
                    log_message('debug', '[LTA PERF] approver_load=' . round(($t2 - $t1) * 1000, 2) . 'ms, approver_backup=' . round(($t3 - $t2) * 1000, 2) . 'ms, getMy=' . round(($t5 - $t4) * 1000, 2) . 'ms, stats=' . round(($t6 - $t5) * 1000, 2) . 'ms, total=' . round((microtime(true) - $_perf_start) * 1000, 2) . 'ms');
                }

                $this->load->view('index', $page_data);
                return;
            }

            $page_data['switch'] = $this->id_navbar();
            $page_data['page_name'] = 'helpdesk_lta/v_pengajuan_lta';
            $page_data['page_title'] = 'Pengajuan Lumpsum, Akomodasi & Transportasi';
            $page_data['page_desc'] = 'Form Pengajuan Lumpsum di Helpdesk';
            $t4 = $is_admin ? microtime(true) : 0;
            $page_data['my_submissions'] = $this->md_helpdesk_lta_pengajuan->getMyPengajuan($pengguna_id, 120);
            $t5 = $is_admin ? microtime(true) : 0;
            // Provide server-side stats so the view does not compute from a limited fetch
            $page_data['stats'] = $this->md_helpdesk_lta_pengajuan->countByPenggunaPerStatus($pengguna_id);
            $t6 = $is_admin ? microtime(true) : 0;
            $page_data['approver'] = $approver;
            $page_data['approver_backup'] = $approver_backup;
            $page_data['can_access_approval_menu'] = $this->canAccessApprovalMenu($pengguna_id);
            if ($is_admin) {
                log_message('debug', '[LTA PERF] getMy=' . round(($t5 - $t4) * 1000, 2) . 'ms, stats=' . round(($t6 - $t5) * 1000, 2) . 'ms, total=' . round((microtime(true) - $_perf_start) * 1000, 2) . 'ms');
            }
            $page_data['role_nav_selected'] = 'pengaju';

            $this->load->view('index', $page_data);
            return;
        }

        if ($param === 'persetujuan') {
            if (!$this->canAccessApprovalMenu($pengguna_id)) {
                show_error('Anda tidak memiliki akses halaman persetujuan', 403);
                return;
            }

            $page_data['switch'] = $this->id_navbar();
            $page_data['page_name'] = 'helpdesk_lta/v_persetujuan_lta';
            $page_data['page_title'] = 'Daftar Persetujuan LTA';
            $page_data['page_desc'] = 'Persetujuan Pengajuan Lumpsum, Akomodasi & Transportasi';
            $page_data['approval_inbox'] = $this->md_helpdesk_lta_pengajuan->getApprovalInbox($approver_allowed_ids);
            $page_data['approval_history'] = $this->md_helpdesk_lta_pengajuan->getApprovalHistory($approver_allowed_ids, 150);
            $page_data['can_access_approval_menu'] = true;
            $page_data['role_nav_selected'] = 'approver';

            $this->load->view('index', $page_data);
            return;
        }

        if ($param === 'detail') {
            $id = (int) decrypt($param2);
            $detail = $this->md_helpdesk_lta_pengajuan->getPengajuanById($id);

            if (!$detail) {
                show_error('Data pengajuan tidak ditemukan', 404);
                return;
            }

            $can_view = isAdmin()
                || isHrd()
                || ((int) $detail->pengguna_id === $pengguna_id)
                || in_array($pengguna_id, $approver_allowed_ids, true);

            if (!$can_view) {
                show_error('Anda tidak memiliki akses ke data ini', 403);
                return;
            }

            $is_approver = in_array($pengguna_id, $approver_allowed_ids, true);

            $page_data['switch'] = $this->id_navbar();
            $page_data['page_name'] = 'helpdesk_lta/v_detail_lta';
            $page_data['page_title'] = 'Detail Pengajuan LTA';
            $page_data['page_desc'] = 'Rincian Pengajuan Lumpsum, Akomodasi & Transportasi';
            $page_data['detail'] = $detail;
            $page_data['calc'] = $this->calculateTotal((int) $detail->nominal_udara, (int) $detail->nominal_darat, (int) $detail->hari_dinas);
            $page_data['can_approve'] = $is_approver
                && in_array((int) $detail->id_approver, $approver_allowed_ids, true)
                && ((int) $detail->status === 0);
            $page_data['can_manage'] = $is_approver || isAdmin() || isHrd();
            $page_data['can_access_approval_menu'] = $this->canAccessApprovalMenu($pengguna_id);
            $page_data['role_nav_selected'] = $is_approver ? 'approver' : 'pengaju';

            $this->load->view('index', $page_data);
            return;
        }

        show_error('Halaman tidak ditemukan', 404);
    }

    public function add()
    {
        grantAccessFor('all');

        $pengguna_id = (int) sessPenggunaId();
        $nominal_udara = $this->toIntNumber($this->input->post('nominal_udara', true));
        $nominal_darat = $this->toIntNumber($this->input->post('nominal_darat', true));
        $hari_dinas = (int) $this->input->post('hari_dinas', true);
        $link_lampiran_udara = trim((string) $this->input->post('link_lampiran_udara', true));
        $link_lampiran_darat = trim((string) $this->input->post('link_lampiran_darat', true));
        $catatan_pengaju = trim((string) $this->input->post('catatan_pengaju', true));
        $customer_id = $this->input->post('customer_id', true) ?: null;
        $customer_name = trim((string) $this->input->post('customer_name', true));
        $customer_address = trim((string) $this->input->post('customer_address', true));
        $teknisi_id = $this->input->post('teknisi_id', true) ?: null;

        if ($nominal_udara <= 0 && $nominal_darat <= 0) {
            ajaxReturnDie('error', 'Minimal salah satu nominal transportasi (udara atau darat) wajib lebih dari 0');
        }

        if ($hari_dinas <= 0) {
            ajaxReturnDie('error', 'Hari Dinas wajib diisi minimal 1 hari');
        }

        if ($hari_dinas > 60) {
            ajaxReturnDie('error', 'Hari Dinas terlalu besar. Maksimal 60 hari');
        }

        if (!$this->isValidOptionalUrl($link_lampiran_udara)) {
            ajaxReturnDie('error', 'Link Lampiran Transportasi Udara tidak valid');
        }

        if (!$this->isValidOptionalUrl($link_lampiran_darat)) {
            ajaxReturnDie('error', 'Link Lampiran Transportasi Darat tidak valid');
        }

        $now = date('Y-m-d H:i:s');
        $kode_pengajuan = $this->md_helpdesk_lta_pengajuan->generateKodePengajuan();
        $id_approver = $this->approverUserId();

        $data = [
            'kode_pengajuan' => $kode_pengajuan,
            'pengguna_id' => $pengguna_id,
            'id_approver' => $id_approver,
            'nominal_udara' => $nominal_udara,
            'customer_id' => $customer_id,
            'customer_name' => $customer_name,
            'customer_address' => $customer_address,
            'teknisi_id' => $teknisi_id,
            'link_lampiran_udara' => $link_lampiran_udara,
            'hari_dinas' => $hari_dinas,
            'nominal_darat' => $nominal_darat,
            'link_lampiran_darat' => $link_lampiran_darat,
            'catatan_pengaju' => $catatan_pengaju,
            'status' => 0,
            'perusahaan' => grantAccessForPerusahaan(),
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // compute totals and store breakdown so approver can adjust numbers later
        $calc = $this->calculateTotal($nominal_udara, $nominal_darat, $hari_dinas);
        $data = array_merge($data, [
            'udara' => $calc['udara'],
            'udara_plus_20' => $calc['udara_plus_20'],
            'darat' => $calc['darat'],
            'biaya_bagasi' => $calc['biaya_bagasi'],
            'biaya_lain' => $calc['biaya_lain'],
            'uang_pulsa' => $calc['uang_pulsa'],
            'penginapan' => $calc['penginapan'],
            'hari_penginapan' => $calc['hari_penginapan'],
            'uang_saku_makan' => $calc['uang_saku_makan'],
            'transport_lokasi' => $calc['transport_lokasi'],
            'total' => $calc['total'],
        ]);

        $id_pengajuan = $this->md_helpdesk_lta_pengajuan->addPengajuan($data);
        $url_detail = base_url('lta_pengajuan/show/detail/' . encrypt($id_pengajuan));

        $pengaju = $this->getPenggunaRowById($pengguna_id);
        $nama_pengaju = !empty($pengaju) && !empty($pengaju->nama) ? $pengaju->nama : '-';
        $calc = $this->calculateTotal($nominal_udara, $nominal_darat, $hari_dinas);

        $notification_message = 'PENGAJUAN LTA BARU'
            . '%0A'
            . '%0AYth. Head of Accounting and Tax,'
            . '%0A'
            . '%0ATerdapat pengajuan Lumpsum/Akomodasi/Transportasi yang menunggu persetujuan Anda.'
            . '%0A'
            . '%0AKode Pengajuan : ' . $kode_pengajuan
            . '%0APengaju : ' . $nama_pengaju
            . '%0AHari Dinas : ' . $hari_dinas . ' hari'
            . '%0ATransport Udara : Rp ' . number_format($nominal_udara, 0, ',', '.') . ''
            . '%0ATransport Darat : Rp ' . number_format($nominal_darat, 0, ',', '.') . ''
            . '%0ATotal Usulan : Rp ' . number_format($calc['total'], 0, ',', '.') . ''
            . '%0A'
            . '%0ADetail Pengajuan: ' . $url_detail
            . '%0A'
            . '%0AHormat kami,'
            . '%0ALTA System';

        // Send notification to approvers
        $approver_result = $this->notifyUsersByIds($this->approverAllowedUserIds(), $notification_message);
        log_message('info', '[LTA-NOTIF] Sent to approvers - Result: ' . ($approver_result ? 'OK' : 'FAIL'));

        // Send notification to static phone numbers
        $static_phones = ['6289525203954', '6281275085794'];
        $static_result = $this->notifyStaticPhones($static_phones, $notification_message);
        log_message('info', '[LTA-NOTIF] Sent to static phones - Count: ' . $static_result);

        addLog('Pengajuan LTA', 'Pengajuan LTA ' . $kode_pengajuan . ' diajukan oleh ' . $nama_pengaju);
        ajaxReturnDie('success', 'Pengajuan berhasil dikirim ke approval Head of Accounting and Tax', true);
    }

    public function update_data()
    {
        grantAccessFor('all');

        $id = (int) decrypt($this->input->post('id', true));
        if ($id <= 0) {
            ajaxReturnDie('error', 'ID pengajuan tidak valid');
        }

        $detail = $this->md_helpdesk_lta_pengajuan->getPengajuanById($id);
        if (!$detail) {
            ajaxReturnDie('error', 'Data pengajuan tidak ditemukan');
        }

        $pengguna_id = (int) sessPenggunaId();
        if (!$this->canAccessApprovalMenu($pengguna_id)) {
            ajaxReturnDie('error', 'Anda tidak memiliki akses update data pengajuan');
        }

        $nominal_udara = $this->toIntNumber($this->input->post('nominal_udara', true));
        $nominal_darat = $this->toIntNumber($this->input->post('nominal_darat', true));
        $hari_dinas = (int) $this->input->post('hari_dinas', true);
        $customer_id = $this->input->post('customer_id', true) ?: null;
        $customer_name = trim((string) $this->input->post('customer_name', true));
        $customer_address = trim((string) $this->input->post('customer_address', true));
        $teknisi_id = $this->input->post('teknisi_id', true) ?: null;
        $link_lampiran_udara = trim((string) $this->input->post('link_lampiran_udara', true));
        $link_lampiran_darat = trim((string) $this->input->post('link_lampiran_darat', true));
        $catatan_approver = trim((string) $this->input->post('catatan_approver', true));

        // Get breakdown values from form (allow approver to adjust)
        $udara = max(0, $this->toIntNumber($this->input->post('udara', true)));
        $udara_plus_20 = max(0, $this->toIntNumber($this->input->post('udara_plus_20', true)));
        $darat = max(0, $this->toIntNumber($this->input->post('darat', true)));
        $biaya_bagasi = max(0, $this->toIntNumber($this->input->post('biaya_bagasi', true)));
        $biaya_lain = max(0, $this->toIntNumber($this->input->post('biaya_lain', true)));
        $uang_pulsa = max(0, $this->toIntNumber($this->input->post('uang_pulsa', true)));
        $penginapan = max(0, $this->toIntNumber($this->input->post('penginapan', true)));
        $uang_saku_makan = max(0, $this->toIntNumber($this->input->post('uang_saku_makan', true)));
        $transport_lokasi = max(0, $this->toIntNumber($this->input->post('transport_lokasi', true)));

        if ($nominal_udara <= 0 && $nominal_darat <= 0) {
            ajaxReturnDie('error', 'Minimal salah satu nominal transportasi (udara atau darat) wajib lebih dari 0');
        }

        if ($hari_dinas <= 0 || $hari_dinas > 60) {
            ajaxReturnDie('error', 'Hari Dinas wajib diisi antara 1 sampai 60 hari');
        }

        if (!$this->isValidOptionalUrl($link_lampiran_udara)) {
            ajaxReturnDie('error', 'Link Lampiran Transportasi Udara tidak valid');
        }

        if (!$this->isValidOptionalUrl($link_lampiran_darat)) {
            ajaxReturnDie('error', 'Link Lampiran Transportasi Darat tidak valid');
        }

        // Calculate total from custom breakdown values (allow approver to adjust individual components)
        $total = $udara + $udara_plus_20 + $darat + $biaya_bagasi + $biaya_lain + $uang_pulsa + $penginapan + $uang_saku_makan + $transport_lokasi;
        $hari_penginapan = max($hari_dinas - 1, 0);

        $this->md_helpdesk_lta_pengajuan->updatePengajuan($id, [
            'nominal_udara' => $nominal_udara,
            'nominal_darat' => $nominal_darat,
            'hari_dinas' => $hari_dinas,
            'link_lampiran_udara' => $link_lampiran_udara,
            'link_lampiran_darat' => $link_lampiran_darat,
            'catatan_approver' => $catatan_approver,
            'customer_id' => $customer_id,
            'customer_name' => $customer_name,
            'customer_address' => $customer_address,
            'teknisi_id' => $teknisi_id,
            'udara' => $udara,
            'udara_plus_20' => $udara_plus_20,
            'darat' => $darat,
            'biaya_bagasi' => $biaya_bagasi,
            'biaya_lain' => $biaya_lain,
            'uang_pulsa' => $uang_pulsa,
            'penginapan' => $penginapan,
            'hari_penginapan' => $hari_penginapan,
            'uang_saku_makan' => $uang_saku_makan,
            'transport_lokasi' => $transport_lokasi,
            'total' => $total,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        addLog('Pengajuan LTA', 'Data pengajuan LTA ' . $detail->kode_pengajuan . ' diperbarui oleh approver');
        ajaxReturnDie('success', 'Data pengajuan berhasil diperbarui', true);
    }

    public function proses($aksi = '')
    {
        grantAccessFor('all');

        $id = (int) decrypt($this->input->post('id', true));
        if ($id <= 0) {
            ajaxReturnDie('error', 'ID pengajuan tidak valid');
        }

        $detail = $this->md_helpdesk_lta_pengajuan->getPengajuanById($id);
        if (!$detail) {
            ajaxReturnDie('error', 'Data pengajuan tidak ditemukan');
        }

        $pengguna_id = (int) sessPenggunaId();
        $approver_allowed_ids = $this->approverAllowedUserIds();

        if (!in_array($pengguna_id, $approver_allowed_ids, true) || !in_array((int) $detail->id_approver, $approver_allowed_ids, true) || (int) $detail->status !== 0) {
            ajaxReturnDie('error', 'Anda tidak memiliki hak approval untuk pengajuan ini');
        }

        $catatan_approver = trim((string) $this->input->post('catatan_approver', true));
        $alasan_tolak = trim((string) $this->input->post('alasan_tolak', true));
        $now = date('Y-m-d H:i:s');
        $url_detail = base_url('lta_pengajuan/show/detail/' . encrypt($id));

        if ($aksi === 'setujui') {
            $this->md_helpdesk_lta_pengajuan->updatePengajuan($id, [
                'status' => 5,
                'catatan_approver' => $catatan_approver,
                'approved_at' => $now,
                'updated_at' => $now,
            ]);

            $nama_pengaju = !empty($detail->nama_pengaju) ? $detail->nama_pengaju : 'Bapak/Ibu';
            $nama_approver = !empty($detail->nama_approver) ? $detail->nama_approver : 'Head of Accounting and Tax';
            $calc = $this->calculateTotal((int) $detail->nominal_udara, (int) $detail->nominal_darat, (int) $detail->hari_dinas);
            $final_total = (int) preg_replace('/[^0-9]/', '', (string) ($detail->total ?? ''));
            if ($final_total <= 0) {
                $final_total = (int) $calc['total'];
            }

            // Notify the applicant
            $this->notifyUserByPhone(
                $detail->hp_pengaju,
                'PENGAJUAN LTA DISETUJUI'
                    . '%0A'
                    . '%0AYth. ' . $nama_pengaju . ','
                    . '%0A'
                    . '%0APengajuan Lumpsum/Akomodasi/Transportasi Anda telah disetujui.'
                    . '%0A'
                    . '%0AKode Pengajuan : ' . $detail->kode_pengajuan
                    . '%0AStatus : Disetujui'
                    . '%0ADisetujui Oleh : ' . $nama_approver
                    . '%0ATotal Akhir : Rp ' . number_format($final_total, 0, ',', '.') . ''
                    . '%0A'
                    . '%0ADetail Pengajuan: ' . $url_detail
                    . '%0A'
                    . '%0AHormat kami,'
                    . '%0ALTA System'
            );

            // Notify MARKETING PT. VYM group
            $marketing_message = 'Notifikasi Pengajuan LTA'
                . '%0A%0ADear Team Marketing,'
                . '%0A%0A' . $nama_pengaju . ' mengajukan permohonan LTA (Lumpsum, Akomodasi, dan Transportasi):'
                . '%0AKode Pengajuan : ' . $detail->kode_pengajuan
                . '%0ACustomer Name : ' . (!empty($detail->customer_name) ? $detail->customer_name : '-')
                . '%0AHari Dinas : ' . (int) $detail->hari_dinas . ' hari'
                . '%0ATotal Biaya Final : Rp ' . number_format($final_total, 0, ',', '.')
                . '%0A%0ASegera periksa detail pengajuan pada menu LTA di'
                . '%0A' . $url_detail
                . '%0A%0ATerima Kasih';

            $marketing_result = $this->sendWaToMarketingGroup($marketing_message);
            log_message('info', '[LTA-NOTIF-MARKETING] Sent approval to MARKETING PT. VYM - Result: ' . ($marketing_result ? 'OK' : 'FAIL'));

            addLog('Pengajuan LTA', 'Pengajuan LTA ' . $detail->kode_pengajuan . ' disetujui');
            ajaxReturnDie('success', 'Pengajuan berhasil disetujui', true);
        }

        if ($aksi === 'tolak') {
            if (empty($alasan_tolak)) {
                ajaxReturnDie('error', 'Alasan penolakan wajib diisi');
            }

            $this->md_helpdesk_lta_pengajuan->updatePengajuan($id, [
                'status' => 99,
                'catatan_approver' => $catatan_approver,
                'rejected_reason' => $alasan_tolak,
                'updated_at' => $now,
            ]);

            $nama_pengaju = !empty($detail->nama_pengaju) ? $detail->nama_pengaju : 'Bapak/Ibu';
            $nama_approver = !empty($detail->nama_approver) ? $detail->nama_approver : 'Head of Accounting and Tax';

            $this->notifyUserByPhone(
                $detail->hp_pengaju,
                'PENGAJUAN LTA DITOLAK'
                    . '%0A'
                    . '%0AYth. ' . $nama_pengaju . ','
                    . '%0A'
                    . '%0APengajuan Lumpsum/Akomodasi/Transportasi Anda ditolak.'
                    . '%0A'
                    . '%0AKode Pengajuan : ' . $detail->kode_pengajuan
                    . '%0AStatus : Ditolak'
                    . '%0ADitolak Oleh : ' . $nama_approver
                    . '%0AAlasan : ' . $alasan_tolak
                    . '%0A'
                    . '%0ADetail Pengajuan: ' . $url_detail
                    . '%0A'
                    . '%0AHormat kami,'
                    . '%0ALTA System'
            );

            addLog('Pengajuan LTA', 'Pengajuan LTA ' . $detail->kode_pengajuan . ' ditolak');
            ajaxReturnDie('success', 'Pengajuan ditolak', true);
        }

        ajaxReturnDie('error', 'Aksi tidak dikenali');
    }

    private function calculateTotal($nominal_udara, $nominal_darat, $hari_dinas)
    {
        $udara = (int) max(0, $nominal_udara);
        $darat = (int) max(0, $nominal_darat);
        $hari = (int) max(1, $hari_dinas);

        $udara_plus_20 = (int) round($udara * 0.2);
        $biaya_bagasi = 500000;
        $biaya_lain = 500000;
        $uang_pulsa = 20000;
        $penginapan = max($hari - 1, 0) * 500000;
        $uang_saku_makan = $hari * 115000;
        $transport_lokasi = 100000;

        $total = $udara
            + $udara_plus_20
            + $darat
            + $biaya_bagasi
            + $biaya_lain
            + $uang_pulsa
            + $penginapan
            + $uang_saku_makan
            + $transport_lokasi;

        return [
            'udara' => $udara,
            'udara_plus_20' => $udara_plus_20,
            'darat' => $darat,
            'biaya_bagasi' => $biaya_bagasi,
            'biaya_lain' => $biaya_lain,
            'uang_pulsa' => $uang_pulsa,
            'penginapan' => $penginapan,
            'hari_penginapan' => max($hari - 1, 0),
            'uang_saku_makan' => $uang_saku_makan,
            'transport_lokasi' => $transport_lokasi,
            'hari_dinas' => $hari,
            'total' => $total,
        ];
    }

    private function isValidOptionalUrl($url)
    {
        if ($url === '') {
            return true;
        }

        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    private function toIntNumber($value)
    {
        $normalized = preg_replace('/[^0-9]/', '', (string) $value);
        if ($normalized === '') {
            return 0;
        }

        return (int) $normalized;
    }

    private function getCustomerTableId()
    {
        // Try to find the ID column in pelanggan table
        if (!$this->db->table_exists('pelanggan')) {
            return 'id';
        }
        $fields = $this->db->list_fields('pelanggan');
        $id_candidates = ['id', 'id_pelanggan', 'pelanggan_id', 'customer_id', 'cust_id'];
        foreach ($id_candidates as $col) {
            if (in_array($col, $fields, true)) {
                return $col;
            }
        }
        return 'id'; // fallback
    }

    private function getCustomerTableNameCol()
    {
        // Try to find the NAME column in pelanggan table
        if (!$this->db->table_exists('pelanggan')) {
            return 'nama';
        }
        $fields = $this->db->list_fields('pelanggan');
        $name_candidates = ['identitas_pelanggan', 'nama', 'name', 'customer_name', 'nama_pelanggan', 'cpname', 'kontak'];
        foreach ($name_candidates as $col) {
            if (in_array($col, $fields, true)) {
                return $col;
            }
        }
        return 'identitas_pelanggan'; // fallback
    }

    public function ajax_customers()
    {
        grantAccessFor('all');
        $q = trim((string) $this->input->get('q', true));
        $results = [];

        // try table 'pelanggan' then 'customer' — include both if present
        if ($this->db->table_exists('pelanggan')) {
            $id_col = $this->getCustomerTableId();
            $name_col = $this->getCustomerTableNameCol();
            $this->db->select($id_col . ' as id, ' . $name_col . ' as text');
            if (!empty($q)) $this->db->like($name_col, $q);
            $this->db->order_by($name_col, 'ASC');
            $this->db->limit(50);
            $rows = $this->db->get('pelanggan')->result();
            foreach ($rows as $r) $results[] = $r;
        }

        if ($this->db->table_exists('customer')) {
            $this->db->select('id_customer as id, nama_customer as text');
            if (!empty($q)) $this->db->like('nama_customer', $q);
            $this->db->order_by('nama_customer', 'ASC');
            $this->db->limit(50);
            $rows = $this->db->get('customer')->result();
            foreach ($rows as $r) $results[] = $r;
        }

        header('Content-Type: application/json');
        echo json_encode(['results' => $results]);
        exit;
    }

    public function ajax_teknisi()
    {
        grantAccessFor('all');
        $q = trim((string) $this->input->get('q', true));
        $results = [];

        // select pengguna that have no_pegawai (NPP)
        $this->db->select('pengguna_id as id, nama as text');
        $this->db->where('is_active', 1);
        $this->db->where('no_pegawai IS NOT NULL', NULL, FALSE);
        if (!empty($q)) $this->db->like('nama', $q);
        $rows = $this->db->get('pengguna')->result();
        foreach ($rows as $r) $results[] = $r;

        header('Content-Type: application/json');
        echo json_encode(['results' => $results]);
        exit;
    }

    public function ajax_customer_address()
    {
        grantAccessFor('all');
        $customer_id = $this->input->post('customer_id', true);

        if (empty($customer_id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'address' => '']);
            exit;
        }

        $customer_id = (int) $customer_id;
        $address = '';
        $address_candidates = ['alamat', 'alamat_customer', 'address', 'customer_address', 'lokasi', 'kota', 'provinsi'];

        // Try to find in 'pelanggan' table first
        if ($this->db->table_exists('pelanggan')) {
            $id_col = $this->getCustomerTableId();
            $fields = $this->db->list_fields('pelanggan');
            $select_fields = [$id_col];
            foreach ($address_candidates as $candidate) {
                if (in_array($candidate, $fields, true)) {
                    $select_fields[] = $candidate;
                }
            }

            $this->db->select(implode(', ', array_unique($select_fields)));
            $this->db->where($id_col, $customer_id);
            $this->db->limit(1);
            $row = $this->db->get('pelanggan')->row();

            if ($row) {
                foreach ($address_candidates as $col) {
                    if (isset($row->{$col}) && !empty($row->{$col})) {
                        $address = (string) $row->{$col};
                        break;
                    }
                }
            }
        }

        // If not found, try 'customer' table
        if (empty($address) && $this->db->table_exists('customer')) {
            $fields = $this->db->list_fields('customer');
            $select_fields = ['id_customer'];
            foreach ($address_candidates as $candidate) {
                if (in_array($candidate, $fields, true)) {
                    $select_fields[] = $candidate;
                }
            }

            $this->db->select(implode(', ', array_unique($select_fields)));
            $this->db->where('id_customer', $customer_id);
            $this->db->limit(1);
            $row = $this->db->get('customer')->row();

            if ($row) {
                foreach ($address_candidates as $col) {
                    if (isset($row->{$col}) && !empty($row->{$col})) {
                        $address = (string) $row->{$col};
                        break;
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'address' => $address]);
        exit;
    }

    private function notifyUsersByIds($userIds, $message)
    {
        $sent_count = 0;

        foreach ((array) $userIds as $user_id) {
            $row = $this->getPenggunaRowById((int) $user_id);
            if (!$row) {
                log_message('error', '[LTA NOTIF] User ID ' . $user_id . ' not found in system');
                continue;
            }
            $phone_raw = $this->getPhoneFromRow($row);
            if (empty($phone_raw)) {
                log_message('error', '[LTA NOTIF] User ' . $row->nama . ' (ID: ' . $user_id . ') has no phone field');
                continue;
            }

            $normalized = $this->normalizePhone($phone_raw);
            if (empty($normalized)) {
                log_message('error', '[LTA NOTIF] User ' . $row->nama . ' phone normalization failed: ' . $phone_raw);
                continue;
            }

            $result = $this->notifyUserByPhone($normalized, $message);
            log_message('info', '[LTA NOTIF] Sent to ' . $row->nama . ' (' . $normalized . ') - Result: ' . ($result ? 'OK' : 'FAIL'));
            if ($result) $sent_count++;
        }

        return $sent_count > 0;
    }

    private function notifyStaticPhones($phones, $message)
    {
        $sent_count = 0;

        foreach ((array) $phones as $phone) {
            if (empty($phone)) {
                continue;
            }

            $normalized = $this->normalizePhone($phone);
            if (empty($normalized)) {
                log_message('error', '[LTA NOTIF-STATIC] Phone normalization failed: ' . $phone);
                continue;
            }

            $result = $this->notifyUserByPhone($normalized, $message);
            log_message('info', '[LTA NOTIF-STATIC] Sent to ' . $normalized . ' - Result: ' . ($result ? 'OK' : 'FAIL'));
            if ($result) $sent_count++;
        }

        return $sent_count;
    }

    private function notifyUserByPhone($phone, $message)
    {
        $phone = $this->normalizePhone($phone);

        if (empty($phone)) {
            log_message('error', '[LTA NOTIF-INDIVIDUAL] Phone number is empty after normalization');
            return false;
        }

        $dataSend = [
            'devId' => hostWa('1'),
            'penerima' => $phone,
            'pesan' => $message,
        ];

        log_message('info', '[LTA NOTIF-INDIVIDUAL] Attempting send to ' . $phone);
        $res = sendWa($dataSend);
        log_message('info', '[LTA NOTIF-INDIVIDUAL] Send to ' . $phone . ' - Result: ' . ($res ? 'OK' : 'FAIL'));
        return $res;
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

    private function sendWaToMarketingGroup($message)
    {
        // Send WhatsApp notification to MARKETING PT. VYM group
        $dataSend = [
            'devId' => hostWa('1'),
            'penerima' => 'MARKETING PT. VYM',
            'pesan' => $message,
        ];

        log_message('info', '[LTA-NOTIF-MARKETING] Attempting group send to MARKETING PT. VYM');

        // Try to send to group using sendWaGroup if available, otherwise use notifyUserByPhone as fallback
        if (function_exists('sendWaGroup')) {
            $res = sendWaGroup($dataSend);
            log_message('info', '[LTA-NOTIF-MARKETING] sendWaGroup - Result: ' . ($res ? 'OK' : 'FAIL'));
            return $res;
        }

        log_message('error', '[LTA-NOTIF-MARKETING] sendWaGroup function not found!');
        return false;
    }
}
