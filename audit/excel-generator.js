/**
 * Excel Generator Engine (5-Sheet) - Portal Office & Audit Belanja PBOK / PPA
 */

const ExcelAuditEngine = {

    /**
     * Memformat angka menjadi string Mata Uang Rupiah (Rp)
     */
    formatRupiah: function(val) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val || 0);
    },

    /**
     * Ekspor Workbook 5-Sheet Lengkap
     */
    exportFullWorkbook: function(auditData, pbokData, findingsData, kpiSummary) {
        if (typeof XLSX === 'undefined') {
            alert('Error: Library SheetJS (XLSX) belum dimuat.');
            return;
        }

        const wb = XLSX.utils.book_new();

        // Sheet 1: Executive Summary Portal Office
        const wsSummary = this.buildExecutiveSummarySheet(auditData, pbokData, kpiSummary);
        XLSX.utils.book_append_sheet(wb, wsSummary, "Executive Summary");

        // Sheet 2: Detail Audit Karyawan
        const wsEmp = this.buildEmployeeDetailSheet(auditData);
        XLSX.utils.book_append_sheet(wb, wsEmp, "Detail Audit Karyawan");

        // Sheet 3: Detail Audit Belanja PBOK & PPA (NEW)
        const wsPbok = this.buildPbokPpaDetailSheet(pbokData);
        XLSX.utils.book_append_sheet(wb, wsPbok, "Audit Belanja PBOK & PPA");

        // Sheet 4: Matriks Temuan & Action Plan
        const wsFindings = this.buildFindingsMatrixSheet(findingsData);
        XLSX.utils.book_append_sheet(wb, wsFindings, "Matriks Temuan & Risk");

        // Sheet 5: Template Input PBOK & PPA
        const wsTemplate = this.buildBlankPbokTemplateSheet();
        XLSX.utils.book_append_sheet(wb, wsTemplate, "Template Input PBOK PPA");

        const now = new Date().toISOString().slice(0, 10);
        const fileName = `Laporan_Audit_Office_PBOK_PPA_${now}.xlsx`;

        XLSX.writeFile(wb, fileName);
    },

    /**
     * Sheet 1: Executive Summary Portal Office
     */
    buildExecutiveSummarySheet: function(auditData, pbokData, kpiSummary) {
        const rows = [];

        rows.push(["LAPORAN AUDIT PORTAL OFFICE: BELANJA OPERASIONAL (PBOK) & PEMBELIAN ASET (PPA)"]);
        rows.push(["PT CORPORATE OFFICE PORTAL - INTERNAL AUDIT & ASSET OVERSIGHT"]);
        rows.push([`Periode Audit: Masa Menjabat Active | PBOK (Operasional) & PPA (Pembelian Aset)`]);
        rows.push([]);

        // Metrik Keuangan PBOK & PPA
        rows.push(["RINGKASAN EXECUTIVE AUDIT BELANJA PBOK & PPA"]);
        rows.push(["Indikator Evaluasi Belanja", "Nilai Realisasi (Rp) / Status", "Target / Catatan"]);
        rows.push(["Total Nilai Pengajuan Belanja (PBOK & PPA)", this.formatRupiah(kpiSummary.totalPbokAmount), "Seluruh Divisi"]);
        rows.push(["Persentase Barang Terverifikasi Ada", `${kpiSummary.verifiedPhysicalPct}%`, "Target Min. 95%"]);
        rows.push(["Total Potensi Kerugian / Selisih Fiktif", this.formatRupiah(kpiSummary.totalPotentialLoss), kpiSummary.totalPotentialLoss === 0 ? "AMAN" : "BUTUH INVESTIGASI"]);
        rows.push([]);

        // Metrik SDM
        rows.push(["RINGKASAN EVALUASI KINERJA KARYAWAN"]);
        rows.push(["Total Karyawan Di-audit", kpiSummary.totalEmployees, "100% Audit Complete"]);
        rows.push(["Rata-rata Skor Kinerja (1-5)", kpiSummary.avgScore, kpiSummary.avgScore >= 3.5 ? "Sangat Baik" : "Evaluasi"]);
        rows.push(["Kepatuhan SOP (%)", `${kpiSummary.sopCompliance}%`, "Min. 85.0%"]);
        rows.push([]);

        // Summary per Divisi
        rows.push(["REKAPITULASI PENGGUNAAN ANGGARAN PBOK/PPA PER DIVISI"]);
        rows.push(["Divisi / Departemen", "Jumlah Pengajuan", "Total Pengajuan (Rp)", "Barang Ada & Sesuai", "Potensi Kerugian (Rp)"]);

        const depts = [...new Set(pbokData.map(p => p.dept))];
        depts.forEach(dept => {
            const list = pbokData.filter(p => p.dept === dept);
            const count = list.length;
            const sumAmt = list.reduce((acc, curr) => acc + curr.amount, 0);
            const okCount = list.filter(p => p.physicalStatus === 'Sudah Sesuai SOP' || p.physicalStatus === 'Terverifikasi Ada').length;
            const sumLoss = list.reduce((acc, curr) => acc + curr.lossAmount, 0);

            rows.push([dept, count, this.formatRupiah(sumAmt), `${okCount} / ${count} Items`, this.formatRupiah(sumLoss)]);
        });

        const ws = XLSX.utils.aoa_to_sheet(rows);
        ws['!cols'] = [{ wch: 40 }, { wch: 30 }, { wch: 30 }, { wch: 20 }, { wch: 25 }];
        return ws;
    },

    /**
     * Sheet 2: Detail Audit Karyawan
     */
    buildEmployeeDetailSheet: function(auditData) {
        const rows = [];
        rows.push(["NIK", "Nama Karyawan", "Departemen", "Jabatan", "Realisasi KPI (%)", "Quality Score", "Kepatuhan SOP (%)", "Presensi (%)", "Risk Level", "Status", "Catatan Auditor"]);

        auditData.forEach(e => {
            rows.push([e.nik, e.name, e.dept, e.role, e.kpi, e.quality, e.sop, e.attendance, e.risk, e.status, e.notes]);
        });

        const ws = XLSX.utils.aoa_to_sheet(rows);
        ws['!cols'] = [{ wch: 15 }, { wch: 25 }, { wch: 22 }, { wch: 22 }, { wch: 15 }, { wch: 15 }, { wch: 16 }, { wch: 14 }, { wch: 14 }, { wch: 16 }, { wch: 40 }];
        return ws;
    },

    /**
     * Sheet 3: Detail Audit Belanja PBOK & PPA
     */
    buildPbokPpaDetailSheet: function(pbokData) {
        const rows = [];
        rows.push([
            "Kode Pengajuan", 
            "NIK Pemohon", 
            "Nama Pemohon", 
            "Divisi / Dept", 
            "Masa Menjabat", 
            "Deskripsi Belanja & Vendor", 
            "Nilai Pengajuan (Rp)", 
            "Status Keberadaan Fisik Barang", 
            "Status Analisis Kerugian", 
            "Nilai Potensi Kerugian (Rp)", 
            "Catatan Investigasi Auditor"
        ]);

        pbokData.forEach(p => {
            rows.push([
                p.code,
                p.nik,
                p.name,
                p.dept,
                p.tenure,
                p.item,
                p.amount,
                p.physicalStatus,
                p.lossStatus,
                p.lossAmount,
                p.notes
            ]);
        });

        const ws = XLSX.utils.aoa_to_sheet(rows);
        ws['!cols'] = [
            { wch: 16 },
            { wch: 15 },
            { wch: 24 },
            { wch: 22 },
            { wch: 22 },
            { wch: 40 },
            { wch: 20 },
            { wch: 28 },
            { wch: 25 },
            { wch: 22 },
            { wch: 45 }
        ];
        return ws;
    },

    /**
     * Sheet 4: Matriks Temuan
     */
    buildFindingsMatrixSheet: function(findingsData) {
        const rows = [];
        rows.push(["ID Risk", "Pemohon / Divisi", "Temuan Audit & Root Cause", "Tingkat Keparahan", "Rekomendasi Action Plan", "PIC & Target", "Status Aksi"]);

        findingsData.forEach(f => {
            rows.push([f.id, `${f.empName} (${f.dept})`, `${f.finding} - Root: ${f.rootCause}`, f.severity, f.actionPlan, `${f.pic} (${f.deadline})`, f.status]);
        });

        const ws = XLSX.utils.aoa_to_sheet(rows);
        ws['!cols'] = [{ wch: 14 }, { wch: 28 }, { wch: 45 }, { wch: 16 }, { wch: 40 }, { wch: 25 }, { wch: 16 }];
        return ws;
    },

    /**
     * Sheet 5: Blank Template PBOK & PPA
     */
    buildBlankPbokTemplateSheet: function() {
        const rows = [];
        rows.push(["FORMULIR INPUT AUDIT BELANJA PBOK & PPA (BLANK TEMPLATE)"]);
        rows.push([]);
        rows.push([
            "Kode Pengajuan (PBOK/PPA)", 
            "NIK Pemohon", 
            "Nama Pemohon", 
            "Divisi", 
            "Masa Menjabat", 
            "Item Belanja & Vendor", 
            "Nilai Pengajuan (Rp)", 
            "Keberadaan Fisik (Terverifikasi Ada/Fisik Tidak Ada/Indikasi Fiktif)", 
            "Status Kerugian (Aman / Wajar / Potensi Kerugian)", 
            "Nilai Potensi Kerugian (Rp)", 
            "Catatan Auditor"
        ]);

        rows.push(["PBOK-2026-000", "EMP-2026-000", "Nama Contoh", "Operations & Logistics", "2024 - 2026", "Contoh Pembelian Alat Kantor", 5000000, "Terverifikasi Ada", "Aman / Wajar", 0, "Catatan fisik barang lengkap."]);

        const ws = XLSX.utils.aoa_to_sheet(rows);
        ws['!cols'] = [{ wch: 22 }, { wch: 15 }, { wch: 22 }, { wch: 22 }, { wch: 20 }, { wch: 35 }, { wch: 20 }, { wch: 35 }, { wch: 30 }, { wch: 22 }, { wch: 35 }];
        return ws;
    }
};
