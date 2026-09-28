<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class md_prov_kota extends CI_Model
{
    function getAllProvinsi()
    {
        $this->db->order_by('k.nama', 'ASC');
        return $this->db->get('provinsi k')->result();
    }
    function getAllkota()
    {
        $this->db->order_by('k.nama', 'ASC');
        return $this->db->get('kota k')->result();
    }

    function getById($id)
    {
        return $this->db->get_where('tiket t', array('t.id_tiket' => $id))->result();
    }

    function getByIdTopik($id)
    {
        return $this->db->get_where('tiket t', array('t.id_topik' => $id,'t.status_data' => 1))->result();
    }

    function updateTiket($id, $data)
    {
        $this->db->where('id_tiket', $id);
        $this->db->update('tiket', $data);
    }
	
	function addSuratList($surat="", $data)
    {
        $this->db->insert($tabel, $data);
    }

    function addSurat($surat="", $data)
    {
		if($surat=="list"){
			$tabel = "surat_list";
		}else if($surat=="pb"){
			$tabel = "surat_biaya_dinas";
		}else if($surat=="dpb"){
			$tabel = "surat_detail_biaya_dinas";
		}
		
        $this->db->insert($tabel, $data);
    }
	
    function getAllSurat()
    {
		$this->db->order_by('s.id_list_surat', 'DESC');
        return $this->datatables
            ->select('
                s.status as stat_persetujuan,
                sPB.id_pb as idPB,
				sPB.kode_pb as kodePB,
				sPB.id_pengguna as idPengaju,
				sPB.kota as kota,
				sPB.keperluan as perihal,
				p.nama as pengaju,
				sKat.kat_surat as kategori
            ')
            ->from('surat_list s')
            ->join('surat_biaya_dinas sPB', 's.id_srt=sPB.id_pb')
			->join('pengguna p', 'sPB.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sPB.id_kat_surat=sKat.id_kat_surat')
            ->generate();
    }
	
	function getAllSuratByPengaju($id_pengaju)
    {
		$this->db->order_by('s.id_list_surat', 'DESC');
        return $this->datatables
            ->select('
                s.status as stat_persetujuan,
                sPB.id_pb as idPB,
				sPB.kode_pb as kodePB,
				sPB.id_pengguna as idPengaju,
				sPB.kota as kota,
				sPB.keperluan as perihal,
				p.nama as pengaju,
				sKat.kat_surat as kategori
            ')
            ->from('surat_list s')
            ->join('surat_biaya_dinas sPB', 's.id_srt=sPB.id_pb')
			->join('pengguna p', 'sPB.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sPB.id_kat_surat=sKat.id_kat_surat')
			->where('p.pengguna_id', $id_pengaju)
            ->generate();
    }
	
	function getPBById($idPB)
    {
            $this->db->select('
                sPB.id_pb as idPB,
				sPB.kode_pb as kodePB,
				sPB.id_pengguna as idPengaju,
				sPB.kota as kota,
				sPB.keperluan as perihal,
				sPB.tgl_pengajuan as tglPengajuan,
				sPB.tgl_pergi as tglPergi,
				sPB.tgl_kembali as tglKembali,
				sPB.tgl_laporan as tglLaporan,
				sPB.pengajuan_ttd_1 as aju_ttd1,
				sPB.pengajuan_ttd_2 as aju_ttd2,
				sPB.pengajuan_ttd_3 as aju_ttd3,
				sPB.laporan_ttd_1 as lap_ttd1,
				sPB.laporan_ttd_2 as lap_ttd2,
				sPB.laporan_ttd_3 as lap_ttd3,
				p.nama as pengaju,
				p.short_name as nama_ttd,
				p.jabatan as jabatan,
				sKat.kat_surat as kategori
            ')
            ->from('surat_list s')
            ->join('surat_biaya_dinas sPB', 's.id_srt=sPB.id_pb')
			->join('pengguna p', 'sPB.id_pengguna=p.pengguna_id')
			->join('surat_kategori sKat', 'sPB.id_kat_surat=sKat.id_kat_surat')
			->where('sPB.id_pb', $idPB);
			return $this->db->get()->result();
    }
	
	function getDetailPb($idPB)
    {
            $this->db->select('
                sPB.id_pb as idPB,
				dPB.tanggal as tgl,
				dPB.keterangan as ket,
				dPB.nominal as nominal
            ')
            ->from('surat_detail_biaya_dinas dPB')
			->join('surat_biaya_dinas sPB', 'dPB.id_pb=sPB.id_pb')
			->where('dPB.id_pb', $idPB)
			->where('dPB.kode_laporan = 1');
			return $this->db->get()->result();
    }
	
	function getPbLastId(){
		return $this->db->select("*")->limit(1)->order_by('id_pb',"DESC")->get('surat_biaya_dinas')->row();
	}
	
	function update_pb($id, $data)
    {
        $this->db->where('id_pb', $id);
        $this->db->update('surat_biaya_dinas', $data);
    }
	
	function update_pbByWhere($data, $where)
    {
        $this->db->where($where);
        $this->db->update('surat_biaya_dinas', $data);
    }
	
	function update_pbaja()
    {
		$data = array(
               'pengajuan_ttd_1' => 1
            );
        $this->db->where('id_pb = 1');
        $this->db->update('surat_biaya_dinas', $data);
    }
	
	function reset_increment($tabel){
		$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
	}

}
