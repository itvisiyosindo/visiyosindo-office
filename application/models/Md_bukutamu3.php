<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_bukutamu extends CI_Model
{
    public function get_data()
    {
        return $this->db->get('bukutamu')->result_array();
    }
}