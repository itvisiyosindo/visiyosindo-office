<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function _salary_helper_load_models()
{
    $CI = &get_instance();
    if (!isset($CI->md_pengguna)) {
        $CI->load->model('md_pengguna');
    }
    if (!isset($CI->md_salary)) {
        $CI->load->model('md_salary');
    }
    if (!isset($CI->md_divisi_pengguna)) {
        $CI->load->model('md_divisi_pengguna');
    }
    if (!isset($CI->md_absensi)) {
        $CI->load->model('md_absensi');
    }
    return $CI;
}

function tunjangan($pengguna_id, $month = "")
{
    $CI = _salary_helper_load_models();

    $id_divisi = $CI->md_pengguna->getById($pengguna_id);
    $dataPengguna = $CI->md_divisi_pengguna->getByIdPengguna($id_divisi[0]->id_divisi);
    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);

    $tunjangan_transportasi = isset($salary[0]->tunjangan_transportasi) ? '' . ($salary[0]->tunjangan_transportasi) : '0';
    $tunjangan_komunikasi = isset($salary[0]->tunjangan_komunikasi) ? '' . ($salary[0]->tunjangan_komunikasi) : '0';
    $tunjangan_kinerja = isset($salary[0]->tunjangan_kinerja) ? '' . ($salary[0]->tunjangan_kinerja) : '0';
    $tunjangan_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? '' . ($salary[0]->tunjangan_konsumsi) : '0';
    $tunjangan_jabatan = isset($salary[0]->tunjangan_jabatan) ? '' . ($salary[0]->tunjangan_jabatan) : '0';
    $tunjangan_bbm1 = isset($salary[0]->tunjangan_bbm) ? '' . ($salary[0]->tunjangan_bbm) : '0';
    $tunjangan_lainnya = isset($salary[0]->pendapatan_lain) ? '' . ($salary[0]->pendapatan_lain) : '0';
    $potongan = isset($salary[0]->potongan) ? '' . ($salary[0]->potongan) : '0';

    $salary = $CI->md_absensi->getTunjanganByMonth($pengguna_id, $month);
    $salarydinas = $CI->md_absensi->getTunjanganByMonthDinas($pengguna_id, $month);
    
    $salaryBoddyB = $CI->md_absensi->getTunjanganBoddyBiasa($pengguna_id, $month);
    $salaryBoddyL = $CI->md_absensi->getTunjanganBoddyLibur($pengguna_id, $month);

    $count = count($salary);
    $countdinas = count($salarydinas);
    $total_kinerja = $count * $tunjangan_kinerja;
    $total_konsumsi = $count * $tunjangan_konsumsi;
    $total_kinerjadinas = $countdinas * $tunjangan_kinerja;
    $total_konsumsidinas = $countdinas * $tunjangan_konsumsi;
    $tunjangan_bbm = $countdinas * $tunjangan_bbm1;

    $countBiasa = count($salaryBoddyB);
    $countLibur = count($salaryBoddyL);
    $total_kinerja_Biasa = $countBiasa * $tunjangan_kinerja;
    $total_kinerja_Libur = $countLibur * $tunjangan_kinerja;
    $total_konsumsi_Biasa = $countBiasa * $tunjangan_konsumsi;
    $total_konsumsi_Libur = $countLibur * $tunjangan_konsumsi;

    $total_tunjangan = $tunjangan_jabatan + $total_kinerja + $total_konsumsi + $tunjangan_komunikasi + $tunjangan_transportasi + $tunjangan_bbm;

    $data = [
        'absen_approved' => $count,
        'dinas_approved' => $countdinas,
        'total_kinerja' => $total_kinerja,
        'total_kinerjadinas' => $total_kinerjadinas,
        'total_konsumsi' => $total_konsumsi,
        'total_konsumsidinas' => $total_konsumsidinas,
        'total_komunikasi' => $tunjangan_komunikasi,
        'total_tunjangan' => $total_tunjangan,
        'total_bbm' => $tunjangan_bbm,
        'total_tunjanganlain' => $tunjangan_lainnya,
        'total_potongan' => $potongan,
        'total_transportasi' => $tunjangan_transportasi,
        'total_kinerja_Biasa' => $total_kinerja_Biasa,
        'total_kinerja_Libur' => $total_kinerja_Libur,
        'total_konsumsi_Biasa' => $total_konsumsi_Biasa,
        'total_konsumsi_Libur' => $total_konsumsi_Libur
    ];
    return $data;
}

function pajakdibayarkan($pengguna_id){
    $CI = _salary_helper_load_models();

    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);

    $pajakdibayar = isset($salary[0]->pajakdibayarkan) ? $salary[0]->pajakdibayarkan : 0;
    
    return $pajakdibayar;  
}

function tunjanganJabatan($pengguna_id)
{
    $CI = _salary_helper_load_models();

    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);

    $tunjanganJabatan = isset($salary[0]->tunjangan_jabatan) ? $salary[0]->tunjangan_jabatan : 0;

    return $tunjanganJabatan;
}

function tunjanganRaya($pengguna_id)
{
    $CI = _salary_helper_load_models();

    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);

    $tunjanganRaya = isset($salary[0]->tunjangan_raya) ? $salary[0]->tunjangan_raya : 0;

    return $tunjanganRaya;
}

function bonusTahunan($pengguna_id)
{
    $CI = _salary_helper_load_models();

    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);

    $bonusTahunan = isset($salary[0]->bonus_tahunan) ? $salary[0]->bonus_tahunan : 0;

    return $bonusTahunan;
}

function gajiPokok($pengguna_id)
{
    $CI = _salary_helper_load_models();

    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);

    $gajiPokok = isset($salary[0]->gaji_pokok) ? $salary[0]->gaji_pokok : 0;

    return $gajiPokok;
}

function dasarBPJSKesehatan($pengguna_id)
{
    $CI = &get_instance();

    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);

    $dasarBPJSKesehatan = isset($salary[0]->dasar_bpjs_sehat) ? $salary[0]->dasar_bpjs_sehat : 0;

    return $dasarBPJSKesehatan;
}

function dasarBPJSKetenagakerjaan($pengguna_id)
{
    $CI = &get_instance();

    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);

    $dasarBPJSKetenagakerjaan = isset($salary[0]->dasar_bpjs_kerja) ? $salary[0]->dasar_bpjs_kerja : 0;

    return $dasarBPJSKetenagakerjaan;
}

function tunjanganByAbsen($pengguna_id, $month = "")
{
    $CI = &get_instance();

    $id_divisi = $CI->md_pengguna->getById($pengguna_id);
    $dataPengguna = $CI->md_divisi_pengguna->getByIdPengguna($id_divisi[0]->id_divisi);
    if ($id_divisi[0]->id_divisi != NULL && $dataPengguna[0]->nama == 'Helper') {
        $tunjangan_kinerja = 0;
        $tunjangan_konsumsi = 15000;
    } else if ($id_divisi[0]->id_divisi != NULL && $dataPengguna[0]->nama == 'Marketing') {
        $tunjangan_kinerja = 0;
        $tunjangan_konsumsi = 0;
    } else if ($id_divisi[0]->id_divisi != NULL && $dataPengguna[0]->nama == 'Director') {
        $tunjangan_kinerja = 0;
        $tunjangan_konsumsi = 5000000;
    } else if ($dataPengguna[0]->pengguna_id == '61' || $dataPengguna[0]->pengguna_id == '67') {
        $tunjangan_kinerja = 0;
        $tunjangan_konsumsi = 15000;
    } else {
        $tunjangan_kinerja = 9775;
        $tunjangan_konsumsi = 15000;
    }

    $salary = $CI->md_absensi->getTunjanganByMonth($pengguna_id, $month);
    $count = count($salary);
    if ($dataPengguna[0]->pengguna_id == 61 || $dataPengguna[0]->pengguna_id == 67) {
        $total_kinerja = 0;
    } else {
        $total_kinerja = $count * $tunjangan_kinerja;
    }

    if ($id_divisi[0]->id_divisi != NULL && $dataPengguna[0]->nama == 'Director') {
        $total_konsumsi = $tunjangan_konsumsi;
    } else {
        $total_konsumsi = $count * $tunjangan_konsumsi;
    }

    $total_tunjangan = $total_kinerja + $total_konsumsi;
    $data = [
        'absen_approved' => $count,
        'total_kinerja' => $total_kinerja,
        'total_konsumsi' => $total_konsumsi,
        'total_tunjangan' => $total_tunjangan
    ];
    return $data;
}

function tunjanganBPJSKesehatan($pengguna_id)
{
    $CI = &get_instance();
    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    if (empty($pengguna) || empty($pengguna[0]->id_latestriwayat_salary)) return 0;
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
    if (empty($salary)) return 0;
    $dasar = isset($salary[0]->dasar_potong_bpjs) ? $salary[0]->dasar_potong_bpjs : (isset($salary[0]->dasar_bpjs_sehat) ? $salary[0]->dasar_bpjs_sehat : 0);
    return $dasar * 0.01;
}

function tunjanganBPJStk($pengguna_id)
{
    $CI = &get_instance();
    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    if (empty($pengguna) || empty($pengguna[0]->id_latestriwayat_salary)) return 0;
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
    if (empty($salary)) return 0;
    $dasar = isset($salary[0]->dasar_potong_bpjs_tk) ? $salary[0]->dasar_potong_bpjs_tk : (isset($salary[0]->dasar_bpjs_kerja) ? $salary[0]->dasar_bpjs_kerja : 0);
    return $dasar * 0.02;
}

function tunjanganBPJStkpph($pengguna_id)
{
    $CI = &get_instance();
    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
    $dasar = isset($salary[0]->dasar_bpjs_kerja) ? $salary[0]->dasar_bpjs_kerja : 0;
    return $bpjs = $dasar * 0.0424;
}

function potonganBPJStk($pengguna_id)
{
    $CI = &get_instance();
    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
    $dasar = isset($salary[0]->dasar_bpjs_kerja) ? $salary[0]->dasar_bpjs_kerja : 0;
    $data['bpjstk'] = $dasar * 0.02;
    return $data;
}

function potonganBPJSkes($pengguna_id)
{
    $CI = &get_instance();
    $pengguna = $CI->md_pengguna->getById($pengguna_id);
    $salary = $CI->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
    $dasar = isset($salary[0]->dasar_bpjs_sehat) ? $salary[0]->dasar_bpjs_sehat : 0;
    $data['bpjskes'] = $dasar * 0.01;
    return $data;
}

function komisi($pengguna_id, $bulan = null)
{
    $CI = &get_instance();
    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);
    if (empty($dataPengguna)) {
        return 0;
    }

    $id_komisi = isset($dataPengguna[0]->id_komisi) ? $dataPengguna[0]->id_komisi : null;

    if ($id_komisi) {
        if (!isset($CI->md_komisi)) {
            $CI->load->model('md_komisi');
        }
        $data_komisi = $CI->md_komisi->getById($id_komisi);
        if (!empty($data_komisi)) {
            if ($bulan && property_exists($data_komisi[0], 'bulan') && !empty($data_komisi[0]->bulan) && $data_komisi[0]->bulan != '0000-00-00') {
                $komisi_bulan = date('Y-m', strtotime($data_komisi[0]->bulan));
                $target_bulan = date('Y-m', strtotime($bulan));
                if ($komisi_bulan !== $target_bulan) {
                    return 0;
                }
            }
            return isset($data_komisi[0]->jumlah) ? $data_komisi[0]->jumlah : 0;
        }
    }

    return 0;
}

function pendapatan_lain($pengguna_id)
{
    $CI = &get_instance();
    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);
    $id_pendapatan_lain = isset($dataPengguna[0]->id_pendapatan_lain) ? $dataPengguna[0]->id_pendapatan_lain : null;
    if (!$id_pendapatan_lain) {
        return 0;
    }
    $data_pendapatanLain = $CI->md_pendapatan_lain->getById($id_pendapatan_lain);
    if (empty($data_pendapatanLain)) {
        $pendapatan_lain = 0;
    } else {
        $pendapatan_lain = isset($data_pendapatanLain[0]->jumlah) ? $data_pendapatanLain[0]->jumlah : 0;
    }
    return $pendapatan_lain;
}

function pph21_manual($pengguna_id, $bulan = null)
{
    $CI = &get_instance();

    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);

    if (empty($dataPengguna)) {
        return 0;
    }

    $id_pph21 = isset($dataPengguna[0]->id_pph21) ? $dataPengguna[0]->id_pph21 : null;

    if (!$id_pph21) {
        return 0;
    }

    if (!isset($CI->md_pph21)) {
        $CI->load->model('md_pph21');
    }
    $data_pph21 = $CI->md_pph21->getById($id_pph21);

    if (empty($data_pph21)) {
        return 0;
    }

    if ($bulan && property_exists($data_pph21[0], 'bulan') && !empty($data_pph21[0]->bulan) && $data_pph21[0]->bulan != '0000-00-00') {
        $pph_bulan = date('Y-m', strtotime($data_pph21[0]->bulan));
        $target_bulan = date('Y-m', strtotime($bulan));
        if ($pph_bulan !== $target_bulan) {
            return 0;
        }
    }

    return isset($data_pph21[0]->jumlah) ? $data_pph21[0]->jumlah : 0;
}

function potongan_lain($pengguna_id)
{
    $CI = &get_instance();
    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);
    $id_pendapatan_lain = $dataPengguna[0]->id_pendapatan_lain;
    $data_pendapatanLain = $CI->md_pendapatan_lain->getById($id_pendapatan_lain);
    if (empty($data_pendapatanLain)) {
        $pendapatan_lain = 0;
    } else {
        $pendapatan_lain = $data_pendapatanLain[0]->pengurangan;
    }
    if (sessPenggunaId() == 56) {
        $pendapatan_lain = 1000000;
    }
    return $pendapatan_lain;
}

function penghasilan_sebelumPajak($pengguna_id)
{
    $CI = &get_instance();
    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);
    $gaji_terakhir = $dataPengguna[0]->id_latestriwayat_salary;
    $dataGaji = $CI->md_salary->getById($gaji_terakhir);
    $dasar = isset($dataGaji[0]->gaji_pokok) ? $dataGaji[0]->gaji_pokok : 0;

    $data['gapok'] = $dasar;
    $data['pendapatan_lain'] = isset($dataGaji[0]->pendapatan_lain) ? $dataGaji[0]->pendapatan_lain : 0;

    $total1 = array_sum($data);

    $data2['bpjskes'] = tunjanganBPJSKesehatan($pengguna_id);
    $data2['bpjstk'] = tunjanganBPJStk($pengguna_id);
    $data2['potlain'] = potongan_lain($pengguna_id);

    $total2 = array_sum($data2);
    return (int) $total1 - (int) $total2;
}

function penghasilan_bruto($pengguna_id)
{
    $CI = &get_instance();
    $bulan = date("Y-m");
    $bulanItung = date("Y-m", strtotime('-1 month', strtotime($bulan)));

    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);
    $gaji_terakhir = $dataPengguna[0]->id_latestriwayat_salary;
    $dataGaji = $CI->md_salary->getById($gaji_terakhir);
    $dataTunjangan = tunjangan($pengguna_id, $bulanItung);

    $data['komisi'] = isset($dataGaji[0]->komisi) ? $dataGaji[0]->komisi : 0;
    $data['gapok'] = isset($dataGaji[0]->gaji_pokok) ? $dataGaji[0]->gaji_pokok : 0;
    $data['jabatan'] = isset($dataGaji[0]->tunjangan_jabatan) ? $dataGaji[0]->tunjangan_jabatan : 0;
    $data['tKinerja'] = $dataTunjangan['total_kinerja'];
    $data['tkonsumsi'] = $dataTunjangan['total_konsumsi'];
    $data['tkomunikasi'] = $dataTunjangan['total_komunikasi'];
    $data['ttransport'] = $dataTunjangan['total_transportasi'];
    $data['bpjstk']     = tunjanganBPJStkpph($pengguna_id);
    $data['pendapatan_lain'] = isset($dataGaji[0]->pendapatan_lain) ? $dataGaji[0]->pendapatan_lain : 0;

    return array_sum($data);
}

function penghasilan_netto($pengguna_id)
{
    $CI = &get_instance();
    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);
    $id_wilayah_kerja = $dataPengguna[0]->id_wilayah_kerja;
    $gaji_terakhir = $dataPengguna[0]->id_latestriwayat_salary;
    $tanggal_masuk = $dataPengguna[0]->tgl_masuk;

    $masa_kerja = masaKerjaBulan($tanggal_masuk);
    $penghasilan_bruto = penghasilan_bruto($pengguna_id);
    $dataGaji = $CI->md_salary->getById($gaji_terakhir);

    $pengurangt_jabatan = round((5 / 100) * $penghasilan_bruto);
    if ($pengurangt_jabatan > 500000) {
        $data['tunjanganJabatan'] = 500000;
    } else {
        $data['tunjanganJabatan'] = $pengurangt_jabatan;
    }

    $dasar = isset($dataGaji[0]->dasar_potong_bpjs) ? $dataGaji[0]->dasar_potong_bpjs : 0;
    $umk = findUmkById($id_wilayah_kerja);

    $dasarbpjstk = isset($dataGaji[0]->dasar_bpjs_kerja) ? $dataGaji[0]->dasar_bpjs_kerja : 0;
    if ($dasarbpjstk != 0) {
        if ($dataPengguna[0]->id_divisi == 5 || $dataPengguna[0]->id_divisi == 6 || $dataPengguna[0]->id_divisi == 4) {
            $data['bpjstk'] = 0;
        } else {
            $data['bpjstk'] = round((6.24 / 100) * $umk['jumlah']);
        }
    } else {
        $data['bpjstk'] = 0;
    }

    $dasarbpjskes = isset($dataGaji[0]->dasar_potong_bpjs) ? $dataGaji[0]->dasar_potong_bpjs : 0;
    if ($dasarbpjskes != 0) {
        if ($dataPengguna[0]->id_divisi == 5) {
            $data['bpjskes'] = round((5 / 100) * $dasar);
        } else if ($dataPengguna[0]->id_divisi == 6) {
            $data['bpjskes'] = 0;
        } else {
            $data['bpjskes'] = round((5 / 100) * $dasar);
        }
    } else {
        $data['bpjskes'] = 0;
    }

    $pengurangan = array_sum($data);

    $netto = $penghasilan_bruto - $pengurangan;
    $tahunan = ($netto * 12);
    return $tahunan;
}

function penghasilan_kena_pajak($pengguna_id)
{
    $CI = &get_instance();
    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);

    $id_pasangan = $dataPengguna[0]->id_pasangan_sekantor;
    $nettoPasangan = $dataPengguna[0]->penghasilan_netto_suami;
    if ($id_pasangan != 0) {
        $dasar_perhitungan = penghasilan_netto($pengguna_id) + $nettoPasangan;
    } else {
        $dasar_perhitungan = penghasilan_netto($pengguna_id);
    }

    $status = statusFindbyId($dataPengguna[0]->id_status_perkawinan);
    $kena_pajak = $dasar_perhitungan - $status['jumlah'];
    return $kena_pajak;
}

function dasar_perhitungan_pajak_pribadi($pengguna_id)
{
    $CI = &get_instance();
    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);
    $id_pasangan = $dataPengguna[0]->id_pasangan_sekantor;
    $nettoPasangan = $dataPengguna[0]->penghasilan_netto_suami;

    if ($id_pasangan != 0) {
        $dasarPribadi = penghasilan_netto($pengguna_id) + $nettoPasangan;
    } else {
        $dasarPribadi = penghasilan_netto($pengguna_id);
    }
    return $dasarPribadi;
}

function penghasilan_sebulan($pengguna_id)
{
    $CI = &get_instance();
    $bulan = date("Y-m");
    $bulanItung = date("Y-m", strtotime('-1 month', strtotime($bulan)));

    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);
    $gaji_terakhir = $dataPengguna[0]->id_latestriwayat_salary;
    $dataGaji = $CI->md_salary->getById($gaji_terakhir);
    $dataTunjangan = tunjangan($pengguna_id, $bulanItung);

    $data['gapok'] = isset($dataGaji[0]->gaji_pokok) ? $dataGaji[0]->gaji_pokok : 0;
    $data['jabatan'] = isset($dataGaji[0]->tunjangan_jabatan) ? $dataGaji[0]->tunjangan_jabatan : 0;
    $data['tKinerja'] = $dataTunjangan['total_kinerja'];
    $data['tkonsumsi'] = $dataTunjangan['total_konsumsi'];
    $data['tkomunikasi'] = $dataTunjangan['total_komunikasi'];
    $data['ttransport'] = $dataTunjangan['total_transportasi'];
    $data['komisi'] = isset($dataGaji[0]->komisi) ? $dataGaji[0]->komisi : 0;
    $data['pendapatan_lain'] = isset($dataGaji[0]->pendapatan_lain) ? $dataGaji[0]->pendapatan_lain : 0;

    return array_sum($data);
}

function pph_new_perbulan($pengguna_id)
{
    $CI = &get_instance();
    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);
    $id_status_kawin = $dataPengguna[0]->id_status_perkawinan;
    $salary_sebulan = penghasilan_sebulan($pengguna_id);

    $salary_pph21 = 0;

    if ($id_status_kawin == 1 || $id_status_kawin == 2 || $id_status_kawin == 5)
    {
        if ($salary_sebulan > 0 && $salary_sebulan <= 5400000) {
            $salary_pph21 = 0;
        } elseif ($salary_sebulan > 5400000 && $salary_sebulan <= 5650000 ) {
             $salary_pph21 = $salary_sebulan * (0.25 / 100);
        } elseif ($salary_sebulan > 5650000 && $salary_sebulan <= 5950000 ) {
             $salary_pph21 = $salary_sebulan * (0.5 / 100);
        } elseif ($salary_sebulan > 5950000 && $salary_sebulan <= 6300000 ) {
             $salary_pph21 = $salary_sebulan * (0.75 / 100);
        } elseif ($salary_sebulan > 6300000 && $salary_sebulan <= 6750000 ) {
             $salary_pph21 = $salary_sebulan * (1 / 100);
        } elseif ($salary_sebulan > 6750000 && $salary_sebulan <= 7500000 ) {
             $salary_pph21 = $salary_sebulan * (1.25 / 100);
        } elseif ($salary_sebulan > 7500000 && $salary_sebulan <= 8550000 ) {
             $salary_pph21 = $salary_sebulan * (1.5 / 100);
        } elseif ($salary_sebulan > 8550000 && $salary_sebulan <= 9650000 ) {
             $salary_pph21 = $salary_sebulan * (1.75 / 100);
        } elseif ($salary_sebulan > 9650000 && $salary_sebulan <= 10050000 ) {
             $salary_pph21 = $salary_sebulan * (2 / 100);
        } elseif ($salary_sebulan > 10050000 && $salary_sebulan <= 10350000 ) {
             $salary_pph21 = $salary_sebulan * (2.25 / 100);
        } elseif ($salary_sebulan > 10350000 && $salary_sebulan <= 10700000 ) {
             $salary_pph21 = $salary_sebulan * (2.5 / 100);
        } elseif ($salary_sebulan > 10700000 && $salary_sebulan <= 11050000 ) {
             $salary_pph21 = $salary_sebulan * (3 / 100);
        } elseif ($salary_sebulan > 11050000 && $salary_sebulan <= 11600000 ) {
             $salary_pph21 = $salary_sebulan * (4 / 100);
        } elseif ($salary_sebulan > 11600000 && $salary_sebulan <= 12500000 ) {
             $salary_pph21 = $salary_sebulan * (4 / 100);
        } elseif ($salary_sebulan > 12500000 && $salary_sebulan <= 13750000 ) {
             $salary_pph21 = $salary_sebulan * (5 / 100);
        }
    } elseif ($id_status_kawin == 3 || $id_status_kawin == 4 || $id_status_kawin == 6 || $id_status_kawin == 7) {
        if ($salary_sebulan > 0 && $salary_sebulan <= 6200000) {
            $salary_pph21 = 0;
        } elseif ($salary_sebulan > 6200000 && $salary_sebulan <= 6500000 ) {
             $salary_pph21 = $salary_sebulan * (0.25 / 100);
        } elseif ($salary_sebulan > 6500000 && $salary_sebulan <= 6850000 ) {
             $salary_pph21 = $salary_sebulan * (0.5 / 100);
        } elseif ($salary_sebulan > 6850000 && $salary_sebulan <= 7300000 ) {
             $salary_pph21 = $salary_sebulan * (0.75 / 100);
        } elseif ($salary_sebulan > 7300000 && $salary_sebulan <= 9200000 ) {
             $salary_pph21 = $salary_sebulan * (1 / 100);
        } elseif ($salary_sebulan > 9200000 && $salary_sebulan <= 10750000 ) {
             $salary_pph21 = $salary_sebulan * (1.5 / 100);
        } elseif ($salary_sebulan > 10750000 && $salary_sebulan <= 11250000 ) {
             $salary_pph21 = $salary_sebulan * (2 / 100);
        }
    } elseif ($id_status_kawin == 8 || $id_status_kawin == 12) {
        if ($salary_sebulan > 0 && $salary_sebulan <= 6600000) {
            $salary_pph21 = 0;
        } elseif ($salary_sebulan > 17050000 && $salary_sebulan <= 19500000 ) {
             $salary_pph21 = $salary_sebulan * (7 / 100);
        } elseif ($salary_sebulan > 19500000 && $salary_sebulan <= 22700000 ) {
             $salary_pph21 = $salary_sebulan * (8 / 100);
        } elseif ($salary_sebulan > 22700000 && $salary_sebulan <= 26600000 ) {
             $salary_pph21 = $salary_sebulan * (9 / 100);
        } elseif ($salary_sebulan > 26600000 && $salary_sebulan <= 28100000 ) {
             $salary_pph21 = $salary_sebulan * (10 / 100);
        } elseif ($salary_sebulan > 28100000 && $salary_sebulan <= 30100000 ) {
             $salary_pph21 = $salary_sebulan * (11 / 100);
        }
    }

    return $salary_pph21;
}

function dasar_tarif($pengguna_id)
{
    $CI = &get_instance();
    $dataPengguna = $CI->md_pengguna->getById($pengguna_id);
    $jumlah = penghasilan_kena_pajak($pengguna_id);

    if ($dataPengguna[0]->npwp == "") {
        $npwp = 120 / 100;
    } else {
        $npwp = 100 / 100;
    }

    if ($jumlah < 0) {
        $pph21 = 0;
    } else {
        $pot1 = 0;
        $pot2 = 0;
        $pot3 = 0;
        $pot4 = 0;
        $pot5 = 0;

        if ($jumlah > 0 && $jumlah <= 60000000) {
            $pot1 = $jumlah * $npwp * (5 / 100);
        } elseif ($jumlah > 60000000) {
            $pot1 = 60000000 * $npwp * (5 / 100);
        }

        if ($jumlah > 60000000 && $jumlah <= 250000000) {
            $pot2 = ($jumlah - 60000000) * $npwp * (15 / 100);
        } elseif ($jumlah > 250000000) {
            $pot2 = 190000000 * $npwp * (15 / 100);
        }

        if ($jumlah > 250000000 && $jumlah <= 500000000) {
            $pot3 = ($jumlah - 250000000) * $npwp * (25 / 100);
        } elseif ($jumlah > 500000000) {
            $pot3 = 250000000 * $npwp * (25 / 100);
        }

        if ($jumlah > 500000000 && $jumlah <= 5000000000) {
            $pot4 = ($jumlah - 500000000) * $npwp * (30 / 100);
        } elseif ($jumlah > 5000000000) {
            $pot4 = 4500000000 * $npwp * (30 / 100);
        }

        if ($jumlah > 5000000000) {
            $pot5 = ($jumlah - 5000000000) * $npwp * (35 / 100);
        }

        $pph21_terutang_setahun = $pot1 + $pot2 + $pot3 + $pot4 + $pot5;
        $netto = penghasilan_netto($pengguna_id);
        $dasar = dasar_perhitungan_pajak_pribadi($pengguna_id);

        $id_pasangan = $dataPengguna[0]->id_pasangan_sekantor;

        if ($id_pasangan != 0) {
            $dasar_perhitungan = $netto / $dasar * $pph21_terutang_setahun;
        } else {
            $dasar_perhitungan = $pph21_terutang_setahun;
        }

        $tanggal_masuk = $dataPengguna[0]->tgl_masuk;
        $masa_kerja = masaKerjaBulan($tanggal_masuk);

        if ($masa_kerja > 12) {
            $pph21 = round($dasar_perhitungan / 12);
        } else {
            $pph21 = round($dasar_perhitungan / $masa_kerja);
        }
    }

    return $pph21;
}

function dppp($pengguna_id)
{
    return round(penghasilan_netto($pengguna_id) / dasar_perhitungan_pajak_pribadi($pengguna_id));
}
