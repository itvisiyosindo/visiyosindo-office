<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_calonpelanggan extends CI_Model
{

    function addcalon($data)
    {
        $this->db->insert('calonpelanggan', $data);
    }
    function addpic($data)
    {
        $this->db->insert('calonpelangganpic', $data);
    }
    function addpiccalon($data)
    {
        $this->db->insert_batch('calonpelangganpic', $data);
    }

    function addberkasdokumenpelanggan($data1)
    {
        //$this->db->where('length(linkberkas)>0');
        $this->db->insert_batch('pelanggananberkas', $data1);
    }

    function addberkasdokumenlainpelanggan($data1)
    {
        //$this->db->where('length(linkberkas)>0');
        $this->db->insert_batch('pelanggananberkaslainnya', $data1);
    }


    function addpelanggan($data)
    {
        $this->db->insert('pelangganan', $data);
    }

    function updatecalonmejadipelanggan($idcalon)
    {
        $this->db->where('id', $idcalon);
        $this->db->update('calonpelanggan', array('statuscaloncustomer' => 0));
    }

    function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('calonpelanggan', $data);
    }
    function updatepic($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('calonpelangganpic', $data);
    }
    function hapus($id, $pengguna_id)
    {
        $data = array(
            'deleted' => 1,
            'pengguna_id' => $pengguna_id
        );
        $this->db->where('id', $id);
        $this->db->where('calonpelanggan.statuscaloncustomer', 1);
        $this->db->update('calonpelanggan', $data);
    }
    function hapusmodality($id, $pengguna_id)
    {
        $data = array(
            'deleted' => 1,
            'pengguna_id' => $pengguna_id
        );
        $this->db->where('id', $id);
        $this->db->update('pelanggananmodality', $data);
    }
    function hapuspic($id, $pengguna_id)
    {
        $data = array(
            'deleted' => 1,
            'pengguna_id' => $pengguna_id
        );
        $this->db->where('id', $id);
        $this->db->update('calonpelangganpic', $data);
    }

    function getAllCalonPelanggan()
    {
        $this->db->order_by('calonpelanggan.id', 'desc');
        return $this->datatables
            ->select('
				calonpelanggan.id,
                calonpelanggan.kodecaloncustomer,
                pengguna.nama as namamarketing,
				calonpelanggan.namacaloncustomer,
				calonpelanggan.statuscaloncustomer,
				calonpelanggan.tipecustomer,
				calonpelanggan.kelascustomer,
				calonpelanggan.namapihakketiga,
				calonpelanggan.provinsi as id_prov,
                provinsi.nama as provinsi,
                calonpelanggan.kota as id_kota,
                kota.nama as kota,
                calonpelanggan.alamatcaloncustomer,
                calonpelanggan.email,
                calonpelanggan.website,
                calonpelanggan.data_created
            ')
            ->from('calonpelanggan')
            ->join('pengguna', 'pengguna.pengguna_id = calonpelanggan.pengguna_id')
            ->join('provinsi', 'calonpelanggan.provinsi = provinsi.kode')
            ->join('kota', 'calonpelanggan.kota = kota.id')
            ->where('calonpelanggan.statuscaloncustomer', 1)
            ->where('calonpelanggan.deleted', 0)
            ->generate();
    }
    function getAllCalonPelangganByPenggunaID($pengguna_id)
    {
        $this->db->order_by('calonpelanggan.id', 'desc');
        return $this->datatables
            ->select('
				calonpelanggan.id,
                calonpelanggan.kodecaloncustomer,
                pengguna.nama as namamarketing,
				calonpelanggan.namacaloncustomer,
				calonpelanggan.statuscaloncustomer,
				calonpelanggan.tipecustomer,
				calonpelanggan.kelascustomer,
				calonpelanggan.namapihakketiga,
				calonpelanggan.provinsi as id_prov,
                provinsi.nama as provinsi,
                calonpelanggan.kota as id_kota,
                kota.nama as kota,
                calonpelanggan.alamatcaloncustomer,
                calonpelanggan.email,
                calonpelanggan.website,
                calonpelanggan.data_created
            ')
            ->from('calonpelanggan')
            ->join('pengguna', 'pengguna.pengguna_id = calonpelanggan.pengguna_id')
            ->join('provinsi', 'calonpelanggan.provinsi = provinsi.kode')
            ->join('kota', 'calonpelanggan.kota = kota.id')
            ->where('calonpelanggan.statuscaloncustomer', 1)
            ->where('calonpelanggan.pengguna_id', $pengguna_id)
            ->generate();
    }
    function getAllCalonPelangganById($id)
    {
        $where = array('calonpelanggan.id' => $id);
        return $this->db
            ->select('
				calonpelanggan.id,
                calonpelanggan.kodecaloncustomer,
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
            ->join('pengguna', 'pengguna.pengguna_id = calonpelanggan.pengguna_id')
            ->join('provinsi', 'calonpelanggan.provinsi = provinsi.kode')
            ->join('kota', 'calonpelanggan.kota = kota.id')
            ->get_where('calonpelanggan', $where)
            ->result();
    }

    // Untuk export data ke Excel
    function getAllCalonPelangganByTGL($pengguna_id = NULL, $tglawal, $tglakhir)
    {
        $this->db->order_by('capel.data_created', 'DESC');
        //return $this->db
        $query = $this->db
            ->select('
                capel.id,
                capel.kodecaloncustomer,
                capel.idmarketing,
                p.nama as namamarketing,
				capel.namacaloncustomer,
				capel.statuscaloncustomer,
				capel.tipecustomer,
				capel.kelascustomer,
				capel.namapihakketiga,
				capel.provinsi as id_prov,
                prov.nama as provinsi,
                capel.kota as id_kota,
                kot.tipe,
                kot.nama as kota,
                capel.alamatcaloncustomer,
                capel.email,
                capel.website,
                capel.data_created,
                pelmod.nama as namamodality,
                pelmod.merk as merkmodality,
                masmod.nama as kategorimodality,
                pic.namapic,
                pic.jabatanpic,
                pic.teleponpic


            ')
            ->from('calonpelanggan capel')
            ->join('pengguna p', 'capel.idmarketing=p.pengguna_id')
            ->join('provinsi prov', 'capel.provinsi=prov.kode')
            ->join('kota kot', 'capel.kota = kot.id')
            ->join('pelanggananmodality pelmod', 'capel.id = pelmod.id_pelanggan', 'left')
            ->join('masterkategorimodality masmod', 'pelmod.kategori=masmod.id', 'left')
            ->join('calonpelangganpic pic', 'capel.id=pic.idpic', 'left')
            //->where('capel.idmarketing', $pengguna_id)
            ->where('capel.data_created >=', date('Y-m-d', strtotime($tglawal)))
            ->where('capel.data_created <=', date('Y-m-d', strtotime($tglakhir)));
        //->get()
        //->result();

        // Kondisi jika ada marketing yang dipilih
        if ($pengguna_id != NULL) {
            $query->where('capel.idmarketing', $pengguna_id);
        }

        return $query->get()->result();
    }


    function getAllPICCalonPelangganById($id)
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
            ->get_where('calonpelangganpic', $where)
            ->result();
    }

    function getAllModalityCalonPelangganan($id_pelanggan)
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
            ->join('calonpelanggan', 'calonpelanggan.id=pelanggananmodality.id_pelanggan')
            ->join('masterkategorimodality', 'masterkategorimodality.id=pelanggananmodality.kategori')
            ->where('id_pelanggan', decrypt($id_pelanggan))
            ->where('pelanggananmodality.deleted', 0)
            ->generate();
    }
    function getAllPICCalonPelangganan($id_pelanggan)
    {
        $this->db->order_by('namapic', 'DESC');
        return $this->datatables
            ->select('
            calonpelangganpic.id,
            calonpelangganpic.namapic,
            calonpelangganpic.jabatanpic,
            calonpelangganpic.teleponpic,
            calonpelangganpic.idpic
        ')
            ->from('calonpelangganpic')
            ->where('idpic', decrypt($id_pelanggan))
            ->where('deleted', 0)
            ->generate();
    }


    function getAllCalonPelangganFunnel()
    {
        $this->db->order_by('calonpelanggan.id', 'desc');
        return $this->db
            ->get('calonpelanggan')
            ->result();
    }

    function getAllCalonPelangganFunnelByPenggunaID($pengguna_id)
    {
        $where = array('calonpelanggan.pengguna_id' => $pengguna_id);
        $this->db->order_by('calonpelanggan.namacaloncustomer', 'ASC');
        return $this->db
            ->get_where('calonpelanggan', $where)
            ->result();
    }

    function getDetailCalonPelanggan($kodeTiket)
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.kode_tiket,
                p.provinsi,
				p.kota
            ')
            ->from('tiket t')
            ->join('calonpelanggan p', 't.id_cpelanggan=p.id_cpelanggan')
            ->where('t.kode_tiket', $kodeTiket)
            ->generate();
    }
    function getMasterBerkasPelanggan()
    {
        $this->db->order_by('id', 'ASC');
        return $this->db->get('masterberkaspelanggan')->result();
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
    function getMasterBerkasLainPelanggan()
    {
        $this->db->order_by('id', 'ASC');
        return $this->db->get('masterberkaslainpelanggan')->result();
    }

    function getCalonNoUrut()
    {
        $nourut = $this->db->query("SELECT MAX(id) AS id FROM calonpelanggan;")->row(0);
        if ($nourut == null) {
            return 0;
        } else {
            return $nourut->id;
        }
    }
    function getNoUrut()
    {
        $nourut = $this->db->query("SELECT MAX(id) AS id FROM pelangganan;")->row(0);
        if ($nourut == null) {
            return 0;
        } else {
            return $nourut->id;
        }
    }
    function getmerkbyid($id)
    {
        $querys = "SELECT merk  FROM pelanggananmodality WHERE id=" . $id;
        $merk = $this->db->query($querys)->row(0);
        if ($merk == null) {
            return 0;
        } else {
            return $merk->merk;
        }
    }

    function getprovinsikota($id)
    {
        $querys = "SELECT (CONCAT(provinsi.nama,SPACE(1),'-',SPACE(1),kota.tipe,SPACE(1),kota.nama)) as provinsikota FROM calonpelanggan INNER JOIN provinsi ON calonpelanggan.provinsi = provinsi.kode INNER JOIN kota ON calonpelanggan.kota = kota.id WHERE calonpelanggan.id=" . $id;
        $provinsikota = $this->db->query($querys)->row(0);

        if ($provinsikota == null) {
            return 0;
        } else {
            return  $provinsikota->provinsikota;
        }
    }
    function getJumlahBerkasPelanggan()
    {
        $nourut = $this->db->query("SELECT COUNT(id) AS id FROM masterberkaspelanggan;")->row(0);
        if ($nourut == null) {
            return 0;
        } else {
            return $nourut->id;
        }
    }
    function getJumlahBerkasLainPelanggan()
    {
        $nourut = $this->db->query("SELECT COUNT(id) AS id FROM masterberkaslainpelanggan;")->row(0);
        if ($nourut == null) {
            return 0;
        } else {
            return $nourut->id;
        }
    }
}
