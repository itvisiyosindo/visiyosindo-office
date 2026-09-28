<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_setting_thr extends CI_Model
{
  // Ambil daftar karyawan aktif beserta status THR-nya
  public function getAllKaryawan()
  {
    $this->db->select('p.pengguna_id, p.nama, p.no_pegawai, p.jabatan, djt.tipe_thr');
    $this->db->from('pengguna p');
    $this->db->join('data_jenis_thr djt', 'p.pengguna_id = djt.pengguna_id', 'left');

    $this->db->where('p.is_active', 1);
    $this->db->where('p.status', 1);
    $this->db->where('p.level !=', 'Administrator');

    // --- UPDATE: FILTER HANYA YANG PUNYA NPP & SORTING ---
    $this->db->where('p.no_pegawai !=', '');
    $this->db->where('p.no_pegawai IS NOT NULL');
    $this->db->order_by('p.no_pegawai', 'ASC'); // Urutkan NPP dari awal
    // -----------------------------------------------------

    return $this->db->get()->result();
  }

  public function updateJenisTHR($pengguna_id, $tipe)
  {
    $cek = $this->db->get_where('data_jenis_thr', ['pengguna_id' => $pengguna_id]);

    if ($cek->num_rows() > 0) {
      $this->db->where('pengguna_id', $pengguna_id);
      $this->db->update('data_jenis_thr', ['tipe_thr' => $tipe]);
    } else {
      $data = [
        'pengguna_id' => $pengguna_id,
        'tipe_thr' => $tipe
      ];
      $this->db->insert('data_jenis_thr', $data);
    }
    return true;
  }

  public function generateDefaultData()
  {
    // Query: Masukkan ke tabel 'data_jenis_thr' (pengguna_id, 'IDUL_FITRI')
    // Dari tabel 'pengguna'
    // Dimana pengguna tersebut BELUM ADA di tabel 'data_jenis_thr'

    $sql = "INSERT INTO data_jenis_thr (pengguna_id, tipe_thr)
            SELECT pengguna_id, 'IDUL_FITRI'
            FROM pengguna p
            WHERE p.is_active = 1 
            AND p.status = 1
            AND p.no_pegawai != ''
            AND p.no_pegawai IS NOT NULL
            AND p.level != 'Administrator'
            AND NOT EXISTS (
                SELECT 1 FROM data_jenis_thr djt 
                WHERE djt.pengguna_id = p.pengguna_id
            )";

    $this->db->query($sql);
    return $this->db->affected_rows(); // Mengembalikan jumlah data yang berhasil dibuat
  }

  public function getUnregisteredEmployees()
  {
    // Cari karyawan yang Punya NPP tapi belum ada di tabel data_jenis_thr
    $this->db->select('p.pengguna_id, p.nama, p.no_pegawai, p.jabatan');
    $this->db->from('pengguna p');
    $this->db->where('p.is_active', 1);
    $this->db->where('p.status', 1);
    $this->db->where("p.no_pegawai != ''");
    $this->db->where("p.no_pegawai IS NOT NULL");
    $this->db->where('p.level !=', 'Administrator');

    // Logika NOT EXISTS (Belum ada di tabel setting)
    $this->db->where("NOT EXISTS (
        SELECT 1 FROM data_jenis_thr djt 
        WHERE djt.pengguna_id = p.pengguna_id
    )", NULL, FALSE);

    $this->db->order_by('p.no_pegawai', 'ASC');
    return $this->db->get()->result();
  }
}
