<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Md_evaluasi extends CI_Model
{
	// Add
	function addEv($data)
	{
		$this->db->insert('evaluasi', $data);
	}

	function update($where, $data)
	{
		$this->db->where($where);
		$this->db->update('evaluasi', $data);
	}

	function addEvNotif($data)
	{
		$this->db->insert('evaluasi_notif', $data);
	}

	public function deleteEvNotif($where)
	{
		$this->db->where($where);
		$this->db->delete('evaluasi_notif');
	}

	function addEvdetail($data)
	{
		$this->db->insert('evaluasi_detail', $data);
	}

	function updateEvDetail($where, $data)
	{
		$this->db->where($where);
		$this->db->update('evaluasi_detail', $data);
	}

	function getLastId()
	{
		return $this->db->select("*")->limit(1)->order_by('id', "DESC")->get('evaluasi')->row();
	}

	// fungsi reset urutan id pada tabel
	function reset_increment($tabel)
	{
		$this->db->query("ALTER TABLE " . $tabel . " AUTO_INCREMENT = 1");
	}

	function getAllEv()
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
							f.id as id_po,
							f.id_pengguna,
							f.jenis_evaluasi,
							f.smt,
							f.tahun,
							f.status,
							p.nama as pegawai,
							p.no_pegawai,
							p.jabatan as jabatan
							')
			->from('evaluasi f')
			->join('pengguna p', 'f.id_pengguna=p.pengguna_id')
			//->where('f.status = 1')
			->generate();
	}

	function getAllEvPenilai($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
							f.id as id_po,
							f.id_pengguna,
							f.jenis_evaluasi,
							f.smt,
							f.tahun,
							f.status,
							p.nama as pegawai,
							p.no_pegawai,
							p.jabatan as jabatan,
							en.id_penilai
							')
			->from('evaluasi f')
			->join('pengguna p', 'f.id_pengguna=p.pengguna_id')
			->join('evaluasi_notif en', 'f.id=en.id_evaluasi')
			->where('en.id_penilai', $id)
			//->where('f.status = 1')
			->generate();
	}

	function getAllEvMy($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
							f.id as id_po,
							f.id_pengguna,
							f.jenis_evaluasi,
							f.smt,
							f.tahun,
							f.status,
							p.nama as pegawai,
							p.no_pegawai,
							p.jabatan as jabatan
							')
			->from('evaluasi f')
			->join('pengguna p', 'f.id_pengguna=p.pengguna_id')
			->where('f.id_pengguna', $id)
			->where('f.status = 50')
			->generate();
	}

	function getEvById($id)
	{
		$this->db->select('
							f.id as id_po,
							f.id_pengguna,
							f.jenis_evaluasi,
							f.smt,
							f.tahun,
							f.status,
							p.nama as pegawai,
							p.no_pegawai,
							p.jabatan as jabatan
					')
			->from('evaluasi f')
			->join('pengguna p', 'f.id_pengguna=p.pengguna_id')
			->where('f.id', $id);
		return $this->db->get()->row();
	}


	function getDetailPOById($id)
	{
		$this->db->order_by('f.id', 'ASC');
		$this->db->select('
						f.id as id_pod,
						f.id_jobdesc,
						f.nilai,
						f.point,
						f.idPengguna,
						f.deskripsi,
						f.status
					')
			->from('jobdesc_detail f')
			->where('f.idPengguna', $id)
			->where('f.status = 1');
		return $this->db->get()->result();
	}

	public function getJobdescByPengguna($id_pengguna)
	{
		$this->db->select('id'); // Hanya ambil ID jobdesc
		$this->db->from('jobdesc_detail');
		$this->db->where('idPengguna', $id_pengguna);
		//$this->db->where('nilai', 1); // Hanya ambil yang memiliki nilai 1
		return $this->db->get()->result();
	}

	function getDetailBywhereID($where)
	{
		$this->db->select('
								f.id,
								f.id_evaluasi,
								f.idPengguna,
								f.id_jobdesc,
								f.penilaia,
								f.penilaib,
								f.penilaic,
								f.penilaid,
								f.penilaie,
								f.penilaif,
								f.nilaia,
								f.nilaib,
								f.nilaic,
								f.nilaid,
								f.nilaie,
								f.nilaif,
								f.masukana,
								f.masukanb,
								f.masukanc,
								f.masukand,
								f.masukane,
								f.masukanf,
								jd.deskripsi,
								jd.nilai
									')
			->from('evaluasi_detail f')
			->join('jobdesc_detail jd', 'f.id_jobdesc=jd.id')
			->where($where)
			->order_by('f.id', 'ASC');
		return $this->db->get()->result();
	}

	function getDetailBywhereIDDone($where)
	{
		$this->db->select('
								f.id,
								f.id_evaluasi,
								f.idPengguna,
								f.id_jobdesc,
								f.penilaia,
								f.penilaib,
								f.penilaic,
								f.penilaid,
								f.penilaie,
								f.penilaif,
								f.nilaia,
								f.nilaib,
								f.nilaic,
								f.nilaid,
								f.nilaie,
								f.nilaif,
								f.masukana,
								f.masukanb,
								f.masukanc,
								f.masukand,
								f.masukane,
								f.masukanf,
								jd.deskripsi,
								jd.nilai,
								p.nama as namaa,
								p2.nama as namab,
								p3.nama as namac,
								p4.nama as namad,
								p5.nama as namae,
								p6.nama as namaf
									')
			->from('evaluasi_detail f')
			->join('jobdesc_detail jd', 'f.id_jobdesc=jd.id')
			->join('pengguna p', 'f.penilaia=p.pengguna_id', 'left')
			->join('pengguna p2', 'f.penilaib=p2.pengguna_id', 'left')
			->join('pengguna p3', 'f.penilaic=p3.pengguna_id', 'left')
			->join('pengguna p4', 'f.penilaid=p4.pengguna_id', 'left')
			->join('pengguna p5', 'f.penilaie=p5.pengguna_id', 'left')
			->join('pengguna p6', 'f.penilaif=p6.pengguna_id', 'left')
			->where($where)
			->order_by('f.id', 'ASC');
		return $this->db->get()->result();
	}


	public function getDetailBywhereIDPengguna($where)
	{

		$subQuery = $this->db->select('jd.point')

			->from('evaluasi_detail f')

			->join('jobdesc_detail jd', 'f.id_jobdesc = jd.id')

			->where($where)

			->where('(

                  f.penilaia = ' . sessPenggunaId() . ' 

                  OR f.penilaib = ' . sessPenggunaId() . '

                  OR f.penilaic = ' . sessPenggunaId() . '

                  OR f.penilaid = ' . sessPenggunaId() . '

                  OR f.penilaie = ' . sessPenggunaId() . '

                  OR f.penilaif = ' . sessPenggunaId() . '

              )', NULL, FALSE)

			->get_compiled_select();



		$this->db->select('

              f.id,

              f.id_evaluasi,

              f.idPengguna,

              f.id_jobdesc,

              f.penilaia,

              f.penilaib,

              f.penilaic,

              f.penilaid,

              f.penilaie,

              f.penilaif,

            

              f.nilaia,

              f.nilaib,

              f.nilaic,

              f.nilaid,

              f.nilaie,

              f.nilaif,

              f.masukana,

              f.masukanb,

              f.masukanc,

              f.masukand,

              f.masukane,

              f.masukanf,

              jd.deskripsi,

              jd.nilai,

              jd.point,

              GROUP_CONCAT(DISTINCT p.nama SEPARATOR ", ") as nama_penilai

          ')

			->from('evaluasi_detail f')

			->join('jobdesc_detail jd', 'f.id_jobdesc = jd.id')

			->join(

				'pengguna p',

				'p.pengguna_id = f.penilaia 

              OR p.pengguna_id = f.penilaib 

              OR p.pengguna_id = f.penilaic 

              OR p.pengguna_id = f.penilaid 

              OR p.pengguna_id = f.penilaie 

              OR p.pengguna_id = f.penilaif',

				'left'

			)

			->where("jd.point IN ($subQuery)", NULL, FALSE) // Hanya data dengan point yang sama

			->where($where) // Pastikan hanya data dari f.id_evaluasi yang diminta

			->group_by([ // <-- INI ADALAH PERBAIKANNYA

				'f.id',
				'f.id_evaluasi',
				'f.idPengguna',
				'f.id_jobdesc',

				'f.penilaia',
				'f.penilaib',
				'f.penilaic',
				'f.penilaid',
				'f.penilaie',
				'f.penilaif',

				'f.nilaia',
				'f.nilaib',
				'f.nilaic',
				'f.nilaid',
				'f.nilaie',
				'f.nilaif',

				'f.masukana',
				'f.masukanb',
				'f.masukanc',
				'f.masukand',
				'f.masukane',
				'f.masukanf',

				'jd.deskripsi',
				'jd.nilai',
				'jd.point'

			])

			->order_by('f.id', 'ASC');



		return $this->db->get()->result();
	}

	public function getPenilaiByEvaluasiId($id_evaluasi)
	{
		$this->db->select('id_penilai');
		$this->db->from('evaluasi_notif');
		$this->db->where('id_evaluasi', $id_evaluasi);
		$query = $this->db->get();
		return $query->result();
	}

	function getAllPo()
	{
		$this->db->order_by('p.nama', 'ASC');
		return $this->datatables
			->select('
							f.id as id_po,
							f.id_pengguna,
							p.nama as pegawai,
							p.no_pegawai,
							p.jabatan as jabatan
							')
			->from('jobdesc f')
			->join('pengguna p', 'f.id_pengguna=p.pengguna_id')
			->generate();
	}

	function getAllDetailbyID($id)
	{
		$this->db->order_by('f.id', 'DESC');
		return $this->datatables
			->select('
								f.id as id_po,
								f.idPengguna,
								f.deskripsi,
								f.status
								')
			->from('jobdesc_detail f')
			->where('f.idPengguna', $id)
			->where('f.status = 1')
			->generate();
	}

	function getJobById($id)
	{
		$this->db->select('
							f.id as id_po,
							f.id_pengguna,
							p.nama as pegawai,
							p.no_pegawai,
							p.jabatan as jabatan
					')
			->from('jobdesc f')
			->join('pengguna p', 'f.id_pengguna=p.pengguna_id')
			->where('f.id', $id);
		return $this->db->get()->row();
	}

	function getById($id)
	{
		return $this->db->get_where('jobdesc_detail p', array('p.id' => $id))->result();
	}

	/* ====================================================================== */

	/* FUNGSI-FUNGSI BARU UNTUK PENILAIAN UMUM                                */
	/* Created By : Tengku Muhammad Zainul Aprilizar | 18 November 2025       */

	/* ====================================================================== */

	/**
	 * Mengambil data penilaian umum berdasarkan id_evaluasi
	 * Jika data tidak ada, kembalikan objek kosong agar view tidak error
	 */
	public function getPenilaianUmum($id_evaluasi)
	{

		$this->db->from('evaluasi_umum');

		$this->db->where('id_evaluasi', $id_evaluasi);

		$query = $this->db->get();



		if ($query->num_rows() > 0) {

			return $query->row(); // Kembalikan data jika ada

		} else {

			// Buat objek kosong agar view tidak error saat mengakses properti

			$fields = $this->db->list_fields('evaluasi_umum');

			$empty_obj = new stdClass();

			foreach ($fields as $field) {

				$empty_obj->$field = '';
			}

			return $empty_obj;
		}
	}

	/**
	 * Menyimpan atau Update data penilaian umum
	 * Menggunakan metode "upsert" (Update jika ada, Insert jika tidak ada)
	 */
	public function saveOrUpdatePenilaianUmum($data, $id_evaluasi)
	{

		// Cek apakah data untuk id_evaluasi ini sudah ada

		$this->db->where('id_evaluasi', $id_evaluasi);

		$this->db->from('evaluasi_umum');

		$exists = $this->db->count_all_results() > 0;



		if ($exists) {

			// Jika ada, update

			$this->db->where('id_evaluasi', $id_evaluasi);

			$this->db->update('evaluasi_umum', $data);
		} else {

			// Jika tidak ada, insert

			$data['id_evaluasi'] = $id_evaluasi; // Pastikan id_evaluasi ada di data

			$this->db->insert('evaluasi_umum', $data);
		}
	}

	/* ====================================================================== */
	/* FUNGSI RIWAYAT SEMESTER (Added Feature)                                */
	/* ====================================================================== */

	// Ambil semua history evaluasi milik user tertentu, diurutkan dari yang terbaru
	public function getHistoryEvaluasi($id_pengguna)
	{
		$this->db->select('id, smt, tahun, status, jenis_evaluasi');
		$this->db->from('evaluasi');
		$this->db->where('id_pengguna', $id_pengguna);
		// Urutkan tahun terbesar dlu, baru semester terbesar
		$this->db->order_by('tahun', 'DESC');
		$this->db->order_by('smt', 'DESC');
		return $this->db->get()->result();
	}
}
