<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Tracking extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_tracking');
        $this->load->model('md_ekspedisi');
        $this->load->model('md_detail_barang_keluar');
        $this->load->model('md_pengeluaran_barang');
        $this->load->model('md_pengiriman_stok');
        $this->load->model('md_detail_barang_pengiriman_stok');
        // COMMENTED OUT: Model untuk penerimaan_stok - diganti dengan serah_terima_barang
        // $this->load->model('md_penerimaan_stok');
        // $this->load->model('md_detail_barang_penerimaan_stok');
        // NEW: Model untuk serah_terima_barang
        $this->load->model('md_serah_terima_barang');
        // NEW: Model untuk kirim_dokumen
        $this->load->model('md_kirim');
        $this->load->helper('whatsapp_helper');
    }

    function id_navbar()
    {
        $id_navbar = "inventory";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor('all');

        // Sinkronisasi otomatis dokumen SJBK, TTB, dan STTB aktif yang belum masuk ke tracking_barang
        $this->md_tracking->autoBackfillUntrackedDocuments();

        $page_data['switch']          = $this->id_navbar();
        $page_data['list_gudang']   = $this->md_tracking->getAllGudang();
        $page_data['list_cust']     = $this->md_tracking->getAllCustomer();
        $page_data['list_eks']      = $this->md_tracking->getAllEkspedisi();
        $page_data['list_sj']       = $this->md_tracking->getAllSJ();
        $page_data['list_pengiriman_stok'] = $this->md_tracking->getAllPengirimanStok();
        // COMMENTED OUT: list_penerimaan_stok - diganti dengan list_serah_terima_barang
        // $page_data['list_penerimaan_stok'] = $this->md_tracking->getAllPenerimaanStok();
        // NEW: list_serah_terima_barang (STTB)
        $page_data['list_serah_terima_barang'] = $this->md_tracking->getAllSerahTerimaBarang();
        $page_data['page_name']      = 'tracking/v_tracking';
        $page_data['page_title']    = 'Tracking Barang';
        $page_data['page_desc']      = 'Management Data Tracking Barang (SJBK, TTB, STTB)';
        $page_data['tracking_mode']  = 'barang'; // Exclude kirim_dokumen
        $this->load->view('index', $page_data);
    }

    public function tracking_dokumen()
    {
        grantAccessFor('all');

        $page_data['switch']          = $this->id_navbar();
        $page_data['list_eks']      = $this->md_tracking->getAllEkspedisi();
        // Get all kirim_dokumen untuk dropdown (simple query)
        $this->db->select('id, kode, nama_customer, pic');
        $this->db->from('kirim_dokumen');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(100);
        $page_data['list_kirim'] = $this->db->get()->result();

        $page_data['page_name']      = 'tracking/v_tracking_dokumen_manage';
        $page_data['page_title']    = 'Tracking Dokumen';
        $page_data['page_desc']      = 'Management Tracking Kirim Dokumen';
        $this->load->view('index', $page_data);
    }


    /**
     * Detail Tracking Dokumen (untuk update status)
     * FIXED VERSION - Sesuai struktur database
     */
    public function detail_dokumen($param1 = null)
    {
        grantAccessFor('all');

        if (empty($param1)) {
            $this->session->set_flashdata('error_message', 'Parameter ID kirim dokumen tidak valid.');
            redirect('tracking/tracking_dokumen');
            return;
        }

        $id_kirim = decrypt($param1);

        // Get data kirim dokumen dari tabel kirim_dokumen
        $this->db->select('
        kd.*,
        e.nama_ekspedisi
    ');
        $this->db->from('kirim_dokumen kd');
        $this->db->join('ekspedisi e', 'kd.id_ekspedisi = e.id_ekspedisi', 'LEFT');
        $this->db->where('kd.id', $id_kirim);
        $data_kirim = $this->db->get()->result();

        if (empty($data_kirim)) {
            $this->session->set_flashdata('error_message', 'Data kirim dokumen tidak ditemukan.');
            redirect('tracking/tracking_dokumen');
            return;
        }

        // Get status history dari tabel kirim_status berdasarkan id_kirim
        $this->db->select('
        ks.*,
        p.nama as nama_pengguna
    ');
        $this->db->from('kirim_status ks');
        $this->db->join('pengguna p', 'ks.id_pengguna = p.pengguna_id', 'LEFT');
        $this->db->where('ks.id_kirim', $id_kirim);
        $this->db->order_by('ks.created_at', 'ASC');
        $status_history = $this->db->get()->result();

        $page_data['switch'] = $this->id_navbar();
        $page_data['list_eks'] = $this->md_tracking->getAllEkspedisi();
        $page_data['data_tracking'] = $data_kirim;
        $page_data['data_status'] = $status_history;
        $page_data['page_name'] = 'tracking/v_detail_tracking_dokumen';
        $page_data['page_title'] = 'Detail Tracking Dokumen';
        $page_data['page_desc'] = 'Detail & Update Status Tracking Dokumen';
        $this->load->view('index', $page_data);
    }

    public function detail($param1 = null)
    {
        grantAccessFor('all');

        // Validasi parameter
        if (empty($param1)) {
            $this->session->set_flashdata('error', 'Parameter ID tracking tidak valid.');
            redirect('tracking');
            return;
        }

        $page_data['switch']          = $this->id_navbar();
        $page_data['list_gudang']   = $this->md_tracking->getAllGudang();
        $page_data['list_cust']     = $this->md_tracking->getAllCustomer();
        $page_data['list_eks']      = $this->md_tracking->getAllEkspedisi();
        $page_data['data_tracking']    = $this->md_tracking->getByWhereIDExtended(['t.id_tracking' => decrypt($param1)]);
        $page_data['data_status']      = $this->md_tracking->getUpdateById(['t.id_tracking' => decrypt($param1)]);

        //Versi Terbaru
        $IDTRACK = $this->md_tracking->getByWhereIDExtended(['t.id_tracking' => decrypt($param1)]);

        $page_data['page_name'] = 'tracking/v_detail_tracking';

        // Load detail barang berdasarkan tracking_type
        if (!empty($IDTRACK) && isset($IDTRACK[0]->tracking_type)) {
            $tracking_type = $IDTRACK[0]->tracking_type;

            if ($tracking_type == 'pengiriman_stok') {
                // Untuk pengiriman stok, load detail barang dari detail_barang_pengiriman_stok
                $page_data['detail_barang_keluar'] = $this->md_detail_barang_pengiriman_stok->getByIdPengirimanStok($IDTRACK[0]->id_pengiriman_stok);
            } elseif ($tracking_type == 'serah_terima_barang') {
                // NEW: Untuk serah terima barang, load detail dari surat_stb_detail
                $page_data['detail_barang_keluar'] = $this->md_tracking->getDetailSerahTerimaBarangById($IDTRACK[0]->id_serah_terima_barang);
                // COMMENTED OUT: elseif untuk penerimaan_stok
                // } elseif ($tracking_type == 'penerimaan_stok') {
                //     // Untuk penerimaan stok, load detail barang dari detail_barang_penerimaan_stok
                //     $page_data['detail_barang_keluar'] = $this->md_detail_barang_penerimaan_stok->getByIdPenerimaanStok($IDTRACK[0]->id_penerimaan_stok);
            } elseif ($tracking_type == 'kirim_dokumen') {
                // NEW: Untuk kirim dokumen, tidak ada detail barang
                $page_data['detail_barang_keluar'] = [];
            } else {
                // Untuk pengeluaran barang (default)
                $page_data['detail_barang_keluar'] = $this->md_detail_barang_keluar->getByIdPengeluaranBarang($IDTRACK[0]->id_pengeluaran_barang);
            }
        } else {
            // Default: pengeluaran barang
            $page_data['detail_barang_keluar'] = $this->md_detail_barang_keluar->getByIdPengeluaranBarang($IDTRACK[0]->id_pengeluaran_barang);
        }

        $page_data['page_title']    = 'Tracking Barang';
        $page_data['page_desc']      = 'Management Data Tracking Barang';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        // Tentukan tipe tracking (pengeluaran_barang, pengiriman_stok, serah_terima_barang, atau kirim_dokumen)
        $tracking_type = $this->input->post('tracking_type', TRUE);

        // Validasi: Cek apakah data sudah ada di tracking
        $doc_id = null;
        $doc_label = '';

        if ($tracking_type == 'pengiriman_stok') {
            $doc_id = $this->input->post('id_pengiriman_stok');
            $doc_label = 'No. Pemindahan ' . $this->input->post('no_pemindahan');
        } elseif ($tracking_type == 'serah_terima_barang') {
            $doc_id = $this->input->post('id_stb');
            $doc_label = 'Kode STTB ' . $this->input->post('kode_stb');
        } elseif ($tracking_type == 'kirim_dokumen') {
            $doc_id = $this->input->post('id_kirim_dokumen');
            $doc_label = 'Kode Kirim Dokumen ' . $this->input->post('kode_kirim_dokumen');
        } else {
            $doc_id = $this->input->post('id_pb');
            $doc_label = 'No. SJ ' . $this->input->post('no_sj');
        }

        // Check if document already exists in tracking
        $existingTracking = $this->md_tracking->checkExistingInTracking($tracking_type, $doc_id);
        if ($existingTracking) {
            ajaxReturnDie('error', $doc_label . ' sudah ada di tracking! (ID Tracking: ' . $existingTracking->id_tracking . ')', FALSE);
            return;
        }

        if ($tracking_type == 'pengiriman_stok') {
            // Tracking untuk Pengiriman Stok (internal transfer - outgoing)
            $data['id_gudang']             = $this->input->post('id_gudang');
            $data['id_customer']           = NULL; // Tidak ada customer untuk internal transfer
            $data['pic_penerima']          = $this->input->post('pic_penerima_ps');
            $data['alamat_penerima']       = $this->input->post('alamat_penerima_ps');
            $data['id_ekspedisi']          = $this->input->post('id_ekspedisi_ps');
            $data['nama_ekspedisi']        = $this->input->post('nama_ekspedisi_ps');
            $data['tgl_pengiriman']        = $this->input->post('tgl_pengiriman_ps');
            $data['no_sj']                 = $this->input->post('no_pemindahan');
            $data['id_pengeluaran_barang'] = NULL;
            $data['id_pengiriman_stok']    = $this->input->post('id_pengiriman_stok');
            $data['id_serah_terima_barang'] = NULL;
            $data['tracking_type']         = 'pengiriman_stok';
            $gudangAsal                    = $this->input->post('gudang_asal_ps');
            $gudangTujuan                  = $this->input->post('gudang_tujuan_ps');
        } elseif ($tracking_type == 'serah_terima_barang') {
            // NEW: Tracking untuk Serah Terima Barang (STTB)
            $data['id_gudang']             = NULL;
            $data['id_customer']           = $this->input->post('id_customer_stb');
            $data['pic_penerima']          = $this->input->post('pic_penerima_stb');
            $data['alamat_penerima']       = $this->input->post('alamat_penerima_stb');
            $data['id_ekspedisi']          = $this->input->post('id_ekspedisi_stb');
            $data['nama_ekspedisi']        = $this->input->post('nama_ekspedisi_stb');
            $data['tgl_pengiriman']        = date_db_format($this->input->post('tgl_pengajuan_stb', TRUE));
            $data['no_sj']                 = $this->input->post('kode_stb');
            $data['id_pengeluaran_barang'] = NULL;
            $data['id_pengiriman_stok']    = NULL;
            $data['id_serah_terima_barang'] = $this->input->post('id_stb');
            $data['tracking_type']         = 'serah_terima_barang';
            $pihak1                        = $this->input->post('nama_pihak1_stb');
            $pihak2                        = $this->input->post('nama_pihak2_stb');
            // COMMENTED OUT: elseif untuk penerimaan_stok
            // } elseif ($tracking_type == 'penerimaan_stok') {
            //     // Tracking untuk Penerimaan Stok (internal transfer - incoming)
            //     $data['id_gudang']             = $this->input->post('id_gudang_pns'); // Gudang tujuan (yang menerima)
            //     $data['id_customer']           = NULL;
            //     $data['pic_penerima']          = $this->input->post('pic_penerima_pns');
            //     $data['alamat_penerima']       = $this->input->post('alamat_penerima_pns');
            //     $data['id_ekspedisi']          = $this->input->post('id_ekspedisi_pns');
            //     $data['nama_ekspedisi']        = $this->input->post('nama_ekspedisi_pns');
            //     $data['tgl_pengiriman']        = $this->input->post('tgl_penerimaan_pns');
            //     $data['no_sj']                 = $this->input->post('no_penerimaan');
            //     $data['id_pengeluaran_barang'] = NULL;
            //     $data['id_pengiriman_stok']    = NULL;
            //     $data['id_penerimaan_stok']    = $this->input->post('id_penerimaan_stok');
            //     $data['tracking_type']         = 'penerimaan_stok';
            //     $gudangAsal                    = $this->input->post('gudang_asal_pns');
            //     $gudangTujuan                  = $this->input->post('gudang_tujuan_pns');
        } elseif ($tracking_type == 'kirim_dokumen') {
            // NEW: Tracking untuk Kirim Dokumen
            $data['id_gudang']             = NULL; // Kirim dokumen tidak memiliki gudang
            $data['id_customer']           = NULL; // Customer disimpan di tabel kirim_dokumen
            $data['pic_penerima']          = $this->input->post('pic_kirim_dokumen');
            $data['alamat_penerima']       = $this->input->post('alamat_kirim_dokumen');
            $data['id_ekspedisi']          = $this->input->post('id_ekspedisi_kd');
            $data['nama_ekspedisi']        = $this->input->post('nama_ekspedisi_kd');
            $data['tgl_pengiriman']        = date_db_format($this->input->post('tgl_kirim_kd', TRUE));
            $data['no_sj']                 = $this->input->post('kode_kirim_dokumen');
            $data['id_pengeluaran_barang'] = NULL;
            $data['id_pengiriman_stok']    = NULL;
            $data['id_serah_terima_barang'] = NULL;
            $data['id_kirim_dokumen']      = $this->input->post('id_kirim_dokumen');
            $data['tracking_type']         = 'kirim_dokumen';
        } else {
            // Tracking untuk Pengeluaran Barang (default - to customer)
            $data['id_gudang']             = $this->input->post('id_gudang');
            $data['id_customer']           = $this->input->post('id_customer');
            $data['pic_penerima']          = $this->input->post('pic_penerima_in');
            $data['alamat_penerima']       = $this->input->post('alamat_penerima_in');
            $data['id_ekspedisi']          = $this->input->post('id_ekspedisi');
            $data['nama_ekspedisi']        = $this->input->post('nama_ekspedisi_in');
            $data['tgl_pengiriman']        = $this->input->post('tgl_keluar_in');
            $data['no_sj']                 = $this->input->post('no_sj');
            $data['id_pengeluaran_barang'] = $this->input->post('id_pb');
            $data['id_pengiriman_stok']    = NULL;
            $data['id_serah_terima_barang'] = NULL;
            $data['tracking_type']         = 'pengeluaran_barang';
            $gudangAsal                    = $this->input->post('gudangAsal');
        }

        //Input Manual
        $data['tgl_sampai']            = date_db_format($this->input->post('tgl_sampai', TRUE));
        $data['no_resi']               = $this->input->post('no_resi');
        $data['link_resi']             = $this->input->post('link_resi');
        $data['keterangan']            = $this->input->post('keterangan');

        // --- Waktu Tracking Kecepatan Warehouse (SLA) ---
        $parseDtInput = function ($val) {
            if (empty($val)) return null;
            $ts = strtotime(str_replace('T', ' ', trim($val)));
            return ($ts && $ts > 0) ? date('Y-m-d H:i:s', $ts) : null;
        };

        $waktu_po_cro          = $parseDtInput($this->input->post('waktu_po_cro', TRUE));
        $waktu_cetak_sj        = $parseDtInput($this->input->post('waktu_cetak_sj', TRUE));
        $waktu_order_ekspedisi = $parseDtInput($this->input->post('waktu_order_ekspedisi', TRUE));
        $waktu_packaging       = $parseDtInput($this->input->post('waktu_packaging', TRUE));
        $waktu_loading         = $parseDtInput($this->input->post('waktu_loading_handover', TRUE));

        // Auto-resolve dari dokumen sumber jika kosong
        if (!empty($data['id_pengeluaran_barang'])) {
            $pbRow = $this->db->select('no_po, data_created, tgl_keluar')
                ->from('pengeluaran_barang')
                ->where('id_pengeluaran_barang', $data['id_pengeluaran_barang'])
                ->get()->row();
            if ($pbRow) {
                if (!$waktu_cetak_sj && !empty($pbRow->data_created)) {
                    $waktu_cetak_sj = $pbRow->data_created;
                }
                if (!$waktu_po_cro && !empty($pbRow->no_po) && $this->db->table_exists('po_masuk')) {
                    $poRow = $this->db->select('cro_date, gm_date, created_at')
                        ->from('po_masuk')
                        ->where('no_po', $pbRow->no_po)
                        ->where('deleted', 0)
                        ->order_by('id_po', 'DESC')
                        ->limit(1)
                        ->get()->row();
                    if ($poRow) {
                        $waktu_po_cro = $poRow->cro_date ?: ($poRow->gm_date ?: $poRow->created_at);
                    }
                }
            }
        } elseif (!empty($data['id_pengiriman_stok'])) {
            $psRow = $this->db->select('data_created')->from('pengiriman_stok')->where('id_pengiriman_stok', $data['id_pengiriman_stok'])->get()->row();
            if ($psRow && !$waktu_cetak_sj && !empty($psRow->data_created)) {
                $waktu_cetak_sj = $psRow->data_created;
            }
        } elseif (!empty($data['id_serah_terima_barang'])) {
            $stbRow = $this->db->select('created_at')->from('surat_stb')->where('id_stb', $data['id_serah_terima_barang'])->get()->row();
            if ($stbRow && !$waktu_cetak_sj && !empty($stbRow->created_at)) {
                $waktu_cetak_sj = $stbRow->created_at;
            }
        }

        $statusInput = (int)$this->input->post('status', TRUE);
        if ($statusInput == 0) $statusInput = 1;

        if (!$waktu_order_ekspedisi) {
            $waktu_order_ekspedisi = date('Y-m-d H:i:s');
        }
        if (!$waktu_packaging && in_array($statusInput, [7, 2, 3, 4, 5], true)) {
            $waktu_packaging = date('Y-m-d H:i:s');
        }
        if (!$waktu_loading && in_array($statusInput, [2, 3, 4, 5], true)) {
            $waktu_loading = date('Y-m-d H:i:s');
        }

        $data['waktu_po_cro']           = $waktu_po_cro;
        $data['waktu_cetak_sj']         = $waktu_cetak_sj;
        $data['waktu_order_ekspedisi']  = $waktu_order_ekspedisi;
        $data['waktu_packaging']        = $waktu_packaging;
        $data['waktu_loading_handover'] = $waktu_loading;

        $this->md_tracking->add($data);

        //Menambahkan ke Status
        $lastGcId = $this->md_tracking->getTrackLastId();
        $lastGcId = $lastGcId->id_tracking;

        $this->md_tracking->reset_increment("tracking_status");
        $dataDetailGc['id_tracking']    = $lastGcId;
        //Logic untuk Status
        $status                        = $this->input->post('status', TRUE);

        // Jika status bernilai null, set status ke 1
        if ($status == 0) {
            $status = "1";
        }

        if ($status == "5") {
            $dataDetailGc['id_status']       = $status;
            $dataDetailGc['nama_penerima']   = $this->input->post('nama_penerima', TRUE);
            $dataDetailGc['tgl_penerima']    = date_db_format($this->input->post('tgl_penerima', TRUE));
            $dataDetailGc['bukti_penerima']  = $this->input->post('bukti_penerima', TRUE);
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
            $keTerangan = "Barang Sudah Diterima oleh " . $this->input->post('nama_penerima', TRUE);
        } else {
            $dataDetailGc['id_status']       = $status;
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
            $dataDetailGc['keterangan_konfirmasi']  = $this->input->post('keterangan_konfirmasi', TRUE);
            $keTerangan                      = $this->input->post('keterangan_konfirmasi', TRUE);
        }


        $this->md_tracking->addStatus($dataDetailGc);


        if ($status == 1) {
            $statusTracking = 'Proses Kirim / Order Ekspedisi';
        } else if ($status == 7) {
            $statusTracking = 'Packaging Barang';
        } else if ($status == 2) {
            $statusTracking = 'Manifest Berangkat / Loading Hand-over';
        } else if ($status == 3) {
            $statusTracking = 'Proses Sortir';
        } else if ($status == 4) {
            $statusTracking = 'Pengantaran Kurir';
        } else if ($status == 5) {
            $statusTracking = 'Diterima';
        } else if ($status == 6) {
            $statusTracking = 'Menunggu Konfirmasi';
        }

        $idTracking = encrypt($lastGcId);

        // Customize notification based on tracking type
        if ($tracking_type == 'pengiriman_stok') {
            $dataWa = [
                // TEST: Kirim ke group test dulu
                // 'idPenerima1'     => 'Test Api Wa Group',
                // 'idPenerima2'     => 'Test Api Wa Group',
                // PRODUCTION: Aktifkan baris di bawah untuk kirim ke group gudang
                'idPenerima1'     => 'MARKETING PT. VYM',
                'idPenerima2'     => 'Gudang PT. VYM',
                'namaSurat'       => 'Tracking Pengiriman Stok',
                'statusSurat'     => 'Update Status',
                'statusTracking'  => $statusTracking,
                'status'          => 'memperbarui status',
                'nosj'            => $data['no_sj'],
                'gudangAsal'      => $gudangAsal,
                'alamatTujuan'    => $gudangTujuan . ' - ' . $data['alamat_penerima'],
                'keTerangan'      => $keTerangan,
                'idTracking'      => $idTracking,
                'csname'          => 'Gudang Tujuan: ' . $gudangTujuan
            ];
        } elseif ($tracking_type == 'serah_terima_barang') {
            // NEW: Notifikasi untuk Serah Terima Barang (STTB)
            $dataWa = [
                // TEST: Kirim ke group test dulu
                'idPenerima1'     => 'API WA GROUP TEST',
                'idPenerima2'     => 'API WA GROUP TEST',
                // PRODUCTION: Aktifkan baris di bawah untuk kirim ke group
                // 'idPenerima1'     => 'MARKETING PT. VYM',
                // 'idPenerima2'     => 'Gudang PT. VYM',
                'namaSurat'       => 'Tracking Serah Terima Barang',
                'statusSurat'     => 'Update Status',
                'statusTracking'  => $statusTracking,
                'status'          => 'memperbarui status',
                'nosj'            => $data['no_sj'],
                'gudangAsal'      => $pihak1,
                'alamatTujuan'    => $data['alamat_penerima'],
                'keTerangan'      => $keTerangan,
                'idTracking'      => $idTracking,
                'csname'          => 'Pihak Penerima: ' . $pihak2
            ];
            // COMMENTED OUT: elseif untuk penerimaan_stok
            // } elseif ($tracking_type == 'penerimaan_stok') {
            //     $dataWa = [
            //         // TEST: Kirim ke group test dulu
            //         'idPenerima1'     => 'Test Api Wa Group',
            //         'idPenerima2'     => 'Test Api Wa Group',
            //         // PRODUCTION: Aktifkan baris di bawah untuk kirim ke group gudang
            //         // 'idPenerima1'     => 'MARKETING PT. VYM',
            //         // 'idPenerima2'     => 'Gudang PT. VYM',
            //         'namaSurat'       => 'Tracking Penerimaan Stok',
            //         'statusSurat'     => 'Update Status',
            //         'statusTracking'  => $statusTracking,
            //         'status'          => 'memperbarui status',
            //         'nosj'            => $data['no_sj'],
            //         'gudangAsal'      => $gudangAsal,
            //         'alamatTujuan'    => $gudangTujuan . ' - ' . $data['alamat_penerima'],
            //         'keTerangan'      => $keTerangan,
            //         'idTracking'      => $idTracking,
            //         'csname'          => 'Gudang Penerima: ' . $gudangTujuan
            //     ];
        } elseif ($tracking_type == 'kirim_dokumen') {
            // NEW: Notifikasi untuk Kirim Dokumen
            $dataWa = [
                // TEST: Kirim ke group test dulu
                // 'idPenerima1'     => 'Test Api Wa Group',
                // 'idPenerima2'     => 'Test Api Wa Group',
                // PRODUCTION: Aktifkan baris di bawah untuk kirim ke group
                'idPenerima1'     => 'MARKETING PT. VYM',
                'idPenerima2'     => 'Gudang PT. VYM',
                'namaSurat'       => 'Tracking Kirim Dokumen',
                'statusSurat'     => 'Update Status',
                'statusTracking'  => $statusTracking,
                'status'          => 'memperbarui status',
                'nosj'            => $data['no_sj'],
                'gudangAsal'      => 'Warehouse',
                'alamatTujuan'    => $data['alamat_penerima'],
                'keTerangan'      => $keTerangan,
                'idTracking'      => $idTracking,
                'csname'          => $data['pic_penerima']
            ];
        } else {
            $ambilDataCustomer = $this->md_tracking->getCustomerById($data['id_customer']);
            $namacs = isset($ambilDataCustomer[0]) ? $ambilDataCustomer[0]->nama_customer : '-';

            $dataWa = [
                'idPenerima1'     => 'MARKETING PT. VYM',
                'idPenerima2'     => 'Gudang PT. VYM',
                'namaSurat'       => 'Tracking Barang',
                'statusSurat'     => 'Update Status',
                'statusTracking'  => $statusTracking,
                'status'          => 'memperbarui status',
                'nosj'            => $data['no_sj'],
                'gudangAsal'      => $gudangAsal,
                'alamatTujuan'    => $data['alamat_penerima'],
                'keTerangan'      => $keTerangan,
                'idTracking'      => $idTracking,
                'csname'          => $namacs
            ];
        }
        $this->notifWaAppGudangGroup(2, $dataWa);

        //add log
        $aksi = 'Tracking Barang';
        $ket = 'Menambahkan data Tracking Barang No: ' . $data['no_sj'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }

    /**
     * Add Status Tracking Dokumen
     */
    public function add_status_dokumen()
    {
        grantAccessFor('all');

        $id_kirim = $this->input->post('id_kirim');
        $id_status = $this->input->post('id_status');

        if (empty($id_kirim) || $id_status === '') {
            ajaxReturnDie('error', 'Data tidak lengkap', FALSE);
            return;
        }

        $data = [
            'id_kirim' => $id_kirim,
            'id_status' => $id_status,
            'id_pengguna' => sessPenggunaId(),
            'keterangan_konfirmasi' => $this->input->post('keterangan_konfirmasi')
        ];

        // Jika status Diterima (5)
        if ($id_status == 5) {
            $data['nama_penerima'] = $this->input->post('nama_penerima');
            $data['tgl_penerima'] = $this->input->post('tgl_penerima');
            $data['bukti_penerima'] = $this->input->post('bukti_penerima');
        }

        // Insert to kirim_status
        $this->db->insert('kirim_status', $data);

        addlog('Tracking Dokumen', 'Menambahkan status tracking dokumen ID: ' . $id_kirim);
        ajaxReturnDie('success', 'Status tracking berhasil ditambahkan', TRUE);
    }

    /**
     * Update Status Tracking Dokumen (dari detail)
     */
    public function update_status_dokumen()
    {
        grantAccessFor('all');

        $id_kirim = decrypt($this->input->post('id_kirim'));
        $id_status = $this->input->post('status');

        if (empty($id_kirim) || $id_status === '') {
            ajaxReturnDie('error', 'Data tidak lengkap', FALSE);
            return;
        }

        $data = [
            'id_kirim' => $id_kirim,
            'id_status' => $id_status,
            'id_pengguna' => sessPenggunaId(),
            'keterangan_konfirmasi' => $this->input->post('keterangan_konfirmasi')
        ];

        // Jika status Diterima (5)
        if ($id_status == 5) {
            $data['nama_penerima'] = $this->input->post('nama_penerima');
            $data['tgl_penerima'] = date_db_format($this->input->post('tgl_penerima'));
            $data['bukti_penerima'] = $this->input->post('bukti_penerima');
        }

        // Insert to kirim_status
        $this->db->insert('kirim_status', $data);

        addlog('Tracking Dokumen', 'Update status tracking dokumen ID: ' . $id_kirim);
        ajaxReturnDie('success', 'Status tracking berhasil diupdate', TRUE);
    }

    /**
     * Pagination for Tracking Dokumen (DataTables Server-Side)
     */
    public function pagination_dokumen()
    {
        grantAccessFor('all');

        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search')['value'];

        // Get data with latest status - SIMPLIFIED QUERY
        $sql = "SELECT 
                    kd.id as id_kirim_dokumen,
                    ks.id,
                    ks.id_kirim,
                    ks.id_status,
                    ks.created_at,
                    kd.kode,
                    kd.nama_customer,
                    kd.pic,
                    kd.ekspedisi,
                    kd.no_resi,
                    CASE ks.id_status
                        WHEN 0 THEN 'Pending'
                        WHEN 1 THEN 'Proses Kirim'
                        WHEN 2 THEN 'Pickup'
                        WHEN 3 THEN 'On Transit'
                        WHEN 4 THEN 'Out for Delivery'
                        WHEN 5 THEN 'Diterima'
                        ELSE 'Unknown'
                    END as status_label,
                    te.id_tagihan as tagihan_exists
                FROM kirim_dokumen kd
                LEFT JOIN (
                    SELECT id_kirim, MAX(id) as max_id
                    FROM kirim_status
                    GROUP BY id_kirim
                ) latest ON kd.id = latest.id_kirim
                LEFT JOIN kirim_status ks ON latest.max_id = ks.id
                LEFT JOIN tagihan_ekspedisi te ON te.id_kirim = kd.id
                WHERE 1=1";

        if (!empty($search)) {
            $sql .= " AND (kd.kode LIKE '%" . $this->db->escape_like_str($search) . "%' 
                      OR kd.nama_customer LIKE '%" . $this->db->escape_like_str($search) . "%'
                      OR kd.no_resi LIKE '%" . $this->db->escape_like_str($search) . "%')";
        }

        // Get total count
        $total_query = $this->db->query($sql);
        $total_records = $total_query->num_rows();

        // Add limit for pagination
        $sql .= " ORDER BY kd.id DESC LIMIT " . $length . " OFFSET " . $start;

        $query = $this->db->query($sql);
        $data = $query->result();

        $result = [];
        $no = $start + 1;
        $is_admin = in_array(sessPenggunaId(), [1, 15, 33, 7, 73, 749, 763, 769]);

        foreach ($data as $row) {
            // Status badge color
            $status_class = 'secondary';
            if ($row->id_status == 5) $status_class = 'success';
            elseif ($row->id_status == 2 || $row->id_status == 3) $status_class = 'info';
            elseif ($row->id_status == 4) $status_class = 'warning';

            $status_badge = '<span class="badge badge-' . $status_class . '">' . $row->status_label . '</span>';

            $id_enc = encrypt($row->id_kirim);

            // Action buttons
            $actions = '<div class="btn-group" role="group">';
            $actions .= '<a href="' . base_url('tracking/detail_dokumen/' . $id_enc) . '" 
                           class="btn btn-sm btn-info" title="Lihat Detail & Update Status">
                           <i class="fas fa-eye"></i>
                        </a>';

            // Admin: Edit Full
            if ($is_admin) {
                $actions .= '<a href="' . base_url('tracking/edit_full_dokumen/' . $id_enc) . '" 
                               class="btn btn-sm btn-warning" title="Edit Full (Admin)">
                               <i class="fas fa-edit"></i>
                            </a>';
            }

            // Status Diterima + ada ekspedisi: Ajukan Tagihan
            if ($row->id_status == 5 && !empty($row->ekspedisi)) {
                if (!empty($row->tagihan_exists)) {
                    // Sudah ada tagihan - tombol disabled
                    $actions .= '<button class="btn btn-sm btn-secondary" disabled title="Tagihan Sudah Diajukan" style="cursor: not-allowed; opacity: 0.6;">
                                   <i class="fas fa-check-circle"></i>
                                </button>';
                } else {
                    // Belum ada tagihan - tombol aktif
                    $actions .= '<a href="' . base_url('tagihan/ajukan_kirim/' . $id_enc) . '" 
                                   class="btn btn-sm btn-success" title="Ajukan Tagihan Ekspedisi">
                                   <i class="fas fa-file-invoice-dollar"></i>
                                </a>';
                }
            }

            $actions .= '</div>';

            $result[] = [
                $row->id_kirim_dokumen,
                indo_date($row->created_at),
                '<strong>' . $row->kode . '</strong>',
                $row->nama_customer . '<br><small class="text-muted">' . $row->pic . '</small>',
                $row->ekspedisi ?: '-',
                $row->no_resi ?: '-',
                $status_badge,
                $actions
            ];
        }

        echo json_encode([
            'draw' => $draw,
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_records,
            'data' => $result
        ]);
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_ekspedisi->getById($id);
        foreach ($dt as $row) {
            $row->id_ekspedisi = encrypt($row->id_ekspedisi);
        }
        echo json_encode($dt);
        die;
    }

    /**
     * Edit Full Tracking Dokumen (Admin Only)
     * FIXED VERSION - Sesuai struktur database
     */
    public function edit_full_dokumen($param1 = null)
    {
        // Admin only
        if (!in_array(sessPenggunaId(), [1, 15, 33, 7, 73, 749, 763, 769])) {
            $this->session->set_flashdata('error_message', 'Anda tidak memiliki akses untuk mengedit data tracking dokumen.');
            redirect('tracking/tracking_dokumen');
            return;
        }

        if (empty($param1)) {
            $this->session->set_flashdata('error_message', 'Parameter ID tidak valid.');
            redirect('tracking/tracking_dokumen');
            return;
        }

        $id_kirim = decrypt($param1);

        // Get data kirim dokumen dari tabel kirim_dokumen
        $this->db->select('
        kd.*,
        e.nama_ekspedisi
    ');
        $this->db->from('kirim_dokumen kd');
        $this->db->join('ekspedisi e', 'kd.id_ekspedisi = e.id_ekspedisi', 'LEFT');
        $this->db->where('kd.id', $id_kirim);
        $data_kirim = $this->db->get()->result();

        if (empty($data_kirim)) {
            $this->session->set_flashdata('error_message', 'Data kirim dokumen tidak ditemukan.');
            redirect('tracking/tracking_dokumen');
            return;
        }

        // Get status history dari tabel kirim_status berdasarkan id_kirim
        $this->db->select('
        ks.*,
        p.nama as nama_pengguna
    ');
        $this->db->from('kirim_status ks');
        $this->db->join('pengguna p', 'ks.id_pengguna = p.pengguna_id', 'LEFT');
        $this->db->where('ks.id_kirim', $id_kirim);
        $this->db->order_by('ks.created_at', 'ASC');
        $status_history = $this->db->get()->result();

        // Get customer list
        $list_cust = $this->md_tracking->getAllCustomer();

        // Get ekspedisi list
        $list_ekspedisi = $this->md_ekspedisi->getByWhere();

        $page_data['list_status'] = [
            '0' => 'Pending',
            '1' => 'Proses Kirim',
            '2' => 'Pickup',
            '3' => 'On Transit',
            '4' => 'Out for Delivery',
            '5' => 'Diterima'
        ];

        $page_data['switch'] = $this->id_navbar();
        $page_data['data_tracking'] = $data_kirim;
        $page_data['data_status'] = $status_history;
        $page_data['list_cust'] = $list_cust;
        $page_data['list_ekspedisi'] = $list_ekspedisi;
        $page_data['page_name'] = 'tracking/v_edit_tracking_dokumen';
        $page_data['page_title'] = 'Management Tracking Dokumen';
        $page_data['page_desc'] = 'Edit Data Tracking Kirim Dokumen';

        $this->load->view('index', $page_data);
    }


    /**
     * Edit Tracking Page (Admin Only)
     */
    public function edit_full($param1 = null)
    {
        // Admin only
        if (!in_array(sessPenggunaId(), [1, 15, 33, 7, 749, 73, 763, 769])) {
            $this->session->set_flashdata('error_message', 'Anda tidak memiliki akses untuk mengedit data tracking.');
            redirect('tracking');
            return;
        }

        if (empty($param1)) {
            $this->session->set_flashdata('error_message', 'Parameter ID tracking tidak valid.');
            redirect('tracking');
            return;
        }

        $id_tracking = decrypt($param1);

        // Get tracking data
        $data_tracking = $this->md_tracking->getByWhereIDExtended(['t.id_tracking' => $id_tracking]);
        if (empty($data_tracking)) {
            $this->session->set_flashdata('error_message', 'Data tracking tidak ditemukan.');
            redirect('tracking');
            return;
        }

        $tracking = $data_tracking[0];
        $tracking_type = $tracking->tracking_type ?? 'pengeluaran_barang';

        // Get detail barang based on tracking type
        $detail_barang = [];
        if ($tracking_type == 'pengiriman_stok' && !empty($tracking->id_pengiriman_stok)) {
            $detail_barang = $this->md_detail_barang_pengiriman_stok->getByIdPengirimanStok($tracking->id_pengiriman_stok);
        } elseif ($tracking_type == 'serah_terima_barang' && !empty($tracking->id_serah_terima_barang)) {
            $detail_barang = $this->md_tracking->getDetailSerahTerimaBarangById($tracking->id_serah_terima_barang);
        } elseif (!empty($tracking->id_pengeluaran_barang)) {
            $detail_barang = $this->md_detail_barang_keluar->getByIdPengeluaranBarang($tracking->id_pengeluaran_barang);
        }

        // Get status history
        $status_history = $this->md_tracking->getStatusHistoryByTracking($id_tracking);

        // Get ekspedisi list
        $list_ekspedisi = $this->md_ekspedisi->getByWhere();

        // Define list status untuk dropdown dan label
        $list_status = [
            0 => 'Menunggu Order Ekspedisi',
            1 => 'Proses Kirim / Order Ekspedisi',
            7 => 'Packaging Barang',
            2 => 'Manifest Berangkat / Loading Hand-over',
            3 => 'Proses Sortir',
            4 => 'Pengantaran Kurir',
            5 => 'Diterima',
            6 => 'Menunggu Konfirmasi'
        ];

        $page_data['switch'] = $this->id_navbar();
        $page_data['data_tracking'] = $data_tracking;
        $page_data['detail_barang'] = $detail_barang;
        $page_data['data_status'] = $status_history;
        $page_data['status_history'] = $status_history; // Tambahan untuk view yang masih pakai variabel lama
        $page_data['list_status'] = $list_status;
        $page_data['list_ekspedisi'] = $list_ekspedisi;
        $page_data['page_name'] = 'tracking/v_edit_tracking';
        $page_data['page_title'] = 'Edit Tracking';
        $page_data['page_desc'] = 'Edit Data Tracking Barang (Admin)';

        $this->load->view('index', $page_data);
    }

    public function update_full_dokumen()
    {
        header('Content-Type: application/json');

        // Validasi Admin
        if (!in_array(sessPenggunaId(), [1, 15, 33, 7, 73, 749, 763, 769])) {
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            return;
        }

        // Validasi ID
        $id_enc = $this->input->post('id_kirim');
        if (!$id_enc) {
            echo json_encode(['status' => 'error', 'message' => 'ID Kirim tidak valid.']);
            return;
        }

        $id_kirim = decrypt($id_enc);
        $tgl_kirim  = $this->input->post('tgl_kirim');
        $tgl_sampai = $this->input->post('tgl_sampai');

        // Ambil data ekspedisi untuk mendapatkan nama
        $id_ekspedisi_input = $this->input->post('id_ekspedisi', TRUE);
        $nama_ekspedisi = '';

        if (!empty($id_ekspedisi_input)) {
            $ekspedisi = $this->md_ekspedisi->getById($id_ekspedisi_input);
            if (!empty($ekspedisi)) {
                $nama_ekspedisi = $ekspedisi[0]->nama_ekspedisi;
            }
        }

        // Siapkan Data untuk tabel kirim_dokumen
        $data_update = [
            'nama_customer' => $this->input->post('nama_customer_text', TRUE),
            'pic'           => $this->input->post('pic', TRUE),
            'alamat'        => $this->input->post('alamat', TRUE),
            'id_ekspedisi'  => $id_ekspedisi_input, // ID ekspedisi
            'ekspedisi'     => $nama_ekspedisi, // Nama ekspedisi
            'no_resi'       => $this->input->post('no_resi', TRUE),
            // Update keterangan (pastikan ini ada)
            'keterangan'    => $this->input->post('keterangan', TRUE),
            // Tambahkan baris ini:
            'link_resi'     => $this->input->post('link_resi', TRUE),
        ];

        // Validasi Tanggal
        if (!empty($tgl_kirim)) {
            $data_update['tgl_kirim'] = date('Y-m-d', strtotime($tgl_kirim));
        }

        if (!empty($tgl_sampai)) {
            $data_update['tgl_sampai'] = date('Y-m-d', strtotime($tgl_sampai));
        } else {
            $data_update['tgl_sampai'] = NULL;
        }

        // Update tabel kirim_dokumen
        $this->db->where('id', $id_kirim);
        $this->db->update('kirim_dokumen', $data_update);
        $affected = $this->db->affected_rows();

        if ($affected > 0) {
            addlog('Update Dokumen', 'Admin update kirim_dokumen.id=' . $id_kirim);
            echo json_encode([
                'status' => 'success',
                'message' => 'Data dokumen berhasil diperbarui.'
            ]);
        } elseif ($affected === 0) {
            echo json_encode([
                'status' => 'warning',
                'message' => 'Data sudah sesuai, tidak ada perubahan yang disimpan.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal memperbarui database.'
            ]);
        }
    }

    /**
     * Update Full Tracking Data (Admin Only)
     */
    public function update_full()
    {
        // Pastikan session admin/akses valid
        if (!in_array(sessPenggunaId(), [1, 15, 33, 7, 749, 73, 763, 769])) {
            ajaxReturnDie('error', 'Akses Ditolak!', FALSE);
            return;
        }

        $id_tracking_raw = $this->input->post('id_tracking');
        if (!$id_tracking_raw) {
            ajaxReturnDie('error', 'ID Tracking Kosong!', FALSE);
            return;
        }

        $id_tracking = decrypt($id_tracking_raw);

        // Siapkan data update
        $data_update = [
            'pic_penerima'   => $this->input->post('pic_penerima', TRUE),
            'alamat_penerima' => $this->input->post('alamat_penerima', TRUE),
            'id_ekspedisi'   => $this->input->post('id_ekspedisi', TRUE),
            'tgl_pengiriman' => $this->input->post('tgl_pengiriman', TRUE),
            'tgl_sampai'     => $this->input->post('tgl_sampai') ?: null,
            'biaya'          => $this->input->post('biaya') ?: 0,
            'no_resi'        => $this->input->post('no_resi', TRUE),
            'link_resi'      => $this->input->post('link_resi', TRUE),
            'keterangan'     => $this->input->post('keterangan', TRUE)
        ];

        $parseDtInput = function ($val) {
            if (empty($val)) return null;
            $ts = strtotime(str_replace('T', ' ', trim($val)));
            return ($ts && $ts > 0) ? date('Y-m-d H:i:s', $ts) : null;
        };

        if ($this->input->post('waktu_po_cro') !== null) {
            $data_update['waktu_po_cro'] = $parseDtInput($this->input->post('waktu_po_cro', TRUE));
        }
        if ($this->input->post('waktu_cetak_sj') !== null) {
            $data_update['waktu_cetak_sj'] = $parseDtInput($this->input->post('waktu_cetak_sj', TRUE));
        }
        if ($this->input->post('waktu_order_ekspedisi') !== null) {
            $data_update['waktu_order_ekspedisi'] = $parseDtInput($this->input->post('waktu_order_ekspedisi', TRUE));
        }
        if ($this->input->post('waktu_packaging') !== null) {
            $data_update['waktu_packaging'] = $parseDtInput($this->input->post('waktu_packaging', TRUE));
        }
        if ($this->input->post('waktu_loading_handover') !== null) {
            $data_update['waktu_loading_handover'] = $parseDtInput($this->input->post('waktu_loading_handover', TRUE));
        }

        // Ambil nama ekspedisi
        $ekspedisi = $this->md_ekspedisi->getById($data_update['id_ekspedisi']);
        if (!empty($ekspedisi)) {
            $data_update['nama_ekspedisi'] = $ekspedisi[0]->nama_ekspedisi;
        }

        $this->db->trans_begin();

        // 1. Update Tabel Tracking
        $this->md_tracking->update(['id_tracking' => $id_tracking], $data_update);

        // 2. Jika ada status baru yang dipilih
        $new_status = $this->input->post('new_status');
        if (!empty($new_status)) {
            $status_data = [
                'id_tracking' => $id_tracking,
                'id_status'   => $new_status,
                'id_pengguna' => sessPenggunaId(),
                'keterangan_konfirmasi' => $this->input->post('new_keterangan', TRUE),
                'created_at'  => date('Y-m-d H:i:s')
            ];

            // Proteksi jika status 5 dipilih tapi field tambahan kosong
            if ($new_status == 5) {
                $status_data['nama_penerima'] = $this->input->post('nama_penerima') ?? $data_update['pic_penerima'];
                $status_data['tgl_penerima']  = $this->input->post('tgl_penerima') ?? date('Y-m-d');
                $status_data['bukti_penerima'] = $this->input->post('bukti_penerima') ?? '-';

                // Update tgl_sampai di tracking_barang jika status Diterima
                if (!empty($status_data['tgl_penerima'])) {
                    $this->md_tracking->update(['id_tracking' => $id_tracking], [
                        'tgl_sampai' => $status_data['tgl_penerima']
                    ]);
                }
            }

            $this->md_tracking->addStatus($status_data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Gagal update database!', FALSE);
        } else {
            $this->db->trans_commit();
            addlog('Edit Tracking (Admin)', 'Update data ID: ' . $id_tracking);

            // PASTIKAN TIDAK ADA ECHO LAIN SEBELUM INI
            ajaxReturnDie('success', 'Data Tracking Barang Berhasil Diperbarui!', TRUE);
        }
    }

    public function update_status_history()
    {
        if (!$this->input->is_ajax_request()) {
            echo json_encode(['status' => 'error', 'message' => 'Akses tidak valid']);
            exit;
        }

        header('Content-Type: application/json');

        // Support untuk kedua format parameter (id_history atau status_id)
        $id_history = $this->input->post('id_history', TRUE) ?: $this->input->post('status_id', TRUE);
        $id_status  = $this->input->post('status_edit', TRUE) ?: $this->input->post('id_status', TRUE);
        $tgl_status = $this->input->post('tgl_status', TRUE);
        $keterangan = $this->input->post('keterangan', TRUE);
        $doc_type   = $this->input->post('doc_type', TRUE) ?: $this->input->post('type', TRUE);

        if (empty($id_history)) {
            echo json_encode(['status' => 'error', 'message' => 'ID History tidak valid!']);
            exit;
        }

        // Tentukan tabel berdasarkan doc_type
        $is_dokumen = ($doc_type === 'dokumen' || $doc_type === 'kirim_dokumen');
        $table_status = $is_dokumen ? 'kirim_status' : 'tracking_status';
        $table_main   = $is_dokumen ? 'kirim_dokumen' : 'tracking_barang';
        $field_id     = $is_dokumen ? 'id_kirim' : 'id_tracking';

        // 1. Ambil data lama untuk pengecekan
        $existing = $this->db->get_where($table_status, ['id' => $id_history])->row();
        if (!$existing) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan di tabel ' . $table_status]);
            exit;
        }

        // 2. Siapkan data update
        $data_update = [
            'keterangan_konfirmasi' => $keterangan,
            'id_pengguna'           => sessPenggunaId(),
        ];

        // Jika id_status dikirim (untuk dokumen), tambahkan ke update
        if (!empty($id_status)) {
            $data_update['id_status'] = $id_status;
        }

        if (!empty($tgl_status)) {
            $data_update['created_at'] = date('Y-m-d H:i:s', strtotime($tgl_status));
        }

        // Jika status 5 (Diterima) untuk dokumen
        if ($is_dokumen && $id_status == "5") {
            $data_update['nama_penerima']  = $this->input->post('nama_penerima', TRUE);
            $data_update['tgl_penerima']   = $this->input->post('tgl_penerima', TRUE);
            $data_update['bukti_penerima'] = $this->input->post('bukti_penerima', TRUE);
        } elseif ($is_dokumen) {
            $data_update['nama_penerima']  = NULL;
            $data_update['tgl_penerima']   = NULL;
            $data_update['bukti_penerima'] = NULL;
        }

        $this->db->trans_start();

        // Update status history
        $this->db->where('id', $id_history);
        $this->db->update($table_status, $data_update);

        // Jika dokumen dengan status diterima, update juga tgl_sampai di tabel utama
        if ($is_dokumen && $id_status == "5" && !empty($data_update['tgl_penerima'])) {
            $this->db->where('id', $existing->$field_id);
            $this->db->update($table_main, ['tgl_sampai' => $data_update['tgl_penerima']]);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Data berhasil diperbarui!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal memperbarui database.'
            ]);
        }
    }

    /**
     * Delete Status History (Admin Only)
     */
    public function delete_status()
    {
        // Admin only
        if (!in_array(sessPenggunaId(), [1, 15, 33, 7, 749, 763, 769])) {
            ajaxReturnDie('error', 'AKSES DITOLAK! Hanya Administrator yang dapat menghapus status tracking.', FALSE);
            return;
        }

        $id_status = $this->input->post('id_status');
        $doc_type = $this->input->post('doc_type', TRUE); // kirim_dokumen atau tracking_barang

        if (empty($id_status)) {
            ajaxReturnDie('error', 'ID Status tidak valid!', FALSE);
            return;
        }

        // Tentukan table berdasarkan doc_type
        $table = ($doc_type === 'kirim_dokumen') ? 'kirim_status' : 'tracking_status';

        // Verify ID exists before deleting
        $verify = $this->db->where('id', $id_status)->from($table)->get();
        if ($verify->num_rows() == 0) {
            ajaxReturnDie('error', 'Data status tidak ditemukan di database table ' . $table . '!', FALSE);
            return;
        }

        // Delete with transaction
        $this->db->trans_begin();
        $this->db->where('id', $id_status);
        $result = $this->db->delete($table);

        if ($this->db->trans_status() === FALSE || !$result) {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Gagal menghapus data dari database table ' . $table . '!', FALSE);
            return;
        }

        $this->db->trans_commit();

        // Add log
        $aksi = 'Hapus Status ' . ($doc_type === 'kirim_dokumen' ? 'Kirim Dokumen' : 'Tracking Barang') . ' (Admin)';
        $ket = 'Admin menghapus status ID: ' . $id_status;
        addlog($aksi, $ket);

        // FIX: Set header JSON secara eksplisit
        header('Content-Type: application/json');
        $type_label = ($doc_type === 'kirim_dokumen') ? 'Kirim Dokumen' : 'Tracking Barang';
        ajaxReturnDie('success', 'Status ' . $type_label . ' berhasil dihapus oleh Admin!', TRUE);
    }

    public function delete($param)
    {
        grantAccessFor('all');


        $id_tracking    = decrypt($param);
        $data['stat'] = 0;
        $this->md_tracking->update(['id_tracking' => $id_tracking], $data);

        //add log
        $temp = $this->md_tracking->getById($id_tracking);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data Tracking Barang';
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data berhasil dihapus', 'reload_table');
    }

    public function update()
    {
        grantAccessFor('all');


        $status                        = $this->input->post('status', TRUE);
        if ($status == "5") {
            $dataDetailGc['id_tracking']     = decrypt($this->input->post('id_tracking'));
            $dataDetailGc['id_status']       = $status;
            $dataDetailGc['nama_penerima']   = $this->input->post('nama_penerima', TRUE);
            $dataDetailGc['tgl_penerima']    = date_db_format($this->input->post('tgl_penerima', TRUE));
            $dataDetailGc['bukti_penerima']  = $this->input->post('bukti_penerima', TRUE);
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
            $keTerangan = "Barang Sudah Diterima oleh " . $this->input->post('nama_penerima', TRUE);
        } else {
            $dataDetailGc['id_tracking']     = decrypt($this->input->post('id_tracking'));
            $dataDetailGc['id_status']       = $status;
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
            $dataDetailGc['keterangan_konfirmasi']  = $this->input->post('keterangan_konfirmasi', TRUE);
            $keTerangan                      = $this->input->post('keterangan_konfirmasi', TRUE);
        }

        //$this->md_tracking->updateStatus($id_tracking, $dataDetailGc);
        checkEmptyForm($dataDetailGc);
        $this->md_tracking->addStatus($dataDetailGc);

        // Auto-set waktu_order_ekspedisi / waktu_loading_handover saat status diupdate oleh Warehouse
        $ambilDataTracking     = $this->md_tracking->getById($dataDetailGc['id_tracking']);
        if (!empty($ambilDataTracking[0])) {
            $trCur = $ambilDataTracking[0];
            $whUpdate = [];
            if (empty($trCur->waktu_order_ekspedisi) && (int)$status >= 1) {
                $whUpdate['waktu_order_ekspedisi'] = date('Y-m-d H:i:s');
            }
            if (empty($trCur->waktu_packaging) && in_array((int)$status, [7, 2, 3, 4, 5], true)) {
                $whUpdate['waktu_packaging'] = date('Y-m-d H:i:s');
            }
            if (empty($trCur->waktu_loading_handover) && in_array((int)$status, [2, 3, 4, 5], true)) {
                $whUpdate['waktu_loading_handover'] = date('Y-m-d H:i:s');
            }
            if (!empty($whUpdate)) {
                $this->md_tracking->update(['id_tracking' => $dataDetailGc['id_tracking']], $whUpdate);
            }
        }
        $idCustomer            = $ambilDataTracking[0]->id_customer;
        $noSj                = $ambilDataTracking[0]->no_sj;
        $alamatTujuan        = $ambilDataTracking[0]->alamat_penerima;
        $id_gudang            = $ambilDataTracking[0]->id_gudang;

        $ambilDataGudang     = $this->md_tracking->getGudangById($id_gudang);
        $gudangAsal            = $ambilDataGudang[0]->nama_gudang;


        if ($status == 1) {
            $statusTracking = 'Proses Kirim / Order Ekspedisi';
        } else if ($status == 7) {
            $statusTracking = 'Packaging Barang';
        } else if ($status == 2) {
            $statusTracking = 'Manifest Berangkat / Loading Hand-over';
        } else if ($status == 3) {
            $statusTracking = 'Proses Sortir';
        } else if ($status == 4) {
            $statusTracking = 'Pengantaran Kurir';
        } else if ($status == 5) {
            $statusTracking = 'Diterima';
        } else if ($status == 6) {
            $statusTracking = 'Menunggu Konfirmasi';
        }


        $ambilDataCustomer     = $this->md_tracking->getCustomerById($idCustomer);
        $namacs                = isset($ambilDataCustomer[0]) ? $ambilDataCustomer[0]->nama_customer : '-';

        $idTracking         = encrypt($dataDetailGc['id_tracking']);

        // Get tracking type for notification customization
        $tracking_type = isset($ambilDataTracking[0]->tracking_type) ? $ambilDataTracking[0]->tracking_type : 'pengeluaran_barang';

        if ($tracking_type == 'pengiriman_stok') {
            // Get gudang tujuan for pengiriman_stok
            $id_pengiriman_stok = $ambilDataTracking[0]->id_pengiriman_stok;
            $dataPengirimanStok = $this->md_pengiriman_stok->getById($id_pengiriman_stok);
            $gudang_tujuan_id = isset($dataPengirimanStok[0]->id_gudang_tujuan) ? $dataPengirimanStok[0]->id_gudang_tujuan : null;
            $gudangTujuanData = $gudang_tujuan_id ? $this->md_tracking->getGudangById($gudang_tujuan_id) : null;
            $gudangTujuan = isset($gudangTujuanData[0]->nama_gudang) ? $gudangTujuanData[0]->nama_gudang : '-';

            $dataWa = [
                // TEST: Kirim ke group test dulu
                // 'idPenerima1'     => 'Test Api Wa Group',
                // 'idPenerima2'     => 'Test Api Wa Group',
                // PRODUCTION: Aktifkan baris di bawah untuk kirim ke group gudang
                'idPenerima1'     => 'MARKETING PT. VYM',
                'idPenerima2'     => 'Gudang PT. VYM',
                'namaSurat'       => 'Tracking Pengiriman Stok',
                'statusSurat'     => 'Update Status',
                'statusTracking'  => $statusTracking,
                'status'          => 'memperbarui status',
                'nosj'            => $noSj,
                'gudangAsal'      => $gudangAsal,
                'alamatTujuan'    => $gudangTujuan . ' - ' . $alamatTujuan,
                'keTerangan'      => $keTerangan,
                'idTracking'      => $idTracking,
                'csname'          => 'Gudang Tujuan: ' . $gudangTujuan
            ];
        } elseif ($tracking_type == 'serah_terima_barang') {
            // NEW: Get info for serah_terima_barang (STTB)
            $id_stb = isset($ambilDataTracking[0]->id_serah_terima_barang) ? $ambilDataTracking[0]->id_serah_terima_barang : null;
            $dataSTB = $id_stb ? $this->md_tracking->getSerahTerimaBarangById($id_stb) : null;
            $pihak1 = isset($dataSTB[0]->nama_pihak1) ? $dataSTB[0]->nama_pihak1 : '-';
            $pihak2 = isset($dataSTB[0]->nama_pihak2) ? $dataSTB[0]->nama_pihak2 : '-';

            $dataWa = [
                // TEST: Kirim ke group test dulu
                // 'idPenerima1'     => 'Test Api Wa Group',
                // 'idPenerima2'     => 'Test Api Wa Group',
                // PRODUCTION: Aktifkan baris di bawah untuk kirim ke group
                'idPenerima1'     => 'MARKETING PT. VYM',
                'idPenerima2'     => 'Gudang PT. VYM',
                'namaSurat'       => 'Tracking Serah Terima Barang',
                'statusSurat'     => 'Update Status',
                'statusTracking'  => $statusTracking,
                'status'          => 'memperbarui status',
                'nosj'            => $noSj,
                'gudangAsal'      => $pihak1,
                'alamatTujuan'    => $alamatTujuan,
                'keTerangan'      => $keTerangan,
                'idTracking'      => $idTracking,
                'csname'          => 'Pihak Penerima: ' . $pihak2
            ];
            // COMMENTED OUT: elseif untuk penerimaan_stok
            // } elseif ($tracking_type == 'penerimaan_stok') {
            //     // Get info for penerimaan_stok
            //     $id_penerimaan_stok = $ambilDataTracking[0]->id_penerimaan_stok;
            //     $dataPenerimaanStok = $this->md_penerimaan_stok->getById($id_penerimaan_stok);
            //     $id_pengiriman_stok_ref = isset($dataPenerimaanStok[0]->id_pengiriman_stok) ? $dataPenerimaanStok[0]->id_pengiriman_stok : null;
            //
            //     $dataPengirimanStokRef = $id_pengiriman_stok_ref ? $this->md_pengiriman_stok->getById($id_pengiriman_stok_ref) : null;
            //     $gudang_asal_id = isset($dataPengirimanStokRef[0]->id_gudang_asal) ? $dataPengirimanStokRef[0]->id_gudang_asal : null;
            //     $gudang_tujuan_id = isset($dataPengirimanStokRef[0]->id_gudang_tujuan) ? $dataPengirimanStokRef[0]->id_gudang_tujuan : null;
            //
            //     $gudangAsalData = $gudang_asal_id ? $this->md_tracking->getGudangById($gudang_asal_id) : null;
            //     $gudangAsalPenerimaan = isset($gudangAsalData[0]->nama_gudang) ? $gudangAsalData[0]->nama_gudang : '-';
            //
            //     $gudangTujuanData = $gudang_tujuan_id ? $this->md_tracking->getGudangById($gudang_tujuan_id) : null;
            //     $gudangTujuanPenerimaan = isset($gudangTujuanData[0]->nama_gudang) ? $gudangTujuanData[0]->nama_gudang : '-';
            //
            //     $dataWa = [
            //         // TEST: Kirim ke group test dulu
            //         'idPenerima1'     => 'Test Api Wa Group',
            //         'idPenerima2'     => 'Test Api Wa Group',
            //         // PRODUCTION: Aktifkan baris di bawah untuk kirim ke group gudang
            //         // 'idPenerima1'     => 'MARKETING PT. VYM',
            //         // 'idPenerima2'     => 'Gudang PT. VYM',
            //         'namaSurat'       => 'Tracking Penerimaan Stok',
            //         'statusSurat'     => 'Update Status',
            //         'statusTracking'  => $statusTracking,
            //         'status'          => 'memperbarui status',
            //         'nosj'            => $noSj,
            //         'gudangAsal'      => $gudangAsalPenerimaan,
            //         'alamatTujuan'    => $gudangTujuanPenerimaan . ' - ' . $alamatTujuan,
            //         'keTerangan'      => $keTerangan,
            //         'idTracking'      => $idTracking,
            //         'csname'          => 'Gudang Penerima: ' . $gudangTujuanPenerimaan
            //     ];
        } else {
            $dataWa = [
                //'idPenerima1' 	=> 'Test Api Wa Group',
                //'idPenerima2' 	=> 'Test2',
                'idPenerima1'     => 'MARKETING PT. VYM',
                'idPenerima2'     => 'Gudang PT. VYM',
                'namaSurat'       => 'Tracking Barang',
                'statusSurat'     => 'Update Status',
                'statusTracking'  => $statusTracking,
                'status'          => 'memperbarui status',
                'nosj'            => $noSj,
                'gudangAsal'      => $gudangAsal,
                'alamatTujuan'    => $alamatTujuan,
                'keTerangan'      => $keTerangan,
                'idTracking'      => $idTracking,
                'csname'          => $namacs
            ];
        }
        $this->notifWaAppGudangGroup(2, $dataWa);


        addLog('Tracking Barang', 'Update Status Tracking No :' . $noSj);
        ajaxReturnDie('success', 'Status berhasil diperbaharui', TRUE);
    }

    /**
     * Update Waktu Milestone Kecepatan Warehouse (PO CRO -> Cetak SJ -> Order Ekspedisi -> Loading / Hand-over)
     */
    public function update_warehouse_sla()
    {
        grantAccessFor('all');

        $id_tracking_enc = $this->input->post('id_tracking', TRUE);
        $id_tracking     = !empty($id_tracking_enc) ? decrypt($id_tracking_enc) : null;

        if (empty($id_tracking)) {
            ajaxReturnDie('error', 'ID Tracking tidak valid!', FALSE);
            return;
        }

        $parseDt = function ($val) {
            if (empty($val)) return null;
            $ts = strtotime(str_replace('T', ' ', trim($val)));
            return ($ts && $ts > 0) ? date('Y-m-d H:i:s', $ts) : null;
        };

        $data_update = [
            'waktu_po_cro'           => $parseDt($this->input->post('waktu_po_cro', TRUE)),
            'waktu_cetak_sj'         => $parseDt($this->input->post('waktu_cetak_sj', TRUE)),
            'waktu_order_ekspedisi'  => $parseDt($this->input->post('waktu_order_ekspedisi', TRUE)),
            'waktu_loading_handover' => $parseDt($this->input->post('waktu_loading_handover', TRUE))
        ];

        $this->md_tracking->update(['id_tracking' => $id_tracking], $data_update);

        addlog('Tracking Warehouse SLA', 'Update Waktu Kecepatan Warehouse ID Tracking: ' . $id_tracking);
        ajaxReturnDie('success', 'Waktu Tracking & Kecepatan Warehouse berhasil disimpan!', TRUE);
    }

    public function addOLD()
    {
        grantAccessFor('all');

        $data['id_gudang']             = $this->input->post('id_gudang');
        $data['id_customer']           = $this->input->post('id_customer');
        $data['nama_barang']           = $this->input->post('nama_barang');
        $data['pic_penerima']          = $this->input->post('pic_penerima');
        $data['alamat_penerima']       = $this->input->post('alamat_penerima');

        //Logic untuk Ekspedisi
        $lainnya                       = $this->input->post('id_ekspedisi', TRUE);
        if ($lainnya == "1") {
            $data['id_ekspedisi']    = $this->input->post('id_kirim', TRUE);
            $data['nama_ekspedisi']  = $this->input->post('nama_diantarkan', TRUE);
        } else if ($lainnya == "2") {
            $data['id_ekspedisi']    = $this->input->post('nama_dikirim', TRUE);
            $data['nama_ekspedisi']  = $this->input->post('nama_ekspedisi', TRUE);
        }

        $data['tgl_pengiriman']        = date_db_format($this->input->post('tgl_pengiriman', TRUE));
        $data['tgl_sampai']            = date_db_format($this->input->post('tgl_sampai', TRUE));
        $data['biaya']                 = $this->input->post('biaya');
        $data['no_sj']                 = $this->input->post('no_sj');
        $data['no_resi']               = $this->input->post('no_resi');
        $data['link_resi']               = $this->input->post('link_resi');
        $data['keterangan']            = $this->input->post('keterangan');
        //$data['status']                = $this->input->post('status');



        checkEmptyForm($data);

        $this->md_tracking->add($data);

        //Menambahkan ke Status
        $lastGcId = $this->md_tracking->getTrackLastId();
        $lastGcId = $lastGcId->id_tracking;

        $this->md_tracking->reset_increment("tracking_status");
        $dataDetailGc['id_tracking']    = $lastGcId;
        //$dataDetailGc['id_status']  	= $this->input->post('status');
        //Logic untuk Status
        $status                        = $this->input->post('status', TRUE);

        // Jika status bernilai null, set status ke 1
        if ($status == 0) {
            $status = "1";
        }

        if ($status == "5") {
            //$dataDetailGc['status']          = $this->input->post('status');
            $dataDetailGc['id_status']       = $status;
            $dataDetailGc['nama_penerima']   = $this->input->post('nama_penerima', TRUE);
            $dataDetailGc['tgl_penerima']    = date_db_format($this->input->post('tgl_penerima', TRUE));
            $dataDetailGc['bukti_penerima']  = $this->input->post('bukti_penerima', TRUE);
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
        } else if ($status == "6") {
            $dataDetailGc['id_status']       = $status;
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
            $dataDetailGc['keterangan_konfirmasi']  = $this->input->post('keterangan_konfirmasi', TRUE);
        } else {
            $dataDetailGc['id_status']       = $status;
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
        }



        $this->md_tracking->addStatus($dataDetailGc);



        if ($status == 1) {
            $statusTracking = 'Proses Kirim / Order Ekspedisi';
        } else if ($status == 7) {
            $statusTracking = 'Packaging Barang';
        } else if ($status == 2) {
            $statusTracking = 'Manifest Berangkat / Loading Hand-over';
        } else if ($status == 3) {
            $statusTracking = 'Proses Sortir';
        } else if ($status == 4) {
            $statusTracking = 'Pengantaran Kurir';
        } else if ($status == 5) {
            $statusTracking = 'Diterima';
        } else if ($status == 6) {
            $statusTracking = 'Menunggu Konfirmasi';
        }


        $ambilDataCustomer     = $this->md_tracking->getCustomerById($data['id_customer']);
        $namacs                = $ambilDataCustomer[0]->nama_customer;

        $idTracking         = encrypt($lastGcId);



        $dataWa = [
            //'idPenerima1' 	=> 'Test Api Wa Group',
            //'idPenerima2' 	=> 'Test2',
            'idPenerima1'     => 'MARKETING PT. VYM',
            'idPenerima2'     => 'Gudang PT. VYM',
            'namaSurat'          => 'Tracking Barang',
            'statusSurat'     => 'Update Status',
            'statusTracking'     => $statusTracking,
            'status'             => 'memperbarui status',
            'nosj'             => $data['no_sj'],
            'idTracking'         => $idTracking,
            'csname'             => $namacs
        ];
        $this->notifWaAppGudangGroup(2, $dataWa);

        //add log
        $aksi = 'Tambah Data Tracking Barang';
        $ket = 'Menambahkan data Tracking Barang - ' . $data['nama_barang'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }




    public function updateOLD()
    {
        grantAccessFor('all');


        $status                        = $this->input->post('status', TRUE);
        if ($status == "5") {
            $dataDetailGc['id_tracking']     = decrypt($this->input->post('id_tracking'));
            $dataDetailGc['id_status']       = $this->input->post('status');
            $dataDetailGc['nama_penerima']   = $this->input->post('nama_penerima', TRUE);
            $dataDetailGc['tgl_penerima']    = date_db_format($this->input->post('tgl_penerima', TRUE));
            $dataDetailGc['bukti_penerima']  = $this->input->post('bukti_penerima', TRUE);
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
        } else if ($status == "6") {
            $dataDetailGc['id_tracking']     = decrypt($this->input->post('id_tracking'));
            $dataDetailGc['id_status']       = $this->input->post('status');
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
            $dataDetailGc['keterangan_konfirmasi']   = $this->input->post('keterangan_konfirmasi', TRUE);
        } else {
            $dataDetailGc['id_tracking']     = decrypt($this->input->post('id_tracking'));
            $dataDetailGc['id_status']       = $this->input->post('status');
            $dataDetailGc['id_pengguna']     = sessPenggunaId();
        }

        //$this->md_tracking->updateStatus($id_tracking, $dataDetailGc);
        checkEmptyForm($dataDetailGc);
        $this->md_tracking->addStatus($dataDetailGc);

        // Auto-set waktu_order_ekspedisi / waktu_loading_handover saat status diupdate oleh Warehouse
        $ambilDataTracking     = $this->md_tracking->getById($dataDetailGc['id_tracking']);
        if (!empty($ambilDataTracking[0])) {
            $trCur = $ambilDataTracking[0];
            $whUpdate = [];
            if (empty($trCur->waktu_order_ekspedisi) && (int)$status >= 1) {
                $whUpdate['waktu_order_ekspedisi'] = date('Y-m-d H:i:s');
            }
            if (empty($trCur->waktu_packaging) && in_array((int)$status, [7, 2, 3, 4, 5], true)) {
                $whUpdate['waktu_packaging'] = date('Y-m-d H:i:s');
            }
            if (empty($trCur->waktu_loading_handover) && in_array((int)$status, [2, 3, 4, 5], true)) {
                $whUpdate['waktu_loading_handover'] = date('Y-m-d H:i:s');
            }
            if (!empty($whUpdate)) {
                $this->md_tracking->update(['id_tracking' => $dataDetailGc['id_tracking']], $whUpdate);
            }
        }
        $idCustomer            = $ambilDataTracking[0]->id_customer;
        $noSj                = $ambilDataTracking[0]->no_sj;


        if ($status == 1) {
            $statusTracking = 'Proses Kirim / Order Ekspedisi';
        } else if ($status == 7) {
            $statusTracking = 'Packaging Barang';
        } else if ($status == 2) {
            $statusTracking = 'Manifest Berangkat / Loading Hand-over';
        } else if ($status == 3) {
            $statusTracking = 'Proses Sortir';
        } else if ($status == 4) {
            $statusTracking = 'Pengantaran Kurir';
        } else if ($status == 5) {
            $statusTracking = 'Diterima';
        } else if ($status == 6) {
            $statusTracking = 'Menunggu Konfirmasi';
        }


        $ambilDataCustomer     = $this->md_tracking->getCustomerById($idCustomer);
        $namacs                = $ambilDataCustomer[0]->nama_customer;

        $idTracking         = encrypt($dataDetailGc['id_tracking']);



        $dataWa = [
            //'idPenerima1' 	=> 'Test Api Wa Group',
            //'idPenerima2' 	=> 'Test2',
            'idPenerima1'     => 'MARKETING PT. VYM',
            'idPenerima2'     => 'Gudang PT. VYM',
            'namaSurat'          => 'Tracking Barang',
            'statusSurat'     => 'Update Status',
            'statusTracking'     => $statusTracking,
            'status'             => 'memperbarui status',
            'nosj'             => $noSj,
            'idTracking'         => $idTracking,
            'csname'             => $namacs
        ];
        $this->notifWaAppGudangGroup(2, $dataWa);


        addLog('Memperbaharui Status', 'Memperbaharui Status Tracking');
        ajaxReturnDie('success', 'Status berhasil diperbaharui', TRUE);
    }

    public function pagination()
    {
        grantAccessFor('all');

        // Get tracking_mode from POST request
        $tracking_mode = $this->input->post('tracking_mode'); // 'barang' or 'dokumen'

        // Jika tracking_mode = 'dokumen', hanya ambil kirim_dokumen
        // Jika tracking_mode = 'barang', exclude kirim_dokumen
        if ($tracking_mode === 'dokumen') {
            $_POST['filter_type'] = 'kirim_dokumen';
        } elseif ($tracking_mode === 'barang') {
            // Will exclude kirim_dokumen in model query (handled in model)
            $_POST['exclude_kirim_dokumen'] = true;
            $searchArr = $this->input->post('search', TRUE);
            $searchVal = isset($searchArr['value']) ? trim((string)$searchArr['value']) : '';
            if ((int)$this->input->post('start') === 0 && $searchVal === '') {
                $this->md_tracking->autoBackfillUntrackedDocuments();
            }
        }

        $dt = $this->md_tracking->getAllTrackingNew();

        $start = $this->input->post('start');
        $data  = array();

        foreach ($dt['data'] as $row) {
            $id = encrypt($row->id_tracking);

            // --- Logic Tombol Edit/Hapus ---
            if (sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId() == 73 || sessPenggunaId() == 763 || sessPenggunaId() == 769) {
                $li_btn   = '
            <div class="btn-group" role="group" aria-label="First group">
                <a href="tracking/detail/' . $id . '" class="btn btn-sm btn-primary" title="Lihat Detail">
                    <i class="bx bx-show"></i>
                </a>
                <a href="tracking/edit_full/' . $id . '" class="btn btn-sm btn-warning" title="Edit Full (Admin)">
                    <i class="bx bx-edit"></i>
                </a>
                <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="tracking/delete"><i class="bx bx-trash"></i></button>
            </div>';
            } else {
                $li_btn   = '
            <div class="btn-group" role="group" aria-label="First group">
                <a href="tracking/detail/' . $id . '" class="btn btn-sm btn-primary btn-edit" title="Lihat Detail">
                    <i class="bx bx-show"></i>
                </a>
            </div>';
            }

            // --- Logic Status Badge ---
            $stat = '<span class="badge badge-secondary">Unknown</span>'; // Default
            if ($row->id_status === "0" || $row->id_status === 0 || $row->id_status === null || $row->id_status === "") {
                $stat = '<span class="badge badge-ecommerce badge-secondary">Menunggu Order Eks</span>';
            } else if ($row->id_status == "1") {
                $stat = '<span class="badge badge-ecommerce badge-info">Proses Kirim / Order Eks</span>';
            } else if ($row->id_status == "7") {
                $stat = '<span class="badge badge-ecommerce badge-primary">Packaging Barang</span>';
            } else if ($row->id_status == "2") {
                $stat = '<span class="badge badge-ecommerce badge-success">Manifest Berangkat / Hand-over</span>';
            } else if ($row->id_status == "6") {
                $stat = '<span class="badge badge-ecommerce badge-warning">Menunggu Konfirmasi</span>';
            } else if ($row->id_status == "3") {
                $stat = '<span class="badge badge-ecommerce badge-success">Proses Sortir</span>';
            } else if ($row->id_status == "4") {
                $stat = '<span class="badge badge-ecommerce badge-success">Pengantaran Kurir</span>';
            } else if ($row->id_status == "5") {
                $stat = '<span class="badge badge-ecommerce badge-success">Diterima</span>';
            }

            // --- Logic Barang ---
            if ($row->id_tracking <= 371) {
                $detail_barang = $row->nama_barang;
            } else {
                $detail_barang = $row->barang_list;
            }

            $SJ = '<a href="tracking/detail/' . $id . '">' . $row->no_sj . '</a>';

            // --- Logic untuk nama penerima (customer atau gudang tujuan) ---
            $tracking_type = isset($row->tracking_type) ? $row->tracking_type : 'pengeluaran_barang';
            if ($tracking_type == 'pengiriman_stok') {
                $nama_penerima = isset($row->gudang_tujuan) ? $row->gudang_tujuan : '-';
                $type_badge = '<span class="badge badge-warning badge-sm">Transfer Stok</span>';
            } elseif ($tracking_type == 'penerimaan_stok') {
                $nama_penerima = isset($row->gudang_asal_penerimaan) ? $row->gudang_asal_penerimaan : '-';
                $type_badge = '<span class="badge badge-secondary badge-sm">Penerimaan Stok</span>';
            } elseif ($tracking_type == 'serah_terima_barang') {
                // Untuk STTB, penerima adalah pihak kedua
                $nama_penerima = isset($row->nama_pihak2_stb) ? $row->nama_pihak2_stb : $row->nama_customer;
                $type_badge = '<span class="badge badge-info badge-sm">STTB</span>';
            } elseif ($tracking_type == 'kirim_dokumen') {
                // Untuk kirim dokumen
                $nama_penerima = isset($row->nama_customer_kirim) ? $row->nama_customer_kirim : '-';
                $type_badge = '<span class="badge badge-primary badge-sm">Dokumen</span>';
            } else {
                // Default: pengeluaran_barang
                $nama_penerima = $row->nama_customer;
                $type_badge = '<span class="badge badge-primary badge-sm">Pengiriman</span>';
            }

            // Tombol 1-Klik Lanjut Tahap Berikutnya
            $nextStage = $this->md_tracking->getNextStageInfo($row->id_status ?? 0);
            if ($nextStage) {
                $nextBtnClass = ((int)$nextStage['next_status'] === 5) ? 'btn-success' : 'btn-outline-primary';
                $stat .= '<div class="mt-1">'
                    . '<button type="button" class="btn btn-xs ' . $nextBtnClass . ' font-weight-bold shadow-sm btn-quick-next-track" '
                    . 'style="font-size:10.5px; padding:2px 7px; border-radius:6px;" '
                    . 'data-id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '" '
                    . 'data-nosj="' . htmlspecialchars($row->no_sj ?? '-', ENT_QUOTES, 'UTF-8') . '" '
                    . 'data-cur="' . (int)$row->id_status . '" '
                    . 'data-next="' . (int)$nextStage['next_status'] . '" '
                    . 'data-next-label="' . htmlspecialchars($nextStage['label'], ENT_QUOTES, 'UTF-8') . '" '
                    . 'data-penerima="' . htmlspecialchars($nama_penerima ?? '-', ENT_QUOTES, 'UTF-8') . '" '
                    . 'data-resi="' . htmlspecialchars($row->no_resi ?? '-', ENT_QUOTES, 'UTF-8') . '" '
                    . 'title="1-Klik Majukan ke Tahap: ' . htmlspecialchars($nextStage['label'], ENT_QUOTES, 'UTF-8') . '">'
                    . '<i class="fas ' . $nextStage['icon'] . ' mr-1"></i>Lanjut: ' . htmlspecialchars($nextStage['btn_label'], ENT_QUOTES, 'UTF-8')
                    . '</button></div>';
            }

            // --- Kalkulasi Kecepatan Warehouse (PO CRO -> Cetak SJ -> Order Ekspedisi -> Packaging -> Loading/Hand-over) ---
            $sla = $this->md_tracking->resolveWarehouseMilestoneTimes($row);
            $poInfoHtml = '';
            if (!empty($sla['no_po'])) {
                $poTimeStr = !empty($sla['waktu_po_cro']) ? date('d/m/y H:i', strtotime($sla['waktu_po_cro'])) : 'Manual';
                $poInfoHtml = '<div class="mt-1"><span class="badge badge-light border text-dark" style="font-size:10.5px;"><i class="fas fa-file-signature text-primary mr-1"></i>PO: ' . htmlspecialchars($sla['no_po']) . ' <span class="text-muted">(' . $poTimeStr . ')</span></span></div>';
            }

            $makeSlaBadge = function ($label, $dur) {
                if (!empty($dur['valid'])) {
                    return '<div class="d-flex align-items-center justify-content-between px-2 py-1 rounded mb-1" style="background:' . $dur['badge_bg'] . '; color:' . $dur['badge_text'] . '; border:1px solid ' . $dur['badge_bdr'] . '; font-size:10.5px; line-height:1.25;">'
                        . '<span class="font-weight-bold mr-2">' . $label . ':</span>'
                        . '<span class="font-weight-bold text-nowrap"><i class="fas ' . $dur['icon'] . ' mr-1"></i>' . htmlspecialchars($dur['text']) . ' (' . $dur['label_tier'] . ')</span>'
                        . '</div>';
                }
                return '<div class="d-flex align-items-center justify-content-between px-2 py-1 rounded mb-1" style="background:#f8fafc; color:#94a3b8; border:1px dashed #cbd5e1; font-size:10.5px; line-height:1.25;">'
                    . '<span>' . $label . ':</span>'
                    . '<span>Menunggu</span>'
                    . '</div>';
            };

            $sopInfo = $sla['sop'];
            $sopBadgeHtml = '<div class="d-flex align-items-center justify-content-between px-2 py-1 rounded mt-1" style="background:' . $sopInfo['badge_bg'] . '; color:' . $sopInfo['badge_text'] . '; border:1px solid ' . $sopInfo['badge_bdr'] . '; font-size:10.5px; line-height:1.25;">'
                . '<span class="font-weight-bold mr-2"><i class="fas fa-clipboard-check mr-1"></i>SOP Gudang:</span>'
                . '<span class="font-weight-bold text-nowrap">' . htmlspecialchars($sopInfo['label']) . '</span>'
                . '</div>';

            $kecepatanHtml = '<div class="text-left" style="min-width: 215px;">'
                . $makeSlaBadge('1. SJ ➔ Order Eks', $sla['dur_sj_to_order'])
                . $makeSlaBadge('2. Order ➔ Pack', $sla['dur_order_to_pack'])
                . $makeSlaBadge('3. Pack ➔ Loading', $sla['dur_pack_to_load'])
                . $makeSlaBadge('Total SJ ➔ Load', $sla['dur_sj_to_load'])
                . $sopBadgeHtml
                . '</div>';

            $ktp = $sla['ketepatan'];
            $estDateStr = (!empty($row->tgl_sampai) && strpos($row->tgl_sampai, '0000') === false) ? date('d-M-Y', strtotime($row->tgl_sampai)) : '-';
            $actDateHtml = !empty($ktp['tgl_diterima']) ? '<div class="small text-muted mt-1">Tiba: <b>' . date('d-M-Y', strtotime($ktp['tgl_diterima'])) . '</b></div>' : '';
            $ketepatanBadge = '<div class="mt-1"><span class="badge px-2 py-1" style="background:' . $ktp['badge_bg'] . '; color:' . $ktp['badge_text'] . '; border:1px solid ' . $ktp['badge_bdr'] . '; font-size:10.5px;"><i class="fas ' . $ktp['icon'] . ' mr-1"></i>' . htmlspecialchars($ktp['label']) . '</span></div>';
            $estimasiKetepatanHtml = '<div><strong>Est: ' . $estDateStr . '</strong>' . $actDateHtml . $ketepatanBadge . '</div>';

            $th = array();
            $th[] = ++$start . '.';                               // Index 0
            $th[] = $SJ . ' ' . $type_badge . $poInfoHtml;        // Index 1
            $th[] = $row->nama_gudang;                            // Index 2
            $th[] = $nama_penerima;                               // Index 3
            $th[] = $detail_barang;                               // Index 4
            $th[] = $row->alamat_penerima;                        // Index 5
            $th[] = $row->nama_ekspedisi;                         // Index 6
            $th[] = $kecepatanHtml;                               // Index 7 - Kecepatan Warehouse & SOP
            $th[] = $estimasiKetepatanHtml;                       // Index 8 - Estimasi & Ketepatan Pengiriman
            $th[] = $stat;                                        // Index 9
            $th[] = $li_btn;                                      // Index 10 (Kolom Aksi)

            // --- DATA HIDDEN ---
            $th[] = $row->id_status;                              // Index 11
            $th[] = $id;                                          // Index 12

            // PERBAIKAN: Gunakan isset agar tidak error jika property tidak ada
            $th[] = isset($row->id_tagihan) ? $row->id_tagihan : null; // Index 13

            $th[] = isset($row->status_approval) ? $row->status_approval : -1; // Index 14

            $th[] = $tracking_type;                               // Index 15 - tracking type

            $th[] = isset($row->no_resi) ? $row->no_resi : '-';  // Index 16 - no_resi

            $data[] = $th;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    /**
     * Endpoint 1-Klik Majukan Tahap Status Tracking Barang
     */
    public function quick_next_status()
    {
        grantAccessFor('all');

        $id_enc        = $this->input->post('id_tracking', TRUE);
        $id_tracking   = !empty($id_enc) ? (int)decrypt($id_enc) : 0;
        $target_status = (int)$this->input->post('target_status', TRUE);

        if ($id_tracking <= 0 || !in_array($target_status, [1, 7, 2, 3, 4, 5, 6], true)) {
            ajaxReturnDie('error', 'Parameter tracking atau status tidak valid.');
        }

        $trRows = $this->md_tracking->getById($id_tracking);
        if (empty($trRows)) {
            ajaxReturnDie('error', 'Data tracking tidak ditemukan.');
        }
        $tr = $trRows[0];

        $no_resi        = trim((string)$this->input->post('no_resi', TRUE));
        $tgl_sampai     = trim((string)$this->input->post('tgl_sampai', TRUE));
        $nama_penerima  = trim((string)$this->input->post('nama_penerima', TRUE));
        $tgl_penerima   = trim((string)$this->input->post('tgl_penerima', TRUE));
        $bukti_penerima = trim((string)$this->input->post('bukti_penerima', TRUE));
        $keterangan     = trim((string)$this->input->post('keterangan', TRUE));

        $statusLabels = [
            1 => 'Proses Kirim / Order Ekspedisi',
            7 => 'Packaging Barang',
            2 => 'Manifest Berangkat / Loading Hand-over',
            3 => 'Proses Sortir',
            6 => 'Menunggu Konfirmasi',
            4 => 'Pengantaran Kurir',
            5 => 'Diterima',
        ];
        $statusLabel = $statusLabels[$target_status] ?? 'Tahap Berikutnya';

        $opts = [
            'force'          => true,
            'keterangan'     => $keterangan !== '' ? $keterangan : ('Diperbarui ke tahap ' . $statusLabel),
            'no_resi'        => $no_resi,
            'nama_penerima'  => $nama_penerima !== '' ? $nama_penerima : ($tr->pic_penerima ?: 'Penerima'),
            'tgl_penerima'   => $tgl_penerima !== '' ? date('Y-m-d', strtotime($tgl_penerima)) : date('Y-m-d'),
            'bukti_penerima' => $bukti_penerima !== '' ? $bukti_penerima : ($tr->link_resi ?: '-'),
        ];
        if ($tgl_sampai !== '' && strtotime($tgl_sampai) > 0) {
            $opts['tgl_sampai']           = date('Y-m-d', strtotime($tgl_sampai));
            $opts['overwrite_tgl_sampai'] = true;
        }

        $ok = $this->md_tracking->advanceTrackingStage($id_tracking, $target_status, $opts);
        if (!$ok) {
            ajaxReturnDie('error', 'Gagal memperbarui tahapan status tracking.');
        }

        // Sinkron balik ke pengeluaran_barang jika ada perubahan resi atau status diterima
        if (!empty($tr->id_pengeluaran_barang) && $this->db->table_exists('pengeluaran_barang')) {
            $pbUpd = [];
            if ($no_resi !== '' && $no_resi !== '-') {
                $pbUpd['resi'] = $no_resi;
            }
            if ($target_status === 5) {
                $pbUpd['status_pengiriman'] = 'Diterima';
            }
            if (!empty($pbUpd)) {
                $this->db->where('id_pengeluaran_barang', (int)$tr->id_pengeluaran_barang)->update('pengeluaran_barang', $pbUpd);
            }
        }

        // Sinkron balik no_resi ke pengiriman_stok jika TTB
        if (!empty($tr->id_pengiriman_stok) && $no_resi !== '' && $no_resi !== '-' && $this->db->table_exists('pengiriman_stok')) {
            $this->db->where('id_pengiriman_stok', (int)$tr->id_pengiriman_stok)->update('pengiriman_stok', ['no_resi' => $no_resi]);
        }

        addlog('Quick Next Status Tracking', 'Memajukan status tracking ' . ($tr->no_sj ?? ('#' . $id_tracking)) . ' ke ' . $statusLabel);
        ajaxReturnDie('success', 'Status ' . ($tr->no_sj ?? '') . ' berhasil dimajukan ke: ' . $statusLabel, 'reload_table');
    }

    public function notifWaAppGudangGroup($ulang, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju     = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju            = $ambilDataPengaju[0]->nama;



        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                $idpenerima = $detail['idPenerima1'];
                $penerima   = '_Team Marketing_';
            } else if ($i == 2) {
                $idpenerima = $detail['idPenerima2'];
                $penerima   = '_Team Warehouse_';
            }


            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);
            //

            $url = 'https://office.visiyosindo.id/tracking/detail/';

            $dataWa = [
                'namaSurat'     => urlencode($detail['namaSurat']),
                'noPenerima'     => $idpenerima,
                'namaPengaju'   => $namaPengaju,
                'csname'         => urlencode($detail['csname']),
                'nosj'             => $detail['nosj'],
                'gudangAsal'     => urlencode($detail['gudangAsal']),
                'alamatTujuan'     => urlencode($detail['alamatTujuan']),
                'statusTracking' => $detail['statusTracking'],
                'statusSurat'   => $detail['statusSurat'],
                'status'         => $detail['status'],
                'keTerangan'     => urlencode($detail['keTerangan']),
                'url'             => $url,
                'idTracking'     => $detail['idTracking'],
                'namaPenerima'    => $penerima
            ];

            waAppGroupGudang($dataWa);
        }
    }



    public function exportlaporan()
    {

        $data = $this->md_tracking->getTrackingByTgl($this->input->get('tglawal'), $this->input->get('tglakhir'));



        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];


        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];

        $sheet->setCellValue('A1', "Rekap Data Tracking Barang"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:U1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Gudang Pengiriman');
        $sheet->setCellValue('C4', 'Nama Customer');
        $sheet->setCellValue('D4', 'Nama Barang');
        $sheet->setCellValue('E4', 'PIC Penerima');
        $sheet->setCellValue('F4', 'Alamat Penerima');
        $sheet->setCellValue('G4', 'Ekspedisi');
        $sheet->setCellValue('H4', 'Tanggal Pengiriman');
        $sheet->setCellValue('I4', 'Estimasi Penerimaan');
        $sheet->setCellValue('J4', 'Biaya Ekspedisi');
        $sheet->setCellValue('K4', 'No Surat Jalan');
        $sheet->setCellValue('L4', 'No Resi');
        $sheet->setCellValue('M4', 'Keterangan Lainnya');
        $sheet->setCellValue('N4', 'Status Barang');
        $sheet->setCellValue('O4', 'Nama Penerima');
        $sheet->setCellValue('P4', 'Tanggal Penerimaan');
        $sheet->setCellValue('Q4', 'Bukti Penerimaan');
        $sheet->setCellValue('R4', 'Created at');
        $sheet->setCellValue('S4', 'No PO');
        $sheet->setCellValue('T4', 'Waktu PO Diterima CRO');
        $sheet->setCellValue('U4', 'Waktu Cetak SJ');
        $sheet->setCellValue('V4', 'Waktu Order Ekspedisi');
        $sheet->setCellValue('W4', 'Waktu Packaging');
        $sheet->setCellValue('X4', 'Waktu Loading / Hand-over');
        $sheet->setCellValue('Y4', 'Kecepatan Cetak SJ -> Order Ekspedisi');
        $sheet->setCellValue('Z4', 'Kecepatan Order Ekspedisi -> Packaging');
        $sheet->setCellValue('AA4', 'Kecepatan Packaging -> Loading/Hand-over');
        $sheet->setCellValue('AB4', 'Total Kecepatan Cetak SJ -> Loading/Hand-over');
        $sheet->setCellValue('AC4', 'Ketepatan Pengiriman');
        $sheet->setCellValue('AD4', 'Kepatuhan SOP Warehouse');

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $headerCols = array_merge(range('A', 'Z'), ['AA', 'AB', 'AC', 'AD']);
        foreach ($headerCols as $colLtr) {
            $sheet->getStyle($colLtr . '4')->applyFromArray($style_col);
        }


        $kolom = 5;
        $nomor = 1;

        foreach ($data as $marketing) {

            if ($marketing->id_status === '0' || $marketing->id_status === 0 || $marketing->id_status === null || $marketing->id_status === '') {
                $status = "Menunggu Order Ekspedisi";
            } else if ($marketing->id_status == 1) {
                $status = "Proses Kirim / Order Ekspedisi";
            } else if ($marketing->id_status == 7) {
                $status = "Packaging Barang";
            } else if ($marketing->id_status == 2) {
                $status = "Manifest Berangkat / Loading Hand-over";
            } else if ($marketing->id_status == 3) {
                $status = "Proses Sortir";
            } else if ($marketing->id_status == 4) {
                $status = "Pengantaran Kurir";
            } else if ($marketing->id_status == 5) {
                $status = "Diterima";
            } else if ($marketing->id_status == 6) {
                $status = "Menunggu Konfirmasi";
            } else {
                $status = "-";
            }

            if ($marketing->tgl_penerima != '') {
                $tglPenerima = date('j F Y', strtotime($marketing->tgl_penerima));
            } else {
                $tglPenerima = "";
            }

            $tglKirimStr = (!empty($marketing->tgl_pengiriman) && strpos((string)$marketing->tgl_pengiriman, '0000') === false && strtotime($marketing->tgl_pengiriman) > 0)
                ? date('j F Y', strtotime($marketing->tgl_pengiriman))
                : '-';
            $tglSampaiStr = (!empty($marketing->tgl_sampai) && strpos((string)$marketing->tgl_sampai, '0000') === false && strtotime($marketing->tgl_sampai) > 0)
                ? date('j F Y', strtotime($marketing->tgl_sampai))
                : '-';

            $sla = $this->md_tracking->resolveWarehouseMilestoneTimes($marketing);
            $durSjOrderStr = !empty($sla['dur_sj_to_order']['valid']) ? $sla['dur_sj_to_order']['text'] . ' (' . $sla['dur_sj_to_order']['label_tier'] . ')' : '-';
            $durOrderPackStr = !empty($sla['dur_order_to_pack']['valid']) ? $sla['dur_order_to_pack']['text'] . ' (' . $sla['dur_order_to_pack']['label_tier'] . ')' : '-';
            $durPackLoadStr = !empty($sla['dur_pack_to_load']['valid']) ? $sla['dur_pack_to_load']['text'] . ' (' . $sla['dur_pack_to_load']['label_tier'] . ')' : '-';
            $durSjLoadStr = !empty($sla['dur_sj_to_load']['valid']) ? $sla['dur_sj_to_load']['text'] . ' (' . $sla['dur_sj_to_load']['label_tier'] . ')' : '-';

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $marketing->nama_gudang)
                ->setCellValue('C' . $kolom, $marketing->nama_customer)
                ->setCellValue('D' . $kolom, $marketing->nama_barang)
                ->setCellValue('E' . $kolom, $marketing->pic_penerima)
                ->setCellValue('F' . $kolom, $marketing->alamat_penerima)
                ->setCellValue('G' . $kolom, $marketing->nama_ekspedisi)
                ->setCellValue('H' . $kolom, $tglKirimStr)
                ->setCellValue('I' . $kolom, $tglSampaiStr)
                ->setCellValue('J' . $kolom, $marketing->biaya)
                ->setCellValue('K' . $kolom, $marketing->no_sj)
                ->setCellValue('L' . $kolom, $marketing->no_resi)
                ->setCellValue('M' . $kolom, $marketing->keterangan)
                ->setCellValue('N' . $kolom, $status)
                ->setCellValue('O' . $kolom, $marketing->nama_penerima)
                ->setCellValue('P' . $kolom, $tglPenerima)
                ->setCellValue('Q' . $kolom, $marketing->bukti_penerima)
                ->setCellValue('R' . $kolom, $marketing->createdAt)
                ->setCellValue('S' . $kolom, $sla['no_po'] ?: '-')
                ->setCellValue('T' . $kolom, $sla['waktu_po_cro'] ?: '-')
                ->setCellValue('U' . $kolom, $sla['waktu_cetak_sj'] ?: '-')
                ->setCellValue('V' . $kolom, $sla['waktu_order_eks'] ?: '-')
                ->setCellValue('W' . $kolom, $sla['waktu_packaging'] ?: '-')
                ->setCellValue('X' . $kolom, $sla['waktu_loading'] ?: '-')
                ->setCellValue('Y' . $kolom, $durSjOrderStr)
                ->setCellValue('Z' . $kolom, $durOrderPackStr)
                ->setCellValue('AA' . $kolom, $durPackLoadStr)
                ->setCellValue('AB' . $kolom, $durSjLoadStr)
                ->setCellValue('AC' . $kolom, $sla['ketepatan']['label'] ?? '-')
                ->setCellValue('AD' . $kolom, $sla['sop']['label'] ?? '-');





            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle('J' . $kolom)->getNumberFormat()->setFormatCode('Rp #,##0');

            $kolom++;
            $nomor++;
        }

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(18); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(35); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(35); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(55); // Set width kolom E
        $sheet->getColumnDimension('F')->setWidth(55); // Set width kolom F
        $sheet->getColumnDimension('G')->setWidth(40); // Set width kolom G
        $sheet->getColumnDimension('H')->setWidth(40); // Set width kolom H
        $sheet->getColumnDimension('I')->setWidth(55); // Set width kolom I
        $sheet->getColumnDimension('J')->setWidth(65); // Set width kolom J
        $sheet->getColumnDimension('K')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('L')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('M')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('N')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('O')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('P')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('Q')->setWidth(65); // Set width kolom k
        $sheet->getColumnDimension('R')->setWidth(65); // Set width kolom k

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Data Tracking Barang");
        ob_end_clean();
        // Proses file excel
        $filename = "Data Tracking Barang.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    /**
     * Halaman Import Tracking Dokumen
     */
    public function import_dokumen_page()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['list_eks'] = $this->md_tracking->getAllEkspedisi();
        $page_data['page_name'] = 'tracking/v_import_dokumen';
        $page_data['page_title'] = 'Import Tracking Dokumen';
        $page_data['page_desc'] = 'Import Data Tracking Dokumen dari Excel';

        $this->load->view('index', $page_data);
    }

    /**
     * Download Template Excel untuk Import Tracking Dokumen
     */
    public function download_template_dokumen()
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Style header kolom
        $style_col = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];

        // Set title
        $sheet->setCellValue('A1', "TEMPLATE IMPORT TRACKING DOKUMEN");
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set header columns
        $sheet->setCellValue('A3', "NO");
        $sheet->setCellValue('B3', "NO. RESI");
        $sheet->setCellValue('C3', "TGL RESI");
        $sheet->setCellValue('D3', "PENERIMA");
        $sheet->setCellValue('E3', "No Surat Jalan");
        $sheet->setCellValue('F3', "Desc Product");
        $sheet->setCellValue('G3', "TGL DI TERIMA");

        // Apply style to header
        $sheet->getStyle('A3:G3')->applyFromArray($style_col);

        // Set column width
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(15);

        // Add sample data row 4
        $sheet->setCellValue('A4', "1");
        $sheet->setCellValue('B4', "600019099153");
        $sheet->setCellValue('C4', "01/10/2025");
        $sheet->setCellValue('D4', "Rsud Rokan Hulu");
        $sheet->setCellValue('E4', "tidak ada");
        $sheet->setCellValue('F4', "Dokumen");
        $sheet->setCellValue('G4', "10/07/2026");

        // Add sample data row 5
        $sheet->setCellValue('A5', "2");
        $sheet->setCellValue('B5', "600019139482");
        $sheet->setCellValue('C5', "10/03/2025");
        $sheet->setCellValue('D5', "Pt Rajawali Nusindo");
        $sheet->setCellValue('E5', "tidak ada");
        $sheet->setCellValue('F5', "Dokumen");
        $sheet->setCellValue('G5', "10/04/2026");

        // Add sample data row 6
        $sheet->setCellValue('A6', "3");
        $sheet->setCellValue('B6', "600019215658");
        $sheet->setCellValue('C6', "10/07/2025");
        $sheet->setCellValue('D6', "Rsud bengkalis");
        $sheet->setCellValue('E6', "tidak ada");
        $sheet->setCellValue('F6', "Dokumen");
        $sheet->setCellValue('G6', "10/10/2025");

        // Add note
        $sheet->setCellValue('A26', "CATATAN:");
        $sheet->setCellValue('A27', "- TGL RESI Format: YYYY-MM-DD (contoh: 2025-09-01)");
        $sheet->setCellValue('A28', "- PENERIMA: Nama customer (akan dicari di database untuk auto-fill alamat & PIC)");
        $sheet->setCellValue('A29', "- No Surat Jalan: Opsional, bisa dikosongkan");
        $sheet->setCellValue('A30', "- Desc Product: Keterangan dokumen");
        $sheet->setCellValue('A31', "- TGL DI TERIMA: Opsional, format YYYY-MM-DD atau DD/MM/YYYY (bisa dikosongkan)");
        $sheet->setCellValue('A32', "- Kode dokumen akan auto-generate melanjutkan nomor terakhir di database");
        $sheet->getStyle('A26')->getFont()->setBold(true);

        // Set filename and download
        ob_end_clean();
        $filename = "Template_Import_Tracking_Dokumen.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    /**
     * Import Tracking Dokumen dari Excel
     */
    public function import_dokumen()
    {
        grantAccessFor('all');

        $file_mimes = array(
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        if (!isset($_FILES['berkas_excel']['name']) || !in_array($_FILES['berkas_excel']['type'], $file_mimes)) {
            ajaxReturnDie('error', 'File tidak valid. Upload file Excel (.xlsx, .xls, atau .csv)', FALSE);
            return;
        }

        // Validasi input form
        $id_ekspedisi = $this->input->post('id_ekspedisi');
        $nama_ekspedisi = $this->input->post('nama_ekspedisi');
        $periode = $this->input->post('periode');
        $status_default = $this->input->post('status_default');

        if (empty($id_ekspedisi) || empty($periode)) {
            ajaxReturnDie('error', 'Data ekspedisi dan periode harus diisi', FALSE);
            return;
        }

        if ($status_default === '' || $status_default === null) {
            ajaxReturnDie('error', 'Status default harus dipilih', FALSE);
            return;
        }

        try {
            // Load Excel file
            $arr_file = explode('.', $_FILES['berkas_excel']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['berkas_excel']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            // Validasi minimal ada data
            if (count($sheetData) < 4) {
                ajaxReturnDie('error', 'File Excel kosong atau format tidak sesuai', FALSE);
                return;
            }

            $success_count = 0;
            $error_count = 0;
            $errors = [];
            $first_kode = '';
            $last_kode = '';

            // Generate kode kirim_dokumen - setup roman numerals
            $month_to_roman = [
                '01' => 'I',
                '02' => 'II',
                '03' => 'III',
                '04' => 'IV',
                '05' => 'V',
                '06' => 'VI',
                '07' => 'VII',
                '08' => 'VIII',
                '09' => 'IX',
                '10' => 'X',
                '11' => 'XI',
                '12' => 'XII'
            ];

            // Get nomor terakhir dari database SEKALI di awal
            $lastCode = $this->db->select('kode')
                ->from('kirim_dokumen')
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get()
                ->row();

            // Initial counter untuk nomor dokumen
            if ($lastCode) {
                $parts = explode('/', $lastCode->kode);
                $current_num = intval($parts[0]);
            } else {
                $current_num = 0;
            }

            // Get ID terakhir dari database untuk auto-increment manual
            $lastId = $this->db->select('id')
                ->from('kirim_dokumen')
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get()
                ->row();

            // Initial counter untuk ID kirim_dokumen
            if ($lastId && $lastId->id > 0) {
                $current_id = intval($lastId->id);
            } else {
                $current_id = 0;
            }

            // Get ID terakhir dari kirim_status untuk auto-increment manual
            $lastStatusId = $this->db->select('id')
                ->from('kirim_status')
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get()
                ->row();

            // Initial counter untuk ID kirim_status
            if ($lastStatusId && $lastStatusId->id > 0) {
                $current_status_id = intval($lastStatusId->id);
            } else {
                $current_status_id = 0;
            }

            // Mulai transaksi database untuk memastikan data konsisten
            $this->db->trans_start();

            // Mulai dari baris 4 (skip header dan sample)
            // Adjust sesuai format: A=NO, B=NO_RESI, C=TGL_RESI, D=PENERIMA, E=NO_SJ, F=DESC, G=TGL_TERIMA
            for ($i = 3; $i < count($sheetData); $i++) {
                $row_num = $i + 1;

                // Skip jika baris kosong
                $no_resi = isset($sheetData[$i][1]) ? trim($sheetData[$i][1]) : '';
                if (empty($no_resi)) {
                    continue;
                }

                try {
                    $tgl_resi = isset($sheetData[$i][2]) ? $sheetData[$i][2] : '';
                    $penerima = isset($sheetData[$i][3]) ? trim($sheetData[$i][3]) : '';
                    $no_sj = isset($sheetData[$i][4]) ? trim($sheetData[$i][4]) : '';
                    $desc_product = isset($sheetData[$i][5]) ? trim($sheetData[$i][5]) : '';
                    $tgl_terima = isset($sheetData[$i][6]) ? $sheetData[$i][6] : '';

                    // Validasi
                    if (empty($penerima)) {
                        $errors[] = "Baris $row_num: PENERIMA tidak boleh kosong";
                        $error_count++;
                        continue;
                    }

                    // Parse tanggal kirim
                    if (is_numeric($tgl_resi)) {
                        // Excel date format (number)
                        $tgl_kirim = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl_resi)->format('Y-m-d');
                    } else if (!empty($tgl_resi)) {
                        // String date format (support DD/MM/YYYY or YYYY-MM-DD)
                        if (strpos($tgl_resi, '/') !== false) {
                            // Format DD/MM/YYYY
                            $parts = explode('/', $tgl_resi);
                            if (count($parts) == 3) {
                                $tgl_kirim = $parts[2] . '-' . str_pad($parts[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                            } else {
                                $tgl_kirim = date('Y-m-d', strtotime($tgl_resi));
                            }
                        } else {
                            $tgl_kirim = date('Y-m-d', strtotime($tgl_resi));
                        }
                    } else {
                        $tgl_kirim = date('Y-m-d');
                    }

                    // Parse tanggal terima (optional)
                    $tgl_sampai = null;
                    if (!empty($tgl_terima)) {
                        if (is_numeric($tgl_terima)) {
                            // Excel date format (number)
                            $tgl_sampai = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl_terima)->format('Y-m-d');
                        } else {
                            // String date format (support DD/MM/YYYY or YYYY-MM-DD)
                            if (strpos($tgl_terima, '/') !== false) {
                                // Format DD/MM/YYYY
                                $parts = explode('/', $tgl_terima);
                                if (count($parts) == 3) {
                                    $tgl_sampai = $parts[2] . '-' . str_pad($parts[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                                } else {
                                    $tgl_sampai = date('Y-m-d', strtotime($tgl_terima));
                                }
                            } else {
                                $tgl_sampai = date('Y-m-d', strtotime($tgl_terima));
                            }
                        }
                    }

                    // PENTING: Increment ID dan nomor kode SEBELUM generate kode
                    // Harus di awal setelah validasi sukses
                    $current_id++;
                    $current_num++;

                    // Generate kode dengan nomor yang sudah di-increment
                    $month_num = date('m', strtotime($tgl_kirim));
                    $month_roman = $month_to_roman[$month_num];
                    $year = date('Y', strtotime($tgl_kirim));
                    $kode = sprintf('%03d/KD/VYM/%s/%s', $current_num, $month_roman, $year);

                    // Cari customer di database berdasarkan nama (pencarian spesifik)
                    $customer = $this->db->select('id_customer, nama_customer, alamat_customer, contact')
                        ->from('customer')
                        ->like('nama_customer', $penerima, 'both')
                        ->limit(1)
                        ->get()
                        ->row();

                    // Auto-fill data customer jika ditemukan
                    $alamat = '';
                    $pic = '';

                    if ($customer) {
                        $alamat = $customer->alamat_customer ?? '';
                        $pic = $customer->contact ?? '';
                    }

                    // Debug log - bisa dihapus nanti
                    log_message('debug', "Import Row $row_num: Generated ID = $current_id, kode = $kode (num = $current_num)");

                    // Simpan kode pertama dan terakhir untuk response
                    if (empty($first_kode)) {
                        $first_kode = $kode;
                    }
                    $last_kode = $kode;

                    // Insert ke tabel kirim_dokumen dengan ID manual
                    $data_kirim = [
                        'id' => $current_id, // MANUAL ID INCREMENT
                        'kode' => $kode,
                        'nama_customer' => $penerima,
                        'alamat' => $alamat, // Auto-fill dari customer jika ditemukan
                        'pic' => $pic, // Auto-fill dari customer jika ditemukan
                        'asal' => $periode, // Periode untuk referensi
                        'marketing' => sessNama(),
                        'keterangan' => $desc_product,
                        'link_doc' => '',
                        'id_pengguna' => sessPenggunaId(),
                        'ekspedisi' => $nama_ekspedisi,
                        'no_resi' => $no_resi,
                        'link_resi' => '',
                        'biaya' => 0,
                        'tgl_kirim' => $tgl_kirim,
                        'tgl_sampai' => $tgl_sampai, // Dari kolom TGL DI TERIMA
                        'id_ekspedisi' => $id_ekspedisi
                    ];

                    $this->db->insert('kirim_dokumen', $data_kirim);
                    // Gunakan ID yang sudah kita generate manual
                    $id_kirim = $current_id;

                    // Increment ID untuk kirim_status
                    $current_status_id++;

                    // Tentukan status: jika ada tgl_sampai gunakan status 5, jika tidak gunakan status_default
                    $final_status = !empty($tgl_sampai) ? 5 : $status_default;

                    // Insert status awal sesuai pilihan user dengan ID manual
                    $data_status = [
                        'id' => $current_status_id, // MANUAL ID INCREMENT untuk kirim_status
                        'id_kirim' => $id_kirim,
                        'id_status' => $final_status,
                        'id_pengguna' => sessPenggunaId(),
                        'keterangan_konfirmasi' => 'Import dari Excel - Periode: ' . $periode . ($customer ? ' (Data customer auto-filled)' : '')
                    ];

                    // Jika status 5 (Diterima) dan ada tgl_sampai, tambahkan info penerima
                    if ($final_status == 5 && !empty($tgl_sampai)) {
                        $data_status['tgl_penerima'] = $tgl_sampai;
                        $data_status['nama_penerima'] = $penerima;
                        $data_status['bukti_penerima'] = 'Import dari Excel';
                    }

                    $this->db->insert('kirim_status', $data_status);
                    $success_count++;
                } catch (Exception $e) {
                    $errors[] = "Baris $row_num: " . $e->getMessage();
                    $error_count++;
                }
            }

            // Commit transaksi
            $this->db->trans_complete();

            // Cek jika transaksi gagal
            if ($this->db->trans_status() === FALSE) {
                ajaxReturnDie('error', 'Terjadi kesalahan saat menyimpan data ke database', FALSE);
                return;
            }

            // Log aktivitas
            addlog('Kirim Dokumen', "Import $success_count data kirim dokumen dari Excel (Periode: $periode)");

            // Response
            $message = "Import selesai: $success_count berhasil";
            if (!empty($first_kode) && !empty($last_kode)) {
                $message .= " (Kode: $first_kode s/d $last_kode)";
            }
            if ($error_count > 0) {
                $message .= ", $error_count gagal";
                if (count($errors) > 0) {
                    $message .= "\n\nError:\n" . implode("\n", array_slice($errors, 0, 10));
                    if (count($errors) > 10) {
                        $message .= "\n... dan " . (count($errors) - 10) . " error lainnya";
                    }
                }
            }

            ajaxReturnDie('success', $message, TRUE);
        } catch (Exception $e) {
            ajaxReturnDie('error', 'Error: ' . $e->getMessage(), FALSE);
        }
    }

    /**
     * Add Status Tracking Dokumen (Admin - Manual Entry)
     * FIXED VERSION - Sesuai struktur database
     */
    public function add_status_dokumen_admin()
    {
        // Cek Admin
        if (!in_array(sessPenggunaId(), [1, 15, 33, 7, 73, 749, 763, 769])) {
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            return;
        }

        header('Content-Type: application/json');

        $id_kirim_enc = $this->input->post('id_kirim', TRUE);
        $id_kirim     = decrypt($id_kirim_enc);
        $status       = $this->input->post('status', TRUE);
        $created_at   = $this->input->post('created_at', TRUE);

        if (empty($id_kirim) || $status === '') {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
            return;
        }

        // Validasi id_kirim exists di tabel kirim_dokumen
        $check = $this->db->select('id')->from('kirim_dokumen')->where('id', $id_kirim)->get()->row();
        if (!$check) {
            echo json_encode(['status' => 'error', 'message' => 'Data kirim dokumen tidak ditemukan.']);
            return;
        }

        // Format data insert ke tabel kirim_status
        $data = [
            'id_kirim'              => $id_kirim,
            'id_status'             => $status,
            'id_pengguna'           => sessPenggunaId(),
            'keterangan_konfirmasi' => $this->input->post('keterangan_konfirmasi', TRUE),
            'created_at'            => !empty($created_at) ? date('Y-m-d H:i:s', strtotime($created_at)) : date('Y-m-d H:i:s')
        ];

        // Jika status Diterima (5)
        if ($status == 5) {
            $data['nama_penerima']  = $this->input->post('nama_penerima', TRUE);
            $data['tgl_penerima']   = $this->input->post('tgl_penerima', TRUE);
            $data['bukti_penerima'] = $this->input->post('bukti_penerima', TRUE);

            // Update juga tgl_sampai di tabel kirim_dokumen
            if (!empty($data['tgl_penerima'])) {
                $this->db->where('id', $id_kirim);
                $this->db->update('kirim_dokumen', ['tgl_sampai' => $data['tgl_penerima']]);
            }
        }

        // Insert ke tabel kirim_status
        $insert = $this->db->insert('kirim_status', $data);

        if ($insert) {
            addlog('Tracking Dokumen', 'Admin Insert Status Manual untuk id_kirim: ' . $id_kirim);
            echo json_encode(['status' => 'success', 'message' => 'Status baru berhasil ditambahkan.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
        }
    }

    // =========================================================================
    // SISTEM TRACKING IMPORT (9 TAHAP: PO MANUFACTURE -> GUDANG PUSAT/JKT/JOGJA)
    // =========================================================================

    public function tracking_import()
    {
        grantAccessFor('all');

        $filter_gudang = $this->input->get('gudang', TRUE);
        $filter_stage  = $this->input->get('stage', TRUE);

        $import_list = $this->md_tracking->getAllTrackingImport($filter_gudang, $filter_stage);
        $metrics_map = [];
        foreach ($import_list as $row) {
            $metrics_map[$row->id_import] = $this->md_tracking->resolveImportMetrics($row);
        }

        $page_data['switch']           = $this->id_navbar();
        $page_data['import_list']      = $import_list;
        $page_data['metrics_map']      = $metrics_map;
        $page_data['stage_defs']       = $this->md_tracking->getImportStageDefinitions();
        $page_data['list_po_supplier'] = $this->md_tracking->getAllPoSupplierForImport();
        $page_data['list_forwarder']   = $this->md_tracking->getAllForwarderMaster();
        $page_data['list_eks']         = $this->md_tracking->getAllEkspedisi();
        $page_data['list_gudang']      = $this->md_tracking->getAllGudang();
        $page_data['filter_gudang']    = $filter_gudang;
        $page_data['filter_stage']     = $filter_stage;
        $page_data['page_name']        = 'tracking/v_tracking_import';
        $page_data['page_title']       = 'Sistem Tracking Import';
        $page_data['page_desc']        = 'Tracking Import: PO Manufacture → Forwarder → Kapal → Port → Pajak → Ekspedisi Domestik → Gudang VYM';

        $this->load->view('index', $page_data);
    }

    public function add_import()
    {
        grantAccessFor('all');

        $no_po_manufacture = trim((string)$this->input->post('no_po_manufacture', TRUE));
        $nama_manufacture  = trim((string)$this->input->post('nama_manufacture', TRUE));
        $daftar_unit       = trim((string)$this->input->post('daftar_unit', TRUE));

        if ($no_po_manufacture === '' || $nama_manufacture === '' || $daftar_unit === '') {
            ajaxReturnDie('error', 'No. PO Manufacture, Nama Manufacture, dan Daftar Unit wajib diisi!', FALSE);
            return;
        }

        $id_forwarder   = $this->input->post('id_forwarder', TRUE) ?: null;
        $nama_forwarder = trim((string)$this->input->post('nama_forwarder', TRUE));
        if (!empty($id_forwarder) && $nama_forwarder === '') {
            $fw = $this->db->get_where('forwarder', ['id' => $id_forwarder])->row();
            if ($fw) {
                $nama_forwarder = $fw->nama;
            }
        }

        $id_ekspedisi_domestik   = $this->input->post('id_ekspedisi_domestik', TRUE) ?: null;
        $nama_ekspedisi_domestik = trim((string)$this->input->post('nama_ekspedisi_domestik', TRUE));
        if (!empty($id_ekspedisi_domestik) && $nama_ekspedisi_domestik === '') {
            $eks = $this->md_ekspedisi->getById($id_ekspedisi_domestik);
            if (!empty($eks)) {
                $nama_ekspedisi_domestik = $eks[0]->nama_ekspedisi;
            }
        }

        $current_stage = (int)($this->input->post('current_stage', TRUE) ?: 1);
        if ($current_stage < 1 || $current_stage > 9) {
            $current_stage = 1;
        }

        $now = date('Y-m-d H:i:s');
        $tgl_po_input = $this->input->post('waktu_1_po_manufacture', TRUE);
        $waktu_po = !empty($tgl_po_input) ? date('Y-m-d H:i:s', strtotime($tgl_po_input)) : $now;

        $kode_import = $this->md_tracking->generateKodeImport();
        $stage_defs  = $this->md_tracking->getImportStageDefinitions();

        $data = [
            'kode_import'                => $kode_import,
            'id_po_supplier'             => $this->input->post('id_po_supplier', TRUE) ?: null,
            'no_po_manufacture'          => $no_po_manufacture,
            'nama_manufacture'           => $nama_manufacture,
            'negara_asal'                => $this->input->post('negara_asal', TRUE) ?: null,
            'daftar_unit'                => $daftar_unit,
            'moda_transportasi'          => $this->input->post('moda_transportasi', TRUE) ?: 'Sea Freight (Kapal)',
            'id_forwarder'               => $id_forwarder,
            'nama_forwarder'             => $nama_forwarder ?: null,
            'no_bl_awb'                  => $this->input->post('no_bl_awb', TRUE) ?: null,
            'nama_kapal_flight'          => $this->input->post('nama_kapal_flight', TRUE) ?: null,
            'pelabuhan_asal'             => $this->input->post('pelabuhan_asal', TRUE) ?: null,
            'pelabuhan_tujuan'           => $this->input->post('pelabuhan_tujuan', TRUE) ?: null,
            'id_ekspedisi_domestik'      => $id_ekspedisi_domestik,
            'nama_ekspedisi_domestik'    => $nama_ekspedisi_domestik ?: null,
            'no_resi_domestik'           => $this->input->post('no_resi_domestik', TRUE) ?: null,
            'nama_gudang_tujuan'         => $this->input->post('nama_gudang_tujuan', TRUE) ?: 'Gudang Pusat',
            'tgl_estimasi_sampai_port'   => $this->input->post('tgl_estimasi_sampai_port', TRUE) ?: null,
            'tgl_estimasi_sampai_gudang' => $this->input->post('tgl_estimasi_sampai_gudang', TRUE) ?: null,
            'waktu_1_po_manufacture'     => $waktu_po,
            'current_stage'              => $current_stage,
            'keterangan'                 => $this->input->post('keterangan', TRUE) ?: null,
            'created_by'                 => sessPenggunaId(),
            'created_at'                 => $now,
            'updated_at'                 => $now,
            'stat'                       => 1
        ];

        // Jika stage awal langsung > 1, isi otomatis timestamp tahapan sebelumnya
        for ($s = 2; $s <= $current_stage; $s++) {
            $field = $stage_defs[$s]['field'];
            $data[$field] = $now;
        }

        $this->db->trans_begin();
        $this->db->insert('tracking_import', $data);
        $id_import = $this->db->insert_id();

        $this->db->insert('tracking_import_log', [
            'id_import'       => $id_import,
            'stage'           => 1,
            'stage_label'     => $stage_defs[1]['label'],
            'waktu_milestone' => $waktu_po,
            'catatan'         => 'Data Tracking Import dibuat (PO Manufacture: ' . $no_po_manufacture . ' - ' . $nama_manufacture . ')',
            'id_pengguna'     => sessPenggunaId(),
            'created_at'      => $now
        ]);

        if ($current_stage > 1) {
            $this->db->insert('tracking_import_log', [
                'id_import'       => $id_import,
                'stage'           => $current_stage,
                'stage_label'     => $stage_defs[$current_stage]['label'],
                'waktu_milestone' => $now,
                'catatan'         => $data['keterangan'] ?: ('Tahapan awal diset pada: ' . $stage_defs[$current_stage]['label']),
                'id_pengguna'     => sessPenggunaId(),
                'created_at'      => $now
            ]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Gagal menyimpan data Tracking Import!', FALSE);
            return;
        }

        $this->db->trans_commit();
        addlog('Tracking Import', 'Menambahkan Tracking Import ' . $kode_import . ' (' . $no_po_manufacture . ')');
        ajaxReturnDie('success', 'Data Tracking Import (' . $kode_import . ') berhasil ditambahkan!', TRUE);
    }

    public function detail_import($param1 = null)
    {
        grantAccessFor('all');

        if (empty($param1)) {
            redirect('tracking/tracking_import');
            return;
        }

        $id_import = decrypt($param1);
        $import_row = $this->md_tracking->getTrackingImportById($id_import);
        if (!$import_row) {
            $this->session->set_flashdata('error_message', 'Data Tracking Import tidak ditemukan.');
            redirect('tracking/tracking_import');
            return;
        }

        $page_data['switch']         = $this->id_navbar();
        $page_data['import_row']     = $import_row;
        $page_data['metrics']        = $this->md_tracking->resolveImportMetrics($import_row);
        $page_data['logs']           = $this->md_tracking->getTrackingImportLogs($id_import);
        $page_data['stage_defs']     = $this->md_tracking->getImportStageDefinitions();
        $page_data['list_forwarder'] = $this->md_tracking->getAllForwarderMaster();
        $page_data['list_eks']       = $this->md_tracking->getAllEkspedisi();
        $page_data['page_name']      = 'tracking/v_detail_tracking_import';
        $page_data['page_title']     = 'Detail Tracking Import - ' . $import_row->kode_import;
        $page_data['page_desc']      = 'Monitoring 9 Tahapan Import, Pembayaran Pajak PIB & Kecepatan Proses';

        $this->load->view('index', $page_data);
    }

    public function update_import_stage()
    {
        grantAccessFor('all');

        $id_import_enc = $this->input->post('id_import', TRUE);
        $id_import     = decrypt($id_import_enc);
        $row           = $this->md_tracking->getTrackingImportById($id_import);

        if (!$row) {
            ajaxReturnDie('error', 'Data Tracking Import tidak ditemukan!', FALSE);
            return;
        }

        $stage = (int)$this->input->post('stage', TRUE);
        $stage_defs = $this->md_tracking->getImportStageDefinitions();
        if (!isset($stage_defs[$stage])) {
            ajaxReturnDie('error', 'Tahapan import tidak valid!', FALSE);
            return;
        }

        $now = date('Y-m-d H:i:s');
        $update = [
            'current_stage' => max((int)$row->current_stage, $stage),
            'updated_at'    => $now
        ];

        // Catat otomatis waktu tahapan jika belum terisi (atau tahapan yang sedang diupdate)
        $target_field = $stage_defs[$stage]['field'];
        if (empty($row->$target_field) || strpos((string)$row->$target_field, '0000-00-00') !== false) {
            $update[$target_field] = $now;
        }
        // Pastikan tahapan sebelumnya yang terlewati ikut tercatat waktunya agar SLA timeline tidak bolong
        for ($s = 1; $s < $stage; $s++) {
            $f = $stage_defs[$s]['field'];
            if (empty($row->$f) && empty($update[$f])) {
                $update[$f] = $now;
            }
        }

        // Update field informasi logistik/pajak/domestik bila dikirimkan dari form
        $optional_fields = [
            'no_po_manufacture', 'nama_manufacture', 'negara_asal', 'daftar_unit',
            'moda_transportasi', 'nama_forwarder', 'no_bl_awb', 'nama_kapal_flight',
            'pelabuhan_asal', 'pelabuhan_tujuan', 'no_pib', 'bukti_bayar_pajak',
            'nama_ekspedisi_domestik', 'no_resi_domestik', 'nama_gudang_tujuan',
            'pic_penerima_gudang', 'bukti_terima_gudang',
            'tgl_estimasi_sampai_port', 'tgl_estimasi_sampai_gudang', 'keterangan'
        ];

        foreach ($optional_fields as $fld) {
            $val = $this->input->post($fld, TRUE);
            if ($val !== null && trim((string)$val) !== '') {
                $update[$fld] = trim((string)$val);
            }
        }

        $nominal_pajak = $this->input->post('nominal_pajak_import', TRUE);
        if ($nominal_pajak !== null && trim((string)$nominal_pajak) !== '') {
            $clean_nominal = preg_replace('/[^0-9.]/', '', str_replace(',', '', (string)$nominal_pajak));
            $update['nominal_pajak_import'] = (float)$clean_nominal;
        }

        $id_forwarder = $this->input->post('id_forwarder', TRUE);
        if (!empty($id_forwarder)) {
            $update['id_forwarder'] = (int)$id_forwarder;
            if (empty($update['nama_forwarder'])) {
                $fw = $this->db->get_where('forwarder', ['id' => $id_forwarder])->row();
                if ($fw) $update['nama_forwarder'] = $fw->nama;
            }
        }

        $id_eks_dom = $this->input->post('id_ekspedisi_domestik', TRUE);
        if (!empty($id_eks_dom)) {
            $update['id_ekspedisi_domestik'] = (int)$id_eks_dom;
            if (empty($update['nama_ekspedisi_domestik'])) {
                $eks = $this->md_ekspedisi->getById($id_eks_dom);
                if (!empty($eks)) $update['nama_ekspedisi_domestik'] = $eks[0]->nama_ekspedisi;
            }
        }

        $catatan    = trim((string)$this->input->post('catatan_log', TRUE));
        $bukti_link = trim((string)$this->input->post('bukti_link', TRUE));

        if ($stage == 6 && empty($bukti_link) && !empty($update['bukti_bayar_pajak'])) {
            $bukti_link = $update['bukti_bayar_pajak'];
        }
        if ($stage == 9 && empty($bukti_link) && !empty($update['bukti_terima_gudang'])) {
            $bukti_link = $update['bukti_terima_gudang'];
        }

        $this->db->trans_begin();
        $this->db->where('id_import', $id_import)->update('tracking_import', $update);

        $this->db->insert('tracking_import_log', [
            'id_import'       => $id_import,
            'stage'           => $stage,
            'stage_label'     => $stage_defs[$stage]['label'],
            'waktu_milestone' => $now,
            'catatan'         => $catatan !== '' ? $catatan : ('Update tahapan ke: ' . $stage_defs[$stage]['label']),
            'bukti_link'      => $bukti_link !== '' ? $bukti_link : null,
            'id_pengguna'     => sessPenggunaId(),
            'created_at'      => $now
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Gagal memperbarui tahapan Tracking Import!', FALSE);
            return;
        }

        $this->db->trans_commit();
        addlog('Tracking Import', 'Update tahapan ' . $row->kode_import . ' -> ' . $stage_defs[$stage]['label']);
        ajaxReturnDie('success', 'Tahapan "' . $stage_defs[$stage]['label'] . '" berhasil disimpan!', TRUE);
    }

    public function delete_import($param1 = null)
    {
        grantAccessFor('all');

        $id_import = decrypt($param1);
        if (empty($id_import)) {
            ajaxReturnDie('error', 'ID Tracking Import tidak valid!', FALSE);
            return;
        }

        $this->db->where('id_import', $id_import)->update('tracking_import', ['stat' => 0]);
        addlog('Tracking Import', 'Menghapus data Tracking Import ID: ' . $id_import);
        ajaxReturnDie('success', 'Data Tracking Import berhasil dihapus', TRUE);
    }

    public function export_import()
    {
        grantAccessFor('all');

        $filter_gudang = $this->input->get('gudang', TRUE);
        $filter_stage  = $this->input->get('stage', TRUE);
        $rows          = $this->md_tracking->getAllTrackingImport($filter_gudang, $filter_stage);
        $stage_defs    = $this->md_tracking->getImportStageDefinitions();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Tracking Import');

        $headers = [
            'A' => 'No',
            'B' => 'Kode Import',
            'C' => 'No. PO Manufacture',
            'D' => 'Manufacture & Negara Asal',
            'E' => 'Daftar Unit / Barang',
            'F' => 'Moda & Forwarder',
            'G' => 'No. B/L / AWB & Kapal/Flight',
            'H' => 'No. PIB & Pajak Impor (Rp)',
            'I' => 'Ekspedisi Domestik & No. Resi',
            'J' => 'Gudang Tujuan & PIC Penerima',
            'K' => 'Tahapan Saat Ini',
            'L' => '1. Waktu PO Manufacture',
            'M' => '2. Waktu Order Forwarder',
            'N' => '3. Waktu Loading Kapal',
            'O' => '4. Waktu Perjalanan Kapal',
            'P' => '5. Waktu Sampai Seaport/Airport',
            'Q' => '6. Waktu Pembayaran Pajak',
            'R' => '7. Waktu Keluar Gudang Port',
            'S' => '8. Waktu Hand-over Domestik',
            'T' => '9. Waktu Sampai Gudang VYM',
            'U' => 'Durasi Origin (PO -> Loading)',
            'V' => 'Durasi Freight (Loading -> Port)',
            'W' => 'Durasi Pajak & Clearance (Port -> Keluar Port)',
            'X' => 'Durasi Domestik (Keluar Port -> Gudang)',
            'Y' => 'Total Lead Time Import',
            'Z' => 'Ketepatan Estimasi Gudang'
        ];

        foreach ($headers as $col => $text) {
            $sheet->setCellValue($col . '1', $text);
        }
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);

        $r = 2;
        $no = 1;
        foreach ($rows as $item) {
            $m = $this->md_tracking->resolveImportMetrics($item);
            $curStageLabel = $stage_defs[$item->current_stage]['label'] ?? ('Tahap ' . $item->current_stage);

            $sheet->setCellValue('A' . $r, $no++);
            $sheet->setCellValue('B' . $r, $item->kode_import);
            $sheet->setCellValue('C' . $r, $item->no_po_manufacture);
            $sheet->setCellValue('D' . $r, $item->nama_manufacture . ($item->negara_asal ? ' (' . $item->negara_asal . ')' : ''));
            $sheet->setCellValue('E' . $r, $item->daftar_unit);
            $sheet->setCellValue('F' . $r, ($item->moda_transportasi ?: '-') . ' / ' . ($item->nama_forwarder ?: '-'));
            $sheet->setCellValue('G' . $r, ($item->no_bl_awb ?: '-') . ' / ' . ($item->nama_kapal_flight ?: '-'));
            $sheet->setCellValue('H' . $r, ($item->no_pib ?: '-') . ' / Rp ' . number_format((float)$item->nominal_pajak_import, 0, ',', '.'));
            $sheet->setCellValue('I' . $r, ($item->nama_ekspedisi_domestik ?: '-') . ' / ' . ($item->no_resi_domestik ?: '-'));
            $sheet->setCellValue('J' . $r, ($item->nama_gudang_tujuan ?: '-') . ($item->pic_penerima_gudang ? ' (' . $item->pic_penerima_gudang . ')' : ''));
            $sheet->setCellValue('K' . $r, $curStageLabel);
            $sheet->setCellValue('L' . $r, $m['times'][1] ?: '-');
            $sheet->setCellValue('M' . $r, $m['times'][2] ?: '-');
            $sheet->setCellValue('N' . $r, $m['times'][3] ?: '-');
            $sheet->setCellValue('O' . $r, $m['times'][4] ?: '-');
            $sheet->setCellValue('P' . $r, $m['times'][5] ?: '-');
            $sheet->setCellValue('Q' . $r, $m['times'][6] ?: '-');
            $sheet->setCellValue('R' . $r, $m['times'][7] ?: '-');
            $sheet->setCellValue('S' . $r, $m['times'][8] ?: '-');
            $sheet->setCellValue('T' . $r, $m['times'][9] ?: '-');
            $sheet->setCellValue('U' . $r, $m['dur_origin']['valid'] ? $m['dur_origin']['text'] : '-');
            $sheet->setCellValue('V' . $r, $m['dur_freight']['valid'] ? $m['dur_freight']['text'] : '-');
            $sheet->setCellValue('W' . $r, $m['dur_customs']['valid'] ? $m['dur_customs']['text'] : '-');
            $sheet->setCellValue('X' . $r, $m['dur_domestik']['valid'] ? $m['dur_domestik']['text'] : '-');
            $sheet->setCellValue('Y' . $r, $m['dur_total']['valid'] ? $m['dur_total']['text'] : '-');
            $sheet->setCellValue('Z' . $r, $m['ketepatan']['label']);
            $r++;
        }

        foreach (range('A', 'Z') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Tracking_Import_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
