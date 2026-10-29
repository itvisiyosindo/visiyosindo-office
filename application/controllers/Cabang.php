<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Cabang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_cabang');
        $this->load->model('md_barang');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_cabang';
        $page_data['page_title'] 	= 'Cabang';
        $page_data['page_desc']  	= 'Management Data Cabang & Koordinat Absensi';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_cabang'] = $this->input->post('nama_cabang', TRUE);
        $data['alamat_cabang'] = $this->input->post('alamat_cabang', TRUE);
        $data['penanggung_jawab'] = $this->input->post('penanggung_jawab', TRUE);
        $data['latitude'] = trim((string)$this->input->post('latitude', TRUE)) ?: NULL;
        $data['longitude'] = trim((string)$this->input->post('longitude', TRUE)) ?: NULL;
        $data['radius'] = (int)($this->input->post('radius', TRUE) ?: 100);
        checkEmptyForm([
            'nama_cabang' => $data['nama_cabang'],
            'alamat_cabang' => $data['alamat_cabang'],
            'penanggung_jawab' => $data['penanggung_jawab']
        ]);

        $this->md_cabang->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data cabang  - ' . $data['nama_cabang'];
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_cabang->getById($id);
        foreach ($dt as $row) {
            $row->id_cabang = encrypt($row->id_cabang);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        //cek apakah data sudah di gunakan
        $cek = $this->md_barang->getByWhere(['b.id_cabang' => decrypt($param)]);
        if ($cek) {
            ajaxReturnDie('error', 'Data sudah di gunakan!');
        }

        $id_cabang    = decrypt($param);
        $data['status'] = 0;
        $this->md_cabang->update(['id_cabang' => $id_cabang], $data);

        //add log
        $temp = $this->md_cabang->getById($id_cabang);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data cabang - ' . $temp[0]->nama_cabang;
        
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'cabang berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_cabang'));
        $data['nama_cabang'] = $this->input->post('nama_cabang', TRUE);
        $data['alamat_cabang'] = $this->input->post('alamat_cabang', TRUE);
        $data['penanggung_jawab'] = $this->input->post('penanggung_jawab', TRUE);
        $data['latitude'] = trim((string)$this->input->post('latitude', TRUE)) ?: NULL;
        $data['longitude'] = trim((string)$this->input->post('longitude', TRUE)) ?: NULL;
        $data['radius'] = (int)($this->input->post('radius', TRUE) ?: 100);
        checkEmptyForm([
            'nama_cabang' => $data['nama_cabang'],
            'alamat_cabang' => $data['alamat_cabang'],
            'penanggung_jawab' => $data['penanggung_jawab']
        ]);

        $this->md_cabang->update(['id_cabang' => $id], $data);

        //add log
        $temp = $this->md_cabang->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data cabang - ' . $temp[0]->nama_cabang;
        
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_cabang->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_cabang);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" title="Edit Cabang & Koordinat" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="cabang/delete"><i class="bx bx-trash"></i></button>
                </div>';

            if (!empty($row->latitude) && !empty($row->longitude)) {
                $gps_info = '<div style="font-size:12px; line-height:1.4;">
                    <span class="badge badge-success" style="font-size:11px; padding:3px 8px;"><i class="fas fa-map-marker-alt"></i> ' . htmlspecialchars($row->latitude) . ', ' . htmlspecialchars($row->longitude) . '</span><br>
                    <span class="badge badge-info" style="font-size:10px; padding:2px 6px; margin-top:2px;">Radius: ' . ((int)($row->radius ?: 100)) . ' meter</span>
                    <a href="https://maps.google.com/?q=' . urlencode($row->latitude . ',' . $row->longitude) . '" target="_blank" class="btn btn-xs btn-outline-primary ml-1" style="font-size:10px; padding:1px 5px;" title="Lihat di Google Maps"><i class="fas fa-external-link-alt"></i></a>
                </div>';
            } else {
                $gps_info = '<span class="badge badge-secondary" style="font-size:11px; padding:3px 8px;"><i class="fas fa-exclamation-circle"></i> Belum Diset</span>';
            }

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_cabang;
            $th[] = $row->penanggung_jawab;
            $th[] = $row->alamat_cabang;
            $th[] = $gps_info;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
