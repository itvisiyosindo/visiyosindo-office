<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use function GuzzleHttp\json_decode;

defined('BASEPATH') or exit('No direct script access allowed');

class calonpelanggan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Md_calonpelanggan');
        $this->load->model('md_pelangganan');
        $this->load->model('md_pengguna');
        $this->load->model('md_prov_kota');
        $this->load->helper('encrypt_helper');
    }

    function id_navbar()
    {
        $id_navbar = "marketing";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch'] = $this->id_navbar();
        $page_data['id_pengguna'] = sessPenggunaId();
        $page_data['kategorimodality']  = $this->md_pelangganan->getMasterKategoriModality();
        $page_data['jenisfakturpajak']  = $this->Md_calonpelanggan->getMasterJenisFakturPajak();
        $page_data['syaratpembayaran']  = $this->Md_calonpelanggan->getMasterSyaratPembayaran();
        $page_data['kodecaloncustomer'] = $this->Md_calonpelanggan->getCalonNoUrut() + 1;
        $page_data['kodecustomer'] = $this->Md_calonpelanggan->getNoUrut() + 1;
        $page_data['masterberkaspelanggan'] = $this->Md_calonpelanggan->getMasterBerkasPelanggan();
        $page_data['jumlahberkaspelanggan'] = $this->Md_calonpelanggan->getJumlahBerkasPelanggan();
        $page_data['masterberkaslainpelanggan'] = $this->Md_calonpelanggan->getMasterBerkasLainPelanggan();
        $page_data['jumlahberkaslainpelanggan'] = $this->Md_calonpelanggan->getJumlahBerkasLainPelanggan();
        $page_data['nama_marketing']  = $this->md_pengguna->getPenggunaMarketing();
        $page_data['provinsi']  = $this->md_prov_kota->getAllProvinsi();
        $page_data['page_name']     = 'marketing/v_calonpelanggan';
        $page_data['page_title']    = 'CalonPelanggan';
        $page_data['page_desc']     = 'Management Data Calon Pelanggan';

        $this->load->view('index', $page_data);
    }

    public function addpelanggan()
    {
        $page_data['switch'] = $this->id_navbar();

        if (empty($this->input->post('tanggalregistrasi'))) {
            ajaxReturnDie('error', 'Tanggal Registrasi tidak boleh kosong..!!');
        } elseif (empty($this->input->post('namapelanggan'))) {
            ajaxReturnDie('error', 'Nama Pelanggan tidak boleh kosong..!!');
        } elseif (empty($this->input->post('nik'))) {
            ajaxReturnDie('error', 'NIK tidak boleh kosong..!!');
        } elseif (empty($this->input->post('nonpwp'))) {
            ajaxReturnDie('error', 'NPWP tidak boleh kosong..!!');
        } elseif (empty($this->input->post('namanpwp'))) {
            ajaxReturnDie('error', 'Nama NPWP tidak boleh kosong..!!');
        } elseif (empty($this->input->post('jenisfakturpajak'))) {
            ajaxReturnDie('error', 'Jenis Faktur Pajak tidak boleh kosong..!!');
        } elseif (empty($this->input->post('syaratpembayaran'))) {
            ajaxReturnDie('error', 'Syarat Pembayaran tidak boleh kosong..!!');
        } elseif (empty($this->input->post('pengirimandokumen'))) {
            ajaxReturnDie('error', 'Pengiriman Dokumen tidak boleh kosong..!!');
        } elseif (empty($this->input->post('statuspiutang'))) {
            ajaxReturnDie('error', 'Status Piutang tidak boleh kosong..!!');
        } elseif (empty($this->input->post('limitpiutang'))) {
            ajaxReturnDie('error', 'Limit Piutang tidak boleh kosong..!!');
        } elseif (empty($this->input->post('alamatpengiriman'))) {
            ajaxReturnDie('error', 'Alamat Pengiriman tidak boleh kosong..!!');
        } elseif (empty($this->input->post('alamatpenagihan'))) {
            ajaxReturnDie('error', 'Alamat Penagihan tidak boleh kosong..!!');
        } elseif (empty($this->input->post('keterangan'))) {
            ajaxReturnDie('error', 'Keterangan tidak boleh kosong..!!');
        }

        $kode = sprintf('%03d', $this->Md_calonpelanggan->getNoUrut() + 1);
        $data['idcalonpelanggan']    = decrypt($this->input->post('idcalon'));

        $data['kodecaloncustomer']    = $this->input->post('kodecaloncustomer2');
        $data['kodecustomer']    = substr($this->input->post('kodecustomer'), 0, 9) . '/' . $kode; //$this->input->post('kodecustomer');
        $data['namapelanggan']  = $this->input->post('namapelanggan');
        $data['tanggalregistrasi'] = $this->input->post('tanggalregistrasi');
        $data['nik'] = $this->input->post('nik');
        $data['nonpwp']    = $this->input->post('nonpwp');
        $data['namanpwp'] = $this->input->post('namanpwp');
        $data['jenisfakturpajak']    = $this->input->post('jenisfakturpajak');
        $data['syaratpembayaran']    = $this->input->post('syaratpembayaran');
        $data['pengirimandokumen'] = $this->input->post('pengirimandokumen');
        $data['statuspiutang']    = $this->input->post('statuspiutang');
        $data['limitpiutang'] = $this->input->post('limitpiutang');
        $data['alamatpengiriman']    = $this->input->post('alamatpengiriman');
        $data['alamatpenagihan'] = $this->input->post('alamatpenagihan');
        $data['keterangan'] = $this->input->post('keterangan');
        $data['pengguna_id'] = sessPenggunaId();

        //ajaxReturnDie('error', json_encode( $this->input->post('chkberkas[]')));
        $jumlahberkas = $this->input->post('jumlahberkas');
        // $idberkas = $this->input->post('chkberkas[]');
        $linkberkas = $this->input->post('linkberkas[]');


        $jumlahberkaslain = $this->input->post('jumlahberkaslainnya');
        $linkberkaslain = $this->input->post('linkberkaslainnya[]');

        $this->Md_calonpelanggan->addpelanggan($data);

        $insert = $this->db->insert_id();
        //ajaxReturnDie('error', json_encode($insert));

        $databerkas = array();
        $databerkaslainnya = array();

        for ($index = 0; $index < $jumlahberkas; $index++) {
            if ($linkberkas[$index] != '') {
                $databerkas[] = array(
                    "idberkas" => $index + 1,
                    "linkberkas" => $linkberkas[$index],
                    "kodecustomer" => $data['kodecustomer'],
                    "idcust" => $insert
                );
            }
        }

        for ($i = 0; $i < $jumlahberkaslain; $i++) {
            if ($linkberkaslain[$i] != '') {
                $databerkaslainnya[] = array(
                    "idberkas" => $i + 1,
                    "linkberkas" => $linkberkaslain[$i],
                    "kodecustomer" => $data['kodecustomer'],
                    "idcust" => $insert
                );
            }
        }

        if (count($databerkas) > 0) {
            $this->Md_calonpelanggan->addberkasdokumenpelanggan($databerkas);
        }
        if (count($databerkas) > 0) {
            $this->Md_calonpelanggan->addberkasdokumenlainpelanggan($databerkaslainnya);
        }


        $this->Md_calonpelanggan->updatecalonmejadipelanggan($data['idcalonpelanggan']);

        // /** LOG */
        addLog('Penambahan Pelanggan', 'Menambah Pelanggan "' . $data['kodecustomer'] . '"');
        ajaxReturnDie('success', 'Pelanggan berhasil ditambahkan', 'reload_table');
    }

    public function add()
    {
        $page_data['switch'] = $this->id_navbar();

        $kode = sprintf('%03d', $this->Md_calonpelanggan->getCalonNoUrut() + 1);
        $kodeprov = $this->input->post('provinsi');
        $kodekota = sprintf('%02d', $this->input->post('kota'));
        $kelas = $this->input->post('kelascustomer');

        $data['kodecaloncustomer']    = $kodeprov . '/' . $kodekota . '/' . $kelas . '/' . $kode; //$this->input->post('kodecaloncustomer');
        $data['namacaloncustomer']  = $this->input->post('namacaloncustomer');
        $data['statuscaloncustomer'] = $this->input->post('statuscaloncustomer');
        $data['tipecustomer'] = $this->input->post('tipecustomer');
        $data['kelascustomer']    = $this->input->post('kelascustomer');
        $data['statuscaloncustomer'] = 1;
        $data['namapihakketiga'] = $this->input->post('namapihakketiga');
        $data['provinsi']    = $this->input->post('provinsi');
        $data['kota']    = $this->input->post('kota');
        $data['alamatcaloncustomer'] = $this->input->post('alamatcaloncustomer');
        $data['email']    = $this->input->post('email');
        $data['website'] = $this->input->post('website');
        $data['idmarketing'] = sessPenggunaId();
        $data['pengguna_id'] = sessPenggunaId();


        $this->Md_calonpelanggan->addcalon($data);
        $insert = $this->db->insert_id();

        $result = array();
        foreach ($_POST['namapic'] as $key => $val) {
            $result[] = array(
                'namapic' => $_POST['namapic'][$key],
                'jabatanpic' => $_POST['jabatanpic'][$key],
                'teleponpic' => $_POST['teleponpic'][$key],
                'idpic' => $insert
            );
        }
        $this->Md_calonpelanggan->addpiccalon($result);

        /** LOG */
        addLog('Penambahan Calon Pelanggan', 'Menambah Calon Pelanggan "' . $data['namacaloncustomer'] . '"');
        ajaxReturnDie('success', 'Calon Pelanggan berhasil ditambahkan', 'reload_table');
    }
    public function addpic()
    {

        //ajaxReturnDie('error', encrypt(0));
        grantAccessFor('all');

        $data['idpic'] = decrypt($this->input->post('idpic'));
        $data['namapic'] = $this->input->post('namapic');
        $data['jabatanpic'] = $this->input->post('jabatanpic');
        $data['teleponpic'] = $this->input->post('teleponpic');

        $this->md_pelangganan->addpic($data);

        // /** LOG */
        addLog('Penambahan PIC Pelanggan', 'Menambah PIC Pelanggan "' . $data['namapic'] . '"');
        ajaxReturnDie('success', 'PIC Pelanggan berhasil ditambahkan', 'reload_table');
    }

    public function addmodality()
    {

        //ajaxReturnDie('error', $this->input->post('idpelanggan'));
        grantAccessFor('all');

        $data['id_pelanggan'] = decrypt($this->input->post('idpelanggan'));
        $data['kodepelanggan'] = $this->input->post('kodepelanggan');
        $data['kategori'] = $this->input->post('kategori');
        $data['merk'] = $this->input->post('merk');
        $data['nama'] = $this->input->post('nama');
        $data['pengguna_id'] = sessPenggunaId();;

        $this->md_pelangganan->addModality($data);

        // /** LOG */
        addLog('Penambahan Pelanggan', 'Menambah Modality Pelanggan "' . $data['nama'] . '"');
        ajaxReturnDie('success', 'Modality Pelanggan berhasil ditambahkan', 'reload_table');
    }

    function add_ajax_kota($id_prov)
    {
        $query = $this->db->get_where('kota', array('id_prov' => $id_prov));
        $data = "<option value=''>- Pilih Kabupaten/Kota -</option>";
        foreach ($query->result() as $value) {
            $data .= "<option value='" . $value->id . "'>" . $value->tipe . ' ' . $value->nama . "</option>";
        }
        echo $data;
    }



    function add_ajax_kota2($idprov, $id_kota)
    {
        $query = $this->db->get_where('kota', array('id_prov' => $idprov));
        $data = "<option value=''>- Pilih Kabupaten/Kota -</option>";
        foreach ($query->result() as $value) {
            if ($value->id == $id_kota) {
                $data .= "<option value='" . $value->id . "' selected>" . $value->tipe . ' ' . $value->nama . "</option>";
            } else {
                $data .= "<option value='" . $value->id . "'>" . $value->tipe . ' ' . $value->nama . "</option>";
            }
        }
        echo $data;
    }

    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->Md_calonpelanggan->getAllCalonPelangganById($id);

        if (isset($dt[0]))
            echo json_encode($dt[0]);
        else
            ajaxReturnDie('error', 'error');
        die;
    }

    public function update()
    {

        grantAccessFor('all');

        $id =  $this->input->post('id');
        $data['kodecaloncustomer'] = $this->input->post('kodecaloncustomer');
        $data['namacaloncustomer']  = $this->input->post('namacaloncustomer');
        $data['tipecustomer'] = $this->input->post('tipecustomer');
        $data['kelascustomer']    = $this->input->post('kelascustomer');
        $data['provinsi']    = $this->input->post('provinsi');
        $data['kota']    = $this->input->post('kota');
        $data['alamatcaloncustomer'] = $this->input->post('alamatcaloncustomer');
        $data['email']    = $this->input->post('email');
        $data['website'] = $this->input->post('website');
        $data['pengguna_id'] = sessPenggunaId();

        $this->Md_calonpelanggan->update($id, $data);

        /** LOG */
        addLog('Update Pelanggan', 'Memperbarui data Pelanggan "' . $data['namacaloncustomer'] . '"');
        ajaxReturnDie('success', 'Data pelanggan ' . $data['namacaloncustomer'] . ' berhasil diperbarui', 'reload_table');
    }

    public function delete($id)
    {
        grantAccessFor('all');
        $data = explode(",", decryptvym($id));
        $pengguna_id = sessPenggunaId();
        $this->Md_calonpelanggan->hapus($data[0], $pengguna_id);

        addLog('Menghapus Calon Pelanggan', 'Menghapus Calon Pelanggan ' . $data[1]);
        ajaxReturnDie('success', 'Calon Pelanggan ' . $data[1] . ' Berhasil Dihapus', 'reload_table');
    }
    public function deletemodality($par)
    {
        grantAccessFor('all');
        $data = explode(",", decryptvym($par));
        $pengguna_id = sessPenggunaId();

        $this->Md_calonpelanggan->hapusmodality($data[0], $pengguna_id);

        addLog('Menghapus Modality', 'Menghapus Modality ' . $data[2]);
        ajaxReturnDie('success', 'Modality ' . $data[2] . ' Berhasil Dihapus', 'reload_table');
    }
    public function editpic($param1)
    {
        grantAccessFor('all');
        $par = explode(",", decryptvym($param1));
        $data['id'] = $par[0];
        $data['namapic'] = $par[1];
        $data['jabatanpic'] = $par[2];
        $data['teleponpic'] = $par[3];
        echo json_encode($data);
        die;
    }
    public function updatepic()
    {

        grantAccessFor('all');

        $id =  decrypt($this->input->post('id'));
        $data['namapic'] = $this->input->post('namapic');
        $data['jabatanpic']  = $this->input->post('jabatanpic');
        $data['teleponpic'] = $this->input->post('teleponpic');
        $data['pengguna_id'] = sessPenggunaId();

        $this->Md_calonpelanggan->updatepic($id, $data);

        /** LOG */
        addLog('Update PIC', 'Memperbarui data PIC "' . $data['namapic'] . '"');
        ajaxReturnDie('success', 'Data PIC ' . $data['namapic'] . ' berhasil diperbarui', 'reload_table');
    }
    public function hapuspic($id)
    {
        grantAccessFor('all');
        $data = explode(",", decryptvym($id));
        $pengguna_id = sessPenggunaId();
        $this->Md_calonpelanggan->hapuspic($data[0], $pengguna_id);

        addLog('Menghapus Calon Pelanggan', 'Menghapus Calon Pelanggan ' . $data[1]);
        ajaxReturnDie('success', 'Calon Pelanggan ' . $data[1] . ' Berhasil Dihapus', 'reload_table');
    }

    public function showdetail($id)
    {
        grantAccessFor('all');
        $page_data['switch'] = $this->id_navbar();
        $page_data['id_pengguna'] = sessPenggunaId();
        $dt    =  $this->Md_calonpelanggan->getAllCalonPelangganById(decrypt($id));
        $page_data['datacaloncustomer'] = $dt[0];
        $page_data['pic']        = $this->Md_calonpelanggan->getAllPICCalonPelangganById(decrypt($id));
        $page_data['page_name']       = 'marketing/v_calonpelanggandetail';
        $page_data['page_title']      = 'Detail Calon Pelanggan';
        $page_data['page_desc']       = 'Data Calon Pelanggan';
        $this->load->view('index', $page_data);
    }

    public function pagination()
    {
        grantAccessFor('all');
        $pengguna_id = sessPenggunaId();
        if (isAdmin() || isHrd() || isCRO() || $pengguna_id == '105' || $pengguna_id == '745' || $pengguna_id == '755') {
            $dt    = $this->Md_calonpelanggan->getAllCalonPelanggan($pengguna_id);
        } else {
            $dt    = $this->Md_calonpelanggan->getAllCalonPelangganByPenggunaID($pengguna_id);
        }
        $start = $this->input->post('start');
        $data  = array();
        $index = 1;
        foreach ($dt['data'] as $row) {
            $id           = encrypt($row->id);
            $myObj = $row->id . "," . $row->namacaloncustomer;
            $parJSON = encryptvym($myObj);
            $li_btn       = '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $parJSON . '" data-object="calonpelanggan/delete/' . $parJSON . '"><i class="bx bx-trash"></i></button>
                </div>';
            // $li_btnstatus = '
            //     <div class="btn-group" role="group" aria-label="First group">

            //        <button type="button" class="btn btn-sm btn-warning btn-reset" data-id="' . $id . '"><i class="bx bx-home"></i></button>
            //     </div>';    
            //$statuscalon	 = '<button type="button" class="btn btn-sm btn-success btn-status" data-id="status'. $id .'"><i class="icons icon-plus"></i>&nbsp;Pelanggan</button>&nbsp&nbsp';
            $statuscalon      = '<a href="javascript:;" id="btnstatus' . $id . '" class="btn btn-sm btn-success" "><i class="icons icon-plus"></i>&nbsp;Pelanggan</a>';
            $ModalityBtn      = '<div class="btn-group" role="group" aria-label="First group"> 
                                    <a href="javascript:;" id="btnmodality' . $id . '" class="btn btn-sm btn-dark" "><i class="icons icon-plus">Modality</i></a>
                                </div>';
            $kodecaloncustomer    = '<a href="calonpelanggan/showdetail/' . encrypt($row->id) . '">' . $row->kodecaloncustomer . '</a>';
            $th = array();
            $th[] = $index;
            $th[] = encrypt($row->id);
            $th[] = $row->kodecaloncustomer;
            $th[] = $kodecaloncustomer;
            $th[] = $row->namamarketing;
            $th[] = $row->namacaloncustomer;
            $th[] = $row->alamatcaloncustomer;
            $th[] = $row->provinsi;
            $th[] = $row->kota;
            $th[] = '<i class="fa fa-clock-o"></i> ' . date('Y-m-d', strtotime($row->data_created));
            if ($row->statuscaloncustomer == 1) {
                $th[] = $statuscalon;
            }
            $th[] = $ModalityBtn;
            if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || $pengguna_id == '755') {
                $th[] = $li_btn;
            }
            $data[] = $th;
            $index++;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
    }

    public function paginationmodality($id_pelanggan)
    {
        grantAccessFor('all');
        //$id = decrypt($id_pelanggan);
        if ($id_pelanggan != null) {
            $dt    = $this->Md_calonpelanggan->getAllModalityCalonPelangganan($id_pelanggan);
            $data  = array();
            $index = 1;
            foreach ($dt['data'] as $row) {
                $th = array();
                $id           = encrypt($row->id);
                $myObj = $row->id . "," . $row->idkategori . "," . $row->nama;
                $parJSON = encryptvym($myObj);
                $li_btn       = '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-danger btn-deletemodality" title="Hapus Data" data-id="' . $parJSON . '" data-object="pelangganan/delete/' . $parJSON . '"><i class="bx bx-trash"></i></button>
                </div>';
                $th[] = $index;
                $th[] = $row->kategori;
                $th[] = $row->nama;
                $th[] = $row->merk;
                $th[] = '<i class="fa fa-clock-o"></i> ' . date('Y-m-d', strtotime($row->data_created));
                $th[] = $li_btn;
                $data[] = $th;
                $index++;
            }

            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else {
            $dt['data'] = '';
            echo json_encode($dt);
            die;
        }
    }

    public function paginationpic($id_pelanggan)
    {
        grantAccessFor('all');
        if ($id_pelanggan != null) {
            $dt    = $this->Md_calonpelanggan->getAllPICCalonPelangganan($id_pelanggan);
            $data  = array();
            $index = 1;
            foreach ($dt['data'] as $row) {
                $th = array();
                $myObj = $row->id . "," . $row->namapic;
                $parJSON = encryptvym($myObj);
                //$dataedit  = array();
                //$dataedit['id'] = encrypt($row->id);
                //$dataedit['namapic'] = $row->namapic;
                //$dataedit['jabatanpic'] = $row->jabatanpic;
                //$dataedit['teleponpic'] = $row->teleponpic;
                $myObjedit = encrypt($row->id) . ',' . $row->namapic . ',' . $row->jabatanpic . ',' . $row->teleponpic;
                $parJSONedit = encryptvym($myObjedit);
                $li_btn       = '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" title="Edit Data" data-id="' . $parJSONedit . '"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $parJSON . '" data-object="calonpelanggan/hapuspic/' . $parJSON . '"><i class="bx bx-trash"></i></button>
                </div>';
                $th[] = $index;
                $th[] = $row->namapic;
                $th[] = $row->jabatanpic;
                $th[] = $row->teleponpic;
                $th[] = $li_btn;
                $data[] = $th;
                $index++;
            }

            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else {
            $dt['data'] = '';
            echo json_encode($dt);
            die;
        }
    }


    public function exportlaporan()
    {

        $data = $this->Md_calonpelanggan->getAllCalonPelangganByTGL($this->input->get('idmarketing'), $this->input->get('tglawal'), $this->input->get('tglakhir'));



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

        $sheet->setCellValue('A1', "DATA CALON PELANGGAN " . strtoupper($this->input->get('namamarketing'))); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Kode');
        $sheet->setCellValue('C4', 'Nama Marketing');
        $sheet->setCellValue('D4', 'Nama Calon Pelanggan');
        $sheet->setCellValue('E4', 'Tipe');
        $sheet->setCellValue('F4', 'Alamat');
        $sheet->setCellValue('G4', 'Kota');
        $sheet->setCellValue('H4', 'Provinsi');
        $sheet->setCellValue('I4', 'Tanggal');
        $sheet->setCellValue('J4', 'Kategori Modality');
        $sheet->setCellValue('K4', 'Merk Modality');
        $sheet->setCellValue('L4', 'Nama Modality');
        $sheet->setCellValue('M4', 'Nama PIC');
        $sheet->setCellValue('N4', 'Jabatan PIC');
        $sheet->setCellValue('O4', 'Telepon PIC');
        $sheet->setCellValue('P4', 'Email');
        $sheet->setCellValue('Q4', 'Website');

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A4')->applyFromArray($style_col);
        $sheet->getStyle('B4')->applyFromArray($style_col);
        $sheet->getStyle('C4')->applyFromArray($style_col);
        $sheet->getStyle('D4')->applyFromArray($style_col);
        $sheet->getStyle('E4')->applyFromArray($style_col);
        $sheet->getStyle('F4')->applyFromArray($style_col);
        $sheet->getStyle('G4')->applyFromArray($style_col);
        $sheet->getStyle('H4')->applyFromArray($style_col);
        $sheet->getStyle('I4')->applyFromArray($style_col);
        $sheet->getStyle('J4')->applyFromArray($style_col);
        $sheet->getStyle('K4')->applyFromArray($style_col);
        $sheet->getStyle('L4')->applyFromArray($style_col);
        $sheet->getStyle('M4')->applyFromArray($style_col);
        $sheet->getStyle('N4')->applyFromArray($style_col);
        $sheet->getStyle('O4')->applyFromArray($style_col);
        $sheet->getStyle('P4')->applyFromArray($style_col);
        $sheet->getStyle('Q4')->applyFromArray($style_col);


        $kolom = 5;
        $nomor = 1;

        foreach ($data as $marketing) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $marketing->kodecaloncustomer)
                ->setCellValue('C' . $kolom, $marketing->namamarketing)
                ->setCellValue('D' . $kolom, $marketing->namacaloncustomer)
                ->setCellValue('E' . $kolom, $marketing->tipecustomer)
                ->setCellValue('F' . $kolom, $marketing->alamatcaloncustomer)
                ->setCellValue('G' . $kolom, $marketing->kota)
                ->setCellValue('H' . $kolom, $marketing->provinsi)
                ->setCellValue('I' . $kolom, date('j F Y', strtotime($marketing->data_created)))
                ->setCellValue('J' . $kolom, $marketing->kategorimodality)
                ->setCellValue('K' . $kolom, $marketing->merkmodality)
                ->setCellValue('L' . $kolom, $marketing->namamodality)
                ->setCellValue('M' . $kolom, $marketing->namapic)
                ->setCellValue('N' . $kolom, $marketing->jabatanpic)
                ->setCellValue('O' . $kolom, $marketing->teleponpic)
                ->setCellValue('P' . $kolom, $marketing->email)
                ->setCellValue('Q' . $kolom, $marketing->website);

            $kolom++;
            $nomor++;
        }

        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(25); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(30); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(50); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(20); // Set width kolom E
        $sheet->getColumnDimension('F')->setWidth(50); // Set width kolom F
        $sheet->getColumnDimension('G')->setWidth(25); // Set width kolom G
        $sheet->getColumnDimension('H')->setWidth(33); // Set width kolom H
        $sheet->getColumnDimension('I')->setWidth(25); // Set width kolom I
        $sheet->getColumnDimension('J')->setWidth(30); // Set width kolom J
        $sheet->getColumnDimension('K')->setWidth(30); // Set width kolom K
        $sheet->getColumnDimension('L')->setWidth(30); // Set width kolom L
        $sheet->getColumnDimension('M')->setWidth(30); // Set width kolom L
        $sheet->getColumnDimension('N')->setWidth(30); // Set width kolom L
        $sheet->getColumnDimension('O')->setWidth(30); // Set width kolom L
        $sheet->getColumnDimension('P')->setWidth(30); // Set width kolom L
        $sheet->getColumnDimension('Q')->setWidth(30); // Set width kolom L

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Data Calon Pelanggan");
        ob_end_clean();
        // Proses file excel
        $filename = "Data Calon Pelanggan - " . strtoupper($this->input->get('namamarketing')) . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
