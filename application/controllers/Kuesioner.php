<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kuesioner extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }    
	
    public function index()
    {
        grantAccessFor('all');

        redirect('https://docs.google.com/forms/d/e/1FAIpQLSfBWqz7lGq6bJwzjmslM06NrJkLfK0Jpr7EmHYoVAReupVpng/viewform');
    }

   
}
