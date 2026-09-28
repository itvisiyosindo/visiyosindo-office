<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined("BASEPATH") or exit("No direct script access allowed");

class Forwarder extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model("md_forwarder");
        $this->load->model("md_pelanggan");
        $this->load->model("md_pengguna");
        $this->load->model("md_prov_kota");
        $this->load->model("md_kategori_tiket");
        $this->load->model("md_tiket");
        $this->load->helper("terbilang_helper");
        $this->load->helper("tanggal_helper");
        $this->load->helper("datetime_helper");
        $this->load->helper("whatsapp_helper");
        $this->load->helper("encrypt_helper");
    }

    function id_navbar()
    {
        $id_navbar = "helpdesk";
        return $id_navbar;
    }

    private function _clean_and_convert_price($input_string)
    {
        $cleaned_string = trim(str_replace(["Rp", " "], "", $input_string));

        // Coba konversi ke float. Jika gagal atau menghasilkan 0, kita kembalikan 0.
        $float_value = (float) $cleaned_string;

        return $float_value;
    }

    // Fungsi Helper untuk memproses input detail harga (Mengurangi kode berulang)
    private function _process_detail_price(
        $input_name_base,
        $kurs,
        $is_add = true
    ) {
        $suffix = $is_add ? "1" : "";

        $uang_input_name = "uang_" . $input_name_base . $suffix;
        $nilai_input_name = $input_name_base;

        $uang_pilihan = $this->input->post($uang_input_name, true) ?: "2";
        $nilai_input = $this->input->post($nilai_input_name, true);

        $nilai_input_bersih = preg_replace("/[^0-9]/", "", (string) $nilai_input);

        $nilai_float = $nilai_input_bersih !== "" ? (float) $nilai_input_bersih : 0;

        if ($uang_pilihan == "1") {
            return $kurs * $nilai_float;
        } else {
            return $nilai_float;
        }
    }

    public function index()
    {
        grantAccessFor("all");

        $page_data["switch"] = $this->id_navbar();
        $page_data["page_name"] = "forwarder/v_forwarder";
        $page_data["page_title"] = "Forwarder";
        $page_data["page_desc"] = "Master Data Forwarder";
        $this->load->view("index", $page_data);
    }

    public function add()
    {
        grantAccessFor("all");

        $data["nama"] = $this->input->post("nama", true);
        $data["alamat"] = $this->input->post("alamat", true);
        $data["contact"] = $this->input->post("contact", true);
        $data["website"] = $this->input->post("website", true);
        $data["keterangan"] = $this->input->post("keterangan", true);

        $this->md_forwarder->add($data);

        /** LOG */
        addLog("Forwarder", 'Menambah Forwarder "' . $data["nama"] . '"');
        ajaxReturnDie("success", "Data berhasil ditambahkan", "reload_table");
    }

    public function edit($param1)
    {
        grantAccessFor("all");
        $id = decrypt($param1);
        $dt = $this->md_forwarder->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die();
    }

    public function update()
    {
        grantAccessFor("all");
        $id = decrypt($this->input->post("id_pelanggan"));
        $data["nama"] = $this->input->post("nama", true);
        $data["alamat"] = $this->input->post("alamat", true);
        $data["contact"] = $this->input->post("contact", true);
        $data["website"] = $this->input->post("website", true);
        $data["keterangan"] = $this->input->post("keterangan", true);

        $this->md_forwarder->update($id, $data);

        /** LOG */
        addLog(
            "Forwarder",
            'Memperbarui data Forwarder "' . $data["nama"] . '"',
        );
        ajaxReturnDie("success", "Data berhasil diperbarui", "reload_table");
    }

    public function delete($param1)
    {
        grantAccessFor("all");

        $id = $param1;
        $this->md_forwarder->hapus("id = " . $id, "forwarder");

        addLog("Forwarder", "Menghapus Forwarder");
        ajaxReturnDie("success", "Forwarder Berhasil Dihapus", "reload_table");
    }

    public function pagination()
    {
        grantAccessFor("all");

        $dt = $this->md_forwarder->getAll();
        $start = $this->input->post("start");
        $data = [];
        foreach ($dt["data"] as $row) {
            $id = encrypt($row->id);
            $li_btn =
                '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' .
                $id .
                '"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' .
                $row->id .
                '" data-object="forwarder/delete/' .
                $row->id .
                '"><i class="bx bx-trash"></i></button>
                </div>';

            $th = [];
            $th[] = ++$start . ".";
            $th[] = $row->nama;
            $th[] = $row->alamat;
            $th[] = $row->contact;
            $th[] = $row->website;
            $th[] = $row->keterangan;
            $th[] = $li_btn;

            $data[] = $th;
        }

        $dt["data"] = $data;
        echo json_encode($dt);
        die();
    }

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor("all");
        if ($param == "list") {
            $page_data["switch"] = $this->id_navbar();
            $page_data["page_name"] = "forwarder/v_list_forwarder";
            $page_data["page_title"] = "Approval Forwarder";
            $page_data["page_desc"] = "Daftar Approval Forwarder Luar Negeri";
            $this->load->view("index", $page_data);
        } elseif ($param == "permintaan") {
            $page_data["switch"] = $this->id_navbar();
            $page_data["page_name"] = "forwarder/v_all_forwarder";
            $page_data["page_title"] = "Approval Forwarder";
            $page_data["page_desc"] =
                "Daftar Approval Forwarder Luar Negeri yang Diajukan";
            $this->load->view("index", $page_data);
        } elseif ($param == "detail") {
            $page_data["switch"] = $this->id_navbar();
            $page_data["data_aprv"] = $this->md_forwarder->getAppByID($param2);
            $page_data["detail_aprv"] = $this->md_forwarder->getDetailAppById(
                $param2,
            );
            $page_data["forwarderNames"] = array_unique(
                array_map(function ($item) {
                    return $item->nama_forwarder;
                }, $page_data["detail_aprv"]),
            );
            $page_data["list_for"] = $this->md_forwarder->getForByWhere([
                "g.status" => 1,
            ]);
            $page_data["pengguna"] = $this->md_pengguna->getById(
                sessPenggunaId(),
            );
            $page_data["list_kota"] = $this->md_prov_kota->getAllKota();
            $page_data["page_name"] = "forwarder/v_aju_forwarder";
            $page_data["page_title"] = "Approval Forwarder";
            $page_data["page_desc"] =
                "Form Pengajuan Approval Forwarder Luar Negeri";
            $this->load->view("index", $page_data);
        } elseif ($param == "detail_data") {
            $page_data["switch"] = $this->id_navbar();
            $page_data["data_aprv"] = $this->md_forwarder->getAppByID($param2);
            $page_data["detail_aprv"] = $this->md_forwarder->getDetailAppById(
                $param2,
            );
            $page_data["forwarderNames"] = array_unique(
                array_map(function ($item) {
                    return $item->nama_forwarder;
                }, $page_data["detail_aprv"]),
            );
            $page_data["list_for"] = $this->md_forwarder->getForByWhere([
                "g.status" => 1,
            ]);
            $page_data["pengguna"] = $this->md_pengguna->getById(
                sessPenggunaId(),
            );
            $page_data["list_kota"] = $this->md_prov_kota->getAllKota();
            $page_data["page_name"] = "forwarder/v_detail_forwarder";
            $page_data["page_title"] = "Approval Forwarder";
            $page_data["page_desc"] = "Detail Approval Forwarder Luar Negeri";
            $this->load->view("index", $page_data);
        }
    }

    public function pagination_permintaan()
    {
        grantAccessFor("all");

        $dt = $this->md_forwarder->getAllAppBy(sessPenggunaId());
        $start = $this->input->post("start");
        $data = [];
        foreach ($dt["data"] as $row) {
            $id = encrypt($row->id);

            // LOGIKA STATUS (Dipertahankan)
            if ($row->status == 1 || $row->status == 0) {
                $li_btn =
                    '
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' .
                    $id .
                    '"><i class="bx bx-pencil"></i></button>
                    </div>';
            } else {
                $li_btn = "";
            }
            $kode =
                '<a href="forwarder/show/detail/' .
                $row->id .
                '">' .
                $row->kode .
                "</a>";

            if ($row->status == "0") {
                $stat_surat =
                    '<span class="badge badge-ecommerce badge-success">Proses Pengajuan</span>';
            } elseif ($row->status == "1") {
                $stat_surat =
                    '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
            } elseif ($row->status == "2") {
                $stat_surat =
                    '<span class="badge badge-ecommerce badge-info">Disetujui Director of Corporate Planning & Business Management</span>';
            } elseif ($row->status == "3") {
                $stat_surat =
                    '<span class="badge badge-ecommerce badge-info">Disetujui Director</span>';
            } else {
                $stat_surat =
                    '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
            }

            // LOGIKA TANGGAL (Dipertahankan)
            $tanggal = $row->tanggal;
            $bulanTahun = date("F Y", strtotime($tanggal));
            $bulanIndonesia = [
                "January" => "Januari",
                "February" => "Februari",
                "March" => "Maret",
                "April" => "April",
                "May" => "Mei",
                "June" => "Juni",
                "July" => "Juli",
                "August" => "Agustus",
                "September" => "September",
                "October" => "Oktober",
                "November" => "November",
                "December" => "Desember",
            ];
            [$bulanInggris, $tahun] = explode(" ", $bulanTahun);
            // $tanggalFormatted = $bulanIndonesia[$bulanInggris] . ' ' . $tahun; // Variabel ini tidak dipakai di $th

            // FIX ERROR number_format() - BARIS INI KRITIS
            $kurs_bersih = $this->_clean_and_convert_price($row->kurs);

            if ($kurs_bersih > 0) {
                // Gunakan number_format pada float yang sudah bersih.
                $kurs = "Rp " . number_format($kurs_bersih, 0, ",", ".");
            } else {
                // Handle jika kurs tidak valid atau nol
                $kurs = "Rp ";
            }

            $th = [];
            $th[] = ++$start . ".";
            $th[] = $kode;
            $th[] = $row->nama;
            $th[] = $row->pol;
            $th[] = $row->pod;
            $th[] = $kurs; // Sudah diformat atau kosong
            $th[] = $row->pengaju;
            $th[] = $stat_surat;
            $th[] = $li_btn;

            $data[] = $th;
        }

        $dt["data"] = $data;
        echo json_encode($dt);
        die();
    }

    public function pagination_list()
    {
        grantAccessFor("all");

        $dt = $this->md_forwarder->getAllApp();
        $start = $this->input->post("start");
        $data = [];
        foreach ($dt["data"] as $row) {
            $id = encrypt($row->id);

            // LOGIKA STATUS (Dipertahankan)
            $admin_btn = "";
            if (isAdmin()) {
                $admin_btn = '<a href="javascript:void(0)" onclick="openAdminEditModal(\'forwarder\', \'' . encrypt($row->id) . '\')" class="btn btn-sm btn-warning" title="Edit Data (Admin)"><i class="fas fa-pencil-alt text-dark"></i></a>';
            }

            if ($row->status == 1 || $row->status == 0) {
                $li_btn =
                    '
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' .
                    $id .
                    '"><i class="bx bx-pencil"></i></button>
                        ' . $admin_btn . '
                    </div>';
            } else {
                if ($admin_btn !== "") {
                    $li_btn = '
                    <div class="btn-group" role="group" aria-label="First group">
                        ' . $admin_btn . '
                    </div>';
                } else {
                    $li_btn = "";
                }
            }
            $kode =
                '<a href="forwarder/show/detail_data/' .
                $row->id .
                '">' .
                $row->kode .
                "</a>";

            if ($row->status == "0") {
                $stat_surat =
                    '<span class="badge badge-ecommerce badge-success">Proses Pengajuan</span>';
            } elseif ($row->status == "1") {
                $stat_surat =
                    '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
            } elseif ($row->status == "2") {
                $stat_surat =
                    '<span class="badge badge-ecommerce badge-info">Disetujui Director of Corporate Planning & Business Management</span>';
            } elseif ($row->status == "3") {
                $stat_surat =
                    '<span class="badge badge-ecommerce badge-info">Disetujui Director</span>';
            } else {
                $stat_surat =
                    '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
            }

            // FIX ERROR number_format() - BARIS INI KRITIS
            $kurs_bersih = $this->_clean_and_convert_price($row->kurs);

            if ($kurs_bersih > 0) {
                // Gunakan number_format pada float yang sudah bersih.
                $kurs = "Rp " . number_format($kurs_bersih, 0, ",", ".");
            } else {
                // Handle jika kurs tidak valid atau nol
                $kurs = "Rp ";
            }

            $th = [];
            $th[] = ++$start . ".";
            $th[] = $kode;
            $th[] = $row->nama;
            $th[] = $row->pol;
            $th[] = $row->pod;
            $th[] = $kurs; // Sudah diformat atau kosong
            $th[] = $row->pengaju;
            $th[] = $stat_surat;
            $th[] = $li_btn;

            $data[] = $th;
        }

        $dt["data"] = $data;
        echo json_encode($dt);
        die();
    }

    //=========================================
    //=========== DUPLIKAT ====================
    //=========================================
    public function duplikat($id)
    {
        grantAccessFor("all");

        $originalApp = $this->md_forwarder->getAppByID($id);
        $originalDetail = $this->md_forwarder->getDetailAppById($id);

        if (!$originalApp) {
            show_404();
        }

        // 1. Buat kode baru (Logika dipertahankan)
        $idFpp = $this->md_forwarder->getKodeId();
        $ambilId = $idFpp->id + 1;
        $panjangId = strlen($ambilId);

        if ($panjangId == 1) {
            $kodeFpp = "00" . $ambilId;
        } elseif ($panjangId == 2) {
            $kodeFpp = "0" . $ambilId;
        } else {
            $kodeFpp = $ambilId;
        }

        $bulan = ambil_bulan();
        $tahun = ambil_tahun();
        $kodeFpp = $kodeFpp . "/PF/VYM/" . $bulan . "/" . $tahun;

        // 2. Siapkan data baru approval_forwarder
        $dataBaru = [
            "nama" => $originalApp[0]->nama,
            "kode" => $kodeFpp,
            "sistem_pengiriman" => $originalApp[0]->sistem_pengiriman,
            "pol" => $originalApp[0]->pol,
            "pod" => $originalApp[0]->pod,
            "berat_dimensi" => $originalApp[0]->berat_dimensi,
            "nilai_inv" => $originalApp[0]->nilai_inv,
            "mata_nilai_inv" => $originalApp[0]->mata_nilai_inv,
            "kurs" => $originalApp[0]->kurs,
            "id_pengguna" => sessPenggunaId(),
            "created_at" => date("Y-m-d H:i:s"),
            "status" => 0, // Reset status ke proses pengajuan
        ];

        // 3. Insert ke approval_forwarder dan ambil ID baru
        $idBaru = $this->md_forwarder->insertApprovalForwarder($dataBaru);

        // 4. Duplikat semua detail
        foreach ($originalDetail as $detail) {
            $dataDetail = [
                "id_app" => $idBaru,
                "id_forwarder" => $detail->id_forwarder,
                "asal_thc" => $detail->asal_thc,
                "asal_bl_fee" => $detail->asal_bl_fee,
                "asal_vgm" => $detail->asal_vgm,
                "asal_agaency_fee" => $detail->asal_agaency_fee,
                "asal_handling_fee" => $detail->asal_handling_fee,
                "asal_transportasi" => $detail->asal_transportasi,
                "asal_loading" => $detail->asal_loading,
                "asal_custom" => $detail->asal_custom,
                "freight_ocean" => $detail->freight_ocean,
                "freight_air" => $detail->freight_air,
                "tuj_cfs" => $detail->tuj_cfs,
                "tuj_doc" => $detail->tuj_doc,
                "tuj_agency_fee" => $detail->tuj_agency_fee,
                "tuj_handling" => $detail->tuj_handling,
                "tuj_do" => $detail->tuj_do,
                "tuj_admin" => $detail->tuj_admin,
                "tuj_devanning" => $detail->tuj_devanning,
                "tuj_fordwarding_fee" => $detail->tuj_fordwarding_fee,
                "tuj_mechanics" => $detail->tuj_mechanics,
                "tuj_other" => $detail->tuj_other,
                "cust_clearance" => $detail->cust_clearance,
                "cust_red_line" => $detail->cust_red_line,
                "cust_handling" => $detail->cust_handling,
                "cust_admin_fee" => $detail->cust_admin_fee,
                "cust_pib_fee" => $detail->cust_pib_fee,
                "cust_transfer" => $detail->cust_transfer,
                "oth_do" => $detail->oth_do,
                "oth_storage" => $detail->oth_storage,
                "oth_trucking" => $detail->oth_trucking,
                "ins_nilai" => $detail->ins_nilai,
                "ins_jenis" => $detail->ins_jenis,
                "ppn" => $detail->ppn,
                "dipilih" => $detail->dipilih,
                "alasan" => $detail->alasan,
                "keterangan" => $detail->keterangan_detail,
                "link_invoice" => $detail->link_invoice,
                "link_packing" => $detail->link_packing,
                "link_sph_for" => $detail->link_sph_for,
                "link_sph_ins" => $detail->link_sph_ins,
            ];

            $this->md_forwarder->insertDetailApprovalForwarder($dataDetail);
        }

        addLog(
            "Approval Forwarder",
            "Menduplikasi Approval Forwarder dari ID Lama " .
                $id .
                " menjadi ID Baru " .
                $idBaru,
        );
        redirect("forwarder/show/detail/" . $idBaru);
    }

    public function addForwarder()
    {
        grantAccessFor("all");

        $this->md_forwarder->reset_increment("approval_forwarder");
        $idFpp = $this->md_forwarder->getKodeId();
        $ambilId = $idFpp->id;
        $ambilId = $ambilId + 1;
        $panjangId = strlen($ambilId);

        if ($panjangId == 1) {
            $kodeFpp = "00" . $ambilId;
        } elseif ($panjangId == 2) {
            $kodeFpp = "0" . $ambilId;
        } else {
            $kodeFpp = $ambilId;
        }

        $bulan = ambil_bulan();
        $tahun = ambil_tahun();

        $kodeFpp = $kodeFpp . "/PF/VYM/" . $bulan . "/" . $tahun;
        $data["kode"] = $kodeFpp;

        $data["id_pengguna"] = sessPenggunaId();
        $data["nama"] = $this->input->post("nama", true);
        //$tanggal_input = $this->input->post('tanggal', TRUE); // ex: 06-2025
        //$tanggal_db = DateTime::createFromFormat('m-Y', $tanggal_input)->format('Y-m-01');
        //$data['tanggal'] = $tanggal_db; // jadi: 2025-06-01

        $data["sistem_pengiriman"] = $this->input->post(
            "sistem_pengiriman",
            true,
        );
        $data["pol"] = $this->input->post("pol", true);
        $data["pod"] = $this->input->post("pod", true);
        $data["berat_dimensi"] = $this->input->post("berat_dimensi", true);
        $data["mata_nilai_inv"] =
            $this->input->post("uang_nilai_inv", true) ?: "USD";
        $data["nilai_inv"] = $this->input->post("nilai_inv", true);
        //$data['nilai_inv']         = $mata.' '.$nilai;
        $data["kurs"] = $this->input->post("kurs", true);
        $this->md_forwarder->addForwarder($data);

        /** LOG */
        addLog(
            "Approval Forwarder",
            "Permintaan Approval Forwarder Kode: " . $kodeFpp,
        );
        ajaxReturnDie("success", "Berhasil Diajukan", true);
    }

    public function editForwarder($param1)
    {
        grantAccessFor("all");
        $id = decrypt($param1);
        $dt = $this->md_forwarder->getByIdForwarder($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die();
    }

    public function updateForwarder()
    {
        grantAccessFor("all");
        $id = decrypt($this->input->post("id_pelanggan"));
        $data["nama"] = $this->input->post("nama", true);
        $data["sistem_pengiriman"] = $this->input->post(
            "sistem_pengiriman",
            true,
        );
        $data["pol"] = $this->input->post("pol", true);
        $data["pod"] = $this->input->post("pod", true);
        $data["berat_dimensi"] = $this->input->post("berat_dimensi", true);
        $data["mata_nilai_inv"] = $this->input->post("uang_nilai_inv", true);
        $data["nilai_inv"] = $this->input->post("nilai_inv", true);
        $data["kurs"] = $this->input->post("kurs", true);

        $this->md_forwarder->updateForwarder($id, $data);

        /** LOG */
        addLog(
            "Approval Forwarder",
            'Memperbarui data Forwarder "' . $id . '"',
        );
        ajaxReturnDie("success", "Data berhasil diperbarui", "reload_table");
    }

    public function addDetailForwarder()
    {
        grantAccessFor("all");

        $data["id_app"] = $this->input->post("id_app", true);
        $data["id_forwarder"] = $this->input->post("id_forwarder", true);

        // Data statis/non-harga
        $data["oth_do"] = $this->input->post("oth_do", true);
        $data["oth_storage"] = $this->input->post("oth_storage", true);
        $data["ins_jenis"] =
            $this->input->post("ins_jenis1", true) ?: "NON CLAIM";
        $data["dipilih"] = $this->input->post("dipilih1", true) ?: "2";
        $data["ppn"] = "Harga Belum PPN";
        $data["alasan"] = $this->input->post("alasan", true);
        $data["keterangan"] = $this->input->post("keterangan", true);
        $data["link_invoice"] = $this->input->post("link_invoice", true);
        $data["link_packing"] = $this->input->post("link_packing", true);
        $data["link_sph_for"] = $this->input->post("link_sph_for", true);
        $data["link_sph_ins"] = $this->input->post("link_sph_ins", true);

        // Input Ongkos Sesuai Mata Uang (Menggunakan Helper Lokal)
        $mata = $this->md_forwarder->getAppByID($data["id_app"]);
        $kurs = $mata[0]->kurs;

        $data["asal_thc"] = $this->_process_detail_price(
            "asal_thc",
            $kurs,
            true,
        );
        $data["asal_bl_fee"] = $this->_process_detail_price(
            "asal_bl_fee",
            $kurs,
            true,
        );
        $data["asal_vgm"] = $this->_process_detail_price(
            "asal_vgm",
            $kurs,
            true,
        );
        $data["asal_agaency_fee"] = $this->_process_detail_price(
            "asal_agaency_fee",
            $kurs,
            true,
        );
        $data["asal_handling_fee"] = $this->_process_detail_price(
            "asal_handling_fee",
            $kurs,
            true,
        );
        $data["asal_transportasi"] = $this->_process_detail_price(
            "asal_transportasi",
            $kurs,
            true,
        );
        $data["asal_loading"] = $this->_process_detail_price(
            "asal_loading",
            $kurs,
            true,
        );
        $data["asal_pickup"] = $this->_process_detail_price(
            "asal_pickup",
            $kurs,
            true,
        );
        $data["asal_seal"] = $this->_process_detail_price(
            "asal_seal",
            $kurs,
            true,
        );
        $data["asal_custom"] = $this->_process_detail_price(
            "asal_custom",
            $kurs,
            true,
        );
        $data["asal_shipping"] = $this->_process_detail_price(
            "asal_shipping",
            $kurs,
            true,
        );
        $data["asal_cfs"] = $this->_process_detail_price(
            "asal_cfs",
            $kurs,
            true,
        );
        $data["asal_dg"] = $this->_process_detail_price(
            "asal_dg",
            $kurs,
            true,
        );
        $data["asal_psa"] = $this->_process_detail_price(
            "asal_psa",
            $kurs,
            true,
        );
        $data["asal_other"] = $this->_process_detail_price(
            "asal_other",
            $kurs,
            true,
        );
        $data["freight_ocean"] = $this->_process_detail_price(
            "freight_ocean",
            $kurs,
            true,
        );
        $data["freight_air"] = $this->_process_detail_price(
            "freight_air",
            $kurs,
            true,
        );
        $data["tuj_cfs"] = $this->_process_detail_price("tuj_cfs", $kurs, true);
        $data["tuj_doc"] = $this->_process_detail_price("tuj_doc", $kurs, true);
        $data["tuj_agency_fee"] = $this->_process_detail_price(
            "tuj_agency_fee",
            $kurs,
            true,
        );
        $data["tuj_handling"] = $this->_process_detail_price(
            "tuj_handling",
            $kurs,
            true,
        );
        $data["tuj_do"] = $this->_process_detail_price("tuj_do", $kurs, true);
        $data["tuj_admin"] = $this->_process_detail_price(
            "tuj_admin",
            $kurs,
            true,
        );
        $data["tuj_devanning"] = $this->_process_detail_price(
            "tuj_devanning",
            $kurs,
            true,
        );
        $data["tuj_fordwarding_fee"] = $this->_process_detail_price(
            "tuj_fordwarding_fee",
            $kurs,
            true,
        );
        $data["tuj_mechanics"] = $this->_process_detail_price(
            "tuj_mechanics",
            $kurs,
            true,
        );
        $data["tuj_other"] = $this->_process_detail_price(
            "tuj_other",
            $kurs,
            true,
        );
        $data["cust_clearance"] = $this->_process_detail_price(
            "cust_clearance",
            $kurs,
            true,
        );
        $data["cust_red_line"] = $this->_process_detail_price(
            "cust_red_line",
            $kurs,
            true,
        );
        $data["cust_handling"] = $this->_process_detail_price(
            "cust_handling",
            $kurs,
            true,
        );
        $data["cust_admin_fee"] = $this->_process_detail_price(
            "cust_admin_fee",
            $kurs,
            true,
        );
        $data["cust_pib_fee"] = $this->_process_detail_price(
            "cust_pib_fee",
            $kurs,
            true,
        );
        $data["cust_transfer"] = $this->_process_detail_price(
            "cust_transfer",
            $kurs,
            true,
        );
        $data["cust_storage"] = $this->_process_detail_price(
            "cust_storage",
            $kurs,
            true,
        );
        $data["oth_trucking"] = $this->_process_detail_price(
            "oth_trucking",
            $kurs,
            true,
        );
        $data["oth_handling"] = $this->_process_detail_price(
            "oth_handling",
            $kurs,
            true,
        );
        $data["oth_buruh"] = $this->_process_detail_price(
            "oth_buruh",
            $kurs,
            true,
        );
        $data["oth_adm"] = $this->_process_detail_price(
            "oth_adm",
            $kurs,
            true,
        );
        $data["oth_other"] = $this->_process_detail_price(
            "oth_other",
            $kurs,
            true,
        );
        $data["ins_nilai"] = $this->_process_detail_price(
            "ins_nilai",
            $kurs,
            true,
        );

        $this->md_forwarder->addDetailForwarder($data);

        /** LOG */
        addLog(
            "Approval Forwarder",
            "Penambahan Detail Harga Forwarder untuk ID: " . $data["id_app"],
        );
        ajaxReturnDie("success", "Berhasil Diajukan", true);
    }

    public function editDetailForwarder($param1)
    {
        grantAccessFor("all");
        $id = decrypt($param1);
        $dt = $this->md_forwarder->getByIdDetail($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die();
    }

    public function UpdateDetailForwarder()
    {
        grantAccessFor("all");

        // DEBUG: Validasi id_pelanggan ada dan bukan kosong
        $id_pelanggan_encrypted = $this->input->post("id_pelanggan");

        if (empty($id_pelanggan_encrypted)) {
            // Log untuk debugging
            error_log("ERROR UpdateDetailForwarder: id_pelanggan kosong. POST data: " . json_encode($this->input->post()));
            ajaxReturnDie("error", "Error: Field id_pelanggan kosong atau tidak dikirim dari form. Periksa console untuk detail", false);
        }

        $id = decrypt($id_pelanggan_encrypted);

        if (empty($id)) {
            error_log("ERROR UpdateDetailForwarder: Decrypt gagal untuk encrypted string: " . $id_pelanggan_encrypted);
            ajaxReturnDie("error", "Error: Gagal decrypt ID. ID mungkin corrupted atau key tidak sesuai. Encrypted: " . substr($id_pelanggan_encrypted, 0, 20) . "...", false);
        }

        $id_app = $this->input->post("id_app", true);

        if (empty($id_app)) {
            error_log("WARNING UpdateDetailForwarder: id_app kosong untuk id detail: " . $id);
        }

        // Data statis/non-harga
        $data["id_forwarder"] = $this->input->post("id_forwarder", true);
        $data["oth_do"] = $this->input->post("oth_do", true);
        $data["oth_storage"] = $this->input->post("oth_storage", true);
        $data["ins_jenis"] = $this->input->post("ins_jenis", true);
        $data["dipilih"] = $this->input->post("dipilih", true);
        $data["alasan"] = $this->input->post("alasan", true);
        $data["keterangan"] = $this->input->post("keterangan", true);
        $data["link_invoice"] = $this->input->post("link_invoice", true);
        $data["link_packing"] = $this->input->post("link_packing", true);
        $data["link_sph_for"] = $this->input->post("link_sph_for", true);
        $data["link_sph_ins"] = $this->input->post("link_sph_ins", true);

        // Input Ongkos Sesuai Mata Uang (Menggunakan Helper Lokal)
        $mata = $this->md_forwarder->getAppByID($id_app);
        $kurs = $mata[0]->kurs;

        $data["asal_thc"] = $this->_process_detail_price(
            "asal_thc",
            $kurs,
            false,
        );
        $data["asal_bl_fee"] = $this->_process_detail_price(
            "asal_bl_fee",
            $kurs,
            false,
        );
        $data["asal_vgm"] = $this->_process_detail_price(
            "asal_vgm",
            $kurs,
            false,
        );
        $data["asal_agaency_fee"] = $this->_process_detail_price(
            "asal_agaency_fee",
            $kurs,
            false,
        );
        $data["asal_handling_fee"] = $this->_process_detail_price(
            "asal_handling_fee",
            $kurs,
            false,
        );
        $data["asal_transportasi"] = $this->_process_detail_price(
            "asal_transportasi",
            $kurs,
            false,
        );
        $data["asal_loading"] = $this->_process_detail_price(
            "asal_loading",
            $kurs,
            false,
        );
        $data["asal_pickup"] = $this->_process_detail_price(
            "asal_pickup",
            $kurs,
            false,
        );
        $data["asal_seal"] = $this->_process_detail_price(
            "asal_seal",
            $kurs,
            false,
        );
        $data["asal_custom"] = $this->_process_detail_price(
            "asal_custom",
            $kurs,
            false,
        );
        $data["asal_shipping"] = $this->_process_detail_price(
            "asal_shipping",
            $kurs,
            false,
        );
        $data["asal_cfs"] = $this->_process_detail_price(
            "asal_cfs",
            $kurs,
            false,
        );
        $data["asal_dg"] = $this->_process_detail_price(
            "asal_dg",
            $kurs,
            false,
        );
        $data["asal_psa"] = $this->_process_detail_price(
            "asal_psa",
            $kurs,
            false,
        );
        $data["asal_other"] = $this->_process_detail_price(
            "asal_other",
            $kurs,
            false,
        );
        $data["freight_ocean"] = $this->_process_detail_price(
            "freight_ocean",
            $kurs,
            false,
        );
        $data["freight_air"] = $this->_process_detail_price(
            "freight_air",
            $kurs,
            false,
        );
        $data["tuj_cfs"] = $this->_process_detail_price(
            "tuj_cfs",
            $kurs,
            false,
        );
        $data["tuj_doc"] = $this->_process_detail_price(
            "tuj_doc",
            $kurs,
            false,
        );
        $data["tuj_agency_fee"] = $this->_process_detail_price(
            "tuj_agency_fee",
            $kurs,
            false,
        );
        $data["tuj_handling"] = $this->_process_detail_price(
            "tuj_handling",
            $kurs,
            false,
        );
        $data["tuj_do"] = $this->_process_detail_price("tuj_do", $kurs, false);
        $data["tuj_admin"] = $this->_process_detail_price(
            "tuj_admin",
            $kurs,
            false,
        );
        $data["tuj_devanning"] = $this->_process_detail_price(
            "tuj_devanning",
            $kurs,
            false,
        );
        $data["tuj_fordwarding_fee"] = $this->_process_detail_price(
            "tuj_fordwarding_fee",
            $kurs,
            false,
        );
        $data["tuj_mechanics"] = $this->_process_detail_price(
            "tuj_mechanics",
            $kurs,
            false,
        );
        $data["tuj_other"] = $this->_process_detail_price(
            "tuj_other",
            $kurs,
            false,
        );
        $data["cust_clearance"] = $this->_process_detail_price(
            "cust_clearance",
            $kurs,
            false,
        );
        $data["cust_red_line"] = $this->_process_detail_price(
            "cust_red_line",
            $kurs,
            false,
        );
        $data["cust_handling"] = $this->_process_detail_price(
            "cust_handling",
            $kurs,
            false,
        );
        $data["cust_admin_fee"] = $this->_process_detail_price(
            "cust_admin_fee",
            $kurs,
            false,
        );
        $data["cust_pib_fee"] = $this->_process_detail_price(
            "cust_pib_fee",
            $kurs,
            false,
        );
        $data["cust_transfer"] = $this->_process_detail_price(
            "cust_transfer",
            $kurs,
            false,
        );
        $data["cust_storage"] = $this->_process_detail_price(
            "cust_storage",
            $kurs,
            false,
        );
        $data["oth_trucking"] = $this->_process_detail_price(
            "oth_trucking",
            $kurs,
            false,
        );
        $data["oth_handling"] = $this->_process_detail_price(
            "oth_handling",
            $kurs,
            false,
        );
        $data["oth_buruh"] = $this->_process_detail_price(
            "oth_buruh",
            $kurs,
            false,
        );
        $data["oth_adm"] = $this->_process_detail_price(
            "oth_adm",
            $kurs,
            false,
        );
        $data["oth_other"] = $this->_process_detail_price(
            "oth_other",
            $kurs,
            false,
        );
        $data["ins_nilai"] = $this->_process_detail_price(
            "ins_nilai",
            $kurs,
            false,
        );

        // Update data dengan error handling
        $update_result = $this->md_forwarder->updateDetailForwarder($id, $data);

        // Cek apakah update berhasil
        if (!$update_result['success']) {
            if (isset($update_result['found']) && $update_result['found'] === false) {
                ajaxReturnDie("warning", "Data tidak ditemukan", false);
            }
            // Jika ada error database, return error
            if (!empty($update_result['error'])) {
                ajaxReturnDie("error", "Gagal Diperbarui: " . $update_result['error'], false);
            }
        }

        /** LOG */
        addLog("Approval Forwarder", "Edit Detail Harga Forwarder ID: " . $id);
        if (isset($update_result['changed']) && $update_result['changed'] === false) {
            ajaxReturnDie("success", "Data ditemukan, tetapi tidak ada perubahan nilai", true);
        }

        ajaxReturnDie("success", "Berhasil Diperbarui", true);
    }

    public function deleteDetail($id)
    {
        grantAccessFor("all");

        // Langsung hapus data
        $this->md_forwarder->deleteDetail($id);

        addLog("Approval Forwarder", "Menghapus Detail Forwarder");
        ajaxReturnDie("success", "berhasil dihapus", true);
    }

    public function ajukanApp()
    {
        grantAccessFor("all");

        $id_sp = $this->input->post("id");
        $data["status"] = 1;
        $this->md_forwarder->updateForwarder($id_sp, $data);

        $dataApp = $this->md_forwarder->getAppByID($id_sp);
        $kodeApp = $dataApp[0]->kode;

        //APPROVAL NOTIFIKASI
        $urlNotif = "[https://office.visiyosindo.id/forwarder/show/detail_data/$id_sp](https://office.visiyosindo.id/forwarder/show/detail_data/$id_sp)";

        //send notif wa
        $dataWa = [
            "idPenerima1" => 23,
            "idPenerima2" => "",
            "namaSurat" => "Approval Forwarder",
            "urlNotif" => $urlNotif,
            "penerima" => "Meilina Safitri",
            "perihal" => "",
            "kode" => $kodeApp,
        ];

        $this->notifWaAddSuratLink(1, $dataWa);

        addLog("Approval Forwarder", "Permintaan Diajukan Kode " . $kodeApp);
        ajaxReturnDie("success", "Data Berhasil Ditambahkan", true);
    }

    public function notifWaAddSuratLink($ulang, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju = $ambilDataPengaju[0]->nama;

        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                $idpenerima = $detail["idPenerima1"];
            } elseif ($i == 2) {
                $idpenerima = $detail["idPenerima2"];
            }

            $dataPenerima = $this->md_pengguna->getById($idpenerima);
            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set("display_errors", 0);
            //
            $nope = $dataPenerima[0]->no_hp;
            $dataWa = [
                "namaSurat" => urlencode($detail["namaSurat"]),
                "urlNotif" => urlencode($detail["urlNotif"]),
                "noPenerima" => $nope,
                "kodeSurat" => $detail["kode"],
                "namaPengaju" => $namaPengaju,
                "perihal" => urlencode($detail["perihal"]),
                "namaPenerima" => urlencode($detail["penerima"]),
            ];
            waSuratOpenLink($dataWa);
        }
    }

    public function ttd_setujui($param1 = "", $param2 = "", $param3 = "")
    {
        grantAccessFor("all");

        if ($param1 == "ttd_1") {
            $id_sp = $this->input->post("id");
            $data["status"] = 2;
            $data["ttd_1"] = 1;
            $this->md_forwarder->updateForwarder($id_sp, $data);

            //$dataApp    = $this->md_forwarder->getAppByID($id_sp);
            //$kodeApp     = $dataApp[0]->kode;

            $urlNotif = "[https://office.visiyosindo.id/forwarder/show/detail_data/$id_sp](https://office.visiyosindo.id/forwarder/show/detail_data/$id_sp)";

            $dataWa = [
                "id" => $id_sp,
                "idPenerima1" => "54",
                "idPenerima2" => "",
                "namaSurat" => "Approval Forwarder",
                "urlNotif" => $urlNotif,
                "penerima" => "Director",
                "ttd_sebelum1" => "Meilina Safitri",
                "ttd_sebelum2" => "",
                "ttd_sebelum3" => "",
                "ttd_sebelum4" => "",
            ];

            $this->notifWaAprovBa(1, 1, $dataWa);

            addLog(
                "Approval Forwarder",
                "Permintaan Approval Forwarder Disetujui",
            );
            ajaxReturnDie("success", "Data Berhasil Ditambahkan", true);
        } elseif ($param1 == "ttd_2") {
            $id_sp = $this->input->post("id");
            $data["status"] = 3;
            $data["ttd_2"] = 1;
            $this->md_forwarder->updateForwarder($id_sp, $data);

            $urlNotif = "[https://office.visiyosindo.id/forwarder/show/detail/$id_sp](https://office.visiyosindo.id/forwarder/show/detail/$id_sp)";

            $dataWa = [
                "id" => $id_sp,
                "idPenerima1" => "",
                "idPenerima2" => "",
                "namaSurat" => "Approval Forwarder",
                "urlNotif" => $urlNotif,
                "penerima" => "",
                "ttd_sebelum1" => "Meilina Safitri",
                "ttd_sebelum2" => "Bob Ariyos",
                "ttd_sebelum3" => "",
                "ttd_sebelum4" => "",
            ];

            $this->notifWaAprovBa(1, 2, $dataWa);

            addLog(
                "Approval Forwarder",
                "Permintaan Approval Forwarder Disetujui",
            );
            ajaxReturnDie("success", "Data Berhasil Ditambahkan", true);
        }
    }

    public function notifWaAprovBa($ulang, $param, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju = $this->md_forwarder->getAppByID($detail["id"]);
        $namaPengaju = $ambilDataPengaju[0]->pengaju;
        $kode = $ambilDataPengaju[0]->kode;
        $perihal = $ambilDataPengaju[0]->nama;
        $idpengaju = $ambilDataPengaju[0]->id_pengguna;

        //send notif wa
        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                //id pengaju surat
                if ($param == 2) {
                    $idpenerima = $idpengaju;
                } else {
                    $idpenerima = $detail["idPenerima1"];
                }
            } elseif ($i == 2) {
                //id ???
                $idpenerima = $detail["idPenerima2"];
            }

            $dataPenerima = $this->md_pengguna->getById($idpenerima);
            $nope = $dataPenerima[0]->no_hp;
            $dataWa = [
                "namaSurat" => urlencode($detail["namaSurat"]),
                "urlNotif" => urlencode($detail["urlNotif"]),
                "noPenerima" => $nope,
                "kodeSurat" => $kode,
                "namaPengaju" => urlencode($namaPengaju),
                "namaPenerima" => urlencode($detail["penerima"]),
                "perihal" => urlencode($perihal),
                "ttd_sebelum1" => urlencode($detail["ttd_sebelum1"]),
                "ttd_sebelum2" => urlencode($detail["ttd_sebelum2"]),
                "ttd_sebelum3" => urlencode($detail["ttd_sebelum3"]),
                "ttd_sebelum4" => urlencode($detail["ttd_sebelum4"]),
            ];

            if ($param == "1") {
                waSuratAprovOnProgVisilab($dataWa);
            } elseif ($param == "2") {
                waSuratAprovAllVisilab($dataWa);
            }
        }
    }

    public function ttd_tolak($param1 = "", $param2 = "", $param3 = "")
    {
        grantAccessFor("all");

        if ($param1 == "ttd_1") {
            $id_sp = $this->input->post("id");
            $data["status"] = 4;
            $data["ttd_1"] = 2;
            $this->md_forwarder->updateForwarder($id_sp, $data);

            //send notif wa
            $dataWa = [
                "namaSurat" => "Approval Forwarder",
                "id" => $id_sp,
                "idPenolak" => "23",
                "namaPenolak" => "Meilina Safitri",
            ];
            $this->notifWaRejectBa($dataWa);
            /** LOG */
            addLog(
                "Approval Forwarder",
                "Permintaan Approval Forwarder Ditolak",
            );
            ajaxReturnDie("success", "Data Berhasil Ditambahkan", true);
        } elseif ($param1 == "ttd_2") {
            $id_sp = $this->input->post("id");
            $data["status"] = 5;
            $data["ttd_2"] = 2;
            $this->md_forwarder->updateForwarder($id_sp, $data);

            //send notif wa
            $dataWa = [
                "namaSurat" => "Approval Forwarder",
                "id" => $id_sp,
                "idPenolak" => "54",
                "namaPenolak" => "Bob Ariyos",
            ];
            $this->notifWaRejectBa($dataWa);
            /** LOG */
            addLog(
                "Approval Forwarder",
                "Permintaan Approval Forwarder Ditolak",
            );
            ajaxReturnDie("success", "Data Berhasil Ditambahkan", true);
        }
    }

    public function notifWaRejectBa($detail)
    {
        $ambilDataPengaju = $this->md_forwarder->getAppByID($detail["id"]);
        $namaPengaju = $ambilDataPengaju[0]->pengaju;
        $kode = $ambilDataPengaju[0]->kode;
        $perihal = $ambilDataPengaju[0]->nama;
        $idpengaju = $ambilDataPengaju[0]->id_pengguna;

        //ambil nomor
        $dataPenerima = $this->md_pengguna->getById($idpengaju);
        $nope = $dataPenerima[0]->no_hp;
        $dataPenolak = $this->md_pengguna->getById($detail["idPenolak"]);
        $nopePenolak = $dataPenolak[0]->no_hp;

        $perihal = "";

        //send notif wa
        $dataWa = [
            "namaSurat" => $detail["namaSurat"],
            "noPenerima" => $nope,
            "kodeSurat" => $kode,
            "perihal" => urlencode($perihal),
            "namaPengaju" => $namaPengaju,
            "namaPenolak" => $detail["namaPenolak"],
            "noPenolak" => $nopePenolak,
        ];
        waSuratReject($dataWa);
    }

    public function print_page($param1 = "", $param2 = "")
    {
        grantAccessFor("all");

        if ($param1 == "detail") {
            $dataApp = $this->md_forwarder->getAppByID($param2);
            $detailApp = $this->md_forwarder->getDetailAppById($param2);

            $dt = [
                "title_pdf" => "ba",
                "object" => $param1,
                "data_aprv" => $this->md_forwarder->getAppByID($param2),
                "detail_aprv" => $this->md_forwarder->getDetailAppById($param2),
                "forwarderNames" => array_unique(
                    array_map(function ($item) {
                        return $item->nama_forwarder;
                    }, $detailApp),
                ),
            ];

            //load mpdf dan membuat page size
            $mpdf = new Mpdf(["format" => "A4"]);

            // $mpdf->SetMargins(0, 0, 0, true);

            //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
            $mpdf->AddPage("P", "", "", "", "", "5", "5", "4", "1");

            // filename dari pdf ketika didownload
            $file_pdf = "Approval Forwarder " . $dataApp[0]->pengaju;

            // page htmk yang akan di jadikan ke pdf
            $html = $this->load->view("pages/v_print/print_appfor", $dt, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output($file_pdf . ".pdf", "I");
        }
    }
}
