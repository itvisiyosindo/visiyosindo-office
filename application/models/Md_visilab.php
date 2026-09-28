<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_visilab extends CI_Model {

    function getStokKodeId()
		{
			$this->db->select("COUNT(*) as id")
					->from('visilab_stok')
					->where(array(
							'YEAR(created_at)' => date('Y')
					))
					->order_by('id', 'DESC')
					->limit(1);

			$result = $this->db->get()->row();
			return $result;
		}

		
  function getStokLastId(){
		return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('visilab_stok')->row();
	}

    

    function reset_increment($tabel){
			$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
		}

    function add($data)
    {
        $this->db->insert('visilab_stok', $data);
    }

		function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('visilab_stok', $data);
    }



		function addDetail($data)
    {
        $this->db->insert('visilab_stok_detail', $data);
    }

		function updateSoDetail($where, $data)
    {
        $this->db->where($where);
        $this->db->update('visilab_stok_detail', $data);
    }

    function getAll()
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
				->select('
					f.id as idGc,
					f.kode,
					f.id_pengaju as idPengaju,
					f.status,
					f.created_at,
					p.nama as pengaju
					')
				->from('visilab_stok f')
				->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
				->generate();
		}

		function getBywhereID($where)
    {
				$this->db->select('
							f.id as idGc,
							f.kode,
							f.id_pengaju as idPengaju,
							f.id_setujui,
							f.status,
							f.kota_pengajuan as kota_aju,
							f.created_at,
							f.penggunaan,
							f.link_lampiran,
							f.ttd_1,
							p.nama as pengaju,
							p.jabatan as jabatan,
							p.jabatan_visilab as jabatan_visilab,
							p2.nama as setujui,
							p2.jabatan as jabatan2,
							p2.jabatan_visilab as jabatan2_visilab
								')
					->from('visilab_stok f')
					->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
					->join('pengguna p2', 'f.id_setujui=p2.pengguna_id')
					->where($where)
					->order_by('f.created_at', 'DESC');
        return $this->db->get()->result();
    }

		function getDetailBywhereID($where)
    {
				$this->db->select('
							f.id,
							f.id_vs,
							f.nama_alat,
							f.id_pengguna as idPengaju,
							f.no_seri,
							f.waktu_keluar,
							f.waktu_masuk,
							f.ket,
							f.created_at,
							va.nama,
							va.serial_number
								')
					->from('visilab_stok_detail f')
					->join('visilab_alat va', 'f.nama_alat=va.id')
					->where($where)
					->order_by('f.created_at', 'ASC');
        return $this->db->get()->result();
    }

		function getDetailByMonth($where)
		{
				$this->db->select('
										f.id,
										f.id_vs,
										f.nama_alat,
										f.id_pengguna as idPengaju,
										f.no_seri,
										f.waktu_keluar,
										f.waktu_masuk,
										f.penggunaan_alat,
										f.ket,
										f.created_at,
										p.nama as pengaju,
										va.nama,
										va.serial_number
								')
						->from('visilab_stok_detail f')
						->join('pengguna p', 'f.id_pengguna=p.pengguna_id')
						->join('visilab_alat va', 'f.nama_alat=va.id')
						// Menggunakan DATE_FORMAT untuk menyesuaikan dengan format YYYY-MM
						->where("DATE_FORMAT(f.created_at, '%Y-%m') =", $where['f.created_at'])
						->order_by('f.created_at', 'ASC');
				
				return $this->db->get()->result();
		}



// ==============================================
// ============ Data Alat Visilab ===============
// ==============================================

		function addAlat($data)
    {
        $this->db->insert('visilab_alat', $data);
    }

    function updateAlat($where, $data)
    {
        $this->db->where($where);
        $this->db->update('visilab_alat', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('visilab_alat b', array('b.id' => $id))->result();
    }

    function getByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('visilab_alat g')->result();
    }

    function getAllAlat(){
            
        return $this->datatables
        ->select('  
            b.id,
            b.nama,
            b.serial_number,
            b.tgl_kalibrasi,
            b.tgl_masa_kalibrasi,
            b.tempat_kalibrasi,
            b.status,
            b.created_at

        ')
        ->from('visilab_alat b')
        ->where('b.status = 1')
        ->generate();        
    }

		function getAlat()
    {
        $this->db->order_by('k.nama', 'ASC');
        return $this->db->get('visilab_alat k')->result();
    }

  

    

// ==============================================
// ======== END Data Alat Visilab ===============
// ==============================================



// ==============================================
// ========= Start Approval Harga ===============
// ==============================================

function getApprovalKodeId(){
		return $this->db->select("COUNT(*) as id_approval")->limit(1)->order_by('id_approval',"DESC")->get_where('approval_visilab',array('YEAR(`tgl`)' => date('Y')))->row();
}

function addSurat($surat="", $data)
    {
		if($surat=="list"){
			$tabel = "surat_list";
		}else if($surat=="approval"){
			$tabel = "approval_visilab";
		}else if($surat=="dapproval"){
			$tabel = "approval_visilab_detail";
		}else{
			$tabel=$surat;
		}
		
        $this->db->insert($tabel, $data);
}

function getApprovalLastId(){
		return $this->db->select("*")->limit(1)->order_by('id_approval',"DESC")->get('approval_visilab')->row();
}


function getAllApproval(){
		$this->db->order_by('approval.id_approval', 'DESC');
        return $this->datatables
            ->select('
                approval.id_approval as id_approval,
								approval.kode as kode,
								approval.id_kat_surat as id_kat_surat,
								approval.id_pengguna as idPengaju,
								approval.tgl as tgl,
								approval.nama as nama,
								approval.nama_customer as nama_customer,
								approval.detail_order as detail_order,
								approval.status,
								p.nama as pengaju
            ')
        ->from('approval_visilab approval')
				->join('pengguna p', 'approval.id_pengguna=p.pengguna_id')
        ->generate();
}

function getApprovalById($id){ 
            $this->db->select('
        approval.id_approval as id_approval,
				approval.kode as kode,
				approval.id_kat_surat as id_kat_surat,
				approval.id_pengguna as idPengaju,
				approval.tgl as tgl,
				approval.nama as nama,
				approval.nama_customer as nama_customer,
				approval.detail_order as detail_order,
				approval.ttd_2 as ttd_2,
				approval.ttd_1 as ttd_1,
				approvald.nama_barang as nama_barang,
				approvald.acuan_hrg as acuan_hrg,
				approvald.hrg_ditawarkan as hrg_ditawarkan,
				approvald.approvall as approvall,
				approvald.trf_komisi as trf_komisi,
				approvald.catatan as catatan,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				p.jabatan_visilab as jabatan_visilab
            ')
      ->from('approval_visilab approval')
      ->join('approval_visilab_detail approvald', 'approval.id_approval=approvald.id_approval')
			->join('pengguna p', 'approval.id_pengguna=p.pengguna_id')
			->where('approval.id_approval', $id);
			return $this->db->get()->result();
}

function getDetailApproval($idapproval){
            $this->db->select('
                dAPPROVAL.id as id,
                sAPPROVAL.id_approval as idapproval,
                sAPPROVAL.kode as kode,
								dAPPROVAL.nama_barang as nama_barang,
								dAPPROVAL.acuan_hrg as acuan_hrg,
								dAPPROVAL.hrg_ditawarkan as hrg_ditawarkan,
								masterjenisapproval.keterangan as modal,
								dAPPROVAL.approvall as approvall,
								dAPPROVAL.trf_komisi as trf_komisi,
								dAPPROVAL.catatan as catatan,
            ') 
            ->from('approval_visilab_detail dAPPROVAL')
			->join('approval_visilab sAPPROVAL', 'dAPPROVAL.id_approval=sAPPROVAL.id_approval')
			->join('masterjenisapproval','masterjenisapproval.id=dAPPROVAL.modal','left')
			->where('dAPPROVAL.id_approval', $idapproval);
			return $this->db->get()->result();
}

function getDetailApprovalrevisicount($idapproval){
           $query =  $this->db
 		->select('iddetail, COUNT(iddetail) as total')
		->group_by('iddetail')
		->where('id_approval',$idapproval)
		->order_by('iddetail', 'asc')
    	 ->get('logapproval');
        return $query->result();
}

function getDetailApprovalModaltable($idapproval){
           return $this->datatables->select('
						  	dAPPROVAL.id,
                sAPPROVAL.id_approval as idapproval,
                sAPPROVAL.kode as kode,
				dAPPROVAL.nama_barang as nama_barang,
				dAPPROVAL.acuan_hrg as acuan_hrg,
				dAPPROVAL.hrg_ditawarkan as hrg_ditawarkan,
				dAPPROVAL.modal as modal,
				dAPPROVAL.approvall as approvall,
				dAPPROVAL.trf_komisi as trf_komisi,
				dAPPROVAL.catatan as catatan,
            ') 
            ->from('approval_visilab_detail dAPPROVAL')
			->join('approval_visilab sAPPROVAL', 'dAPPROVAL.id_approval=sAPPROVAL.id_approval')
			->where('dAPPROVAL.id_approval', $idapproval)
			->generate();
}

function updatemodalapproval($id, $modal){		 
     $this->db->where('id', $id);
     $this->db->update('approval_visilab_detail', array('modal' => $modal));
}

function updateSuratApprovall($id, $data){
		$this->db->where('id_approval', $id);
		$this->db->update('approval_visilab_detail', $data); 
	}

function updateSuratApprovallDetail($id, $data){
		$this->db->where('id', $id);
		$this->db->update('approval_visilab_detail', $data); 
}

function getDetailApprovalById($id)
{
    return $this->db->get_where('approval_visilab_detail p', array('p.id' => $id))->result();
}

function update_approval($id, $data)
{
        $this->db->where('id_approval', $id);
        $this->db->update('approval_visilab', $data);
}



// ==============================================
// ======== END Start Approval Harga ============
// ==============================================


// ==============================================
// ====================== Start SUHU ============
// ==============================================


function addSuhu($data)
    {
        $this->db->insert('visilab_suhu', $data);
    }

    function updateSuhu($where, $data)
    {
        $this->db->where($where);
        $this->db->update('visilab_suhu', $data);
    }

    function getSuhuById($id)
    {
        return $this->db->get_where('visilab_suhu b', array('b.id' => $id))->result();
    }

    function getSuhuByWhere($where="")
    {
        $this->db->where($where);
        return $this->db->get('visilab_suhu g')->result();
    }

    function getAllSuhu(){

				if ($this->input->post('filter_month')) {
            $this->datatables->where("DATE_FORMAT(b.created_at,'%Y-%m')", $this->input->post('filter_month'));
        } else {
            $this->db->where("DATE_FORMAT(b.created_at,'%Y-%m')", date('Y-m'));
        }

        $this->db->order_by('b.id', 'DESC');  
        return $this->datatables
        ->select('  
            b.id,
            b.suhu,
            b.kelembapan,
            b.keterangan,
            b.status,
						b.id_pengguna,
            b.created_at,
						p.nama

        ')
        ->from('visilab_suhu b')
				->join('pengguna p', 'b.id_pengguna=p.pengguna_id', 'left')
        ->where('b.status = 1')
        ->generate();        
    }


		function getSuhuByMonth($where)
		{
				$this->db->select('
										b.id,
										b.suhu,
										b.kelembapan,
										b.keterangan,
										b.status,
										b.id_pengguna,
										b.created_at
								')
						->from('visilab_suhu b')
						// Menggunakan DATE_FORMAT untuk menyesuaikan dengan format YYYY-MM
						->where("DATE_FORMAT(b.created_at, '%Y-%m') =", $where['b.created_at'])
						->order_by('b.created_at', 'ASC');
				
				return $this->db->get()->result();
		}

		



// ==============================================
// ====================== END SUHU ==============
// ==============================================


// ==================================================
// ====================== Berita Acara ==============
// ==================================================

function getBywhereActive()
{
	$excluded_ids = [1, 727, 714, 84, 109, 110, 79, 72, 81, 70, 58, 69, 57, 74, 56, 83, 55, 107, 86, 37, 73, 68, 724, 94, 77];
	$placeholders = implode(',', array_fill(0, count($excluded_ids), '?'));

	$sql = "SELECT * FROM pengguna p 
			WHERE p.is_active = 1 
			AND p.pengguna_id NOT IN ($placeholders)
			ORDER BY p.nama ASC";

	$query = $this->db->query($sql, $excluded_ids);
	return $query->result(); // Mengembalikan hasil query dalam bentuk array objek
}


function addBeritaAcara($data)
		{
				$this->db->insert('ba_visilab', $data);
		}

		function getBaKodeId(){
			return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('ba_visilab',array('YEAR(`created_at`)' => date('Y')))->row();
		}

		function getBAlLastId(){
					return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('ba_visilab')->row();
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
					f.status,
					f.lampiran,
					f.jumlah_ttd,
					f.id_ttd1, f.id_ttd2, f.id_ttd3, f.id_ttd4, f.id_ttd5,
					f.ttd_1, f.ttd_2, f.ttd_3, f.ttd_4, f.ttd_5,
					p.nama as pengaju,
					p.jabatan as jabatan,
					p.jabatan_visilab as jabatan_visilab,

					p1.nama as nama1, p1.jabatan_visilab as jabatan_visilab1, p1.jabatan as jabatan1,
					p2.nama as nama2, p2.jabatan_visilab as jabatan_visilab2, p2.jabatan as jabatan2,
					p3.nama as nama3, p3.jabatan_visilab as jabatan_visilab3, p3.jabatan as jabatan3,
					p4.nama as nama4, p4.jabatan_visilab as jabatan_visilab4, p4.jabatan as jabatan4,
					p5.nama as nama5, p5.jabatan_visilab as jabatan_visilab5, p5.jabatan as jabatan5
				')
				->from('ba_visilab f')
				->join('pengguna p', 'f.id_pengaju = p.pengguna_id')
				->join('pengguna p1', 'f.id_ttd1 = p1.pengguna_id', 'left')
				->join('pengguna p2', 'f.id_ttd2 = p2.pengguna_id', 'left')
				->join('pengguna p3', 'f.id_ttd3 = p3.pengguna_id', 'left')
				->join('pengguna p4', 'f.id_ttd4 = p4.pengguna_id', 'left')
				->join('pengguna p5', 'f.id_ttd5 = p5.pengguna_id', 'left')
				->generate();
		}


		function getBeritaAcaraById($id)
		{
				$this->db->select('
						f.id as id_ba,
						f.kode_ba,
						f.tanggal,
						f.hasil,
						f.analisis,
						f.penanganan,
						f.lampiran,
						f.id_pengaju as idPengaju,
						f.status,
						f.jumlah_ttd,

						f.id_ttd1, f.id_ttd2, f.id_ttd3, f.id_ttd4, f.id_ttd5,
						f.ttd_1, f.ttd_2, f.ttd_3, f.ttd_4, f.ttd_5,

						p.nama as pengaju,
						p.jabatan as jabatan,
						p.jabatan_visilab as jabatan_visilab,

						p1.nama as nama1, p1.jabatan_visilab as jabatan_visilab1, p1.jabatan as jabatan1,
						p2.nama as nama2, p2.jabatan_visilab as jabatan_visilab2, p2.jabatan as jabatan2,
						p3.nama as nama3, p3.jabatan_visilab as jabatan_visilab3, p3.jabatan as jabatan3,
						p4.nama as nama4, p4.jabatan_visilab as jabatan_visilab4, p4.jabatan as jabatan4,
						p5.nama as nama5, p5.jabatan_visilab as jabatan_visilab5, p5.jabatan as jabatan5
				')
				->from('ba_visilab f')
				->join('pengguna p', 'f.id_pengaju = p.pengguna_id')
				->join('pengguna p1', 'f.id_ttd1 = p1.pengguna_id', 'left')
				->join('pengguna p2', 'f.id_ttd2 = p2.pengguna_id', 'left')
				->join('pengguna p3', 'f.id_ttd3 = p3.pengguna_id', 'left')
				->join('pengguna p4', 'f.id_ttd4 = p4.pengguna_id', 'left')
				->join('pengguna p5', 'f.id_ttd5 = p5.pengguna_id', 'left')
				->where('f.id', $id);

				return $this->db->get()->result();
		}

		function updateBeritaAcara($id, $data)
		{
				$this->db->where('id', $id);
				$this->db->update('ba_visilab', $data);
		}




// ==================================================
// ================== END Berita Acara ==============
// ==================================================


		//=======================================
		//====   Start Purchase Order (PO)  =====
		//=======================================
		
		//Add
		function addPo($data)
			{
					$this->db->insert('surat_po_visilab', $data);
			}

		//UPDATE 
		function updatePo($id, $data)
			{
				$this->db->where('id', $id);
				$this->db->update('surat_po_visilab', $data);
			}

		//GET
		function getPoKodeId(){
			return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('surat_po_visilab',array('YEAR(`created_at`)' => date('Y')))->row();
		}

		function getPOLastId(){
					return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('surat_po_visilab')->row();
		}


		function getPoByID($id)
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
					->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.id_pengaju as idPengaju,
						f.no_po,
						f.id_pelanggan,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						pl.id_pelanggan,
						pl.identitas_pelanggan
						')
						->from('surat_po_visilab f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pelanggan pl', 'f.id_pelanggan=pl.id_pelanggan')
						->where('f.id_pengaju', $id)
						->generate();
		}

		function getAllPo()
		{
			$this->db->order_by('f.id', 'DESC');
				return $this->datatables
					->select('
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.id_pengaju as idPengaju,
						f.no_po,
						f.id_pelanggan,
						f.status,
						p.nama as pengaju,
						p.jabatan as jabatan,
						pl.id_pelanggan,
						pl.identitas_pelanggan
						')
						->from('surat_po_visilab f')
						->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
						->join('pelanggan pl', 'f.id_pelanggan=pl.id_pelanggan')
						->generate();
		}

		function getDetailPoById($id)
		{
				$this->db->select('
					
						f.id as idGc,
						f.kode,
						f.tanggal,
						f.id_pengaju as idPengaju,
						f.no_po,
						f.item1,
						f.item2,
						f.item3,
						f.item4,
						f.item5,
						f.alasan,
						f.catatan,
						f.catatan_hr,
						f.catatan_gm,
						f.id_pelanggan,
						f.status,
						f.lampiran,
						f.ttd_1,
						f.ttd_2,
						f.ttd_3,
						f.jenis,
						f.created_at,
						p.nama as pengaju,
						p.jabatan as jabatan,
						pl.id_pelanggan,
						pl.identitas_pelanggan
				')
				->from('surat_po_visilab f')
				->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
				->join('pelanggan pl', 'f.id_pelanggan=pl.id_pelanggan')
				->where('f.id', $id);
				return $this->db->get()->result();
		}

		//=======================================
		//======   End Purchase Order (PO)  =====
		//=======================================

}