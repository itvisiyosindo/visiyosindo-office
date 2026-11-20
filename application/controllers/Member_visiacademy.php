<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member_visiacademy extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_member_visiacademy');
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']     = 'home';
        $page_data['page_name']  = 'member_visiacademy/v_member_visiacademy';
        $page_data['page_title'] = 'Member Visi Academy';
        $page_data['page_desc']  = 'Program Referral Marketing & Komisi Alkes PT Visi Yosindo Medikal';
        $page_data['members']    = $this->Md_member_visiacademy->getAllMember();
        $page_data['deals']      = $this->Md_member_visiacademy->getAllDeals();
        $page_data['next_kode']  = $this->Md_member_visiacademy->generateNextKode();
        $page_data['summary']    = $this->Md_member_visiacademy->getSummary();

        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $nama_member        = trim($this->input->post('nama_member', true));
        $kode_member        = trim($this->input->post('kode_member', true));
        $no_telp            = trim($this->input->post('no_telp', true));
        $email              = trim($this->input->post('email', true));
        $institusi_asal     = trim($this->input->post('institusi_asal', true));
        $nama_bank          = trim($this->input->post('nama_bank', true));
        $no_rekening        = trim($this->input->post('no_rekening', true));
        $atas_nama_rekening = trim($this->input->post('atas_nama_rekening', true));

        if (empty($nama_member)) {
            ajaxReturnDie('error', 'Nama member wajib diisi!');
        }

        if (empty($kode_member)) {
            $kode_member = $this->Md_member_visiacademy->generateNextKode();
        } else {
            // Cek apakah kode sudah digunakan
            $existing = $this->Md_member_visiacademy->getMemberByKode($kode_member);
            if ($existing) {
                ajaxReturnDie('error', 'Kode member "' . htmlspecialchars($kode_member) . '" sudah digunakan. Silakan gunakan kode lain.');
            }
        }

        $data = [
            'kode_member'        => $kode_member,
            'nama_member'        => $nama_member,
            'no_telp'            => $no_telp,
            'email'              => $email,
            'institusi_asal'     => $institusi_asal,
            'nama_bank'          => $nama_bank,
            'no_rekening'        => $no_rekening,
            'atas_nama_rekening' => $atas_nama_rekening,
            'status'             => 'aktif'
        ];

        $inserted = $this->Md_member_visiacademy->addMember($data);
        if ($inserted) {
            ajaxReturnDie('success', 'Member berhasil ditambahkan dengan kode ' . $kode_member, true);
        } else {
            ajaxReturnDie('error', 'Gagal menyimpan data member ke database.');
        }
    }

    public function addDeal()
    {
        grantAccessFor('all');

        $id_member      = (int) $this->input->post('id_member', true);
        $nama_produk    = trim($this->input->post('nama_produk', true));
        $raw_nilai      = $this->input->post('estimasi_nilai', true);
        $raw_persen     = $this->input->post('persen_komisi', true);
        $status_deal    = $this->input->post('status_deal', true) ?: 'Pending';
        $catatan        = trim($this->input->post('catatan', true));

        if (empty($id_member)) {
            ajaxReturnDie('error', 'Silakan pilih member yang mereferensikan!');
        }

        if (empty($nama_produk)) {
            ajaxReturnDie('error', 'Nama produk kebutuhan alkes wajib diisi!');
        }

        // Sanitasi angka (hapus titik pemisah ribuan atau string Rp)
        $clean_nilai = preg_replace('/[^0-9]/', '', (string)$raw_nilai);
        $estimasi_nilai = (float) $clean_nilai;

        $clean_persen = str_replace(',', '.', (string)$raw_persen);
        $persen_komisi = (float) $clean_persen;

        if ($estimasi_nilai <= 0) {
            ajaxReturnDie('error', 'Estimasi nilai transaksi harus lebih dari 0!');
        }

        if ($persen_komisi < 0) {
            ajaxReturnDie('error', 'Persentase komisi tidak boleh negatif!');
        }

        $nominal_komisi = ($estimasi_nilai * $persen_komisi) / 100;

        $data = [
            'id_member'      => $id_member,
            'nama_produk'    => $nama_produk,
            'estimasi_nilai' => $estimasi_nilai,
            'persen_komisi'  => $persen_komisi,
            'nominal_komisi' => $nominal_komisi,
            'status_deal'    => in_array($status_deal, ['Pending', 'Deal', 'Batal']) ? $status_deal : 'Pending',
            'catatan'        => $catatan
        ];

        $inserted = $this->Md_member_visiacademy->addDeal($data);
        if ($inserted) {
            $newId = $this->db->insert_id();
            if ($data['status_deal'] === 'Deal' && $newId) {
                $this->_notifDiranggaDeal($newId);
            }
            ajaxReturnDie('success', 'Deal kebutuhan produk berhasil disimpan', true);
        } else {
            ajaxReturnDie('error', 'Gagal menyimpan data deal ke database.');
        }
    }

    public function updateDealStatus()
    {
        grantAccessFor('all');

        $id_deal = (int) $this->input->post('id_deal', true);
        $status  = $this->input->post('status_deal', true);

        if (!$id_deal || !in_array($status, ['Pending', 'Deal', 'Batal'])) {
            ajaxReturnDie('error', 'Data tidak valid.');
        }

        $oldDeal = $this->Md_member_visiacademy->getDealById($id_deal);
        $this->Md_member_visiacademy->updateDealStatus($id_deal, $status);

        // Notifikasi otomatis ke Pak Dirangga saat status berubah menjadi DEAL
        if ($status === 'Deal' && ($oldDeal['status_deal'] ?? '') !== 'Deal') {
            $this->_notifDiranggaDeal($id_deal);
        }

        ajaxReturnDie('success', 'Status deal berhasil diubah menjadi ' . $status, true);
    }

    public function konfirmasiBayar()
    {
        grantAccessFor('all');

        $id_deal      = (int) $this->input->post('id_deal', true);
        $status_bayar = $this->input->post('status_bayar', true) ?: 'Sudah Ditransfer';
        $catatan      = trim($this->input->post('catatan_transfer', true));
        $tgl_input    = $this->input->post('tgl_transfer', true);

        if (!$id_deal) {
            ajaxReturnDie('error', 'ID Deal tidak valid.');
        }

        $deal = $this->Md_member_visiacademy->getDealById($id_deal);
        if (!$deal) {
            ajaxReturnDie('error', 'Data deal tidak ditemukan.');
        }

        $updateData = [
            'status_bayar'     => in_array($status_bayar, ['Belum Ditransfer', 'Sudah Ditransfer']) ? $status_bayar : 'Sudah Ditransfer',
            'catatan_transfer' => $catatan
        ];

        if ($updateData['status_bayar'] === 'Sudah Ditransfer') {
            $updateData['tgl_transfer'] = !empty($tgl_input) ? date('Y-m-d H:i:s', strtotime($tgl_input)) : date('Y-m-d H:i:s');
        } else {
            $updateData['tgl_transfer'] = null;
        }

        if (!empty($_FILES['bukti_transfer']['name'])) {
            $uploadDir = FCPATH . 'uploads/bukti_komisi/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $config['upload_path']   = $uploadDir;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|webp';
            $config['max_size']      = 5120; // 5MB
            $config['file_name']     = 'bukti_' . $id_deal . '_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('bukti_transfer')) {
                $uploadData = $this->upload->data();
                $updateData['bukti_transfer'] = $uploadData['file_name'];
            } else {
                ajaxReturnDie('error', 'Gagal upload bukti transfer: ' . $this->upload->display_errors('', ''));
            }
        }

        $this->Md_member_visiacademy->updatePayment($id_deal, $updateData);
        ajaxReturnDie('success', 'Status dan data pembayaran komisi berhasil disimpan.', true);
    }

    public function deleteMember($id = null)
    {
        grantAccessFor('all');

        $id = (int) $id;
        if (!$id) {
            ajaxReturnDie('error', 'ID member tidak valid.');
        }

        $deals = $this->Md_member_visiacademy->getDealByMember($id);
        if (!empty($deals)) {
            ajaxReturnDie('error', 'Member tidak dapat dihapus karena sudah memiliki riwayat deal.');
        }

        $this->Md_member_visiacademy->deleteMember($id);
        ajaxReturnDie('success', 'Member berhasil dihapus.', true);
    }

    public function deleteDeal($id = null)
    {
        grantAccessFor('all');

        $id = (int) $id;
        if (!$id) {
            ajaxReturnDie('error', 'ID deal tidak valid.');
        }

        $this->Md_member_visiacademy->deleteDeal($id);
        ajaxReturnDie('success', 'Data deal berhasil dihapus.', true);
    }

    /**
     * Kirim notifikasi WhatsApp otomatis ke Pak Dirangga (Head of Accounting and Tax)
     * saat ada referral alkes yang berhasil DEAL dan komisi siap diproses.
     */
    private function _notifDiranggaDeal($id_deal)
    {
        try {
            $deal = $this->Md_member_visiacademy->getDealById($id_deal);
            if (!$deal) {
                return false;
            }

            // Cari nomor WhatsApp Pak Dirangga (pengguna_id: 106)
            $dirangga = $this->db->select('nama, no_hp, nohp')
                                 ->from('pengguna')
                                 ->group_start()
                                     ->where('pengguna_id', 106)
                                     ->or_like('nama', 'Dirangga', 'both')
                                 ->group_end()
                                 ->where('status', 1)
                                 ->limit(1)
                                 ->get()
                                 ->row_array();

            $penerima = '081275085794';
            if ($dirangga) {
                if (!empty($dirangga['no_hp'])) {
                    $penerima = $dirangga['no_hp'];
                } elseif (!empty($dirangga['nohp'])) {
                    $penerima = $dirangga['nohp'];
                }
            }

            $namaMember = $deal['nama_member'] ?: '-';
            $kodeMember = $deal['kode_member'] ?: '-';
            $institusi  = $deal['institusi_asal'] ?: '-';
            $produk     = $deal['nama_produk'] ?: '-';
            $nilaiDeal  = 'Rp ' . number_format($deal['estimasi_nilai'], 0, ',', '.');
            $persen     = $deal['persen_komisi'] . '%';
            $komisi     = 'Rp ' . number_format($deal['nominal_komisi'], 0, ',', '.');
            $bank       = $deal['nama_bank'] ?: 'Belum diisi';
            $noRek      = $deal['no_rekening'] ?: 'Belum diisi';
            $anRek      = $deal['atas_nama_rekening'] ?: $namaMember;
            $catatan    = !empty($deal['catatan']) ? $deal['catatan'] : '-';

            $pesan = "*Notifikasi Deal Baru - Member Visi Academy* 🤝%0A%0A"
                   . "Halo Pak Dirangga,%0A"
                   . "Terdapat transaksi pengadaan alkes dari program Referral Member Visi Academy yang telah berstatus *DEAL* dan siap diproses komisi pencairannya oleh Finance:%0A%0A"
                   . "📋 *Detail Transaksi:*%0A"
                   . "• *ID Deal:* #DL-" . str_pad($deal['id_deal'], 4, '0', STR_PAD_LEFT) . "%0A"
                   . "• *Produk Alkes:* " . $produk . "%0A"
                   . "• *Nilai Transaksi:* " . $nilaiDeal . "%0A"
                   . "• *Komisi Member (" . $persen . "):* *" . $komisi . "*%0A"
                   . "• *Catatan:* " . $catatan . "%0A%0A"
                   . "👤 *Data Penerima (Member):*%0A"
                   . "• *Nama:* " . $namaMember . " (" . $kodeMember . ")%0A"
                   . "• *Instansi:* " . $institusi . "%0A"
                   . "• *Bank:* " . $bank . "%0A"
                   . "• *No. Rekening:* *" . $noRek . "*%0A"
                   . "• *Atas Nama:* " . $anRek . "%0A%0A"
                   . "Silakan periksa detail rekening dan konfirmasi transfer komisi pada sistem ERP Office:%0A"
                   . "https://office.visiyosindo.id/member_visiacademy";

            sendWa([
                'devId'    => hostWa('3'),
                'penerima' => $penerima,
                'pesan'    => $pesan,
            ]);

            return true;
        } catch (\Throwable $e) {
            log_message('error', 'Gagal kirim notif WA Dirangga: ' . $e->getMessage());
            return false;
        }
    }
}
