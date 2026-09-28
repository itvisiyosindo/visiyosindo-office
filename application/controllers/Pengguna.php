<?php

use FontLib\Table\Type\post;

use Mpdf\Mpdf;

defined('BASEPATH') or exit('No direct script access allowed');

class Pengguna extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengguna');
        $this->load->model('md_salary');
    }
	
	function id_navbar(){
		$id_navbar = "kepegawaian";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');
		
        // Load divisi model
        $this->load->model('md_divisi_pengguna');
        
		$page_data['switch']      	= $this->id_navbar();
        $page_data['page_name']     = 'v_pengguna';
        $page_data['page_title']    = 'Data Karyawan';
        $page_data['page_desc']     = 'Management Data Karyawan';
        $page_data['divisi_list']   = $this->md_divisi_pengguna->getBywhere(['is_active' => 1]);
        $this->load->view('index', $page_data);
    }
    
    public function get_statistics()
    {
        grantAccessFor('all');
        
        // Get filter parameters
        $filter_is_active = $this->input->post('filter_is_active');
        
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as aktif,
            SUM(CASE WHEN is_active = 0 OR is_active IS NULL THEN 1 ELSE 0 END) as tidak_aktif,
            SUM(CASE WHEN LOWER(status_karyawan) = "training" THEN 1 ELSE 0 END) as training
        ');
        $this->db->from('pengguna');
        $this->db->where('status', 1);
        $this->db->where('pengguna_id !=', 1);
        
        $result = $this->db->get()->row();
        
        echo json_encode([
            'success' => true,
            'data' => [
                'total' => (int)$result->total,
                'aktif' => (int)$result->aktif,
                'tidak_aktif' => (int)$result->tidak_aktif,
                'training' => (int)$result->training
            ]
        ]);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama']     = $this->input->post('nama', TRUE);
        $data['email']    = $this->input->post('email', TRUE);
        $data['username'] = $this->input->post('username', TRUE);
        $data['password'] = hash('sha512', $this->input->post('password', TRUE));
        $data['level']    = $this->input->post('level', TRUE);
        $data['hirarki']    = $this->input->post('hirarki', TRUE);

        if ($this->input->post('password', TRUE) != $this->input->post('rpassword', TRUE))
            ajaxReturnDie('error', 'Cek Kembali Confirm Password');
        else {
            $this->md_pengguna->addPengguna($data);

            /** LOG */
            addLog('Menambah Pengguna', 'Menambah pengguna "' . $data['nama'] . '" sebagai ' . $data['level']);
            ajaxReturnDie('success', 'Pengguna berhasil ditambahkan', 'reload_table');
        }
    }

    public function addgaji()
    {
        grantAccessFor('all');
        
        $data['pengguna_id']            = decrypt($this->input->post('pengguna_id'));
        $data['gaji_pokok']             = $this->input->post('gaji_pokok') ? delete_currency($this->input->post('gaji_pokok')) : 0;
        $data['tunjangan_jabatan']      = $this->input->post('tunjangan_jabatan') ? delete_currency($this->input->post('tunjangan_jabatan')) : 0;
        $data['komisi']                 = $this->input->post('komisi') ? delete_currency($this->input->post('komisi')) : 0;
        $data['pendapatan_lain']        = $this->input->post('pendapatan_lain') ? delete_currency($this->input->post('pendapatan_lain')) : 0;
        $data['tunjangan_kinerja']      = $this->input->post('tunjangan_kinerja') ? delete_currency($this->input->post('tunjangan_kinerja')) : 0;
        $data['tunjangan_konsumsi']     = $this->input->post('tunjangan_konsumsi') ? delete_currency($this->input->post('tunjangan_konsumsi')) : 0;
        $data['tunjangan_komunikasi']   = $this->input->post('tunjangan_komunikasi') ? delete_currency($this->input->post('tunjangan_komunikasi')) : 0;
        $data['tunjangan_transportasi'] = $this->input->post('tunjangan_transportasi') ? delete_currency($this->input->post('tunjangan_transportasi')) : 0;
        $data['tunjangan_bbm']          = $this->input->post('tunjangan_bbm') ? delete_currency($this->input->post('tunjangan_bbm')) : 0;
        $data['tunjangan_raya']         = $this->input->post('tunjangan_raya') ? delete_currency($this->input->post('tunjangan_raya')) : 0;
        $data['dasar_bpjs_kerja']       = $this->input->post('dasar_bpjs_kerja') ? delete_currency($this->input->post('dasar_bpjs_kerja')) : 0;
        $data['dasar_bpjs_sehat']       = $this->input->post('dasar_bpjs_sehat') ? delete_currency($this->input->post('dasar_bpjs_sehat')) : 0;
        $data['potongan']               = $this->input->post('potongan') ? delete_currency($this->input->post('potongan')) : 0;
        $data['bonus_tahunan']          = $this->input->post('bonus_tahunan') ? delete_currency($this->input->post('bonus_tahunan')) : 0;
        // $data['dasar_potong_bpjs']      = $this->input->post('dasar_potongan_bpjs') ;
        // $data['dasar_potong_bpjs_tk']   = $this->input->post('dasar_potongan_bpjs_tk') ;
        $this->md_salary->addRiwayatSalary($data);

        //update latest riwayat salary
        $data2['id_latestriwayat_salary'] = $this->db->insert_id();
        $this->md_pengguna->updatePengguna($data['pengguna_id'], $data2);

        /** LOG */
        $tmp = $this->md_pengguna->getById($data['pengguna_id']);
        addLog('Update Salary', 'Mengupdate salary "' . $tmp[0]->nama . '"');
        ajaxReturnDie('success', 'Salary Berhasil di Edit', TRUE);
    }

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor(['Administrator', 'Hrd', 'Karyawan','Ga']);
        if ($param == 'detail_pengguna') {
			if(isKaryawan()){
				$page_data['switch']	= "home";
			}else{
				$page_data['switch']      	= $this->id_navbar();
			}
            			
            $page_data['pengguna']      = $this->md_pengguna->getByWhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator']);
            $page_data['data_pengguna'] = $this->md_pengguna->getByWhere(['p.pengguna_id' => decrypt($param2)]);
            $page_data['data_salary']   = $this->md_salary->getByWhere(['sl.pengguna_id' => decrypt($param2)]);
            $page_data['page_name']     = 'v_pengguna_detail';
            $page_data['page_title']    = 'Detail Karyawan';
            $page_data['page_desc']     = 'Profil Karyawan';
            $this->load->view('index', $page_data);
        } else if ($param == 'lihat_file') {
            $id = decrypt($this->input->post('id'));
            $object = 'file_' . $this->input->post('object');
            $data = $this->md_pengguna->getById($id)[0]->$object;
            echo json_encode($data);
            die;
        } else if ($param == 'download_file') {
            $id = decrypt($param2);
            $object = 'file_' . $param3;
            $data = $this->md_pengguna->getById($id);
            if ($data[0]->$object) {
                $this->load->helper('download');
                force_download('uploads/file_karyawan/' . $param3 . '/' . $data[0]->$object, NULL);
            } else {
                show_404();
            }
        }
    }

    public function edit($param1)
    {
        grantAccessFor(['Administrator', 'Hrd','Ga']);
        $id = decrypt($param1);
        $dt = $this->md_pengguna->getById($id);
        foreach ($dt as $row) {
            $row->pengguna_id = encrypt($row->pengguna_id);
        }
        echo json_encode($dt);
        die;
    }

    public function verifikasi($param)
    {
        $pengguna_id = decrypt($param);
        $cek     = $this->md_pengguna->getByWhere(['p.pengguna_id' => $pengguna_id, 'p.status' => 1, 'p.is_active' => 1]);
        if ($cek) {
            $this->session->set_flashdata('success', 'Akun sudah terverifikasi sebelumnya');
            redirect('auth');
        } else {
            $data['status_approval'] = 'Menunggu';
            $this->md_pengguna->updateByWhere(['pengguna_id' => $pengguna_id,], $data);

            /** LOG */
            $data2['nama'] = $this->md_pengguna->getByWhere(['p.pengguna_id' => $pengguna_id])[0]->nama;
            $judul      = 'Pengguna Verifikasi Email';
            $keterangan = $data2['nama'] . ' baru saja memverifikasi email pendaftaran. ';
            addLog($judul, $keterangan);
            $this->session->set_flashdata('success', 'Silahkan login setelah admin meng- approve');
            redirect('auth');
        }
    }

    public function update($param = "", $param2 = "")
    {
        if ($param == 'is_active') {
            grantAccessFor('all');
            $pengguna_id = decrypt($this->input->post('pengguna_id', TRUE));
            $data['is_active'] = $this->input->post('value');
            $data['status_approval'] = NULL;
            $this->md_pengguna->updatePengguna($pengguna_id, $data);

            $datalog =  $data['is_active'] == 1 ? "Aktif" : "Tidak Aktif";
            $datalog2 = $this->md_pengguna->getById($pengguna_id);
            addLog('Memperbaharui Pengguna', 'Mengubah status aktif "' . $datalog2[0]->nama . '" menjadi "' . $datalog . '"');
            ajaxReturnDie('success', 'Status Aktif Berhasil Diubah', TRUE);
        } else if ($param == 'edit_on_detail') {
            grantAccessFor(['Administrator', 'Hrd','Ga']);
            //grantAccessFor(all);
            $pengguna_id      = decrypt($this->input->post('pengguna_id'));
            $data['nama']               = $this->input->post('nama');
            $data['no_pegawai']         = $this->input->post('no_pegawai');
            $data['status_karyawan']    = $this->input->post('status_karyawan');
            $data['no_hp']              = $this->input->post('no_hp');
            $data['email']              = $this->input->post('email');
            $data['lama_training']      = $this->input->post('lama_training');
            $data['jabatan']            = $this->input->post('jabatan');
            $data['jabatan_visilab']    = $this->input->post('jabatan_visilab');
            $data['pendidikan']         = $this->input->post('pendidikan');
            $data['kontak_keluarga']    = $this->input->post('kontak_keluarga');
            $data['hubungan_keluarga']  = $this->input->post('hubungan_keluarga');
            $data['alamat']             = $this->input->post('alamat');
            $data['tgl_lahir']          = $this->input->post('tgl_lahir') ? date_db_format($this->input->post('tgl_lahir')) : NULL;
            $data['tgl_masuk']          = $this->input->post('tgl_masuk') ? date_db_format($this->input->post('tgl_masuk')) : NULL;
            $data['tgl_keluar']         = $this->input->post('tgl_keluar') ? date_db_format($this->input->post('tgl_keluar')) : NULL;
            $data['tgl_kontrak']        = $this->input->post('tgl_kontrak') ? date_db_format($this->input->post('tgl_kontrak')) : NULL;
            $data['alasan_keluar']      = $this->input->post('alasan_keluar');
            $data['keterangan_lain']    = $this->input->post('keterangan_lain');
            $data['terima_tunjangan_tt']= $this->input->post('terima_tunjangan_tt') == 'on' ? 1 : 0;
            $data['terima_tunjangan_konsumsi']= $this->input->post('terima_tunjangan_konsumsi') == 'on' ? 1 : 0;
            $data['terima_tunjangan_kinerja'] = $this->input->post('terima_tunjangan_kinerja') == 'on' ? 1 : 0;
            $data['terima_tunjangan_komunikasi'] = $this->input->post('terima_tunjangan_komunikasi') == 'on' ? 1 : 0;
            $data['terima_tunjangan_transportasi'] = $this->input->post('terima_tunjangan_transportasi') == 'on' ? 1 : 0;
            $data['terima_tunjangan_jabatan'] = $this->input->post('terima_tunjangan_jabatan') == 'on' ? 1 : 0;
            $data['terima_tunjangan_bbm'] = $this->input->post('terima_tunjangan_bbm') == 'on' ? 1 : 0;
            $data['terima_tunjangan_raya'] = $this->input->post('terima_tunjangan_raya') == 'on' ? 1 : 0;
            $data['terima_bonus_tahunan'] = $this->input->post('terima_bonus_tahunan') == 'on' ? 1 : 0;
            $data['no_rek']             = $this->input->post('no_rek');
            $data['npwp']               = $this->input->post('npwp');
            $data['nik']               = $this->input->post('nik');
            $data['id_status_perkawinan']   = $this->input->post('status_perkawinan');
            $pasangan_sekantor              = $this->input->post('pasangan_sekantor');
            $data['id_pasangan_sekantor']   = $pasangan_sekantor == "" ? NULL : decrypt($pasangan_sekantor);
            $this->md_pengguna->updatePengguna($pengguna_id, $data);
            // $data2 = $this->md_pengguna->getById($pengguna_id);
            // addLog('Memperbaharui Karyawan', 'Memperbaharui data Karyawan ' . $data2[0]->nama);
            ajaxReturnDie('success', 'Pengguna berhasil diperbaharui', TRUE);
        } else if ($param == 'file') {
            $pengguna_id = decrypt($this->input->post('pengguna_id'));
            $karyawan = $this->md_pengguna->getById($pengguna_id);

            //cek file sebelumnya, jika ada hapus file itu
            $tmp = 'file_' . $param2;
            if ($karyawan[0]->$tmp) {
                file_exists('uploads/file_karyawan/' . $param2 . '/' .  $karyawan[0]->$tmp) ? unlink('uploads/file_karyawan/' . $param2 . '/' .  $karyawan[0]->$tmp) : '';
            }

            $nama_karyawan1 = str_replace(" ", "_", $karyawan[0]->nama);
            $nama_karyawan = str_replace(".", "_", $nama_karyawan1);
            $file = $_FILES[$param2];
            if ($file['name']) {
                $config['file_name']        = $nama_karyawan . '-' .  time();
                $config['upload_path']      = 'uploads/file_karyawan/' . $param2;
                $config['allowed_types']    = 'pdf|xls|xlsx|doc|docx|jpg|jpeg|png';
                $config['max_size']         = 4000;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload($param2)) {
                    ajaxReturnDie('error', $this->upload->display_errors());
                } else {
                    $dt = $this->upload->data();
                    $data['file_' . $param2] = $dt['file_name'];
                    $this->md_pengguna->updatePengguna($pengguna_id, $data);
                    addLog('Memperbaharui Karyawan', 'Memperbaharui File Karyawan ' . $karyawan[0]->nama);
                    ajaxReturnDie('success', 'Pengguna berhasil diperbaharui', TRUE);
                }
            } else {
                ajaxReturnDie('error', 'Silahkan upload File Pendukung');
            }
        } else {
            grantAccessFor(['Administrator']);
            $pengguna_id      = decrypt($this->input->post('pengguna_id'));
            $data['nama']     = $this->input->post('nama');
            $data['email']    = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            $data['level']    = $this->input->post('level');
            $data['hirarki']    = $this->input->post('hirarki');
            $this->md_pengguna->updatePengguna($pengguna_id, $data);
            addLog('Memperbaharui Pengguna', 'Memperbaharui data pengguna ' . $data['nama']);
            ajaxReturnDie('success', 'Pengguna berhasil diperbaharui', 'reload_table');
        }
    }


    public function delete($param1)
    {
        grantAccessFor(['Administrator']);

        $pengguna_id    = decrypt($param1);
        $temp           = $this->md_pengguna->getById($pengguna_id);
        $data['status'] = 2;
        $this->md_pengguna->updatePengguna($pengguna_id, $data);
        addLog('Menghapus Pengguna', 'Menghapus pengguna ' . $temp[0]->nama);
        ajaxReturnDie('success', 'Pengguna berhasil dihapus', 'reload_table');
    }

    public function reset()
    {
        grantAccessFor(['Administrator']);

        if ($this->input->post('password') != $this->input->post('rpassword')) {
            ajaxReturnDie('error', 'Konfirmasi Password Salah!');
        }

        if (!preg_match('/^(?=.*[0-d9]).{8,}/', $this->input->post('password', TRUE))) {
            ajaxReturnDie('error', 'Password minimal 8 karakter, terdiri dari kombinasi huruf dan angka');
        }

        $pengguna_id      = decrypt($this->input->post('pengguna_id'));
        $temp             = $this->md_pengguna->getById($pengguna_id);
        $data['password'] = hash('sha512', $this->input->post('password'));
        $this->md_pengguna->updatePengguna($pengguna_id, $data);

        /** LOG */
        addLog('Reset Password', 'Reset password pengguna ' . $temp[0]->nama);

        ajaxReturnDie('success', 'Reset Password Berhasil! Silahkan logout dan login kembali');
    }

    public function pagination()
    {
        grantAccessFor('all');

        // Get filter parameters
        $filters = [
            'nama' => $this->input->post('filter_nama'),
            'divisi' => $this->input->post('filter_divisi'),
            'status_karyawan' => $this->input->post('filter_status_karyawan'),
            'level' => $this->input->post('filter_level'),
            'is_active' => $this->input->post('filter_is_active')
        ];

        $dt    = $this->md_pengguna->getAllPenggunaFiltered($filters);
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            // Modern badges
            $is_active = $row->is_active == 1 
                ? '<span class="badge-modern badge-active"><i class="fas fa-check-circle"></i> Aktif</span>' 
                : '<span class="badge-modern badge-inactive"><i class="fas fa-times-circle"></i> Tidak Aktif</span>';
            
            $level_badge = '<span class="badge-modern badge-level">' . $row->level . '</span>';
            
            $id = encrypt($row->pengguna_id);
            
            // Get initial for avatar
            $initial = strtoupper(substr($row->nama, 0, 1));
            
            // Employee cell with avatar
            $nama_pengguna = '
                <div class="employee-cell">
                    <div class="employee-avatar-placeholder">' . $initial . '</div>
                    <div class="employee-info">
                        <h6><a href="pengguna/show/detail_pengguna/' . $id . '" style="color: #1e293b; text-decoration: none;">' . $row->nama . '</a></h6>
                        <p>' . ($row->jabatan ? $row->jabatan : '-') . '</p>
                    </div>
                </div>';
            
            // Modern action buttons
            $li_btn = '
                <div class="table-actions">
                    <a href="pengguna/show/detail_pengguna/' . $id . '" class="btn-table-action btn-view" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                    <button type="button" class="btn-table-action btn-table-edit btn-edit" data-id="' . $id . '" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                    ' . ($row->level != 'Administrator' ? '<button type="button" class="btn-table-action btn-table-delete btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="pengguna/delete"><i class="fas fa-trash-alt"></i></button>' : '') . '
                </div>';
            
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $nama_pengguna;
            $th[] = $row->email ? $row->email : '-';
            $th[] = $level_badge;
            $th[] = $is_active;
            if (isAdmin() || isGa() || isHrd()) {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }


    public function cetakPengguna()
    {
            grantAccessFor('all');
            
            $page_data['page_name']     = 'v_print/print_pengguna';
            $page_data['page_title']    = 'Daftar Pegawai';
            $page_data['page_desc']     = 'Daftar Pegawai';
            $this->load->view('index', $page_data);
        
            $dt = [
                'title_pdf'	=> 'Daftar Pegawai',
                'data'	    => $this->md_pengguna->getAllPegawai(),
            ];
        
        //load mpdf dan membuat page size 
        $mpdf = new Mpdf(['format' => 'Tabloid']);

        //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
        $mpdf->AddPage('L', '', '', '', '', '', '', '5', '5');
        
        
        
        // filename dari pdf ketika didownload
        $file_pdf = 'Daftar Pegawai';

        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_pengguna', $dt, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');


        //add log
            $aksi = 'Pengguna';
            $ket = 'Cetak data Daftar Pegawai';
            addlog($aksi, $ket);

    }
    
    public function exportExcel()
    {
        grantAccessFor('all');
        
        // Get filter parameters
        $filters = [
            'nama' => $this->input->get('filter_nama'),
            'divisi' => $this->input->get('filter_divisi'),
            'status_karyawan' => $this->input->get('filter_status_karyawan'),
            'level' => $this->input->get('filter_level'),
            'is_active' => $this->input->get('filter_is_active')
        ];
        
        // Get data
        $this->db->select('pg.nama, pg.email, pg.jabatan, pg.level, pg.status_karyawan, pg.status_approval, pg.is_active, d.nama as divisi_nama');
        $this->db->from('pengguna pg');
        $this->db->join('divisi d', 'd.id_divisi = pg.id_divisi', 'left');
        $this->db->where('pg.status', 1);
        $this->db->where('pg.pengguna_id !=', 1);
        
        // Apply filters
        if (!empty($filters['nama'])) {
            $this->db->like('pg.nama', $filters['nama']);
        }
        if (!empty($filters['divisi'])) {
            $this->db->where('pg.id_divisi', $filters['divisi']);
        }
        if (!empty($filters['status_karyawan'])) {
            $this->db->where('LOWER(pg.status_karyawan)', strtolower($filters['status_karyawan']));
        }
        if (!empty($filters['level'])) {
            $this->db->where('pg.level', $filters['level']);
        }
        if (isset($filters['is_active']) && $filters['is_active'] !== '' && $filters['is_active'] !== null) {
            $this->db->where('pg.is_active', $filters['is_active']);
        }
        
        $this->db->order_by('pg.nama', 'ASC');
        $data = $this->db->get()->result();
        
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data_Karyawan_' . date('Y-m-d_H-i-s') . '.xls"');
        header('Cache-Control: max-age=0');
        
        // Output Excel content
        echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        echo '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>';
        echo '<body>';
        echo '<table border="1">';
        echo '<tr style="background-color: #667eea; color: white; font-weight: bold;">';
        echo '<th>No</th>';
        echo '<th>Nama</th>';
        echo '<th>Email</th>';
        echo '<th>Jabatan</th>';
        echo '<th>Divisi</th>';
        echo '<th>Level</th>';
        echo '<th>Status Karyawan</th>';
        echo '<th>Status Aktif</th>';
        echo '</tr>';
        
        $no = 1;
        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . $row->nama . '</td>';
            echo '<td>' . ($row->email ?: '-') . '</td>';
            echo '<td>' . ($row->jabatan ?: '-') . '</td>';
            echo '<td>' . ($row->divisi_nama ?: '-') . '</td>';
            echo '<td>' . $row->level . '</td>';
            echo '<td>' . ($row->status_karyawan ?: '-') . '</td>';
            echo '<td>' . ($row->is_active == 1 ? 'Aktif' : 'Tidak Aktif') . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
        echo '</body></html>';
        
        // Add log
        addLog('Export Excel', 'Export data karyawan ke Excel');
    }
    
    /**
     * Upload Signature (TTD)
     * File only - not saved to database
     */
    public function upload_signature()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga', 'Karyawan']);
        
        $pengguna_id = decrypt($this->input->post('pengguna_id'));
        
        if (empty($pengguna_id)) {
            echo json_encode(['status' => 'error', 'msg' => 'ID Pengguna tidak valid']);
            return;
        }
        
        // Check if file uploaded
        if (empty($_FILES['signature_file']['name'])) {
            echo json_encode(['status' => 'error', 'msg' => 'File tidak ditemukan']);
            return;
        }
        
        // Validate file type
        $allowed_types = ['image/png'];
        if (!in_array($_FILES['signature_file']['type'], $allowed_types)) {
            echo json_encode(['status' => 'error', 'msg' => 'Hanya file PNG yang diperbolehkan']);
            return;
        }
        
        // Validate file size (max 2MB)
        if ($_FILES['signature_file']['size'] > 2 * 1024 * 1024) {
            echo json_encode(['status' => 'error', 'msg' => 'Ukuran file maksimal 2MB']);
            return;
        }
        
        // Create directory if not exists
        $upload_path = FCPATH . 'uploads/file_karyawan/ttd/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        
        // Generate filename
        $filename = 'ttd_' . $pengguna_id . '.png';
        $filepath = $upload_path . $filename;
        
        // Delete old file if exists
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['signature_file']['tmp_name'], $filepath)) {
            // Add log
            $temp = $this->md_pengguna->getById($pengguna_id);
            addLog('Upload TTD', 'Upload tanda tangan untuk karyawan ' . ($temp[0]->nama ?? ''));
            
            echo json_encode(['status' => 'success', 'msg' => 'Tanda tangan berhasil diupload']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Gagal mengupload file']);
        }
    }
    
    /**
     * Delete Signature (TTD)
     */
    public function delete_signature()
    {
        grantAccessFor('all');
        
        $pengguna_id = decrypt($this->input->post('pengguna_id'));
        
        if (empty($pengguna_id)) {
            echo json_encode(['status' => 'error', 'msg' => 'ID Pengguna tidak valid']);
            return;
        }
        
        // File path
        $filepath = FCPATH . 'uploads/file_karyawan/ttd/ttd_' . $pengguna_id . '.png';
        
        // Delete file if exists
        if (file_exists($filepath)) {
            if (unlink($filepath)) {
                // Add log
                $temp = $this->md_pengguna->getById($pengguna_id);
                addLog('Hapus TTD', 'Menghapus tanda tangan karyawan ' . ($temp[0]->nama ?? ''));
                
                echo json_encode(['status' => 'success', 'msg' => 'Tanda tangan berhasil dihapus']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Gagal menghapus file']);
            }
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'File tidak ditemukan']);
        }
    }

    public function get_employees_json()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        $this->db->select('pg.pengguna_id as id, pg.pengguna_id, pg.nik, pg.no_pegawai, pg.nama as name, pg.jabatan as role, d.nama as dept');
        $this->db->from('pengguna pg');
        $this->db->join('divisi d', 'd.id_divisi = pg.id_divisi', 'left');
        $this->db->where('pg.status', 1);
        $this->db->where('pg.is_active', 1);
        $this->db->where('pg.pengguna_id !=', 1);
        $this->db->order_by('pg.nama', 'ASC');
        
        $employees = $this->db->get()->result();
        echo json_encode($employees);
        die;
    }

    public function get_audit_full_data_json()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        $this->db->select('pg.pengguna_id as id, pg.pengguna_id, pg.nik, pg.no_pegawai, pg.nama as name, pg.jabatan as role, d.nama as dept');
        $this->db->from('pengguna pg');
        $this->db->join('divisi d', 'd.id_divisi = pg.id_divisi', 'left');
        $this->db->where('pg.status', 1);
        $this->db->where('pg.is_active', 1);
        $this->db->where('pg.pengguna_id !=', 1);
        $employees = $this->db->get()->result();

        $pbok_ppa = [];
        $base_url_office = base_url();

        // 1. Fetch PBOK transactions
        if ($this->db->table_exists('surat_pbok')) {
            $pboks = $this->db->select('sb.id_pbok as id, sb.kode_pbok as code, sb.id_pengguna as pengguna_id, p.nama as name, p.nik, d.nama as dept, sb.tgl_pengajuan, sb.lampiran')
                             ->from('surat_pbok sb')
                             ->join('pengguna p', 'p.pengguna_id = sb.id_pengguna', 'left')
                             ->join('divisi d', 'd.id_divisi = p.id_divisi', 'left')
                             ->order_by('sb.id_pbok', 'DESC')
                             ->limit(100)
                             ->get()
                             ->result();

            foreach ($pboks as $row) {
                // Get detail item & total
                $items = [];
                $total_nominal = 0;
                if ($this->db->table_exists('surat_pbok_detail')) {
                    $details = $this->db->get_where('surat_pbok_detail', ['id_pbok' => $row->id])->result();
                    foreach ($details as $det) {
                        $total_nominal += (float)$det->total;
                        if (!empty($det->keterangan)) {
                            $items[] = $det->keterangan;
                        }
                    }
                }

                $pbok_ppa[] = [
                    'id' => 'PBOK-' . $row->id,
                    'code' => $row->code,
                    'type' => 'PBOK',
                    'pengguna_id' => $row->pengguna_id,
                    'nik' => !empty($row->nik) ? $row->nik : 'EMP-' . $row->pengguna_id,
                    'name' => $row->name ?: 'Karyawan',
                    'dept' => $row->dept ?: 'General Affair',
                    'date' => !empty($row->tgl_pengajuan) ? date('d-m-Y', strtotime($row->tgl_pengajuan)) : '',
                    'item' => !empty($items) ? implode(', ', $items) : 'Pengajuan Biaya Operasional Kantor',
                    'amount' => $total_nominal,
                    'physicalStatus' => 'Terverifikasi Ada',
                    'lossStatus' => 'Aman / Wajar',
                    'lossAmount' => 0,
                    'officeUrl' => $base_url_office . 'surat/show/detail_surat/pbok/' . $row->id . '/1',
                    'notes' => 'Integrasi data otomatis dari Sistem Office'
                ];
            }
        }

        // 2. Fetch PPA transactions
        if ($this->db->table_exists('surat_ppa')) {
            $ppas = $this->db->select('sp.id_ppa as id, sp.kode_ppa as code, sp.id_pengguna as pengguna_id, p.nama as name, p.nik, d.nama as dept, sp.tgl_pengajuan, sp.lampiran')
                            ->from('surat_ppa sp')
                            ->join('pengguna p', 'p.pengguna_id = sp.id_pengguna', 'left')
                            ->join('divisi d', 'd.id_divisi = p.id_divisi', 'left')
                            ->order_by('sp.id_ppa', 'DESC')
                            ->limit(100)
                            ->get()
                            ->result();

            foreach ($ppas as $row) {
                // Get detail item & total
                $items = [];
                $total_nominal = 0;
                if ($this->db->table_exists('surat_ppa_detail')) {
                    $details = $this->db->get_where('surat_ppa_detail', ['id_ppa' => $row->id])->result();
                    foreach ($details as $det) {
                        $total_nominal += (float)$det->total;
                        if (!empty($det->keterangan)) {
                            $items[] = $det->keterangan;
                        }
                    }
                }

                $pbok_ppa[] = [
                    'id' => 'PPA-' . $row->id,
                    'code' => $row->code,
                    'type' => 'PPA',
                    'pengguna_id' => $row->pengguna_id,
                    'nik' => !empty($row->nik) ? $row->nik : 'EMP-' . $row->pengguna_id,
                    'name' => $row->name ?: 'Karyawan',
                    'dept' => $row->dept ?: 'General Affair',
                    'date' => !empty($row->tgl_pengajuan) ? date('d-m-Y', strtotime($row->tgl_pengajuan)) : '',
                    'item' => !empty($items) ? implode(', ', $items) : 'Pengajuan Pembelian & Pemeliharaan Aset',
                    'amount' => $total_nominal,
                    'physicalStatus' => 'Terverifikasi Ada',
                    'lossStatus' => 'Aman / Wajar',
                    'lossAmount' => 0,
                    'officeUrl' => $base_url_office . 'surat/show/detail_surat/ppa/' . $row->id . '/1',
                    'notes' => 'Integrasi data otomatis dari Sistem Office'
                ];
            }
        }

        echo json_encode([
            'status' => 'success',
            'employees' => $employees,
            'pbok_ppa' => $pbok_ppa
        ]);
        die;
    }
}
