/**
 * App State & Data Management - Corporate Office Portal & PBOK / PPA Audit Engine
 */

const defaultEmployees = [
    {
        id: '105',
        pengguna_id: '105',
        nik: '105',
        name: 'Muhammad Reza Fadila',
        dept: 'Finance, Accounting & Tax (FAT)',
        role: 'Staff Accounting',
        kpi: 88.5,
        quality: 4.2,
        sop: 89.0,
        attendance: 96.0,
        risk: 'Low',
        status: 'Terverifikasi (Out)',
        tenure: '25-03-2026 s/d Sekarang (Out 08-07-2026)'
    }
];
const defaultPbokList = [
    { id: 'AUD-REZ-01', code: '280/PBOK/FAT/VYM/VII/2026', date: '05-07-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pengajuan Pembelian ATK Juli 2026', amount: 611500, physicalStatus: 'Diajukan', officeUrl: 'https://drive.google.com/file/d/1E0HgyMsoadANwujrJybhKXTQpf6D0GWz/view?usp=sharing', taxVerified: true, taxVerifiedDate: '05-07-2026', notes: 'Pengajuan ATK bulanan operasional FAT' },
    { id: 'AUD-REZ-02', code: '276/PBOK/FAT/VYM/VII/2026', date: '02-07-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Listrik Gudang Jakarta (ID : 547301119764 - Sonny Badaruddin)', amount: 828064, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1WIqJl1vWndwbEq8ef4cw-rmfxvKSLM4u/view?usp=sharing', taxVerified: true, taxVerifiedDate: '02-07-2026', notes: 'Tagihan PLN Gudang Jakarta' },
    { id: 'AUD-REZ-03', code: '277/PBOK/FAT/VYM/VII/2026', date: '02-07-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Konsumsi Pantry Periode Juli 2026', amount: 800000, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1KCHG7cpxWziwDkYtyI8-lDZVhnz_9kfw/view?usp=drive_link', taxVerified: true, taxVerifiedDate: '02-07-2026', notes: 'Kebutuhan konsumsi pantry kantor' },
    { id: 'AUD-REZ-04', code: '278/PBOK/FAT/VYM/VII/2026', date: '02-07-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Cicilan Bank Mandiri', amount: 14152000, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1AYSCxyjJUJmr9Rb6FEIw0IEBEO_9gQnM/view?usp=sharing', taxVerified: true, taxVerifiedDate: '02-07-2026', notes: 'Cicilan rutin Bank Mandiri' },
    { id: 'AUD-REZ-05', code: '279/PBOK/FAT/VYM/VII/2026', date: '02-07-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran IPL Gudang Jakarta Periode Juli 2026', amount: 240000, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/142DLSzPKxVOJvJGF1YtuIO9z_SxwZ0dD/view?usp=sharing', taxVerified: true, taxVerifiedDate: '02-07-2026', notes: 'Iuran Pengelolaan Lingkungan Gudang Jakarta' },
    { id: 'AUD-REZ-06', code: '270/PBOK/FAT/VYM/VII/2026', date: '01-07-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran esign & ematerai PO ekatalog RSUP Jayapura', amount: 17330, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1nyv3E8SwSf-pa4NRuLFAtv8VcLJ9-ZDv/view?usp=sharing', taxVerified: true, taxVerifiedDate: '01-07-2026', notes: 'E-Sign PO E-Catalog Jayapura' },
    { id: 'AUD-REZ-07', code: '273/PBOK/FAT/VYM/VII/2026', date: '01-07-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Pembelian Esign & Emeterai untuk Addendum RSUP Jayapura', amount: 17330, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/12XcxlavwEIbkKxzXMON8i6OlH6BEcVSJ/view?usp=sharing', taxVerified: true, taxVerifiedDate: '01-07-2026', notes: 'E-Sign Addendum RSUP Jayapura' },
    { id: 'AUD-REZ-08', code: '269/PBOK/FAT/VYM/VI/2026', date: '30-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Deposit Accurate Juni 2026', amount: 366300, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/11t_VhF-imMaYy8DPfqefTRi0Q9AEn3JG/view?usp=drive_link', taxVerified: true, taxVerifiedDate: '30-06-2026', notes: 'Subscription Deposit Accurate Online' },
    { id: 'AUD-REZ-09', code: '266/PBOK/FAT/VYM/VI/2026', date: '26-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Pembelian Esign & Emeterai untuk Addendum RSUD Eka Candrarini', amount: 17330, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1SHRNcQZHeraiLMcecr36wpXrwjHZXUQ4/view?usp=sharing', taxVerified: true, taxVerifiedDate: '26-06-2026', notes: 'E-Sign Addendum Eka Candrarini' },
    { id: 'AUD-REZ-10', code: '258/PBOK/FAT/VYM/VI/2026', date: '25-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Pembelian Esign untuk BAST RSUD Sultan Sulaiman & RSUD Penyabungan', amount: 10216, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1P0tWh0MbnOuUGvGcmQn8m5zDR_NkyMST/view?usp=sharing', taxVerified: true, taxVerifiedDate: '25-06-2026', notes: 'E-Sign BAST RSUD' },
    { id: 'AUD-REZ-11', code: '256/PBOK/FAT/VYM/VI/2026', date: '25-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Pengadaan Seragam Batik Kantor VYM', amount: 14280000, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/14EGxOElI0bvRCYwVRTKF-5I6_Ge7nr9C/view?usp=sharing', taxVerified: true, taxVerifiedDate: '25-06-2026', notes: 'Pengadaan Batik VYM' },
    { id: 'AUD-REZ-12', code: '257/PBOK/FAT/VYM/VI/2026', date: '25-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Sewa Gudang Jakarta Periode 25 Juni 2026 s/d 25 Juni 2027', amount: 23000000, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/14EGxOElI0bvRCYwVRTKF-5I6_Ge7nr9C/view?usp=sharing', taxVerified: true, taxVerifiedDate: '25-06-2026', notes: 'Sewa Tahunan Gudang Jakarta' },
    { id: 'AUD-REZ-13', code: '246/PBOK/FAT/VYM/VI/2026', date: '23-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Gaji Karyawan Periode Juni 2026 (Draft 1)', amount: 0, physicalStatus: 'Diajukan', officeUrl: 'https://drive.google.com/file/d/1RBVFRR8d5oHOBWVv6745fECLPUSSH8YD/view?usp=drive_link', taxVerified: false, notes: 'Draft Pengajuan Gaji Juni 2026' },
    { id: 'AUD-REZ-14', code: '247/PBOK/FAT/VYM/VI/2026', date: '23-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Gaji Karyawan Periode Juni 2026 (Efektif 25 Juni 2026)', amount: 135262727, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1RBVFRR8d5oHOBWVv6745fECLPUSSH8YD/view?usp=drive_link', taxVerified: true, taxVerifiedDate: '23-06-2026', notes: 'Payroll Gaji Juni 2026' },
    { id: 'AUD-REZ-15', code: '248/PBOK/FAT/VYM/VI/2026', date: '23-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Perjanjian Kerjasama Konsultan Manajemen & PPh Pasal 21', amount: 8330000, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1qeYZrVFAEeCd2FqBNCz5kTD1BDcgaIke/view?usp=sharing', taxVerified: true, taxVerifiedDate: '23-06-2026', notes: 'Jasa Konsultan Manajemen' },
    { id: 'AUD-REZ-16', code: '243/PBOK/FAT/VYM/VI/2026', date: '22-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Cicilan Bank Mandiri', amount: 14152000, physicalStatus: 'Diajukan', officeUrl: 'https://drive.google.com/file/d/1ELdFjpS08teRVpvfunfyjF1BX_pyWid6/view?usp=sharing', taxVerified: false, notes: 'Pengajuan Cicilan Mandiri' },
    { id: 'AUD-REZ-17', code: '236/PBOK/FAT/VYM/VI/2026', date: '10-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Tunjangan Jabatan, Kinerja, Konsumsi, Komunikasi, Transportasi, BBM & Freelance', amount: 48611500, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1GdMPpLEs--bDq3KqF8fVYWIaeBccIZ8q/view?usp=sharing', taxVerified: true, taxVerifiedDate: '10-06-2026', notes: 'Tunjangan Operasional Karyawan' },
    { id: 'AUD-REZ-18', code: '221/PBOK/FAT/VYM/VI/2026', date: '04-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Sewa Axa Tower Periode 13 Juni 2026 s/d 12 Juli 2026 (AXA-INV/2026/1620)', amount: 8080000, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1IOyegR29gFcFHpd24w-TTFj2F6nYQCWX/view?usp=drive_link', taxVerified: true, taxVerifiedDate: '04-06-2026', notes: 'Sewa AXA Tower Juni-Juli' },
    { id: 'AUD-REZ-19', code: '222/PBOK/FAT/VYM/VI/2026', date: '04-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Hutang Pembelian Inv No SGM.2026.006 PT Sumpah Gajah Mada', amount: 9435000, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1SmXAprGJs-bgHxQ9DnE_5nv9qlJyTJR_/view?usp=drive_link', taxVerified: true, taxVerifiedDate: '04-06-2026', notes: 'Hutang Pembelian PT SGM' },
    { id: 'AUD-REZ-20', code: '217/PBOK/FAT/VYM/VI/2026', date: '03-06-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran E-sign & E-materai PO E-Catalog RSUD Eka Candrarini', amount: 17330, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1lszhG938aT77aoJImi8sGHnE5d4Sf8AY/view?usp=drive_link', taxVerified: true, taxVerifiedDate: '03-06-2026', notes: 'E-Sign PO E-Catalog Eka Candrarini' },
    { id: 'AUD-REZ-21', code: '210/PBOK/FAT/VYM/V/2026', date: '30-05-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran E-sign & E-materai PO E-Catalog RSUD Dumai', amount: 17330, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1XuYwZ_BSPNtkYbJ1qufAigdesdFsicyz/view?usp=sharing', taxVerified: true, taxVerifiedDate: '30-05-2026', notes: 'E-Sign PO Dumai' },
    { id: 'AUD-REZ-22', code: '188/PBOK/FAT/VYM/V/2026', date: '11-05-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran esign & ematerai PO ekatalog RSUD Sultan Sulaiman', amount: 17330, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1raazphWpp-MtPCo-RsF7j1tL3TZAT_TG/view?usp=sharing', taxVerified: true, taxVerifiedDate: '11-05-2026', notes: 'E-Sign PO Sultan Sulaiman' },
    { id: 'AUD-REZ-23', code: '185/PBOK/FAT/VYM/V/2026', date: '07-05-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Hutang Pembelian Inv No 2600845 + PPN', amount: 550782, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1IjP82aHKNqq78Keili5UaCcShWeeS7U9/view?usp=drive_link', taxVerified: true, taxVerifiedDate: '07-05-2026', notes: 'Hutang Pembelian Supplier' },
    { id: 'AUD-REZ-24', code: '168/PBOK/FAT/VYM/V/2026', date: '04-05-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Termin International Hospital Expo 2026', amount: 11673900, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1qI4A778qg3bjyPjvST2yOCKt_hIFfeQl/view?usp=sharing', taxVerified: true, taxVerifiedDate: '04-05-2026', notes: 'Akomodasi Hospital Expo' },
    { id: 'AUD-REZ-25', code: '169/PBOK/FAT/VYM/V/2026', date: '04-05-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Sewa Axa Tower Periode 13 May 2026 s/d 12 Juni 2026 (AXA-INV/2026/1200)', amount: 8080000, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1Fg3KHJ6KyOx-hIootQGnrAAg68OGc1P3/view?usp=sharing', taxVerified: true, taxVerifiedDate: '04-05-2026', notes: 'Sewa AXA Tower Mei-Juni' },
    { id: 'AUD-REZ-26', code: '166/PBOK/FAT/VYM/IV/2026', date: '30-04-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran esign & ematerai PO ekatalog RSUD Penyabungan', amount: 17330, physicalStatus: 'Diajukan', officeUrl: '', taxVerified: false, notes: 'Pengajuan E-Sign Penyabungan' },
    { id: 'AUD-REZ-27', code: '167/PBOK/FAT/VYM/IV/2026', date: '30-04-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran esign & ematerai PO ekatalog RSUD Penyabungan', amount: 17330, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1e_1fibYcSdquj7lF-LEGtnSS8_oTJ0UM/view?usp=drive_link', taxVerified: true, taxVerifiedDate: '30-04-2026', notes: 'E-Sign PO Penyabungan' },
    { id: 'AUD-REZ-28', code: '153/PBOK/FAT/VYM/IV/2026', date: '20-04-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Termin III Inv no SINV-10965', amount: 8309070, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1J4nLdJFEnRMviHRg3CSFojQnJ6h0iPjL/view?usp=drive_link', taxVerified: true, taxVerifiedDate: '20-04-2026', notes: 'Termin III Pembayaran' },
    { id: 'AUD-REZ-29', code: '123/PBOK/FAT/VYM/IV/2026', date: '02-04-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Sewa Axa Tower Periode 13 April 2026 s/d 12 Mei 2026 (AXA-INV/2026/0938)', amount: 8000000, physicalStatus: 'Diajukan', officeUrl: 'https://drive.google.com/file/d/1wGfaon95avV8lwMJZhvkkwB5PPbZ64ek/view?usp=sharing', taxVerified: false, notes: 'Pengajuan Sewa AXA Tower' },
    { id: 'AUD-REZ-30', code: '125/PBOK/FAT/VYM/IV/2026', date: '02-04-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Sewa Axa Tower Periode 13 April 2026 s/d 12 Mei 2026 (AXA-INV/2026/0938)', amount: 8000000, physicalStatus: 'Diajukan', officeUrl: 'https://drive.google.com/file/d/1wGfaon95avV8lwMJZhvkkwB5PPbZ64ek/view?usp=sharing', taxVerified: false, notes: 'Pengajuan Sewa AXA Tower' },
    { id: 'AUD-REZ-31', code: '126/PBOK/FAT/VYM/IV/2026', date: '02-04-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Sewa Axa Tower Periode 13 April 2026 s/d 12 Mei 2026 (AXA-INV/2026/0938)', amount: 8080000, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/1wGfaon95avV8lwMJZhvkkwB5PPbZ64ek/view', taxVerified: true, taxVerifiedDate: '02-04-2026', notes: 'Sewa AXA Tower April-Mei' },
    { id: 'AUD-REZ-32', code: '119/PBOK/FAT/VYM/III/2026', date: '26-03-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Termin II Inv no SINV-10765', amount: 8309070, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/file/d/14HGqFupQVZqiz8Sb9MVkQJRDY_M9ORP7/view?usp=drive_link', taxVerified: true, taxVerifiedDate: '26-03-2026', notes: 'Termin II Pembayaran' },
    { id: 'AUD-REZ-33', code: '118/PBOK/FAT/VYM/III/2026', date: '25-03-2026', pengguna_id: '105', nik: '105', name: 'Muhammad Reza Fadila', dept: 'Finance, Accounting & Tax (FAT)', item: 'Pembayaran Hutang Pembelian Inv No 2600195', amount: 9100890, physicalStatus: 'Terverifikasi Ada', officeUrl: 'https://drive.google.com/drive/folders/1lG5mYiLJba1FkN9i6EkVQTTrrZXq8hIL', taxVerified: true, taxVerifiedDate: '25-03-2026', notes: 'Hutang Pembelian Supplier' }
];

let deletedEmpIds = JSON.parse(localStorage.getItem('deleted_emp_ids') || '[]');
let deletedPbokIds = JSON.parse(localStorage.getItem('deleted_pbok_ids') || '[]');
let editedEmployees = JSON.parse(localStorage.getItem('edited_employees') || '{}');
let editedPboks = JSON.parse(localStorage.getItem('edited_pboks') || '{}');

function applyEmployeeEdits(emp) {
    if (!emp) return emp;
    const empKeyId = String(emp.id || emp.pengguna_id || '').toLowerCase().trim();
    const empKeyNik = String(emp.nik || '').toLowerCase().trim();
    const empKeyName = String(emp.name || emp.nama || '').toLowerCase().trim();

    const edit = editedEmployees[empKeyNik] || editedEmployees[empKeyId] || editedEmployees[empKeyName];
    if (edit) {
        return {
            ...emp,
            nik: edit.nik || emp.nik,
            name: edit.name || emp.name,
            dept: edit.dept || emp.dept,
            role: edit.role || emp.role,
            tenure: edit.tenure || emp.tenure,
            risk: edit.risk || emp.risk,
            status: edit.status || emp.status
        };
    }
    return emp;
}

function applyPbokEdits(p) {
    if (!p) return p;
    const pKeyId = String(p.id || '').toLowerCase().trim();
    const pKeyCode = String(p.code || p.kode || '').toLowerCase().trim();

    const edit = (pKeyId && editedPboks[pKeyId]) || (pKeyCode && editedPboks[pKeyCode]);
    if (edit) {
        return {
            ...p,
            ...edit
        };
    }
    return p;
}

function savePbokEditRecord(pbok) {
    if (!pbok) return;
    const pKeyId = String(pbok.id || '').toLowerCase().trim();
    const pKeyCode = String(pbok.code || '').toLowerCase().trim();

    // Status Fisik Barang otomatis mengikuti status verifikasi Head of Accounting (taxVerified)
    pbok.physicalStatus = pbok.taxVerified ? 'Terverifikasi Ada' : (pbok.lossAmount > 0 ? 'Fisik Tidak Ada' : 'Belum Diverifikasi');

    const editObj = {
        taxVerified: pbok.taxVerified,
        taxVerifiedBy: pbok.taxVerifiedBy,
        taxVerifiedDate: pbok.taxVerifiedDate,
        physicalStatus: pbok.physicalStatus,
        lossAmount: pbok.lossAmount,
        lossStatus: pbok.lossStatus,
        notes: pbok.notes,
        officeUrl: pbok.officeUrl
    };

    if (pKeyId) editedPboks[pKeyId] = editObj;
    if (pKeyCode) editedPboks[pKeyCode] = editObj;
    localStorage.setItem('edited_pboks', JSON.stringify(editedPboks));
}

function isEmployeeDeleted(e) {
    if (!e) return false;
    const eId = String(e.id || e.pengguna_id || '').toLowerCase().trim();
    const eNik = String(e.nik || '').toLowerCase().trim();
    const eName = String(e.name || e.nama || '').toLowerCase().trim();

    return (eId !== '' && deletedEmpIds.includes(eId)) ||
           (eNik !== '' && deletedEmpIds.includes(eNik)) ||
           (eName !== '' && deletedEmpIds.includes(eName));
}

function isPbokDeleted(p) {
    if (!p) return false;
    const pId = String(p.id || '').toLowerCase().trim();
    const pCode = String(p.code || p.kode || '').toLowerCase().trim();

    return (pId !== '' && deletedPbokIds.includes(pId)) ||
           (pCode !== '' && deletedPbokIds.includes(pCode));
}

let storedEmps = JSON.parse(localStorage.getItem('audit_employees') || 'null');
let storedPboks = JSON.parse(localStorage.getItem('audit_pbokList') || 'null');

// Filter data awal dari blacklist penghapusan & terapkan editan kustom
let initialEmps = (storedEmps && storedEmps.length > 0 ? storedEmps : defaultEmployees)
    .map(applyEmployeeEdits)
    .filter(e => !isEmployeeDeleted(e));
let initialPboks = (storedPboks && storedPboks.length > 0 ? storedPboks : defaultPbokList)
    .map(p => {
        const empEdit = applyEmployeeEdits({ id: p.pengguna_id, nik: p.nik, name: p.name });
        return applyPbokEdits({
            ...p,
            dept: empEdit.dept || p.dept
        });
    })
    .filter(p => !isPbokDeleted(p) && !isEmployeeDeleted(p));

// Pastikan seluruh 35 transaksi pengajuan dari PDF lampiran terdaftar sempurna
defaultPbokList.forEach(defItem => {
    const exists = initialPboks.some(p => String(p.code || '').toLowerCase() === String(defItem.code || '').toLowerCase());
    if (!exists && !isPbokDeleted(defItem) && !isEmployeeDeleted(defItem)) {
        initialPboks.push(defItem);
    }
});

const defaultPlans = [
    {
        id: 'PLAN-01',
        unit: 'Gudang & Logistik Alkes',
        tujuan: 'Memastikan pemenuhan pemantauan suhu & kalibrasi alat ukur alkes sesuai CDAKB Permenkes 4/2014.',
        kriteria: 'CDAKB Permenkes 4/2014 & ISO 9001:2015',
        auditor: 'Risma Nurhandayani, Yolanda Pratiwi',
        date: '2026-09-10',
        time: '09:00 - 14:00 WIB',
        suratTugas: '001/STG/PTVYM/XII/2025',
        status: 'completed'
    },
    {
        id: 'PLAN-02',
        unit: 'Keuangan & Pengadaan (PBOK/PPA)',
        tujuan: 'Verifikasi keabsahan bukti pengeluaran PBOK/PPA dan kesesuaian SOP pengadaan barang alkes.',
        kriteria: 'SOP VYM-007-HRGA-18-046 & ISO 9001:2015',
        auditor: 'Risma Nurhandayani, Kardonal',
        date: '2026-09-14',
        time: '10:00 - 15:30 WIB',
        suratTugas: '002/STG/PTVYM/XII/2025',
        status: 'in_progress'
    }
];

const defaultCars = [
    {
        id: 'CAR-01',
        noCar: 'CAR-01-09-2026',
        unit: 'Gudang & Logistik Alkes',
        kriteria: 'CDAKB Permenkes 4/2014 Poin 5.3',
        klasifikasi: 'major',
        dokumen: true,
        wawancara: true,
        observasi: true,
        uraian: 'Alat ukur suhu gudang alkes (thermo-hygrometer) belum dilakukan kalibrasi ulang oleh lembaga terakreditasi.',
        rencana: 'Melakukan pengajuan kalibrasi instrumen ke BPFK / Lembaga Kalibrasi Terakreditasi.',
        pic: 'Rahmat Hidayat (Head of Warehouse)',
        target: '2026-09-25',
        status: 'OPEN'
    },
    {
        id: 'CAR-02',
        noCar: 'CAR-02-09-2026',
        unit: 'Gudang & Logistik Alkes',
        kriteria: 'SOP Pengisian Form Pemantauan Suhu',
        klasifikasi: 'minor',
        dokumen: true,
        wawancara: false,
        observasi: true,
        uraian: 'Tidak ditemukan bukti pencatatan pemantauan suhu harian pada tanggal 3 & 4 September 2026.',
        rencana: 'Menunjuk backup officer pengisi logbook suhu saat petugas utama dinas luar.',
        pic: 'Staf Logistik',
        target: '2026-09-12',
        status: 'CLOSE'
    }
];

const defaultBeritaAcara = [
    {
        id: 'BA-01',
        noBa: 'BA-AUDIT-VYM-2026-001',
        date: '2026-09-15',
        lingkup: 'Audit Mutu Internal Gudang, Logistik & Pengadaan Alkes',
        hasil: 'Sistem manajemen mutu berjalan baik. Ditemukan 1 CAR Major (Kalibrasi alat ukur) dan 1 CAR Minor (Pencatatan suhu).',
        saran: 'Segera selesaikan pengajuan kalibrasi alat ukur alkes dan lakukan pembinaan konsistensi pengisian logbook harian.',
        dibuat: 'Risma Nurhandayani (GA / Auditor)',
        diketahui: 'Yolanda Pratiwi (Head of Admin)',
        disetujui: 'Bob Ariyos (Direktur Utama)'
    }
];

let auditState = {
    employees: initialEmps,
    pbokList: initialPboks,
    findings: JSON.parse(localStorage.getItem('audit_findings') || '[]'),
    plans: JSON.parse(localStorage.getItem('audit_plans') || JSON.stringify(defaultPlans)),
    cars: JSON.parse(localStorage.getItem('audit_cars') || JSON.stringify(defaultCars)),
    beritaAcara: JSON.parse(localStorage.getItem('audit_berita_acara') || JSON.stringify(defaultBeritaAcara)),
    filteredEmployees: [],
    filteredPbok: [],
    pbokChart: null,
    physicalChart: null
};

function saveStateToStorage() {
    localStorage.setItem('audit_employees', JSON.stringify(auditState.employees));
    localStorage.setItem('audit_pbokList', JSON.stringify(auditState.pbokList));
    localStorage.setItem('audit_findings', JSON.stringify(auditState.findings));
    localStorage.setItem('audit_plans', JSON.stringify(auditState.plans));
    localStorage.setItem('audit_cars', JSON.stringify(auditState.cars));
    localStorage.setItem('audit_berita_acara', JSON.stringify(auditState.beritaAcara));
    localStorage.setItem('deleted_emp_ids', JSON.stringify(deletedEmpIds));
    localStorage.setItem('deleted_pbok_ids', JSON.stringify(deletedPbokIds));
    localStorage.setItem('edited_employees', JSON.stringify(editedEmployees));
    localStorage.setItem('edited_pboks', JSON.stringify(editedPboks));

    syncCentralAuditState();
}

function syncCentralAuditState() {
    try {
        const payload = {
            edited_employees: editedEmployees,
            edited_pboks: editedPboks,
            deleted_emp_ids: deletedEmpIds,
            deleted_pbok_ids: deletedPbokIds,
            pbokList: auditState.pbokList,
            employees: auditState.employees,
            user: sessionUser?.name || 'Office User'
        };

        fetch('api.php?action=save_state', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        }).catch(err => {});
    } catch(e) {}
}

function fetchCentralAuditState() {
    fetch('api.php?action=get_state')
        .then(res => res.json())
        .then(res => {
            if (res && res.status === 'success' && res.data) {
                const data = res.data;
                let hasNewUpdates = false;

                if (data.edited_employees && Object.keys(data.edited_employees).length > 0) {
                    editedEmployees = { ...editedEmployees, ...data.edited_employees };
                    localStorage.setItem('edited_employees', JSON.stringify(editedEmployees));
                    hasNewUpdates = true;
                }

                if (data.edited_pboks && Object.keys(data.edited_pboks).length > 0) {
                    editedPboks = { ...editedPboks, ...data.edited_pboks };
                    localStorage.setItem('edited_pboks', JSON.stringify(editedPboks));
                    hasNewUpdates = true;
                }

                if (Array.isArray(data.deleted_emp_ids) && data.deleted_emp_ids.length > 0) {
                    deletedEmpIds = [...new Set([...deletedEmpIds, ...data.deleted_emp_ids])];
                    localStorage.setItem('deleted_emp_ids', JSON.stringify(deletedEmpIds));
                    hasNewUpdates = true;
                }

                if (Array.isArray(data.deleted_pbok_ids) && data.deleted_pbok_ids.length > 0) {
                    deletedPbokIds = [...new Set([...deletedPbokIds, ...data.deleted_pbok_ids])];
                    localStorage.setItem('deleted_pbok_ids', JSON.stringify(deletedPbokIds));
                    hasNewUpdates = true;
                }

                if (Array.isArray(data.employees) && data.employees.length > 0) {
                    data.employees.forEach(emp => {
                        const empId = String(emp.id || emp.pengguna_id || '').toLowerCase().trim();
                        const empNik = String(emp.nik || '').toLowerCase().trim();
                        const empName = String(emp.name || emp.nama || '').toLowerCase().trim();

                        const idx = auditState.employees.findIndex(item => {
                            const iId = String(item.id || item.pengguna_id || '').toLowerCase().trim();
                            const iNik = String(item.nik || '').toLowerCase().trim();
                            const iName = String(item.name || item.nama || '').toLowerCase().trim();
                            return (empId && iId === empId) || (empNik && iNik === empNik) || (empName && iName === empName);
                        });

                        if (idx !== -1) {
                            auditState.employees[idx] = applyEmployeeEdits({ ...auditState.employees[idx], ...emp });
                        } else if (!isEmployeeDeleted(emp)) {
                            auditState.employees.unshift(applyEmployeeEdits(emp));
                        }
                    });
                    hasNewUpdates = true;
                }

                if (Array.isArray(data.pbokList) && data.pbokList.length > 0) {
                    data.pbokList.forEach(p => {
                        const pId = String(p.id || '').toLowerCase().trim();
                        const pCode = String(p.code || p.kode || '').toLowerCase().trim();

                        const idx = auditState.pbokList.findIndex(item => {
                            const iId = String(item.id || '').toLowerCase().trim();
                            const iCode = String(item.code || '').toLowerCase().trim();
                            return (pId && iId === pId) || (pCode && iCode === pCode);
                        });

                        if (idx !== -1) {
                            auditState.pbokList[idx] = applyPbokEdits({ ...auditState.pbokList[idx], ...p });
                        } else if (!isPbokDeleted(p)) {
                            auditState.pbokList.unshift(applyPbokEdits(p));
                        }

                        if (p.name || p.nik) {
                            const empId = String(p.pengguna_id || '').toLowerCase().trim();
                            const empNik = String(p.nik || '').toLowerCase().trim();
                            const empName = String(p.name || '').toLowerCase().trim();

                            const empExists = auditState.employees.some(item => {
                                const iId = String(item.id || item.pengguna_id || '').toLowerCase().trim();
                                const iNik = String(item.nik || '').toLowerCase().trim();
                                const iName = String(item.name || item.nama || '').toLowerCase().trim();
                                return (empId && iId === empId) || (empNik && iNik === empNik) || (empName && iName === empName);
                            });

                            if (!empExists && !isEmployeeDeleted({ id: empId, nik: empNik, name: empName })) {
                                const empObj = applyEmployeeEdits({
                                    id: p.pengguna_id || `EMP-${Date.now()}`,
                                    pengguna_id: p.pengguna_id || '0',
                                    nik: p.nik || 'EMP-NEW',
                                    name: p.name || 'Karyawan',
                                    dept: p.dept || 'Operations & Logistics',
                                    role: 'Staff / Pemohon',
                                    kpi: 90,
                                    quality: 4.0,
                                    sop: 92,
                                    attendance: 95,
                                    risk: 'Low',
                                    status: 'Terverifikasi',
                                    tenure: p.tenure || '2024 - 2026 (Masa Menjabat)'
                                });
                                auditState.employees.unshift(empObj);
                            }
                        }
                    });
                    hasNewUpdates = true;
                }

                if (hasNewUpdates) {
                    auditState.employees = auditState.employees.map(applyEmployeeEdits).filter(e => !isEmployeeDeleted(e));
                    deduplicateEmployees();
                    auditState.pbokList = auditState.pbokList.filter(p => !isPbokDeleted(p) && !isEmployeeDeleted(p));
                    auditState.filteredEmployees = [...auditState.employees];
                    auditState.filteredPbok = [...auditState.pbokList];

                    localStorage.setItem('audit_employees', JSON.stringify(auditState.employees));
                    localStorage.setItem('audit_pbokList', JSON.stringify(auditState.pbokList));

                    renderOverview();
                    renderCharts();
                    renderTables();
                    updateDepartmentFilters();

                    const tenureModalEl = document.getElementById('employeeTenureModal');
                    if (activeTenureEmployee && tenureModalEl && tenureModalEl.classList.contains('active')) {
                        openEmployeeTenureModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id);
                    }

                    const printModalEl = document.getElementById('printAuditModal');
                    if (printModalEl && printModalEl.classList.contains('active')) {
                        if (activePrintEmployee) {
                            openPrintAuditModal(activePrintEmployee.nik, activePrintEmployee.name, activePrintEmployee.id);
                        } else if (activeTenureEmployee) {
                            openPrintAuditModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id);
                        }
                    }

                    const printKinerjaModalEl = document.getElementById('printAuditKinerjaModal');
                    if (printKinerjaModalEl && printKinerjaModalEl.classList.contains('active')) {
                        if (activePrintKinerjaEmployee) {
                            openPrintKinerjaModal(activePrintKinerjaEmployee.nik, activePrintKinerjaEmployee.name, activePrintKinerjaEmployee.id);
                        } else if (activeTenureEmployee) {
                            openPrintKinerjaModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id);
                        }
                    }
                }
            }
        })
        .catch(() => {});
}

/**
 * 4-Layer Bulletproof Deletion Engine:
 * 1. Animasi Hapus DOM Seketika (Instant Visual Response)
 * 2. Multi-Identifier Blacklist di LocalStorage
 * 3. Backup Cookie Browser
 * 4. Background Sync POST ke Server CodeIgniter
 */
function confirmDeleteEmployee(btn) {
    if (!btn) return;
    const id = (btn.getAttribute('data-id') || '').trim();
    const nik = (btn.getAttribute('data-nik') || '').trim();
    const name = (btn.getAttribute('data-name') || '').trim();

    const displayName = name || nik || id || 'Karyawan';

    if (!confirm(`Apakah Anda yakin ingin menghapus data audit "${displayName}"?`)) {
        return;
    }

    // Layer 1: Animasi Hapus Baris Tabel di Layar (Seketika)
    const tr = btn.closest('tr');
    if (tr) {
        tr.style.transition = 'all 0.3s ease';
        tr.style.opacity = '0';
        tr.style.transform = 'scale(0.95)';
        setTimeout(() => { tr.remove(); }, 300);
    }

    // Layer 2: Masukkan Seluruh Pengenal ke Blacklist
    if (id) deletedEmpIds.push(id.toLowerCase());
    if (nik) deletedEmpIds.push(nik.toLowerCase());
    if (name) deletedEmpIds.push(name.toLowerCase());

    auditState.employees.concat(auditState.pbokList).forEach(item => {
        const itemEmpId = String(item.id || item.pengguna_id || item.user_id || '').toLowerCase().trim();
        const itemNik = String(item.nik || '').toLowerCase().trim();
        const itemName = String(item.name || item.nama || '').toLowerCase().trim();

        const matchId = id && itemEmpId === id.toLowerCase();
        const matchNik = nik && itemNik === nik.toLowerCase();
        const matchName = name && (itemName === name.toLowerCase() || itemName.includes(name.toLowerCase()));

        if (matchId || matchNik || matchName) {
            if (itemEmpId) deletedEmpIds.push(itemEmpId);
            if (itemNik) deletedEmpIds.push(itemNik);
            if (itemName) deletedEmpIds.push(itemName);
            if (item.id) deletedPbokIds.push(String(item.id).toLowerCase());
            if (item.code) deletedPbokIds.push(String(item.code).toLowerCase());
        }
    });

    deletedEmpIds = [...new Set(deletedEmpIds.filter(Boolean))];
    deletedPbokIds = [...new Set(deletedPbokIds.filter(Boolean))];

    // Layer 3: Filter State & Simpan ke LocalStorage + Cookie
    auditState.employees = auditState.employees.filter(e => !isEmployeeDeleted(e));
    auditState.filteredEmployees = [...auditState.employees];

    auditState.pbokList = auditState.pbokList.filter(p => !isEmployeeDeleted(p) && !isPbokDeleted(p));
    auditState.filteredPbok = [...auditState.pbokList];

    saveStateToStorage();

    // Layer 4: Background Sync ke Server CodeIgniter
    try {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('nik', nik);
        formData.append('name', name);
        fetch('https://office.visiyosindo.id/pengguna/delete_audit_employee', {
            method: 'POST',
            body: formData
        }).catch(() => {});
    } catch(e) {}

    renderOverview();
    renderCharts();

    if (activeTenureEmployee && isEmployeeDeleted(activeTenureEmployee)) {
        closeTenureModal();
    }
}

/**
 * 4-Layer Bulletproof Deletion Engine untuk PBOK / PPA
 */
function confirmDeletePbok(btn) {
    if (!btn) return;
    const id = (btn.getAttribute('data-id') || '').trim();
    const code = (btn.getAttribute('data-code') || '').trim();

    const displayCode = code || id || 'Pengajuan';

    if (!confirm(`Apakah Anda yakin ingin menghapus pengajuan "${displayCode}"?`)) {
        return;
    }

    // Layer 1: Animasi Hapus Baris Tabel di Layar
    const tr = btn.closest('tr');
    if (tr) {
        tr.style.transition = 'all 0.3s ease';
        tr.style.opacity = '0';
        tr.style.transform = 'scale(0.95)';
        setTimeout(() => { tr.remove(); }, 300);
    }

    // Layer 2 & 3: Blacklist & Storage
    if (id) deletedPbokIds.push(id.toLowerCase());
    if (code) deletedPbokIds.push(code.toLowerCase());

    deletedPbokIds = [...new Set(deletedPbokIds.filter(Boolean))];

    auditState.pbokList = auditState.pbokList.filter(p => !isPbokDeleted(p));
    auditState.filteredPbok = [...auditState.pbokList];

    saveStateToStorage();
    renderOverview();
    renderCharts();

    const tenureModalEl = document.getElementById('employeeTenureModal');
    if (activeTenureEmployee && tenureModalEl && tenureModalEl.classList.contains('active')) {
        openEmployeeTenureModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id);
    }
}

/**
 * Fungsi Hapus Total Seluruh Data Karyawan & Audit
 */
function clearAllEmployeesAndAuditData() {
    if (confirm('🚨 HAPUS SELURUH DATA AUDIT & KARYAWAN:\n\nApakah Anda yakin ingin menghapus SELURUH data karyawan dan transaksi PBOK/PPA dari menu audit? Layar audit akan dikosongkan total.')) {
        auditState.employees = [];
        auditState.pbokList = [];
        auditState.findings = [];
        auditState.filteredEmployees = [];
        auditState.filteredPbok = [];
        deletedEmpIds = [];
        deletedPbokIds = [];
        editedEmployees = {};
        editedPboks = {};

        localStorage.removeItem('audit_employees');
        localStorage.removeItem('audit_pbokList');
        localStorage.removeItem('audit_findings');
        localStorage.removeItem('deleted_emp_ids');
        localStorage.removeItem('deleted_pbok_ids');
        localStorage.removeItem('edited_employees');
        localStorage.removeItem('edited_pboks');

        saveStateToStorage();
        renderOverview();
        renderCharts();
        renderTables();
        updateDepartmentFilters();

        fetch('api.php?action=purge_state')
            .finally(() => {
                alert('✅ Seluruh data karyawan & audit berhasil dikosongkan.');
                location.reload();
            });
    }
}

function purgeAuditStorage() {
    clearAllEmployeesAndAuditData();
}

document.addEventListener('DOMContentLoaded', () => {
    initApp();
    fetchSessionUser();
    fetchCentralAuditState();
    fetchFullAuditDataLive();

    // Auto-sync realtime terpusat antar akun office setiap 5 detik
    setInterval(fetchCentralAuditState, 5000);
});

/**
 * Mengambil Data Karyawan + Transaksi PBOK & PPA Realtime 100% dari Database Office Visi Yosindo
 */
function fetchFullAuditDataLive() {
    const fullApiUrls = [
        'https://office.visiyosindo.id/pengguna/get_audit_full_data_json',
        '/pengguna/get_audit_full_data_json',
        '../pengguna/get_audit_full_data_json',
        'https://office.visiyosindo.id/surat/get_audit_full_data_json',
        '/surat/get_audit_full_data_json'
    ];

    function tryFetchFull(index) {
        if (index >= fullApiUrls.length) {
            // Fallback ke pemanggilan terpisah jika API gabungan belum aktif
            fetchRealDatabaseEmployees();
            fetchRealDatabasePbokPpa();
            return;
        }

        fetch(fullApiUrls[index])
            .then(res => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(data => {
                if (data && data.status === 'success') {
                    console.log('⚡ BERHASIL MEMUAT DATA AUDIT REALTIME LIVE:', data);

                    // Update Data Karyawan Realtime (Mempertimbangkan Blacklist Penghapusan & Editan User)
                    if (Array.isArray(data.employees) && data.employees.length > 0) {
                        const fetchedEmps = data.employees.map((emp, i) => applyEmployeeEdits({
                            id: emp.id || emp.pengguna_id || (i + 1),
                            pengguna_id: emp.pengguna_id || emp.id,
                            nik: emp.nik || `EMP-${100 + i}`,
                            name: emp.name || emp.nama || `Karyawan ${i + 1}`,
                            dept: emp.dept || emp.divisi || 'Operations & Logistics',
                            role: emp.role || emp.jabatan || 'Staff',
                            kpi: parseFloat(emp.kpi || 90),
                            quality: parseFloat(emp.quality || 4.0),
                            sop: parseFloat(emp.sop || 92),
                            attendance: parseFloat(emp.attendance || 95),
                            risk: emp.risk || 'Low',
                            status: emp.status || 'Terverifikasi',
                            tenure: emp.tenure || '2024 - 2026 (Masa Menjabat)'
                        })).filter(e => !isEmployeeDeleted(e));

                        fetchedEmps.forEach(emp => {
                            const empId = String(emp.id || emp.pengguna_id || '').toLowerCase().trim();
                            const empNik = String(emp.nik || '').toLowerCase().trim();
                            const empName = String(emp.name || emp.nama || '').toLowerCase().trim();

                            const idx = auditState.employees.findIndex(item => {
                                const iId = String(item.id || item.pengguna_id || '').toLowerCase().trim();
                                const iNik = String(item.nik || '').toLowerCase().trim();
                                const iName = String(item.name || item.nama || '').toLowerCase().trim();
                                return (empId && iId === empId) || (empNik && iNik === empNik) || (empName && iName === empName);
                            });

                            if (idx !== -1) {
                                auditState.employees[idx] = applyEmployeeEdits({ ...auditState.employees[idx], ...emp });
                            } else if (!isEmployeeDeleted(emp)) {
                                auditState.employees.push(applyEmployeeEdits(emp));
                            }
                        });

                        auditState.employees = auditState.employees.map(applyEmployeeEdits).filter(e => !isEmployeeDeleted(e));
                        deduplicateEmployees();
                        auditState.filteredEmployees = [...auditState.employees];
                    }

                    // Update Data PBOK & PPA Realtime (Mempertimbangkan Blacklist Penghapusan User & Verifikasi Server)
                    if (Array.isArray(data.pbok_ppa) && data.pbok_ppa.length > 0) {
                        data.pbok_ppa.forEach((p, i) => {
                            const id = p.id || (i + 101);
                            const code = p.code || p.kode || `PBOK/PPA-${i + 1}`;

                            const idx = auditState.pbokList.findIndex(item => 
                                String(item.id) === String(id) || String(item.code) === String(code)
                            );

                            if (idx !== -1) {
                                auditState.pbokList[idx] = {
                                    ...auditState.pbokList[idx],
                                    amount: parseFloat(p.amount || p.nominal || auditState.pbokList[idx].amount),
                                    item: p.item || p.perihal || auditState.pbokList[idx].item
                                };
                            } else if (!isPbokDeleted(p) && !isEmployeeDeleted(p)) {
                                auditState.pbokList.unshift({
                                    id: id,
                                    code: code,
                                    pengguna_id: p.pengguna_id || p.user_id || 0,
                                    nik: p.nik || 'EMP-000',
                                    name: p.name || p.nama_pemohon || 'Karyawan',
                                    dept: p.dept || p.divisi_nama || 'Operations & Logistics',
                                    tenure: p.tenure || 'Masa Menjabat',
                                    item: p.item || p.perihal || 'Pengajuan Belanja Office',
                                    amount: parseFloat(p.amount || p.nominal || 0),
                                    physicalStatus: p.physicalStatus || 'Terverifikasi Ada',
                                    lossStatus: (parseFloat(p.lossAmount || 0) > 0 ? 'Potensi Kerugian' : 'Aman / Wajar'),
                                    lossAmount: parseFloat(p.lossAmount || 0),
                                    officeUrl: p.officeUrl || (p.notes && p.notes.includes('http') ? p.notes.match(/https?:\/\/[^\s]+/)?.[0] : '') || '',
                                    notes: p.notes || 'Data terintegrasi live dari database office.'
                                });
                            }
                        });

                        auditState.filteredPbok = [...auditState.pbokList];
                    }

                    renderOverview();
                    renderCharts();
                    renderTables();

                    // Panggil sinkronisasi data terpusat agar verifikasi dari Device A langsung tampil di Device B
                    fetchCentralAuditState();
                } else {
                    tryFetchFull(index + 1);
                }
            })
            .catch(err => {
                console.log(`Menjajal API alternatif (${index + 1})...`);
                tryFetchFull(index + 1);
            });
    }

    tryFetchFull(0);
}

/**
 * Membaca Profil User Session Login Aktif dari CodeIgniter Office
 */
function fetchSessionUser() {
    const sessionUrls = [
        'https://office.visiyosindo.id/auth/get_user_session_json',
        '/auth/get_user_session_json',
        '../auth/get_user_session_json',
        'https://office.visiyosindo.id/pengguna/get_user_session_json'
    ];

    function tryFetchUser(index) {
        if (index >= sessionUrls.length) return;

        fetch(sessionUrls[index])
            .then(res => res.json())
            .then(user => {
                if (user && (user.nama || user.username || user.pengguna_id)) {
                    const nameDisp = user.nama || user.username || 'User Office';
                    const idDisp = user.pengguna_id || user.id || '58';
                    const roleDisp = user.level || user.jabatan || 'GA & Asset Control';

                    const nameEl = document.getElementById('displayUserName');
                    const roleEl = document.getElementById('displayUserRole');

                    if (nameEl) nameEl.innerText = `User ID: ${idDisp} (${nameDisp})`;
                    if (roleEl) roleEl.innerText = `${roleDisp} — Visi Yosindo Medikal`;
                }
            })
            .catch(() => tryFetchUser(index + 1));
    }

    tryFetchUser(0);
}

/**
 * Mengambil Data Pengajuan Belanja PBOK & PPA Aset Asli dari Database Office
 */
function fetchRealDatabasePbokPpa() {
    const apiUrls = [
        'https://office.visiyosindo.id/surat/get_office_pbok_ppa_live',
        '/surat/get_office_pbok_ppa_live',
        'https://office.visiyosindo.id/pengguna/get_office_pbok_ppa_live',
        '/pengguna/get_office_pbok_ppa_live',
        'https://office.visiyosindo.id/surat/get_audit_pbok_ppa_json',
        '/surat/get_audit_pbok_ppa_json'
    ];

    function tryFetchPbok(index) {
        if (index >= apiUrls.length) return;

        fetch(apiUrls[index])
            .then(res => {
                if (!res.ok) throw new Error('HTTP error ' + res.status);
                return res.json();
            })
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    console.log('Berhasil memuat pengajuan PBOK & PPA live dari Surat Office Visi Yosindo:', data.length);
                    
                    const fetchedPboks = data.map((p, i) => ({
                        id: p.id || (i + 101),
                        code: p.code || p.no_pbok || p.no_ppa || `PBOK/PPA-${2026}-${i + 1}`,
                        pengguna_id: p.pengguna_id || p.user_id || 0,
                        nik: p.nik || 'EMP-000',
                        name: p.name || p.nama_pemohon || 'Karyawan',
                        dept: p.dept || p.divisi_nama || 'Operations & Logistics',
                        tenure: p.tenure || 'Masa Menjabat',
                        item: p.item || p.perihal || p.deskripsi || 'Pengajuan Belanja Office',
                        amount: parseFloat(p.amount || p.nominal || p.total_nominal || 0),
                        physicalStatus: p.physicalStatus || p.status_fisik || 'Terverifikasi Ada',
                        lossStatus: p.lossStatus || (parseFloat(p.lossAmount || 0) > 0 ? 'Potensi Kerugian' : 'Aman / Wajar'),
                        lossAmount: parseFloat(p.lossAmount || 0),
                        notes: p.notes || 'Data terintegrasi live dari aktivitas pengajuan office.'
                    })).filter(p => !isPbokDeleted(p) && !isEmployeeDeleted(p));

                    defaultPbokList.filter(p => String(p.id).startsWith('AUD-')).forEach(audItem => {
                        const exists = fetchedPboks.some(p => String(p.id) === String(audItem.id) || String(p.code) === String(audItem.code));
                        if (!exists && !isPbokDeleted(audItem) && !isEmployeeDeleted(audItem)) {
                            fetchedPboks.push(audItem);
                        }
                    });

                    auditState.pbokList = fetchedPboks;
                    auditState.filteredPbok = [...auditState.pbokList];
                    renderOverview();
                    renderCharts();
                    renderTables();
                }
            })
            .catch(err => {
                tryFetchPbok(index + 1);
            });
    }

    tryFetchPbok(0);
}

/**
 * Mengambil Data Karyawan Asli dari Database Office Visi Yosindo Medikal
 */
function fetchRealDatabaseEmployees() {
    // Mencoba mengambil data karyawan asli dari API Database CodeIgniter Visi Yosindo Medikal
    const apiUrls = [
        'https://office.visiyosindo.id/pengguna/get_employees_json',
        '/pengguna/get_employees_json',
        '../pengguna/get_employees_json'
    ];

    function tryFetch(index) {
        if (index >= apiUrls.length) {
            console.log('Menggunakan data awal sampel audit (Menunggu koneksi API database live).');
            return;
        }

        fetch(apiUrls[index])
            .then(response => {
                if (!response.ok) throw new Error('HTTP error ' + response.status);
                return response.json();
            })
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    console.log('Berhasil memuat data karyawan asli dari Database Visi Yosindo:', data.length);
                    
                    const fetchedEmps = data.map((emp, i) => applyEmployeeEdits({
                        id: emp.id || emp.pengguna_id || (i + 1),
                        pengguna_id: emp.pengguna_id || emp.id,
                        nik: emp.nik || emp.no_pegawai || `EMP-${100 + i}`,
                        name: emp.nama || emp.nama_lengkap || `Karyawan ${i + 1}`,
                        dept: emp.divisi || emp.divisi_nama || 'Operations & Logistics',
                        role: emp.jabatan || 'Staff',
                        kpi: parseFloat(emp.kpi || 90),
                        quality: parseFloat(emp.quality || 4.0),
                        sop: parseFloat(emp.sop || 92),
                        attendance: parseFloat(emp.attendance || 95),
                        risk: emp.risk || 'Low',
                        status: emp.status || 'Terverifikasi',
                        notes: emp.notes || 'Karyawan terdaftar aktif di database kantor Visi Yosindo Medikal.'
                    })).filter(e => !isEmployeeDeleted(e));

                    fetchedEmps.forEach(emp => {
                        const empId = String(emp.id || emp.pengguna_id || '').toLowerCase().trim();
                        const empNik = String(emp.nik || '').toLowerCase().trim();
                        const empName = String(emp.name || emp.nama || '').toLowerCase().trim();

                        const idx = auditState.employees.findIndex(item => {
                            const iId = String(item.id || item.pengguna_id || '').toLowerCase().trim();
                            const iNik = String(item.nik || '').toLowerCase().trim();
                            const iName = String(item.name || item.nama || '').toLowerCase().trim();
                            return (empId && iId === empId) || (empNik && iNik === empNik) || (empName && iName === empName);
                        });

                        if (idx !== -1) {
                            auditState.employees[idx] = applyEmployeeEdits({ ...auditState.employees[idx], ...emp });
                        } else if (!isEmployeeDeleted(emp)) {
                            auditState.employees.push(applyEmployeeEdits(emp));
                        }
                    });

                    auditState.employees = auditState.employees.map(applyEmployeeEdits).filter(e => !isEmployeeDeleted(e));
                    deduplicateEmployees();
                    auditState.filteredEmployees = [...auditState.employees];
                    renderOverview();
                    renderCharts();
                    renderTables();
                }
            })
            .catch(err => {
                console.log(`Gagal memuat API dari ${apiUrls[index]}, mencoba alternatif...`);
                tryFetch(index + 1);
            });
    }

    tryFetch(0);
}

function initApp() {
    auditState.filteredEmployees = [...auditState.employees];
    auditState.filteredPbok = [...auditState.pbokList];

    setupSidebarToggle();
    setupTabNavigation();
    setupFilters();
    setupModals();
    setupExcelImport();

    renderOverview();
    renderCharts();
    renderTables();

    // Bind Export Buttons
    document.getElementById('btnExportExcelMain').addEventListener('click', handleExportAll);
    document.getElementById('btnExportFullWorkbook5').addEventListener('click', handleExportAll);
}

function setupSidebarToggle() {
    const btnToggle = document.getElementById('btnToggleSidebar');
    const appContainer = document.querySelector('.app-container');
    if (!btnToggle || !appContainer) return;

    // Check saved state
    const isCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
    if (isCollapsed) {
        appContainer.classList.add('sidebar-collapsed');
    }

    btnToggle.addEventListener('click', () => {
        appContainer.classList.toggle('sidebar-collapsed');
        const nowCollapsed = appContainer.classList.contains('sidebar-collapsed');
        localStorage.setItem('sidebar_collapsed', nowCollapsed ? 'true' : 'false');
    });
}

/* Tab Switching */
function setupTabNavigation() {
    const navItems = document.querySelectorAll('.sidebar-nav .nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const tabId = item.getAttribute('data-tab');
            switchTab(tabId);
        });
    });
}

function switchTab(tabId) {
    document.querySelectorAll('.sidebar-nav .nav-item').forEach(el => {
        el.classList.remove('active');
        if (el.getAttribute('data-tab') === tabId) el.classList.add('active');
    });

    document.querySelectorAll('.tab-page').forEach(page => page.classList.remove('active'));

    const targetPage = document.getElementById(`tab-${tabId}`);
    if (targetPage) targetPage.classList.add('active');

    const pageTitle = document.getElementById('pageTitle');
    const pageSubtitle = document.getElementById('pageSubtitle');

    switch(tabId) {
        case 'overview':
            pageTitle.innerText = 'Office Overview & Analytics Dashboard';
            pageSubtitle.innerText = 'Monitoring kinerja SDM dan verifikasi realisasi pengajuan belanja PBOK & PPA kantor';
            break;
        case 'audit-plan':
            pageTitle.innerText = 'Program & Rencana Audit Internal (Audit Plan)';
            pageSubtitle.innerText = 'Perencanaan jadwal audit unit/divisi, kriteria acuan ISO 9001:2015 & CDAKB, serta Surat Tugas';
            break;
        case 'audit-list':
            pageTitle.innerText = 'Audit Kinerja & Evaluasi Karyawan';
            pageSubtitle.innerText = 'Tabel evaluasi target KPI, kualitas kerja, kepatuhan SOP, dan presensi';
            break;
        case 'pbok-ppa':
            pageTitle.innerText = 'Audit Pengajuan Belanja PBOK & PPA (Masa Menjabat)';
            pageSubtitle.innerText = 'Verifikasi keabsahan belanja kantor, fisik barang, dan pencegahan kerugian perusahaan';
            break;
        case 'findings':
            pageTitle.innerText = 'Lembar Temuan CAR (Corrective Action Request) & Monitoring';
            pageSubtitle.innerText = 'Daftar temuan ketidaksesuaian Major, Minor, Rekomendasi, PIC, dan status perbaikan';
            break;
        case 'berita-acara':
            pageTitle.innerText = 'Berita Acara Laporan & Persetujuan Audit Internal';
            pageSubtitle.innerText = 'Dokumen Berita Acara hasil audit dengan pengesahan tanda tangan Direktur Utama';
            break;
        case 'excel-reports':
            pageTitle.innerText = 'Pusat Ekspor Laporan Excel Multi-Sheet (.xlsx)';
            pageSubtitle.innerText = 'Unduh berkas laporan resmi terintegrasi 5-Sheet terstruktur';
            break;
    }
}

/* Overview KPIs */
function renderOverview() {
    const pbok = auditState.pbokList;
    const emp = auditState.employees;

    const totalPbokAmount = pbok.reduce((acc, curr) => acc + curr.amount, 0);
    const verifiedOkCount = pbok.filter(p => p.physicalStatus === 'Terverifikasi Ada').length;
    const verifiedPct = ((verifiedOkCount / (pbok.length || 1)) * 100).toFixed(1);
    const totalLoss = pbok.reduce((acc, curr) => acc + curr.lossAmount, 0);

    const avgScore = (emp.reduce((acc, curr) => acc + curr.quality, 0) / (emp.length || 1)).toFixed(2);

    document.getElementById('kpiTotalPbokAmount').innerText = ExcelAuditEngine.formatRupiah(totalPbokAmount);
    document.getElementById('kpiVerifiedPhysical').innerText = `${verifiedPct}%`;
    document.getElementById('kpiPotentialLoss').innerText = ExcelAuditEngine.formatRupiah(totalLoss);
    document.getElementById('kpiAvgEmployeeScore').innerHTML = `${avgScore} <small>/ 5.0</small>`;

    const lossBadge = document.getElementById('kpiLossStatus');
    if (totalLoss > 0) {
        lossBadge.className = 'kpi-badge badge-critical';
        lossBadge.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> Indikasi Kerugian: ${ExcelAuditEngine.formatRupiah(totalLoss)}`;
    } else {
        lossBadge.className = 'kpi-badge badge-positive';
        lossBadge.innerHTML = `<i class="fa-solid fa-circle-check"></i> Bebas Risiko Kerugian`;
    }
}

function getKPISummaryObject() {
    const pbok = auditState.pbokList;
    const emp = auditState.employees;

    const totalPbokAmount = pbok.reduce((acc, curr) => acc + curr.amount, 0);
    const verifiedOkCount = pbok.filter(p => p.physicalStatus === 'Terverifikasi Ada').length;
    const verifiedPct = ((verifiedOkCount / (pbok.length || 1)) * 100).toFixed(1);
    const totalLoss = pbok.reduce((acc, curr) => acc + curr.lossAmount, 0);

    const avgScore = (emp.reduce((acc, curr) => acc + curr.quality, 0) / (emp.length || 1)).toFixed(2);
    const sopCompliance = (emp.reduce((acc, curr) => acc + curr.sop, 0) / (emp.length || 1)).toFixed(1);

    return {
        totalPbokAmount,
        verifiedPhysicalPct: verifiedPct,
        totalPotentialLoss: totalLoss,
        totalEmployees: emp.length,
        avgScore,
        sopCompliance
    };
}

/* Charts */
function renderCharts() {
    renderPbokDeptChart();
    renderPhysicalStatusChart();
}

function renderPbokDeptChart() {
    const ctx = document.getElementById('pbokDeptChart');
    if (!ctx) return;

    const depts = [...new Set(auditState.pbokList.map(p => p.dept))];
    const amounts = depts.map(dept => {
        return auditState.pbokList.filter(p => p.dept === dept).reduce((acc, curr) => acc + curr.amount, 0);
    });

    if (auditState.pbokChart) auditState.pbokChart.destroy();

    auditState.pbokChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: depts,
            datasets: [{
                label: 'Nilai Belanja (Rp)',
                data: amounts,
                backgroundColor: 'rgba(6, 182, 212, 0.7)',
                borderColor: '#06b6d4',
                borderWidth: 1.5,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: {
                        color: '#9ca3af',
                        callback: function(value) { return 'Rp ' + (value / 1000000) + ' Jt'; }
                    }
                },
                x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } }
            }
        }
    });
}

function renderPhysicalStatusChart() {
    const ctx = document.getElementById('physicalStatusChart');
    if (!ctx) return;

    const pbok = auditState.pbokList;
    const countOk = pbok.filter(p => p.physicalStatus === 'Terverifikasi Ada').length;
    const countMissing = pbok.filter(p => p.physicalStatus === 'Fisik Tidak Ada').length;
    const countFake = pbok.filter(p => p.physicalStatus === 'Indikasi Fiktif').length;

    if (auditState.physicalChart) auditState.physicalChart.destroy();

    auditState.physicalChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Terverifikasi Ada', 'Fisik Tidak Ada', 'Indikasi Fiktif'],
            datasets: [{
                data: [countOk, countMissing, countFake],
                backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#9ca3af', padding: 14 } }
            },
            cutout: '70%'
        }
    });
}

/* Tables */
function renderTables() {
    renderMainAuditTable();
    renderPbokEmployeeTable();
    renderFindingsTable();
    renderAuditPlanTable();
    renderBeritaAcaraList();
}

function openOfficePlanModal() {
    const m = document.getElementById('officePlanModal');
    if (m) { m.classList.add('active'); m.style.display = 'flex'; }
}

function saveOfficePlan(e) {
    e.preventDefault();
    const plan = {
        id: 'PLAN-' + Date.now(),
        unit: document.getElementById('inputPlanUnit').value.trim(),
        kriteria: document.getElementById('inputPlanKriteria').value.trim(),
        tujuan: document.getElementById('inputPlanTujuan').value.trim(),
        auditor: document.getElementById('inputPlanAuditor').value.trim(),
        suratTugas: document.getElementById('inputPlanSuratTugas').value.trim(),
        date: document.getElementById('inputPlanDate').value,
        time: document.getElementById('inputPlanTime').value.trim(),
        status: document.getElementById('inputPlanStatus').value
    };
    auditState.plans.unshift(plan);
    saveStateToStorage();
    renderAuditPlanTable();
    closeModal('officePlanModal');
    alert('Rencana Program Audit berhasil disimpan!');
}

function deleteOfficePlan(id) {
    if (confirm('Hapus Rencana Audit Plan ini?')) {
        auditState.plans = auditState.plans.filter(p => String(p.id) !== String(id));
        saveStateToStorage();
        renderAuditPlanTable();
    }
}

function openOfficeCarModal() {
    const m = document.getElementById('officeCarModal');
    if (m) {
        document.getElementById('inputCarNo').value = 'CAR-0' + (auditState.cars.length + 1) + '-' + new Date().toISOString().slice(5, 7) + '-' + new Date().getFullYear();
        m.classList.add('active');
        m.style.display = 'flex';
    }
}

function saveOfficeCar(e) {
    e.preventDefault();
    const car = {
        id: 'CAR-' + Date.now(),
        noCar: document.getElementById('inputCarNo').value.trim(),
        unit: document.getElementById('inputCarUnit').value.trim(),
        kriteria: document.getElementById('inputCarKriteria').value.trim(),
        klasifikasi: document.getElementById('inputCarKlasifikasi').value,
        dokumen: document.getElementById('checkCarDokumen').checked,
        wawancara: document.getElementById('checkCarWawancara').checked,
        observasi: document.getElementById('checkCarObservasi').checked,
        uraian: document.getElementById('inputCarUraian').value.trim(),
        rencana: document.getElementById('inputCarRencana').value.trim(),
        pic: document.getElementById('inputCarPic').value.trim(),
        target: document.getElementById('inputCarTarget').value,
        status: document.getElementById('inputCarStatus').value
    };
    auditState.cars.unshift(car);
    saveStateToStorage();
    renderFindingsTable();
    closeModal('officeCarModal');
    alert('Form CAR Temuan Audit berhasil diterbitkan!');
}

function toggleOfficeCarStatus(id) {
    const car = auditState.cars.find(c => String(c.id) === String(id));
    if (car) {
        car.status = car.status === 'OPEN' ? 'CLOSE' : 'OPEN';
        saveStateToStorage();
        renderFindingsTable();
    }
}

function deleteOfficeCar(id) {
    if (confirm('Hapus temuan CAR ini?')) {
        auditState.cars = auditState.cars.filter(c => String(c.id) !== String(id));
        saveStateToStorage();
        renderFindingsTable();
    }
}

function openOfficeBaModal() {
    const m = document.getElementById('officeBaModal');
    if (m) {
        document.getElementById('inputBaNo').value = 'BA-AUDIT-VYM-' + new Date().getFullYear() + '-00' + (auditState.beritaAcara.length + 1);
        document.getElementById('inputBaDate').value = new Date().toISOString().slice(0, 10);
        m.classList.add('active');
        m.style.display = 'flex';
    }
}

function saveOfficeBa(e) {
    e.preventDefault();
    const ba = {
        id: 'BA-' + Date.now(),
        noBa: document.getElementById('inputBaNo').value.trim(),
        date: document.getElementById('inputBaDate').value,
        lingkup: document.getElementById('inputBaLingkup').value.trim(),
        hasil: document.getElementById('inputBaHasil').value.trim(),
        saran: document.getElementById('inputBaSaran').value.trim(),
        dibuat: document.getElementById('inputBaDibuat').value.trim(),
        diketahui: document.getElementById('inputBaDiketahui').value.trim(),
        disetujui: document.getElementById('inputBaDisetujui').value.trim()
    };
    auditState.beritaAcara.unshift(ba);
    saveStateToStorage();
    renderBeritaAcaraList();
    closeModal('officeBaModal');
    alert('Berita Acara Audit Internal berhasil diterbitkan!');
}

function deleteOfficeBa(id) {
    if (confirm('Hapus Berita Acara Audit ini?')) {
        auditState.beritaAcara = auditState.beritaAcara.filter(b => String(b.id) !== String(id));
        saveStateToStorage();
        renderBeritaAcaraList();
    }
}

function renderAuditPlanTable() {
    const tbody = document.getElementById('auditPlanTableBody');
    if (!tbody) return;
    const plans = auditState.plans || [];
    tbody.innerHTML = plans.map(p => `
        <tr>
            <td><strong>${escapeHtml(p.unit)}</strong></td>
            <td style="max-width: 250px;">${escapeHtml(p.tujuan || '-')}</td>
            <td><span class="kpi-badge badge-info">${escapeHtml(p.kriteria)}</span></td>
            <td><strong>${escapeHtml(p.auditor)}</strong></td>
            <td><div>${p.date || 'TBA'}</div><div class="emp-sub">${escapeHtml(p.time || 'Full-day')}</div></td>
            <td><strong style="font-family: monospace; color: #1d4ed8;">${escapeHtml(p.suratTugas || '-')}</strong></td>
            <td style="text-align: center;">
                <span class="status-pill ${p.status === 'completed' ? 'risk-low' : (p.status === 'in_progress' ? 'risk-medium' : 'risk-low')}">
                    ${p.status === 'completed' ? 'SELESAI' : (p.status === 'in_progress' ? 'BERJALAN' : 'TERJADWAL')}
                </span>
            </td>
            <td style="text-align: center;">
                <button class="btn btn-sm btn-danger-custom" onclick="deleteOfficePlan('${p.id}')" title="Hapus Plan"><i class="fa-solid fa-trash-can"></i></button>
            </td>
        </tr>
    `).join('') || `<tr><td colspan="8" style="text-align: center; padding: 20px;">Belum ada Rencana Audit Plan.</td></tr>`;
}

function renderFindingsTable() {
    const tbody = document.getElementById('findingsTableBody');
    if (!tbody) return;
    const cars = auditState.cars || [];
    tbody.innerHTML = cars.map(c => `
        <tr>
            <td style="font-family: monospace; font-weight: bold; color: #dc2626;">${escapeHtml(c.noCar)}</td>
            <td><strong>${escapeHtml(c.unit)}</strong><div class="emp-sub">${escapeHtml(c.kriteria)}</div></td>
            <td>
                <span class="${c.klasifikasi === 'major' ? 'badge-major' : (c.klasifikasi === 'minor' ? 'badge-minor' : 'badge-rekomendasi')}">
                    ${escapeHtml(c.klasifikasi)}
                </span>
            </td>
            <td style="font-size: 11px;">
                ${c.dokumen ? '📄Dokumen ' : ''}${c.wawancara ? '💬Wawancara ' : ''}${c.observasi ? '👁️Observasi' : ''}
            </td>
            <td style="max-width: 220px;">${escapeHtml(c.uraian)}</td>
            <td style="max-width: 220px; font-size: 11px;">${escapeHtml(c.rencana || '-')}</td>
            <td><strong>${escapeHtml(c.pic || '-')}</strong><div class="emp-sub">Target: ${c.target || '-'}</div></td>
            <td style="text-align: center;">
                <button class="${c.status === 'CLOSE' ? 'car-status-close' : 'car-status-open'}" onclick="toggleOfficeCarStatus('${c.id}')" title="Klik ubah status">
                    ${c.status}
                </button>
            </td>
            <td style="text-align: center;">
                <button class="btn btn-sm btn-danger-custom" onclick="deleteOfficeCar('${c.id}')" title="Hapus CAR"><i class="fa-solid fa-trash-can"></i></button>
            </td>
        </tr>
    `).join('') || `<tr><td colspan="9" style="text-align: center; padding: 20px;">Belum ada temuan CAR.</td></tr>`;
}

function renderBeritaAcaraList() {
    const container = document.getElementById('beritaAcaraListContainer');
    if (!container) return;
    const list = auditState.beritaAcara || [];
    container.innerHTML = list.map(b => `
        <div class="glass-card" style="padding: 20px; border-left: 4px solid #10b981;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 12px;">
                <div>
                    <span style="font-family: monospace; font-weight: bold; color: #059669; font-size: 13px;">${escapeHtml(b.noBa)}</span>
                    <h3 style="font-size: 15px; margin-top: 2px;">${escapeHtml(b.lingkup)}</h3>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 12px; color: #64748b;">Tanggal: ${b.date || '-'}</span>
                    <button class="btn btn-sm btn-danger-custom" onclick="deleteOfficeBa('${b.id}')"><i class="fa-solid fa-trash-can"></i> Hapus</button>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 12px; margin-bottom: 14px;">
                <div><strong>Hasil Sementara Audit:</strong><p style="color: #334155; margin-top: 4px;">${escapeHtml(b.hasil)}</p></div>
                <div><strong>Saran, Masukan & Arahan Penanganan:</strong><p style="color: #334155; margin-top: 4px;">${escapeHtml(b.saran || '-')}</p></div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; font-size: 11px; border-top: 1px solid #f1f5f9; padding-top: 10px; color: #475569;">
                <div><strong>Dibuat (Auditor):</strong> ${escapeHtml(b.dibuat)}</div>
                <div><strong>Diketahui (Auditee):</strong> ${escapeHtml(b.diketahui)}</div>
                <div><strong>Disetujui (Direktur):</strong> ${escapeHtml(b.disetujui)}</div>
            </div>
        </div>
    `).join('') || `<p style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada Berita Acara Audit.</p>`;
}

function getStatusBadgeHtml(status) {
    const s = String(status || 'Terverifikasi').trim();
    if (s === 'Dalam Peninjauan' || s === 'Dalam Review' || s === 'Perlu Peninjauan') {
        return `<span class="kpi-badge badge-warning" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;"><i class="fa-solid fa-clock"></i> Dalam Peninjauan</span>`;
    }
    if (s === 'Perlu Clarifikasi' || s === 'Perlu Klarifikasi') {
        return `<span class="kpi-badge badge-critical" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;"><i class="fa-solid fa-triangle-exclamation"></i> Perlu Klarifikasi</span>`;
    }
    return `<span class="kpi-badge badge-positive" style="background: #dcfce7; color: #166534; border: 1px solid #86efac; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;"><i class="fa-solid fa-circle-check"></i> ${s}</span>`;
}

function renderMainAuditTable() {
    const tbody = document.getElementById('auditTableBody');
    const countBadge = document.getElementById('tableRecordCount');
    if (!tbody) return;

    const list = auditState.filteredEmployees;
    countBadge.innerText = `${list.length} Data Audit`;

    tbody.innerHTML = list.map(e => `
        <tr>
            <td class="emp-nik">${e.nik}</td>
            <td><strong>${e.name}</strong></td>
            <td><strong>${e.dept}</strong><div class="emp-sub">${e.role}</div></td>
            <td><strong>${e.kpi}%</strong></td>
            <td><strong>${e.quality}</strong> / 5.0</td>
            <td>SOP: <strong>${e.sop}%</strong></td>
            <td><span class="status-pill risk-${e.risk.toLowerCase()}">${e.risk} Risk</span></td>
            <td>${getStatusBadgeHtml(e.status)}</td>
            <td style="white-space: nowrap;">
                <button class="btn btn-sm btn-emerald" onclick="openPrintKinerjaModal('${e.nik}', '${escapeHtml(e.name)}', '${e.id || e.pengguna_id || ''}')" title="Cetak Laporan Hasil Audit Kinerja Karyawan (LHA Kinerja) + Tanda Tangan Atasan" style="font-weight: 700; background: #059669; color: #ffffff; border: none; padding: 5px 10px; margin-right: 4px;">
                    <i class="fa-solid fa-award"></i> Cetak LHA Kinerja
                </button>
                <button class="btn btn-sm btn-secondary" onclick="openEditEmployeeModal(this)" data-id="${e.id || e.pengguna_id || ''}" data-nik="${e.nik}" data-name="${escapeHtml(e.name)}" title="Edit Divisi & Jabatan Karyawan">
                    <i class="fa-solid fa-pen-to-square text-sky"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger-custom" onclick="confirmDeleteEmployee(this)" data-id="${e.id || e.pengguna_id || ''}" data-nik="${e.nik}" data-name="${escapeHtml(e.name)}" title="Hapus Karyawan ini dari Audit" style="margin-left: 4px;">
                    <i class="fa-solid fa-trash-can"></i> Hapus
                </button>
            </td>
        </tr>
    `).join('');
}

/**
 * Render Tabel Utama TAB 3: Daftar Karyawan & Ringkasan PBOK/PPA (Masa Menjabat)
 */
function renderPbokEmployeeTable() {
    const tbody = document.getElementById('pbokEmployeeTableBody');
    if (!tbody) return;

    // Dapatkan daftar seluruh karyawan unik dari database & state (Abaikan yang telah di-blacklist & terapkan editan)
    let empList = auditState.employees.map(applyEmployeeEdits).filter(e => !isEmployeeDeleted(e));
    
    // Jika ada karyawan dari pbokList yang belum di employees, gabungkan (Hanya jika belum di-blacklist)
    auditState.pbokList.filter(p => !isPbokDeleted(p) && !isEmployeeDeleted(p)).forEach(p => {
        const empEdit = applyEmployeeEdits({ id: p.pengguna_id, nik: p.nik, name: p.name, dept: p.dept });
        const finalNik = empEdit.nik || p.nik || 'EMP-000';
        const finalName = empEdit.name || p.name || 'Karyawan';
        const finalDept = empEdit.dept || p.dept || 'Operations & Logistics';

        if (!empList.some(e => String(e.nik).toLowerCase() === String(finalNik).toLowerCase() || String(e.name).toLowerCase() === String(finalName).toLowerCase())) {
            empList.push({
                id: p.pengguna_id || p.nik || p.id,
                nik: finalNik,
                name: finalName,
                dept: finalDept,
                role: empEdit.role || 'Karyawan / Pemohon',
                tenure: p.tenure || '2024 - 2026'
            });
        }
    });

    empList = empList.map(applyEmployeeEdits).filter(e => !isEmployeeDeleted(e));

    const searchQ = (document.getElementById('searchPbok')?.value || '').toLowerCase().trim();
    const deptQ = document.getElementById('filterPbokDept')?.value || 'ALL';
    const lossQ = document.getElementById('filterLossStatus')?.value || 'ALL';

    // Filter daftar karyawan
    let filteredEmps = empList.filter(e => {
        const matchSearch = e.name.toLowerCase().includes(searchQ) || 
                            e.nik.toLowerCase().includes(searchQ) || 
                            e.dept.toLowerCase().includes(searchQ);
        const matchDept = (deptQ === 'ALL') || (e.dept === deptQ);
        return matchSearch && matchDept;
    });

    if (filteredEmps.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; color: #9ca3af; padding: 24px;">Tidak ada data karyawan atau pengajuan yang sesuai filter.</td></tr>`;
        return;
    }

    tbody.innerHTML = filteredEmps.map(e => {
        const empIdStr = String(e.id || e.pengguna_id || '');
        const empNikStr = String(e.nik || '').toLowerCase().trim();
        const empNameStr = String(e.name || '').toLowerCase().trim();

        // Ambil transaksi pengajuan belanja PBOK & PPA milik karyawan ini (Strict & Flexible ID Match)
        const userPboks = auditState.pbokList.filter(p => {
            if (isPbokDeleted(p)) return false;
            const pIdStr = String(p.pengguna_id || p.user_id || '');
            const pNikStr = String(p.nik || '').toLowerCase().trim();
            const pNameStr = String(p.name || '').toLowerCase().trim();

            const matchId = (empIdStr !== '' && pIdStr !== '' && empIdStr === pIdStr);
            const matchNik = (empNikStr !== '' && pNikStr !== '' && empNikStr === pNikStr);
            const matchName = (empNameStr !== '' && pNameStr !== '' && (empNameStr === pNameStr || empNameStr.includes(pNameStr) || pNameStr.includes(empNameStr)));

            return matchId || matchNik || matchName;
        });
        
        const totalAmount = userPboks.reduce((acc, curr) => acc + curr.amount, 0);
        const totalLoss = userPboks.reduce((acc, curr) => acc + curr.lossAmount, 0);
        const hasMissingPhysical = userPboks.some(p => p.physicalStatus !== 'Terverifikasi Ada');
        
        const tenureText = e.tenure || (userPboks[0] ? userPboks[0].tenure : '2024 - 2026 (Masa Menjabat)');

        // Filter status kerugian jika dipilih
        if (lossQ === 'Potensi Kerugian' && totalLoss === 0) return '';
        if (lossQ === 'Aman / Wajar' && totalLoss > 0) return '';

        return `
            <tr>
                <td class="emp-nik">${e.nik}</td>
                <td>
                    <a href="javascript:void(0)" onclick="openEmployeeTenureModal('${e.nik}', '${escapeHtml(e.name)}', '${e.id || e.pengguna_id || ''}')" style="color: #1d4ed8; font-weight: 800; text-decoration: none;">
                        <i class="fa-solid fa-user-tie"></i> ${e.name}
                    </a>
                </td>
                <td><strong>${e.dept}</strong><div class="emp-sub">${e.role || 'Staff'}</div></td>
                <td><span class="emp-sub"><i class="fa-solid fa-calendar-days"></i> ${tenureText}</span></td>
                <td><strong style="color: #0284c7; font-size: 14px;">${userPboks.length} Transaksi</strong></td>
                <td><span class="currency-format">${ExcelAuditEngine.formatRupiah(totalAmount)}</span></td>
                <td>
                    ${hasMissingPhysical 
                        ? `<span class="status-pill physical-missing"><i class="fa-solid fa-triangle-exclamation"></i> Ada Selisih Fisik</span>`
                        : `<span class="status-pill physical-ok"><i class="fa-solid fa-circle-check"></i> Terverifikasi Ada</span>`
                    }
                </td>
                <td>
                    ${totalLoss > 0 
                        ? `<span class="currency-loss"><i class="fa-solid fa-triangle-exclamation"></i> ${ExcelAuditEngine.formatRupiah(totalLoss)}</span>`
                        : `<span class="kpi-badge badge-positive">Aman / Wajar</span>`
                    }
                </td>
                <td style="text-align: center; white-space: nowrap;">
                    <button class="btn btn-sm btn-primary" onclick="openPrintAuditModal('${e.nik}', '${escapeHtml(e.name)}', '${e.id || e.pengguna_id || ''}')" title="Cetak Laporan Hasil Audit Belanja (LHA Belanja) + Tanda Tangan Atasan" style="font-weight: 700;">
                        <i class="fa-solid fa-print"></i> Cetak LHA Belanja
                    </button>
                    <button class="btn btn-sm btn-amber" onclick="openEmployeeTenureModal('${e.nik}', '${escapeHtml(e.name)}', '${e.id || e.pengguna_id || ''}')" title="Edit Status Fisik Barang & Analisis Kerugian PBOK/PPA" style="background: #f59e0b; color: #ffffff; border: none; font-weight: 700; padding: 5px 10px; margin-left: 4px;">
                        <i class="fa-solid fa-file-pen"></i> Edit Fisik & Risk
                    </button>
                    <button class="btn btn-sm btn-secondary" onclick="openEditEmployeeModal(this)" data-id="${e.id || e.pengguna_id || ''}" data-nik="${e.nik}" data-name="${escapeHtml(e.name)}" title="Edit Divisi & Jabatan Karyawan" style="margin-left: 4px;">
                        <i class="fa-solid fa-pen-to-square text-sky"></i> Edit Divisi
                    </button>
                    <button class="btn btn-sm btn-danger-custom" onclick="confirmDeleteEmployee(this)" data-id="${e.id || e.pengguna_id || ''}" data-nik="${e.nik}" data-name="${escapeHtml(e.name)}" title="Hapus Karyawan ini dari Audit" style="margin-left: 4px;">
                        <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/* Modal Management & Add PBOK Manual */
function openAddPbokModal(empName = '', empId = '') {
    const modal = document.getElementById('pbokModal');
    if (!modal) {
        alert('Elemen modal pbokModal tidak ditemukan.');
        return;
    }

    const elNik = document.getElementById('inputPbokNik');
    const elName = document.getElementById('inputPbokName');
    const elCode = document.getElementById('inputPbokCode');
    const elTenure = document.getElementById('inputPbokTenure');

    if (elName && empName) elName.value = empName;
    if (elNik && empId) elNik.value = empId;
    if (elCode && (!elCode.value || elCode.value.trim() === '')) {
        elCode.value = 'PBOK-' + Math.floor(1000 + Math.random() * 9000);
    }
    if (elTenure && (!elTenure.value || elTenure.value.trim() === '')) {
        elTenure.value = '2024 - 2026 (Masa Menjabat)';
    }

    modal.classList.add('active');
    modal.style.display = 'flex';
}

function handleSaveManualPbok(event) {
    event.preventDefault();

    const empName = document.getElementById('manualEmpName').value.trim();
    const empId = document.getElementById('manualEmpId').value.trim();
    const type = document.getElementById('manualType').value;
    const code = document.getElementById('manualCode').value.trim();
    const amount = parseFloat(document.getElementById('manualAmount').value) || 0;
    const item = document.getElementById('manualItem').value.trim();
    const notes = document.getElementById('manualNotes').value.trim();

    if (!empName || !code || !item) {
        alert('Mohon lengkapi Nama Karyawan, Kode Surat, dan Item Belanja!');
        return;
    }

    const newPbok = {
        id: code + '-' + Date.now(),
        code: code,
        pengguna_id: empId || '771',
        nik: empId ? ('EMP-' + empId) : '1471111011990025',
        name: empName,
        dept: 'Operations & Logistics',
        tenure: '2024 - 2026 (Masa Menjabat)',
        item: item,
        amount: amount,
        physicalStatus: 'Terverifikasi Ada',
        lossStatus: 'Aman / Wajar',
        lossAmount: 0,
        notes: notes || `Pengajuan ${type} diinputkan secara manual.`
    };

    // Cari atau tambahkan karyawan di state jika belum ada
    let emp = auditState.employees.find(e => 
        (empId && String(e.id) === String(empId)) || 
        e.name.toLowerCase().trim() === empName.toLowerCase()
    );

    if (!emp) {
        emp = {
            id: empId || String(Date.now()),
            pengguna_id: empId || String(Date.now()),
            nik: empId ? ('EMP-' + empId) : '1471111011990025',
            name: empName,
            dept: 'Operations & Logistics',
            role: 'Staff Operasional',
            kpi: 92,
            quality: 4.5,
            sop: 95,
            attendance: 98,
            risk: 'Low',
            status: 'Terverifikasi',
            tenure: '2024 - 2026 (Masa Menjabat)'
        };
        auditState.employees.push(emp);
    }

    auditState.pbokList.unshift(newPbok);
    saveStateToStorage();

    closeModal('addPbokManualModal');
    renderTables();
    renderOverview();
    renderCharts();

    alert(`✅ Berhasil menambahkan pengajuan ${type} (${code}) senilai ${ExcelAuditEngine.formatRupiah(amount)} untuk ${empName}!`);

    // Jika window modal tenure karyawan ini terbuka, refresh isinya
    openEmployeeTenureModal(emp.nik, emp.name, emp.id);
}

function renderFindingsTable() {
    const tbody = document.getElementById('findingsTableBody');
    if (!tbody) return;

    tbody.innerHTML = auditState.findings.map(f => `
        <tr>
            <td class="emp-nik">${f.id}</td>
            <td><strong>${f.empName}</strong><div class="emp-sub">${f.dept}</div></td>
            <td style="max-width: 260px;"><strong style="color: #f3f4f6;">${f.finding}</strong><div style="font-size: 11px; color: #9ca3af;">${f.rootCause}</div></td>
            <td><span class="status-pill risk-${f.severity.toLowerCase()}">${f.severity}</span></td>
            <td style="max-width: 240px; font-size: 12px; color: #34d399;"><i class="fa-solid fa-shield-heart"></i> ${f.actionPlan}</td>
            <td><strong>${f.pic}</strong><div class="emp-sub">${f.deadline}</div></td>
            <td><span class="kpi-badge badge-warning">${f.status}</span></td>
        </tr>
    `).join('');
}

function getPhysicalBadgeClass(status) {
    if (status === 'Terverifikasi Ada') return 'physical-ok';
    if (status === 'Fisik Tidak Ada') return 'physical-missing';
    return 'physical-fake';
}

/* Setup Filters */
function setupFilters() {
    // PBOK Filters
    const searchPbok = document.getElementById('searchPbok');
    const filterPhysical = document.getElementById('filterPhysicalStatus');
    const filterLoss = document.getElementById('filterLossStatus');

    function applyPbokFilters() {
        const q = searchPbok.value.toLowerCase().trim();
        const phys = filterPhysical.value;
        const loss = filterLoss.value;

        auditState.filteredPbok = auditState.pbokList.filter(p => {
            const matchQuery = p.code.toLowerCase().includes(q) ||
                               p.name.toLowerCase().includes(q) ||
                               p.nik.toLowerCase().includes(q) ||
                               p.item.toLowerCase().includes(q);
            const matchPhys = (phys === 'ALL') || (p.physicalStatus === phys);
            const matchLoss = (loss === 'ALL') || 
                              (loss === 'Aman / Wajar' && p.lossAmount === 0) ||
                              (loss === 'Potensi Kerugian' && p.lossAmount > 0);

            return matchQuery && matchPhys && matchLoss;
        });

        renderPbokTable();
    }

    if (searchPbok) searchPbok.addEventListener('input', applyPbokFilters);
    if (filterPhysical) filterPhysical.addEventListener('change', applyPbokFilters);
    if (filterLoss) filterLoss.addEventListener('change', applyPbokFilters);
}

/* Open Employee Tenure Window Modal */
let activeTenureEmployee = null;
let activePrintEmployee = null;
let activePrintKinerjaEmployee = null;

function openEmployeeTenureModal(nik, name, empId) {
    const modal = document.getElementById('employeeTenureModal');
    if (!modal) return;

    const emp = applyEmployeeEdits(
        auditState.employees.find(e => (empId && String(e.id) === String(empId)) || e.nik === nik || e.name.toLowerCase() === name.toLowerCase()) || {
            id: empId || nik || 'EMP-000',
            nik: nik || 'EMP-000',
            name: name,
            dept: 'Operations & Logistics',
            tenure: '2024 - 2026 (Masa Menjabat)'
        }
    );

    activeTenureEmployee = emp;

    // Ambil transaksi pengajuan PBOK & PPA milik karyawan ini
    const userPboks = auditState.pbokList.filter(p => {
        const matchId = (p.pengguna_id && emp.id && String(p.pengguna_id) === String(emp.id));
        const matchNik = p.nik && emp.nik && p.nik.toLowerCase() === emp.nik.toLowerCase();
        const matchName = p.name && emp.name && (
            p.name.toLowerCase().trim() === emp.name.toLowerCase().trim() ||
            emp.name.toLowerCase().includes(p.name.toLowerCase()) ||
            p.name.toLowerCase().includes(emp.name.toLowerCase())
        );
        return matchId || matchNik || matchName;
    });

    const totalPbok = userPboks.filter(p => (p.code || '').startsWith('PBOK')).reduce((acc, curr) => acc + curr.amount, 0);
    const totalPpa = userPboks.filter(p => (p.code || '').startsWith('PPA')).reduce((acc, curr) => acc + curr.amount, 0);
    const totalLoss = userPboks.reduce((acc, curr) => acc + curr.lossAmount, 0);

    // Update Header Modal
    document.getElementById('tenureModalEmployeeName').innerText = `Audit Pengajuan Belanja: ${emp.name}`;
    document.getElementById('tenureModalEmployeeSub').innerText = `NIK: ${emp.nik} | Divisi: ${emp.dept} | Masa Menjabat: ${emp.tenure || '2024 - 2026'}`;

    document.getElementById('tenureModalPbokTotal').innerText = ExcelAuditEngine.formatRupiah(totalPbok);
    document.getElementById('tenureModalPpaTotal').innerText = ExcelAuditEngine.formatRupiah(totalPpa);
    document.getElementById('tenureModalLossTotal').innerText = ExcelAuditEngine.formatRupiah(totalLoss);

    // Render Tabel Transaksi
    const tbody = document.getElementById('tenureModalTableBody');
    if (userPboks.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; color: #9ca3af; padding: 20px;">Belum ada catatan pengajuan belanja PBOK atau PPA untuk karyawan ini.</td></tr>`;
    } else {
        tbody.innerHTML = userPboks.map(p => {
            const rawId = (p.code || '').replace(/[^0-9]/g, '') || '2371';
            const officeLink = p.officeUrl || ((p.notes && p.notes.includes('http')) ? p.notes.match(/https?:\/\/[^\s]+/)?.[0] : '');

            const physText = p.taxVerified ? 'Terverifikasi Ada' : (p.lossAmount > 0 ? 'Fisik Tidak Ada' : 'Belum Diverifikasi');
            const badgeClass = p.taxVerified ? 'physical-ok' : (p.lossAmount > 0 ? 'physical-fake' : 'physical-missing');

            return `
            <tr>
                <td class="emp-nik">${p.code}</td>
                <td><span class="badge-modern badge-level" style="background: #e0f2fe; color: #0284c7;">${(p.code || '').startsWith('PPA') ? 'PPA (Aset)' : 'PBOK (OPEX)'}</span></td>
                <td><strong style="color: #0f172a;">${p.item}</strong></td>
                <td><span class="currency-format">${ExcelAuditEngine.formatRupiah(p.amount)}</span></td>
                <td>
                    ${officeLink ? `
                        <a href="${officeLink}" target="_blank" class="btn btn-sm btn-primary" style="font-size: 11px; padding: 4px 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; border-radius: 6px; font-weight: 700;">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Link Office
                        </a>
                    ` : `<span style="color: #94a3b8; font-style: italic; font-size: 11px;">-</span>`}
                </td>
                <td><span class="status-pill ${badgeClass}">${physText}</span></td>
                <td>
                    ${p.lossAmount > 0 
                        ? `<span class="currency-loss"><i class="fa-solid fa-triangle-exclamation"></i> ${ExcelAuditEngine.formatRupiah(p.lossAmount)}</span>`
                        : `<span class="kpi-badge badge-positive">Aman / Wajar</span>`
                    }
                </td>
                <td style="white-space: nowrap;">
                    ${p.taxVerified ? `
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <span class="badge-modern" style="background: #dcfce7; color: #15803d; font-weight: 700; padding: 4px 8px; border-radius: 6px; font-size: 10px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i> Diverifikasi (ID 107)
                            </span>
                            <button class="btn btn-sm btn-secondary" onclick="toggleTaxVerification('${p.id}')" title="Batalkan Verifikasi ID 107" style="font-size: 9px; padding: 2px 6px;">
                                <i class="fa-solid fa-rotate-left"></i> Batal Verifikasi
                            </button>
                        </div>
                    ` : `
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <button class="btn btn-sm btn-emerald" onclick="toggleTaxVerification('${p.id}')" style="font-weight: 700; font-size: 10px; padding: 5px 8px; white-space: nowrap; display: inline-flex; align-items: center; gap: 4px;" title="Verifikasi Pengajuan Ini sebagai Head of Tax & Acc (ID 107)">
                                <i class="fa-solid fa-check-double"></i> Verifikasi (Tax & Acc ID 107)
                            </button>
                            <span style="font-size: 9.5px; color: #94a3b8; font-style: italic;">⏳ Menunggu Verifikasi</span>
                        </div>
                    `}
                </td>
                <td style="font-size: 11px; color: #64748b;">${p.notes}</td>
                <td style="text-align: center; white-space: nowrap;">
                    <button class="btn btn-sm btn-secondary" onclick="openEditPbokModal('${p.id}')" title="Edit Status Fisik Barang & Analisis Kerugian" style="margin-right: 4px;">
                        <i class="fa-solid fa-pen-to-square text-sky"></i> Edit Status
                    </button>
                    <button class="btn btn-sm btn-danger-custom" onclick="confirmDeletePbok(this)" data-id="${p.id}" data-code="${p.code}" title="Hapus Transaksi Ini">
                        <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </td>
            </tr>
            `;
        }).join('');
    }

    modal.classList.add('active');
}

/* Modals setup */
function setupModals() {
    // Tenure Modal Listeners
    const tenureModal = document.getElementById('employeeTenureModal');
    const btnCloseTenure = document.getElementById('btnCloseTenureModal');
    const btnCancelTenure = document.getElementById('btnCancelTenureModal');
    const btnAddNewPbok = document.getElementById('btnAddNewPbokForEmployee');
    const btnExportSingle = document.getElementById('btnExportEmployeeSingleExcel');

    function closeTenureModal() {
        activeTenureEmployee = null;
        if (tenureModal) {
            tenureModal.classList.remove('active');
            tenureModal.style.display = 'none';
        }
    }

    if (btnCloseTenure) btnCloseTenure.addEventListener('click', closeTenureModal);
    if (btnCancelTenure) btnCancelTenure.addEventListener('click', closeTenureModal);

    if (btnAddNewPbok) {
        btnAddNewPbok.addEventListener('click', () => {
            if (activeTenureEmployee) {
                openAddPbokModal(activeTenureEmployee.name, activeTenureEmployee.id || activeTenureEmployee.pengguna_id);
            } else {
                openAddPbokModal();
            }
        });
    }

    if (btnExportSingle) {
        btnExportSingle.addEventListener('click', () => {
            if (!activeTenureEmployee) return;
            const userPboks = auditState.pbokList.filter(p => p.nik === activeTenureEmployee.nik || p.name.toLowerCase() === activeTenureEmployee.name.toLowerCase());
            ExcelAuditEngine.exportFullWorkbook([activeTenureEmployee], userPboks, [], getKPISummaryObject());
        });
    }

    const btnPrintKinerja = document.getElementById('btnPrintTenureKinerjaPdf');
    const btnPrintBelanja = document.getElementById('btnPrintTenurePdf');

    if (btnPrintKinerja) {
        btnPrintKinerja.addEventListener('click', () => {
            if (!activeTenureEmployee) return;
            openPrintKinerjaModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id || activeTenureEmployee.pengguna_id);
        });
    }

    if (btnPrintBelanja) {
        btnPrintBelanja.addEventListener('click', () => {
            if (!activeTenureEmployee) return;
            openPrintAuditModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id || activeTenureEmployee.pengguna_id);
        });
    }
    // PBOK Modal
    const pbokModal = document.getElementById('pbokModal');
    const btnOpenPbok = document.getElementById('btnOpenPbokModal');
    const btnAddPbokMain = document.getElementById('btnAddPbokMain');
    const btnClosePbok = document.getElementById('btnClosePbokModal');
    const btnCancelPbok = document.getElementById('btnCancelPbokModal');
    const pbokForm = document.getElementById('pbokForm');

    function openPbokModal() {
        if (!pbokModal) return;
        const elCode = document.getElementById('inputPbokCode');
        if (elCode && (!elCode.value || elCode.value.trim() === '')) {
            elCode.value = 'PBOK-' + Math.floor(1000 + Math.random() * 9000);
        }
        pbokModal.classList.add('active');
        pbokModal.style.display = 'flex';
    }

    function closePbokModal() {
        if (!pbokModal) return;
        pbokModal.classList.remove('active');
        pbokModal.style.display = 'none';
        if (pbokForm) pbokForm.reset();
    }

    if (btnOpenPbok) btnOpenPbok.addEventListener('click', openPbokModal);
    if (btnAddPbokMain) btnAddPbokMain.addEventListener('click', () => { switchTab('pbok-ppa'); openPbokModal(); });
    if (btnClosePbok) btnClosePbok.addEventListener('click', closePbokModal);
    if (btnCancelPbok) btnCancelPbok.addEventListener('click', closePbokModal);

    if (pbokForm) {
        pbokForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const code = document.getElementById('inputPbokCode').value.trim();
            const nik = document.getElementById('inputPbokNik').value.trim();
            const name = document.getElementById('inputPbokName').value.trim();
            const dept = document.getElementById('inputPbokDept').value;
            const tenure = document.getElementById('inputPbokTenure').value.trim();
            const amount = parseFloat(document.getElementById('inputPbokAmount').value);
            const item = document.getElementById('inputPbokItem').value.trim();
            const physicalStatus = document.getElementById('inputPbokPhysical').value;
            const lossStatus = document.getElementById('inputPbokLossStatus').value;
            const lossAmount = parseFloat(document.getElementById('inputPbokLossAmount').value || 0);
            const officeUrl = document.getElementById('inputPbokOfficeUrl')?.value.trim();
            const notes = document.getElementById('inputPbokNotes').value.trim();

            auditState.pbokList.unshift({
                id: Date.now(),
                code, nik, name, dept, tenure, item, amount, physicalStatus, lossStatus, lossAmount, notes, officeUrl
            });

            auditState.filteredPbok = [...auditState.pbokList];
            saveStateToStorage();
            renderOverview();
            renderCharts();
            renderTables();
            closePbokModal();
        });
    }

    // Employee Modal
    const auditModal = document.getElementById('auditModal');
    const btnAddAudit = document.getElementById('btnAddAudit');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const btnCancelModal = document.getElementById('btnCancelModal');
    const auditForm = document.getElementById('auditForm');

    if (btnAddAudit) btnAddAudit.addEventListener('click', openAddAuditModal);
    if (btnCloseModal) btnCloseModal.addEventListener('click', closeAddAuditModal);
    if (btnCancelModal) btnCancelModal.addEventListener('click', closeAddAuditModal);

    if (auditForm) {
        auditForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const nik = document.getElementById('inputNik').value.trim();
            const name = document.getElementById('inputName').value.trim();
            const dept = document.getElementById('inputDept').value;
            const role = document.getElementById('inputRole').value.trim();
            const kpi = parseFloat(document.getElementById('inputKpi').value || 90);
            const quality = parseFloat(document.getElementById('inputQuality').value || 4.0);
            const sop = parseFloat(document.getElementById('inputSop').value || 95);
            const attendance = parseFloat(document.getElementById('inputAttendance').value || 95);
            const risk = document.getElementById('inputRisk').value;
            const status = document.getElementById('inputStatus').value;
            const notes = document.getElementById('inputNotes').value.trim();

            const newEmp = applyEmployeeEdits({
                id: `EMP-${Date.now()}`,
                pengguna_id: `USR-${Date.now()}`,
                nik: nik || `EMP-${Date.now()}`,
                name: name,
                dept: dept,
                role: role,
                kpi: kpi,
                quality: quality,
                sop: sop,
                attendance: attendance,
                risk: risk,
                status: status,
                notes: notes,
                tenure: '2024 - 2026 (Masa Menjabat)'
            });

            const existingIdx = auditState.employees.findIndex(e => 
                (e.nik && e.nik.toLowerCase() === newEmp.nik.toLowerCase()) || 
                (e.name && e.name.toLowerCase() === newEmp.name.toLowerCase())
            );

            if (existingIdx !== -1) {
                auditState.employees[existingIdx] = applyEmployeeEdits({ ...auditState.employees[existingIdx], ...newEmp });
            } else {
                auditState.employees.unshift(newEmp);
            }

            deduplicateEmployees();
            auditState.filteredEmployees = [...auditState.employees];
            saveStateToStorage();
            updateDepartmentFilters();
            renderOverview();
            renderCharts();
            renderTables();
            closeAddAuditModal();
            alert(`✅ Karyawan baru "${name}" berhasil ditambahkan ke database audit!`);
        });
    }
}

function openAddAuditModal() {
    const modal = document.getElementById('auditModal');
    if (!modal) {
        alert('Elemen modal auditModal tidak ditemukan.');
        return;
    }
    const inputNik = document.getElementById('inputNik');
    if (inputNik && (!inputNik.value || inputNik.value.trim() === '')) {
        inputNik.value = 'EMP-' + Math.floor(100 + Math.random() * 900);
    }
    modal.classList.add('active');
    modal.style.display = 'flex';
}

function closeAddAuditModal() {
    const modal = document.getElementById('auditModal');
    if (!modal) return;
    modal.classList.remove('active');
    modal.style.display = 'none';
    const auditForm = document.getElementById('auditForm');
    if (auditForm) auditForm.reset();
}

function deletePbok(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data audit PBOK/PPA ini?')) {
        auditState.pbokList = auditState.pbokList.filter(p => String(p.id) != String(id));
        auditState.filteredPbok = [...auditState.pbokList];
        saveStateToStorage();
        renderOverview();
        renderCharts();
        renderTables();
    }
}

function deleteEmployee(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data audit karyawan ini?')) {
        auditState.employees = auditState.employees.filter(e => String(e.id) != String(id));
        auditState.filteredEmployees = [...auditState.employees];
        saveStateToStorage();
        renderOverview();
        renderCharts();
        renderTables();
    }
}

/* Excel Import */
function setupExcelImport() {
    const btnImport = document.getElementById('btnImportExcel');
    const fileInput = document.getElementById('excelFileInput');

    if (btnImport && fileInput) {
        btnImport.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(evt) {
                try {
                    const data = new Uint8Array(evt.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const sheetName = workbook.SheetNames[0];
                    const json = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);

                    if (json.length === 0) return alert('File Excel kosong.');

                    json.forEach((row, i) => {
                        if (row['Kode Pengajuan'] || row['Kode PBOK']) {
                            auditState.pbokList.unshift({
                                id: Date.now() + i,
                                code: row['Kode Pengajuan'] || `PBOK-IMP-${i}`,
                                nik: row['NIK Pemohon'] || 'EMP-IMP',
                                name: row['Nama Pemohon'] || 'Pemohon Import',
                                dept: row['Divisi'] || 'Operations & Logistics',
                                tenure: row['Masa Menjabat'] || '2024 - 2026',
                                item: row['Item Belanja & Vendor'] || row['Deskripsi'] || 'Pengadaan Belanja Office',
                                amount: parseFloat(row['Nilai Pengajuan (Rp)'] || 10000000),
                                physicalStatus: row['Keberadaan Fisik'] || 'Terverifikasi Ada',
                                lossStatus: row['Status Kerugian'] || 'Aman / Wajar',
                                lossAmount: parseFloat(row['Nilai Potensi Kerugian (Rp)'] || 0),
                                notes: row['Catatan Auditor'] || 'Imported via Excel'
                            });
                        }
                    });

                    auditState.filteredPbok = [...auditState.pbokList];
                    renderOverview();
                    renderCharts();
                    renderTables();
                    alert('Impor data Excel berhasil!');
                } catch(err) {
                    console.error(err);
                    alert('Gagal membaca file Excel.');
                }
            };
            reader.readAsArrayBuffer(file);
        });
    }
}

/* Export All Trigger */
function handleExportAll() {
    const kpiSummary = getKPISummaryObject();
    ExcelAuditEngine.exportFullWorkbook(auditState.employees, auditState.pbokList, auditState.findings, kpiSummary);
}

/* Helper Modal Manager */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.add('active');
    modal.style.display = 'flex';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.remove('active');
    modal.style.display = 'none';
    if (modalId === 'printAuditModal') activePrintEmployee = null;
    if (modalId === 'printAuditKinerjaModal') activePrintKinerjaEmployee = null;
}

/* Edit Data Karyawan (Divisi & Jabatan) - 100% Fail-Proof Engine */
function openEditEmployeeModal(btnOrId) {
    let empId = '';
    let empNik = '';
    let empName = '';

    if (typeof btnOrId === 'object' && btnOrId !== null) {
        empId = (btnOrId.getAttribute('data-id') || '').trim();
        empNik = (btnOrId.getAttribute('data-nik') || '').trim();
        empName = (btnOrId.getAttribute('data-name') || '').trim();
    } else {
        empId = String(btnOrId || '').trim();
    }

    // 1. Cari karyawan di auditState.employees
    let emp = auditState.employees.find(e => 
        (empId && (String(e.id) === empId || String(e.pengguna_id) === empId)) ||
        (empNik && String(e.nik).toLowerCase() === empNik.toLowerCase()) ||
        (empName && String(e.name).toLowerCase() === empName.toLowerCase()) ||
        (empId && (String(e.nik).toLowerCase() === empId.toLowerCase() || String(e.name).toLowerCase() === empId.toLowerCase()))
    );

    // 2. Jika belum ada di list karyawan (misal dari pbok), buat objek sementara
    if (!emp && (empName || empNik || empId)) {
        emp = {
            id: empId || Date.now(),
            pengguna_id: empId || Date.now(),
            nik: empNik || 'EMP-000',
            name: empName || 'Karyawan',
            dept: 'Operations & Logistics',
            role: 'Staff Operasional',
            risk: 'Low',
            status: 'Terverifikasi'
        };
        auditState.employees.push(emp);
    }

    if (!emp) {
        alert('Data karyawan tidak ditemukan.');
        return;
    }

    const modal = document.getElementById('editEmployeeModal');
    if (!modal) {
        alert('Elemen modal editEmployeeModal tidak ditemukan.');
        return;
    }

    const elId = document.getElementById('editEmpId');
    const elNik = document.getElementById('editEmpNik');
    const elName = document.getElementById('editEmpName');
    const elRole = document.getElementById('editEmpRole');
    const elTenure = document.getElementById('editEmpTenure');
    const elRisk = document.getElementById('editEmpRisk');
    const elStatus = document.getElementById('editEmpStatus');

    if (elId) elId.value = emp.id || emp.pengguna_id || emp.nik;
    if (elNik) elNik.value = emp.nik || '';
    if (elName) elName.value = emp.name || '';
    if (elRole) elRole.value = emp.role || 'Staff Operasional';
    if (elTenure) elTenure.value = emp.tenure || '2024 - 2026 (Masa Menjabat)';
    if (elRisk) elRisk.value = emp.risk || 'Low';
    if (elStatus) elStatus.value = emp.status || 'Terverifikasi';

    const userPboks = auditState.pbokList.filter(p => {
        const matchId = (p.pengguna_id && emp.id && String(p.pengguna_id) === String(emp.id));
        const matchNik = p.nik && emp.nik && String(p.nik).toLowerCase() === String(emp.nik).toLowerCase();
        const matchName = p.name && emp.name && String(p.name).toLowerCase().trim() === String(emp.name).toLowerCase().trim();
        return matchId || matchNik || matchName;
    });

    const currentPhysical = userPboks[0]?.physicalStatus || 'Terverifikasi Ada';
    const currentLoss = userPboks.reduce((acc, curr) => acc + (curr.lossAmount || 0), 0);

    const elPhysical = document.getElementById('editEmpPhysical');
    const elLoss = document.getElementById('editEmpLossAmount');
    if (elPhysical) elPhysical.value = currentPhysical;
    if (elLoss) elLoss.value = currentLoss;

    const deptSelect = document.getElementById('editEmpDept');
    if (deptSelect) {
        let hasOpt = false;
        for (let opt of deptSelect.options) {
            if (opt.value === emp.dept) {
                opt.selected = true;
                hasOpt = true;
                break;
            }
        }
        if (!hasOpt && emp.dept) {
            const newOpt = document.createElement('option');
            newOpt.value = emp.dept;
            newOpt.text = emp.dept;
            newOpt.selected = true;
            deptSelect.appendChild(newOpt);
        }
    }

    modal.classList.add('active');
    modal.style.display = 'flex';
}

function toggleCustomDeptInput(select) {
    const customInput = document.getElementById('editEmpDeptCustom');
    if (!customInput) return;
    if (select.value === 'NEW_CUSTOM_DEPT') {
        customInput.style.display = 'block';
        customInput.focus();
    } else {
        customInput.style.display = 'none';
    }
}

function updateDepartmentFilters() {
    const depts = new Set([
        'Operations & Logistics',
        'Technical & Engineering',
        'Accounting & Finance',
        'General Affair & Admin',
        'Quality Assurance & Control',
        'Corporate Planning',
        'Marketing & Brand',
        'Visilab Operations'
    ]);

    auditState.employees.forEach(e => { if (e.dept) depts.add(e.dept); });
    auditState.pbokList.forEach(p => { if (p.dept) depts.add(p.dept); });

    const filterSelects = [document.getElementById('filterPbokDept'), document.getElementById('filterDept')];
    filterSelects.forEach(select => {
        if (!select) return;
        const currentVal = select.value;
        select.innerHTML = '<option value="ALL">Seluruh Divisi</option>';
        Array.from(depts).sort().forEach(d => {
            const opt = document.createElement('option');
            opt.value = d;
            opt.text = d;
            if (d === currentVal) opt.selected = true;
            select.appendChild(opt);
        });
    });
}

function deduplicateEmployees() {
    const map = new Map();
    auditState.employees.forEach(emp => {
        if (!emp) return;
        const empId = String(emp.id || emp.pengguna_id || '').toLowerCase().trim();
        const empNik = String(emp.nik || '').toLowerCase().trim();
        const empName = String(emp.name || emp.nama || '').toLowerCase().trim();

        const isTengku = empId === '58' || empNik === 'emp-058' || empName.includes('tengku');
        const key = isTengku ? '58' : (empId || empNik || empName);

        if (map.has(key)) {
            const existing = map.get(key);
            map.set(key, {
                ...existing,
                ...emp,
                id: isTengku ? '58' : (existing.id || emp.id),
                nik: isTengku ? 'EMP-058' : (existing.nik || emp.nik),
                name: isTengku ? 'Tengku Muhammad Zainul Aprilizar' : (existing.name || emp.name),
                tenure: emp.tenure || existing.tenure || '2024 - 2026 (Masa Menjabat)'
            });
        } else {
            map.set(key, emp);
        }
    });
    auditState.employees = Array.from(map.values());
}

function saveEditEmployee(e) {
    if (e) e.preventDefault();

    const empId = document.getElementById('editEmpId')?.value;
    const newNik = document.getElementById('editEmpNik')?.value.trim();
    const newName = document.getElementById('editEmpName')?.value.trim();
    let newDept = document.getElementById('editEmpDept')?.value;
    const customDeptInput = document.getElementById('editEmpDeptCustom');

    if (newDept === 'NEW_CUSTOM_DEPT') {
        const customVal = customDeptInput ? customDeptInput.value.trim() : '';
        if (!customVal) {
            alert('Silakan ketikkan nama divisi baru!');
            if (customDeptInput) customDeptInput.focus();
            return;
        }
        newDept = customVal;

        const deptSelect = document.getElementById('editEmpDept');
        if (deptSelect) {
            const opt = document.createElement('option');
            opt.value = newDept;
            opt.text = newDept;
            opt.selected = true;
            deptSelect.insertBefore(opt, deptSelect.lastElementChild);
        }
    }

    const newRole = document.getElementById('editEmpRole')?.value.trim();
    const newTenure = document.getElementById('editEmpTenure')?.value.trim() || '2024 - 2026 (Masa Menjabat)';
    const newRisk = document.getElementById('editEmpRisk')?.value;
    const newStatus = document.getElementById('editEmpStatus')?.value;

    let empIndex = auditState.employees.findIndex(emp => 
        String(emp.id) === String(empId) || 
        String(emp.pengguna_id) === String(empId) || 
        String(emp.nik).toLowerCase() === String(newNik).toLowerCase() ||
        String(emp.name).toLowerCase() === String(newName).toLowerCase()
    );

    if (empIndex === -1) {
        auditState.employees.push({
            id: empId || Date.now(),
            pengguna_id: empId || Date.now(),
            nik: newNik,
            name: newName,
            dept: newDept,
            role: newRole,
            tenure: newTenure,
            kpi: 90,
            quality: 4.0,
            sop: 95,
            attendance: 95,
            risk: newRisk,
            status: newStatus
        });
        empIndex = auditState.employees.length - 1;
    }

    const oldEmp = auditState.employees[empIndex];
    const oldNik = oldEmp.nik;
    const oldName = oldEmp.name;

    auditState.employees[empIndex] = {
        ...oldEmp,
        nik: newNik,
        name: newName,
        dept: newDept,
        role: newRole,
        tenure: newTenure,
        risk: newRisk,
        status: newStatus
    };

    auditState.pbokList.forEach(p => {
        const matchId = (p.pengguna_id && empId && String(p.pengguna_id) === String(empId));
        const matchNik = (p.nik && (String(p.nik).toLowerCase() === newNik.toLowerCase() || String(p.nik).toLowerCase() === String(oldNik).toLowerCase()));
        const matchName = (p.name && (String(p.name).toLowerCase() === newName.toLowerCase() || String(p.name).toLowerCase() === String(oldName).toLowerCase()));
        if (matchId || matchNik || matchName) {
            p.nik = newNik;
            p.name = newName;
            p.dept = newDept;
            p.tenure = newTenure;
        }
    });

    deduplicateEmployees();

    const editData = {
        nik: newNik,
        name: newName,
        dept: newDept,
        role: newRole,
        tenure: newTenure,
        risk: newRisk,
        status: newStatus
    };

    if (empId) editedEmployees[String(empId).toLowerCase().trim()] = editData;
    if (newNik) editedEmployees[newNik.toLowerCase()] = editData;
    if (newName) editedEmployees[newName.toLowerCase()] = editData;
    if (oldNik) editedEmployees[oldNik.toLowerCase()] = editData;
    if (oldName) editedEmployees[oldName.toLowerCase()] = editData;

    localStorage.setItem('edited_employees', JSON.stringify(editedEmployees));

    const newPhysical = document.getElementById('editEmpPhysical')?.value;
    const newLossAmount = parseFloat(document.getElementById('editEmpLossAmount')?.value || 0);

    // Cascade update ke pbokList dengan pencocokan Multi-Identifier
    auditState.pbokList.forEach(p => {
        const pIdStr = String(p.pengguna_id || p.user_id || '').toLowerCase().trim();
        const pNikStr = String(p.nik || '').toLowerCase().trim();
        const pNameStr = String(p.name || '').toLowerCase().trim();

        const matchId = empId && pIdStr && pIdStr === String(empId).toLowerCase().trim();
        const matchNik = (oldNik && pNikStr === String(oldNik).toLowerCase().trim()) || (newNik && pNikStr === String(newNik).toLowerCase().trim());
        const matchName = (oldName && (pNameStr === String(oldName).toLowerCase().trim() || pNameStr.includes(String(oldName).toLowerCase().trim()))) ||
                          (newName && (pNameStr === String(newName).toLowerCase().trim() || pNameStr.includes(String(newName).toLowerCase().trim())));

        if (matchId || matchNik || matchName) {
            p.nik = newNik;
            p.name = newName;
            p.dept = newDept;
            if (newPhysical) p.physicalStatus = newPhysical;
            p.lossAmount = newLossAmount;
            p.lossStatus = (newLossAmount > 0) ? 'Potensi Kerugian' : 'Aman / Wajar';
        }
    });

    auditState.filteredEmployees = [...auditState.employees];
    auditState.filteredPbok = [...auditState.pbokList];

    saveStateToStorage();
    updateDepartmentFilters();
    closeModal('editEmployeeModal');

    if (customDeptInput) {
        customDeptInput.value = '';
        customDeptInput.style.display = 'none';
    }

    renderOverview();
    renderCharts();
    renderTables();

    alert(`✅ Berhasil memperbarui Karyawan "${newName}"!\nDivisi: ${newDept}\nJabatan: ${newRole}`);
}

/* Toggle Status Verifikasi Head Tax & Accounting (ID 107 - Dirangga Madali) */
function toggleTaxVerification(pbokId) {
    if (!pbokId) return;

    const pbokIndex = auditState.pbokList.findIndex(p => String(p.id) === String(pbokId) || String(p.code) === String(pbokId));
    if (pbokIndex === -1) return;

    const pbok = auditState.pbokList[pbokIndex];
    pbok.taxVerified = !pbok.taxVerified;

    if (pbok.taxVerified) {
        pbok.taxVerifiedBy = 'Dirangga Madali (Head Tax & Acc - ID 107)';
        pbok.taxVerifiedDate = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
    } else {
        pbok.taxVerifiedBy = null;
        pbok.taxVerifiedDate = null;
    }

    savePbokEditRecord(pbok);
    saveStateToStorage();
    renderOverview();
    renderCharts();
    renderTables();

    const tenureModalEl = document.getElementById('employeeTenureModal');
    if (activeTenureEmployee && tenureModalEl && (tenureModalEl.classList.contains('active') || tenureModalEl.style.display !== 'none')) {
        openEmployeeTenureModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id);
    }
}

/* Membatalkan SELURUH Status Verifikasi Head of Tax & Accounting (Massal 1-Klik) */
function unverifyAllTaxVerifications() {
    if (!confirm('⚠️ KONFIRMASI BATALKAN SELURUH VERIFIKASI:\n\nApakah Anda yakin ingin MEMBATALKAN SELURUH status verifikasi Head of Tax & Accounting (ID 107 - Dirangga Madali) untuk SELURUH dokumen pengajuan belanja di kantor?\n\nSeluruh transaksi akan dikembalikan ke status "Belum Diverifikasi".')) {
        return;
    }

    let updatedCount = 0;
    auditState.pbokList.forEach(pbok => {
        if (pbok.taxVerified) {
            pbok.taxVerified = false;
            pbok.taxVerifiedBy = null;
            pbok.taxVerifiedDate = null;
            savePbokEditRecord(pbok);
            updatedCount++;
        }
    });

    auditState.filteredPbok = [...auditState.pbokList];
    saveStateToStorage();
    renderOverview();
    renderCharts();
    renderTables();

    const elTaxVer = document.getElementById('editPbokTaxVerified');
    if (elTaxVer) elTaxVer.value = 'FALSE';

    const tenureModalEl = document.getElementById('employeeTenureModal');
    if (activeTenureEmployee && tenureModalEl && (tenureModalEl.classList.contains('active') || tenureModalEl.style.display !== 'none')) {
        openEmployeeTenureModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id);
    }

    alert(`✅ Berhasil membatalkan status verifikasi pada ${updatedCount} dokumen pengajuan!\nSeluruh verifikasi kini dikembalikan ke status "Belum Diverifikasi".`);
}

/* Verifikasi SELURUH Pengajuan Kantor (Massal 1-Klik) */
function verifyAllTaxVerifications() {
    if (!confirm('✅ KONFIRMASI VERIFIKASI MASSAL:\n\nApakah Anda yakin ingin DIVERIFIKASI AMAN SELURUH dokumen pengajuan belanja oleh Head of Tax & Accounting (ID 107 - Dirangga Madali)?')) {
        return;
    }

    const dateToday = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
    let updatedCount = 0;

    auditState.pbokList.forEach(pbok => {
        if (!pbok.taxVerified) {
            pbok.taxVerified = true;
            pbok.taxVerifiedBy = 'Dirangga Madali (Head Tax & Acc - ID 107)';
            pbok.taxVerifiedDate = dateToday;
            savePbokEditRecord(pbok);
            updatedCount++;
        }
    });

    auditState.filteredPbok = [...auditState.pbokList];
    saveStateToStorage();
    renderOverview();
    renderCharts();
    renderTables();

    const elTaxVer = document.getElementById('editPbokTaxVerified');
    if (elTaxVer) elTaxVer.value = 'TRUE';

    const tenureModalEl = document.getElementById('employeeTenureModal');
    if (activeTenureEmployee && tenureModalEl && (tenureModalEl.classList.contains('active') || tenureModalEl.style.display !== 'none')) {
        openEmployeeTenureModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id);
    }

    alert(`✅ Berhasil memverifikasi ${updatedCount} dokumen pengajuan belanja!\nSeluruh transaksi kini berstatus DIVERIFIKASI AMAN oleh Dirangga Madali.`);
}

/* Membatalkan Verifikasi Khusus Karyawan Aktif */
function unverifyEmployeeTaxVerifications(empNik = '', empName = '', empId = '') {
    const targetNik = empNik || (activeTenureEmployee ? activeTenureEmployee.nik : '');
    const targetName = empName || (activeTenureEmployee ? activeTenureEmployee.name : '');
    const targetId = empId || (activeTenureEmployee ? (activeTenureEmployee.id || activeTenureEmployee.pengguna_id) : '');

    const displayName = targetName || targetNik || targetId || 'Karyawan Ini';

    if (!confirm(`⚠️ KONFIRMASI BATALKAN VERIFIKASI KARYAWAN:\n\nApakah Anda yakin ingin MEMBATALKAN SELURUH verifikasi Head of Tax & Accounting (ID 107) khusus untuk pengajuan milik "${displayName}"?`)) {
        return;
    }

    let updatedCount = 0;
    auditState.pbokList.forEach(pbok => {
        const matchId = targetId && (String(pbok.pengguna_id) === String(targetId) || String(pbok.id) === String(targetId));
        const matchNik = targetNik && pbok.nik && String(pbok.nik).toLowerCase() === String(targetNik).toLowerCase();
        const matchName = targetName && pbok.name && (
            String(pbok.name).toLowerCase().trim() === String(targetName).toLowerCase().trim() ||
            String(pbok.name).toLowerCase().includes(String(targetName).toLowerCase()) ||
            String(targetName).toLowerCase().includes(String(pbok.name).toLowerCase())
        );

        if ((matchId || matchNik || matchName) && pbok.taxVerified) {
            pbok.taxVerified = false;
            pbok.taxVerifiedBy = null;
            pbok.taxVerifiedDate = null;
            savePbokEditRecord(pbok);
            updatedCount++;
        }
    });

    auditState.filteredPbok = [...auditState.pbokList];
    saveStateToStorage();
    renderOverview();
    renderCharts();
    renderTables();

    const tenureModalEl = document.getElementById('employeeTenureModal');
    if (activeTenureEmployee && tenureModalEl && (tenureModalEl.classList.contains('active') || tenureModalEl.style.display !== 'none')) {
        openEmployeeTenureModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id);
    }

    alert(`✅ Berhasil membatalkan status verifikasi pada ${updatedCount} dokumen milik ${displayName}.`);
}

/* Edit Status Fisik Barang & Analisis Kerugian PBOK/PPA */
function openEditPbokModal(pbokId) {
    if (!pbokId) return;

    const pbok = auditState.pbokList.find(p => String(p.id) === String(pbokId) || String(p.code) === String(pbokId));
    if (!pbok) {
        alert('Data pengajuan belanja tidak ditemukan.');
        return;
    }

    document.getElementById('editPbokId').value = pbok.id || pbok.code;
    document.getElementById('editPbokCodeTitle').innerText = pbok.code || 'PBOK';
    document.getElementById('editPbokItem').value = pbok.item || '';
    document.getElementById('editPbokAmount').value = pbok.amount || 0;
    document.getElementById('editPbokLossAmount').value = pbok.lossAmount || 0;
    document.getElementById('editPbokNotes').value = pbok.notes || '';
    
    const elTaxVer = document.getElementById('editPbokTaxVerified');
    if (elTaxVer) elTaxVer.value = pbok.taxVerified ? 'TRUE' : 'FALSE';

    const elUrl = document.getElementById('editPbokOfficeUrl');
    if (elUrl) elUrl.value = pbok.officeUrl || (pbok.notes && pbok.notes.includes('http') ? pbok.notes.match(/https?:\/\/[^\s]+/)?.[0] || '' : '');

    openModal('editPbokModal');
}

function saveEditPbok(e) {
    if (e) e.preventDefault();

    const pbokId = document.getElementById('editPbokId')?.value;
    const newItem = document.getElementById('editPbokItem')?.value.trim();
    const newAmount = parseFloat(document.getElementById('editPbokAmount')?.value || 0);
    const newLossAmount = parseFloat(document.getElementById('editPbokLossAmount')?.value || 0);
    const newTaxVerified = (document.getElementById('editPbokTaxVerified')?.value === 'TRUE');
    const newOfficeUrl = document.getElementById('editPbokOfficeUrl')?.value.trim() || '';
    const newNotes = document.getElementById('editPbokNotes')?.value.trim();

    const pbokIndex = auditState.pbokList.findIndex(p => String(p.id) === String(pbokId) || String(p.code) === String(pbokId));
    if (pbokIndex !== -1) {
        const oldPbok = auditState.pbokList[pbokIndex];
        const newLossStatus = (newLossAmount > 0) ? 'Potensi Kerugian' : 'Aman / Wajar';
        const dateToday = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });

        const newPhysical = newTaxVerified ? 'Terverifikasi Ada' : (newLossAmount > 0 ? 'Fisik Tidak Ada' : 'Belum Diverifikasi');

        auditState.pbokList[pbokIndex] = {
            ...oldPbok,
            item: newItem,
            amount: newAmount,
            physicalStatus: newPhysical,
            lossAmount: newLossAmount,
            lossStatus: newLossStatus,
            taxVerified: newTaxVerified,
            taxVerifiedBy: newTaxVerified ? 'Dirangga Madali (Head Tax & Acc - ID 107)' : null,
            taxVerifiedDate: newTaxVerified ? dateToday : null,
            notes: newNotes,
            officeUrl: newOfficeUrl
        };

        savePbokEditRecord(auditState.pbokList[pbokIndex]);

        auditState.filteredPbok = [...auditState.pbokList];

        saveStateToStorage();
        closeModal('editPbokModal');

        renderOverview();
        renderCharts();
        renderTables();

        const tenureModalEl = document.getElementById('employeeTenureModal');
        if (activeTenureEmployee && tenureModalEl && tenureModalEl.classList.contains('active')) {
            openEmployeeTenureModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id);
        }

        const printModalEl = document.getElementById('printAuditModal');
        if (printModalEl && printModalEl.classList.contains('active')) {
            if (activePrintEmployee) {
                openPrintAuditModal(activePrintEmployee.nik, activePrintEmployee.name, activePrintEmployee.id);
            } else if (activeTenureEmployee) {
                openPrintAuditModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id);
            } else {
                openPrintAuditModal(oldPbok.nik || '', oldPbok.name || '', oldPbok.pengguna_id || '');
            }
        }

        alert(`✅ Berhasil memperbarui status pengajuan "${oldPbok.code}"!\nVerifikasi Tax & Acc: ${newTaxVerified ? 'DIVERIFIKASI AMAN' : 'Belum Diverifikasi'}\nStatus Fisik: ${newPhysical}`);
    }
}

function triggerPrintFromActiveTenure() {
    if (!activeTenureEmployee) return;
    openPrintAuditModal(activeTenureEmployee.nik, activeTenureEmployee.name, activeTenureEmployee.id || activeTenureEmployee.pengguna_id);
}

/* Cetak Dokumen Laporan Hasil Audit Internal (LHA) + Tanda Tangan Atasan */
function openPrintAuditModal(empNik = '', empName = '', empId = '') {
    const modal = document.getElementById('printAuditModal');
    if (!modal) return;

    if (!empNik && !empName && !empId) {
        if (activePrintEmployee) {
            empNik = activePrintEmployee.nik || '';
            empName = activePrintEmployee.name || '';
            empId = activePrintEmployee.id || '';
        } else if (activeTenureEmployee) {
            empNik = activeTenureEmployee.nik || '';
            empName = activeTenureEmployee.name || '';
            empId = activeTenureEmployee.id || '';
        }
    }

    let emp = auditState.employees.find(e => 
        (empId && (String(e.id) === String(empId) || String(e.pengguna_id) === String(empId))) ||
        (empNik && String(e.nik).toLowerCase() === String(empNik).toLowerCase()) ||
        (empName && String(e.name).toLowerCase() === String(empName).toLowerCase())
    );

    if (emp) {
        emp = applyEmployeeEdits(emp);
    } else if (empNik || empName || empId) {
        emp = applyEmployeeEdits({
            id: empId || empNik || '58',
            nik: empNik || 'EMP-058',
            name: empName || 'Tengku Muhammad Zainul Aprilizar',
            dept: 'Operations & Logistics / IT',
            role: 'General Affair & IT Control',
            tenure: '2024 - 2026 (Masa Menjabat)',
            risk: 'Low',
            status: 'Terverifikasi'
        });
    } else {
        emp = applyEmployeeEdits({
            id: '58',
            nik: 'EMP-058',
            name: 'Tengku Muhammad Zainul Aprilizar',
            dept: 'Operations & Logistics / IT',
            role: 'General Affair & IT Control',
            tenure: '2024 - 2026 (Masa Menjabat)',
            risk: 'Low',
            status: 'Terverifikasi'
        });
    }

    activePrintEmployee = {
        id: emp.id || emp.pengguna_id,
        nik: emp.nik,
        name: emp.name
    };

    let userPboks = auditState.pbokList.filter(p => {
        if (isPbokDeleted(p)) return false;
        const matchId = (p.pengguna_id && emp.id && (String(p.pengguna_id) === String(emp.id) || String(p.pengguna_id) === String(emp.pengguna_id)));
        const matchNik = p.nik && emp.nik && String(p.nik).toLowerCase() === String(emp.nik).toLowerCase();
        const matchName = p.name && emp.name && (
            String(p.name).toLowerCase().trim() === String(emp.name).toLowerCase().trim() ||
            String(emp.name).toLowerCase().includes(String(p.name).toLowerCase()) ||
            String(p.name).toLowerCase().includes(String(emp.name).toLowerCase())
        );
        return matchId || matchNik || matchName;
    });

    if (userPboks.length === 0 && (String(emp.id) === '105' || String(emp.nik) === '105' || String(emp.name).toLowerCase().includes('reza'))) {
        userPboks = auditState.pbokList.filter(p => !isPbokDeleted(p) && (
            String(p.pengguna_id) === '105' || String(p.nik) === '105' || (p.name && p.name.toLowerCase().includes('reza'))
        ));
        if (userPboks.length === 0) {
            userPboks = defaultPbokList.filter(p => !isPbokDeleted(p));
        }
    }

    if (String(emp.id) === '105' || String(emp.nik) === '105' || String(emp.name).toLowerCase().includes('reza')) {
        document.getElementById('lhaEmpDept').innerText = 'Finance, Accounting & Tax (FAT)';
        document.getElementById('lhaEmpRole').innerText = 'Staff Accounting';
        document.getElementById('lhaEmpTenure').innerText = '25-03-2026 s/d Sekarang (Out 08-07-2026)';
        if (userPboks.length === 0) {
            userPboks = defaultPbokList.filter(p => !isPbokDeleted(p));
        }
    }

    const totalAmount = userPboks.reduce((acc, curr) => acc + (curr.amount || 0), 0);
    const totalLoss = userPboks.reduce((acc, curr) => acc + (curr.lossAmount || 0), 0);

    const randNum = Math.floor(100 + Math.random() * 900);
    const dateToday = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });

    document.getElementById('lhaDocNumber').innerText = `LHA/SPI-VYM/2026/09-${randNum}`;
    document.getElementById('lhaDocDate').innerText = dateToday;
    document.getElementById('lhaSignDate').innerText = dateToday;

    document.getElementById('lhaEmpName').innerText = emp.name;
    document.getElementById('lhaEmpNik').innerText = emp.nik;
    document.getElementById('lhaEmpDept').innerText = emp.dept;
    document.getElementById('lhaEmpRole').innerText = emp.role || 'Staff Operasional';
    document.getElementById('lhaEmpTenure').innerText = emp.tenure || '2024 - 2026';
    document.getElementById('lhaEmpStatus').innerText = `${emp.risk || 'Low'} Risk (${emp.status || 'Terverifikasi'})`;

    const tbody = document.getElementById('lhaTableBody');
    if (userPboks.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; color: #9ca3af; padding: 12px;">Tidak ada dokumen belanja PBOK / PPA yang tercatat untuk karyawan ini.</td></tr>`;
    } else {
        tbody.innerHTML = userPboks.map((p, idx) => {
            const officeLink = p.officeUrl || ((p.notes && p.notes.includes('http')) ? p.notes.match(/https?:\/\/[^\s]+/)?.[0] : p.link || '');
            const isPpa = (p.code || '').startsWith('PPA');
            const displayUrl = officeLink ? (officeLink.replace(/^https?:\/\/(www\.)?/, '').substring(0, 24) + '...') : '';
            const physText = p.taxVerified ? 'Terverifikasi Ada' : (p.lossAmount > 0 ? 'Fisik Tidak Ada' : 'Belum Diverifikasi');
            const physColor = p.taxVerified ? '#166534' : (p.lossAmount > 0 ? '#dc2626' : '#b45309');
            const physIcon = p.taxVerified ? '✅ ' : (p.lossAmount > 0 ? '🚨 ' : '⏳ ');

            return `
            <tr>
                <td style="text-align: center; vertical-align: top; font-weight: 600; font-size: 8pt;">${idx + 1}</td>
                <td style="vertical-align: top; font-size: 8pt; word-break: break-word;"><strong>${p.code}</strong></td>
                <td style="vertical-align: top; font-size: 8pt; word-break: break-word;">
                    <span style="font-weight:700; color:${isPpa ? '#0284c7' : '#0d9488'};">[${isPpa ? 'PPA' : 'PBOK'}]</span>
                    <strong>${p.item}</strong>
                </td>
                <td style="font-weight: 700; vertical-align: top; text-align: right; white-space: nowrap; font-size: 8pt;">${ExcelAuditEngine.formatRupiah(p.amount)}</td>
                <td style="font-size: 7.5pt; vertical-align: top; word-break: break-all;">
                    ${officeLink ? `
                        <a href="${officeLink}" target="_blank" style="color: #0284c7; font-weight: 700; text-decoration: underline; display: inline-block;" title="${officeLink}">
                            🔗 Link Drive
                        </a>
                        <br><span style="color: #64748b; font-size: 6.5pt; word-break: break-all;">(${displayUrl})</span>
                    ` : '<span style="color:#94a3b8; font-style:italic;">-</span>'}
                </td>
                <td style="vertical-align: top; background: ${p.taxVerified ? '#f0fdf4' : '#fffbebf'}; border: 1px solid ${p.taxVerified ? '#bbf7d0' : '#fef3c7'}; padding: 4px; word-break: break-word;">
                    ${p.taxVerified ? `
                        <div style="font-size: 7.5pt; color: #166534; font-weight: 700; line-height: 1.2;">
                            <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i> <strong>DIVERIFIKASI AMAN</strong><br>
                            <span style="color: #334155; font-weight: 600;">Dirangga Madali</span><br>
                            <span style="color: #64748b; font-size: 7pt;">(Head Tax & Acc)</span>
                            ${p.taxVerifiedDate ? `<br><span style="color: #166534; font-size: 6.5pt;">Tgl: ${p.taxVerifiedDate}</span>` : ''}
                        </div>
                    ` : `
                        <div style="font-size: 7.5pt; color: #b45309; font-weight: 700; line-height: 1.2;">
                            <i class="fa-solid fa-clock" style="color: #d97706;"></i> <strong>BELUM DIVERIFIKASI</strong><br>
                            <span style="color: #64748b; font-size: 7pt;">Menunggu ID 107</span>
                        </div>
                    `}
                </td>
                <td style="vertical-align: top; text-align: center; font-size: 7.5pt; word-break: break-word;">
                    <strong style="color: ${physColor};">${physIcon}${physText}</strong>
                    ${p.lossAmount > 0 ? `<br><span style="color:#dc2626; font-size:7pt; font-weight:700;">Rugi: ${ExcelAuditEngine.formatRupiah(p.lossAmount)}</span>` : ''}
                </td>
                <td style="font-size: 7.5pt; color: #334155; vertical-align: top; line-height: 1.3; word-break: break-word;">
                    ${p.notes || '-'}
                </td>
            </tr>
            `;
        }).join('');
    }

    document.getElementById('lhaTotalAmount').innerText = ExcelAuditEngine.formatRupiah(totalAmount);
    document.getElementById('lhaTotalLoss').innerText = ExcelAuditEngine.formatRupiah(totalLoss);

    const notesBox = document.getElementById('lhaNotesContent');
    if (notesBox) {
        if (totalLoss > 0) {
            notesBox.innerHTML = `⚠️ <strong>TEMUAN KERUGIAN AUDIT:</strong> Terdapat indikasi potensi kerugian sebesar <strong>${ExcelAuditEngine.formatRupiah(totalLoss)}</strong> pada pengajuan dokumen belanja di atas. Direkomendasikan pembentukan tim investigasi khusus dan klarifikasi tertulis dengan atasan langsung.`;
        } else {
            notesBox.innerHTML = `✅ <strong>HASIL AUDIT BERSIH & SESUAI SOP:</strong> Berdasarkan pemeriksaan sampel fisik barang dan keabsahan kuitansi/nota office, seluruh transaksi belanja operasional dinyatakan <strong>SESUAI SOP & AMAN</strong> tanpa potensi kerugian perusahaan.`;
        }
    }

    openModal('printAuditModal');
}

/* Cetak Dokumen Laporan Hasil Audit Kinerja Karyawan (LHA Kinerja) Masa Menjabat + Tanda Tangan Atasan */
function openPrintKinerjaModal(empNik = '', empName = '', empId = '') {
    const modal = document.getElementById('printAuditKinerjaModal');
    if (!modal) return;

    if (!empNik && !empName && !empId) {
        if (activePrintKinerjaEmployee) {
            empNik = activePrintKinerjaEmployee.nik || '';
            empName = activePrintKinerjaEmployee.name || '';
            empId = activePrintKinerjaEmployee.id || '';
        } else if (activeTenureEmployee) {
            empNik = activeTenureEmployee.nik || '';
            empName = activeTenureEmployee.name || '';
            empId = activeTenureEmployee.id || '';
        }
    }

    let emp = auditState.employees.find(e => 
        (empId && (String(e.id) === String(empId) || String(e.pengguna_id) === String(empId))) ||
        (empNik && String(e.nik).toLowerCase() === String(empNik).toLowerCase()) ||
        (empName && String(e.name).toLowerCase() === String(empName).toLowerCase())
    );

    if (emp) {
        emp = applyEmployeeEdits(emp);
    } else if (empNik || empName || empId) {
        emp = applyEmployeeEdits({
            id: empId || empNik || '58',
            nik: empNik || 'EMP-058',
            name: empName || 'Tengku Muhammad Zainul Aprilizar',
            dept: 'Operations & Logistics / IT',
            role: 'General Affair & IT Control',
            tenure: '2024 - 2026 (Masa Menjabat)',
            risk: 'Low',
            status: 'Terverifikasi'
        });
    } else {
        emp = applyEmployeeEdits({
            id: '58',
            nik: 'EMP-058',
            name: 'Tengku Muhammad Zainul Aprilizar',
            dept: 'Operations & Logistics / IT',
            role: 'General Affair & IT Control',
            tenure: '2024 - 2026 (Masa Menjabat)',
            risk: 'Low',
            status: 'Terverifikasi'
        });
    }

    activePrintKinerjaEmployee = {
        id: emp.id || emp.pengguna_id,
        nik: emp.nik,
        name: emp.name
    };

    let userPboks = auditState.pbokList.filter(p => {
        if (isPbokDeleted(p)) return false;
        const matchId = (p.pengguna_id && emp.id && (String(p.pengguna_id) === String(emp.id) || String(p.pengguna_id) === String(emp.pengguna_id)));
        const matchNik = p.nik && emp.nik && String(p.nik).toLowerCase() === String(emp.nik).toLowerCase();
        const matchName = p.name && emp.name && (
            String(p.name).toLowerCase().trim() === String(emp.name).toLowerCase().trim() ||
            String(emp.name).toLowerCase().includes(String(p.name).toLowerCase()) ||
            String(p.name).toLowerCase().includes(String(emp.name).toLowerCase())
        );
        return matchId || matchNik || matchName;
    });

    if (userPboks.length === 0 && (String(emp.id) === '58' || String(emp.nik) === 'EMP-058' || String(emp.name).toLowerCase().includes('zainul'))) {
        userPboks = auditState.pbokList.filter(p => !isPbokDeleted(p));
    }

    const totalLoss = userPboks.reduce((acc, curr) => acc + (curr.lossAmount || 0), 0);
    const kpiVal = emp.kpi !== undefined ? emp.kpi : (emp.kpiScore !== undefined ? emp.kpiScore : (emp.score ? (emp.score * 20).toFixed(1) : 92.5));
    const attendanceVal = emp.attendance !== undefined ? (typeof emp.attendance === 'number' ? emp.attendance + '%' : emp.attendance) : '98.2%';
    const sopVal = emp.sop !== undefined ? (typeof emp.sop === 'number' ? emp.sop + '%' : emp.sop) : (emp.sopScore || '95.0%');

    const randNum = Math.floor(100 + Math.random() * 900);
    const dateToday = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });

    document.getElementById('lhaKinerjaDocNumber').innerText = `LHA-KINERJA/SPI-VYM/2026/09-${randNum}`;
    document.getElementById('lhaKinerjaDocDate').innerText = dateToday;
    document.getElementById('lhaKinerjaSignDate').innerText = dateToday;

    document.getElementById('lhaKinerjaEmpName').innerText = emp.name;
    document.getElementById('lhaKinerjaEmpNik').innerText = emp.nik;
    document.getElementById('lhaKinerjaEmpDept').innerText = emp.dept;
    document.getElementById('lhaKinerjaEmpRole').innerText = emp.role || 'Staff Operasional';
    document.getElementById('lhaKinerjaEmpTenure').innerText = emp.tenure || '2024 - 2026 (Masa Menjabat)';

    const gradeEl = document.getElementById('lhaKinerjaEmpGrade');
    if (kpiVal >= 90) {
        gradeEl.innerHTML = `<span style="color: #047857; font-weight: 800;">Grade A (Optimal / Sangat Baik)</span>`;
    } else if (kpiVal >= 75) {
        gradeEl.innerHTML = `<span style="color: #0284c7; font-weight: 800;">Grade B (Baik)</span>`;
    } else {
        gradeEl.innerHTML = `<span style="color: #d97706; font-weight: 800;">Grade C (Cukup / Evaluasi)</span>`;
    }

    document.getElementById('lhaKinerjaKpiScore').innerText = `${kpiVal}%`;
    document.getElementById('lhaKinerjaAttendance').innerText = attendanceVal;
    document.getElementById('lhaKinerjaSopScore').innerText = sopVal;

    const isReza = String(emp.id) === '105' || String(emp.nik).toLowerCase() === '105' || String(emp.nik).toLowerCase() === 'npp-105' || String(emp.nik).toLowerCase() === 'emp-105' || String(emp.name).toLowerCase().includes('reza');
    const isTengku = String(emp.id) === '58' || String(emp.nik).toLowerCase() === 'emp-058' || String(emp.nik).toLowerCase() === '58' || String(emp.name).toLowerCase().includes('tengku') || String(emp.name).toLowerCase().includes('zainul');
    const isItStaff = isTengku || (((emp.role || '').toLowerCase().includes('it') || (emp.dept || '').toLowerCase() === 'it') && !(emp.dept || '').toLowerCase().includes('ops'));
    const isTaxOrAcc = (emp.dept || '').toLowerCase().includes('finance') || (emp.dept || '').toLowerCase().includes('acc') || (emp.dept || '').toLowerCase().includes('tax') || (emp.role || '').toLowerCase().includes('tax') || (emp.role || '').toLowerCase().includes('accounting');

    let jobdeskRows = [];

    if (isReza) {
        document.getElementById('lhaKinerjaEmpDept').innerText = 'Finance, Accounting & Tax (FAT)';
        document.getElementById('lhaKinerjaEmpRole').innerText = 'Staff Accounting';
        document.getElementById('lhaKinerjaEmpTenure').innerText = '25 Maret 2026 – 08 Juli 2026 (Out 8 Juli)';

        jobdeskRows = [
            { no: 1, jobdesk: '[1] Handle PO Pembelian & Komunikasi dengan Supplier', target: 'PO & Supplier Valid', status: '✔ Selesai (Sebagian)', findings: 'Terlaksana 11 kegiatan (pembuatan PO pengajuan Warehouse 19 & 24 Juni). Tidak ada catatan evaluasi rutin supplier.', link: '' },
            { no: 2, jobdesk: '[2] Negosiasi Harga & Input Resi Pengiriman di E-Katalog', target: 'E-Catalog 100% Update', status: '✔ Selesai', findings: 'Terlaksana 30 kegiatan. Aktif buat paket, negosiasi harga (Kemenkes, RSUP Jayapura), & input resi E-Katalog (1-3 Juli 2026).', link: '' },
            { no: 3, jobdesk: '[3] Handle Informasi Piutang dan Hutang Perusahaan', target: 'Piutang & Hutang Akurat', status: '✔ Selesai (Dominan)', findings: 'Terlaksana 57 kegiatan. Update piutang jatuh tempo, cicilan, hutang ekspedisi (dengan kwitansi), & hutang supplier.', link: '' },
            { no: 4, jobdesk: '[4] Handle Invoice & Dokumen Pelengkap (Penjualan & Pembelian)', target: 'Dokumen Invoice Lengkap', status: '✔ Selesai (Dominan)', findings: 'Terlaksana 66 kegiatan (porsi terbanyak). Invoice copier/medikal, pemberkasan RSUD (Dumai/Rohul/Jayapura), & invoice AXA/EVO.', link: '' },
            { no: 5, jobdesk: '[5] Handle & Koordinasi Terkait Data Laporan Keuangan', target: 'Input Accurate & Laporan', status: '✔ Selesai', findings: 'Terlaksana 26 kegiatan. Input pembukuan keuangan di Accurate & koordinasi rutin dengan Senior Accounting/Director.', link: '' },
            { no: 6, jobdesk: '[6] Handling Pengajuan Biaya Rutin, E-Payment & Pajak Aset Kendaraan', target: 'Pajak Aset Bebas Denda', status: '✖ Tidak Ditemukan (Terbengkalai)', findings: 'TEMUAN AUDIT: Hanya 12 kegiatan e-payment. Pembayaran Pajak Aset Kendaraan Kantor (Mobil Ertiga - Poin 18) & akomodasi dinas tidak pernah diajukan.', link: '' },
            { no: 7, jobdesk: '[7] Pelaporan Mingguan & Akuntabilitas Kerja Harian (Periode Out)', target: 'Laporan 100% Terisi s/d Out', status: '⚠ Belum Selesai (Terhenti 05 Juli)', findings: 'Laporan mingguan terhenti 05 Juli 2026 (21:58 WIB). Tanggal 06–31 Juli 2026 status 100% "Belum Diisi" sehubungan out pada 08 Juli 2026.', link: '' }
        ];
    } else if (isItStaff) {
        jobdeskRows = [
            { no: 1, jobdesk: 'Melakukan instalasi perangkat IT perusahaan', target: 'Normal & Terinstal', status: '✔ Selesai', findings: 'Tidak ditemukan kerusakan pada seluruh perangkat IT kantor', link: 'https://drive.google.com/drive/folders/1IyzcGLSqvdx5by17MJ7jGog0mE89yrfP' },
            { no: 2, jobdesk: 'Memastikan seluruh perangkat terinstal dengan baik', target: '100% Berfungsi', status: '✔ Selesai', findings: 'Seluruh perangkat IT (PC, Laptop, Router) terinstal & berfungsi lancar', link: 'https://drive.google.com/drive/folders/1IyzcGLSqvdx5by17MJ7jGog0mE89yrfP' },
            { no: 3, jobdesk: 'Backup email perusahaan', target: 'Data Backup Lengkap', status: '✔ Selesai', findings: 'Data email & password terlampir pada Google Spreadsheet perusahaan', link: 'https://drive.google.com/file/d/1Bv5kilsWKC8S7goRE3jgtwL-imKw4eO4/view?usp=sharing' },
            { no: 4, jobdesk: 'Backup database perusahaan', target: 'Database Backup Complete', status: '✔ Selesai', findings: 'Database Website Office & Portal Web lengkap terlampir pada Spreadsheet', link: 'https://drive.google.com/file/d/1HlkLow4NJpRCTIkK_7v-dVaW2uhIP-0q/view?usp=sharing' },
            { no: 5, jobdesk: 'Backup konfigurasi WiFi', target: 'Konfig Lengkap', status: '✔ Selesai', findings: 'Data SSID & password router WiFi kantor ter-backup rapi', link: 'https://drive.google.com/file/d/1q9lus6rzl0Ow2itOv0kVbF3moaUHdNKp/view?usp=sharing' },
            { no: 6, jobdesk: 'Backup password akun kerja', target: 'Kredensial Aman', status: '✔ Selesai', findings: 'Seluruh kredensial password akun kerja ter-backup pada Google Spreadsheet', link: 'https://drive.google.com/drive/folders/16HesuKJ4-oaWCC53oZGdRe8PE1UAfHnk' },
            { no: 7, jobdesk: 'Sosialisasi penggunaan perangkat IT', target: 'Briefing Karyawan', status: '⚠ Belum Selesai', findings: 'Telah dilakukan briefing & presentasi, diperlukan sosialisasi rutin susulan', link: 'https://drive.google.com/drive/folders/1fuRpba0FrILB5AZTDjbaZYx9C4BRHyWG' },
            { no: 8, jobdesk: 'Mencari solusi permasalahan IT', target: 'Solusi Permasalahan', status: '⚠ Belum Selesai', findings: 'Akun WhatsApp Notifikasi Office terdeteksi spam regulasi WA (Temuan Audit)', link: 'https://drive.google.com/drive/folders/1QXdg5RjkSrbBAgIUplCYSVVbu4PiEzon' },
            { no: 9, jobdesk: 'Pembuatan akun email karyawan', target: 'Akun Email Aktif', status: '✔ Selesai', findings: 'Seluruh akun email karyawan ter-setup & terkelola dengan baik', link: 'https://drive.google.com/drive/folders/1l8ciTapYNEhdr1WHtho0VOW7RNSN9uog' },
            { no: 10, jobdesk: 'Service perangkat IT rusak', target: 'Zero Downtime', status: '✔ Selesai', findings: 'Seluruh perbaikan perangkat IT terlaksana dengan baik', link: 'https://drive.google.com/drive/folders/1J_w3iO1t7o7Y-J3d4mU4v4lH-O_9kfw' },
            { no: 11, jobdesk: 'Mengatasi troubleshooting jaringan internet', target: 'Internet Stabil', status: '✔ Selesai', findings: 'Koneksi jaringan internet kantor terverifikasi aman & stabil', link: 'https://drive.google.com/drive/folders/1KCHG7cpxWziwDkYtyI8-lDZVhnz_9kfw' },
            { no: 12, jobdesk: 'Backup data perusahaan secara berkala', target: 'Routine Backup', status: '✔ Selesai', findings: 'Prosedur backup data berkala dilaksanakan sesuai jadwal', link: 'https://drive.google.com/drive/folders/1HlkLow4NJpRCTIkK_7v-dVaW2uhIP-0q' },
            { no: 13, jobdesk: 'Pengembangan program Marketing', target: 'Fitur Marketing Web', status: '⚠ Belum Selesai', findings: 'Tahap pengujian fitur program marketing masih memerlukan penyesuaian lanjutan', link: 'https://drive.google.com/drive/folders/1QXdg5RjkSrbBAgIUplCYSVVbu4PiEzon' },
            { no: 14, jobdesk: 'Perbaikan bug program', target: 'Bug Fixes', status: 'N/A', findings: 'Tidak dapat diverifikasi / belum ada perbaikan khusus', link: '' },
            { no: 15, jobdesk: 'Update software perusahaan', target: 'Software Up to Date', status: 'N/A', findings: 'Tidak dapat diverifikasi / belum ada perbaikan khusus', link: '' },
            { no: 16, jobdesk: 'Handle website perusahaan', target: '100% Uptime Web', status: '✔ Selesai', findings: 'Data lengkap (office.visiyosindo.id & visiyosindo.com) dapat diakses normal', link: 'https://drive.google.com/drive/folders/1XXQ8hW-iFtOAJGG3EEVuF_yAzSqjMfDk' },
            { no: 17, jobdesk: 'Desain website', target: 'Tampilan Modern', status: '✔ Selesai', findings: 'Melakukan perubahan & pembaruan tampilan pada Menu Portal Office', link: 'https://drive.google.com/drive/folders/1S1R3WdcWzVF74MMf5E6Ktq_aV4UYCvXT' },
            { no: 18, jobdesk: 'Handle email perusahaan', target: 'Email Transaksional', status: '✔ Selesai', findings: 'Akun email perusahaan (@visiyosindo.com) terkelola dengan data lengkap', link: 'https://drive.google.com/drive/folders/1DNGrFnpfL8ndbsHZnJ6ne7dkwH9tI43Y' }
        ];
    } else if (isTaxOrAcc) {
        jobdeskRows = [
            { no: 1, jobdesk: 'Verifikasi & Pelaporan Pembukuan Keuangan', target: '100% Validated', status: '✔ Selesai', findings: 'Seluruh transaksi pembukuan diperiksa keabsahannya', link: '' },
            { no: 2, jobdesk: 'Penyusunan Rekapitulasi Kas & Bank Bulanan', target: 'Balance & Accurate', status: '✔ Selesai', findings: 'Laporan kas & bank tepat waktu dan akurat', link: '' },
            { no: 3, jobdesk: 'Pelaporan & Setor Pajak PPh/PPN Perusahaan', target: 'Nihil Denda Pajak', status: '✔ Selesai', findings: 'Kepatuhan pelaporan & penyetoran PPh/PPN dilaksanakan tepat waktu', link: '' },
            { no: 4, jobdesk: 'Pengawasan Akuntabilitas & Efisiensi Operasional', target: 'Zero Deficit', status: '✔ Selesai', findings: 'Pengawasan efisiensi anggaran berjalan tertib', link: '' }
        ];
    } else {
        jobdeskRows = [
            { no: 1, jobdesk: `Pelaksanaan Target Utama Jabatan (${emp.role || 'Staff Operasional'})`, target: 'Memenuhi Target KPI', status: '✔ Selesai', findings: `Target individu tercapai dengan skor KPI rata-rata ${kpiVal}%`, link: '' },
            { no: 2, jobdesk: 'Kedisiplinan Presensi & Jam Kerja Harian', target: 'Kehadiran > 95%', status: '✔ Selesai', findings: `Catatan presensi kehadiran ${attendanceVal} dengan kedisiplinan baik`, link: '' },
            { no: 3, jobdesk: 'Pelaksanaan Prosedur Operasional Standar (SOP)', target: 'Patuh SOP Office', status: '✔ Selesai', findings: `Skor kepatuhan SOP ${sopVal}, pelaksanaan tugas sesuai standar`, link: '' },
            { no: 4, jobdesk: 'Tanggung Jawab Operasional & Kerjasama Tim', target: 'Proaktif & Kolaboratif', status: '✔ Selesai', findings: 'Menunjukkan koordinasi & komunikasi kerja yang baik antar divisi', link: '' }
        ];
    }

    const totalTasks = jobdeskRows.length;
    const completedTasks = jobdeskRows.filter(r => r.status.includes('Selesai') || r.status.includes('✔')).length;
    const taskPct = Math.round((completedTasks / totalTasks) * 100);

    const taskStatusEl = document.getElementById('lhaKinerjaTaskStatus');
    const taskSubEl = document.getElementById('lhaKinerjaTaskSub');
    if (taskStatusEl) {
        taskStatusEl.innerText = `${completedTasks} / ${totalTasks} Selesai`;
        taskStatusEl.style.color = taskPct >= 70 ? '#15803d' : '#d97706';
    }
    if (taskSubEl) {
        taskSubEl.innerText = `${taskPct}% Jobdesk Terlaksana`;
        taskSubEl.style.color = taskPct >= 70 ? '#166534' : '#b45309';
    }

    const tbody = document.getElementById('lhaKinerjaTableBody');
    tbody.innerHTML = jobdeskRows.map(row => {
        let statusBadge = '';
        if (row.status.includes('✔') || row.status.includes('Selesai')) {
            statusBadge = `<span class="kpi-badge badge-positive" style="font-size: 7.5pt; padding: 2px 6px;">${row.status}</span>`;
        } else if (row.status.includes('⚠') || row.status.includes('Belum')) {
            statusBadge = `<span class="kpi-badge badge-warning" style="font-size: 7.5pt; padding: 2px 6px;">${row.status}</span>`;
        } else if (row.status.includes('✖') || row.status.includes('Tidak')) {
            statusBadge = `<span class="kpi-badge badge-critical" style="font-size: 7.5pt; padding: 2px 6px;">${row.status}</span>`;
        } else {
            statusBadge = `<span class="badge-modern" style="background: #f1f5f9; color: #64748b; font-size: 7.5pt; padding: 2px 6px;">${row.status}</span>`;
        }

        const gdriveHtml = row.link ? `
            <a href="${row.link}" target="_blank" style="color: #0284c7; font-weight: 700; text-decoration: underline; font-size: 7.5pt; word-break: break-all;" title="${row.link}">
                🔗 Link GDrive
            </a>
        ` : `<span style="color: #94a3b8; font-style: italic; font-size: 7.5pt;">-</span>`;

        return `
        <tr>
            <td style="text-align: center; vertical-align: top; font-weight: 600;">${row.no}</td>
            <td style="vertical-align: top; word-break: break-word;">
                <strong style="color: #0f172a; font-size: 8.5pt;">${row.jobdesk}</strong>
            </td>
            <td style="text-align: center; vertical-align: top; font-size: 7.5pt; color: #475569;">${row.target}</td>
            <td style="text-align: center; vertical-align: top; font-size: 7.5pt;">
                ${statusBadge}
            </td>
            <td style="vertical-align: top; line-height: 1.35; font-size: 7.5pt; color: #334155; word-break: break-word;">
                ${row.findings}
            </td>
            <td style="text-align: center; vertical-align: top; font-size: 7.5pt; word-break: break-all;">
                ${gdriveHtml}
            </td>
        </tr>
        `;
    }).join('');

    // III. RINCIAN TEMUAN AUDIT SPESIFIK & RISIKO OPERASIONAL
    const findingsContainer = document.getElementById('lhaKinerjaSpecificFindings');
    if (findingsContainer) {
        if (isReza) {
            findingsContainer.innerHTML = `
                <div style="display: block;">
                    <div style="border: 1px solid #fca5a5; background: #fef2f2; border-radius: 6px; padding: 10px 14px; font-size: 8pt; margin-bottom: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <strong style="color: #dc2626; font-size: 8.5pt;">TEMUAN AUDIT 1: PENUNGGAKAN PAJAK KENDARAAN OPERASIONAL (MOBIL ERTIGA)</strong>
                            <span style="background: #fee2e2; color: #dc2626; padding: 1px 6px; border-radius: 4px; font-size: 7pt; font-weight: 700;">Risiko Sedang / Kepatuhan</span>
                        </div>
                        <p style="margin: 0 0 4px 0;"><strong>Objek Audit:</strong> Master Jobdesk Poin [6] No 18 (Pengajuan Pembayaran Pajak Aset Kantor Kendaraan Mobil Ertiga)</p>
                        <p style="margin: 0 0 4px 0;"><strong>Kondisi:</strong> Berdasarkan audit perbandingan Master Jobdesk vs Rekap Laporan Mingguan (Maret - Juli 2026), tugas pengajuan/pembayaran pajak kendaraan Mobil Ertiga tidak pernah diajukan maupun dilaporkan.</p>
                        <p style="margin: 0 0 4px 0; color: #dc2626;"><strong>Dampak Perusahaan:</strong> Pajak STNK Mobil Ertiga kantor berisiko menunggak/terkena denda di masa transisi pergantian karyawan.</p>
                        <p style="margin: 0; color: #047857;"><strong>Rekomendasi SPI:</strong> Head of Accounting & Tax (Dirangga Madali) segera mengambil alih pengajuan & penyelesaian pajak susulan.</p>
                    </div>
                    <div style="border: 1px solid #fde68a; background: #fffbebf; border-radius: 6px; padding: 10px 14px; font-size: 8pt;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <strong style="color: #b45309; font-size: 8.5pt;">TEMUAN AUDIT 2: HENTINYA PELAPORAN MINGGUAN PERIODE OUT (06 - 31 JULI 2026)</strong>
                            <span style="background: #fef3c7; color: #b45309; padding: 1px 6px; border-radius: 4px; font-size: 7pt; font-weight: 700;">Tertib Administrasi</span>
                        </div>
                        <p style="margin: 0 0 4px 0;"><strong>Objek Audit:</strong> Rekap Laporan Mingguan Karyawan Juli 2026 (Rentang 01 - 31 Juli 2026)</p>
                        <p style="margin: 0 0 4px 0;"><strong>Kondisi:</strong> Terdaftar 208 kegiatan selesai (Maret–Juli). Namun pelaporan terhenti pada 05 Juli 2026 (21:58 WIB). Tanggal 06 s/d 31 Juli 2026 (20 hari kerja) status 100% "Belum Diisi" sehubungan tanggal out karyawan (08 Juli 2026).</p>
                        <p style="margin: 0; color: #047857;"><strong>Rekomendasi SPI:</strong> Lakukan Berita Acara Serah Terima (Handover Memo) berkas fisik & digital kepada Staff Accounting penerus.</p>
                    </div>
                </div>
            `;
        } else if (isItStaff) {
            findingsContainer.innerHTML = `
                <div style="display: block;">
                    <div style="border: 1px solid #fde68a; background: #fffbebf; border-radius: 6px; padding: 10px 14px; font-size: 8pt;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <strong style="color: #b45309; font-size: 8.5pt;">TEMUAN AUDIT SPESIFIK (OPERASIONAL IT & GA)</strong>
                            <span style="background: #fef3c7; color: #b45309; padding: 1px 6px; border-radius: 4px; font-size: 7pt; font-weight: 700;">Risiko Sedang</span>
                        </div>
                        <p style="margin: 0 0 4px 0;"><strong>Objek Audit:</strong> Perangkat IT, Akun Email, Database & WhatsApp API</p>
                        <p style="margin: 0 0 4px 0;"><strong>Kondisi:</strong> Akun WhatsApp Notifikasi Office terdeteksi sebagai Akun Spam menurut Regulasi WA.</p>
                        <p style="margin: 0 0 4px 0; color: #dc2626;"><strong>Dampak Perusahaan:</strong> Notifikasi pesan otomatis kantor pusat sempat terhenti.</p>
                        <p style="margin: 0; color: #047857;"><strong>Rekomendasi SPI:</strong> Re-konfigurasi ulang API Whacenter & migrasi akun resmi VYM.</p>
                    </div>
                </div>
            `;
        } else {
            findingsContainer.innerHTML = `
                <div style="display: block;">
                    <div style="border: 1px solid #bbf7d0; background: #f0fdf4; border-radius: 6px; padding: 10px 14px; font-size: 8pt; margin-bottom: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <strong style="color: #166534; font-size: 8.5pt;">TEMUAN AUDIT 1: KELENGKAPAN ADM PENGAJUAN BELANJA DIVISI ${emp.dept.toUpperCase()}</strong>
                            <span style="background: #dcfce7; color: #166534; padding: 1px 6px; border-radius: 4px; font-size: 7pt; font-weight: 700;">Tertib Administrasi</span>
                        </div>
                        <p style="margin: 0 0 4px 0;"><strong>Objek Audit:</strong> Dokumen Pengajuan Belanja Operasional (PBOK) & Aset (PPA)</p>
                        <p style="margin: 0 0 4px 0;"><strong>Kondisi:</strong> Seluruh transaksi pengajuan belanja terdaftar dan diperiksa kesesuaian nominal serta keabsahan fisik dokumen pendukung.</p>
                        <p style="margin: 0 0 4px 0; color: #15803d;"><strong>Hasil Audit:</strong> ${totalLoss > 0 ? `<span style="color:#dc2626; font-weight:700;">Terdapat potensi kerugian sebesar ${ExcelAuditEngine.formatRupiah(totalLoss)}</span>` : 'Seluruh pengajuan belanja dinyatakan AMAN & TERVERIFIKASI.'}</p>
                        <p style="margin: 0; color: #047857;"><strong>Rekomendasi SPI:</strong> Melakukan pengarsipan nota fisik & digital di folder drive kantor secara tertib.</p>
                    </div>
                    <div style="border: 1px solid #e2e8f0; background: #fafafa; border-radius: 6px; padding: 10px 14px; font-size: 8pt;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <strong style="color: #334155; font-size: 8.5pt;">TEMUAN AUDIT 2: KEPATUHAN SOP & AKUNTABILITAS KERJA HARIAN</strong>
                            <span style="background: #f1f5f9; color: #475569; padding: 1px 6px; border-radius: 4px; font-size: 7pt; font-weight: 700;">Operasional Rutin</span>
                        </div>
                        <p style="margin: 0 0 4px 0;"><strong>Objek Audit:</strong> Presensi Kehadiran (${attendanceVal}) & Kepatuhan SOP (${sopVal})</p>
                        <p style="margin: 0 0 4px 0;"><strong>Kondisi:</strong> Pelaksanaan tugas harian berjalan teratur dengan tingkat risiko ${emp.risk || 'Low'} Risk.</p>
                        <p style="margin: 0; color: #047857;"><strong>Rekomendasi SPI:</strong> Pertahankan tingkat kepatuhan SOP dan koordinasi antar divisi.</p>
                    </div>
                </div>
            `;
        }
    }

    // IV. TABEL REKOMENDASI AUDITOR INTERN & TARGET PENYELESAIAN (PIC)
    const recBody = document.getElementById('lhaKinerjaRecommendationsBody');
    if (recBody) {
        if (isReza) {
            recBody.innerHTML = `
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: 600;">1</td>
                    <td style="vertical-align: top;">Melakukan pengajuan & penyelesaian tunggakan pajak kendaraan operasional (Mobil Ertiga) melalui Head of Accounting & Tax.</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700;">Finance & Tax / HRGA</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700; color: #0284c7;">3 Hari Kerja</td>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: 600;">2</td>
                    <td style="vertical-align: top;">Melakukan Berita Acara Serah Terima (Hand-over Memo) dokumen fisik & digital (Invoice Copier/Medikal, Tagihan RSUD Dumai/Rohul/Jayapura, PO Warehouse, & E-Catalog) kepada Staff Accounting baru.</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700;">Staff Accounting Baru / Head Acc</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700; color: #0284c7;">5 Hari Kerja</td>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: 600;">3</td>
                    <td style="vertical-align: top;">Verifikasi & rekonsiliasi ulang saldo piutang ekspedisi gudang serta invoice supplier di sistem Accurate pasca-out karyawan.</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700;">Senior Accounting</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700; color: #0284c7;">7 Hari Kerja</td>
                </tr>
            `;
        } else if (isItStaff) {
            recBody.innerHTML = `
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: 600;">1</td>
                    <td style="vertical-align: top;">Menunjuk PIC sementara atau melakukan percepatan proses rekrutmen Staff IT untuk melanjutkan pekerjaan yang belum terselesaikan.</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700;">HRD / Manajemen</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700; color: #0284c7;">7 Hari Kerja</td>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: 600;">2</td>
                    <td style="vertical-align: top;">Menyelesaikan seluruh pekerjaan yang masih Belum Selesai (⚠) berdasarkan hasil audit, termasuk pengembangan program Marketing dan permasalahan WhatsApp Notifikasi Office.</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700;">Staff IT Baru</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700; color: #0284c7;">14 Hari Kerja</td>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: 600;">3</td>
                    <td style="vertical-align: top;">Melakukan verifikasi dan pengujian terhadap seluruh akun, akses sistem, website, database, jaringan, serta backup data untuk memastikan seluruh layanan dapat berjalan normal.</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700;">Staff IT Baru</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700; color: #0284c7;">30 Hari Kerja</td>
                </tr>
            `;
        } else {
            recBody.innerHTML = `
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: 600;">1</td>
                    <td style="vertical-align: top;">Menjaga ketertiban pengarsipan bukti kuitansi & nota pengajuan belanja operasional (${emp.dept}) pada folder Google Drive office.</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700;">${emp.name} / Head Dept</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700; color: #0284c7;">Rutin / 3 Hari Kerja</td>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: 600;">2</td>
                    <td style="vertical-align: top;">Melakukan koordinasi berkala dengan divisi Finance, Accounting & Tax terkait keabsahan administrasi dokumen pengajuan belanja kantor.</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700;">Pemohon / Finance & Acc</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700; color: #0284c7;">7 Hari Kerja</td>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: 600;">3</td>
                    <td style="vertical-align: top;">Mempertahankan tingkat kedisiplinan presensi harian dan kepatuhan terhadap Prosedur Operasional Standar (SOP) divisi ${emp.dept}.</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700;">${emp.name}</td>
                    <td style="text-align: center; vertical-align: top; font-weight: 700; color: #0284c7;">Berkelanjutan</td>
                </tr>
            `;
        }
    }

    // V. KESIMPULAN AUDIT & CATATAN PENUTUP SPI
    const notesBox = document.getElementById('lhaKinerjaNotesContent');
    if (notesBox) {
        if (isReza) {
            notesBox.innerHTML = `
                Berdasarkan audit kinerja komprehensif terhadap mantan karyawan Staff Accounting (<strong>${emp.name}</strong>, NPP: 105, Masa Kerja: 25 Maret – 08 Juli 2026):<br>
                • <strong>Capaian Utama:</strong> Tugas penanganan Invoice Penjualan/Pembelian (66 kegiatan), Piutang/Hutang (57 kegiatan), dan E-Catalog LKPP (30 kegiatan) terverifikasi telah dilaksanakan dengan sangat baik (Total 208 kegiatan selesai).<br>
                • <strong>Temuan Pajak Kendaraan:</strong> Tugas pengurusan Pajak Kendaraan Mobil Ertiga (Master Jobdesk Poin 6 No 18) tidak pernah diajukan di laporan mingguan, sehingga memerlukan pengajuan susulan oleh Head of Accounting & Tax.<br>
                • <strong>Status Out Karyawan:</strong> Terakhir mengisi laporan pada 05 Juli 2026 (21:58 WIB) sebelum resmi out pada 08 Juli 2026. Disarankan finalisasi Berita Acara Handover dokumen agar operasional Divisi FAT berjalan lancar.
            `;
            notesBox.style.background = '#fffbebf';
            notesBox.style.borderColor = '#fde68a';
        } else if (isItStaff) {
            notesBox.innerHTML = `
                Berdasarkan audit terhadap pekerjaan yang dilaksanakan oleh karyawan Staff Information Technology (<strong>${emp.name}</strong>), diperoleh kesimpulan sebagai berikut:<br>
                • <strong>Pekerjaan Selesai:</strong> Mayoritas pekerjaan instalasi perangkat, backup database, email, website, router/switch, dan pembaruan tampilan menu office terverifikasi telah diselesaikan dengan baik (<strong>✔ Selesai</strong>).<br>
                • <strong>Pekerjaan Perlu Tindak Lanjut:</strong> Ditemukan beberapa pekerjaan yang masih memerlukan penyelesaian (<strong>⚠ Belum Selesai</strong>), khususnya pengembangan fitur program Marketing dan penanganan spam WhatsApp Notifikasi.<br>
                • <strong>Mitigasi Risiko:</strong> Direkomendasikan penunjukan PIC/Staff IT Baru sesuai target waktu 7–30 Hari Kerja untuk serah terima pekerjaan secara aman dan lancar.
            `;
            notesBox.style.background = '#f0fdf4';
            notesBox.style.borderColor = '#a7f3d0';
        } else {
            notesBox.innerHTML = `
                Berdasarkan audit kinerja komprehensif terhadap karyawan (<strong>${emp.name}</strong>, Divisi: <strong>${emp.dept}</strong>, Jabatan: <strong>${emp.role || 'Staff Operasional'}</strong>):<br>
                • <strong>Capaian Utama:</strong> Seluruh tugas operasional harian dan pengajuan belanja terverifikasi dilaksanakan sesuai dengan SOP kantor (<strong>✔ Terverifikasi</strong>).<br>
                • <strong>Kepatuhan & Kedisiplinan:</strong> Karyawan berada dalam kategori risiko <strong>${emp.risk || 'Low'} Risk</strong> dengan tingkat kepatuhan SOP <strong>${sopVal}</strong> dan skor kehadiran <strong>${attendanceVal}</strong>.<br>
                • <strong>Rekomendasi SPI:</strong> Direkomendasikan pemeliharaan tertib administrasi dokumen dan koordinasi berkelanjutan antar unit kerja.
            `;
            notesBox.style.background = '#f0fdf4';
            notesBox.style.borderColor = '#a7f3d0';
        }
    }

    openModal('printAuditKinerjaModal');
}
