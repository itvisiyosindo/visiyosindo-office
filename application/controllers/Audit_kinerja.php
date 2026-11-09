<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'controllers/Audit.php');

class Audit_kinerja extends Audit
{
    public function index()
    {
        $this->kinerja();
    }
}
