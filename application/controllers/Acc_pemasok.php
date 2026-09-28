<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;

/**
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property Md_acc_pemasok $md_acc_pemasok
 * @property Md_pengguna $md_pengguna
 */
class Acc_pemasok extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('md_acc_pemasok');
        $this->load->model('md_pengguna');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('encrypt_helper');

        // Access restriction
        if (!isAccountingUser()) {
            redirect(base_url('dashboard'));
        }
    }

    private function id_navbar()
    {
        return 'accounting';
    }

    public function index()
    {
        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'acc_pemasok/v_data_pemasok';
        $page_data['page_title'] = 'Data Pemasok';
        $page_data['page_desc'] = 'Manajemen Informasi dan Dokumen Pemasok';

        // Load lists for filtering dropdowns
        $page_data['status_list'] = $this->md_acc_pemasok->getStatusList();
        $page_data['tipe_list'] = $this->md_acc_pemasok->getTipeList();
        $page_data['product_list'] = $this->md_acc_pemasok->getProductList();
        $page_data['hospital_expo_list'] = $this->md_acc_pemasok->getHospitalExpoList();
        $page_data['countries'] = $this->md_acc_pemasok->getCountriesFromPemasok();

        $this->load->view('index', $page_data);
    }

    public function get_statistics()
    {
        $stats = $this->md_acc_pemasok->getPemasokStats();
        echo json_encode([
            'success' => true,
            'data' => [
                'total' => (int)($stats->total ?? 0),
                'aktif' => (int)($stats->aktif ?? 0),
                'tidak_aktif' => (int)($stats->tidak_aktif ?? 0),
                'referensi' => (int)($stats->referensi ?? 0),
                'baru' => (int)($stats->baru ?? 0)
            ]
        ]);
    }

    public function pagination()
    {
        $filters = [
            'nama_pemasok' => $this->input->post('filter_nama'),
            'id_status' => $this->input->post('filter_status'),
            'id_tipe' => $this->input->post('filter_tipe'),
            'negara' => $this->input->post('filter_negara'),
            'id_product' => $this->input->post('filter_product'),
            'id_hospital_expo' => $this->input->post('filter_hospital_expo'),
            'is_baru' => $this->input->post('is_baru')
        ];

        $dt = $this->md_acc_pemasok->getPemasokDatatable($filters);
        $start = intval($this->input->post('start') ?: 0);
        $data = [];

        foreach ($dt['data'] as $row) {
            $id_enc = encrypt($row->id);

            // Badges for Status
            $status_badge = '<span class="badge badge-secondary">' . htmlspecialchars($row->status_nama ?: '-') . '</span>';
            if ($row->id_status == 1) {
                $status_badge = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> ' . htmlspecialchars($row->status_nama) . '</span>';
            } elseif ($row->id_status == 2) {
                $status_badge = '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> ' . htmlspecialchars($row->status_nama) . '</span>';
            } elseif ($row->id_status == 3) {
                $status_badge = '<span class="badge badge-warning"><i class="fas fa-info-circle"></i> ' . htmlspecialchars($row->status_nama) . '</span>';
            }

            $actions = '
                <div class="table-actions">
                    <a href="' . base_url('acc_pemasok/detail/' . $id_enc) . '" class="btn btn-sm btn-info btn-view" title="Lihat/Edit Detail"><i class="fas fa-pencil-alt"></i> Detail</a>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id_enc . '"><i class="fas fa-trash-alt"></i></button>
                </div>';

            $th = [];
            $th[] = ++$start . '.';
            $th[] = '<strong><a href="' . base_url('acc_pemasok/detail/' . $id_enc) . '" class="text-dark">' . htmlspecialchars($row->nama_pemasok) . '</a></strong>';
            $th[] = $status_badge;
            $th[] = htmlspecialchars($row->tipe_nama ?: '-');
            $th[] = htmlspecialchars($row->negara ?: '-');
            $th[] = htmlspecialchars($row->product_list ?: '-');
            $th[] = $actions;
            $data[] = $th;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
    }

    public function detail($id_enc = '')
    {
        $id = !empty($id_enc) ? decrypt($id_enc) : null;
        $pemasok = null;

        if ($id) {
            $pemasok = $this->md_acc_pemasok->getPemasokById($id);
            if (!$pemasok) {
                show_404();
                return;
            }
        }

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'acc_pemasok/v_detail_pemasok';
        $page_data['page_title'] = $id ? 'Detail Pemasok: ' . $pemasok->nama_pemasok : 'Tambah Pemasok Baru';
        $page_data['page_desc'] = $id ? 'Ubah Informasi Detail Pemasok' : 'Isi Form Untuk Menambahkan Pemasok';
        
        $page_data['pemasok'] = $pemasok;
        $page_data['status_list'] = $this->md_acc_pemasok->getStatusList();
        $page_data['tipe_list'] = $this->md_acc_pemasok->getTipeList();
        $page_data['product_list'] = $this->md_acc_pemasok->getProductList();
        $page_data['hospital_expo_list'] = $this->md_acc_pemasok->getHospitalExpoList();

        $this->load->view('index', $page_data);
    }

    public function save()
    {
        $id_enc = $this->input->post('id_pemasok', TRUE);
        $id = !empty($id_enc) ? decrypt($id_enc) : null;

        $nama_pemasok = $this->input->post('nama_pemasok', TRUE);
        $id_status = $this->input->post('id_status', TRUE);
        $id_tipe = $this->input->post('id_tipe', TRUE);
        $negara = $this->input->post('negara', TRUE);
        $alamat = $this->input->post('alamat', TRUE);
        $kontak = $this->input->post('kontak', TRUE);
        $website = $this->input->post('website', TRUE);
        $npwp = $this->input->post('npwp', TRUE);
        $info_bank = $this->input->post('info_bank', TRUE);
        $info_partner = $this->input->post('info_partner', TRUE);
        $info_garansi = $this->input->post('info_garansi', TRUE);
        $info_pembayaran = $this->input->post('info_pembayaran', TRUE);
        $link_gdrive = $this->input->post('link_gdrive', TRUE);

        $tanggal_terdata_awal = $this->input->post('tanggal_terdata_awal', TRUE);
        $tanggal_loa_awal = $this->input->post('tanggal_loa_awal', TRUE);
        $tanggal_berakhir = $this->input->post('tanggal_berakhir', TRUE);

        if (empty($nama_pemasok) || empty($id_status) || empty($id_tipe) || empty($negara)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama, Status, Tipe, dan Negara wajib diisi']);
            return;
        }

        // Dynamic key-values (info_lainnya)
        $info_lainnya = [];
        $info_keys = $this->input->post('info_lainnya_key', TRUE) ?: [];
        $info_vals = $this->input->post('info_lainnya_value', TRUE) ?: [];
        foreach ($info_keys as $idx => $k) {
            $k = trim($k);
            if (!empty($k)) {
                $info_lainnya[$k] = trim($info_vals[$idx] ?? '');
            }
        }

        $data = [
            'nama_pemasok' => $nama_pemasok,
            'id_status' => intval($id_status),
            'id_tipe' => intval($id_tipe),
            'negara' => $negara,
            'alamat' => $alamat,
            'kontak' => $kontak,
            'website' => $website,
            'npwp' => $npwp,
            'info_bank' => $info_bank,
            'info_partner' => $info_partner,
            'info_garansi' => $info_garansi,
            'info_pembayaran' => $info_pembayaran,
            'info_lainnya' => !empty($info_lainnya) ? json_encode($info_lainnya) : NULL,
            'tanggal_terdata_awal' => !empty($tanggal_terdata_awal) ? date('Y-m-d', strtotime($tanggal_terdata_awal)) : NULL,
            'tanggal_loa_awal' => !empty($tanggal_loa_awal) ? date('Y-m-d', strtotime($tanggal_loa_awal)) : NULL,
            'tanggal_berakhir' => !empty($tanggal_berakhir) ? date('Y-m-d', strtotime($tanggal_berakhir)) : NULL,
            'link_gdrive' => $link_gdrive,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->trans_begin();

        if ($id) {
            $this->md_acc_pemasok->updatePemasok($id, $data);
        } else {
            $data['status_data'] = 1;
            $data['created_by'] = sessPenggunaId();
            $data['created_at'] = date('Y-m-d H:i:s');
            $id = $this->md_acc_pemasok->addPemasok($data);
        }

        // Many-to-many Product sync
        $products = $this->input->post('products', TRUE) ?: [];
        $this->md_acc_pemasok->syncProducts($id, $products);

        // Many-to-many Hospital Expo sync
        $hospital_expos = $this->input->post('hospital_expos', TRUE) ?: [];
        $this->md_acc_pemasok->syncHospitalExpo($id, $hospital_expos);

        // Sertifikasi documents
        $sert_rows = [];
        $sert_names = $this->input->post('sert_nama_dokumen', TRUE) ?: [];
        $sert_tgls = $this->input->post('sert_tanggal_dokumen', TRUE) ?: [];
        $sert_masas = $this->input->post('sert_masa_berlaku', TRUE) ?: [];
        $sert_links = $this->input->post('sert_link_dokumen', TRUE) ?: [];
        
        // Custom keys/values for sertifikasi
        $sert_custom_keys = $this->input->post('sert_custom_key', TRUE) ?: [];
        $sert_custom_vals = $this->input->post('sert_custom_val', TRUE) ?: [];

        foreach ($sert_names as $idx => $s_name) {
            $s_name = trim($s_name);
            if (empty($s_name)) continue;

            $custom_cols = [];
            // Parse custom columns for this row (stored in input hierarchy e.g. sert_custom_key[row_idx][col_idx])
            if (isset($sert_custom_keys[$idx]) && is_array($sert_custom_keys[$idx])) {
                foreach ($sert_custom_keys[$idx] as $c_idx => $c_key) {
                    $c_key = trim($c_key);
                    if (!empty($c_key)) {
                        $custom_cols[] = [
                            'label' => $c_key,
                            'value' => trim($sert_custom_vals[$idx][$c_idx] ?? '')
                        ];
                    }
                }
            }

            $sert_rows[] = [
                'nama_dokumen' => $s_name,
                'tanggal_dokumen' => $sert_tgls[$idx] ?? '',
                'masa_berlaku' => $sert_masas[$idx] ?? '',
                'link_dokumen' => $sert_links[$idx] ?? '',
                'kolom_lainnya' => $custom_cols
            ];
        }
        $this->md_acc_pemasok->saveSertifikasi($id, $sert_rows);

        // Agreement documents
        $agree_rows = [];
        $agree_names = $this->input->post('agree_nama_dokumen', TRUE) ?: [];
        $agree_tgls = $this->input->post('agree_tanggal_dokumen', TRUE) ?: [];
        $agree_masas = $this->input->post('agree_masa_berlaku', TRUE) ?: [];
        $agree_links = $this->input->post('agree_link_dokumen', TRUE) ?: [];
        foreach ($agree_names as $idx => $a_name) {
            $a_name = trim($a_name);
            if (empty($a_name)) continue;
            $agree_rows[] = [
                'nama_dokumen' => $a_name,
                'tanggal_dokumen' => $agree_tgls[$idx] ?? '',
                'masa_berlaku' => $agree_masas[$idx] ?? '',
                'link_dokumen' => $agree_links[$idx] ?? ''
            ];
        }
        $this->md_acc_pemasok->saveAgreement($id, $agree_rows);

        // Brochure documents
        $broch_rows = [];
        $broch_names = $this->input->post('broch_nama_dokumen', TRUE) ?: [];
        $broch_tgls = $this->input->post('broch_tanggal_dokumen', TRUE) ?: [];
        $broch_links = $this->input->post('broch_link_dokumen', TRUE) ?: [];
        foreach ($broch_names as $idx => $b_name) {
            $b_name = trim($b_name);
            if (empty($b_name)) continue;
            $broch_rows[] = [
                'nama_dokumen' => $b_name,
                'tanggal_dokumen' => $broch_tgls[$idx] ?? '',
                'link_dokumen' => $broch_links[$idx] ?? ''
            ];
        }
        $this->md_acc_pemasok->saveBrochure($id, $broch_rows);

        // Harga documents
        $harga_rows = [];
        $harga_names = $this->input->post('harga_nama_dokumen', TRUE) ?: [];
        $harga_tgls = $this->input->post('harga_tanggal_dokumen', TRUE) ?: [];
        $harga_masas = $this->input->post('harga_masa_berlaku', TRUE) ?: [];
        $harga_links = $this->input->post('harga_link_dokumen', TRUE) ?: [];
        foreach ($harga_names as $idx => $h_name) {
            $h_name = trim($h_name);
            if (empty($h_name)) continue;
            $harga_rows[] = [
                'nama_dokumen' => $h_name,
                'tanggal_dokumen' => $harga_tgls[$idx] ?? '',
                'masa_berlaku' => $harga_masas[$idx] ?? '',
                'link_dokumen' => $harga_links[$idx] ?? ''
            ];
        }
        $this->md_acc_pemasok->saveHarga($id, $harga_rows);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data pemasok']);
            return;
        }

        $this->db->trans_commit();
        
        $msg = $id_enc ? 'Perubahan data pemasok berhasil disimpan' : 'Pemasok baru berhasil ditambahkan';
        echo json_encode([
            'status' => 'success',
            'message' => $msg,
            'id' => encrypt($id)
        ]);
    }

    public function delete()
    {
        $id = decrypt($this->input->post('id', TRUE));
        if ($id) {
            $this->md_acc_pemasok->deletePemasok($id);
            echo json_encode(['status' => 'success', 'message' => 'Data pemasok berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID Pemasok tidak valid']);
        }
    }

    public function export_excel()
    {
        $filters = [
            'nama_pemasok' => $this->input->get('filter_nama', TRUE),
            'id_status' => $this->input->get('filter_status', TRUE),
            'id_tipe' => $this->input->get('filter_tipe', TRUE),
            'negara' => $this->input->get('filter_negara', TRUE),
            'id_product' => $this->input->get('filter_product', TRUE),
            'id_hospital_expo' => $this->input->get('filter_hospital_expo', TRUE),
            'is_baru' => $this->input->get('is_baru', TRUE)
        ];

        $list = $this->md_acc_pemasok->getPemasokForExport($filters);

        // Available columns to export (default is all if not specified)
        $selected_cols = $this->input->get('cols', TRUE);
        if (empty($selected_cols)) {
            $selected_cols = ['No', 'Nama Pemasok', 'Status', 'Tipe', 'Negara', 'Produk', 'Alamat', 'Kontak', 'Website', 'NPWP', 'Info Bank', 'Info Partner', 'Info Garansi', 'Info Pembayaran', 'Tgl LOA Awal', 'Tgl Berakhir'];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Data Pemasok');

        // Header Title
        $sheet->setCellValue('A1', 'DAFTAR REKAP DATA PEMASOK (SUPPLIER)');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->setCellValue('A2', 'Tanggal Cetak: ' . date('d F Y H:i'));

        $row_idx = 4;
        
        // Write selected headers
        $col = 'A';
        foreach ($selected_cols as $header) {
            $sheet->setCellValue($col . $row_idx, $header);
            $sheet->getStyle($col . $row_idx)->getFont()->setBold(true);
            $sheet->getStyle($col . $row_idx)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFC6DEFF');
            $col++;
        }

        $line = $row_idx + 1;
        $no = 1;

        foreach ($list as $row) {
            $col = 'A';
            foreach ($selected_cols as $header) {
                $val = '';
                switch ($header) {
                    case 'No':
                        $val = $no;
                        break;
                    case 'Nama Pemasok':
                        $val = $row->nama_pemasok;
                        break;
                    case 'Status':
                        $val = $row->status_nama;
                        break;
                    case 'Tipe':
                        $val = $row->tipe_nama;
                        break;
                    case 'Negara':
                        $val = $row->negara;
                        break;
                    case 'Produk':
                        $val = $row->product_list;
                        break;
                    case 'Alamat':
                        $val = $row->alamat;
                        break;
                    case 'Kontak':
                        $val = $row->kontak;
                        break;
                    case 'Website':
                        $val = $row->website;
                        break;
                    case 'NPWP':
                        $val = $row->npwp;
                        break;
                    case 'Info Bank':
                        $val = $row->info_bank;
                        break;
                    case 'Info Partner':
                        $val = $row->info_partner;
                        break;
                    case 'Info Garansi':
                        $val = $row->info_garansi;
                        break;
                    case 'Info Pembayaran':
                        $val = $row->info_pembayaran;
                        break;
                    case 'Tgl LOA Awal':
                        $val = $row->tanggal_loa_awal ? date('d-m-Y', strtotime($row->tanggal_loa_awal)) : '-';
                        break;
                    case 'Tgl Berakhir':
                        $val = $row->tanggal_berakhir ? date('d-m-Y', strtotime($row->tanggal_berakhir)) : '-';
                        break;
                }
                $sheet->setCellValue($col . $line, (string)$val);
                $col++;
            }
            $no++;
            $line++;
        }

        // Auto size columns
        $max_col = $sheet->getHighestColumn();
        $col_range = range('A', $max_col);
        foreach ($col_range as $colID) {
            $sheet->getColumnDimension($colID)->setAutoSize(true);
        }

        $filename = 'Data_Pemasok_' . date('Y-m-d_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $filters = [
            'nama_pemasok' => $this->input->get('filter_nama', TRUE),
            'id_status' => $this->input->get('filter_status', TRUE),
            'id_tipe' => $this->input->get('filter_tipe', TRUE),
            'negara' => $this->input->get('filter_negara', TRUE),
            'id_product' => $this->input->get('filter_product', TRUE),
            'id_hospital_expo' => $this->input->get('filter_hospital_expo', TRUE),
            'is_baru' => $this->input->get('is_baru', TRUE)
        ];

        $list = $this->md_acc_pemasok->getPemasokForExport($filters);

        $dt = [
            'title_pdf' => 'Daftar Pemasok (Supplier)',
            'data' => $list
        ];

        $mpdf = new Mpdf(['format' => 'A4-L']);
        $mpdf->AddPage('L', '', '', '', '', '10', '10', '10', '10');

        // Simple HTML layout for PDF table
        $html = '
        <html>
        <head>
            <style>
                body { font-family: sans-serif; font-size: 10pt; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                th, td { border: 1px solid #ccc; padding: 6px; font-size: 9pt; }
                th { background-color: #f2f2f2; font-weight: bold; }
                h2 { margin: 0; padding: 0; }
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <h2>DAFTAR DATA PEMASOK</h2>
            <div style="font-size: 9pt; color: #555; margin-bottom: 15px;">Dicetak Pada: ' . date('d-m-Y H:i') . '</div>
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Pemasok</th>
                        <th>Status</th>
                        <th>Tipe</th>
                        <th>Negara</th>
                        <th>Produk</th>
                        <th>Kontak</th>
                        <th>Tgl LOA Awal</th>
                        <th>Tgl Berakhir</th>
                    </tr>
                </thead>
                <tbody>';
        
        $no = 1;
        foreach ($list as $row) {
            $loa = $row->tanggal_loa_awal ? date('d-m-Y', strtotime($row->tanggal_loa_awal)) : '-';
            $akhir = $row->tanggal_berakhir ? date('d-m-Y', strtotime($row->tanggal_berakhir)) : '-';
            $html .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td><strong>' . htmlspecialchars($row->nama_pemasok) . '</strong></td>
                    <td>' . htmlspecialchars($row->status_nama) . '</td>
                    <td>' . htmlspecialchars($row->tipe_nama) . '</td>
                    <td>' . htmlspecialchars($row->negara) . '</td>
                    <td>' . htmlspecialchars($row->product_list) . '</td>
                    <td>' . htmlspecialchars($row->kontak ?: '-') . '</td>
                    <td class="text-center">' . $loa . '</td>
                    <td class="text-center">' . $akhir . '</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        $mpdf->WriteHTML($html);
        $mpdf->Output('Data_Pemasok_' . date('Y-m-d') . '.pdf', 'I');
    }

    public function get_countries()
    {
        $countries = [];
        $url = 'https://restcountries.com/v3.1/all?fields=name';
        
        if (function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 3,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            ]);
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            
            if ($httpCode == 200 && !empty($response)) {
                $data = json_decode($response, true);
                if (is_array($data)) {
                    foreach ($data as $item) {
                        if (isset($item['name']['common'])) {
                            $countries[] = $item['name']['common'];
                        }
                    }
                }
            }
        }
        
        if (empty($countries)) {
            $countries = [
                "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan",
                "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi",
                "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia",
                "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica", "Dominican Republic",
                "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia",
                "Fiji", "Finland", "France",
                "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana",
                "Haiti", "Honduras", "Hungary",
                "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Ivory Coast",
                "Jamaica", "Japan", "Jordan",
                "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan",
                "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg",
                "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar",
                "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway",
                "Oman",
                "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal",
                "Qatar",
                "Romania", "Russia", "Rwanda",
                "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria",
                "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu",
                "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan",
                "Vanuatu", "Vatican City", "Venezuela", "Vietnam",
                "Yemen",
                "Zambia", "Zimbabwe"
            ];
        }
        
        sort($countries);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $countries
        ]);
        exit;
    }
}
