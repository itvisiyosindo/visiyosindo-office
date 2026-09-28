<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Rest extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_pengguna');
		// $this->load->model('md_anggota');
		$this->load->model('md_log');
		$this->load->model('md_notifikasi');
		// $this->load->model('md_wilayah');
		// $this->load->model('md_grup');
		// $this->load->model('md_riwayat_kelas');
		$this->load->helper('email_helper');
		date_default_timezone_set('Asia/Jakarta');
	}


	public function index_post()
	{
		
		$email      = $this->input->post('email');
		$password   = hash('sha512', $this->input->post('password'));


		$pengguna   = $this->md_pengguna->getByWhere(['p.email' => $email, 'p.password' => $password, 'p.status' => 1]);

		if ($pengguna) {
			if ($pengguna[0]->is_active == '0') {
				$this->response( [
					'status' => false,
					'message' => 'Not Auth',
				], 302 );
				die;
			}

			$this->response( [
				'status' => TRUE,
				'message' => 'User found',
				'data' => $pengguna
			], 200 );
		
		} else {
			$this->response( [
				'status' => false,
				'message' => 'No users were found'
			], 404 );
		}
	}




}
