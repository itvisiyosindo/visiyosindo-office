<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Md_reminder extends CI_Model {
    // Untuk tampilan tabel
    function getUpcomingReminders() {
        $sql = "
            SELECT 
                pengguna_id, nama, jabatan, tgl_lahir as tgl_event, 'Ulang Tahun' as jenis, 
                CONCAT(TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) + 1, ' Tahun') as info_tahun,
                DATEDIFF(DATE_ADD(tgl_lahir, INTERVAL YEAR(CURDATE())-YEAR(tgl_lahir) + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(tgl_lahir),1,0) YEAR), CURDATE()) AS sisa_hari
            FROM pengguna 
            WHERE is_active = 1 AND status = 1 AND tgl_lahir IS NOT NULL
            HAVING sisa_hari BETWEEN 0 AND 30
            
            UNION ALL
            
            SELECT 
                pengguna_id, nama, jabatan, tgl_kontrak as tgl_event, 'Work Anniversary' as jenis,
                CONCAT(TIMESTAMPDIFF(YEAR, tgl_kontrak, CURDATE()) + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(tgl_kontrak), 1, 0), ' Tahun') as info_tahun,
                DATEDIFF(DATE_ADD(tgl_kontrak, INTERVAL YEAR(CURDATE())-YEAR(tgl_kontrak) + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(tgl_kontrak),1,0) YEAR), CURDATE()) AS sisa_hari
            FROM pengguna 
            WHERE is_active = 1 AND status = 1 AND tgl_kontrak IS NOT NULL 
            AND tgl_kontrak <= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
            HAVING sisa_hari BETWEEN 0 AND 30
            
            ORDER BY sisa_hari ASC
        ";
        return $this->db->query($sql)->result();
    }

    // Untuk pencarian spesifik (H-7, H-3, H-0)
    function getDataForNotif($days_diff = 3) {
        $target_date = date('m-d', strtotime("+$days_diff days"));

        $this->db->select("nama, jabatan, 'Ulang Tahun' as jenis, tgl_lahir as tgl");
        $this->db->from('pengguna');
        $this->db->where('is_active', 1);
        $this->db->where("DATE_FORMAT(tgl_lahir, '%m-%d') =", $target_date);
        $q_ultah = $this->db->get_compiled_select();

        $this->db->reset_query();

        $this->db->select("nama, jabatan, 'Work Anniversary' as jenis, tgl_kontrak as tgl");
        $this->db->from('pengguna');
        $this->db->where('is_active', 1);
        $this->db->where('YEAR(tgl_kontrak) <', date('Y'));
        $this->db->where("DATE_FORMAT(tgl_kontrak, '%m-%d') =", $target_date);
        $q_anniv = $this->db->get_compiled_select();

        return $this->db->query($q_ultah . " UNION " . $q_anniv)->result();
    }
}