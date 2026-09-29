<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Md_surat_part_two extends CI_Model
{


	// Add
	function addIzinJam($data)
	{
		$this->db->insert('surat_izin_jam_kerja', $data);
		return $this->db->insert_id();
	}

	function addCuti($data)
	{
		$this->db->insert('surat_cuti_tahunan', $data);
		return $this->db->insert_id();
	}

	// ==========================================
	// ========= Calendar Cuti Functions ========
	// ==========================================

	/**
	 * Get all approved leave data for calendar display
	 * @param int $year - Year to filter
	 * @param int $month - Month to filter (optional, 0 for all months)
	 * @return array - Leave events for calendar
	 */
	function getCutiForCalendar($year = null, $month = null)
	{
		if ($year === null) {
			$year = date('Y');
		}

		// 1. Get regular employee leaves
		$this->db->select('
			c.id,
			c.kode_cuti,
			c.id_pengaju,
			c.alasan,
			c.total,
			c.tgl_awal,
			c.tgl_akhir,
			c.status,
			p.nama as nama_karyawan,
			p.jabatan,
			d.nama as nama_divisi
		');
		$this->db->from('surat_cuti_tahunan c');
		$this->db->join('pengguna p', 'c.id_pengaju = p.pengguna_id', 'left');
		$this->db->join('divisi d', 'p.id_divisi = d.id_divisi', 'left');
		// Hanya tampilkan status yang bukan ditolak (0=Baru, 1=GA Approved, 2=HR Approved, 3=GM Approved)
		$this->db->where_in('c.status', array(0, 1, 2, 3));
		$this->db->where('c.jenis', 3); // cuti tahunan
		$this->db->where('YEAR(c.tgl_awal)', $year);

		if ($month !== null && $month > 0) {
			$this->db->group_start();
			$this->db->where('MONTH(c.tgl_awal)', $month);
			$this->db->or_where('MONTH(c.tgl_akhir)', $month);
			$this->db->group_end();
		}

		$this->db->order_by('c.tgl_awal', 'ASC');

		$result = $this->db->get()->result();

		$events = array();
		foreach ($result as $row) {
			// Determine color based on status
			$color = '#6c757d'; // default gray
			$statusText = 'Diajukan';

			if ($row->status == 0) {
				$color = '#ffc107'; // warning - baru diajukan
				$statusText = 'Baru Diajukan';
			} else if ($row->status == 1) {
				$color = '#17a2b8'; // info - disetujui GA
				$statusText = 'Disetujui GA';
			} else if ($row->status == 2) {
				$color = '#007bff'; // primary - disetujui HR
				$statusText = 'Disetujui HR';
			} else if ($row->status == 3) {
				$color = '#28a745'; // success - fully approved
				$statusText = 'Disetujui';
			} else if ($row->status == 4) {
				$color = '#dc3545'; // danger - ditolak
				$statusText = 'Ditolak';
			}

			$events[] = array(
				'id' => 'reg_' . $row->id,
				'title' => $row->nama_karyawan,
				'start' => $row->tgl_awal,
				'end' => date('Y-m-d', strtotime($row->tgl_akhir . ' +1 day')), // FullCalendar end date is exclusive
				'color' => $color,
				'extendedProps' => array(
					'kode_cuti' => $row->kode_cuti,
					'nama_karyawan' => $row->nama_karyawan,
					'jabatan' => $row->jabatan,
					'divisi' => $row->nama_divisi,
					'alasan' => $row->alasan,
					'total_hari' => $row->total,
					'tgl_awal' => date('d-m-Y', strtotime($row->tgl_awal)),
					'tgl_akhir' => date('d-m-Y', strtotime($row->tgl_akhir)),
					'status' => $statusText,
					'status_code' => $row->status
				)
			);
		}

		// 2. Get SGM employee leaves
		$this->db->select('
			c.id,
			c.kode as kode_cuti,
			c.id_pengaju,
			c.alasan,
			c.total,
			c.tgl_awal,
			c.tgl_akhir,
			c.status,
			p.nama as nama_karyawan,
			p.jabatan,
			d.nama as nama_divisi
		');
		$this->db->from('surat_sgm c');
		$this->db->join('pengguna p', 'c.id_pengaju = p.pengguna_id', 'left');
		$this->db->join('divisi d', 'p.id_divisi = d.id_divisi', 'left');
		// Hanya tampilkan status yang bukan ditolak (0=Baru, 1=GA Approved, 2=HR Approved/Fully Approved)
		$this->db->where_in('c.status', array(0, 1, 2));
		$this->db->where('c.jenis', 3); // cuti SGM
		$this->db->where('YEAR(c.tgl_awal)', $year);

		if ($month !== null && $month > 0) {
			$this->db->group_start();
			$this->db->where('MONTH(c.tgl_awal)', $month);
			$this->db->or_where('MONTH(c.tgl_akhir)', $month);
			$this->db->group_end();
		}

		$this->db->order_by('c.tgl_awal', 'ASC');

		$result_sgm = $this->db->get()->result();

		foreach ($result_sgm as $row) {
			// Determine color based on status
			$color = '#6c757d'; // default gray
			$statusText = 'Diajukan';

			if ($row->status == 0) {
				$color = '#ffc107'; // warning - baru diajukan
				$statusText = 'Baru Diajukan';
			} else if ($row->status == 1) {
				$color = '#17a2b8'; // info - disetujui GA
				$statusText = 'Disetujui GA';
			} else if ($row->status == 2) {
				$color = '#28a745'; // success - fully approved for SGM
				$statusText = 'Disetujui';
			} else if ($row->status == 6 || $row->status == 7) {
				$color = '#dc3545'; // danger - ditolak
				$statusText = 'Ditolak';
			}

			$events[] = array(
				'id' => 'sgm_' . $row->id,
				'title' => $row->nama_karyawan,
				'start' => $row->tgl_awal,
				'end' => date('Y-m-d', strtotime($row->tgl_akhir . ' +1 day')), // FullCalendar end date is exclusive
				'color' => $color,
				'extendedProps' => array(
					'kode_cuti' => $row->kode_cuti,
					'nama_karyawan' => $row->nama_karyawan,
					'jabatan' => $row->jabatan,
					'divisi' => $row->nama_divisi,
					'alasan' => $row->alasan,
					'total_hari' => $row->total,
					'tgl_awal' => date('d-m-Y', strtotime($row->tgl_awal)),
					'tgl_akhir' => date('d-m-Y', strtotime($row->tgl_akhir)),
					'status' => $statusText,
					'status_code' => $row->status,
					'is_sgm' => true
				)
			);
		}

		return $events;
	}

	/**
	 * Get leave count per day for a specific month
	 * @param int $year
	 * @param int $month
	 * @return array
	 */
	function getCutiCountPerDay($year, $month)
	{
		$startDate = date('Y-m-01', strtotime("$year-$month-01"));
		$endDate = date('Y-m-t', strtotime("$year-$month-01"));

		$this->db->select('
			c.tgl_awal,
			c.tgl_akhir,
			p.nama as nama_karyawan
		');
		$this->db->from('surat_cuti_tahunan c');
		$this->db->join('pengguna p', 'c.id_pengaju = p.pengguna_id', 'left');
		$this->db->where('c.status !=', 5);
		$this->db->where('c.jenis', 3);
		$this->db->where("(c.tgl_awal <= '$endDate' AND c.tgl_akhir >= '$startDate')");

		return $this->db->get()->result();
	}






	function addNotifikasi($data)
	{
		$this->db->insert('notifikasipengguna', $data);
	}


	//GET
	function getIzinJamKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_izin_jam_kerja', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}

	function getSijkKodeId()
	{
		$this->db->select("COUNT(*) as id")
			->from('surat_izin_jam_kerja')
			->where(array(
				'YEAR(tgl_pengajuan)' => date('Y'),
				'jenis' => '1',  // Gantilah $jenis dengan nilai yang sesuai
			))
			->order_by('id', 'DESC')
			->limit(1);

		$result = $this->db->get()->row();
		return $result;
	}



	function getSimpKodeId()
	{
		$this->db->select("COUNT(*) as id")
			->from('surat_izin_jam_kerja')
			->where(array(
				'YEAR(tgl_pengajuan)' => date('Y'),
				'jenis' => '2',  // Gantilah $jenis dengan nilai yang sesuai
			))
			->order_by('id', 'DESC')
			->limit(1);

		$result = $this->db->get()->row();
		return $result;
	}

	function getCutiKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_cuti_tahunan', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}





	//Surat Izin Jam Kerja
	function getAllSIJK()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode_ijk,
						f.id_pengaju as idPengaju,
						f.tanggal,
						f.jam_mulai,
						f.jam_akhir,
						f.tgl_awal,
						f.tgl_akhir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_izin_jam_kerja f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 1')
			->generate();
	}

	//Surat Izin Meninggalkan Pekerjaan
	function getAllSIMP()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode_ijk,
						f.id_pengaju as idPengaju,
						f.tanggal,
						f.jam_mulai,
						f.jam_akhir,
						f.tgl_awal,
						f.tgl_akhir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_izin_jam_kerja f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 2')
			->generate();
	}


	//Surat Cuti Tahunan
	function getAllCUTI()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode_cuti,
						f.id_pengaju as idPengaju,
						f.tgl_awal,
						f.tgl_akhir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_cuti_tahunan f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 3')
			->generate();
	}

	//=======================================
	//=========      Berita Acara    ========
	//=======================================

	function addBeritaAcara($data)
	{
		$this->db->insert('surat_berita_acara', $data);
	}

	function getBaKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_berita_acara', array('YEAR(`created_at`)' => date('Y')))->row();
	}


	function getAllBA()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.kode_ba,
						f.tanggal,
						f.hasil,
						f.analisis,
						f.id_pengaju as idPengaju,
						f.id_diketahui,
						f.id_disetujui,
						f.status,
						f.ttd_dir,
						f.status_dir,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as nama_diketahui,
						p2.short_name as nama_ttd_diketahui,
						p2.jabatan as jabatan_diketahui,
						p3.nama as nama_disetujui,
						p3.short_name as nama_ttd_disetujui,
						p3.jabatan as jabatan_disetujui
						')
			->from('surat_berita_acara f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->join('pengguna p2', 'f.id_diketahui=p2.pengguna_id')
			->join('pengguna p3', 'f.id_disetujui=p3.pengguna_id')
			->where('f.status != 5')
			->generate();
	}

	function getBAbyID($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.kode_ba,
						f.tanggal,
						f.hasil,
						f.analisis,
						f.id_pengaju as idPengaju,
						f.id_diketahui,
						f.id_disetujui,
						f.status,
						f.ttd_dir,
						f.status_dir,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as nama_diketahui,
						p2.short_name as nama_ttd_diketahui,
						p2.jabatan as jabatan_diketahui,
						p3.nama as nama_disetujui,
						p3.short_name as nama_ttd_disetujui,
						p3.jabatan as jabatan_disetujui
						')
			->from('surat_berita_acara f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->join('pengguna p2', 'f.id_diketahui=p2.pengguna_id')
			->join('pengguna p3', 'f.id_disetujui=p3.pengguna_id')
			->where('f.status != 5')
			->where('f.id_pengaju', $id)
			->generate();
	}



	function getBAbySet($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.kode_ba,
						f.tanggal,
						f.hasil,
						f.analisis,
						f.id_pengaju as idPengaju,
						f.id_diketahui,
						f.id_disetujui,
						f.ttd_dir,
						f.status_dir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as nama_diketahui,
						p2.short_name as nama_ttd_diketahui,
						p2.jabatan as jabatan_diketahui,
						p3.nama as nama_disetujui,
						p3.short_name as nama_ttd_disetujui,
						p3.jabatan as jabatan_disetujui
						')
			->from('surat_berita_acara f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->join('pengguna p2', 'f.id_diketahui=p2.pengguna_id')
			->join('pengguna p3', 'f.id_disetujui=p3.pengguna_id')
			->where('f.status != 5')
			->where('(f.id_disetujui = ' . $id . ' OR f.id_diketahui = ' . $id . ')')
			->generate();
	}
	function getBADirbySet()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.kode_ba,
						f.tanggal,
						f.hasil,
						f.analisis,
						f.id_pengaju as idPengaju,
						f.id_diketahui,
						f.id_disetujui,
						f.ttd_dir,
						f.status_dir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						p2.nama as nama_diketahui,
						p2.short_name as nama_ttd_diketahui,
						p2.jabatan as jabatan_diketahui,
						p3.nama as nama_disetujui,
						p3.short_name as nama_ttd_disetujui,
						p3.jabatan as jabatan_disetujui
						')
			->from('surat_berita_acara f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->join('pengguna p2', 'f.id_diketahui=p2.pengguna_id')
			->join('pengguna p3', 'f.id_disetujui=p3.pengguna_id')
			->where('f.status != 5')
			->where('f.status_dir = 1')
			->generate();
	}


	function getBeritaAcaraById($id)
	{
		$this->db->select('
					f.id as id_ba,
					f.kode_ba,
					f.analisis,
					f.hasil,
					f.penanganan,
					f.tanggal,
					f.id_pengaju as idPengaju,
					f.id_diketahui,
					f.id_disetujui,
					f.lampiran,
					f.status,
					f.ttd_diketahui,
					f.ttd_disetujui,
					f.ttd_dir,
					f.id_dir,
					f.status_dir,
					p.nama as pengaju,
					p.short_name as nama_ttd,
					p.jabatan as jabatan,
					p2.nama as nama_diketahui,
					p2.short_name as nama_ttd_diketahui,
					p2.jabatan as jabatan_diketahui,
					p3.nama as nama_disetujui,
					p3.short_name as nama_ttd_disetujui,
					p3.jabatan as jabatan_disetujui,
					p4.nama as nama_dir,
					p4.jabatan as jabatan_dir
				')
			->from('surat_berita_acara f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->join('pengguna p2', 'f.id_diketahui=p2.pengguna_id')
			->join('pengguna p3', 'f.id_disetujui=p3.pengguna_id')
			->join('pengguna p4', 'f.id_dir=p4.pengguna_id', 'left')
			->where('f.id', $id);
		return $this->db->get()->result();
	}

	function updateBeritaAcara($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_berita_acara', $data);
	}


	public function getBywhereActive($excludedIds = [110, 79, 54])
	{
		$this->db->select('*');
		$this->db->from('pengguna p');

		// 1. Harus Aktif
		$this->db->where('p.is_active', 1);

		// 2. Kecualikan ID tertentu
		if (!empty($excludedIds)) {
			$this->db->where_not_in('p.pengguna_id', $excludedIds);
		}

		// 3. Harus memiliki data NPP (Tidak NULL dan tidak kosong)
		$this->db->where('p.no_pegawai IS NOT NULL');
		$this->db->where('p.no_pegawai !=', '');

		$this->db->order_by('p.nama', 'ASC');

		$query = $this->db->get();
		return $query->result();
	}

	//=======================================
	//=========   END Berita Acara    =======
	//=======================================


	//Surat Izin Jam Kerja
	function getAllSIJKbyID($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode_ijk,
						f.id_pengaju as idPengaju,
						f.tanggal,
						f.jam_mulai,
						f.jam_akhir,
						f.tgl_awal,
						f.tgl_akhir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_izin_jam_kerja f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 1')
			->where('f.id_pengaju', $id)
			->generate();
	}

	//Surat Izin Meninggalkan Pekerjaan
	function getAllSIMPbyID($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode_ijk,
						f.id_pengaju as idPengaju,
						f.tanggal,
						f.jam_mulai,
						f.jam_akhir,
						f.tgl_awal,
						f.tgl_akhir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_izin_jam_kerja f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 2')
			->where('f.id_pengaju', $id)
			->generate();
	}


	//Surat Izin Meninggalkan Pekerjaan
	function getAllCUTIbyID($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode_cuti,
						f.id_pengaju as idPengaju,
						f.tgl_awal,
						f.tgl_akhir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_cuti_tahunan f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 3')
			->where('f.id_pengaju', $id)
			->generate();
	}



	//Surat Izin Jam Kerja
	function getSijkById($id)
	{
		$this->db->select('
						f.id as id_Sijk,
						f.jenis_izin,
						f.alasan,
						f.total,
						f.kode_ijk,
						f.jam_mulai,
						f.jam_akhir,
						f.tgl_awal,
						f.tgl_akhir,
						f.id_pengaju as idPengaju,
						f.tanggal,
						f.kota_pengajuan as kota_pengajuan,
						f.tgl_pengajuan as tgl_Pengajuan,
						f.lampiran,
						f.status,
						f.ttd_1,
						f.ttd_2,
						f.ttd_3,
						p.nama as pengaju,
						p.short_name as nama_ttd,
						p.jabatan as jabatan
					')
			->from('surat_izin_jam_kerja f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.id', $id);
		return $this->db->get()->result();
	}

	//Cuti Tahunan
	function getCutiById($id)
	{
		$this->db->select('
						f.id as id_Sijk,
						f.jenis_izin,
						f.alasan,
						f.total,
						f.kode_cuti,
						f.tgl_awal,
						f.tgl_akhir,
						f.id_pengaju as idPengaju,
						f.kota_pengajuan as kota_pengajuan,
						f.tgl_pengajuan as tgl_Pengajuan,
						f.lampiran,
						f.status,
						f.ttd_1,
						f.ttd_2,
						f.ttd_3,
						p.nama as pengaju,
						p.short_name as nama_ttd,
						p.jabatan as jabatan
					')
			->from('surat_cuti_tahunan f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.id', $id);
		return $this->db->get()->result();
	}


	// fungsi reset urutan id pada tabel
	function reset_increment($tabel)
	{
		$this->db->query("ALTER TABLE " . $tabel . " AUTO_INCREMENT = 1");
	}


	//UPDATE Surat Izin Jam Kerja
	function updateSijk($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_izin_jam_kerja', $data);
	}

	function updateCuti($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_cuti_tahunan', $data);
	}

	//=======================================
	//=========   Pengalaman Kerja    =======
	//=======================================


	function getAllPaklaring()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
							f.id as idGc,
							f.kode,
							f.id_pengaju as idPengaju,
							f.id_pegawai as idPegawai,
							f.ttd_1,
							f.status,
							f.kota_pengajuan as kota_pengajuan,
							f.tgl_pengajuan as tgl_Pengajuan,
							p.nama as pegawai,
							p.nik as nik,
							p.tgl_masuk as tgl_masuk,
							p.tgl_keluar as tgl_keluar,
							p.jabatan as jabatan
							')
			->from('surat_paklaring f')
			->join('pengguna p', 'f.id_pegawai=p.pengguna_id')
			->where('f.status != 5')
			->generate();
	}

	function getPaklaringById($id)
	{
		$this->db->select('
							f.id as idGc,
							f.kode,
							f.id_pengaju as idPengaju,
							f.id_pegawai as idPegawai,
							f.ttd_1,
							f.status,
							f.kota_pengajuan as kota_pengajuan,
							f.tgl_pengajuan as tgl_Pengajuan,
							p.nama as pegawai,
							p.nik as nik,
							p.tgl_masuk as tgl_masuk,
							p.tgl_keluar as tgl_keluar,
							p.jabatan as jabatan
				')
			->from('surat_paklaring f')
			->join('pengguna p', 'f.id_pegawai=p.pengguna_id')
			->where('f.id', $id);
		return $this->db->get()->result();
	}

	function updatePaklaring($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_paklaring', $data);
	}

	function addPaklaring($data)
	{
		$this->db->insert('surat_paklaring', $data);
	}

	function getPaklaringKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_paklaring', array('YEAR(`tgl_pengajuan`)' => date('Y')))->row();
	}



	//=======================================
	//=========   END Pengalaman Kerja ======
	//=======================================


	//=======================================
	//=========   START Meeting				 ======
	//=======================================


	function addMeeting($data)
	{
		$this->db->insert('surat_meetingroom', $data);
	}

	function getMeetingKodeId()
	{
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id', "DESC")->get_where('surat_meetingroom', array('YEAR(`created_at`)' => date('Y')))->row();
	}

	function updateMeeting($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_meetingroom', $data);
	}


	function getAllMeeting()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
							f.id as idGc,
							f.kode,
							f.perihal,
							f.id_pengaju as idPengaju,
							f.ttd_1,
							f.ttd_2,
							f.tgl_pengajuan,
							f.status,
							p.nama,
							p.nik as nik,
							p.jabatan as jabatan
							')
			->from('surat_meetingroom f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->generate();
	}


	function getAllMeetingBy($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
							f.id as idGc,
							f.kode,
							f.perihal,
							f.id_pengaju as idPengaju,
							f.ttd_1,
							f.ttd_2,
							f.tgl_pengajuan,
							f.status,
							p.nama,
							p.nik as nik,
							p.jabatan as jabatan
						')
			->from('surat_meetingroom f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.id_pengaju', $id)
			->generate();
	}


	function getMeetingById($id)
	{
		$this->db->select('
							f.id as idMeet,
							f.kode,
							f.perihal,
							f.id_pengaju as idPengaju,
							f.ttd_1,
							f.ttd_2,
							f.tgl_pengajuan,
							f.jam_mulai,
							f.jam_akhir,
							f.status,
							f.created_at,
							f.lampiran,
							p.nama,
							p.nik as nik,
							p.jabatan as jabatan
				')
			->from('surat_meetingroom f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.id', $id);
		return $this->db->get()->result();
	}




	//=======================================
	//=========   END Meeting					 ======
	//=======================================


	//=======================================
	//=========   Surat SGM					 ========
	//=======================================


	function addSgm($data)
	{
		$this->db->insert('surat_sgm', $data);
	}

	function updateSgm($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('surat_sgm', $data);
	}



	function getAllById($id)
	{
		$this->db->select('
						f.id as id_Sijk,
						f.jenis_izin,
						f.alasan,
						f.total,
						f.kode,
						f.jam_mulai,
						f.jam_akhir,
						f.tgl_awal,
						f.tgl_akhir,
						f.id_pengaju as idPengaju,
						f.kota_pengajuan as kota_pengajuan,
						f.tgl_pengajuan as tgl_Pengajuan,
						f.lampiran,
						f.status,
						f.ttd_1,
						f.ttd_2,
						p.nama as pengaju,
						p.short_name as nama_ttd,
						p.jabatan as jabatan
					')
			->from('surat_sgm f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.id', $id);
		return $this->db->get()->result();
	}


	//Surat Izin Jam Kerja SGM
	function getSijkSgmKodeId()
	{
		$this->db->select("COUNT(*) as id")
			->from('surat_sgm')
			->where(array(
				'YEAR(tgl_pengajuan)' => date('Y'),
				'jenis' => '1',  // Gantilah $jenis dengan nilai yang sesuai
			))
			->order_by('id', 'DESC')
			->limit(1);

		$result = $this->db->get()->row();
		return $result;
	}

	function getAllSIJKSGMbyID($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode,
						f.id_pengaju as idPengaju,
						f.jam_mulai,
						f.jam_akhir,
						f.tgl_awal,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_sgm f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 1')
			->where('f.id_pengaju', $id)
			->generate();
	}


	function getAllSIJKSGM()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode,
						f.id_pengaju as idPengaju,
						f.jam_mulai,
						f.jam_akhir,
						f.tgl_awal,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_sgm f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 1')
			->generate();
	}


	//Surat Izin Meninggalkan Pekerjaan SGM
	function getSimpSgmKodeId()
	{
		$this->db->select("COUNT(*) as id")
			->from('surat_sgm')
			->where(array(
				'YEAR(tgl_pengajuan)' => date('Y'),
				'jenis' => '2',  // Gantilah $jenis dengan nilai yang sesuai
			))
			->order_by('id', 'DESC')
			->limit(1);

		$result = $this->db->get()->row();
		return $result;
	}

	function getAllSimpSGMbyID($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode,
						f.id_pengaju as idPengaju,
						f.jam_mulai,
						f.jam_akhir,
						f.tgl_awal,
						f.tgl_akhir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_sgm f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 2')
			->where('f.id_pengaju', $id)
			->generate();
	}


	function getAllSimpSGM()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode,
						f.id_pengaju as idPengaju,
						f.jam_mulai,
						f.jam_akhir,
						f.tgl_awal,
						f.tgl_akhir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_sgm f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 2')
			->generate();
	}



	//Surat CUTI SGM
	function getCutiSgmKodeId()
	{
		$this->db->select("COUNT(*) as id")
			->from('surat_sgm')
			->where(array(
				'YEAR(tgl_pengajuan)' => date('Y'),
				'jenis' => '3',  // Gantilah $jenis dengan nilai yang sesuai
			))
			->order_by('id', 'DESC')
			->limit(1);

		$result = $this->db->get()->row();
		return $result;
	}


	function getAllCutiSGMbyID($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode,
						f.id_pengaju as idPengaju,
						f.tgl_awal,
						f.tgl_akhir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_sgm f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 3')
			->where('f.id_pengaju', $id)
			->generate();
	}


	function getAllCutiSGM()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
						f.id as idGc,
						f.alasan,
						f.total,
						f.kode,
						f.id_pengaju as idPengaju,
						f.tgl_awal,
						f.tgl_akhir,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan
						')
			->from('surat_sgm f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->where('f.status != 5')
			->where('f.jenis = 3')
			->generate();
	}
	//=======================================
	//=========   END Surat SGM				=======
	//=======================================

}
