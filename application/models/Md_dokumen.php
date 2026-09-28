<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_dokumen extends CI_Model {

    function add($dok, $data)
    {
        if($dok == "rahasia"){
            $this->db->insert('dokumen_rahasia', $data);
        }else if($dok == "umum"){
            $this->db->insert('dokumen_umum', $data);
        }else if($dok == "product"){
            $this->db->insert('dokumen_product', $data);
        }else if($dok == "kategori"){
            $this->db->insert('dokumen_kategori', $data);
        }else if($dok == "kategoriproduct"){
            $this->db->insert('masterdokumenproduct', $data);
        }else if($dok == "visilab"){
            $this->db->insert('dokumen_visilab', $data);
        }else if($dok == "kategorivisilab"){
            $this->db->insert('masterdokumenvisilab', $data);
        }else if($dok == "umum_visilab"){
            $this->db->insert('dokumen_umum_visilab', $data);
        }else if($dok == "kategori_visilab"){
            $this->db->insert('dokumen_kategori_visilab', $data);
        }
    }

    function update($dok, $where, $data)
    {
        if($dok == "rahasia"){
            $this->db->where($where);
            $this->db->update('dokumen_rahasia', $data);
        }else if($dok == "umum"){
            $this->db->where($where);
            $this->db->update('dokumen_umum', $data);
        }else if($dok == "product"){
            $this->db->where($where);
            $this->db->update('dokumen_product', $data);
        }else if($dok == "kategori"){
            $this->db->where($where);
            $this->db->update('dokumen_kategori', $data);
        }else if($dok == "kategoriproduct"){
            $this->db->where($where);
            $this->db->update('masterdokumenproduct', $data);
        }else if($dok == "visilab"){
            $this->db->where($where);
            $this->db->update('dokumen_visilab', $data);
        }else if($dok == "kategorivisilab"){
            $this->db->where($where);
            $this->db->update('masterdokumenvisilab', $data);
        }else if($dok == "umum_visilab"){
            $this->db->where($where);
            $this->db->update('dokumen_umum_visilab', $data);
        }else if($dok == "kategori_visilab"){
            $this->db->where($where);
            $this->db->update('dokumen_kategori_visilab', $data);
        }
        
    }

    function getById($dok, $id)
    {
        if($dok == "rahasia"){
            return $this->db->get_where('dokumen_rahasia tb', array('tb.id' => $id))->result();
        }else if($dok == "umum"){
            return $this->db->get_where('dokumen_umum tb', array('tb.id' => $id))->result();
        }else if($dok == "product"){
            return $this->db->get_where('dokumen_product tb', array('tb.id' => $id))->result();
        }else if($dok == "kategori"){
            return $this->db->get_where('dokumen_kategori tb', array('tb.id' => $id))->result();
        }else if($dok == "visilab"){
            return $this->db->get_where('dokumen_visilab tb', array('tb.id' => $id))->result();
        }else if($dok == "katvisilab"){
            return $this->db->get_where('masterdokumenvisilab tb', array('tb.id' => $id))->result();
        }else if($dok == "katproduct"){
            return $this->db->get_where('masterdokumenproduct tb', array('tb.id' => $id))->result();
        }else if($dok == "umum_visilab"){
            return $this->db->get_where('dokumen_umum_visilab tb', array('tb.id' => $id))->result();
        }else if($dok == "kategori_visilab"){
            return $this->db->get_where('dokumen_kategori_visilab tb', array('tb.id' => $id))->result();
        }
    }

    function getByWhere($dok, $where="")
    {
        if($dok == "rahasia"){
            $this->db->where($where);
            return $this->db->get('dokumen_rahasia dr')->result();
        }else if($dok == "umum"){
            $this->db->where($where);
            return $this->db->get('dokumen_umum du')->result();
        }else if($dok == "kategori"){
            $this->db->where($where);
            return $this->db->get('dokumen_kategori tk')->result();
        }else if($dok == "umum_visilab"){
            $this->db->where($where);
            return $this->db->get('dokumen_umum_visilab du')->result();
        }else if($dok == "kategori_visilab"){
            $this->db->where($where);
            return $this->db->get('dokumen_kategori_visilab tk')->result();
        }
    }
    
    function getAktif($kode) 
    {
        if($kode == 'kategori'){
            $this->db->where('tb.status', 1);
            return $this->db->get('dokumen_kategori tb')->result();
        }else if($kode == 'kategori_visilab'){
            $this->db->where('tb.status', 1);
            return $this->db->get('dokumen_kategori_visilab tb')->result();
        }
    }
    
    function getKategoriProductAktif() 
    {
        return $this->db->get('masterdokumenproduct')->result();
    }

    function getKategoriVisilabAktif() 
    {
        return $this->db->get('masterdokumenvisilab')->result();
    }

    function getAllProduct($id){
         return $this->datatables
            ->select('  
                du.id as id_dokumen,
                du.nama_dokumen,
                du.id_kategori,
                du.tgl_mulai,
                du.tgl_akhir,
                du.file,
                du.status,
                du.created_at,
                du.created_by,
                kt.keterangan as kategori
            ')
            ->from('dokumen_product du')
            ->join('masterdokumenproduct kt', 'du.id_kategori=kt.id')
            ->where('du.status = 1')
            ->where('du.id_kategori',$id)
            ->generate();
    }

    function getAllVisilab($id){
         return $this->datatables
            ->select('  
                du.id as id_dokumen,
                du.nama_dokumen,
                du.id_kategori,
                du.tgl_mulai,
                du.tgl_akhir,
                du.file,
                du.status,
                du.created_at,
                du.created_by,
                kt.keterangan as kategori
            ')
            ->from('dokumen_visilab du')
            ->join('masterdokumenvisilab kt', 'du.id_kategori=kt.id')
            ->where('du.status = 1')
            ->where('du.id_kategori',$id)
            ->generate();
    }

    function getVisilabLastId(){
		return $this->db->select("*")->limit(1)->order_by('id',"DESC")->get('dokumen_visilab')->row();
	}

    
    function getAll($dok)
    {
        if($dok == "rahasia"){
            return $this->datatables
            ->select('  
                dr.id as id_dokumen,
                dr.nama_dokumen,
                dr.id_kategori,
                dr.file,
                dr.status,
                dr.created_at,
                dr.created_by,
                kt.nama as kategori
            ')
            ->from('dokumen_rahasia dr')
            ->join('dokumen_kategori kt', 'dr.id_kategori=kt.id')
            ->where('dr.status = 1')
            ->generate();
            
        }else if($dok == "umum"){
            return $this->datatables
            ->select('  
                du.id as id_dokumen,
                du.nama_dokumen,
                du.id_kategori,
                du.file,
                du.status,
                du.created_at,
                du.created_by,
                kt.nama as kategori
            ')
            ->from('dokumen_umum du')
            ->join('dokumen_kategori kt', 'du.id_kategori=kt.id')
            ->where('du.status = 1')
            ->generate();
        }else if($dok == "product"){
            return $this->datatables
            ->select('  
                du.id as id_dokumen,
                du.nama_dokumen,
                du.id_kategori,
                du.file,
                du.status,
                du.created_at,
                du.created_by,
                kt.keterangan as kategori
            ')
            ->from('dokumen_product du')
            ->join('masterdokumenproduct kt', 'du.id_kategori=kt.id')
            ->where('du.status = 1')
            ->generate();
            
        }else if($dok == "kategori"){
            return $this->datatables
            ->select('  
                tb.id as id_kategori,
                tb.nama,
                tb.deskripsi,
                tb.status,
                tb.created_at,
                tb.created_by
            ')
            ->from('dokumen_kategori tb')
            ->where('tb.status = 1')
            ->generate();
        }else if($dok == "umum_visilab"){
            return $this->datatables
            ->select('  
                du.id as id_dokumen,
                du.nama_dokumen,
                du.id_kategori,
                du.file,
                du.status,
                du.created_at,
                du.created_by,
                kt.nama as kategori
            ')
            ->from('dokumen_umum_visilab du')
            ->join('dokumen_kategori_visilab kt', 'du.id_kategori=kt.id')
            ->where('du.status = 1')
            ->generate();
        }else if($dok == "kategori_visilab"){
            return $this->datatables
            ->select('  
                tb.id as id_kategori,
                tb.nama,
                tb.deskripsi,
                tb.status,
                tb.created_at,
                tb.created_by
            ')
            ->from('dokumen_kategori_visilab tb')
            ->where('tb.status = 1')
            ->generate();
        }
    }
}