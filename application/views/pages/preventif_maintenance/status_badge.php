<?php
/**
 * Partial View: Status Badge untuk Preventif Maintenance
 * Menampilkan badge status dengan warna yang sesuai
 */
 
$status = isset($status) ? $status : 1;

$badges = [
    1 => ['color' => 'warning', 'label' => 'Open', 'icon' => 'fa-exclamation-circle'],
    2 => ['color' => 'info', 'label' => 'In Progress', 'icon' => 'fa-spinner'],
    3 => ['color' => 'success', 'label' => 'Closed', 'icon' => 'fa-check-circle'],
    4 => ['color' => 'danger', 'label' => 'Cancelled', 'icon' => 'fa-times-circle']
];

$badge = isset($badges[$status]) ? $badges[$status] : $badges[1];
?>

<span class="badge badge-<?= $badge['color'] ?>">
    <i class="fas <?= $badge['icon'] ?>"></i> <?= $badge['label'] ?>
</span>
