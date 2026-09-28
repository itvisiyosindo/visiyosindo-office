<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Aset extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_aset');
        $this->load->model('md_ekspedisi');
        $this->load->model('md_pengeluaran_barang');
        $this->load->model('md_pengiriman_stok');
    }

    function id_navbar()
    {
        $id_navbar = "inventory";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']          = $this->id_navbar();
        $page_data['page_name']      = 'v_ekspedisi';
        $page_data['page_title']    = 'Ekspedisi';
        $page_data['page_desc']      = 'Management Data Ekspedisi';
        $this->load->view('index', $page_data);
    }


    //==================================================================
    //==================================================================
    //=================================== Aset =========================
    //==================================================================
    //==================================================================


    public function show($param = "", $param2 = "", $param3 = "", $param4 = "")
    {
        grantAccessFor('all');
        if ($param == 'list') {
            $page_data['switch']          = $this->id_navbar();
            $page_data['page_name']     = 'aset/v_aset';
            $page_data['page_title']    = 'Aset';
            $page_data['page_desc']     = 'Master Data Aset Perusahaan';
            $page_data['kategori_aset'] = $this->md_aset->getWhereKategori();
            $this->load->view('index', $page_data);
        } else if ($param == 'detail') {
            $page_data['switch']          = $this->id_navbar();
            $page_data['data_aset']     = $this->md_aset->getAsetByWhere(['a.id' => decrypt($param2)]);
            $page_data['data_update']   = $this->md_aset->getUpdateById(['al.id_aset' => decrypt($param2)]);
            $this->load->model('md_pengguna');
            $page_data['list_pengguna'] = $this->md_pengguna->getAllPenggunaAktifList();

            // Ambil daftar surat Serah Terima Aset (jenis = 1)
            $this->db->select('id_serah, kode, tanggal');
            $this->db->from('surat_serah');
            $this->db->where('jenis', 1);
            $this->db->order_by('kode', 'DESC');
            $page_data['list_sta']      = $this->db->get()->result();

            $page_data['page_name']     = 'aset/v_detail_aset';
            $page_data['page_title']    = 'Aset';
            $page_data['page_desc']     = 'Detail Data Aset Perusahaan';
            $this->load->view('index', $page_data);
        }
    }









    //============================
    //============ Aset ==========
    //============================

    public function addAset()
    {
        grantAccessFor('all');

        $data['kategori']        = $this->input->post('kategori', TRUE);
        $data['kode']            = $this->input->post('kode', TRUE);
        $data['nama']            = $this->input->post('nama', TRUE);
        $data['nilai']           = $this->input->post('nilai', TRUE);
        $tgl_pembelian_input     = $this->input->post('tgl_pembelian', TRUE);
        $data['tgl_pembelian']   = !empty($tgl_pembelian_input) ? date_db_format($tgl_pembelian_input) : null;
        $data['link_pembelian']  = $this->input->post('link_pembelian', TRUE);
        $data['link_foto']       = $this->input->post('link_foto', TRUE);
        $data['keterangan']      = $this->input->post('keterangan', TRUE);
        $data['dijual']          = $this->input->post('dijual', TRUE) ?: '1';
        $tgl_dijual_input        = $this->input->post('tgl_dijual', TRUE);
        $data['tgl_dijual']      = !empty($tgl_dijual_input) ? date_db_format($tgl_dijual_input) : null;
        $data['posisi']          = $this->input->post('posisi', TRUE);


        $this->md_aset->addAset($data);

        //add log
        $aksi = 'Aset Perusahaan';
        $ket = 'Menambahkan data Aset - ' . $data['nama'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    }


    public function paginationAset()
    {
        grantAccessFor('all');

        $dt    = $this->md_aset->getAllAset();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {

            $id       = encrypt($row->id);
            if (sessPenggunaId() == 1 || sessPenggunaId() == 58 || sessPenggunaId() == 29) {
                $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="aset/deleteAset"><i class="bx bx-trash"></i></button>
                </div>';
            } else {
                $li_btn   = "";
            }



            if (!empty($row->link_pembelian)) {
                $link_pembelian = '<a href="' . htmlspecialchars($row->link_pembelian, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer"><i class="fas fa-link"></i> Pembelian</a>';
            } else {
                $link_pembelian = '';
            }

            if (!empty($row->link_foto)) {
                $link_foto = '<a href="' . htmlspecialchars($row->link_foto, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer"><i class="fas fa-link"></i> Foto Aset</a>';
            } else {
                $link_foto = '';
            }

            if ($row->dijual != 2) {
                $dijual = 'Belum Dijual';
            } else {
                $dijual = 'Sudah Dijual';
            }

            $kodeSN = '<a href="aset/show/detail/' . $id . '">' . (!empty($row->kode) ? $row->kode : '-') . '</a>';

            if ($row->nilai !== null && $row->nilai !== '') {
                $nilai_real = 'Rp ' . number_format($row->nilai, 0, ',', '.');
                $has_permission = (sessPenggunaId() == 58 || sessPenggunaId() == 29 || sessPenggunaId() == 107 || sessPenggunaId() == 54);
                $state = $has_permission ? 'real' : 'masked';
                $display_text = $has_permission ? $nilai_real : 'Rp ******';
                $icon = $has_permission ? 'fa-eye-slash' : 'fa-eye';

                $nilai = '<span class="asset-value-container" data-real="' . $nilai_real . '" data-masked="Rp ******" data-state="' . $state . '">
                            <span class="val-text">' . $display_text . '</span> 
                            <a href="javascript:;" class="toggle-value text-primary ml-1" title="Tampilkan/Sembunyikan"><i class="fas ' . $icon . '"></i></a>
                          </span>';
            } else {
                $nilai = '-';
            }


            $th = array();
            $th[] = ++$start . '.';
            $th[] = $kodeSN;
            $th[] = $row->kategori;
            $th[] = $row->nama;
            $th[] = $nilai;
            $th[] = (!empty($row->tgl_pembelian) && $row->tgl_pembelian !== '0000-00-00' && $row->tgl_pembelian !== '0000-00-00 00:00:00') ? date('d-m-Y ', strtotime($row->tgl_pembelian)) : '';
            $th[] = $link_pembelian;
            $th[] = $link_foto;
            $th[] = $row->keterangan;
            $th[] = $row->posisi;
            $th[] = $row->nama_pengguna;
            $th[] = $dijual;
            $th[] = (!empty($row->tgl_dijual) && $row->tgl_dijual !== '0000-00-00' && $row->tgl_dijual !== '0000-00-00 00:00:00') ? date('d-m-Y ', strtotime($row->tgl_dijual)) : '';
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }


    public function importAset()
    {
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

        if (isset($_FILES['berkas_excel']['name']) && in_array($_FILES['berkas_excel']['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES['berkas_excel']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['berkas_excel']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            for ($i = 2; $i < count($sheetData); $i++) {
                $id = isset($sheetData[$i][0]) ? $sheetData[$i][0] : null;

                $data = [
                    'kategori'        => isset($sheetData[$i][1]) ? $sheetData[$i][1] : '',
                    'kode'            => isset($sheetData[$i][2]) ? $sheetData[$i][2] : '',
                    'nama'            => isset($sheetData[$i][3]) ? $sheetData[$i][3] : '',
                    'nilai'           => isset($sheetData[$i][4]) ? $sheetData[$i][4] : '',
                    'tgl_pembelian'   => (!empty($sheetData[$i][5]) ? date('Y-m-d', strtotime($sheetData[$i][5])) : null),
                    'link_pembelian'  => isset($sheetData[$i][6]) ? $sheetData[$i][6] : '',
                    'link_foto'       => isset($sheetData[$i][7]) ? $sheetData[$i][7] : '',
                    'keterangan'      => isset($sheetData[$i][8]) ? $sheetData[$i][8] : '',
                    'dijual'          => isset($sheetData[$i][9]) ? $sheetData[$i][9] : '',
                    'tgl_dijual'      => (!empty($sheetData[$i][10]) ? date('Y-m-d', strtotime($sheetData[$i][10])) : null),
                    'posisi'          => isset($sheetData[$i][11]) ? $sheetData[$i][11] : ''
                ];


                if (empty($data['nama'])) {
                    continue;
                }

                $data['id'] = $id; // Jika ID diberikan di Excel
                $this->md_aset->addAset($data);
            }

            // Log aktivitas
            addlog('Aset Perusahaan', 'Melakukan Import Aset');
            redirect('/aset/show/list');
        }
    }


    public function editAset($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_aset->getByIdAset($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function updateAset()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_ekspedisi'));

        $data['kategori']        = $this->input->post('kategori', TRUE);
        $data['kode']            = $this->input->post('kode', TRUE);
        $data['nama']            = $this->input->post('nama', TRUE);
        $data['nilai']           = $this->input->post('nilai', TRUE);
        $tgl_pembelian_input     = $this->input->post('tgl_pembelian', TRUE);
        $data['tgl_pembelian']   = !empty($tgl_pembelian_input) ? date_db_format($tgl_pembelian_input) : null;
        $data['link_pembelian']  = $this->input->post('link_pembelian', TRUE);
        $data['link_foto']       = $this->input->post('link_foto', TRUE);
        $data['keterangan']      = $this->input->post('keterangan', TRUE);
        $data['dijual']          = $this->input->post('dijual', TRUE) ?: '1';
        $tgl_dijual_input        = $this->input->post('tgl_dijual', TRUE);
        $data['tgl_dijual']      = !empty($tgl_dijual_input) ? date_db_format($tgl_dijual_input) : null;
        $data['posisi']          = $this->input->post('posisi', TRUE);

        $this->md_aset->updateAset($id, $data);

        //add log
        $aksi = 'Aset Perusahaan';
        $ket = 'Mengupdate data Aset - ' . $data['nama'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Diupdate', TRUE);
    }



    public function deleteAset($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dtLaporan = $this->md_aset->getByIdDeleteAset($id);
        $detail    = $dtLaporan->nama;

        // Langsung hapus data
        $this->md_aset->deleteAset($id);


        //add log
        $aksi = 'Aset Perusahaan';
        $ket = 'Menghapus data Aset - ' . $detail;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Dihapus', TRUE);
    }





    public function printlaporan()
    {
        grantAccessFor('all');

        $page_data['page_name']     = 'v_print/print_aset';
        $page_data['page_title']    = 'Aset perusahaan';
        $page_data['page_desc']     = 'Aset perusahaan';
        $this->load->view('index', $page_data);

        $dt = [
            'title_pdf'    => 'Aset perusahaan' . ' ' . $this->input->get('namamarketing'),
            'data'        => $this->md_aset->getAllAsetByKategori($this->input->get('idmarketing')),
        ];

        //load mpdf dan membuat page size 
        $mpdf = new Mpdf(['format' => 'Tabloid']);

        //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
        $mpdf->AddPage('L', '', '', '', '', '', '', '5', '5');



        // filename dari pdf ketika didownload
        $file_pdf = 'Laporan Merketing ';

        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_aset', $dt, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');


        //add log
        $aksi = 'Aset Perusahaan';
        $ket = 'Cetak data Aset - ' . $this->input->get('namamarketing');
        addlog($aksi, $ket);
    }


    //====== END Aset =======

    public function addAsetLog()
    {
        grantAccessFor('all');

        $id_aset = decrypt($this->input->post('id_aset'));
        $id_pengguna = $this->input->post('id_pengguna', TRUE);
        $tanggal_input = $this->input->post('tanggal', TRUE);
        $id_sta = $this->input->post('id_sta', TRUE) ?: null;

        $next_id = $this->md_aset->getNextAsetLogId();

        $data = [
            'id'          => $next_id,
            'id_aset'     => $id_aset,
            'id_pengguna' => $id_pengguna,
            'id_sta'      => $id_sta,
            'tanggal'     => !empty($tanggal_input) ? date_db_format($tanggal_input) . ' ' . date('H:i:s') : date('Y-m-d H:i:s')
        ];

        $this->md_aset->addAsetLog($data);

        // Fetch asset info for logging
        $aset = $this->md_aset->getByIdDeleteAset($id_aset);
        $nama_aset = $aset ? $aset->nama : '';

        // Add log
        $aksi = 'Aset Perusahaan';
        $ket = 'Menambahkan History Aset - ' . $nama_aset;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'History Berhasil Ditambahkan', TRUE);
    }

    public function editAsetLog($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_aset->getAsetLogById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
            $row->id_aset = encrypt($row->id_aset);
            $row->tanggal = !empty($row->tanggal) ? date('d-m-Y', strtotime($row->tanggal)) : '';
        }
        echo json_encode($dt);
        die;
    }

    public function updateAsetLog()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_history'));
        $id_aset = decrypt($this->input->post('id_aset'));
        $id_pengguna = $this->input->post('id_pengguna', TRUE);
        $tanggal_input = $this->input->post('tanggal', TRUE);
        $id_sta = $this->input->post('id_sta', TRUE) ?: null;

        $data = [
            'id_pengguna' => $id_pengguna,
            'id_sta'      => $id_sta,
            'tanggal'     => !empty($tanggal_input) ? date_db_format($tanggal_input) . ' ' . date('H:i:s') : date('Y-m-d H:i:s')
        ];

        $this->md_aset->updateAsetLog($id, $data);

        // Fetch asset info for logging
        $aset = $this->md_aset->getByIdDeleteAset($id_aset);
        $nama_aset = $aset ? $aset->nama : '';

        // Add log
        $aksi = 'Aset Perusahaan';
        $ket = 'Mengupdate History Aset - ' . $nama_aset;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'History Berhasil Diupdate', TRUE);
    }

    public function deleteAsetLog($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);

        // Fetch current row for logging
        $dtLog = $this->md_aset->getAsetLogById($id);
        $id_aset = !empty($dtLog) ? $dtLog[0]->id_aset : null;

        $this->md_aset->deleteAsetLog($id);

        if ($id_aset) {
            $aset = $this->md_aset->getByIdDeleteAset($id_aset);
            $nama_aset = $aset ? $aset->nama : '';
            addlog('Aset Perusahaan', 'Menghapus History Aset - ' . $nama_aset);
        } else {
            addlog('Aset Perusahaan', 'Menghapus History Aset');
        }

        ajaxReturnDie('success', 'History Berhasil Dihapus', TRUE);
    }
}
