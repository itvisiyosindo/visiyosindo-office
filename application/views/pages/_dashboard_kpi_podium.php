<?php
$isAsyncLoading = !empty($lb_async_mode);
$lbDashData     = (!$isAsyncLoading && isset($lb_dashboard) && is_array($lb_dashboard)) ? $lb_dashboard : [];
$lbTop3         = !empty($lbDashData['top_leaders']) ? $lbDashData['top_leaders'] : [];
$lbMyIds        = !empty($lbDashData['my_ids']) ? $lbDashData['my_ids'] : [(int)sessPenggunaId()];
$lbMyRank       = !empty($lbDashData['rank']) ? (int)$lbDashData['rank'] : 0;
$lbTotalRank    = !empty($lbDashData['total_ranked']) ? (int)$lbDashData['total_ranked'] : 0;
$lbMyRow        = isset($lbDashData['row']) ? $lbDashData['row'] : null;
$lbGapAbove     = isset($lbDashData['gap_to_above']) ? $lbDashData['gap_to_above'] : null;
$lbAboveName    = !empty($lbDashData['above_name']) ? (string)$lbDashData['above_name'] : '';

$defaultSmt     = ((int)date('n') <= 6) ? 1 : 2;
$defaultYear    = (int)date('Y');
$defaultSemLbl  = ($defaultSmt === 1) ? "Semester 1 (Jan–Jun {$defaultYear})" : "Semester 2 (Jul–Des {$defaultYear})";
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
	'linear-gradient(135deg, #2563eb, #0ea5e9)',
	'linear-gradient(135deg, #7c3aed, #ec4899)',
	'linear-gradient(135deg, #059669, #10b981)',
	'linear-gradient(135deg, #ea580c, #f59e0b)',
	'linear-gradient(135deg, #4f46e5, #06b6d4)',
	'linear-gradient(135deg, #db2777, #f43f5e)',
];
$dashTopPins = [
	1 => ['icon' => '🥇', 'title' => 'JUARA #1', 'bg' => 'linear-gradient(135deg,#fef08a,#fde047)', 'fg' => '#713f12', 'bd' => '#eab308'],
	2 => ['icon' => '🥈', 'title' => 'RANK #2',  'bg' => 'linear-gradient(135deg,#f1f5f9,#cbd5e1)', 'fg' => '#1e293b', 'bd' => '#94a3b8'],
	3 => ['icon' => '🥉', 'title' => 'RANK #3',  'bg' => 'linear-gradient(135deg,#ffedd5,#fdba74)', 'fg' => '#7c2d12', 'bd' => '#f97316'],
];
?>
<div class="dash-lb-widget">
	<div class="dash-lb-hero">
		<div class="dash-lb-hero-inner">
			<div>
				<div class="d-flex align-items-center flex-wrap mb-2" style="gap: 8px;">
					<span class="dash-lb-live-pill">
						<span class="dash-lb-live-dot"></span> LIVE KLASEMEN TOP 3
					</span>
					<?php if ($lbSemLabel !== ''): ?>
						<span class="dash-lb-sem-pill">
							<i class="fas fa-bolt"></i> <?= htmlspecialchars(strtoupper($lbSemLabel)) ?>
						</span>
					<?php endif; ?>
				</div>
				<h4 class="font-weight-bold mb-1" style="color: #ffffff; font-size: 17.5px;">
					<i class="fas fa-trophy text-warning mr-2 dash-lb-trophy-anim"></i>Podium Leaderboard KPI Karyawan (Top 1, 2 &amp; 3)
				</h4>
				<div class="d-flex align-items-center flex-wrap mt-2" style="gap: 8px;">
					<?php if ($isAsyncLoading): ?>
						<span class="dash-lb-my-chip">
							<i class="fas fa-spinner fa-spin text-info"></i> Menghitung posisi peringkat KPI Anda...
						</span>
					<?php elseif ($lbMyRank > 0 && $lbMyRow !== null): ?>
						<span class="dash-lb-my-chip">
							<i class="fas fa-medal text-warning"></i>
							Posisi Anda: <strong>#<?= $lbMyRank ?></strong> / <?= $lbTotalRank ?> &bull; Skor <strong><?= number_format((float)$lbMyRow->skor_akhir, 1) ?></strong> (Grade <?= htmlspecialchars((string)($lbMyRow->grade ?? '')) ?>)
						</span>
						<?php if ($lbMyRank > 1 && $lbGapAbove !== null): ?>
							<span class="dash-lb-my-chip" style="background: rgba(249, 115, 22, 0.24); border-color: rgba(251, 146, 60, 0.45); color: #ffedd5;">
								<i class="fas fa-fire-alt text-warning"></i> Selisih <?= number_format((float)$lbGapAbove, 1) ?> poin ke #<?= $lbMyRank - 1 ?> (<?= htmlspecialchars($lbAboveName) ?>)
							</span>
						<?php elseif ($lbMyRank === 1): ?>
							<span class="dash-lb-my-chip" style="background: rgba(16, 185, 129, 0.24); border-color: rgba(52, 211, 153, 0.45); color: #d1fae5;">
								<i class="fas fa-crown text-warning"></i> Pimpinan Klasemen Saat Ini!
							</span>
						<?php endif; ?>
					<?php else: ?>
						<span class="dash-lb-my-chip">
							<i class="fas fa-chart-line text-info"></i> 80% Mutu Kinerja 6 Pilar + 20% Produktivitas
						</span>
					<?php endif; ?>
				</div>
			</div>
			<a href="<?= base_url('task_proyek?tab=leaderboard') ?>" class="dash-lb-cta">
				<i class="fas fa-list-ol"></i> Lihat Klasemen Lengkap
			</a>
		</div>
	</div>

	<div class="dash-lb-podium-body">
		<?php if ($isAsyncLoading): ?>
			<div class="row" style="row-gap: 14px;">
				<?php foreach ([2, 1, 3] as $skelIdx => $skelPos): ?>
					<div class="col-md-4">
						<div class="dash-lb-podium-card dash-lb-podium-<?= $skelPos ?>">
							<div class="dash-lb-skel dash-lb-skel-pill mb-3"></div>
							<div class="d-flex align-items-center" style="gap: 11px;">
								<div class="dash-lb-skel dash-lb-skel-avatar"></div>
								<div style="flex: 1;">
									<div class="dash-lb-skel dash-lb-skel-line mb-2" style="width: 75%;"></div>
									<div class="dash-lb-skel dash-lb-skel-line" style="width: 52%; height: 10px;"></div>
								</div>
							</div>
							<div class="dash-lb-skel dash-lb-skel-line mt-3" style="width: 100%; height: 6px;"></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php elseif (!empty($lbTop3)): ?>
			<div class="row" style="row-gap: 14px;">
				<?php
				$dashOrderCols = [1 => 'order-1 order-md-2', 2 => 'order-2 order-md-1', 3 => 'order-3 order-md-3'];
				$dashCardCls   = [1 => 'dash-lb-podium-1', 2 => 'dash-lb-podium-2', 3 => 'dash-lb-podium-3'];
				foreach ($lbTop3 as $idx => $p):
					$pos = $idx + 1;
					if ($pos > 3) break;
					$pin        = $dashTopPins[$pos];
					$avBg       = $dashAvatarGradients[abs((int)$p->pengguna_id) % count($dashAvatarGradients)];
					$isMe       = in_array((int)$p->pengguna_id, $lbMyIds, true);
					$starsRound = (int)round((float)$p->rating_agregat);
					$pName      = (string)($p->nama ?? '');
					$pJabatan   = (string)($p->jabatan ?? '');
					$pGradeCol  = (string)($p->grade_color ?? '#0f172a');
				?>
					<div class="col-md-4 <?= $dashOrderCols[$pos] ?>">
						<div class="dash-lb-podium-card <?= $dashCardCls[$pos] ?>">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="badge px-2 py-1 <?= $pos === 1 ? 'dash-lb-pin-1' : '' ?>" style="font-size:12px; background:<?= $pin['bg'] ?>; color:<?= $pin['fg'] ?>; border:1px solid <?= $pin['bd'] ?>; border-radius:20px; font-weight:800;">
									<?= $pin['icon'] ?> <?= $pin['title'] ?>
								</span>
								<div class="d-flex align-items-center" style="gap: 5px;">
									<?php if ((int)($p->sp_level ?? 0) > 0): ?>
										<span class="badge badge-danger px-2 py-1" style="font-size: 10px; border-radius: 999px;"><?= htmlspecialchars((string)($p->sp_label ?? '')) ?> Cap <?= (int)$p->sp_cap ?>%</span>
									<?php endif; ?>
									<span class="badge <?= htmlspecialchars((string)($p->grade_badge ?? 'badge-secondary')) ?> px-2 py-1" style="font-size: 10.5px; border-radius: 999px;">Grade <?= htmlspecialchars((string)($p->grade ?? '')) ?></span>
								</div>
							</div>

							<div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
								<div class="d-flex align-items-center" style="gap: 11px; min-width: 0;">
									<div class="dash-lb-avatar" style="background: <?= $avBg ?>;">
										<?= $dashGetInitials($pName) ?>
									</div>
									<div style="min-width: 0;">
										<div class="font-weight-bold text-dark text-truncate" style="font-size: 14.5px;" title="<?= htmlspecialchars($pName) ?>">
											<?= htmlspecialchars($pName) ?>
											<?php if ($isMe): ?><span class="badge badge-primary ml-1" style="font-size: 9.5px; border-radius: 999px;">Saya</span><?php endif; ?>
										</div>
										<div class="text-muted small text-truncate" style="font-size: 11.5px;"><?= htmlspecialchars($pJabatan !== '' ? $pJabatan : '-') ?></div>
										<div class="mt-1" style="font-size: 11.5px; color: #f59e0b; white-space: nowrap;">
											<?php for ($s = 1; $s <= 5; $s++): ?>
												<i class="<?= $s <= $starsRound ? 'fas' : 'far' ?> fa-star" style="<?= $s > $starsRound ? 'color:#cbd5e1;' : '' ?>"></i>
											<?php endfor; ?>
											<span class="font-weight-bold ml-1" style="color: #d97706;"><?= number_format((float)$p->rating_agregat, 2) ?></span>
										</div>
									</div>
								</div>
								<div class="text-right flex-shrink-0">
									<div class="font-weight-bold" style="font-size: 25px; line-height: 1; color: <?= htmlspecialchars($pGradeCol) ?>;"><?= number_format((float)$p->skor_akhir, 1) ?></div>
									<small class="text-muted" style="font-size: 10px;">Skor Akhir</small>
								</div>
							</div>

							<div class="mt-3 pt-2" style="border-top: 1px dashed rgba(148, 163, 184, 0.35);">
								<div class="d-flex justify-content-between small text-muted mb-1" style="font-size: 10.5px;">
									<span>Mutu: <strong class="text-dark"><?= number_format((float)$p->skor_mutu, 1) ?></strong></span>
									<span>Volume: <strong class="text-dark"><?= number_format((float)$p->skor_prod, 0) ?>%</strong></span>
								</div>
								<div style="height: 6px; border-radius: 999px; background: #e2e8f0; overflow: hidden;">
									<div style="height: 100%; width: <?= min(100, max(0, (float)$p->skor_akhir)) ?>%; background: linear-gradient(90deg, #0ea5e9, <?= htmlspecialchars($pGradeCol) ?>); border-radius: 999px;"></div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<div class="text-center py-4 text-muted" style="font-size: 13px;">
				<i class="fas fa-trophy fa-2x mb-2" style="color: #cbd5e1;"></i>
				<p class="mb-0">Belum ada data peringkat Leaderboard KPI untuk periode ini.</p>
			</div>
		<?php endif; ?>
	</div>
</div>
