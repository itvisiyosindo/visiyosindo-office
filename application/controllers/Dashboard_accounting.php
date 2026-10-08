<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property Md_acc_pemasok $md_acc_pemasok
 * @property Md_acc_bukti_lapor_pajak $md_acc_bukti_lapor_pajak
 * @property Md_acc_laporan_keuangan $md_acc_laporan_keuangan
 * @property Md_acc_sk_ketentuan $md_acc_sk_ketentuan
 * @property Md_surat_new $md_surat_new
 */
class Dashboard_accounting extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('md_acc_pemasok');
        $this->load->model('md_acc_bukti_lapor_pajak');
        $this->load->model('md_acc_laporan_keuangan');
        $this->load->model('md_acc_sk_ketentuan');
        $this->load->model('md_surat_new');
        $this->load->helper('url');
        $this->load->helper('form');

        // Access restriction
        if (!isAccountingUser() && !isAdmin() && !isHrd()) {
            redirect(base_url('dashboard'));
        }
    }

    private function id_navbar()
    {
        return "accounting";
    }

    public function index()
    {
        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'dashboard_accounting';
        $page_data['page_title']    = 'Dashboard Accounting & Tax';
        $page_data['tahun_sekarang'] = date('Y');

        // 1. STATS PEMASOK
        $stats_pemasok = $this->md_acc_pemasok->getPemasokStats();
        $page_data['total_pemasok'] = (int)($stats_pemasok->total ?? 0);
        $page_data['pemasok_aktif'] = (int)($stats_pemasok->aktif ?? 0);
        $page_data['pemasok_baru']  = (int)($stats_pemasok->baru ?? 0);
        $page_data['pemasok_ref']   = (int)($stats_pemasok->referensi ?? 0);

        // 2. STATS APPROVAL FAKTUR PAJAK
        $page_data['total_faktur_pajak'] = $this->db
            ->count_all_results('approval_faktur_pajak');

        $page_data['faktur_pajak_approved'] = $this->db
            ->where('status', 4)
            ->count_all_results('approval_faktur_pajak');

        $page_data['faktur_pajak_pending'] = $this->db
            ->where_in('status', [0, 1, 2, 3])
            ->count_all_results('approval_faktur_pajak');

        // 3. STATS BUKTI LAPOR PAJAK
        $page_data['total_bukti_lapor'] = $this->db
            ->where('status', 1)
            ->count_all_results('acc_bukti_lapor_pajak');

        $page_data['bukti_lapor_tahun_ini'] = $this->db
            ->where('status', 1)
            ->where('tahun_pelaporan', date('Y'))
            ->count_all_results('acc_bukti_lapor_pajak');

        // 4. STATS LAPORAN KEUANGAN & SK
        $page_data['total_lap_keuangan'] = $this->db
            ->where('status', 1)
            ->count_all_results('acc_laporan_keuangan');

        $page_data['total_sk_ketentuan'] = $this->db
            ->where('status', 1)
            ->count_all_results('acc_sk_ketentuan');

        // 5. DAFTAR APPROVAL FAKTUR PAJAK TERBARU (10 Terakhir)
        $page_data['faktur_pajak_terbaru'] = $this->db
            ->select('
                fp.id,
                fp.tanggal,
                fp.kode,
                fp.no_po,
                fp.pembayaran,
                fp.status,
                fp.link_lampiran,
                fp.created_at,
                COALESCE(p.nama, "-") as nama_pengaju,
                CASE WHEN fp.id_marketing = 1 THEN "Office / Kantor Pusat" ELSE COALESCE(m.nama, "-") END as nama_marketing,
                COALESCE(c.identitas_pelanggan, "-") as nama_customer
            ', FALSE)
            ->from('approval_faktur_pajak fp')
            ->join('pengguna p', 'fp.id_pengaju = p.pengguna_id', 'left')
            ->join('pengguna m', 'fp.id_marketing = m.pengguna_id', 'left')
            ->join('pelanggan c', 'fp.id_customer = c.id_pelanggan', 'left')
            ->order_by('fp.id', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 6. DAFTAR BUKTI LAPOR PAJAK TERBARU (10 Terakhir)
        $page_data['bukti_lapor_terbaru'] = $this->db
            ->select('
                b.id,
                b.kategori,
                b.nama_dokumen,
                b.tahun_pelaporan,
                b.tanggal_lapor,
                b.batas_akhir,
                b.link_dokumen,
                b.created_at
            ')
            ->from('acc_bukti_lapor_pajak b')
            ->where('b.status', 1)
            ->order_by('b.id', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 7. DAFTAR LAPORAN KEUANGAN TERBARU (10 Terakhir)
        $page_data['lap_keuangan_terbaru'] = $this->db
            ->select('
                lk.id,
                lk.nama_dokumen,
                lk.jenis_dokumen,
                lk.tahun_pelaporan,
                lk.keperluan_dokumen,
                lk.link_dokumen,
                lk.created_at
            ')
            ->from('acc_laporan_keuangan lk')
            ->where('lk.status', 1)
            ->order_by('lk.id', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 8. DAFTAR SK KETENTUAN TERBARU (10 Terakhir)
        $page_data['sk_ketentuan_terbaru'] = $this->db
            ->select('
                sk.id,
                sk.nama_dokumen,
                sk.tanggal_dokumen,
                sk.masa_berlaku,
                sk.link_dokumen,
                sk.created_at
            ')
            ->from('acc_sk_ketentuan sk')
            ->where('sk.status', 1)
            ->order_by('sk.id', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // 9. DISTRIBUSI ARSIP DOKUMEN & TRANSAKSI
        $page_data['distribusi_accounting'] = [
            'Pemasok & Rekanan'    => (int)$page_data['total_pemasok'],
            'Approval Faktur Pajak' => (int)$page_data['total_faktur_pajak'],
            'Bukti Lapor Pajak'    => (int)$page_data['total_bukti_lapor'],
            'Laporan Keuangan'     => (int)$page_data['total_lap_keuangan'],
            'SK & Ketentuan'       => (int)$page_data['total_sk_ketentuan']
        ];

        $this->load->view('index', $page_data);
    }

    public function chart_data_accounting()
    {
        $tahun = $this->input->get('tahun') ?: date('Y');

        // 1. Approval Faktur Pajak per bulan
        $fp_monthly = $this->db
            ->select('MONTH(tanggal) as bulan, COUNT(id) as total')
            ->from('approval_faktur_pajak')
            ->where('YEAR(tanggal)', $tahun)
            ->group_by('MONTH(tanggal)')
            ->get()
            ->result_array();

        // 2. Bukti Lapor Pajak per bulan
        $pajak_monthly = $this->db
            ->select('MONTH(tanggal_lapor) as bulan, COUNT(id) as total')
            ->from('acc_bukti_lapor_pajak')
            ->where('status', 1)
            ->where('tahun_pelaporan', $tahun)
            ->group_by('MONTH(tanggal_lapor)')
            ->get()
            ->result_array();

        // 3. Laporan Keuangan per tahun
        $lap_by_type = $this->db
            ->select('jenis_dokumen, COUNT(id) as total')
            ->from('acc_laporan_keuangan')
            ->where('status', 1)
            ->where('tahun_pelaporan', $tahun)
            ->group_by('jenis_dokumen')
            ->get()
            ->result_array();

        $fp_map    = array_fill(1, 12, 0);
        $pajak_map = array_fill(1, 12, 0);

        foreach ($fp_monthly as $row) {
            $b = (int)$row['bulan'];
            if ($b >= 1 && $b <= 12) {
                $fp_map[$b] = (int)$row['total'];
            }
        }

        foreach ($pajak_monthly as $row) {
            $b = (int)$row['bulan'];
            if ($b >= 1 && $b <= 12) {
                $pajak_map[$b] = (int)$row['total'];
            }
        }

        $bulan_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $response = [
            'labels' => $bulan_labels,
            'faktur_pajak' => array_values($fp_map),
            'bukti_lapor'  => array_values($pajak_map),
            'jenis_laporan' => $lap_by_type
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
