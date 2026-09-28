<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $title_pdf; ?>
    </title>
    <style>
       .gaji-slip{
            border: 1px solid;
       }
       .salary-slip{
        margin: 15px;
       }
      .empDetail {
        width: 100%;
        text-align: left;
        table-layout: fixed;
      }
      
      .head {
        margin: 10px;
        margin-bottom: 50px;
        width: 100%;
      }
      
      .companyName {
        text-align: right;
        font-size: 25px;
        font-weight: bold;
      }
      
      .salaryMonth {
        text-align: center;
      }
      
      .table-border-bottom {
        border-bottom: 1px solid;
      }
      
      .table-border {
        border-top: 1px solid;
        border-bottom: 1px solid;
        padding: 10px;
      }
      .table-border-right {
        border-right: 1px solid;
        padding-right: 3%;
      }
      
      .myBackground {
        font-weight: normal;
        padding-top: 10px;
        text-align: left;
        border: 1px solid black;
        height: 40px;
      }
      
      .myAlign {
        text-align: center;
        border-right: 1px solid black;
      }
      
      .myTotalBackground {
        padding-top: 10px;
        text-align: left;
        background-color: #EBF1DE;
        border-spacing: 0px;
      }
      
      .align-4 {
        width: 25%;
        float: left;
      }
      
      .tail {
        margin-top: 35px;
      }
      
      .align-2 {
        margin-top: 25px;
        width: 50%;
        float: left;
      }
      
      .border-center {
        text-align: center;
      }
      .border-center th, .border-center td {
        border: 1px solid black;
      }
      
      th, td {
        padding-left: 6px;
      }
    </style>
</head>

<body>
  <div class="gaji-slip">
    <table>
        <tr>
          <th>
            <img src="assets/img/kop_surat_vym_underline.png" width="100%" height="30%" />
          </th>
        </tr>
        <tr>
          <th>
            <div style="text-align:center">
              <h2>
                <u><?= $title_pdf ?></u>
              </h2>
            </div>
          </th>
    </table>
    <div style="text-align:right; font-size: 10px;">
      <h2>
        <i>Periode : </i><?= $periode ?>
      </h2>
    </div>
    <table style="margin:2%">
        <tr>
          <th style="text-align: left;">Nama</th><td>:</td><td style="text-align: left;"><?= $dtsalary->nama?></td>
        </tr>
        <tr>
          <th style="text-align: left;">NPP</th><td>:</td><td style="text-align: left;"><?= "-"?></td>
        </tr>
        <tr>
          <th style="text-align: left;">Jabatan</th><td>:</td><td style="text-align: left;"><?= "-"?></td>
        </tr>
        <tr>
          <th style="text-align: left;">Status</th><td>:</td><td style="text-align: left;"><?= "-" ?></td>
        </tr>    
    </table>
    <table style="width: 100%; margin:2%">
        <thead>
          <tr>
            <th class="table-border-right"colspan="4" style="text-align: left;"><u>A.PENGHASILAN</u></th>
            <th style="text-align: left;"><u>B.POTONGAN</u></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th style="text-align: left;">1. Gaji Pokok</th><td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td><td class="table-border-right" style="text-align: right;"><?= ($dtsalary->gajipokok ?   rupiah($dtsalary->gajipokok) : '-')?></td>
            <th colsp style="text-align: left;">1. Pph Pasal 21</th><td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td>
            <td style="text-align: right;"><?= ($dtsalary->pph21 ?   rupiah($dtsalary->pph21) : '-')?>
            </td>
          </tr>
          <tr>
            <th style="text-align: left;">2. Tunjangan Konsumsi</th><td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td><td class="table-border-right" style="text-align: right; width: 25%"><?= ($dtsalary->tunjangankonsumsi ?  rupiah($dtsalary->tunjangankonsumsi) : '-')?></td>
            <th style="text-align: left;">2. BPJS Kesehatan</th><<td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td><td style="text-align: right; width: 25%"><?= ($dtsalary->bpjskesehatan ?  rupiah($dtsalary->bpjskesehatan) : '-')?></td>
          </tr>
          <tr>
            <th style="text-align: left;">3. Tunjangan Kinerja</th><td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td><td class="table-border-right" style="text-align: right;"><?= ($dtsalary->tunjangankinerja ?  rupiah($dtsalary->tunjangankinerja) : '-')?></td>
            <th style="text-align: left; width: 38%;">3. BPJS Ketenagakerjaan</th><td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td><td style="text-align: right;"><?= ($dtsalary->bpjstk ?  rupiah($dtsalary->bpjstk) : '-')?></td>
          </tr>
          <tr>
            <th style="text-align: left; width: 38%;">4. Tunjangan Komunikasi</th><td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td><td class="table-border-right" style="text-align: right;"><?= ($dtsalary->tunjangankomunikasi ?  rupiah($dtsalary->tunjangankomunikasi) : '-')?></td>
            <th style="text-align: left;">4. Potongan Lainnya</th><td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td><td style="text-align: right;"><?= $potonganlain ? $potonganlain : '-'; ?></td>
          </tr>  
           <tr>
            <th style="text-align: left;">5. Tunjangan Transport</th><td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td><td class="table-border-right" style="text-align: right;"><?= ($dtsalary->tunjangantransport ?  rupiah($dtsalary->tunjangantransport) : '-')?></td>
            <th style="text-align: left;"></th><td style="width: fit-content;"></td><td></td>
          </tr>
          <tr>
            <th style="text-align: left;">6. Tunjangan Jabatan</th><td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td><td class="table-border-right" style="text-align: right;"><?= ($dtsalary->tunjanganjabatan ?  rupiah($dtsalary->tunjanganjabatan) : '-')?></td>
            <th style="text-align: left;"></th><td style="width: fit-content;"></td><td></td>
          </tr>
          <tr>
            <th style="text-align: left;">7. Bonus</th><td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td><td class="table-border-right" style="text-align: right;"><?= ($dtsalary->bonus ?  rupiah($dtsalary->bonus) : '-')?></td>
            <th style="text-align: left;"></th><td style="width: fit-content;"></td><td></td>
          </tr>
          <tr>
            <th style="text-align: left;">8. Tunjangan Hari Raya (THR)</th><td style="width: fit-content;">=</td><td style="width: fit-content;">Rp.</td><td class="table-border-right" style="text-align: right;"><?= ($dtsalary->tunjanganraya ?  rupiah($dtsalary->tunjanganraya) : '-')?></td>
            <th style="text-align: left;"></th><td style="width: fit-content;"></td><td></td>
          </tr>
         <tr rowspan="2">
              <th class="table-border" colspan="1" style="text-align: left;">Total A</th><th class="table-border"></th><th class="table-border"  style="width: fit-content;">Rp.</th>
              <th class="table-border" colspan="1" style="text-align: right;  border-right: 1px solid;"><?= ($dtsalary->gajipokok+$dtsalary->tunjangankonsumsi+$dtsalary->tunjangankinerja+$dtsalary->tunjangankomunikasi+$dtsalary->tunjangantransport+$dtsalary->tunjanganjabatan+$dtsalary->bonus+$dtsalary->tunjanganraya) ?  rupiah(($dtsalary->gajipokok+$dtsalary->tunjangankonsumsi+$dtsalary->tunjangankinerja+$dtsalary->tunjangankomunikasi+$dtsalary->tunjangantransport+$dtsalary->tunjanganjabatan+$dtsalary->bonus+$dtsalary->tunjanganraya)) : '-' ?></th>
              <th class="table-border" colspan="2" style="text-align: left;">Total B</th><th class="table-border" style="width: fit-content;">Rp.</th>
              <th class="table-border" colspan="1" style="text-align: right;"><?= ($dtsalary->bpjskesehatan+$dtsalary->bpjstk+$dtsalary->pph21+$potonganlain) ?  rupiah(($dtsalary->bpjskesehatan+$dtsalary->bpjstk+$dtsalary->pph21+$potonganlain)) : '-' ?></th>
          </tr>
          <tr rowspan="1">
              <th class="table-border" colspan="6" style="text-align: left;">TAKE HOME PAY (A-B) </th><th class="table-border" style=" text-align: right; width: fit-content;">Rp.</th>
              <th class="table-border" colspan="4" style="text-align: right;"><?= (($dtsalary->gajipokok+$dtsalary->tunjangankonsumsi+$dtsalary->tunjangankinerja+$dtsalary->tunjangankomunikasi+$dtsalary->tunjangantransport+$dtsalary->tunjanganjabatan+$dtsalary->bonus+$dtsalary->tunjanganraya)-($dtsalary->bpjskesehatan+$dtsalary->bpjstk+$dtsalary->pph21+$potonganlain)) ?  rupiah((($dtsalary->gajipokok+$dtsalary->tunjangankonsumsi+$dtsalary->tunjangankinerja+$dtsalary->tunjangankomunikasi+$dtsalary->tunjangantransport+$dtsalary->tunjanganjabatan+$dtsalary->bonus+$dtsalary->tunjanganraya)-($dtsalary->bpjskesehatan+$dtsalary->bpjstk+$dtsalary->pph21+$potonganlain))) : '-' ?></th>
          </tr>
          <tr rowspan="1">
              <th colspan="5" style="text-align: left;">TERBILANG : </th>
          </tr>
          <tr rowspan="1">
              <td colspan="5" style="text-align: left;"><i><?= penyebut((($dtsalary->gajipokok+$dtsalary->tunjangankonsumsi+$dtsalary->tunjangankinerja+$dtsalary->tunjangankomunikasi+$dtsalary->tunjangantransport+$dtsalary->tunjanganjabatan+$dtsalary->bonus+$dtsalary->tunjanganraya)-($dtsalary->bpjskesehatan+$dtsalary->bpjstk+$dtsalary->pph21+$potonganlain))) ?></i></td>
          </tr>
         
        </tbody>
    </table>
  </div>
</body>
</html>