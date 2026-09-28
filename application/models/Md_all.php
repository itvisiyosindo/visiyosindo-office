<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_all extends CI_Model
{
    function reset_increment($tabel){
		$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
	}
}
?>