<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dimas_seed extends CI_Controller
{
    public function index()
    {
        header('Content-Type: application/json');
        
        $dimas = $this->db->query("SELECT pengguna_id, nama, email, username FROM pengguna WHERE nama LIKE '%dimas%' OR username LIKE '%dimas%'")->result_array();
        
        $dimas_id = null;
        foreach ($dimas as $u) {
            if (stripos($u['nama'], 'dimas') !== false) {
                $dimas_id = $u['pengguna_id'];
                break;
            }
        }
        
        $jobs = [];
        if ($dimas_id) {
            $jobs = $this->db->query("SELECT j.id as id_job, jd.id as id_jobdesc_detail, jd.deskripsi FROM jobdesc j LEFT JOIN jobdesc_detail jd ON j.id = jd.id_jobdesc WHERE j.id_pengguna = ?", [$dimas_id])->result_array();
        }
        
        $existing_laporan = [];
        if ($dimas_id) {
            $existing_laporan = $this->db->query("SELECT * FROM laporan WHERE id_pengaju = ? AND tanggal >= '2026-10-05' AND tanggal <= '2026-10-10' ORDER BY tanggal ASC", [$dimas_id])->result_array();
        }
        
        $do_insert = $this->input->get('do_insert');
        $inserted_count = 0;
        
        if ($do_insert && $dimas_id) {
            $tasks = [
                '2026-10-05' => [
                    'membantu tim visilab yang tidak bisa mengakses webmail',
                    'menghubungi pihak indihome terkait penurunan paket',
                    'maintenance pc gudang pusat',
                    'maintenance cctv jogja',
                    'menambahkan fitur export excel pada menu kalkulator price',
                    'menambahkan menu uploda dokumen npwp dan passport pada profil'
                ],
                '2026-10-06' => [
                    'membersihkan disk email kantor',
                    'merubah tampilan login',
                    'merubah tampilan menu surat lainnya',
                    'koordinasi dengan pihak convia terkait kuota chat api',
                    'merubah tampilan table pada menu persetujuan berita acara',
                    'merubah tampilan tombol pada surat',
                    'menambahkan kolom nama karyawan yang dinas pada table surat dinas'
                ],
                '2026-10-07' => [
                    'menambahkan fitur input absen kosong agar GA bisa mengiput yang tidak sesuai',
                    'merubah tampilan pilihan nomer halaman',
                    'merubah font sidebar',
                    'merubah tampilan halaman dashboard',
                    'merubah tampilan data karyawan',
                    'merubah tampilan rekap absensi'
                ],
                '2026-10-08' => [
                    'mencari provider dan koordinasi tentang paket wifi indihome',
                    'merubah tampilan navbar',
                    'merubah tampilan pada menu tunjangan di profil',
                    'merubah tampilan dashboard pada home administrator card stats',
                    'merubah icon sidebar lebih clean'
                ],
                '2026-10-09' => [
                    'penambahan animasi pada side bar',
                    'merapikan icon pada kolom aksi menu salary tetap',
                    'memperbaiki load agar lebih cepat ketika mengakses menu salary tidak tetap',
                    'merubah tampilan pada tab analitik menu salary tidak tetap',
                    'merubah tampilan pada halaman laporan mingguan ver 2'
                ]
            ];
            
            $default_job_id = !empty($jobs) ? $jobs[0]['id_job'] : 0;
            
            foreach ($tasks as $tgl => $list_pekerjaan) {
                foreach ($list_pekerjaan as $task_desc) {
                    $job_id = $default_job_id;
                    
                    foreach ($jobs as $j) {
                        if (stripos($task_desc, 'email') !== false || stripos($task_desc, 'webmail') !== false || stripos($task_desc, 'wifi') !== false || stripos($task_desc, 'indihome') !== false || stripos($task_desc, 'cctv') !== false || stripos($task_desc, 'pc') !== false) {
                            if (isset($j['deskripsi']) && (stripos($j['deskripsi'], 'jaringan') !== false || stripos($j['deskripsi'], 'hardware') !== false || stripos($j['deskripsi'], 'maintenance') !== false || stripos($j['deskripsi'], 'it') !== false)) {
                                $job_id = $j['id_job'];
                                break;
                            }
                        }
                    }
                    
                    $data_insert = [
                        'id_pengaju'       => $dimas_id,
                        'tanggal'          => $tgl,
                        'id_job'           => $job_id,
                        'jenis'            => 'JOBDESK RUTIN',
                        'progress'         => '100%',
                        'status_pekerjaan' => 'SELESAI',
                        'ket_hasil'        => $task_desc,
                        'pihak'            => '-',
                        'keterangan'       => 'Telah diselesaikan dengan baik',
                        'pencapaian'       => '1'
                    ];
                    
                    $this->db->insert('laporan', $data_insert);
                    $inserted_count++;
                }
            }
        }
        
        echo json_encode([
            'status' => 'success',
            'dimas' => $dimas,
            'dimas_id' => $dimas_id,
            'jobs' => $jobs,
            'existing_laporan_this_week' => $existing_laporan,
            'inserted_count' => $inserted_count
        ], JSON_PRETTY_PRINT);
    }
}
