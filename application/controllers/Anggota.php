<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anggota extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('md_anggota');
        $this->load->model('md_kegiatan_peserta');
        $this->load->model('md_grup_anggota');
        $this->load->model('md_isian_datadiri');
        $this->load->model('md_kelas');
        $this->load->model('md_wilayah');
        $this->load->model('md_grup');
        $this->load->model('md_anggota_logbook');
        $this->load->model('md_anggota_datatambahan');
        $this->load->model('md_riwayat_kelas');
        $this->load->model('md_log');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor(['Administrator', 'Staf Admin Pendukung', 'Staf Admin Penggerak', 'Staf Admin Pelopor']);

        $page_data['switch']		= $this->id_navbar();
		$page_data['dt_kelas']   	= $this->md_kelas->getAll(['status' => 1]);
        $page_data['dt_grup']    	= $this->md_grup->getByWhere(['grup.status' => 1]);
        $page_data['page_name']  	= 'anggota';
        $page_data['page_title'] 	= 'Anggota';
        $page_data['page_desc']  	= 'Daftar seluruh anggota';
        $this->load->view('index', $page_data);
    }

    public function get($param1 = '', $param2 = '')
    {
        if ($param1 == 'for_verifikasi') {
            $anggota_id = decrypt($param2);
            $dt = $this->md_anggota->getByWhere(['a.anggota_id' => $anggota_id]);
            foreach ($dt as $row) {
                $tmp['anggota_id'] = $param2;
                $tmp['nama']          = $row->nama;
                $tmp['handphone']     = $row->no_hp;
                $tmp['jenis_kelamin'] = $row->jenis_kelamin;
                echo json_encode($tmp);
            }
        } else if ($param1 == 'by_search') {
            $temp   = $this->md_anggota->getAnggotaBySearch();
            foreach ($temp as $row) {
                $row->anggota_id = encrypt($row->anggota_id);
            }
            echo json_encode(
                array(
                    'incomplete_results' => true,
                    'items' => $temp,
                    'total' => $this->md_anggota->countAnggotaBySearch()[0]->total
                ) // Total rows without LIMIT on your SQL query
            );
            die;
        } else if ($param1 == 'laporan_list_anggota') {
            $dt = $this->md_anggota->getAnggotaForLaporan();
            $dd = $this->md_isian_datadiri->getByWhere(['status' => 1]);
            $isi = '
                <table>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Jenis Kelamin</th>
                    <th>Kontak</th>
                    <th>Verifikasi</th>
                    <th>Jenjang</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>Kelurahan</th>
                    <th>Kecamatan</th>
                    <th>Murid pada Grup</th>
                    <th>Guru pada Grup</th>
                    <th>Catatan Khusus</th>
            ';
            $total_isian_tambahan = count($dd);
            foreach ($dd as $row) {
                $isi .= '<th>' . $row->name_field . '</th>';
            }
            $no = 0;
            foreach ($dt as $row) {
                /** Guru & Murid Grup */
                $murid_grup = '-';
                if ($row->latest_grupanggota_id) {
                    $murid_grup = $row->kode_grup_murid;
                }
                $guru_grup = '-';
                if ($row->guru_grup == $row->anggota_id) {
                    $guru_grup = $row->kode_grup_guru;
                }

                /** Verifikasi Anggota */
                if ($row->verifikasi == '1')
                    $stat_verifikasi = 'Terverifikasi';
                else if ($row->verifikasi == '2')
                    $stat_verifikasi = 'Pendaftaran Ditolak';
                else if (!$row->verifikasi)
                    $stat_verifikasi = 'Menunggu Verifikasi';

                /** Isian Data Diri Tambahan */
                $isian_tambahan = '';
                if ($total_isian_tambahan) {
                    $value_datatambahan = $this->md_anggota_datatambahan->getByWhere(['status' => 1,'anggota_id'=>$row->anggota_id]);
                    if($value_datatambahan){
                        foreach ($dd as $row2) {
                            foreach ($value_datatambahan as $row3) {
                                if ($row3->isiandatadiri_id == $row2->isiandatadiri_id) {
                                    $isian_tambahan .= '<td>'.$row3->value_isian.'</td>';
                                } 
                            }
                        }
                    }
                    else {
                        foreach ($dd as $row2) {
                            $isian_tambahan.='<td>-</td>';
                        }
                    }
                }
                /** Catatan Khusus */
                $pemberi = $row->guru_pemberi_catatan_khusus ? $row->guru_pemberi_catatan_khusus : 'Administrator';
                $catatan_khusus = $row->catatan_khusus ? $row->catatan_khusus . ' - Oleh: ' . $pemberi : '-';

                $isi .= '
                    <tr>
                        <td>' . ++$no . '.</td>
                        <td>' . $row->nama . '</td>
                        <td>' . $row->nama_kelas . '</td>
                        <td>' . $row->jenis_kelamin . '</td>
                        <td>\'' . $row->no_hp . '</td>
                        <td>' . $stat_verifikasi . '</td>
                        <td>' . $row->nama_kelas . '</td>
                        <td>' . $row->email . '</td>
                        <td>' . $row->alamat . '</td>
                        <td>' . $row->kelurahan . '</td>
                        <td>' . $row->kecamatan . '</td>
                        <td>' . $murid_grup . '</td>
                        <td>' . $guru_grup . '</td>
                        <td>' . $catatan_khusus . '</td>
                        ' . $isian_tambahan . '
                    </tr>
                ';
            }
            $isi .= '</table>';
            $x = '<html>
                <style>
                    table {
                    border-collapse: collapse;
                    }
                    table, td, th {
                    border: 1px solid black;
                    border-width : thin
                    }
                </style>
                <body style="font-family:Calibri Light">
                    ' . $isi . '
                </body>
            </html>';
            // echo_array($x);die;
            header('Content-Type: Application/vnd.ms-excel');
            header('Content-disposition: attachment; filename=List_Anggota_' . date('Y-m-d H:i:s') . '.xls');
            echo ($x);
            die;
        } else if ($param1 == 'get_ketua') {
            $anggota_id = decrypt($this->input->post('anggota_id', TRUE));
            $data = $this->md_anggota->getByWhere(['a.anggota_id' => $anggota_id]);
            if ($data){
                if ($data[0]->guru_grup_id == !NULL) {
                    $dt['ada_guru'] = TRUE;
                    $dt['guru_grup_id'] =  encrypt($data[0]->guru_grup_id);
                    $dt['guru_grup'] =  $data[0]->guru_grup;
                } else {
                    $dt['ada_guru'] = FALSE;
                }
            } else {
                $dt['ada_guru'] = FALSE;
            }

            echo json_encode($dt);
        } else if ($param1 == 'get_ketua_bykelas') {
            $data = $this->md_anggota->getAnggotaBySearch();
            foreach ($data as $row) {
                $row->anggota_id = encrypt($row->anggota_id);
            }
            echo json_encode(
                array(
                    'incomplete_results' => true,
                    'items' => $data,
                    'total' => $this->md_anggota->countAnggotaBySearch()[0]->total
                ) // Total rows without LIMIT on your SQL query
            );
            die;
        } else if ($param1 == 'get_data_anggota') {
            $anggota_id = decrypt($this->input->post('anggota_id'));
            $data = $this->md_anggota->getByWhere(['a.anggota_id'=> $anggota_id]);
            if ($data[0]->guru_grup_id) {
                foreach ($data as $row) {
                    $data[0]->guru_grup_id = encrypt($data[0]->guru_grup_id);
                }
            }
         
            /** lama Dikelas saat ini */
            $tmp                              = $this->md_riwayat_kelas->getByWhere(['rk.riwayatkelas_id'=> $data[0]->latest_riwayatkelas_id]);
            $data[0]->lama_dikelas            = countDays($tmp[0]->tmt,date('Y-m-d'));
            $data[0]->latest_grupanggota_id   = encrypt($data[0]->latest_grupanggota_id);
            $data[0]->latest_riwayatkelas_id  = encrypt($data[0]->latest_riwayatkelas_id);

            $data[0]->is_active = getColorStat($data[0]->is_active);

            echo json_encode($data);
            die;
        }
    }

    public function update($param1 = "", $param2="")
    {
        if ($param1 == 'verifikasi') {
            grantAccessFor(['Administrator', 'Staf Admin Pendukung', 'Staf Admin Penggerak', 'Staf Admin Pelopor']);

            $anggota_id = decrypt($this->input->post('anggota_id'));
            $data['verifikasi'] = $this->input->post('respon');
            $data['is_active'] = $this->input->post('respon') == '1' ? TRUE : NULL;

            $this->md_anggota->update($anggota_id, $data);

            /** LOG */
            $tmp = $this->md_anggota->getByWhere(['a.anggota_id' => $anggota_id]);
            
            //log ubah status anggota menjadi Aktif
            addlog('Status Anggota', sessNama(). ' Mengubah status anggota ' .$tmp[0]->nama. ' menjadi Aktif (Verifikasi Anggota Baru)', $anggota_id);

            if ($data['verifikasi'] == '1') {
                addLog('Verifikasi Anggota Baru', 'Pendaftaran ' . $tmp[0]->nama . ' berhasil diverifikasi');
                ajaxReturnDie('success', 'Verifikasi data berhasil', 'reload_table');
            } else {
                addLog('Verifikasi Anggota Baru', 'Pendaftaran ' . $tmp[0]->nama . ' ditolak');
                ajaxReturnDie('error', 'Pendaftaran anggota ditolak', 'reload_table');
            }
        } else if ($param1 == 'status_aktif') {
            $anggota_id = decrypt($this->input->post('anggota_id', TRUE));
            $data['is_active'] = $this->input->post('is_active');
            $this->md_anggota->updateByWhere(['anggota_id'=> $anggota_id], $data);

        } else if ($param1 == 'avatar') {
            $anggota_id = decrypt($this->input->post('anggota_id'));
            $tmp = $this->md_anggota->getByWhere(['a.anggota_id' => $anggota_id]);

            $data = $_POST['image'];

            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);

            $data = base64_decode($data);
            $new_img = time() . '.png';
            file_put_contents('uploads/' . $new_img, $data);

            if ($tmp[0]->avatar_img) {
                $old_link = 'uploads/' . $tmp[0]->avatar_img;
                file_exists($old_link) ? unlink($old_link) : '';
            }

            /** Update Data Anggota */
            $new_ava['avatar_img'] = $new_img;
            $where['anggota_id']   = $anggota_id;
            $this->md_anggota->updateByWhere($where, $new_ava);
            addLog('Pembaharuan Photo Profil', 'Memperbaharui photo profil');
            ajaxReturnDie('success', 'Gambar berhasil disimpan', true);
        } else if ($param1 == 'catatan_khusus') {
            $anggota_id = decrypt($this->input->post('anggota_id'));
            $tmp = $this->md_anggota->getByWhere(['a.anggota_id' => $anggota_id]);
            if ($this->input->post('catatan_khusus')) {
                $catatan = $this->input->post('catatan_khusus') ? $this->input->post('catatan_khusus') : null;
                $pemberi = $this->input->post('catatan_khusus') ? sessAnggotaId() : null;
                addLog('Pemberian Catatan Khusus', 'Memberikan catatan khusus "' . $catatan . '" kepada "' . $tmp[0]->nama . '"');
            }
            $data['catatan_khusus']         = $catatan;
            $data['pemberi_catatan_khusus'] = $pemberi;
            $where['anggota_id']            = $anggota_id;
            $this->md_anggota->updateByWhere($where, $data);
            ajaxReturnDie('success', 'Catatan khusus disimpan', true);
        } else if ($param1 == 'tmk') {
            $tmp = $this->md_anggota->getByWhere(['a.anggota_id' => decrypt($this->input->post('anggota_id', TRUE))]);
            $data['tmt'] = $this->input->post('tmt');
            checkEmptyForm($data);
            $this->md_riwayat_kelas->updateByWhere(['riwayatkelas_id'=> $tmp[0]->latest_riwayatkelas_id], $data);

            //log
            addLog('Ubah TMK Anggota', $tmp[0]->nama. 'Mengubah waktu TMK menjadi '. $data['tmt']);
            ajaxReturnDie('success','TMK Berhasil Di Ubah');
        } else if ($param1 == 'is_active') {
            if ($param2) {
                $anggota_id = decrypt($param2);
                $data['is_active'] = 1;
                $this->md_anggota->updateByWhere(['anggota_id' => $anggota_id], $data);

                //log
                $tmp = $this->md_anggota->getByWhere(['a.anggota_id' => decrypt($param2)]);
                addLog('Perubahan Status Anggota', sessNama(). ' Mengubah status akun '. $tmp[0]->nama .' menjadi Aktif');
                
                $this->session->set_flashdata('success','Akun Berhasil di aktifkan'); 
                redirect('anggota/show/detail/'.$param2);
            } else {
                $anggota_id = decrypt($this->input->post('anggota_id'));
                $data['is_active'] = $this->input->post('is_active');
            }
            $this->md_anggota->updateByWhere(['anggota_id' => $anggota_id], $data);

            //log
            $tmp = $this->md_anggota->getByWhere(['a.anggota_id' => $anggota_id]);
            $tmp2 = getColorStat($data['is_active']);
            addlog('Status Anggota', sessNama(). ' Mengubah status anggota ' .$tmp[0]->nama. ' menjadi ' .$tmp2['text'], $anggota_id);
            ajaxReturnDie('success','Status Berhasil Di Ubah', TRUE);
            
        } else {
            $this->db->trans_begin();

            $reload = false;
            $reset_text = '';
            $is_reset_pass = false;
            $anggota_id = decrypt($param1);
            $dt_anggota = $this->md_anggota->getByWhere(['a.anggota_id' => $anggota_id]);
            $jenis_form    = $this->input->post('jenis_form');
            switch ($jenis_form) {
                case 'Data Umum':
                    $data['nama']                = $this->input->post('nama') ? $this->input->post('nama') : null;
                    $data['nama_panggilan']      = $this->input->post('nama_panggilan') ? $this->input->post('nama_panggilan') : null;
                    $data['agama']               = $this->input->post('agama') ? $this->input->post('agama') : null;
                    $data['jenis_kelamin']       = $this->input->post('jenis_kelamin') ? $this->input->post('jenis_kelamin') : null;
                    $data['tempat_lahir']        = $this->input->post('tempat_lahir') ? $this->input->post('tempat_lahir') : null;
                    $data['tgl_lahir']           = $this->input->post('tgl_lahir') ? date('Y-m-d', strtotime($this->input->post('tgl_lahir'))) : null;
                    $data['status_marital']      = $this->input->post('status_marital') ? $this->input->post('status_marital') : null;
                    $data['rt']                  = $this->input->post('rt') ? $this->input->post('rt') : null;
                    $data['rw']                  = $this->input->post('rw') ? $this->input->post('rw') : null;
                    $data['alamat']              = $this->input->post('alamat') ? $this->input->post('alamat') : null;
                    $data['email']               = $this->input->post('email') ? $this->input->post('email') : null;
                    $data['pendidikan_terakhir'] = $this->input->post('pendidikan_terakhir') ? $this->input->post('pendidikan_terakhir') : null;
                    $data['penghasilan_perbulan'] = $this->input->post('penghasilan_perbulan') ? $this->input->post('penghasilan_perbulan') : null;
                    $data['wilayah_id']          = $this->input->post('wilayah_id') ? decrypt($this->input->post('wilayah_id')) : null;
                    checkEmptyForm($data);
                    if (isAdmin())
                        $data['no_anggota']                 = $this->input->post('no_anggota') ? $this->input->post('no_anggota') : null;

                    $data['nama_istri_suami']    = $this->input->post('nama_istri_suami');
                    $data['pekerjaan']           = $this->input->post('pekerjaan');
                    $data['no_hp']               = $this->input->post('no_hp');
                    $data['telp_rumah']          = $this->input->post('telp_rumah');
                    $data['no_rumah']            = $this->input->post('no_rumah');
                    $data['kode_pos']            = $this->input->post('kode_pos');
                    $data['link_facebook']       = $this->input->post('link_facebook');
                    $data['link_twitter']        = $this->input->post('link_twitter');
                    $data['link_instagram']      = $this->input->post('link_instagram');
                    $data['latitude']      = $this->input->post('latitude');
                    $data['longitude']      = $this->input->post('longitude');

                    if(!$data['latitude'] || !$data['longitude']){
                        ajaxReturnDie('error','Koordinat alamat di map yang tersedia tidak boleh kosong!');
                    }

                    //Cek Duplikasi
                    $cek = $this->md_anggota->getByWhere(array('a.email' => $data['email'], 'a.status' => 1));
                    if ($cek && $cek[0]->anggota_id != $anggota_id) {
                        ajaxReturnDie('danger', 'Email sudah tersedia !');
                    }


                    /** Cek Apakah ada Reset Password */
                    if ($this->input->post('new_password') || $this->input->post('repeat_new_password')) {

                        //validasi password
                        if (!preg_match('/^(?=.*[0-d9]).{8,}/', $this->input->post('new_password', TRUE))) {
                            ajaxReturnDie('error', 'Password minimal 8 karakter, terdiri dari kombinasi huruf dan angka');
                        }
                        if ($this->input->post('new_password') != $this->input->post('repeat_new_password')) {
                            ajaxReturnDie('error', 'Ulangi Password tidak cocok !');
                        } else {
                            $is_reset_pass = true;
                            $data['password'] = hash('sha512', $this->input->post('new_password'));
                        }
                    }

                    if ($this->input->post('catatan_khusus')) {
                        $data['catatan_khusus']         = $this->input->post('catatan_khusus') ? $this->input->post('catatan_khusus') : null;
                        $data['pemberi_catatan_khusus'] = isAdmin() ? null : sessAnggotaId();
                    }
                    // echo_array($data);die;
                    $this->md_anggota->update($anggota_id, $data);
                    addlog('Update Data Umum Anggota', 'Memperbaharui Data Umum');

                    if ($is_reset_pass) {
                        $reset_text = 'Data dan Password Anggota berhasil diperbaharui. Silahkan logout dan login kembali.';
                        addlog('Reset Password Anggota', 'Melakukan Reset Password Akun Anggota ' . $dt_anggota[0]->nama);
                    }

                    break;

                case 'Data Tambahan':
                    $isiandatadiri_id = $this->input->post('isiandatadiri_id');
                    $isiandatadiri_value = $this->input->post('isiandatadiri_value');
                    // print_r( $isiandatadiri_value );die;
                    $no = 0;
                    for ($i = 0; $i < count($isiandatadiri_id); $i++) {
                        $data['isiandatadiri_id'] = decrypt($isiandatadiri_id[$i]);
                        $data['anggota_id']       = $anggota_id;
                        $cek = $this->md_anggota_datatambahan->getByWhere($data);

                        //cek wajib isi isian_datadiri
                        $cek2 = $this->md_isian_datadiri->getByWhere(['isiandatadiri_id' => $data['isiandatadiri_id']]);
                        if ($cek2[0]->wajib_isi == 1) {
                            // print_r( $isiandatadiri_value[$i] );die;
                            if ($isiandatadiri_value[$i] == NULL) {
                                ajaxReturnDie('error', 'Kolom "' . $cek2[0]->name_field . '" tidak boleh kosong!');
                            }
                        }
                        // print_r( $cek2[0]->wajib_isi );die;
                        if ($cek) {
                            $val['value_isian'] = $isiandatadiri_value[$i];
                            $this->md_anggota_datatambahan->updateByWhere($data, $val);
                        } else {
                            $data['value_isian'] = $isiandatadiri_value[$i];
                            $this->md_anggota_datatambahan->add($data);
                        }
                    }
                    addlog('Update Data Tambahan Anggota', 'Memperbaharui Data Tambahan');
                    break;
            }

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $this->session->set_flashdata('success','Data berhasil diperbaharui');
                ajaxReturnDie('success', $reset_text ? $reset_text : 'Data Anggota berhasil diperbaharui', true);
            } else {
                $this->db->trans_rollback();
                ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Administrator');
            }
        }
    }

    public function delete($param1 = "")
    {
        grantAccessFor(['Administrator', 'Staf Admin Pendukung', 'Staf Admin Penggerak', 'Staf Admin Pelopor']);

        $id             = decrypt($param1);
        $temp           = $this->md_anggota->getByWhere(['a.anggota_id' => $id]);
        $data['status'] = 2;

        $this->md_anggota->update($id, $data);
        $this->md_kegiatan_peserta->updateByWhere(['anggota_id' => $id], $data);
        $this->md_grup_anggota->updateGrupAnggotaByWhere(['anggota_id' => $id], $data);
        $this->md_anggota_datatambahan->updateByWhere(['anggota_id' => $id], $data);
        addLog('Menghapus Anggota', 'Menghapus Anggota bernama "' . $temp[0]->nama . '"');
        ajaxReturnDie('success', 'Anggota berhasil dihapus', 'reload_table');
        die;
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'detail') {
            $id = decrypt($param2);
            $page_data['detail']                 = $this->md_anggota->getByWhere(['a.anggota_id' => $id]);

            if (isStafAdmin() && $page_data['detail'][0]->kelas_id >= 6) {
                $this->session->set_flashdata('error', 'Oopss...Halaman detail Anggota Dewasa & Ahli hanya bisa diakses oleh Administrator!');
                redirect('dashboard');
            }

            $page_data['switch']					= $this->id_navbar();
			$page_data['dt_kelas']               	= $this->md_kelas->getAll();
            $page_data['dt_wilayah']             	= $this->md_wilayah->getAllKecamatan();
            $page_data['isian_tambahan']         	= $this->md_isian_datadiri->getByWhere(['status' => 1]);
            $page_data['riwayat_grupanggota']    	= $this->md_grup_anggota->getGrupAnggotaByWhere(['ga.anggota_id' => $id, 'ga.status' => 1]);
            $page_data['riwayat_kelas']          	= $this->md_riwayat_kelas->getByWhere(['rk.anggota_id' => $id, 'rk.status' => 1]);
            $page_data['riwayat_status']         	= $this->md_log->getByWhere(['anggota_id'=>$id, 'jenis_aksi'=>'status anggota']);
            $page_data['isian_tambahan_anggota'] 	= $this->md_anggota_datatambahan->getByWhere(['anggota_id' => $id, 'status' => 1]);
            $page_data['guru_grup']              	= $this->md_grup->getByWhere(['guru' => $id]);
            $page_data['list_murid']             	= getListMurid($id, true);
            // $page_data['belum_isi_logbook']      = cekBelumIsiLogbook($page_data['detail'][0]->anggota_id);
            $page_data['show_reset_pass']        	= isAdmin() || $id == sessAnggotaId() ? TRUE : FALSE;
            $page_data['show_catatan_khusus']    	= isAdmin() || isKetua() ? TRUE : FALSE;
            $page_data['show_mutasi']    			= isAdmin() || isKetua() ? TRUE : FALSE;
            $page_data['show_edit_anggota']      	= isAdmin() || $id == sessAnggotaId() ? TRUE : FALSE;
            $page_data['page_title']             	= 'Data Anggota';
            $page_data['page_desc']              	= 'Data <span class="font-info">' . $page_data['detail'][0]->nama . '</span>';
            $page_data['page_name']              	= 'anggota_detail';
        }
        $this->load->view('index', $page_data);
    }

    public function pagination()
    {
        grantAccessFor(['Administrator', 'Staf Admin Pendukung', 'Staf Admin Penggerak', 'Staf Admin Pelopor']);

        $dt    = $this->md_anggota->getAllAnggota();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id               = encrypt($row->anggota_id);
            $btn_delete       = '<a href="javascript:;" data-id="' . $id . '" data-object="anggota/delete" class="btn btn-sm btn-danger btn-delete" ><i class="bx bx-trash"></i></a>';
            $is_active        = getColorStat($row->is_active);
            $status_anggota   = '<span class="badge badge-ecommerce badge-'.$is_active['color'].'">'.$is_active['text'].'</span>';

            if (!$row->verifikasi and $row->status) {
                $nama_anggota = '<a href="javascript:;" class="text-center btn-verifikasi" data-id="' . $id . '"><span class="badge-warning badge-pill">' . $row->nama . '</span> (Klik disini)</a>';
                $status_anggota .= '<br><span class="badge badge-warning badge-inline badge-pill badge-rounded">Menunggu Verifikasi !</span><br>';
                if (isAdmin()) {
                    $aksi = $btn_delete;
                }
            } else if ($row->verifikasi == '2') {
                $nama_anggota = '<a href="javascript:;" class="text-center btn-verifikasi" data-id="' . $id . '"><span class="badge badge-danger badge-inline badge-pill badge-rounded">' . $row->nama . '</span></a>';
                if (isAdmin()) {
                    $status_anggota = '<a href="javascript:;" class="text-center btn-verifikasi" data-id="' . $id . '"><span class="badge badge-danger badge-inline badge-pill badge-rounded">Pendaftaran Ditolak</span></a>';
                    $aksi = $btn_delete;
                }
            } else {
                /** Login As Button */
                $loginas = '';
                if (isAdmin()) {
                    $loginas = '<div class="dropdown-divider"></div>
                                 <a href="' . base_url('auth/oauth/manual_anggota/' . $id) . '" onClick="return confirm(\'Apakah anda yakin Masuk sebagai ' . $row->nama . ' ? \')" class="btn btn-sm btn-info" title="Masuk sebagai ' . $row->nama . '"><i class="bx bx-log-in"></i></a>';
                }

                $nama_anggota = $row->nama ? '<a href="' . base_url() . 'anggota/show/detail/' . $id . '" class="font-info">' . $row->nama . '</a>' : '-';
                $li_btn   = '
                    <div class="btn-group btn-group" role="group" aria-label="First group">
                        <a href="' . base_url('anggota/show/detail/' . $id) . '" class="btn btn-sm btn-success btn-edit" data-id="' . $id . '"><i class="bx bx-search"></i></a>
                        ' . $loginas . $btn_delete . '
                        
                    </div>';

                $aksi = $li_btn;
            }
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $nama_anggota;
            $th[] = $row->nama_kelas;
            $th[] = $row->jenis_kelamin ? $row->jenis_kelamin : '-';
            $th[] = $row->kode_grup ? '<a href="grup/show/detail/' . encrypt($row->grup_id) . '">' . $row->kode_grup . '</a>' : '-';
            $th[] = $row->longitude;
            $th[] = $row->latitude;
            $th[] = $status_anggota;
            $th[] = $aksi;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
