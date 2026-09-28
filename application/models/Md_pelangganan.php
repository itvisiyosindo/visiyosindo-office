<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_pelangganan extends CI_Model
{
    function addModality($data)
    {
        $this->db->insert('pelanggananmodality', $data);
    }
    function addpic($data)
    {
        $this->db->insert('calonpelangganpic', $data);
    }
    function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('pelangganan', $data);
    }

   function hapus($id,$pengguna_id)
    {
        $data = array(
            'deleted' => 1,
            'pengguna_id' => $pengguna_id
        );
        $this->db->where('id', $id);
        $this->db->update('pelangganan', $data);
    }
    function hapusmodality($id)
    {
        $this->db->where('id', $id);
        $this->db->update('pelanggananmodality', array('deleted' => 1));
    }
    
    function getAllPelangganan()
    {
        $this->db->order_by('pelangganan.id', 'DESC');
        return $this->datatables
            ->select('
				pelangganan.id,
                pelangganan.idcalonpelanggan,
                pelangganan.kodecustomer,
				calonpelanggan.namacaloncustomer as namapelanggan,
				pelangganan.tanggalregistrasi,
				pelangganan.nik,
				pelangganan.nonpwp,
				pelangganan.namanpwp,
				pelangganan.jenisfakturpajak,
				pelangganan.syaratpembayaran,
				pelangganan.pengirimandokumen,
				pelangganan.statuspiutang,
				pelangganan.limitpiutang,
				pelangganan.alamatpengiriman,
				pelangganan.alamatpenagihan,
				pelangganan.keterangan,
				pelangganan.pengguna_id
            ')
            ->from('pelangganan')
            ->join('calonpelanggan','pelangganan.idcalonpelanggan = calonpelanggan.id')
            ->where('pelangganan.deleted=0')
            ->generate();
    }
    function getAllPelanggananByPenggunaID($pengguna_id)
    {
        $this->db->order_by('pelangganan.id', 'DESC');
        return $this->datatables
            ->select('
				pelangganan.id,
                pelangganan.idcalonpelanggan,
                pelangganan.kodecustomer,
				pelangganan.namapelanggan,
				pelangganan.tanggalregistrasi,
				pelangganan.nik,
				pelangganan.nonpwp,
				pelangganan.namanpwp,
				pelangganan.jenisfakturpajak,
				pelangganan.syaratpembayaran,
				pelangganan.pengirimandokumen,
				pelangganan.statuspiutang,
				pelangganan.limitpiutang,
				pelangganan.alamatpengiriman,
				pelangganan.alamatpenagihan,
				pelangganan.keterangan,
				pelangganan.pengguna_id
            ')
            ->from('pelangganan')
            ->join('calonpelanggan','pelangganan.idcalonpelanggan = calonpelanggan.id')
            ->where('pelangganan.pengguna_id',$pengguna_id)
            ->where('pelangganan.deleted=0')
            ->generate();
    }
    function getAllPelangganById($id)
    {
        $where = array('pelangganan.id' => $id);    
        return $this->db
                ->select('
				pelangganan.id,
                pelangganan.kodecustomer,
                pelangganan.nik,
                pelangganan.nonpwp,
                pelangganan.namanpwp,
                pelangganan.jenisfakturpajak as idjenisfakturpajak,
                masterjenisfakturpajak.nama as jenisfakturpajak,
                pelangganan.syaratpembayaran as idsyaratpembayaran,
                mastersyaratpembayaran.nama as syaratpembayaran,
                pelangganan.pengirimandokumen,
                pelangganan.statuspiutang,
                pelangganan.limitpiutang,
                pelangganan.alamatpengiriman,
                pelangganan.alamatpenagihan,
                pelangganan.keterangan,
                pengguna.nama as namamarketing,
				calonpelanggan.namacaloncustomer,
				calonpelanggan.statuscaloncustomer,
				calonpelanggan.tipecustomer,
				calonpelanggan.kelascustomer,
				calonpelanggan.namapihakketiga,
				calonpelanggan.provinsi as id_prov,
                provinsi.nama as provinsi,
                calonpelanggan.kota as id_kota,
                kota.tipe,
                kota.nama as kota,
                calonpelanggan.alamatcaloncustomer,
                calonpelanggan.email,
                calonpelanggan.website,
                calonpelanggan.data_created
            ')
            ->join('pelangganan','pelangganan.idcalonpelanggan = calonpelanggan.id')
            ->join('pengguna','pengguna.pengguna_id = calonpelanggan.pengguna_id')
            ->join('provinsi', 'calonpelanggan.provinsi = provinsi.kode')
            ->join('kota', 'calonpelanggan.kota = kota.id')
            ->join('masterjenisfakturpajak','masterjenisfakturpajak.id=pelangganan.jenisfakturpajak')
            ->join('mastersyaratpembayaran','mastersyaratpembayaran.id=pelangganan.syaratpembayaran')
            ->get_where('calonpelanggan', $where)
            ->result();
    }

    function getAllModalityPelangganById($id)
    {
        $where = array('pelanggananmodality.id_pelanggan' => $id);  
      
        return $this->db
           ->select('
                 pelanggananmodality.id,    
                (CONCAT(pelanggananmodality.merk,\' - \' ,pelanggananmodality.nama)) as modality
            ') 
            ->get_where('pelanggananmodality', $where)
            ->result();
    }

    function getAllModalityPelangganan($id_pelanggan)
    {
        $this->db->order_by('id', 'DESC');
        return $this->datatables
        ->select('
            pelanggananmodality.id,
            pelanggananmodality.id_pelanggan,
            pelanggananmodality.kodepelanggan,
            pelanggananmodality.kategori as idkategori,
            masterkategorimodality.nama as kategori,
            pelanggananmodality.nama,
            pelanggananmodality.merk,
            pelanggananmodality.data_created
        ')
        ->from('pelanggananmodality')
        ->join('calonpelanggan','calonpelanggan.id=pelanggananmodality.id_pelanggan')
        ->join('pelangganan','pelangganan.idcalonpelanggan=calonpelanggan.id')
        ->join('masterkategorimodality','masterkategorimodality.id=pelanggananmodality.kategori')
        ->where('pelanggananmodality.id_pelanggan',decrypt($id_pelanggan))
        ->where('pelanggananmodality.deleted',0)
        ->generate();
    }
    function getAllPICPelangganById($id)
    {
        $where = array(
            'calonpelangganpic.idpic' => $id,
            'calonpelangganpic.deleted' => 0
        );  
      
        return $this->db
           ->select('
                 calonpelangganpic.id,    
                (CONCAT(calonpelangganpic.namapic,\' - \' ,calonpelangganpic.jabatanpic,\' - \',calonpelangganpic.teleponpic)) as namapic
            ') 
            ->join('pelangganan','pelangganan.idcalonpelanggan=calonpelangganpic.idpic')
            ->get_where('calonpelangganpic', $where)
            ->result();
    }
    
    function getAllBerkasDokumenPembayaranById($id)
    {
        $where = array('pelanggananberkas.idcust' => $id);  
        return $this->db
           ->select('
                pelanggananberkas.id,
                pelanggananberkas.idberkas,
                masterberkaspelanggan.namaberkas,
                pelanggananberkas.linkberkas,
                pelanggananberkas.kodecustomer,
                pelanggananberkas.idcust    
              
            ') 
            ->join('masterberkaspelanggan','masterberkaspelanggan.id=pelanggananberkas.idberkas')
            ->get_where('pelanggananberkas', $where)
            ->result();
    }
    function getAllBerkasDokumenLainById($id)
    {
        $where = array('pelanggananberkaslainnya.idcust' => $id);  
        return $this->db
           ->select('
                pelanggananberkaslainnya.id,
                pelanggananberkaslainnya.idberkas,
                masterberkaslainpelanggan.namaberkas,
                pelanggananberkaslainnya.linkberkas,
                pelanggananberkaslainnya.kodecustomer,
                pelanggananberkaslainnya.idcust    
              
            ') 
            ->join('masterberkaslainpelanggan','masterberkaslainpelanggan.id=pelanggananberkaslainnya.idberkas')
            ->get_where('pelanggananberkaslainnya', $where)
            ->result();
    }
    function getMasterKategoriModality()
    {
        $this->db->order_by('id', 'ASC');
        return $this->db->get('masterkategorimodality')->result();
    }
    
    function getMasterJenisFakturPajak()
    {
        $this->db->order_by('id', 'ASC');
        return $this->db->get('masterjenisfakturpajak')->result();
    }
    function getMasterSyaratPembayaran()
    {
        $this->db->order_by('id', 'ASC');
        return $this->db->get('mastersyaratpembayaran')->result();
    }
}
