<?php
//format currency
function mata_uang($kode)
{
    $mata_uang = "Rp";
    if ($kode == 1) {
        $mata_uang = "Rp";
    } else if ($kode == 2) {
        $mata_uang = "USD";
    } else if ($kode == 3) {
        $mata_uang = "MYR";
    } else if ($kode == 4) {
        $mata_uang = "SGD";
    } else if ($kode == 5) {
        $mata_uang = "INR";
    } else if ($kode == 6) {
        $mata_uang = "Euro";
    }
    return $mata_uang;
}

//format currency symbol
function mata_uang_simbol($kode)
{
    $simbol = "Rp";
    if ($kode == 1) {
        $simbol = "Rp";
    } else if ($kode == 2) {
        $simbol = "$";
    } else if ($kode == 3) {
        $simbol = "RM";
    } else if ($kode == 4) {
        $simbol = "$";
    } else if ($kode == 5) {
        $simbol = "₹";
    } else if ($kode == 6) {
        $simbol = "€";
    }
    return $simbol;
}

//format currency icon
function mata_uang_icon($kode)
{
    $icon = "<i class='fas fa-money-bill' style='color: #FF6B35;'></i> Rupiah";
    if ($kode == 1) {
        $icon = "<i class='fas fa-money-bill' style='color: #FF6B35;'></i> Rupiah";
    } else if ($kode == 2) {
        $icon = "<i class='fas fa-dollar-sign' style='color: #4CAF50;'></i> Dollar";
    } else if ($kode == 3) {
        $icon = "<i class='fas fa-money-bill' style='color: #FFC107;'></i> Ringgit";
    } else if ($kode == 4) {
        $icon = "<i class='fas fa-dollar-sign' style='color: #0066CC;'></i> SGD";
    } else if ($kode == 5) {
        $icon = "<i class='fas fa-rupee-sign' style='color: #FF9F00;'></i> Rupee";
    } else if ($kode == 6) {
        $icon = "<i class='fas fa-euro-sign' style='color: #003399;'></i> Euro";
    }
    return $icon;
}

//format nominal sesuai currency
function nominal($kode, $nom)
{
    if ($kode == 1) {
        $nominal = number_format($nom, 0, ",", ".");
        $nominal .= ",-";
    } else if ($kode == 2 || $kode == 4) {
        $nominal = number_format($nom, 2, ".", ",");
    } else if ($kode == 3) {
        $nominal = number_format($nom, 2, ".", ",");
    } else if ($kode == 5) {
        $nominal = number_format($nom, 2, ".", ",");
    } else if ($kode == 6) {
        $nominal = number_format($nom, 2, ".", ",");
    } else {
        $nominal = number_format($nom, 2, ".", ",");
    }
    return $nominal;
}
