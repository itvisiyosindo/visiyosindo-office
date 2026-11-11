<?php
$isAsyncLoading = !empty($lb_async_mode);
$lbDashData     = (!$isAsyncLoading && isset($lb_dashboard) && is_array($lb_dashboard)) ? $lb_dashboard : [];
$lbTop3         = !empty($lbDashData['top_leaders']) ? $lbDashData['top_leaders'] : [];
$lbMyIds        = !empty($lbDashData['my_ids']) ? $lbDashData['my_ids'] : [(int)sessPenggunaId()];
$lbMyRank       = !empty($lbDashData['rank']) ? (int)$lbDashData['rank'] : 0;
$lbTotalRank    = !empty($lbDashData['total_ranked']) ? (int)$lbDashData['total_ranked'] : 0;
$lbMyRow        = isset($lbDashData['row']) ? $lbDashData['row'] : null;
$lbGapAbove     = isset($lbDashData['gap_to_above']) ? $lbDashData['gap_to_above'] : null;

$defaultSmt     = ((int)date('n') <= 6) ? 1 : 2;
$defaultYear    = (int)date('Y');
$defaultSemLbl  = ($defaultSmt === 1) ? "Semester 1 {$defaultYear}" : "Semester 2 {$defaultYear}";
$lbSemLabel     = !empty($lbDashData['sem_label']) ? (string)$lbDashData['sem_label'] : $defaultSemLbl;

$dashGetInitials = function($fullName) {
	$parts = preg_split('/\s+/', trim((string)$fullName));
	$ini = '';
	foreach ($parts as $pt) {
		if ($pt !== '') {
			$ini .= strtoupper(substr($pt, 0, 1));
			if (strlen($ini) >= 2) break;
		}
	}
	return $ini !== '' ? $ini : '?';
};
$dashAvatarGradients = [
	'linear-gradient(135deg, #2563eb, #06b6d4)',
	'linear-gradient(135deg, #7c3aed, #ec4899)',
	'linear-gradient(135deg, #059669, #10b981)',
	'linear-gradient(135deg, #ea580c, #f59e0b)',
	'linear-gradient(135deg, #4f46e5, #0ea5e9)',
	'linear-gradient(135deg, #db2777, #f43f5e)',
];
$dashMeterColors = [
	1 => 'linear-gradient(90deg, #f59e0b, #facc15)',
	2 => 'linear-gradient(90deg, #475569, #94a3b8)',
	3 => 'linear-gradient(90deg, #ea580c, #fb923c)',
];
$dashMedals = [1 => '🥇', 2 => '🥈', 3 => '🥉'];
?>
<div class="dash-lb-widget">
	<div class="dash-lb-head">
		<div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
			<span class="dash-lb-title-icon"><i class="fas fa-trophy"></i></span>
			<span class="font-weight-bold" style="color: #0f172a; font-size: 14.5px;">Top 3 Leaderboard KPI</span>
			<span class="dash-lb-live-badge"><span class="dash-lb-live-dot"></span> LIVE</span>
			<?php if ($lbSemLabel !== ''): ?>
				<span class="dash-lb-sem-tag"><?= htmlspecialchars($lbSemLabel) ?></span>
			<?php endif; ?>
			<?php if ($isAsyncLoading): ?>
				<span class="dash-lb-my-rank text-muted">
					<i class="fas fa-spinner fa-spin text-primary"></i> Memuat peringkat...
				</span>
			<?php elseif ($lbMyRank > 0 && $lbMyRow !== null): ?>
				<span class="dash-lb-my-rank">
					<i class="fas fa-bolt text-warning"></i>
					Rank Anda: <strong class="text-primary">#<?= $lbMyRank ?></strong>/<?= $lbTotalRank ?>
					&bull; <strong><?= number_format((float)$lbMyRow->skor_akhir, 1) ?></strong>
					<?php if ($lbMyRank === 1): ?>
						<span class="badge badge-success ml-1" style="font-size: 9.5px; border-radius: 999px;">Leader</span>
					<?php elseif ($lbGapAbove !== null): ?>
						<span style="color: #ea580c; font-weight: 700;">(-<?= number_format((float)$lbGapAbove, 1) ?> ke #<?= $lbMyRank - 1 ?>)</span>
					<?php endif; ?>
				</span>
			<?php endif; ?>
		</div>
		<a href="<?= base_url('task_proyek?tab=leaderboard') ?>" class="dash-lb-cta">
			Klasemen Lengkap <i class="fas fa-arrow-right" style="font-size: 10px;"></i>
		</a>
	</div>

	<div class="dash-lb-podium-body">
		<?php if ($isAsyncLoading): ?>
			<div class="row" style="row-gap: 10px;">
				<?php foreach ([1, 2, 3] as $skelPos): ?>
					<div class="col-md-4">
						<div class="dash-lb-item dash-lb-item-<?= $skelPos ?>">
							<div class="d-flex align-items-center" style="gap: 10px; flex: 1; min-width: 0;">
								<div class="dash-lb-skel dash-lb-skel-avatar"></div>
								<div style="flex: 1; min-width: 0;">
									<div class="dash-lb-skel dash-lb-skel-line mb-2" style="width: 72%;"></div>
									<div class="dash-lb-skel dash-lb-skel-line" style="width: 48%; height: 9px;"></div>
								</div>
							</div>
							<div class="dash-lb-skel dash-lb-skel-line" style="width: 38px; height: 22px;"></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php elseif (!empty($lbTop3)): ?>
			<div class="row" style="row-gap: 10px;">
				<?php foreach ($lbTop3 as $idx => $p):
					$pos = $idx + 1;
					if ($pos > 3) break;
					$avBg      = $dashAvatarGradients[abs((int)$p->pengguna_id) % count($dashAvatarGradients)];
					$isMe      = in_array((int)$p->pengguna_id, $lbMyIds, true);
					$pName     = (string)($p->nama ?? '');
					$pJabatan  = (string)($p->jabatan ?? '');
					$pGradeCol = (string)($p->grade_color ?? '#0f172a');
					$scorePct  = min(100, max(0, (float)$p->skor_akhir));
				?>
					<div class="col-md-4">
						<div class="dash-lb-item dash-lb-item-<?= $pos ?>">
							<div class="d-flex align-items-center" style="gap: 9px; min-width: 0;">
								<span class="dash-lb-rank-badge dash-lb-rank-<?= $pos ?>"><?= $dashMedals[$pos] ?> #<?= $pos ?></span>
								<div class="dash-lb-avatar" style="background: <?= $avBg ?>;">
									<?= $dashGetInitials($pName) ?>
								</div>
								<div style="min-width: 0;">
									<div class="font-weight-bold text-dark text-truncate" style="font-size: 13px;" title="<?= htmlspecialchars($pName) ?>">
										<?= htmlspecialchars($pName) ?><?php if ($isMe): ?> <span class="badge badge-primary" style="font-size: 9px; border-radius: 999px;">Saya</span><?php endif; ?>
									</div>
									<div class="text-muted text-truncate" style="font-size: 11px;"><?= htmlspecialchars($pJabatan !== '' ? $pJabatan : '-') ?></div>
								</div>
							</div>
							<div class="text-right flex-shrink-0">
								<div class="font-weight-bold" style="font-size: 19px; line-height: 1.05; color: <?= htmlspecialchars($pGradeCol) ?>;"><?= number_format((float)$p->skor_akhir, 1) ?></div>
								<small class="badge <?= htmlspecialchars((string)($p->grade_badge ?? 'badge-secondary')) ?> px-1" style="font-size: 9.5px; border-radius: 5px;">Grade <?= htmlspecialchars((string)($p->grade ?? '')) ?></small>
							</div>
							<div class="dash-lb-meter">
								<div class="dash-lb-meter-fill" style="width: <?= $scorePct ?>%; background: <?= $dashMeterColors[$pos] ?>;"></div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<div class="text-center py-2 text-muted" style="font-size: 12.5px;">
				Belum ada data peringkat Leaderboard KPI untuk periode ini.
			</div>
		<?php endif; ?>
	</div>
</div>
