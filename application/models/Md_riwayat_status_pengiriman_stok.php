<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_riwayat_status_pengiriman_stok extends CI_Model {

    function add($data){
        $this->db->insert('riwayat_status_pengiriman_stok', $data);
    }

}