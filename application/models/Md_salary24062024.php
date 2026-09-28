<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_salary extends CI_Model
{

    public function getById($id)
    {
        return $this->db->get_where('riwayat_salary ls', array('ls.id_riwayat_salary' => $id))->result();
    }
   
    public function getByIdLatest($id)
    {
        return $this->db->order_by('data_created','DESC')->get_where('riwayat_salary ls', array('ls.id_riwayat_salary' => $id),1)->result();
    }

    public function get_latest_salary($pengguna_id)
    {
        $this->db->select('*');
        $this->db->from('riwayat_salary');
        $this->db->where('pengguna_id', $pengguna_id);
        $this->db->order_by('data_created', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }
    function getBywhere($where)
    {
        $this->db->where($where);
        $this->db->order_by('sl.data_created', 'DESC');
        return $this->db->get('riwayat_salary sl')->result();
    }
    public function addRiwayatSalary($data)
    {
        $this->db->insert('riwayat_salary', $data);
    }
    
    //Tambahkan newpph21
    public function addNewpph21($data)
    {
        $this->db->insert('newpph21', $data);
    }

     //Tambahkan History Salary
    public function addHistorySalary($data)
    {
        $this->db->insert('salary_history', $data);
    }

   

    //Get newpph21
    public function getNewpph21ByPenggunaID($pengguna_id){

        $sql = 'SELECT * FROM newpph21 WHERE idpengguna='.$pengguna_id.'
        ORDER BY created_at DESC';
        $query = $this->db->query($sql);
        return $query->row();
    }


    //Get THR
    public function getTHRByPenggunaID($pengguna_id){

        $sql = 'SELECT * FROM salary_thr WHERE idpengguna='.$pengguna_id.'
        ORDER BY created_at DESC';
        $query = $this->db->query($sql);
        return $query->row();
    }

    

  


    public function addRiwayatSalaryTutupBuku($bulan,$tahun,$penggunaid)
    {
                $sql = '
SELECT 
                     0 as id,'
                    .$bulan.' as bulan,'
                    .$tahun. ' as tahun,'.'
                    pengguna_id as idpengguna,
                    nama,
                    jabatan,
                    status_karyawan as status,
                    no_pegawai as npp,
                    norek,
                    gajipokok as gajipokok,
                    tunjangankonsumsi as tunjangankonsumsi,
                    tunjangankinerja as tunjangankinerja,
                    tunjangankomunikasi as tunjangankomunikasi,
                    tunjangantransportasi as tunjangantransport,
                    tunjanganjabatan as tunjanganjabatan,
                    komisi as bonus,
                    pph as pphpasal21,
                    dasar_bpjs_sehat as dasar_bpjs,
                    dasar_bpjs_kerja as dasar_bpjstk,
                    bpjskkaryawan as bpjskesehatan,
                    bpjstk2persen as bpjstk,
                    0 as potonganlainnya,
                    no_hp as nowhatsapp,'
                    .$penggunaid.' as pengguna_id,'.
                    '0 as tutupbuku
FROM (
SELECT 
pengguna_id,
nama,
no_pegawai,
jabatan,
status_karyawan,
no_hp,
norek,
tahun,
bulan,
hari,
jumlahharikerja,
gajipokok,
tunjanganjabatan,
tunjangankinerja,
tunjangankonsumsi,
tunjangankomunikasi,
tunjangantransportasi,
tunjanganbbm,
komisi,
potongan,
pendapatan_lain,
dasar_bpjs_sehat,
dasar_bpjs_kerja,
bpjskkaryawan,
bpjstkkaryawan,
bpjstk2persen,
bpjskperusahaan,
bpjstkperusahaan,
pajakdibayarkan,
keterangan,
jumlahptkp,
Pendapatanlain,
Bruto,
BiayaJabatan,
TotalPengurangan,
NET,
pbersih,
e34, 
d37,
d38,
d39,
((d37*0.05)+(d38*0.15)+(d39*0.25))/12 as pph,
pkp 
FROM (SELECT 
pengguna_id,
nama,
no_pegawai,
jabatan,
status_karyawan,
no_hp,
norek,
tahun,
bulan,
hari,
jumlahharikerja,
gajipokok,
tunjanganjabatan,
tunjangankinerja,
tunjangankonsumsi,
tunjangankomunikasi,
tunjangantransportasi,
tunjanganbbm,
komisi,
potongan,
pendapatan_lain,
dasar_bpjs_sehat,
dasar_bpjs_kerja,
bpjskkaryawan,
bpjstkkaryawan,
bpjstk2persen,
bpjskperusahaan,
bpjstkperusahaan,
pajakdibayarkan,
keterangan,
jumlahptkp,
Pendapatanlain,
Bruto,
BiayaJabatan,
TotalPengurangan,
NET,
pbersih,
e34,
(CASE WHEN ((e34>0) AND (e34<60000000)) THEN (e34) ELSE (CASE WHEN e34=0 THEN 0 ELSE 60000000 END) END) AS d37, 
(CASE WHEN (e34>60000000) THEN (CASE WHEN (((e34-60000000)>0) AND ((e34-60000000)<=250000000)) THEN ((e34-60000000)) ELSE (CASE WHEN (((e34-60000000)>60000000) AND ((e34-60000000)>=250000000)) THEN (250000000) ELSE (0) END) END) ELSE 0 END) AS d38,
(CASE WHEN (e34>250000000) THEN (CASE WHEN (((e34-60000000-250000000)>0) AND ((e34-60000000-250000000)<=500000000)) THEN (e34-60000000-250000000) ELSE (CASE WHEN (((e34-60000000-250000000)>250000000) AND ((e34-60000000-250000000)>=500000000)) THEN (500000000) ELSE (0) END) END) ELSE 0 END) AS d39,      
pkp 
FROM (SELECT 
pengguna_id,
nama,
no_pegawai,
jabatan,
status_karyawan,
no_hp,
norek,
tahun,
bulan,
hari,
jumlahharikerja,
gajipokok,
tunjanganjabatan,
tunjangankinerja,
tunjangankonsumsi,
tunjangankomunikasi,
tunjangantransportasi,
tunjanganbbm,
komisi,
potongan,
pendapatan_lain,
dasar_bpjs_sehat,
dasar_bpjs_kerja,
bpjskkaryawan,
bpjstkkaryawan,
bpjstk2persen,
bpjskperusahaan,
bpjstkperusahaan,
pajakdibayarkan,
keterangan,
jumlahptkp,
(CASE WHEN Pendapatanlain IS NULL THEN 0 ELSE Pendapatanlain END) PendapatanLain,
Bruto,
BiayaJabatan,
TotalPengurangan,
NET,      
pkp,
NET*12 AS pbersih,
(CASE WHEN ((NET*12)-jumlahptkp)>0 THEN ((NET*12)-jumlahptkp) ELSE 0 END) AS e34
FROM (
SELECT	                
 				 VY.*,
                 (VY.BiayaJabatan+VY.bpjstkkaryawan) AS TotalPengurangan,
                 (CASE WHEN ((VY.Bruto)-(VY.BiayaJabatan+VY.bpjstkkaryawan)) < 0 THEN 0 ELSE ((VY.Bruto)-(VY.BiayaJabatan+VY.bpjstkkaryawan)) END) AS Net,
                 (((VY.Bruto)-(VY.BiayaJabatan+VY.bpjstkkaryawan))*12)-(VY.jumlahptkp) AS pkp
             FROM (
                 SELECT 
                 VYM.*,
                 (CASE WHEN (VYM.Bruto*0.05)>500000 THEN 500000 ELSE CONVERT((VYM.Bruto*0.05),int) END) AS BiayaJabatan
                 FROM (
                 SELECT
                 V.*,
                 (V.gajipokok+V.komisi+V.tunjanganjabatan+V.tunjangankinerja+V.tunjangankomunikasi+V.tunjangankonsumsi+V.tunjangantransportasi+V.tunjanganbbm+V.bpjstkperusahaan)+(CASE WHEN V.Pendapatanlain IS NULL THEN 0 ELSE V.Pendapatanlain END) AS Bruto                
           FROM (
                 SELECT
                 p.pengguna_id,
                 p.nama,
                 p.no_pegawai,
                 p.jabatan,
                 p.status_karyawan,
                 p.no_hp,
                 p.no_rek AS norek,
                FLOOR(( DATE_FORMAT(NOW(),\'%Y%m%d\') - DATE_FORMAT(p.tgl_masuk,\'%Y%m%d\'))/10000) as tahun,
FLOOR((1200 + DATE_FORMAT(NOW(),\'%m%d\')-DATE_FORMAT(p.tgl_masuk,\'%m%d\'))/100) %12 as bulan,
CASE sign(day(NOW())-day(p.tgl_masuk))
  WHEN 0 THEN 0
  WHEN 1 THEN day(NOW())-day(p.tgl_masuk)
  ELSE (DAY(STR_TO_DATE(DATE_FORMAT(p.tgl_masuk + INTERVAL 1 MONTH,\'%Y-%m-01\'),\'%Y-%m-%d\')-INTERVAL 1 DAY)-day(p.tgl_masuk)+day(NOW())) END as hari,
                 (CASE WHEN abs.jumlahharikerja is NULL THEN 0 ELSE abs.jumlahharikerja END) AS jumlahharikerja,
                 rs.gaji_pokok as gajipokok,
                 (CASE WHEN p.terima_tunjangan_jabatan=0 THEN 0 ELSE rs.`tunjangan_jabatan` END) as tunjanganjabatan,
                 (CASE WHEN p.terima_tunjangan_kinerja=0 THEN 0 ELSE (rs.`tunjangan_kinerja`*(CASE WHEN abs.jumlahharikerja is NULL THEN 1 ELSE abs.jumlahharikerja END)) END) as tunjangankinerja,
                 (CASE WHEN p.terima_tunjangan_konsumsi=0 THEN 0 ELSE (rs.`tunjangan_konsumsi`*(CASE WHEN abs.jumlahharikerja is NULL THEN 1 ELSE abs.jumlahharikerja END)) END)  as tunjangankonsumsi,
                 (CASE WHEN p.terima_tunjangan_komunikasi=0 THEN 0 ELSE (rs.`tunjangan_komunikasi`) END) as tunjangankomunikasi,
                 (CASE WHEN p.terima_tunjangan_transportasi=0 THEN 0 ELSE (rs.`tunjangan_transportasi`) END) as tunjangantransportasi,
                 (CASE WHEN p.terima_tunjangan_bbm=0 THEN 0 ELSE (rs.`tunjangan_bbm`) END) as tunjanganbbm,
                 rs.`komisi`,
                 rs.potongan,
                 rs.`pendapatan_lain`,
                 rs.dasar_bpjs_sehat,
                 rs.dasar_bpjs_kerja,
                 CONVERT(rs.`dasar_bpjs_sehat`*(p.potonganBPJSKesehatan),int) as bpjskkaryawan,
                 CONVERT(rs.`dasar_bpjs_kerja`*0.0624,int) as bpjstkkaryawan,
                 CONVERT(rs.`dasar_bpjs_kerja`*0.02,int) as bpjstk2persen,
                 CONVERT(rs.`dasar_bpjs_sehat`*0.04,int) as bpjskperusahaan,
                 CONVERT(rs.`dasar_bpjs_kerja`*0.0424,int) as bpjstkperusahaan,
                 rs.`pajakdibayarkan`,
                 mtp.keterangan,
                 mtp.jumlahptkp,
                 (SELECT jumlah FROM pendapatan_lain WHERE 	id_pendapatan=p.id_pendapatan_lain) AS Pendapatanlain
                 FROM `pengguna` p
                 INNER JOIN mastertarifptkp mtp ON mtp.id=p.id_status_perkawinan     
                 INNER JOIN 
                 (SELECT * FROM `riwayat_salary` a WHERE a.`id_riwayat_salary` in (SELECT MAX(b.`id_riwayat_salary`) FROM `riwayat_salary` b GROUP BY b.pengguna_id)  ) rs 
                 ON p.pengguna_id=rs.pengguna_id
                 LEFT JOIN 
                 (SELECT absensi.pengguna_id, (CASE WHEN absensi.jumlahharikerja IS NULL THEN 1 ELSE absensi.jumlahharikerja END) AS jumlahharikerja FROM (select aa.pengguna_id, (count(aa.id_absensi)) as jumlahharikerja  FROM absensi aa where aa.tutupbuku=0 AND  year(aa.data_created)='.$tahun.' and month(aa.data_created)=('.$bulan.')-1 and aa.approval in (\'terima\') GROUP BY aa.pengguna_id) absensi) abs
                 ON p.pengguna_id=abs.pengguna_id WHERE p.is_active=1 AND p.status=1 ) V)VYM)VY
                 WHERE VY.gajipokok>0
                 ORDER BY VY.nama ) datapenggajian)penggajian)datagaji)datadata
  ';
        $select = $this->db->query($sql);
        if($select->num_rows())
        {
            $insert = $this->db->insert_batch('riwayat_salary_tutupbuku', $select->result_array());
        }
    }







    public function getRiwayatByIdPengguna($pengguna_id)
    {
        $this->db->order_by('sl.pengguna_id', 'desc');
        return $this->datatables
            ->select('  
                    sl.id_riwayat_salary,
                    sl.pengguna_id,
                    sl.gaji_pokok,
                    sl.tunjangan_jabatan,
                    sl.tunjangan_kinerja,
                    sl.tunjangan_konsumsi,
                    sl.tunjangan_komunikasi,
                    sl.tunjangan_transportasi,
                    sl.tunjangan_bbm,
                    sl.potongan,
                    sl.data_created,
            ')
            ->from('riwayat_salary sl')
            ->where('sl.pengguna_id', $pengguna_id)
            ->generate();
    }
    public function getRiwayatTutupBuku()
    {
        $this->db->order_by('nama', 'asc');
        return $this->datatables
            ->select('  
                    sl.id,
                    sl.tahun,
                    sl.bulan,
                    sl.idpengguna,
                    sl.npp,
                    sl.nama,
                    sl.jabatan,
                    sl.status,
                    sl.gajipokok,
                    sl.tunjangankonsumsi,
                    sl.tunjangankinerja,
                    sl.nowhatsapp,
                    sl.norek,
                    sl.pphpasal21
            ')
            ->from('riwayat_salary_tutupbuku sl')
            ->where('sl.tutupbuku',0)
            ->generate();
    }

    public function getSalaryFULLVYM($bulan,$tahun)
    {
  $sql = '
         SELECT	                
 				 VY.*,
                 (VY.BiayaJabatan+VY.bpjstkkaryawan) AS TotalPengurangan,
                 (CASE WHEN ((VY.Bruto)-(VY.BiayaJabatan+VY.bpjstkkaryawan)) < 0 THEN 0 ELSE ((VY.Bruto)-(VY.BiayaJabatan+VY.bpjstkkaryawan)) END) AS Net,
                 (((VY.Bruto)-(VY.BiayaJabatan+VY.bpjstkkaryawan))*12)-(VY.jumlahptkp) AS pkp
             FROM (
                 SELECT 
                 VYM.*,
                 (CASE WHEN (VYM.Bruto*0.05)>500000 THEN 500000 ELSE CONVERT((VYM.Bruto*0.05),int) END) AS BiayaJabatan
                 FROM (
                 SELECT
                 V.*,
                 (V.gajipokok+V.komisi+V.tunjanganjabatan+V.tunjangankinerja+V.tunjangankomunikasi+V.tunjangankonsumsi+V.tunjangantransportasi+V.tunjanganbbm+V.bpjstkperusahaan)+(CASE WHEN V.Pendapatanlain IS NULL THEN 0 ELSE V.Pendapatanlain END) AS Bruto                
           FROM (
                 SELECT
                 p.pengguna_id,
                 p.nama,
                 p.no_rek AS norek,
                FLOOR(( DATE_FORMAT(NOW(),\'%Y%m%d\') - DATE_FORMAT(p.tgl_masuk,\'%Y%m%d\'))/10000) as tahun,
                FLOOR((1200 + DATE_FORMAT(NOW(),\'%m%d\')-DATE_FORMAT(p.tgl_masuk,\'%m%d\'))/100) %12 as bulan,
                CASE sign(day(NOW())-day(p.tgl_masuk))
                  WHEN 0 THEN 0
                  WHEN 1 THEN day(NOW())-day(p.tgl_masuk)
                  ELSE (DAY(STR_TO_DATE(DATE_FORMAT(p.tgl_masuk + INTERVAL 1 MONTH,\'%Y-%m-01\'),\'%Y-%m-%d\')-INTERVAL 1 DAY)-day(p.tgl_masuk)+day(NOW())) END as hari,
                                 (CASE WHEN abs.jumlahharikerja is NULL THEN 0 ELSE abs.jumlahharikerja END) AS jumlahharikerja,
                 rs.gaji_pokok as gajipokok,
                 (CASE WHEN p.terima_tunjangan_jabatan=0 THEN 0 ELSE rs.`tunjangan_jabatan` END) as tunjanganjabatan,
                 (CASE WHEN p.terima_tunjangan_kinerja=0 THEN 0 ELSE (rs.`tunjangan_kinerja`*(CASE WHEN abs.jumlahharikerja is NULL THEN 1 ELSE abs.jumlahharikerja END)) END) as tunjangankinerja,
                 (CASE WHEN p.terima_tunjangan_konsumsi=0 THEN 0 ELSE (rs.`tunjangan_konsumsi`*(CASE WHEN abs.jumlahharikerja is NULL THEN 1 ELSE abs.jumlahharikerja END)) END)  as tunjangankonsumsi,
                 (CASE WHEN p.terima_tunjangan_komunikasi=0 THEN 0 ELSE (rs.`tunjangan_komunikasi`) END) as tunjangankomunikasi,
                 (CASE WHEN p.terima_tunjangan_transportasi=0 THEN 0 ELSE (rs.`tunjangan_transportasi`) END) as tunjangantransportasi,
                 (CASE WHEN p.terima_tunjangan_bbm=0 THEN 0 ELSE (rs.`tunjangan_bbm`) END) as tunjanganbbm,
                 (CASE WHEN p.terima_tunjangan_raya=0 THEN 0 ELSE (rs.`tunjangan_raya`) END) as tunjanganraya,
                 rs.`komisi`,
                 rs.potongan,
                 rs.`pendapatan_lain`,
                 rs.dasar_bpjs_sehat,
                 rs.dasar_bpjs_kerja,
                 CONVERT(rs.`dasar_bpjs_sehat`*(p.potonganBPJSKesehatan),int) as bpjskkaryawan,
                 CONVERT(rs.`dasar_bpjs_kerja`*0.0624,int) as bpjstkkaryawan,
                 CONVERT(rs.`dasar_bpjs_kerja`*0.02,int) as bpjstk2persen,
                 CONVERT(rs.`dasar_bpjs_sehat`*0.04,int) as bpjskperusahaan,
                 CONVERT(rs.`dasar_bpjs_kerja`*0.0424,int) as bpjstkperusahaan,
                 rs.`pajakdibayarkan`,
                 mtp.keterangan,
                 mtp.id as idkawin,
                 mtp.jumlahptkp,
                 (SELECT jumlah FROM pendapatan_lain WHERE 	id_pendapatan=p.id_pendapatan_lain) AS Pendapatanlain,
                 bo.totalbonus
                 FROM `pengguna` p
                 LEFT JOIN riwayat_salarybonus bo ON bo.id_pengguna=p.pengguna_id AND bo.tahun=YEAR(CURDATE()) AND bo.bulan=MONTH(CURDATE())
                 INNER JOIN mastertarifptkp mtp ON mtp.id=p.id_status_perkawinan     
                 INNER JOIN 
                 (SELECT * FROM `riwayat_salary` a WHERE a.`id_riwayat_salary` in (SELECT MAX(b.`id_riwayat_salary`) FROM `riwayat_salary` b GROUP BY b.pengguna_id)  ) rs 
                 ON p.pengguna_id=rs.pengguna_id
                 LEFT JOIN 
                 (SELECT absensi.pengguna_id, (CASE WHEN absensi.jumlahharikerja IS NULL THEN 1 ELSE absensi.jumlahharikerja END) AS jumlahharikerja FROM (select aa.pengguna_id, (count(aa.id_absensi)) as jumlahharikerja  FROM absensi aa where  year(aa.data_created)='.$tahun.' and month(aa.data_created)=('.$bulan.')-1 and aa.jenis_absen=\'kantor\' and aa.approval in (\'terima\') GROUP BY aa.pengguna_id) absensi) abs
                 ON p.pengguna_id=abs.pengguna_id WHERE p.is_active=1 AND p.status=1 ) V)VYM)VY
                 WHERE VY.gajipokok>0
                 ORDER BY VY.nama
  ';
             $query = $this->db->query($sql);
            return $query->result_array();
    }


    public function getSalaryTerakhirTutupBukuByPenggunaID($pengguna_id){

       
        
        
        $sql = 'SELECT * FROM riwayat_salary_tutupbuku WHERE tutupbuku=0 AND idpengguna='.$pengguna_id.'
        ORDER BY data_created DESC';
        $query = $this->db->query($sql);
        return $query->row();
    }



    public function getSalaryFULLVYMByPenggunaID($bulan,$tahun,$pengguna_id)
    {
        $sql = '
        SELECT 
            pengguna_id,
            nama,
            no_pegawai,
            jabatan,
            status_karyawan,
            norek,
            tahun,
            bulan,
            hari,
            jumlahharikerja,
            gajipokok,
            tunjanganjabatan,
            tunjangankinerja,
            tunjangankonsumsi,
            tunjangankomunikasi,
            tunjangantransportasi,
            tunjanganbbm,
            komisi,
            potongan,
            pendapatan_lain,
            dasar_bpjs_sehat,
            dasar_bpjs_kerja,
            bpjskkaryawan,
            bpjstkkaryawan,
            bpjstk2persen,
            bpjskperusahaan,
            bpjstkperusahaan,
            pajakdibayarkan,
            keterangan,
            jumlahptkp,
            Pendapatanlain,
            Bruto,
            BiayaJabatan,
            TotalPengurangan,
            NET,
            pbersih,
            e34, 
            d37,
            d38,
            d39,
            ((d37*0.05)+(d38*0.15)+(d39*0.25))/((CASE WHEN tahun>0 THEN 12 ELSE bulan END)) as pph,
            pkp 
            FROM (SELECT 
            pengguna_id,
            nama,
            no_pegawai,
            jabatan,
            status_karyawan,
            norek,
            tahun,
            bulan,
            hari,
            jumlahharikerja,
            gajipokok,
            tunjanganjabatan,
            tunjangankinerja,
            tunjangankonsumsi,
            tunjangankomunikasi,
            tunjangantransportasi,
            tunjanganbbm,
            komisi,
            potongan,
            pendapatan_lain,
            dasar_bpjs_sehat,
            dasar_bpjs_kerja,
            bpjskkaryawan,
            bpjstkkaryawan,
            bpjstk2persen,
            bpjskperusahaan,
            bpjstkperusahaan,
            pajakdibayarkan,
            keterangan,
            jumlahptkp,
            Pendapatanlain,
            Bruto,
            BiayaJabatan,
            TotalPengurangan,
            NET,
            pbersih,
            e34,
            (CASE WHEN ((e34>0) AND (e34<60000000)) THEN (e34) ELSE (CASE WHEN e34=0 THEN 0 ELSE 60000000 END) END) AS d37, 
            (CASE WHEN (e34>60000000) THEN (CASE WHEN (((e34-60000000)>0) AND ((e34-60000000)<=250000000)) THEN ((e34-60000000)) ELSE (CASE WHEN (((e34-60000000)>60000000) AND ((e34-60000000)>=250000000)) THEN (250000000) ELSE (0) END) END) ELSE 0 END) AS d38,
            (CASE WHEN (e34>250000000) THEN (CASE WHEN (((e34-60000000-250000000)>0) AND ((e34-60000000-250000000)<=500000000)) THEN (e34-60000000-250000000) ELSE (CASE WHEN (((e34-60000000-250000000)>250000000) AND ((e34-60000000-250000000)>=500000000)) THEN (500000000) ELSE (0) END) END) ELSE 0 END) AS d39,      
            pkp 
            FROM (SELECT 
            pengguna_id,
            nama,
            no_pegawai,
            jabatan,
            status_karyawan,
            norek,
            tahun,
            bulan,
            hari,
            jumlahharikerja,
            gajipokok,
            tunjanganjabatan,
            tunjangankinerja,
            tunjangankonsumsi,
            tunjangankomunikasi,
            tunjangantransportasi,
            tunjanganbbm,
            komisi,
            potongan,
            pendapatan_lain,
            dasar_bpjs_sehat,
            dasar_bpjs_kerja,
            bpjskkaryawan,
            bpjstkkaryawan,
            bpjstk2persen,
            bpjskperusahaan,
            bpjstkperusahaan,
            pajakdibayarkan,
            keterangan,
            jumlahptkp,
            (CASE WHEN Pendapatanlain IS NULL THEN 0 ELSE Pendapatanlain END) PendapatanLain,
            Bruto,
            BiayaJabatan,
            TotalPengurangan,
            NET,      
            pkp,
            (NET*(CASE WHEN tahun>0 THEN 12 ELSE bulan END))+totalbonus AS pbersih,
            (CASE WHEN (((NET*(CASE WHEN tahun>0 THEN 12 ELSE bulan END))+totalbonus)-jumlahptkp)>0 THEN (((NET*(CASE WHEN tahun>0 THEN 12 ELSE bulan END))+totalbonus)-jumlahptkp) ELSE 0 END) AS e34
            FROM (
            SELECT	                
 				 VY.*,
                 (VY.BiayaJabatan+VY.bpjstkkaryawan) AS TotalPengurangan,
                 (CASE WHEN ((VY.Bruto)-(VY.BiayaJabatan+VY.bpjstkkaryawan)) < 0 THEN 0 ELSE ((VY.Bruto)-(VY.BiayaJabatan+VY.bpjstkkaryawan)) END) AS Net,
                 (((VY.Bruto)-(VY.BiayaJabatan+VY.bpjstkkaryawan))*12)-(VY.jumlahptkp) AS pkp
             FROM (
                 SELECT 
                 VYM.*,
                 (CASE WHEN (VYM.Bruto*0.05)>500000 THEN 500000 ELSE CONVERT((VYM.Bruto*0.05),int) END) AS BiayaJabatan
                 FROM (
                 SELECT
                 V.*,
                 (V.gajipokok+V.komisi+V.tunjanganjabatan+V.tunjangankinerja+V.tunjangankomunikasi+V.tunjangankonsumsi+V.tunjangantransportasi+V.tunjanganbbm+V.bpjstkperusahaan)+(CASE WHEN V.Pendapatanlain IS NULL THEN 0 ELSE V.Pendapatanlain END) AS Bruto                
           FROM (
                 SELECT
                 p.pengguna_id,
                 p.nama,
                 p.no_pegawai,
                 p.jabatan,
                 p.status_karyawan,
                 p.no_rek AS norek,
                FLOOR(( DATE_FORMAT(NOW(),\'%Y%m%d\') - DATE_FORMAT(p.tgl_masuk,\'%Y%m%d\'))/10000) as tahun,
                FLOOR((1200 + DATE_FORMAT(NOW(),\'%m%d\')-DATE_FORMAT(p.tgl_masuk,\'%m%d\'))/100) %12 as bulan,
                CASE sign(day(NOW())-day(p.tgl_masuk))
                  WHEN 0 THEN 0
                  WHEN 1 THEN day(NOW())-day(p.tgl_masuk)
                  ELSE (DAY(STR_TO_DATE(DATE_FORMAT(p.tgl_masuk + INTERVAL 1 MONTH,\'%Y-%m-01\'),\'%Y-%m-%d\')-INTERVAL 1 DAY)-day(p.tgl_masuk)+day(NOW())) END as hari,
                 (CASE WHEN abs.jumlahharikerja is NULL THEN 0 ELSE abs.jumlahharikerja END) AS jumlahharikerja,
                 rs.gaji_pokok as gajipokok,
                 (CASE WHEN p.terima_tunjangan_jabatan=0 THEN 0 ELSE rs.`tunjangan_jabatan` END) as tunjanganjabatan,
                 (CASE WHEN p.terima_tunjangan_kinerja=0 THEN 0 ELSE (rs.`tunjangan_kinerja`*(CASE WHEN abs.jumlahharikerja is NULL THEN 1 ELSE abs.jumlahharikerja END)) END) as tunjangankinerja,
                 (CASE WHEN p.terima_tunjangan_konsumsi=0 THEN 0 ELSE (rs.`tunjangan_konsumsi`*(CASE WHEN abs.jumlahharikerja is NULL THEN 1 ELSE abs.jumlahharikerja END)) END)  as tunjangankonsumsi,
                 (CASE WHEN p.terima_tunjangan_komunikasi=0 THEN 0 ELSE (rs.`tunjangan_komunikasi`) END) as tunjangankomunikasi,
                 (CASE WHEN p.terima_tunjangan_transportasi=0 THEN 0 ELSE (rs.`tunjangan_transportasi`) END) as tunjangantransportasi,
                 (CASE WHEN p.terima_tunjangan_bbm=0 THEN 0 ELSE (rs.`tunjangan_bbm`) END) as tunjanganbbm,
                 rs.`komisi`,
                 rs.potongan,
                 rs.`pendapatan_lain`,
                 rs.dasar_bpjs_sehat,
                 rs.dasar_bpjs_kerja,
                 CONVERT(rs.`dasar_bpjs_sehat`*(p.potonganBPJSKesehatan),int) as bpjskkaryawan,
                 CONVERT(rs.`dasar_bpjs_kerja`*0.0624,int) as bpjstkkaryawan,
                 CONVERT(rs.`dasar_bpjs_kerja`*0.02,int) as bpjstk2persen,
                 CONVERT(rs.`dasar_bpjs_sehat`*0.04,int) as bpjskperusahaan,
                 CONVERT(rs.`dasar_bpjs_kerja`*0.0424,int) as bpjstkperusahaan,
                 rs.`pajakdibayarkan`,
                 mtp.keterangan,
                 mtp.jumlahptkp,
                 (SELECT jumlah FROM pendapatan_lain WHERE 	id_pendapatan=p.id_pendapatan_lain) AS Pendapatanlain,
                 bo.totalbonus
                 FROM `pengguna` p
                 LEFT JOIN riwayat_salarybonus bo ON bo.id_pengguna=p.pengguna_id AND bo.tahun=YEAR(CURDATE()) AND bo.bulan=MONTH(CURDATE())
                 INNER JOIN mastertarifptkp mtp ON mtp.id=p.id_status_perkawinan     
                 INNER JOIN 
                 (SELECT * FROM `riwayat_salary` a WHERE a.`id_riwayat_salary` in (SELECT MAX(b.`id_riwayat_salary`) FROM `riwayat_salary` b GROUP BY b.pengguna_id)  ) rs 
                 ON p.pengguna_id=rs.pengguna_id
                 LEFT JOIN 
                 (SELECT absensi.pengguna_id, (CASE WHEN absensi.jumlahharikerja IS NULL THEN 1 ELSE absensi.jumlahharikerja END) AS jumlahharikerja FROM (select aa.pengguna_id, (count(aa.id_absensi)) as jumlahharikerja  FROM absensi aa where  year(aa.data_created)='.$tahun.' and month(aa.data_created)=('.$bulan.')-1 and aa.jenis_absen=\'kantor\' and  aa.approval in (\'terima\') GROUP BY aa.pengguna_id) absensi) abs
                 ON p.pengguna_id=abs.pengguna_id WHERE p.is_active=1 AND p.status=1 ) V)VYM)VY
                 WHERE VY.gajipokok>0
                 AND
                 VY.pengguna_id='.$pengguna_id.'
                 ORDER BY VY.nama ) datapenggajian)penggajian)datagaji
  ';

             $query = $this->db->query($sql);
            return $query->row();
    }
}
