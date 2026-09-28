<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_blob extends CI_Model {
    
    function getimage(){
         $query2 = $this->db->query("SELECT berkas FROM tb_berkas")->row(0);
         return $query2->berkas;
    }
    
    function add($data){
        $this->db->insert('tb_berkas', $data);
    }

   
}